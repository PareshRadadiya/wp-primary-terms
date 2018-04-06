<?php
/**
 * WP Primary Category Primary Category Admin.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */

/**
 * WP Primary Category Primary Category Admin.
 *
 * @since 1.0.0
 */
class WPPC_Primary_Category_Admin {
	/**
	 * Parent plugin class.
	 *
	 * @since 1.0.0
	 *
	 * @var   WP_Primary_Category
	 */
	protected $plugin = null;

	public static $key_prefix = '_wp_primary_';

	public $taxonomies = array();

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_Primary_Category $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'include_scripts' ) );
		add_action( 'admin_footer', array( $this, 'print_primary_category_templates' ), 20 );
		add_action( 'save_post', array( $this, 'save_primary_term' ), 10, 1 );
	}

	/**
	 * Load admin page scripts
	 */
	public function include_scripts() {
		global $pagenow, $post;

		if ( 'post-new.php' != $pagenow && 'post.php' != $pagenow ) {
			return;
		}

		$taxonomies = wppc_get_primary_term_taxonomies();

		if ( empty( $taxonomies ) ) {
			return;
		}

		wp_enqueue_script( 'wp-primary-category-admin', WPPC_URL . 'assets/js/wp-primary-category.js', array(), WPPC_VERSION );

		wp_enqueue_style( 'wp-primary-category-admin', WPPC_URL . 'assets/css/styles.css', array(), WPPC_VERSION );

		$tax_data = array();

		foreach ( $taxonomies as $taxonomy ) {
            $tax_data[] = array(
                    'name' => $taxonomy,
                    'termID' => get_post_meta( $post->ID, self::$key_prefix . $taxonomy ),
            );
		}

		wp_localize_script( 'wp-primary-category-admin', 'wptPrimaryTaxonomies', $tax_data );
	}

	public function print_primary_category_templates() {
	    ?>
        <script type="text/html" id="tmpl-wpt-primary-term-button">
            <span class="primary-term">
                <a class="toggle-primary-term">{{ data.isPrimary ? '<?php _e( 'Reset Primary' ) ?>' : '<?php _e( 'Set Primary' ) ?>' }}</a>
            </span>
		</script>
        <script type="text/html" id="tmpl-wpt-primary-term-input">
            <input type="hidden" id="<?php echo self::$key_prefix ?>{{data.name}}" name="<?php echo self::$key_prefix ?>{{data.name}}" value="{{data.termID}}" />
        </script>
		<?php
	}

	/**
	 *
	 */
	public function save_primary_term( $post_ID ) {
		$taxonomies = wppc_get_primary_term_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
	        $meta_key = self::$key_prefix . $taxonomy;
            update_post_meta( $post_ID, $meta_key, $_POST[ $meta_key ] );
        }
    }


}
