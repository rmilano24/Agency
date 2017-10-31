<?php

/**
 * Get the template part relative to the "a" type of the header.
 *
 * @since 1.0.0
 */
function agncy_header_load_template() {
	$breakpoint = wp_is_mobile() ? 'mobile' : 'desktop';
	$type = agncy_get_header_type();

	$agncy_header_path = 'templates/header/types/header-' . $type;
	$agncy_header_path = apply_filters( 'agncy_header_path', $agncy_header_path );

	$agncy_header_mobile_path = 'templates/header/types/header-mobile';
	$agncy_header_mobile_path = apply_filters( 'agncy_header_mobile_path', $agncy_header_mobile_path );

	if ( $breakpoint === 'desktop' ) {
		get_template_part( $agncy_header_path );

		printf( '<script type="text/template" id="%s">', esc_attr( 'agncy-hm' ) );
			get_template_part( $agncy_header_mobile_path );
		echo '</script>';
	} else {
		get_template_part( $agncy_header_mobile_path );
	}
}

add_action( 'agncy_header', 'agncy_header_load_template' );