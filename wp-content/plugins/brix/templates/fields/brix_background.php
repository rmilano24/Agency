<?php

$value = $field->value();

$color_types = apply_filters( 'brix_background_color_types', brix_background_color_types() );

$is_data_ok = isset( $value['desktop'] ) && is_array( $value['desktop'] );
$breakpoints = brix_breakpoints();

$breakpoints_for_select = array();

foreach ( $breakpoints as $k => $breakpoint ) {
	$breakpoints_for_select[$k] = $breakpoint['label'];
}

do_action( 'brix_background_responsive', $field, $breakpoints_for_select, $value );

foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
	$handle = $field->handle() . "[$breakpoint_key]";
	$breakpoint_class = '';
	$_value = array();

	if ( $is_data_ok ) {
		$_value = isset( $value[$breakpoint_key] ) ? $value[$breakpoint_key] : array();
	}
	else {
		if ( $breakpoint_key !== 'desktop' ) {
			$_value = array();
		}
		else {
			$_value = $value;
		}
	}

	$color_type = isset( $_value['color_type'] ) && ! empty( $_value['color_type'] ) ? $_value['color_type'] : 'solid';

	if ( $breakpoint_key === 'desktop' ) {
		$breakpoint_class = 'active';
		$inherit = false;
	}
	else {
		$inherit = isset( $_value['inherit'] ) ? (bool) $_value['inherit'] : true;
	}

	if ( $inherit ) {
		$breakpoint_class .= ' brix-background-inherit';
	}

	printf( '<div class="brix-background-breakpoint %s" data-breakpoint="%s">', esc_attr( $breakpoint_class ), esc_attr( $breakpoint_key ) );

		if ( $breakpoint_key !== 'desktop' ) {
			echo '<span class="brix-background-inherit-wrapper">';
				echo '<span>' . esc_html__( 'Same as desktop', 'brix' ) . '</span>';
				brix_checkbox( $handle . '[inherit]', $inherit, array( 'switch', 'small' ) );
			echo '</span>';
		}

		echo '<div class="brix-background-wrappers">';
			printf( '<div class="brix-background-color-partial-wrapper" data-type="%s">', esc_attr( $color_type ) );
				echo '<h3 class="brix-background-section-heading">' . esc_html__( 'Color', 'brix' ) . '</h3>';

				echo '<div class="brix-background-color-partial-nav">';
					brix_radio( $handle . '[color_type]', $color_types, $color_type, array( 'switch', 'background-nav' ) );
				echo '</div>';

				brix_background_color_partial( $handle, $_value );
			echo '</div>';

			printf( '<div class="brix-background-image-partial-wrapper">' );
				echo '<h3 class="brix-background-section-heading">' . esc_html__( 'Image', 'brix' ) . '</h3>';

				brix_background_image_partial( $handle, $_value, $breakpoint_key );
			echo '</div>';

			$overlay_type = isset( $_value['overlay_type'] ) ? $_value['overlay_type'] : 'solid';

			printf( '<div class="brix-background-overlay-partial-wrapper" data-type="%s">', esc_attr( $overlay_type ) );

				$overlay_types = apply_filters( 'brix_background_overlay_types', brix_background_overlay_types() );

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
}