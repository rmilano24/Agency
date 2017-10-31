<?php

/**
 * Custom styles for the divider builder block.
 *
 * @since 1.0.0
 * @param array $styles The divider builder block styles.
 * @return array
 */
function agncy_brix_styles_divider_block_styles( $styles ) {
	$styles['default']     = __( 'Invisible', 'agncy' );
	$styles['agncy-solid'] = __( 'Solid', 'agncy' );

	return $styles;
}

add_filter( 'brix_block_styles[type:divider]', 'agncy_brix_styles_divider_block_styles' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function agncy_brix_styles_divider_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'height',
		'type' => 'text',
		'label' => __( 'Height', 'agncy' ),
		'config' => array(
			'visible' => array( '_style' => 'agncy-solid' ),
		),
		'default' => '1px'
	);

		$fields[] = array(
			'handle' => 'size',
			'type' => 'select',
			'label' => __( 'Size', 'agncy' ),
			'config' => array(
				'data' => array(
					'full' => __( 'Full width', 'agncy' ),
					'medium' => __( 'Medium', 'agncy' ),
					'small' => __( 'Small', 'agncy' ),
				),
				'controller' => true,
				'visible' => array( '_style' => 'agncy-solid' )
			)
		);

			$fields[] = array(
				'handle' => 'alignment',
				'type' => 'select',
				'label' => __( 'Alignment', 'agncy' ),
				'config' => array(
					'data' => array(
						'left'   => __( 'Left', 'agncy' ),
						'center' => __( 'Center', 'agncy' ),
						'right'  => __( 'Right', 'agncy' ),
					),
					'visible' => array( 'size' => '!=full' )
				)
			);

	$fields[] = array(
		'handle' => 'color',
		'type' => 'color',
		'label' => __( 'Color', 'agncy' ),
		'config' => array(
			'visible' => array( '_style' => 'agncy-solid' )
		)
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:divider]', 'agncy_brix_styles_divider_block_fields' );

/**
 * Add the required inline styles for the divider builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function agncy_brix_styles_process_divider_frontend_block( $block_style, $block, $block_selector ) {
	$height = '';
	$color = '';

	if ( isset( $block->data ) && isset( $block->data->height ) ) {
		$height = $block->data->height;

		if ( is_numeric( $height ) ) {
			$height .= 'px';
		}
	}

	if ( isset( $block->data ) && isset( $block->data->color ) && ! empty( $block->data->color->color ) ) {
		$color = $block->data->color->color;
	} else {
		$color = 'currentColor';
	}

	if ( ! empty( $height ) || ! empty( $color ) ) {
		$block_style .= $block_selector . ' .brix-block-divider{';

			if ( ! empty( $height ) && $block->data->_style != 'default' ) {
				$block_style .= sprintf( "height:%s;", $height );
			}

			if ( ! empty( $color ) ) {
				$block_style .= sprintf( "background-color:%s;", $color );
			}

		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:divider]', 'agncy_brix_styles_process_divider_frontend_block', 10, 3 );

/**
 * Add a specific CSS class to the divider block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function agncy_brix_divider_builder_block_class( $classes, $data ) {
	$size      = isset( $data ) && isset( $data->size ) ? $data->size : 'full';
	$alignment = isset( $data ) && isset( $data->alignment ) ? $data->alignment : 'left';

	if ( $size ) {
		$classes[] = 'agncy-divider-sz-' . $size;
	}

	if ( $alignment ) {
		$classes[] = 'agncy-divider-al-' . $alignment;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:divider]', 'agncy_brix_divider_builder_block_class', 10, 2 );
