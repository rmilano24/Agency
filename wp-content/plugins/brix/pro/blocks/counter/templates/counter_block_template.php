<?php

$counter_value  = '';
$counter_prefix = '';
$counter_suffix = '';
$counter_label  = '';
$counter_icon	= '';

if ( isset( $data->data->value ) && ! empty( $data->data->value ) ) {
	$counter_value = $data->data->value;
}

$counter_value = apply_filters( 'brix_counter_builder_block_counter_value', $counter_value, $data->data );

if ( isset( $data->data->prefix ) && ! empty( $data->data->prefix ) ) {
	$counter_prefix = $data->data->prefix;
}

if ( isset( $data->data->suffix ) && ! empty( $data->data->suffix ) ) {
	$counter_suffix = $data->data->suffix;
}

if ( isset( $data->data->label ) && ! empty( $data->data->label ) ) {
	$counter_label = $data->data->label;
}

if ( isset( $data->data->icon ) && ! empty( $data->data->icon ) ) {
	$counter_icon = $data->data->icon;
}

if ( $counter_icon && isset( $counter_icon->icon ) && ! empty( $counter_icon->icon ) ) {
	echo '<div class="brix-counter-icon-wrapper">';
		echo brix_get_decoration_icon( $counter_icon );
	echo '</div>';
}

if ( $counter_value !== '' ) {
	echo '<p class="brix-counter-value-wrapper">';

		if ( $counter_prefix ) {
			printf( '<span class="brix-counter-prefix">%s</span>', esc_html( $counter_prefix ) );
		}

		printf( '<span class="brix-counter-value" data-value="%s"></span>', esc_attr( $counter_value ) );

		if ( $counter_suffix ) {
			printf( '<span class="brix-counter-suffix">%s</span>', esc_html( $counter_suffix ) );
		}

	echo '</p>';

	if ( $counter_label ) {
		printf( '<p class="brix-counter-label">%s</p>', esc_html( $counter_label ) );
	}
}