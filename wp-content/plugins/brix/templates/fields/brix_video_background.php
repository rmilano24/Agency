<?php

$value = $field->value();

$breakpoint_key = 'desktop';
$handle = $field->handle();
$breakpoint_class = '';

if ( ! isset( $value['video_data'] ) ) {
	$_value = array(
		'video_data' => $value
	);
}
else {
	$_value = $value;
}

$breakpoint_class = 'active';

printf( '<div class="brix-background-breakpoint %s">', esc_attr( $breakpoint_class ) );
	echo '<span class="brix-video-background-wrapper-deco"></span>';

	if ( $breakpoint_key !== 'desktop' ) {
		echo '<span class="brix-background-inherit-wrapper">';
			brix_checkbox( $handle . '[inherit]', $inherit, array( 'switch', 'small' ) );
			esc_html_e( 'Inherit', 'brix' );
		echo '</span>';
	}

	echo '<div class="brix-background-wrappers">';
		printf( '<div class="brix-background-video-partial-wrapper">' );
			echo '<h3 class="brix-background-section-heading">' . esc_html__( 'Video', 'brix' ) . '</h3>';

			brix_background_video_partial( $handle . '[video_data]', $_value );
		echo '</div>';

		$overlay_type = isset( $_value['overlay_type'] ) ? $_value['overlay_type'] : 'solid';

		printf( '<div class="brix-background-overlay-partial-wrapper" data-type="%s">', esc_attr( $overlay_type ) );

			$overlay_types = apply_filters( 'brix_video_background_overlay_types', brix_video_background_overlay_types() );

			echo '<h3 class="brix-background-section-heading">' . esc_html__( 'Overlay', 'brix' ) . '</h3>';

			echo '<div class="brix-background-overlay-partial-nav">';
				brix_radio( $handle . '[overlay_type]', $overlay_types, $overlay_type, array( 'switch', 'background-nav' ) );
			echo '</div>';

			$overlay_value = isset( $_value['overlay'] ) ? $_value['overlay'] : array();

			brix_background_color_partial( $handle . '[overlay]', $overlay_value, true );
			brix_background_image_partial( $handle . '[overlay]', $overlay_value, $breakpoint_key );
		echo '</div>';
	echo '</div>';

echo '</div>';