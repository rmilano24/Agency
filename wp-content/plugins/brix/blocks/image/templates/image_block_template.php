<?php

$source          = '';
$image           = '';
$image_url       = '';
$image_alt       = '';
$alignment       = '';
$img             = '';
$display_caption = '';
$caption         = '';
$caption_class	 = '';
$image_size 	 = 'full';

if ( isset( $data->data->source ) && ! empty( $data->data->source ) ) {
	$source = $data->data->source;
}

if ( isset( $data->data->image ) && ! empty( $data->data->image ) ) {
	$image = $data->data->image;
}

if ( isset( $data->data->image_url ) && ! empty( $data->data->image_url ) ) {
	$image_url = $data->data->image_url;
}

if ( isset( $data->data->image_alignment ) && ! empty( $data->data->image_alignment ) ) {
	$alignment = $data->data->image_alignment;
}

if ( $source == 'image' ) {
	// Media library image display caption option
	if ( isset( $data->data->image_media_caption ) && ! empty( $data->data->image_media_caption ) ) {
		$display_caption = $data->data->image_media_caption;
	}

	if ( ! empty( $image->desktop[1]->image_size ) ) {
		$image_size = $image->desktop[1]->image_size;
	}

	if ( ! empty( $image->desktop[1]->id ) ) {
		// $img = brix_get_image( $image->desktop[1]->id, $image_size );
		$img = $image->desktop[1]->id;

		$caption_content = brix_get_image_caption( $image->desktop[1]->id );
		$custom_caption = isset( $data->data->image_media_custom_caption ) ? $data->data->image_media_custom_caption : '';

		if ( $display_caption && $display_caption == 'media' ) {
			$caption_class = 'brix-i-fcd';
			$caption = esc_html( $caption_content );
		}
		else if ( $display_caption && $display_caption == 'custom' && ! empty( $custom_caption ) ) {
			$caption = $custom_caption;
		}
	}
}
else if ( $source == 'external' ) {
	// External image display caption option
	if ( isset( $data->data->image_external_caption ) && ! empty( $data->data->image_external_caption ) ) {
		$display_caption = $data->data->image_external_caption;
	}

	if ( ! empty( $image_url ) ) {
		$img = $image_url;
	}

	$custom_caption = isset( $data->data->image_external_custom_caption ) ? $data->data->image_external_custom_caption : '';

	if ( $display_caption && $display_caption == 'custom' && ! empty( $custom_caption ) ) {
		$caption = $custom_caption;
	}
}

if ( ! empty( $img ) ) {
	$link = isset( $data->data->link ) ? $data->data->link : '';

	echo brix_get_lazy_image_markup( $img, array(
		'classes'       => array( 'brix-block-image-img' ),
		'size'          => $image_size,
		'link'          => $link,
		'caption'       => $caption,
		'caption_class' => $caption_class
	) );
}