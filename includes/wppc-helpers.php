<?php

/**
 * Get all taxonomies of a post
 * @return array
 */
function wppc_get_primary_term_taxonomies( $post = null ) {

	if ( ! is_a( $post, 'WP_Post' ) ) {
		$post = get_post();
	}

	$post_type = get_post_type( $post );
	$taxonomies = get_object_taxonomies( $post_type );
	$settings = wppc()->primary_terms_settings->get_settings();
	$primary_taxonomies = array();

	foreach ( $taxonomies as $taxonomy ) {

		if ( in_array( $taxonomy, $settings ) ) {
			$primary_taxonomies[] = $taxonomy;
		}
	}

	return apply_filters( 'wppc_get_primary_term_taxonomy', $primary_taxonomies, $post_type );
}

/**
 *
 * @param $tax_name
 * @return mixed|void
 */
function wppt_is_primary_taxonomy( $tax_name ) {
	 $settings = wppc()->primary_terms_settings->get_settings();
	 return apply_filters( 'wppt_is_primary_taxonomy', in_array( $tax_name, $settings ) );
}