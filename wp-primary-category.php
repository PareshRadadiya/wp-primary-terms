<?php
/**
 * Plugin Name: WP Primary Category
 * Plugin URI:  http://pareshradadiya.github.io/wp-primary-category
 * Description: Allow to designate a primary category for posts and custom post types
 * Version:     1.0.0
 * Author:      Paresh
 * Author URI:  https://pareshradadiya.github.io
 * Donate link: http://pareshradadiya.github.io/wp-primary-category
 * License:     GPLv2
 * Text Domain: wp-primary-category
 * Domain Path: /languages
 *
 * @link    http://pareshradadiya.github.io/wp-primary-category
 *
 * @package WP_Primary_Category
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
final class WP_Primary_Category {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    WP_Primary_Category
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of WPPC_Admin
	 *
	 * @since1.0.0
	 * @var WPPC_Admin
	 */
	protected $admin;

	/**
	 * Instance of WPPC_Primary_Category_Admin
	 *
	 * @since1.0.0
	 * @var WPPC_Primary_Category_Admin
	 */
	protected $primary_category_admin;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   1.0.0
	 * @return  WP_Primary_Category A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->define_constants();
		$this->includes();
	}

	public function define_constants() {
		define( 'WPPC_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WPPC_URL', plugin_dir_url( __FILE__ ) );
		define( 'WPPC_PATH', plugin_dir_PATH( __FILE__ ) );
		define( 'WPPC_VERSION', self::VERSION );
	}

	public function includes() {
	    include_once WPPC_PATH . 'includes/wppc-helpers.php';
	    include_once WPPC_PATH . 'includes/admin/class-primary-category-admin.php';
    }

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  1.0.0
	 */
	public function plugin_classes() {
	    $this->primary_category_admin = new WPPC_Primary_Category_Admin( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Init hooks
	 *
	 * @since  1.0.0
	 */
	public function init() {

		// Load translated strings for plugin.
		load_plugin_textdomain( 'wp-primary-category', false, WPPC_BASENAME . '/languages/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $field Field to get.
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
}

/**
 * Grab the WP_Primary_Category object and return it.
 * Wrapper for WP_Primary_Category::get_instance().
 *
 * @since  1.0.0
 * @return WP_Primary_Category  Singleton instance of plugin class.
 */
function wppc() {
	return WP_Primary_Category::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wppc(), 'hooks' ) );
