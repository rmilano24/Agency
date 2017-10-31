<?php

$number     = $data->data->number;
$columns    = $data->data->columns;
$hide_empty = $data->data->hide_empty;
$parent     = $data->data->parent;
$ids        = $data->data->ids;
$order_by   = $data->data->order_by;
$order      = $data->data->order;

$params     = array();

if ( ! empty( $number ) ) {
	$params[] = 'number="' . $number . '"';
}

$params[] = 'columns="' . $columns . '"';
$params[] = 'hide_empty="' . $hide_empty . '"';

if ( $parent != '' ) {
	$params[] = 'parent="' . $parent . '"';
}
if ( ! empty( $ids ) ) {
	$params[] = 'ids="' . $ids . '"';
}

if ( ! empty( $order_by ) && ! empty( $order ) ) {
	$params[] = 'orderby="' . $order_by . '"';
	$params[] = 'order="' . $order . '"';
}

echo '[product_categories ' . implode( $params, ' ' ) . ']';