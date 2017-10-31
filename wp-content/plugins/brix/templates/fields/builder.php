<?php

$data = array();
$value = $field->value();
$handle = $field->handle();

$builder = BrixBuilder::instance();

if ( ! empty( $value ) ) {
	$data = json_decode( $value );
}

$builder->render_admin( $data, $handle );