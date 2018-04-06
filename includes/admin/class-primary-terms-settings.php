<?php
/**
 * WP Primary Category Primary Terms Settings.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */

/**
 * WP Primary Category Primary Terms Settings class.
 *
 * @since 1.0.0
 */
class WPPC_Primary_Terms_Settings {

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected static $key = 'wp_primary_terms_settings';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected static $metabox_id = 'wppt_primary_terms_settings_metabox';

	/**
	 * Options Page title.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $title = '';

	/**
	 * Options Page hook.
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_Primary_Category $plugin Main plugin object.
	 */
	public function __construct() {
		$this->hooks();

		// Set our title.
		$this->title = esc_attr__( 'WP Primary Terms Settings', 'wp-primary-category' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  1.0.0
	 */
	public function hooks() {

		// Hook in our actions to the admin.

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * Register our setting to WP.
	 *
	 * @since  1.0.0
	 */
	public function admin_init() {
		register_setting( self::$key, self::$key );

		// register a new section in the "wporg" page
		add_settings_section( 'wppt_general_section', null, null,  self::$key );

		// register a new field in the "wporg_section_developers" section, inside the "wporg" page
		add_settings_field(
			'wp_primary_terms_taxonomy', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__( 'Taxonomies', 'wporg' ),
			array( $this, 'wp_primary_terms_taxonomy_cb' ),
			self::$key,
			'wppt_general_section'
		);
	}

	/**
	 * custom option and settings:
	 * callback functions
	 */


// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
	public function wp_primary_terms_taxonomy_cb( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'wporg_options' );
		// output the field

        $taxonomies = get_taxonomies( array( 'hierarchical' => true ), 'objects' );

		$settings = $this->get_settings();

		?>

        <?php foreach ( $taxonomies as $taxonomy ):
            $tax_name =  $taxonomy->name;
            ?>
            <p class="taxonomy-<?php $tax_name ?>">
                <input type="checkbox" name="wp_primary_terms_settings[]" id="<?php echo $tax_name ?>" value="<?php echo $tax_name ?>" <?php checked( in_array( $tax_name, $settings ) ) ?>>
                <label for="<?php echo $tax_name ?>"><?php echo $taxonomy->label ?></label>
            </p>
        <?php endforeach; ?>

        <p class="description">
			<?php esc_html_e( 'Toggle primary term support for the taxonomies.', 'wporg' ); ?>
        </p>
		<?php
	}

	/**
	 * Add menu options page.
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			self::$key,
			array( $this, 'admin_page_display' )
		);
	}

	/**
	 * Admin page markup. Mostly handled by CMB2.
	 *
	 * @since  1.0.0
	 */
	public function admin_page_display() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<div class="wrap options-page <?php echo esc_attr( self::$key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wporg"
				settings_fields( self::$key  );
				// output setting sections and their fields
				// (sections are registered for "wporg", each field is registered to a specific section)
				do_settings_sections( self::$key  );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
            </form>
		</div>
		<?php
	}

	/**
     *
	 * @return array
	 */
	public function get_settings() {
		$settings = get_option( self::$key );

		if ( false === $settings ) {
			$settings = array( 'category' );
		    update_option( self::$key, $settings );
        }

		return apply_filters( 'wppt_get_settings', (array) $settings );
    }
}
