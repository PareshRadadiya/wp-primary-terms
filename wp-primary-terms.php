<?php
/**
 * Plugin Name: WP Primary Terms
 * Plugin URI:  http://pareshradadiya.github.io/wp-primary-terms
 * Description: Allow to designate a primary category for posts and custom post types
 * Version:     1.0.0
 * Author:      Paresh
 * Author URI:  https://pareshradadiya.github.io
 * Donate link: http://pareshradadiya.github.io/wp-primary-terms
 * License:     GPLv2
 * Text Domain: wp-primary-terms
 * Domain Path: /languages
 *
 * @link    http://pareshradadiya.github.io/wp-primary-terms
 *
 * @package WP_Primary_Terms
 * @version 1.0.0
 *
 * Built using generator-plugin-wp (https://github.com/WebDevStudios/generator-plugin-wp)
 */

/**
 * Copyright (c) 2018 Paresh (email : pareshpravin@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Include additional php files here.
// require 'includes/something.php';

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
	 * Current version.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

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
		$this->define_constants();
		$this->includes();
	}

	/**
	 * Define plugin constants.
	 */
	public function define_constants() {
		define( 'WPPT_ABSPATH', plugin_dir_path( __FILE__ ) );
		define( 'WPPT_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WPPT_URL', plugin_dir_url( __FILE__ ) );
		define( 'WPPT_VERSION', self::VERSION );
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

/**
 * Grab the WP_Primary_Terms object and return it.
 * Wrapper for WP_Primary_Terms::get_instance().
 *
 * @since  1.0.0
 * @return WP_Primary_Terms instance of plugin class.
 */
function wppt() {
	return WP_Primary_Terms::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wppt(), 'init' ) );
