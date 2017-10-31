<?php

$icon = isset( $data->data ) && isset( $data->data->icon ) ? $data->data->icon : false;
$link = isset( $data->data ) && isset( $data->data->link ) ? $data->data->link : false;

$decoration_html = '';

if ( $icon ) {
	$decoration_html = brix_get_decoration( $icon );
}

if ( $decoration_html ) {
	brix_link( $link, $decoration_html );
}