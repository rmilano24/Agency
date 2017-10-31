<?php

$shortcode_type = $data->data->woo_products_type;
$per_page       = $data->data->per_page;
$columns        = $data->data->columns;
$order_by       = $data->data->order_by;
$order          = $data->data->order;
$ids            = $data->data->ids;
$skus           = $data->data->skus;
$multiple       = $data->data->multiple;

if ( $shortcode_type == 'related_products' ) {
	$columns = '4';
}

if ( ! empty( $shortcode_type ) ) {
	if ( $shortcode_type != 'products' ) {
		$params[] = 'per_page="' . $per_page . '"';
	}

	if ( $shortcode_type == 'products' ) {
		if ( $multiple == 'ids' && ! empty( $ids ) ) {
			$params[] = 'ids="' . $ids . '"';
		} else if ( $multiple == 'skus' && ! empty( $skus ) ) {
			$params[] = 'skus="' . $skus . '"';
		}
	}
}

$params[] = 'columns="' . $columns . '"';

if ( ! empty( $order_by ) && ! empty( $order ) ) {
	$params[] = 'orderby="' . $order_by . '"';
	$params[] = 'order="' . $order . '"';
}

if ( ! empty( $shortcode_type ) ) {
	echo '[' . $shortcode_type . ' ' . implode( $params, ' ' ) . ']';
}