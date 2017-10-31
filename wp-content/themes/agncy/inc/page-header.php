<?php

/**
 * Display the page header according to its type and the page context.
 *
 * @since 1.0.0
 */
function agncy_page_header() {
	if ( is_home() || is_attachment() || is_page_template( 'template-projects-slideshow.php' ) ) {
		return;
	}

	$key = 'agncy_page_header';

	$disabled = false;

	if ( ! function_exists( 'ev_do_action' ) ) {
		do_action( "${key}_before" );
		do_action( $key );
		do_action( "${key}_after" );

		return;
	}

	ev_do_action( "${key}_before" );

		/* Actual page header contents. */
		if ( ! $disabled ) {
			ev_do_action( $key );
		}

	ev_do_action( "${key}_after" );
}

/**
 * Display a page title.
 *
 * @since 1.0.0
 */
function agncy_page_title() {
	$title = '';

	if ( is_search() ) {
		$title = sprintf( '&#8220;%s&#8221;', get_search_query() );
	}
	else if ( is_archive() ) {
		$title = get_the_archive_title();
	}
	else {
		$title = get_the_title();
	}

	$title = apply_filters( 'agncy_page_title', $title );

	if ( ! $title ) {
		return;
	}

	$title = nl2br( $title );

	$data_attrs = array();
	$classes = array();

	$classes[] = 'agncy-ph-t';

	if ( is_front_page() ) {
		$title_markup = 'h2';
	} else {
		$title_markup = 'h1';
	}

	printf( '<%s class="%s" %s><span>', esc_html( $title_markup ), esc_attr( implode( ' ', $classes ) ), esc_attr( implode( ' ', $data_attrs ) ) );
		echo wp_kses_post( $title );
	printf( '</span></%s>', esc_html( $title_markup ) );
}

/**
 * Display the page before title
 *
 * @since 1.0.0
 */
function agncy_page_before_title() {
	$classes = array();
	$before_title = '';
	$before_title_page_option = get_post_meta( get_the_ID(), 'agncy_before_title', true );

	$classes[] = 'agncy-ph-bt';

	if ( is_singular( 'post' ) ) {
		$before_title = agncy_get_post_categories();
	}
	else if ( is_search() ) {
		$before_title = __( 'Search results for:', 'agncy' );
	}
	else if ( is_author() ) {
		$before_title = __( 'Author archive for:', 'agncy' );
	}
	else if ( is_archive() ) {
		$before_title = __( 'Archive for:', 'agncy' );
	} else if ( $before_title_page_option ) {
		$before_title = wp_kses_post( $before_title_page_option );
	}

	if ( $before_title ) {
		echo '<div class="agncy-ph-bt_w">';
			printf( '<p class="%s">%s</p>', esc_attr( implode( ' ', $classes ) ), wp_kses_post( $before_title ) );
		echo '</div>';
	}
}

/**
 * Remove the prefix from the archive title
 *
 * @since 1.0.0
 */
function agncy_change_the_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' ;
	} elseif ( is_year() ) {
		$title = get_the_date( _x( 'Y', 'yearly archives date format', 'agncy' ) );
	} elseif ( is_month() ) {
		$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'agncy' ) );
	} elseif ( is_day() ) {
		$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'agncy' ) );
	} elseif ( is_tax( 'agncy_project_category' ) ) {
		$title = single_tag_title( '', false );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'agncy_change_the_archive_title' );

/**
 * Return the featured image
 *
 * @return string
 */
function agncy_featured_image() {
	$id = get_the_ID();
	$image_size = get_post_meta( $id, 'agncy_page_featured_image_sizes', true );
	$fi = false;

	if ( empty( $image_size ) ) {
		$image_size = 'full';
	}

	$fi = get_the_post_thumbnail_url( $id, $image_size );

	if ( is_archive() || is_search() ) {
		$fi = false;
	}

	if ( ! empty( $fi ) ) {
		echo '<div class="agncy-ph-fi_w">';
			echo '<div class="agncy-ph-fi_w-i">';
				printf( '<img class="agncy-ph-fi" src="%s" />', esc_url( $fi ) );
			echo '</div>';
		echo '</div>';
	}
}

add_action( 'agncy_after_page_header', 'agncy_featured_image' );
