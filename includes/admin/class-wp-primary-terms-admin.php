<?php
/**
 * WP Primary Category Primary Category Admin.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Primary Category Primary Category Admin.
 *
 * @since 1.0.0
 */
class WP_Primary_Terms_Admin {

	/**
     * Option key prefix
	 * @var string
	 */
	CONST KEY_PREFIX = '_wp_primary_';

	/**
	 * Return singleton instance of class
	 *
	 * @return object
	 * @since 1.0
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
	 * Initiate our hooks.
	 *
	 * @since  1.0.0
	 */
	public function setup() {
		add_action( 'admin_enqueue_scripts', array( $this, 'include_scripts' ) );
		add_action( 'admin_footer', array( $this, 'print_primary_category_templates' ), 20 );
		add_action( 'save_post', array( $this, 'save_primary_terms' ), 10, 1 );
	}

	/**
	 * Load admin page scripts
	 */
	public function include_scripts() {
		global $pagenow, $post;

		// Don't include scripts unless add/edit post
		if ( 'post-new.php' != $pagenow && 'post.php' != $pagenow ) {
			return;
		}

		$taxonomies = wppt_get_primary_taxonomies();

		// If we have no taxonomies with primary support, there is no need to continue
		if ( empty( $taxonomies ) ) {
			return;
		}

		wp_enqueue_script( 'wp-primary-category-admin', WPPT_URL . 'assets/dist/js/admin.js', array(), WPPT_VERSION );
		wp_enqueue_style( 'wp-primary-category-admin', WPPT_URL . 'assets/dist/css/admin.css', array(), WPPT_VERSION );

		$tax_data = array();

		foreach ( $taxonomies as $taxonomy ) {
            $tax_data[] = array(
                    'name' => $taxonomy,
                    'termID' => get_post_meta( $post->ID, self::KEY_PREFIX . $taxonomy ),
            );
		}

		wp_localize_script( 'wp-primary-category-admin', 'wptPrimaryTaxonomies', $tax_data );
	}

	/**
	 * Print an Underscore template for Set/Rest Primary Term Button
     * and Hidden input to store a primary term id.
     *
	 * @since 1.0
     * @return void
	 */
	public function print_primary_category_templates() {
	    ?>
        <script type="text/html" id="tmpl-wpt-primary-term-button">
            <span class="primary-term-button">
                <a class="toggle-primary-term">{{ data.isPrimary ? '<?php esc_html_e( 'Reset Primary', 'wp-primary-terms' ) ?>' : '<?php esc_html_e( 'Set Primary', 'wp-primary-terms' ) ?>' }}</a>
                <# if ( data.isPrimary ) { #>
                 <label><?php esc_html_e( 'Primary', 'wp-primary-terms' ) ?></label>
                <# } #>
            </span>
		</script>

        <script type="text/html" id="tmpl-wpt-primary-term-input">
            <input type="hidden" id="<?php echo self::KEY_PREFIX ?>{{data.name}}" name="<?php echo self::KEY_PREFIX ?>{{data.name}}" value="{{data.termID}}" />
        </script>
		<?php
	}

	/**
	 * Saves primary terms ids in the post meta
     *
     * @since 1.0
     * @return void
	 */
	public function save_primary_terms( $post_id ) {

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) || 'revision' === get_post_type( $post_id ) ) {
			return;
		}

		$taxonomies = wppt_get_primary_taxonomies();

		// Update primary term value for each taxonomy
		foreach ( $taxonomies as $taxonomy ) {
	        $meta_key = self::KEY_PREFIX . $taxonomy;
            update_post_meta( $post_id, $meta_key, $_POST[ $meta_key ] );
        }
    }

}
