<?php

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_list_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'size',
		'type' => 'select',
		'label' => __( 'List elements size', 'brix' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Default', 'brix' ),
				'micro'   => __( 'Micro', 'brix' ),
				'small'   => __( 'Small', 'brix' ),
				'large'   => __( 'Large', 'brix' ),
				'custom'  => __( 'Custom', 'brix' )
			)
		)
	);

		$fields[] = array(
			'handle' => 'font_size',
			'label'  => __( 'List elements font size', 'brix' ),
			'type'   => 'text',
			'config' => array(
				'visible' => array( 'size' => 'custom' )
			)
		);

	$fields[] = array(
		'handle' => 'vertical_spacing',
		'label'  => __( 'Vertical spacing', 'brix' ),
		'help'   => __( 'Vertical spacing between list elements.', 'brix' ),
		'type'   => 'text',
	);

	$fields[] = array(
		'handle' => 'icon_spacing',
		'label'  => __( 'Icon spacing', 'brix' ),
		'help'   => __( 'Horizontal spacing between icon and list element text.', 'brix' ),
		'type'   => 'text',
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:list]', 'brix_styles_list_block_fields' );

/**
 * Add the required inline styles for the list builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_list_frontend_block( $block_style, $block, $block_selector ) {
	$font_size        = isset( $block->data ) && isset( $block->data->font_size ) ? $block->data->font_size : '';
	$vertical_spacing = isset( $block->data ) && isset( $block->data->vertical_spacing ) ? $block->data->vertical_spacing : '';
	$icon_spacing     = isset( $block->data ) && isset( $block->data->icon_spacing ) ? $block->data->icon_spacing : '';
	$icon_position    = isset( $block->data ) && isset( $block->data->icon_position ) ? $block->data->icon_position : 'left';

	if ( $font_size ) {
		if ( is_numeric( $font_size ) ) {
			$font_size .= 'px';
		}

		$block_style .= $block_selector . ' ul li {';
			$block_style .= 'font-size:' . $font_size . ';';
		$block_style .= '}';
	}

	if ( $vertical_spacing ) {
		if ( is_numeric( $vertical_spacing ) ) {
			$vertical_spacing .= 'px';
		}

		$block_style .= $block_selector . ' ul li + li {';
			$block_style .= 'margin-top:' . $vertical_spacing . ';';
		$block_style .= '}';
	}

	if ( $icon_spacing ) {
		if ( is_numeric( $icon_spacing ) ) {
			$icon_spacing .= 'px';
		}

		if ( $icon_position == 'left' ) {
			$block_style .= $block_selector . ' ul i {';
				$block_style .= 'margin-right:' . $icon_spacing . ';';
			$block_style .= '}';
		}

		if ( $icon_position == 'right' ) {
			$block_style .= $block_selector . ' ul li span + i {';
				$block_style .= 'margin-left:' . $icon_spacing . ';';
			$block_style .= '}';
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:list]', 'brix_process_list_frontend_block', 10, 3 );