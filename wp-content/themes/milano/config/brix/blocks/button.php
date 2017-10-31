<?php

/**
 * Custom styles for the button builder block in Agncy.
 *
 * @since 1.0.0
 * @param array $styles The button builder block styles.
 * @return array
 */
function agncy_brix_styles_button_block_styles( $styles ) {
	$new_styles                         = array();
	$new_styles['default']              = __( 'Squared block', 'agncy' );
	$new_styles['strikethrough']        = __( 'Strikethrough', 'agncy' );
	$new_styles['strikethrough-simple'] = __( 'Strikethrough simple', 'agncy' );

	return $new_styles;
}

add_filter( 'brix_block_styles[type:button]', 'agncy_brix_styles_button_block_styles' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function agncy_brix_styles_button_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'agncy_btn_size',
		'type' => 'select',
		'label' => __( 'Label size', 'agncy' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Default', 'agncy' ),
				'micro'   => __( 'Micro', 'agncy' ),
				'small'   => __( 'Small', 'agncy' ),
				'large'   => __( 'Large', 'agncy' ),
				'full'    => __( 'Full width', 'agncy' ),
				'custom'  => __( 'Custom', 'agncy' )
			),
		)
	);

		$fields[] = array(
			'handle' => 'agncy_btn_label_font_size',
			'label'  => __( 'Label font size', 'agncy' ),
			'type'   => 'text',
			'config' => array(
				'visible' => array( 'agncy_btn_size' => 'custom' )
			)
		);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:button]', 'agncy_brix_styles_button_block_fields', 1 );

/**
 * Add a specific CSS class to the button block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function agncy_brix_button_builder_block_class( $classes, $data ) {
	$size  = isset( $data ) && isset( $data->agncy_btn_size ) ? $data->agncy_btn_size : 'default';

	$classes[] = 'brix-button-size-' . $size;

	return $classes;
}

add_filter( 'brix_block_classes[type:button]', 'agncy_brix_button_builder_block_class', 10, 2 );


/**
 * Add the required inline styles for the button builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function agncy_brix_styles_process_button_frontend_block( $block_style, $block, $block_selector ) {
	$label_size              = isset( $block->data ) && isset( $block->data->agncy_btn_size ) ? $block->data->agncy_btn_size : '';
	$label_font_size         = isset( $block->data ) && isset( $block->data->agncy_btn_label_font_size ) ? $block->data->agncy_btn_label_font_size : '';
	$has_style_custom = ( $label_size == 'custom' && $label_font_size );

	if ( $has_style_custom ) {
		$block_style .= $block_selector . ' .brix-block-button {';
			if ( $label_size == 'custom' ) {
				if ( $label_font_size ) {
					if ( is_numeric( $label_font_size ) ) {
						$label_font_size .= 'px';
					}

					$block_style .= 'font-size:' . $label_font_size . ';';
				}
			}
		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:button]', 'agncy_brix_styles_process_button_frontend_block', 10, 3 );