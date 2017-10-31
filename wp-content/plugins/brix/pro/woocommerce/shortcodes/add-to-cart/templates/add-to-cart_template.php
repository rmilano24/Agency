<?php

$type   = $data->data->type;
$style  = $data->data->inline_style;
$params = '';

if ( $type == 'id' ) {
	$params = 'id="' . $data->data->id . '"';
} else if ( $type == 'sku' ) {
	$params = 'sku="' . $data->data->sku . '"';
}

if ( ! empty( $style ) ) {
	$params .= ' style="' . $style . '"';
}

echo '[add_to_cart ' . $params . ']';
