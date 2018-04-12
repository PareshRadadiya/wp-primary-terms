<?php
/**
 * Helpers
 *
 * General core functions available on both the front-end and admin.
 *
 * @package WP_Primary_Terms/Functions
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Helper to get all taxonomies of a post for which primary option has enabled
 *
 * @since 1.0.0
 * @return array
 */
function wppt_get_primary_taxonomies( $post = null ) {

	if ( ! is_a( $post, 'WP_Post' ) ) {
		$post = get_post();
	}

	$post_type = get_post_type( $post );
	$taxonomies = get_object_taxonomies( $post_type );
	$settings = WP_Primary_Terms_Settings::get_instance()->get_settings();
	$primary_taxonomies = array();

	// Setup primary taxonomies array that are enabled in the settings.
	foreach ( $taxonomies as $taxonomy ) {
		if ( in_array( $taxonomy, $settings ) && is_taxonomy_hierarchical( $taxonomy ) ) {
			$primary_taxonomies[] = $taxonomy;
		}
	}

	return apply_filters( 'wppc_get_primary_term_taxonomy', $primary_taxonomies, $post_type );
}
