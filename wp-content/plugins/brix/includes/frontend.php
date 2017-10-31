<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Get a component icon/image decoration.
 *
 * @since 1.0.0
 * @param array $decoration The decoration array.
 * @return string
 */
function brix_get_decoration( $decoration ) {
	$decoration_html = '';
	$decoration = (array) $decoration;

	$type = isset( $decoration['type'] ) ? $decoration['type'] : false;

	switch ( $type ) {
		case 'icon':
			$decoration_html = brix_get_decoration_icon( $decoration['icon'] );
			break;
		case 'image':
			$image_url = '';
			$image_id = 0;
			$image_size = 'full';
			$image_alt = '';
			$image_source = isset( $decoration['image_source'] ) ? $decoration['image_source'] : 'image';

			if ( $image_source === 'image' ) {
				$image = ( object ) $decoration['image'];
				$image_desktop = '';

				if ( isset( $image->desktop ) && isset( $image->desktop['1'] ) ) {
					$image_desktop = ( object ) $image->desktop['1'];

					if ( isset( $image_desktop ) && isset( $image_desktop->id ) && ! empty( $image_desktop->id ) ) {
						$image_size = 'full';
						if ( isset( $image_desktop->image_size ) && ! empty( $image_desktop->image_size ) ) {
							$image_size = $image_desktop->image_size;
						}

						$image_url = $image_desktop->id;
						$image_real_url = brix_get_image( $image_desktop->id, $image_size );
					}
				}
			}
			elseif ( $image_source === 'external' ) {
				if ( isset( $decoration['image_url'] ) && ! empty( $decoration['image_url'] ) ) {
					$image_url = $decoration['image_url'];
					$image_real_url = $image_url;
				}
			}

			$width = isset( $decoration[ 'width' ] ) ? $decoration[ 'width' ] : '';
			$style_width = $width;

			if ( is_numeric( $style_width ) ) {
				$style_width .= 'px';
			}

			$height = isset( $decoration[ 'height' ] ) ? $decoration[ 'height' ] : '';
			$style_height = $height;

			if ( is_numeric( $style_height ) ) {
				$style_height .= 'px';
			}

			$image_style = '';

			if ( ! empty( $style_width ) ) {
				$image_style .= sprintf( 'width:%s;', $style_width );
			}

			if ( ! empty( $style_height ) ) {
				$image_style .= sprintf( 'height:%s;', $style_height );
			}

			$image_placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

			if ( is_admin() ) {
				$image_placeholder = $image_real_url;
			}

			$img_html = brix_get_lazy_image_markup( $image_url, array(
				'classes' => array( 'brix-icon' ),
				'size' => $image_size,
				'placeholder' => $image_placeholder,
				'width' => $width,
				'height' => $height,
				'attrs' => array(
					'style' => $image_style,
				)
			) );

			$decoration_html .= $img_html;

			break;
		default:
			break;
	}

	return $decoration_html;
}

/**
 * Get the HTML and style for a decorative icon element.
 *
 * @since 1.0.0
 * @param array $icon The icon configuration array.
 * @return string
 */
function brix_get_decoration_icon( $icon ) {
	$icon_style = '';
	$icon = (array) $icon;
	$html = '';

	if ( ! empty( $icon['icon'] ) ) {
		$html = '<div class="brix-icon-wrapper">';
			$html .= brix_get_icon( $icon['icon'] );
		$html .= '</div>';
	}

	return $html;
}

/**
 * Generate the style for the icon.
 *
 * @since  1.0.0
 * @param  string $block_selector the block selector
 * @param  array $icon the icon data array
 * @return string
 */
function brix_icon_style( $block_selector, $icon ) {
	$icon = (array) $icon;
	$style = '';

	if ( isset( $icon['size'] ) ) {
		if ( empty( $icon['size'] ) ) {
			$icon['size'] = '1em';
		}

		if ( strpos( $icon['size'], '%' ) != -1 ) {
			$icon['size'] = str_replace( '%', '', $icon['size'] );
		}

		if ( is_numeric( $icon['size'] ) ) {
			$icon['size'] .= 'px';
		}

		$style .= $block_selector . ' .brix-icon {';
			$style .= 'width:' . $icon['size'] . ';';
			$style .= 'height:' . $icon['size'] . ';';
		$style .= '}';
	}

	if ( isset( $icon['color'] ) ) {
		$style .= $block_selector . ' .brix-icon * {';
			if ( ! empty( $icon['color'] ) ) {
				$icon_style = 'fill:' . $icon['color'] . ';';
			} else {
				$icon_style = 'fill:currentColor;';
			}
			$style .= apply_filters( 'brix_icon_style', $icon_style, $icon );
		$style .= '}';
	}

	return $style;
}