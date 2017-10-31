<?php

/**
 * Display the site page content according to the page context.
 *
 * @since 1.0.0
 */
function agncy_page_content() {
	$key = 'agncy_page_content';

	if ( ! function_exists( 'ev_do_action' ) ) {
		do_action( "${key}_before" );
		do_action( $key );
		do_action( "${key}_after" );

		return;
	}

	ev_do_action( "${key}_before" );

		/* Actual page content. */
		ev_do_action( $key );

	ev_do_action( "${key}_after" );
}