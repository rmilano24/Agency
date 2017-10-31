<?php

$attribute = $data->data->attribute;
$filter    = $data->data->filter;
$per_page  = $data->data->per_page;
$columns   = $data->data->columns;
$order_by  = $data->data->order_by;
$order     = $data->data->order;

$params    = array();

if ( ! empty( $attribute ) ) {
	$params[] = 'attribute="' . $attribute . '"';
}
if ( ! empty( $filter ) ) {
	$params[] = 'filter="' . $filter . '"';
}
if ( ! empty( $per_page ) ) {
	$params[] = 'per_page="' . $per_page . '"';
}

$params[] = 'columns="' . $columns . '"';

if ( ! empty( $order_by ) && ! empty( $order ) ) {
	$params[] = 'orderby="' . $order_by . '"';
	$params[] = 'order="' . $order . '"';
}

echo '[product_attribute ' . implode( $params, ' ' ) . ']';