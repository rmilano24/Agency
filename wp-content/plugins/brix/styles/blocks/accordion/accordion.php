<?php

/**
 * Custom styles for the accordion builder block.
 *
 * @since 1.0.0
 * @param array $styles The accordion builder block styles.
 * @return array
 */
function brix_styles_accordion_block_styles( $styles ) {
	$styles['default'] = __( 'Default', 'brix' );
	$styles['minimal'] = __( 'Minimal', 'brix' );
	$styles['classic'] = __( 'Classic', 'brix' );

	return $styles;
}

add_filter( 'brix_block_styles[type:accordion]', 'brix_styles_accordion_block_styles' );


/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_accordion_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'text_transform',
		'label'  => __( 'Label case', 'brix' ),
		'type'   => 'select',
		'config' => array(
			'data' => array(
				'none'       => __( 'Inherit', 'brix' ),
				'capitalize' => __( 'Capitalize', 'brix' ),
				'lowercase'  => __( 'Lowercase', 'brix' ),
				'uppercase'  => __( 'Uppercase', 'brix' )
			)
		)
	);

	$fields[] = array(
		'handle' => 'font_weight',
		'label'  => __( 'Label weight', 'brix' ),
		'type'   => 'select',
		'default' => 'bold',
		'config' => array(
			'data' => array(
				'100' => __( '100', 'brix' ),
				'200' => __( '200', 'brix' ),
				'300' => __( '300', 'brix' ),
				'normal' => __( 'Regular (400)', 'brix' ),
				'500' => __( '500', 'brix' ),
				'600' => __( '600', 'brix' ),
				'bold' => __( 'Bold (700)', 'brix' ),
				'800' => __( '800', 'brix' ),
				'900' => __( '900', 'brix' )
			)
		)
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:accordion]', 'brix_styles_accordion_block_fields' );

/**
 * Add the required inline styles for the accordion builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_accordion_frontend_block( $block_style, $block, $block_selector ) {
	$accordion = isset( $block->data ) && isset( $block->data->accordion->toggle ) ? $block->data->accordion->toggle : array();

	$text_transform = isset( $block->data ) && isset( $block->data->text_transform ) ? $block->data->text_transform : '';
	$font_weight    = isset( $block->data ) && isset( $block->data->font_weight ) ? $block->data->font_weight : '';

	$has_style_default = $text_transform || $font_weight;

	if ( $has_style_default ) {
		$block_style .= $block_selector . ' .brix-toggle-trigger {';

			if ( $text_transform ) {
				$block_style .= 'text-transform:' . $text_transform . ';';
			}

			if ( $font_weight ) {
				$block_style .= 'font-weight:' . $font_weight . ';';
			}

		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:accordion]', 'brix_process_accordion_frontend_block', 10, 3 );