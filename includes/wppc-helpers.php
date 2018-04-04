<?php

/**
 * Get all taxonomies of a post
 * @return bool|mixed|void
 */
function wppc_get_primary_term_taxonomies( $post = null ) {

	if ( ! is_a( $post, 'WP_Post' ) ) {
		$post = get_post();
	}

	$post_type = get_post_type( $post );
	$taxonomies = get_object_taxonomies( $post_type );
	return apply_filters( 'wppc_get_primary_term_taxonomy', $taxonomies, $post_type );
}