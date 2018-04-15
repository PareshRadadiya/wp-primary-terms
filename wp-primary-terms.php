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

// Useful global constants
define( 'WPPT_ABSPATH', plugin_dir_path( __FILE__ ) );
define( 'WPPT_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPPT_URL', plugin_dir_url( __FILE__ ) );
define( 'WPPT_VERSION', '1.0.0' );

// Include files
require_once WPPT_ABSPATH . 'includes/class-wp-primary-terms.php';

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
