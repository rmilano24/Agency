<?php

$data = $field->config( 'data' );
$style = $field->config( 'style' );
$value = $field->value();

brix_radio( $field->handle(), $data, $value, $style );