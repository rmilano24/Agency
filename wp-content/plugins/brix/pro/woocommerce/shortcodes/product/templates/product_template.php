<?php
$type   = $data->data->type;
$params = '';

if ( $type == 'id' ) {
	$params = 'id="' . $data->data->id . '"';
} else if ( $type == 'sku' ) {
	$params = 'sku="' . $data->data->sku . '"';
}

echo '[product ' . $params . ']';
