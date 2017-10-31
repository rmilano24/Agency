<?php

/**
 * Display the output for the page content.
 *
 * @since 1.0.0
 */
function agncy_page_content_output() {
	$template = 'templates/page-content/content';

	if ( is_home() ) {
		$template = 'templates/page-content/content-loop';
	}
	elseif ( is_search() ) {
		$template = 'templates/page-content/content-loop-search';
	}
	elseif ( is_archive() ) {
		$template = 'templates/page-content/archive';
	}
	elseif ( is_attachment() ) {
		$template = 'templates/page-content/attachment';
	}

	get_template_part( apply_filters( 'agncy_page_content_output', $template ) );
}

/* Hooking the page content. */
add_action( 'agncy_page_content', 'agncy_page_content_output' );

/* Hooking the page header before the page content. */
add_action( 'agncy_page_content', 'agncy_page_header', 5 );