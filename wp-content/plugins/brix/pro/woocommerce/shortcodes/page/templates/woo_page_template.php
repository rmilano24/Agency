<?php

$params = '';

if ( $data->data->woo_page_type == 'my_account' ) {
	$order_count = '15';

	if ( ! empty( $data->data->order_count ) ) {
		$order_count = $data->data->order_count;
	}

	$params = ' order_count="' . $order_count . '"';
}

echo '[woocommerce_' . $data->data->woo_page_type . $params . ']';