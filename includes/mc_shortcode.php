<?php
/**
 * Add ShortCode for mc-citation custom field
 */
function create_shortcode($atts){
	global $post;
	$atts = shortcode_atts(array('post_id' => NULL), $atts);

	// Get ID of post as params or current post
	$post_id = $atts['post_id'] ?? $post->ID;

	// return value
	return get_post_meta($post_id, 'mc-citation', true);
}