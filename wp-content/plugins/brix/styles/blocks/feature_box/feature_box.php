<?php

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_feature_box_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'icon_spacing',
		'type' => 'text',
		'label' => __( 'Icon spacing', 'brix' ),
		'help' => __( 'Space between icon and content', 'brix' )
	);

		$fields[] = array(
			'handle' => 'icon_box_shape',
			'type' => 'select',
			'label' => __( 'Icon box shape', 'brix' ),
			'config' => array(
				'controller' => true,
				'visible' => array( '_style' => 'boxed' ),
				'data' => array(
					'default' => __( 'Squared borders', 'brix' ),
					'rounded' => __( 'Rounded border', 'brix' ),
					'circle' => __( 'Circle', 'brix' )
				)
			)
		);

			$fields[] = array(
				'handle' => 'icon_box_padding',
				'label'  => __( 'Icon box padding', 'brix' ),
				'type'   => 'select',
				'config' => array(
					'controller' => true,
					'visible' => array( '_style' => 'boxed' ),
					'data' => array(
						'default' => __( 'Default', 'brix' ),
						'small'   => __( 'Small', 'brix' ),
						'large'   => __( 'Large', 'brix' ),
						'custom'  => __( 'Custom', 'brix' )
					)
				)
			);

				$fields[] = array(
					'handle' => 'icon_box_padding_custom',
					'label'  => __( 'Icon box padding size', 'brix' ),
					'type'   => 'text',
					'config' => array(
						'visible' => array( 'icon_box_padding' => 'custom' ),
					)
				);

			$fields[] = array(
				'handle' => 'icon_box_background_color',
				'label'  => __( 'Icon box background color', 'brix' ),
				'type'   => 'color',
				'config' => array(
					'visible' => array( '_style' => 'boxed' ),
				)
			);

			$fields[] = array(
				'handle' => 'icon_box_shape_border',
				'label'  => __( 'Icon box shape border radius', 'brix' ),
				'type'   => 'text',
				'config' => array(
					'visible' => array( 'icon_box_shape' => 'rounded' )
				)
			);

			$fields[] = array(
				'handle' => 'icon_box_border_style',
				'label'  => __( 'Icon box border style', 'brix' ),
				'type'   => 'select',
				'config' => array(
					'controller' => true,
					'visible' => array( '_style' => 'boxed' ),
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
					)
				)
			);

				$fields[] = array(
					'handle' => 'border_size',
					'label'  => __( 'Icon box border size', 'brix' ),
					'type'   => 'text',
					'config' => array(
						'visible' => array(
							'icon_box_border_style' => 'solid,dashed,dotted,double,groove,ridge,inset,outset'
						)
					)
				);

				$fields[] = array(
					'handle' => 'border_color',
					'label'  => __( 'Icon box border color', 'brix' ),
					'type'   => 'color',
					'config' => array(
						'visible' => array(
							'icon_box_border_style' => 'solid,dashed,dotted,double,groove,ridge,inset,outset'
						),
					)
				);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:feature_box]', 'brix_styles_feature_box_block_fields' );

/**
 * Custom styles for the feature box builder block.
 *
 * @since 1.0.0
 * @param array $styles The feature box builder block styles.
 * @return array
 */
function brix_styles_feature_box_block_styles( $styles ) {
	$styles['default'] = __( 'Default', 'brix' );
	$styles['boxed']   = __( 'Boxed icon', 'brix' );

	return $styles;
}

add_filter( 'brix_block_styles[type:feature_box]', 'brix_styles_feature_box_block_styles' );

/**
 * Add the required inline styles for the feature box builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_feature_box_frontend_block( $block_style, $block, $block_selector ) {
	$style = isset( $block->data ) && isset( $block->data->_style ) ? $block->data->_style : 'default';

	$layout                    = isset( $block->data ) && isset( $block->data->layout ) ? $block->data->layout : 'left_fixed';
	$icon_spacing              = isset( $block->data ) && isset( $block->data->icon_spacing ) ? $block->data->icon_spacing : '';

	$icon_box_shape            = isset( $block->data ) && isset( $block->data->icon_box_shape ) ? $block->data->icon_box_shape : '';
	$icon_box_background_color = isset( $block->data ) && isset( $block->data->icon_box_background_color ) ? $block->data->icon_box_background_color->color : '';
	$icon_box_shape_border     = isset( $block->data ) && isset( $block->data->icon_box_shape_border ) ? $block->data->icon_box_shape_border : '';
	$icon_box_border_style     = isset( $block->data ) && isset( $block->data->icon_box_border_style ) ? $block->data->icon_box_border_style : '';
	$icon_box_border_size      = isset( $block->data ) && isset( $block->data->border_size ) ? $block->data->border_size : '';
	$icon_box_border_color     = isset( $block->data ) && isset( $block->data->border_color ) ? $block->data->border_color->color : '';

	$icon_box_padding          = isset( $block->data ) && isset( $block->data->icon_box_padding ) ? $block->data->icon_box_padding : 'default';
	$icon_box_padding_custom   = isset( $block->data ) && isset( $block->data->icon_box_padding_custom ) ? $block->data->icon_box_padding_custom : '';

	$icon_box_style = $icon_box_background_color
		|| $icon_box_border_color
		|| ( $icon_box_border_style != 'none' && $icon_box_border_size )
		|| ( $icon_box_shape != 'default' && $icon_box_shape_border )
		|| $icon_box_padding_custom;

	if ( $icon_box_style ) {
		$block_style .= $block_selector . '.brix-section-column-block-style-boxed .brix-icon-wrapper {';
			$block_style .= 'margin-top: 0;';
		$block_style .= '}';

		if ( $style === 'boxed' ) {
			$block_style .= $block_selector . ' .brix-icon-wrapper {';

			if ( $icon_box_background_color ) {
				$block_style .= 'background-color:' . $icon_box_background_color . ';';
			}

			if ( $icon_box_border_color ) {
				$block_style .= 'border-color:' . $icon_box_border_color . ';';
			}

			if ( $icon_box_border_style && $icon_box_border_style != 'none' ) {
				if ( $icon_box_border_size ) {
					if ( is_numeric( $icon_box_border_size ) ) {
						$icon_box_border_size .= 'px';
					}

					$block_style .= 'border-style:' . $icon_box_border_style . ';';
					$block_style .= 'border-width:' . $icon_box_border_size . ';';
				}
			}

			if ( $icon_box_shape == 'rounded' ) {
				if ( $icon_box_shape_border ) {
					if ( is_numeric( $icon_box_shape_border ) ) {
						$icon_box_shape_border .= 'px';
					}

					$block_style .= 'border-radius:' . $icon_box_shape_border . ';';
				}
			} else if ( $icon_box_shape == 'circle' ) {
				$block_style .= 'border-radius: 50%;';
			}

			if ( $icon_box_padding == 'default' ) {
				$block_style .= 'padding: 1em;';
			}
			else if ( $icon_box_padding == 'small' ) {
				$block_style .= 'padding: .5em;';
			}
			else if ( $icon_box_padding == 'large' ) {
				$block_style .= 'padding: 1.5em;';
			}
			else if ( $icon_box_padding == 'custom' && $icon_box_padding_custom != '' ) {
				if ( is_numeric( $icon_box_padding_custom ) ) {
					$icon_box_padding_custom .= 'px';
				}

				$block_style .= 'padding:' . $icon_box_padding_custom . ';';
			}

			$block_style .= '}';
		}
	};

	if ( $icon_spacing ) {
		if ( is_numeric( $icon_spacing ) ) {
			$icon_spacing .= 'px';
		}

		$block_selector .= '.brix-section-column-block-feature_box';

		if ( $layout == 'left_fixed' ) {
			$block_style .= $block_selector . '.brix-block-feature-box-layout-left_fixed .brix-block-feature-box-content-wrapper {';
				$block_style .= 'padding-left:' . $icon_spacing . ';';
			$block_style .= '}';
		}

		if ( $layout == 'right_fixed' ) {
			$block_style .= $block_selector . '.brix-block-feature-box-layout-right_fixed .brix-block-feature-box-content-wrapper {';
				$block_style .= 'padding-right:' . $icon_spacing . ';';
			$block_style .= '}';
		}

		if ( $layout == 'left_inline' ) {
			$block_style .= $block_selector . '.brix-block-feature-box-layout-left_inline .brix-icon-wrapper {';
				$block_style .= 'margin-right:' . $icon_spacing . ';';
			$block_style .= '}';
		}

		if ( $layout == 'right_inline' ) {
			$block_style .= $block_selector . '.brix-block-feature-box-layout-right_inline .brix-icon-wrapper {';
				$block_style .= 'margin-left:' . $icon_spacing . ';';
			$block_style .= '}';
		}

		if ( $layout == 'centered' ) {
			$block_style .= $block_selector . '.brix-block-feature-box-layout-centered .brix-icon-wrapper + .brix-block-feature-box-content-wrapper {';
				$block_style .= 'margin-top:' . $icon_spacing . ';';
			$block_style .= '}';
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:feature_box]', 'brix_styles_process_feature_box_frontend_block', 10, 3 );