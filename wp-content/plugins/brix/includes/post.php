<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Return the entry date element for the current post in a loop.
 *
 * @since 1.0.0
 * @param string $classes An optional set of CSS classes to be passed to the time element.
 * @return string
 */
function brix_get_entry_date( $classes = '' ) {
	$time_string = '<time class="entry-date published updated ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time><time class="updated screen-reader-text' . esc_attr( $classes ) . '" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	return $time_string;
}

/**
 * Display the entry date element for the current post in a loop.
 *
 * @since 1.0.0
 * @param string $classes An optional set of CSS classes to be passed to the time element.
 */
function brix_entry_date( $classes = '' ) {
	echo brix_get_entry_date( $classes );
}