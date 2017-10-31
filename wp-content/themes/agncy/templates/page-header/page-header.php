<?php

/**
 * Get the template part relative to the page header.
 *
 * @since 1.0.0
 */
function agncy_page_header_default() {
	$post_type = get_post_type();

	$template = 'templates/page-header/types/page-header-default';
	$template = apply_filters( 'agncy_page_header_template', $template );
	$template = apply_filters( "agncy_page_header_template[post_type:$post_type]", $template );

	get_template_part( $template );
}

add_action( 'agncy_page_header', 'agncy_page_header_default' );