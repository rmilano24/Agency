<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Return the gradient value for the background color field
 *
 * @since  1.1.3
 * @param  array $color_types The color types list
 * @return array
 */
function brix_color_type_gradient( $color_types ) {
	$color_types['gradient'] =  __( 'Gradient', 'brix' );

	return $color_types;
}

add_filter( 'brix_background_color_types', 'brix_color_type_gradient' );

/**
 * Return the gradient value for the background overlay field
 *
 * @since  1.1.3
 * @param  array $overlay_types The overlay types list
 * @return array
 */
function brix_overlay_type_gradient( $overlay_types ) {
	$overlay_types['gradient'] =  __( 'Gradient', 'brix' );

	return $overlay_types;
}

add_filter( 'brix_background_overlay_types', 'brix_overlay_type_gradient' );

/**
 * Return the gradient value for the video background overlay field
 *
 * @since  1.1.3
 * @param  array $overlay_types The overlay types list
 * @return array
 */
function brix_video_overlay_type_gradient( $overlay_types ) {
	$overlay_types['gradient'] =  __( 'Gradient', 'brix' );

	return $overlay_types;
}

add_filter( 'brix_video_background_overlay_types', 'brix_video_overlay_type_gradient' );

/**
 * Return the gradient value for the video background overlay field
 *
 * @since  1.1.3
 * @param  array $overlay_types The overlay types list
 * @return array
 */
function brix_background_attachments( $attachments ) {
	$attachments['parallax'] =  __( 'Parallax', 'brix' );

	return $attachments;
}

add_filter( 'brix_background_image_attachments', 'brix_background_attachments' );
add_filter( 'brix_background_video_attachments', 'brix_background_attachments' );

/**
 * Output the parallax shift markup for the background video field.
 *
 * @since  1.1.3
 * @param string $handle The handle of the field.
 * @param array $attachments An array of background attachments.
 * @param string $attachment The attachment currently selected.
 * @param array $image_value The selected image.
 */
function brix_background_parallax_video_shift( $handle, $attachments, $attachment, $image_value ) {
	printf( '<div class="brix-background-image-attribute brix-background-image-motion brix-background-video-motion" data-type="%s">', esc_attr( $attachment ) );
		echo '<h4>' . esc_html__( 'Motion', 'brix' ) . '</h4>';

		brix_radio( $handle . '[attachment]', $attachments, $attachment, array( 'switch', 'small', 'image-attribute-switch', 'image-motion-switch' ) );

		echo '<div class="brix-background-image-attachment-parallax">';
			echo '<h4>' . esc_html__( 'Shift', 'brix' ) . '</h4>';
			printf( '<p>%s</p>',
				esc_html__( 'The image movement expressed in pixels.', 'brix' )
			);

			$shift = isset( $image_value['shift'] ) ? $image_value['shift'] : '100';

			printf( '<input type="number" min="0" name="%s" value="%s">',
				esc_attr( $handle . '[shift]' ),
				esc_attr( $shift )
			);
		echo '</div>';
	echo '</div>';
}

add_action( 'brix_background_parallax_video_shift', 'brix_background_parallax_video_shift', 10, 4 );

/**
 * Output the parallax shift markup for the background video field.
 *
 * @since  1.1.3
 * @param string $handle The handle of the field.
 * @param array $image_value The selected image.
 */
function brix_background_parallax_image_shift( $handle, $image_value ) {
	echo '<div class="brix-background-image-attachment-parallax">';
		echo '<h4>' . esc_html__( 'Shift', 'brix' ) . '</h4>';
		printf( '<p>%s</p>',
			esc_html__( 'The image movement expressed in pixels.', 'brix' )
		);

		$shift = '100';

		if ( isset( $image_value['image'] ) && isset( $image_value['image']['shift'] ) ) {
			$shift = $image_value['image']['shift'];
		}
		elseif ( isset( $image_value['shift'] ) ) {
			$shift = $image_value['shift'];
		}

		printf( '<input type="number" min="0" name="%s" value="%s">',
			esc_attr( $handle . '[image][shift]' ),
			esc_attr( $shift )
		);
	echo '</div>';
}

add_action( 'brix_background_parallax_image_shift', 'brix_background_parallax_image_shift', 10, 2 );

/**
 * Output the responsive feature for the background field
 *
 * @since  1.1.3
 * @param  object $field The field object.
 * @param  array $breakpoints_for_select An array of breakpoints.
 */
function brix_background_responsive( $field, $breakpoints_for_select, $value ) {
	$edit_responsive = isset( $value['edit_responsive'] ) ? (bool) $value['edit_responsive'] : false;

	echo '<div class="brix-background-responsive-mode-wrapper">';
		echo '<div class="brix-background-reponsive-mode-checkbox-wrapper">';
			echo '<span>' . esc_html__( 'Change for different screen sizes', 'brix' ) . '</span>';
			brix_checkbox( $field->handle() . '[edit_responsive]', $edit_responsive, array( 'switch', 'small' ) );
		echo '</div>';

		$wrapper_class = '';

		if ( $edit_responsive ) {
			$wrapper_class = 'active';
		}

		printf( '<div class="brix-background-reponsive-mode-breakpoints-wrapper %s">', esc_attr( $wrapper_class ) );

			echo '<div class="brix-background-breakpoints-label-wrapper">';
				printf( '<h4 class="brix-background-breakpoints-label">%s</h4>', esc_html__( 'Breakpoints', 'brix' ) );
				printf( '<p>%s</p>', esc_html__( 'You can alter the background appearance on specific media queries.', 'brix' ) );
			echo '</div>';

			echo '<div class="brix-background-breakpoints-select-wrapper">';
				brix_select(
					'',
					$breakpoints_for_select,
					'',
					'normal'
				);
			echo '</div>';

		echo '</div>';

		echo '<span class="brix-background-responsive-mode-wrapper-deco"></span>';

	echo '</div>';
}

add_action( 'brix_background_responsive', 'brix_background_responsive', 10, 3 );