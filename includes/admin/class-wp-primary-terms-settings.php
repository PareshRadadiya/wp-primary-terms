<?php
/**
 * Adds admin settings page
 *
 * @class       WC_Admin_Permalink_Settings
 * @package     WP_Primary_Terms/Admin
 * @version     1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP_Primary_Terms_Settings class.
 *
 * @since 1.0.0
 */
class WP_Primary_Terms_Settings {

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	CONST OPTION_KEY = 'wp_primary_terms_settings';

	/**
	 * Settings notice slug
     *
     * @var string
     * @since 1.0.0
	 */
	CONST NOTICE_KEY = self::OPTION_KEY.'-notices';

	/**
     * Setting page title
	 * @var
	 */
	public $title;

	/**
	 * Return singleton instance of class
	 *
	 * @return object
	 * @since 1.0.0
	 */
	public static function get_instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self();
			$instance->setup();
		}
		return $instance;
	}

	/**
	 * Handles registering hooks that initialize this plugin settings.
	 *
	 * @since  1.0.0
	 */
	public function setup() {
		// Hook in our actions to the admin.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
        add_action( 'admin_notice', array( $this, '' ) );
		// Set our title.
		$this->title = esc_attr__( 'WP Primary Terms Settings', 'wp-primary-category' );
	}

	/**
	 * Initialise settings sections, settings fields
	 *
	 * @since  1.0.0
	 */
	public function register_settings() {
	    // Creates our settings in the options table
		register_setting(
		        self::OPTION_KEY,
                self::OPTION_KEY,
                array( 'sanitize_callback' => array( $this, 'settings_sanitize' ) )
        );

		// Create settings section
		add_settings_section( 'wppt_general_section', null, null,  self::OPTION_KEY );

		// Create settings fieldS
		add_settings_field(
		        'wp_primary_terms_taxonomy',
                __( 'Taxonomies', 'wp-primary-terms' ),
                array( $this, 'taxonomies_checkbox_callback' ),
                self::OPTION_KEY,
                'wppt_general_section'
		);
	}

	/**
	 * Settings Sanitization
	 *
	 * Adds a settings error (for the updated message)
	 *
	 * @since 1.0.0
	 *
	 * @param array $input The value inputted in the field
	 *
	 * @return array $input Sanitized value
	 */
	public function settings_sanitize( $input = array() ) {
		if ( isset( $_POST['_wp_http_referer'] ) ) {
			add_settings_error( self::NOTICE_KEY, '', __( 'Settings updated.', 'wp-primary-terms' ), 'updated' );
		}
		return $input;
	}

	/**
	 * Taxonomies list Callback
	 *
	 * Renders registered taxonomies checkbox list
	 *
	 * @since 1.0.0
	 * @param array $args Arguments passed by the setting
	 * @return void
	 */
	public function taxonomies_checkbox_callback( $args ) {

        $taxonomies = get_taxonomies( array( 'hierarchical' => true ), 'objects' );

		$settings = $this->get_settings(); ?>

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
		add_menu_page(
			$this->title,
			__( 'Primary Terms', 'wp-primary-terms' ),
			'manage_options',
			self::OPTION_KEY,
			array( $this, 'settings_screen' )
		);
	}

	/**
	 * The markup for the settings screen.
	 *
	 * @since  1.0.0
	 */
	public function settings_screen() {

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;

		} ?>
		<div class="wrap options-page <?php echo esc_attr( self::OPTION_KEY ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <?php settings_errors( self::NOTICE_KEY ); ?>

            <form action="options.php" method="post">
				<?php
				//  Renders the options page contents.
				settings_fields( self::OPTION_KEY  );

				do_settings_sections( self::OPTION_KEY  );

				// output save settings button
				submit_button( 'Save Settings' );
				?>
            </form>
		</div><!-- .wrap -->
		<?php
	}

	/**
	 * Retrieve the array of plugin settings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_settings() {
		$settings = get_option( self::OPTION_KEY );

		// Looks to see if the specified setting exists, save default if not
		if ( false === $settings ) {
			$settings = array( 'category' );
		    update_option( self::OPTION_KEY, $settings );
        }

		return apply_filters( 'wppt_get_settings', (array) $settings );
    }

}
