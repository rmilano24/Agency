<?php

$category = $data->data->category;
$per_page = $data->data->per_page;
$columns  = $data->data->columns;
$order_by = $data->data->order_by;
$order    = $data->data->order;
$params   = array();

if ( ! empty( $category ) ) {
	$params[] = 'category="' . $category . '"';
}

if ( ! empty( $per_page ) ) {
	$params[] = 'per_page="' . $per_page . '"';
}
if ( ! empty( $columns ) ) {
	$params[] = 'columns="' . $columns . '"';
}
if ( ! empty( $order_by ) && ! empty( $order ) ) {
	$params[] = 'orderby="' . $order_by . '"';
	$params[] = 'order="' . $order . '"';
}

echo '[product_category ' . implode( $params, ' ' ) . ']';