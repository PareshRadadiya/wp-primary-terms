<?php
/**
 * Admin Class.
 *
 * @class    WP_Primary_Terms_Admin
 * @package  WP_Primary_Terms/Admin
 * @version  1.0.0
 */
namespace TenUp\WpPrimaryTerms\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * WP_Primary_Terms_Admin class
 *
 * @since 1.0.0
 */
class WP_Primary_Terms_Admin {

	/**
	 *  Option key prefix
	 * @var string
	 */
	const KEY_PREFIX = '_wp_primary_';

	/**
	 * Return singleton instance of class
	 *
	 * @since 1.0.0
	 * @return object
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
		add_action( 'admin_footer', array( $this, 'print_primary_terms_templates' ), 20 );
		add_action( 'save_post', array( $this, 'save_primary_terms' ), 10, 2 );
	}

	/**
	 * Load script and style files and localized variables
	 *
	 * @since 1.0.0
	 */
	public function include_scripts() {
		global $post;

		$is_primary_tax_post = $this->is_primary_taxonomies_post_page();

		// If we have no taxonomies with primary support is current post edit page,
		// there is no need to continue
		if ( false === $is_primary_tax_post ) {
			return;
		}

		// RTL CSS
		$rtl = is_rtl() ? '.rtl' : '';

		// Enqueue admin scripts and styles
		wp_enqueue_script(
			'wp-primary-terms-admin-script',
			WP_PRIMARY_TERMS_URL . 'assets/dist/js/admin.min.js',
			array(),
			WP_PRIMARY_TERMS_VERSION,
			true
		);

		wp_enqueue_style(
			'wp-primary-terms-admin-style',
			WP_PRIMARY_TERMS_URL . 'assets/dist/css/admin' . $rtl . '.css',
			array(),
			WP_PRIMARY_TERMS_VERSION
		);

		// Get primary taxonomies
		$taxonomies = self::get_primary_taxonomies();
		$tax_data   = array();

		// Loop through all of primary taxonomies and prepare taxonomies data array.
		foreach ( $taxonomies as $taxonomy ) {
			$tax_data[] = array(
				'name'   => $taxonomy,
				'termID' => get_post_meta( $post->ID, self::KEY_PREFIX . $taxonomy ),
			);
		}

		wp_localize_script( 'wp-primary-terms-admin-script', 'wpPrimaryTermsVars', $tax_data );
	}

	/**
	 * Render javascript templates
	 *
	 * @since 1.0.0
	 */
	public function print_primary_terms_templates() {

		$is_primary_tax_post = $this->is_primary_taxonomies_post_page();

		// If we have no taxonomies with primary support is current post edit page,
		// there is no need to continue
		if ( false === $is_primary_tax_post ) {
			return;
		}

		$this->print_toggle_primary_term_button_template();
		$this->print_primary_term_input_template();
	}

	/**
	 * Save primary terms id in post meta when the save_post action is called
	 *
	 * @since 1.0.0
	 * @param $post_id
	 * @param $post
	 */
	public function save_primary_terms( $post_id, $post ) {

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		if ( isset( $post->post_type ) && 'revision' === $post->post_type ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$taxonomies = self::get_primary_taxonomies();

		// Update primary term value for each taxonomy
		foreach ( $taxonomies as $taxonomy ) {
			$this->save_primary_term( $post_id, $taxonomy );
		}
	}

	/**
	 * Print an Underscore template to render Set/Rest Primary Term Button
	 *
	 * @since 1.0.0
	 */
	public function print_toggle_primary_term_button_template() {
		?>
		<script type="text/html" id="tmpl-wp-primary-term-button">
			<span class="primary-term-button">
				<a class="toggle-primary-term">
					{{ data.isPrimary ?
					'<?php esc_html_e( 'Reset Primary', 'wp-primary-terms' ); ?>'
					:'<?php esc_html_e( 'Set Primary', 'wp-primary-terms' ); ?>' }}
				</a>
				<# if ( data.isPrimary ) { #>
					<label><?php esc_html_e( 'Primary', 'wp-primary-terms' ); ?></label>
				<# } #>
			</span>
		</script>
		<?php
	}

	/**
	 * Print an Underscore template to render hidden input to store a primary term id
	 * and nonce field.
	 *
	 * @since 1.0.0
	 */
	public function print_primary_term_input_template() {
		$taxonomies = self::get_primary_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			?>
			<script type="text/html" id="tmpl-wp-primary-<?php echo esc_attr( $taxonomy ); ?>-input">
				<input
					type="hidden"
					id="<?php echo esc_attr( self::KEY_PREFIX . $taxonomy ); ?>"
					name="<?php echo esc_attr( self::KEY_PREFIX . $taxonomy ); ?>"
					value="{{data.termID}}"
				/>
				<?php wp_nonce_field( 'wp-save-primary-' . $taxonomy, 'wp_primary_' . $taxonomy . '_nonce' ); ?>
			</script>
			<?php
		}
	}

	/**
	 * Save primary term
	 *
	 * @since 1.0.0
	 * @param $post_id Post Id
	 * @param $taxonomy Taxonomy name
	 */
	public function save_primary_term( $post_id, $taxonomy ) {
		$meta_key = self::KEY_PREFIX . $taxonomy;

		if ( ! isset( $_POST[ 'wp_primary_' . $taxonomy . '_nonce' ] )
			|| ! wp_verify_nonce( $_POST[ 'wp_primary_' . $taxonomy . '_nonce' ], 'wp-save-primary-' . $taxonomy )
		) {
			return;
		}

		$term_id = filter_input( INPUT_POST, $meta_key, FILTER_VALIDATE_INT );

		if ( $term_id ) {
			update_post_meta( $post_id, $meta_key, $term_id );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	/**
	 * Determines whether a current page is a post page and has primary taxonomies
	 *
	 * @return bool
	 */
	public function is_primary_taxonomies_post_page() {
		global $pagenow;

		// Don't include scripts unless add/edit post
		if ( 'post-new.php' !== $pagenow && 'post.php' !== $pagenow ) {
			return false;
		}

		// Get primary taxonomies
		$taxonomies = self::get_primary_taxonomies();

		if ( empty( $taxonomies ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get all primary taxonomies
	 *
	 * @since 1.0.0
	 * @return array|bool
	 */
	public static function get_primary_taxonomies() {
		static $taxonomies = false;
		if ( false === $taxonomies ) {
			$taxonomies = \TenUp\WpPrimaryTerms\Functions\get_primary_taxonomies();
		}
		return $taxonomies;
	}
}
