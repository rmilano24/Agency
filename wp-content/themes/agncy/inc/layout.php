<?php

/**
 * Output a series of CSS classes to be applied to the main layout wrapper
 * element.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_layout_class() {
	$classes = array();

	$classes[] = 'agncy-l';

	/* Content sidebar. */
	$sidebar = agncy_get_page_sidebar();

	if ( ! empty( $sidebar['sidebar'] ) ) {
		$classes[] = 'agncy-l-c-ws';

		switch ( $sidebar['position'] ) {
			case 'left':
				$classes[] = 'agncy-l-c-sl';
				break;
			case 'right':
				$classes[] = 'agncy-l-c-sr';
				break;
			default:
				break;
		}
	}

	$classes = apply_filters( 'agncy_layout_classes', $classes );

	echo esc_attr( implode( ' ', $classes ) );
}

/**
 * HTML classes
 *
 * @since 1.0.0
 * @return string
 */
function agncy_html_class() {
	$classes = array();

	$classes[] = 'agncy-preloader';

	$classes = apply_filters( 'agncy_html_classes', $classes );

	echo esc_attr( implode( ' ', $classes ) );
}

/**
 * HTML style
 *
 * @since 1.0.0
 * @return string
 */
function agncy_html_style() {
	$style = apply_filters( 'agncy_html_style', '' );

	echo esc_attr( $style );
}