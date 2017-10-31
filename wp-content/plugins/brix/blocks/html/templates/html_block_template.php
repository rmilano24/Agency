<?php

$block_content = '';
$type = '';

if ( isset( $data->data->type ) ) {
	$type = $data->data->type;

	if ( $type === 'snippet' && isset( $data->data->snippet ) ) {
		$block_content = $data->data->snippet;
		$block_content = do_shortcode( $block_content );
	}
	elseif ( $type === 'template' && isset( $data->data->template ) ) {
		$block_content = brix_get_template_part( $data->data->template, array(), false );
	}
}

if ( $type && ! empty( $block_content ) ) {
	echo $block_content;
}