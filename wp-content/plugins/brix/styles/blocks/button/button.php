<?php

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_button_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'button_shape',
		'type' => 'select',
		'label' => __( 'Button shape', 'brix' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Squared borders', 'brix' ),
				'rounded' => __( 'Rounded border', 'brix' )
			),
			'visible' => array( '_style' => 'default' )
		)
	);

		$fields[] = array(
			'handle' => 'shape_border',
			'label'  => __( 'Shape border radius', 'brix' ),
			'type'   => 'text',
			'config' => array(
				'visible' => array( 'button_shape' => 'rounded' )
			)
		);

	$fields[] = array(
		'handle' => 'size',
		'type' => 'select',
		'label' => __( 'Label size', 'brix' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Default', 'brix' ),
				'micro'   => __( 'Micro', 'brix' ),
				'small'   => __( 'Small', 'brix' ),
				'large'   => __( 'Large', 'brix' ),
				'full'    => __( 'Full width', 'brix' ),
				'custom'  => __( 'Custom', 'brix' )
			),
		)
	);

		$fields[] = array(
			'handle' => 'label_font_size',
			'label'  => __( 'Label font size', 'brix' ),
			'type'   => 'text',
			'config' => array(
				'visible' => array( 'size' => 'custom' )
			)
		);

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
			),
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
			),
		)
	);

	$fields[] = array(
		'handle' => 'background_color',
		'label'  => __( 'Background color', 'brix' ),
		'type'   => 'color',
		'config' => array(
			'multiple' => array(
				'standard' => '',
				'active' => 'Active',
				'hover' => 'Hover'
			),
			'visible' => array( '_style' => 'default' )
		)
	);

	$fields[] = array(
		'handle' => 'text_color',
		'label'  => __( 'Text color', 'brix' ),
		'type'   => 'color',
		'config' => array(
			'multiple' => array(
				'standard' => '',
				'active' => 'Active',
				'hover' => 'Hover'
			),
			'visible' => array( '_style' => 'default' )
		)
	);

	$fields[] = array(
		'handle' => 'border_style',
		'label'  => __( 'Border style', 'brix' ),
		'type'   => 'select',
		'config' => array(
			'controller' => true,
			'data' => array(
				'none'   => __( 'No border', 'brix' ),
				'solid'  => __( 'Solid', 'brix' ),
				'dashed' => __( 'Dashed', 'brix' ),
				'dotted' => __( 'Dotted', 'brix' ),
				'double' => __( 'Double', 'brix' ),
				'groove' => __( '3D grooved. The effect depends on the border-color value', 'brix' ),
				'ridge'  => __( '3D ridged. The effect depends on the border-color value', 'brix' ),
				'inset'  => __( '3D inset. The effect depends on the border-color value', 'brix' ),
				'outset' => __( '3D outset. The effect depends on the border-color value', 'brix' ),
			),
			'visible' => array( '_style' => 'default' )
		)
	);

		$fields[] = array(
			'handle' => 'border_size',
			'label'  => __( 'Border size', 'brix' ),
			'type'   => 'text',
			'config' => array(
				'visible' => array(
					'border_style' => 'solid,dashed,dotted,double,groove,ridge,inset,outset'
				)
			)
		);

		$fields[] = array(
			'handle' => 'border_color',
			'label'  => __( 'Border color', 'brix' ),
			'type'   => 'color',
			'config' => array(
				'visible' => array(
					'border_style' => 'solid,dashed,dotted,double,groove,ridge,inset,outset'
				),
				'multiple' => array(
					'standard' => '',
					'active' => 'Active',
					'hover' => 'Hover'
				)
			)
		);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:button]', 'brix_styles_button_block_fields' );


/**
 * Add the required inline styles for the button builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_button_frontend_block( $block_style, $block, $block_selector ) {
	$button_style              = isset( $block->data ) && isset( $block->data->_style ) ? $block->data->_style : 'default';
	$background_color_standard = isset( $block->data ) && isset( $block->data->background_color->standard->color ) ? $block->data->background_color->standard->color : '';
	$background_color_active   = isset( $block->data ) && isset( $block->data->background_color->active->color ) ? $block->data->background_color->active->color : '';
	$background_color_hover    = isset( $block->data ) && isset( $block->data->background_color->hover->color ) ? $block->data->background_color->hover->color : '';

	$text_color_standard       = isset( $block->data ) && isset( $block->data->text_color->standard->color ) ? $block->data->text_color->standard->color : '';
	$text_color_active         = isset( $block->data ) && isset( $block->data->text_color->active->color ) ? $block->data->text_color->active->color : '';
	$text_color_hover          = isset( $block->data ) && isset( $block->data->text_color->hover->color ) ? $block->data->text_color->hover->color : '';

	$border_color_standard     = isset( $block->data ) && isset( $block->data->border_color->standard->color ) ? $block->data->border_color->standard->color : '';
	$border_color_active       = isset( $block->data ) && isset( $block->data->border_color->active->color ) ? $block->data->border_color->active->color : '';
	$border_color_hover        = isset( $block->data ) && isset( $block->data->border_color->hover->color ) ? $block->data->border_color->hover->color : '';

	$border_style              = isset( $block->data ) && isset( $block->data->border_style ) ? $block->data->border_style : '';
	$border_size               = isset( $block->data ) && isset( $block->data->border_size ) ? $block->data->border_size : '';

	$button_shape              = isset( $block->data ) && isset( $block->data->button_shape ) ? $block->data->button_shape : '';
	$button_shape_border       = isset( $block->data ) && isset( $block->data->shape_border ) ? $block->data->shape_border : '';

	$label_size                = isset( $block->data ) && isset( $block->data->size ) ? $block->data->size : '';
	$label_font_size           = isset( $block->data ) && isset( $block->data->label_font_size ) ? $block->data->label_font_size : '';

	$text_transform            = isset( $block->data ) && isset( $block->data->text_transform ) ? $block->data->text_transform : '';
	$font_weight               = isset( $block->data ) && isset( $block->data->font_weight ) ? $block->data->font_weight : '';

	$has_style_default = $background_color_standard
		|| $text_color_standard
		|| $border_color_standard
		|| ( $border_style != 'none' && $border_size )
		|| ( $button_shape == 'rounded' && $button_shape_border )
		|| ( $label_size == 'custom' && $label_font_size )
		|| $text_transform
		|| $font_weight;
	$has_style_hover   = $background_color_hover || $text_color_hover || $border_color_hover;
	$has_style_active  = $background_color_active || $text_color_active || $border_color_active;

	// Default state
	if ( $has_style_default ) {
		$block_style .= $block_selector . ' .brix-block-button {';

			if ( $button_style == 'default' ) {
				if ( $background_color_standard ) {
					$block_style .= 'background-color:' . $background_color_standard . ';';
				}

				if ( $text_color_standard ) {
					$block_style .= 'color:' . $text_color_standard . ';';
				}

				if ( $border_color_standard ) {
					$block_style .= 'border-color:' . $border_color_standard . ';';
				}

				if ( $border_style && $border_style != 'none' ) {
					if ( $border_size ) {
						if ( is_numeric( $border_size ) ) {
							$border_size .= 'px';
						}

						$block_style .= 'border-style:' . $border_style . ';';
						$block_style .= 'border-width:' . $border_size . ';';
					}
				}
			}

			if ( $button_shape == 'rounded' ) {
				if ( $button_shape_border ) {
					if ( is_numeric( $button_shape_border ) ) {
						$button_shape_border .= 'px';
					}

					$block_style .= 'border-radius:' . $button_shape_border . ';';
				}
			}

			if ( $label_size == 'custom' ) {
				if ( $label_font_size ) {
					if ( is_numeric( $label_font_size ) ) {
						$label_font_size .= 'px';
					}

					$block_style .= 'font-size:' . $label_font_size . ';';
				}
			}

			if ( $text_transform ) {
				$block_style .= 'text-transform:' . $text_transform . ';';
			}

			if ( $font_weight ) {
				$block_style .= 'font-weight:' . $font_weight . ';';
			}

		$block_style .= '}';
	}

	// Active state
	if ( $has_style_active && $button_style == 'default' ) {
		$block_style .= $block_selector . ' .brix-block-button:active {';

			if ( $background_color_active ) {
				$block_style .= 'background-color:' . $background_color_active . ';';
			}

			if ( $text_color_active ) {
				$block_style .= 'color:' . $text_color_active . ';';
			}

			if ( $border_color_active ) {
				$block_style .= 'border-color:' . $border_color_active . ';';
			}

		$block_style .= '}';
	}

	// Hover state
	if ( $has_style_hover && $button_style == 'default' ) {
		$block_style .= $block_selector . ' .brix-block-button:hover {';

			if ( $background_color_hover ) {
				$block_style .= 'background-color:' . $background_color_hover . ';';
			}

			if ( $text_color_hover ) {
				$block_style .= 'color:' . $text_color_hover . ';';
			}

			if ( $border_color_hover ) {
				$block_style .= 'border-color:' . $border_color_hover . ';';
			}

		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:button]', 'brix_styles_process_button_frontend_block', 10, 3 );

/**
 * Add a specific CSS class to the button block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_button_builder_block_style_class( $classes, $data ) {
	$size  = isset( $data ) && isset( $data->size ) ? $data->size : 'default';
	$shape = isset( $data ) && isset( $data->button_shape ) ? $data->button_shape : 'default';

	$classes[] = 'brix-button-size-' . $size;
	$classes[] = 'brix-button-shape-' . $shape;

	return $classes;
}

add_filter( 'brix_block_classes[type:button]', 'brix_button_builder_block_style_class', 10, 2 );
