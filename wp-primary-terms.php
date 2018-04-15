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

// Useful global constants
define( 'WP_PRIMARY_TERMS_VERSION', '1.0.0' );
define( 'WP_PRIMARY_TERMS_URL',     plugin_dir_url( __FILE__ ) );
define( 'WP_PRIMARY_TERMS_PATH',    dirname( __FILE__ ) . '/' );
define( 'WP_PRIMARY_TERMS_INC',     WP_PRIMARY_TERMS_PATH . 'includes/' );

// Include files
require_once WP_PRIMARY_TERMS_INC . '/classes/class-wp-primary-terms.php';

/**
 * Grab the WP_Primary_Terms object and return it.
 * Wrapper for WP_Primary_Terms::get_instance().
 *
 * @since  1.0.0
 * @return WP_Primary_Terms instance of plugin class.
 */
function wp_primary_terms() {
	return \TenUp\WpPrimaryTerms\Core\WP_Primary_Terms::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wp_primary_terms(), 'init' ) );
