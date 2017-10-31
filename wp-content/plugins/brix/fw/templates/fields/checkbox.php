<?php
$style = $field->config( 'style' );
$args = array();

brix_checkbox( $field->handle(), $field->value(), $style, $args );