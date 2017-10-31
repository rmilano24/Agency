<?php

/**
 * Custom styles for the feature box builder block in Agncy.
 *
 * @since 1.0.0
 * @param array $styles The feature box builder block styles.
 * @return array
 */
function agncy_brix_styles_feature_box_block_styles( $styles ) {
	$styles['agncy-boxed-icon'] = __( 'Boxed icon', 'agncy' );

	return $styles;
}
add_filter( 'brix_block_styles[type:feature_box]', 'agncy_brix_styles_feature_box_block_styles' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function agncy_brix_styles_feature_box_style_fields( $fields ) {
	$fields[] = array(
		'handle' => 'agncy_feature_box_boxed_icon_style',
		'type' => 'select',
		'label' => __( 'Boxed icon style', 'agncy' ),
		'config' => array(
			'data' => array(
				'filled' => __( 'Filled', 'agncy' ),
				'ghost' => __( 'Ghost', 'agncy' )
			),
			'visible' => array( '_style' => 'agncy-boxed-icon' ),
		)
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:feature_box]', 'agncy_brix_styles_feature_box_style_fields' );

/**
 * Add the required inline styles for the feature box and icon icon style builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function agncy_brix_styles_process_feature_box_icon_style_frontend_block( $block_style, $block, $block_selector ) {
	$color            = isset( $block->data ) && isset( $block->data->decoration->icon->color ) ? $block->data->decoration->icon->color : '';
	$boxed_icon_style = isset( $block->data->agncy_feature_box_boxed_icon_style ) ? $block->data->agncy_feature_box_boxed_icon_style : '';
	$card_bgc         = isset( $block->data->agncy_feature_box_card_color ) ? $block->data->agncy_feature_box_card_color : '';

	if ( isset( $block->data->_style ) && $color && $block->data->_style == 'agncy-boxed-icon' ) {
		if ( $boxed_icon_style == 'filled' ) {

			$block_style .= $block_selector . ' .brix-icon-wrapper {';
				$block_style .= 'background-color:' . $color . ';';
			$block_style .= '}';

			$block_style .= $block_selector . ' .brix-icon-wrapper .brix-icon:not( [data-src*="linea"] ) * {';
				$block_style .= 'fill:white;';
			$block_style .= '}';
			$block_style .= $block_selector . ' .brix-icon-wrapper .brix-icon[data-src*="linea"] * {';
				$block_style .= 'stroke:white;';
			$block_style .= '}';

		} else if ( $boxed_icon_style == 'ghost' ) {
			$block_style .= $block_selector . ' .brix-icon-wrapper {';
				$block_style .= 'border:2px solid ' . $color . ';';
			$block_style .= '}';
			$block_style .= $block_selector . ' .brix-icon-wrapper .brix-icon:not( [data-src*="linea"] ) * {';
				$block_style .= 'fill:' . $color . ';';
			$block_style .= '}';
			$block_style .= $block_selector . ' .brix-icon-wrapper .brix-icon[data-src*="linea"] * {';
				$block_style .= 'stroke:' . $color . ';';
			$block_style .= '}';
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:feature_box]', 'agncy_brix_styles_process_feature_box_icon_style_frontend_block', 10, 3 );