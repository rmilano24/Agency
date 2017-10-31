<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Background color partial.
 *
 * @since 1.1.1
 * @param string $handle The sub-field handle.
 * @param array $data The field data.
 * @param boolean $opacity Set to true to turn on opacity controls.
 */
function brix_background_color_partial( $handle, $data, $opacity = false ) {
	$color_value = isset( $data['color'] ) && isset( $data['color']['color'] ) ? $data['color']['color'] : '';

	$gradients = isset( $data['gradient'] ) ? $data['gradient'] : array(
		'stop_0' => array(
			'color' => array(
				'color' => '#fff',
				'opacity' => '1',
			),
			'location' => '0'
		),
		'stop_1' => array(
			'color' => array(
				'color' => '#000',
				'opacity' => '1',
			),
			'location' => '100'
		)
	);

	$gradient_value = isset( $data['gradient'] ) && isset( $data['color']['color'] ) ? $data['color']['color'] : '';

	echo '<div class="brix-background-color-partial-wrapper">';

		echo '<div class="brix-background-color-color">';
			brix_color( $handle . '[color]', $color_value, $opacity );
		echo '</div>';

		echo '<div class="brix-background-color-gradient">';

			echo '<div class="brix-background-color-gradient-table">';

				echo '<div class="brix-background-color-gradient-table-heading-row">';
					echo '<div class="brix-background-color-gradient-table-cell brix-background-color-gradient-table-color-cell">';
						echo esc_html( __( 'Color', 'brix' ) );
					echo '</div>';
					echo '<div class="brix-background-color-gradient-table-cell">';
						echo esc_html( __( 'Location', 'brix' ) );
					echo '</div>';
				echo '</div>';

				foreach ( $gradients as $i => $gradient ) {
					if ( ! brix_string_starts_with( $i, 'stop_' ) ) {
						continue;
					}

					$gradient_handle = $handle . '[gradient][' . $i . ']';
					$location = isset( $gradient['location'] ) ? intval( $gradient['location'] ) : 0;
					$color_value = isset( $gradient['color'] ) ? $gradient['color'] : array();

					echo '<div class="brix-background-color-gradient-table-row">';

						echo '<div class="brix-background-color-gradient-table-cell brix-background-color-gradient-table-color-cell">';
							brix_color( $gradient_handle . '[color]', $color_value, $opacity, 'small' );
						echo '</div>';

						echo '<div class="brix-background-color-gradient-table-cell">';
							printf( '<input type="number" name="%s" min="0" max="100" value="%s">',
								esc_attr( $gradient_handle . '[location]' ),
								esc_attr( $location )
							);
						echo '</div>';

					echo '</div>';
				}

			echo '</div>';

			echo '<div class="brix-background-color-gradient-attributes">';
				$direction = isset( $data['gradient'] ) && isset( $data['gradient']['direction'] ) ? $data['gradient']['direction'] : 'horizontal';

				$directions = array(
					'horizontal'    => BRIX_ADMIN_ASSETS_URI . 'css/i/background/horz.svg',
					'vertical'      => BRIX_ADMIN_ASSETS_URI . 'css/i/background/vert.svg',
					'diagonal_up'   => BRIX_ADMIN_ASSETS_URI . 'css/i/background/diag_up.svg',
					'diagonal_down' => BRIX_ADMIN_ASSETS_URI . 'css/i/background/diag_down.svg',
					'radial'        => BRIX_ADMIN_ASSETS_URI . 'css/i/background/radial.svg',
				);

				echo '<span class="brix-background-color-gradient-attributes-label">' . esc_html( __( 'Direction', 'brix' ) ) . '</span>';
				brix_radio( $handle . '[gradient][direction]', $directions, $direction, array( 'switch', 'graphic', 'color-attributes' ) );

				echo '<div class="brix-background-color-gradient-reverse">';
					$reverse = isset( $data['gradient'] ) && isset( $data['gradient']['reverse'] ) ? (bool) $data['gradient']['reverse'] : false;

					brix_checkbox( $handle . '[gradient][reverse]', $reverse, array( 'switch', 'small' ) );
					echo __( 'Reverse', 'brix' );
				echo '</div>';
			echo '</div>';

			echo '<div class="brix-background-color-gradient-preview-wrapper">';
				echo '<div class="brix-background-color-gradient-preview-inner-wrapper">';
						echo '<canvas class="brix-background-color-gradient-preview" width="96" height="96"></canvas>';
					echo '</div>';
				echo '</div>';
		echo '</div>';

	echo '</div>';
}

/**
 * Background image partial.
 *
 * @since 1.1.1
 * @param string $handle The sub-field handle.
 * @param array $data The field data.
 * @param string $breakpoint The breakpoint being rendered.
 */
function brix_background_image_partial( $handle, $data, $breakpoint = 'desktop' ) {
	$image_value = $data;
	$breakpoints = brix_breakpoints();
	$breakpoint = $breakpoints[$breakpoint];

	echo '<div class="brix-background-image-external-wrapper">';

		echo '<div class="brix-background-image-image-wrapper">';
			$image_id = isset( $image_value['image'] ) && isset( $image_value['image']['desktop'] ) && isset( $image_value['image']['desktop'][1] ) && isset( $image_value['image']['desktop'][1]['id'] ) ? $image_value['image']['desktop'][1]['id'] : '';
			$image_size = isset( $image_value['image'] ) && isset( $image_value['image']['desktop'] ) && isset( $image_value['image']['desktop'][1] ) && isset( $image_value['image']['desktop'][1]['image_size'] ) ? $image_value['image']['desktop'][1]['image_size'] : '';

			echo '<div class="brix-image-upload-container">';
				brix_image_upload( $handle . '[image]', $image_id, array(
					'thumb_size' => 'medium'
				) );

				echo '<p class="brix-image-upload-image-size-selection">';
					echo '<span>' . esc_html( __( 'Image size', 'brix' ) ) . '</span>';

					brix_select(
						$handle . '[image][desktop][1][image_size]',
						brix_get_image_sizes_for_select(),
						$image_size,
						'small'
					);
				echo '</p>';
			echo '</div>';
		echo '</div>';

		echo '<div class="brix-background-image-attributes-wrapper">';

			echo '<div class="brix-background-image-attributes-col">';
				echo '<div class="brix-background-image-attribute">';
					$size = '';

					if ( isset( $image_value['image'] ) && isset( $image_value['image']['size'] ) ) {
						$size = $image_value['image']['size'];
					}
					elseif ( isset( $image_value['size'] ) ) {
						$size = $image_value['size'];
					}

					echo '<h4>' . esc_html__( 'Size', 'brix' ) . '</h4>';

					$sizes = array(
						'' => array(
							'title' => __( 'Default', 'brix' ),
							'el'    => '<img src="' . BRIX_ADMIN_ASSETS_URI . 'css/i/background/default.svg" />'
						),
						'cover' => array(
							'title' => __( 'As large as possible', 'brix' ),
							'el'    => __( 'Cover', 'brix' )
						),
						'contain' => array(
							'title' => __( 'Fit inside', 'brix' ),
							'el'    => __( 'Contain', 'brix' )
						),
					);

					echo '<span class="brix-background-radio-attributes-wrapper">';
						foreach ( $sizes as $s => $v ) {
							$active_class = '';

							if ( $s == $size ) {
								$active_class = 'active';
							}

							printf( '<span class="brix-background-radio-attributes brix-tooltip %s" data-title="%s" data-value="%s">%s</span>', esc_attr( $active_class ), esc_attr( $v['title'] ), esc_attr( $s ), wp_kses_post( $v['el'] ) );
						}

						printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $handle . '[image][size]' ), esc_attr( $size ) );
					echo '</span>';

				echo '</div>';

				echo '<div class="brix-background-image-attribute">';
					$repeat = 'no-repeat';

					if ( isset( $image_value['image'] ) && isset( $image_value['image']['repeat'] ) ) {
						$repeat = $image_value['image']['repeat'];
					}
					elseif ( isset( $image_value['repeat'] ) ) {
						$repeat = $image_value['repeat'];
					}

					echo '<h4>' . esc_html__( 'Repeat', 'brix' ) . '</h4>';

					$repeats = array(
						'no-repeat' => array(
							'title' => __( 'No repeat', 'brix' ),
							'el' => '<img src="' . BRIX_ADMIN_ASSETS_URI . 'css/i/background/default.svg" />'
						),
						'' => array(
							'title' => __( 'Repeat', 'brix' ),
							'el' => '<img src="' . BRIX_ADMIN_ASSETS_URI . 'css/i/background/repeat.svg" />'
						),
						'repeat-x'  => array(
							'title' => __( 'Repeat horizontally', 'brix' ),
							'el' => '<img src="' . BRIX_ADMIN_ASSETS_URI . 'css/i/background/horz.svg" />'
						),
						'repeat-y'  => array(
							'title' => __( 'Repeat vertically', 'brix' ),
							'el' => '<img src="' . BRIX_ADMIN_ASSETS_URI . 'css/i/background/vert.svg" />'
						),
					);

					echo '<span class="brix-background-radio-attributes-wrapper">';
						foreach ( $repeats as $s => $v ) {
							$active_class = '';

							if ( $s == $repeat ) {
								$active_class = 'active';
							}

							printf( '<span class="brix-background-radio-attributes brix-tooltip %s" data-title="%s" data-value="%s">%s</span>', esc_attr( $active_class ), esc_attr( $v['title'] ), esc_attr( $s ), wp_kses_post( $v['el'] ) );
						}

						printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $handle . '[image][repeat]' ), esc_attr( $repeat ) );
					echo '</span>';

				echo '</div>';

				$attachment = '';

				if ( isset( $image_value['image'] ) && isset( $image_value['image']['attachment'] ) ) {
					$attachment = $image_value['image']['attachment'];
				}
				elseif ( isset( $image_value['attachment'] ) ) {
					$attachment = $image_value['attachment'];
				}

				$attachments = apply_filters( 'brix_background_image_attachments', brix_background_image_attachments() );

				if ( count( $attachments ) > 1 ) {
					printf( '<div class="brix-background-image-attribute brix-background-image-motion" data-type="%s">', esc_attr( $attachment ) );
						echo '<h4>' . esc_html__( 'Motion', 'brix' ) . '</h4>';

						brix_radio( $handle . '[image][attachment]', $attachments, $attachment, array( 'switch', 'small', 'image-attribute-switch', 'image-motion-switch' ) );

						do_action( 'brix_background_parallax_image_shift', $handle, $image_value );
					echo '</div>';
				}

			echo '</div>';

			echo '<div class="brix-background-image-attributes-col">';
				echo '<div class="brix-background-image-attribute">';
					echo '<h4>' . esc_html__( 'Position', 'brix' ) . '</h4>';

					$positions = array(
						'center center' => __( 'Center', 'brix' ),
						'left top'      => __( 'Top left', 'brix' ),
						'right top'     => __( 'Top right', 'brix' ),
						'right bottom'  => __( 'Bottom right', 'brix' ),
						'left bottom'   => __( 'Bottom left', 'brix' ),
						'left center'   => __( 'Center left', 'brix' ),
						'right center'  => __( 'Center right', 'brix' ),
						'center top'    => __( 'Top center', 'brix' ),
						'center bottom' => __( 'Bottom center', 'brix' ),
					);

					$position = 'center center';

					if ( isset( $image_value['image'] ) && isset( $image_value['image']['position'] ) ) {
						$position = $image_value['image']['position'];
					}
					elseif ( isset( $image_value['position'] ) ) {
						$position = $image_value['position'];
					}

					brix_radio( $handle . '[image][position]', $positions, $position, array( 'image-position' ) );
				echo '</div>';
			echo '</div>';

		echo '</div>';

	echo '</div>';
}

/**
 * Background video partial.
 *
 * @since 1.1.1
 * @param string $handle The sub-field handle.
 * @param array $data The field data.
 * @param string $breakpoint The breakpoint being rendered.
 */
function brix_background_video_partial( $handle, $data, $breakpoint = 'desktop' ) {
	$image_value = isset( $data['video_data'] ) ? $data['video_data'] : array();
	$breakpoints = brix_breakpoints();
	$breakpoint = $breakpoints[$breakpoint];

	echo '<div class="brix-background-video-external-wrapper">';

		echo '<div class="brix-background-video-video-wrapper">';
			$image_id = isset( $image_value['poster_image'] ) && isset( $image_value['poster_image']['desktop'] ) && isset( $image_value['poster_image']['desktop'][1] ) && isset( $image_value['poster_image']['desktop'][1]['id'] ) ? $image_value['poster_image']['desktop'][1]['id'] : '';
			$image_size = isset( $image_value['poster_image'] ) && isset( $image_value['poster_image']['desktop'] ) && isset( $image_value['poster_image']['desktop'][1] ) && isset( $image_value['poster_image']['desktop'][1]['image_size'] ) ? $image_value['poster_image']['desktop'][1]['image_size'] : '';

			echo '<div class="brix-image-upload-container">';
				brix_image_upload( $handle . '[poster_image]', $image_id, array(
					'thumb_size' => 'medium'
				) );

				echo '<p class="brix-image-upload-image-size-selection">';
					echo '<span>' . esc_html( __( 'Image size', 'brix' ) ) . '</span>';

					brix_select(
						$handle . '[poster_image][desktop][1][image_size]',
						brix_get_image_sizes_for_select(),
						$image_size,
						'small'
					);
				echo '</p>';
			echo '</div>';

		echo '</div>';

	echo '<div class="brix-background-video-attributes-wrapper">';

			echo '<div class="brix-background-video-url-wrapper">';
				echo '<h4>' . esc_html__( 'Video URL', 'brix' ) . '</h4>';

				$video_url = isset( $image_value['url'] ) ? $image_value['url'] : '';

				printf( '<input type="text" value="%s" name="%s" size="80">',
					esc_attr( $video_url ),
					esc_attr( $handle . '[url]' )
				);
			echo '</div>';

			echo '<div class="brix-background-video-checkbox-wrapper">';
				$mobile_disabled = isset( $image_value['mobile_disabled'] ) ? (bool) $image_value['mobile_disabled'] : false;

				brix_checkbox( $handle . '[mobile_disabled]', $mobile_disabled, array( 'switch', 'small' ) );
				echo '<span>' . esc_html__( 'Disable video on mobile devices', 'brix' ) . '</span>';
			echo '</div>';

			echo '<div class="brix-background-image-attributes-col">';
				$attachment = isset( $image_value['attachment'] ) ? $image_value['attachment'] : '';
				$attachments = apply_filters( 'brix_background_video_attachments', brix_background_video_attachments() );

				if ( count( $attachments ) > 1 ) {
					do_action( 'brix_background_parallax_video_shift', $handle, $attachments, $attachment, $image_value );
				}

			echo '</div>';


		echo '</div>';

	echo '</div>';
}

/**
 * The background color types
 *
 * @since 1.1.3
 * @return array The background color types
 */
function brix_background_color_types() {
	$color_types = array(
		'solid' => __( 'Color', 'brix' )
	);

	return $color_types;
}

/**
 * The background overlay types
 *
 * @since 1.1.3
 * @return array The background overlay types
 */
function brix_background_overlay_types() {
	$overlay_types = array(
		'solid' => __( 'Color', 'brix' ),
		'image' => __( 'Image', 'brix' ),
	);

	return $overlay_types;
}

/**
 * The video background overlay types
 *
 * @since 1.1.3
 * @return array The video background overlay types
 */
function brix_video_background_overlay_types() {
	$overlay_types = array(
		'solid' => __( 'Color', 'brix' ),
		'image' => __( 'Image', 'brix' ),
	);

	return $overlay_types;
}

/**
 * The background image attachments
 *
 * @since 1.1.3
 * @return array The background image attachments
 */
function brix_background_image_attachments() {
	$attachments = array(
		''      => __( 'Default', 'brix' ),
		'fixed' => __( 'Fixed', 'brix' )
	);

	return $attachments;
}

/**
 * The background video attachments
 *
 * @since 1.1.3
 * @return array The background video attachments
 */
function brix_background_video_attachments() {
	$attachments = array(
		'' => __( 'Default', 'brix' )
	);

	return $attachments;
}