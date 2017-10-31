<?php

$block_label       = '';
$block_link        = '';
$button_markup     = '';

$block_label         = isset( $data->data ) && isset( $data->data->label ) ? $data->data->label : false;
$block_link          = isset( $data->data ) && isset( $data->data->link ) ? $data->data->link : false;
$block_icon          = isset( $data->data ) && isset( $data->data->icon ) ? $data->data->icon : false;
$block_icon_position = isset( $data->data ) && isset( $data->data->icon_position ) ? $data->data->icon_position : 'left';

$button_class      = 'brix-block-button';
$block_link->class = $button_class;

if ( ! empty( $block_label ) ) {
	if ( $block_icon_position == 'left' ) {
		$button_markup .= brix_get_decoration( $block_icon );
	}

	$button_markup .= '<span class="brix-block-button-label">';
		$button_markup .= esc_html( $block_label );
	$button_markup .= '</span>';

	if ( $block_icon_position == 'right' ) {
		$button_markup .= brix_get_decoration( $block_icon );
	}

	$button_markup = sprintf( '<span class="brix-block-button-inner-wrapper">%s</span>', $button_markup );

	if ( ! empty( $block_link->url ) ) {
		$button_markup = brix_link( $block_link, $button_markup, false );
	}
	else {
		$button_markup = sprintf( '<span class="%s">%s</span>', esc_attr( $button_class ), $button_markup );
	}
}

if ( $button_markup ) {
	echo $button_markup;
}