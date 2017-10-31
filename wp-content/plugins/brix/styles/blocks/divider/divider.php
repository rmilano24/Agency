<?php

/**
 * Custom styles for the divider builder block.
 *
 * @since 1.0.0
 * @param array $styles The divider builder block styles.
 * @return array
 */
function brix_styles_divider_block_styles( $styles ) {
	$styles['default'] = __( 'Invisible', 'brix' );
	$styles['solid']   = __( 'Solid', 'brix' );

	return $styles;
}

add_filter( 'brix_block_styles[type:divider]', 'brix_styles_divider_block_styles' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_divider_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'height',
		'type' => 'text',
		'label' => __( 'Height', 'brix' ),
		'config' => array(
			'visible' => array( '_style' => 'solid' )
		)
	);

	$fields[] = array(
		'handle' => 'color',
		'type' => 'color',
		'label' => __( 'Color', 'brix' ),
		'config' => array(
			'visible' => array( '_style' => 'solid' )
		)
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:divider]', 'brix_styles_divider_block_fields' );

/**
 * Add the required inline styles for the divider builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_divider_frontend_block( $block_style, $block, $block_selector ) {
	$height = '';
	$color = '';

	if ( isset( $block->data ) && isset( $block->data->height ) ) {
		$height = $block->data->height;

		if ( is_numeric( $height ) ) {
			$height .= 'px';
		}
	}

	if ( isset( $block->data ) && isset( $block->data->color ) ) {
		$color = $block->data->color->color;
	}

	if ( ! empty( $height ) || ! empty( $color ) ) {
		$block_style .= $block_selector . ' .brix-block-divider{';

			if ( ! empty( $height ) ) {
				$block_style .= sprintf( "height:%s;", $height );
			}

			if ( ! empty( $color ) ) {
				$block_style .= sprintf( "background-color:%s;", $color );
			}

		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:divider]', 'brix_styles_process_divider_frontend_block', 10, 3 );