<?php
/**
 * Main Class.
 *
 * @class    WP_Primary_Terms
 * @package  WP_Primary_Terms
 * @version  1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main initiation class.
 *
 * @since  1.0.0
 */
final class WP_Primary_Terms {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object   $instance   A single instance of this class.
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   1.0.0
	 * @return  WP_Primary_Terms instance of this class.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  1.0.0
	 */
	public function setup() {
		$this->includes();
	}

	/**
	 * Include required core files used in admin.
	 */
	public function includes() {
		include_once WPPT_ABSPATH . 'includes/functions.php';
		include_once WPPT_ABSPATH . 'includes/admin/class-wp-primary-terms-settings.php';
		include_once WPPT_ABSPATH . 'includes/admin/class-wp-primary-terms-admin.php';
	}

	/**
	 * Init plugin when WordPress Initialises.
	 *
	 * @since  1.0.
	 */
	public function init() {

		// Before init action.
		do_action( 'before_wp_primary_terms_init' );

		// Load translated strings for plugin.
		load_plugin_textdomain( 'wp-primary-terms', false, WPPT_BASENAME . '/languages/' );

		// Initialize plugin classes.
		WP_Primary_Terms_Admin::get_instance();
		WP_Primary_Terms_Settings::get_instance();

		// After init action.
		do_action( 'after_wp_primary_terms_init' );
	}
}
