<?php

$office_id = isset( $data->data->office_id ) && $data->data->office_id ? $data->data->office_id : 0;

if ( ! $office_id ) {
	return;
}

$image    = isset( $data->data->image ) ? $data->data->image       : false;
$media    = isset( $data->data->media ) ? $data->data->media       : false;

$image_size = isset( $data->data->image_size ) ? $data->data->image_size       : 'full';

$map      = $media && $media == 'map';
$image    = $media && $media == 'image';

$address  = isset( $data->data->address ) ? $data->data->address   : false;
$contacts = isset( $data->data->contacts ) ? $data->data->contacts : false;
$page_id  = isset( $data->data->page_id ) ? $data->data->page_id   : false;
$zoom     = isset( $data->data->map_zoom ) ? $data->data->map_zoom : 14;

if ( $map ) {
	$latlong = get_post_meta( $office_id, 'latlong', true );
	$api_key = ev_get_option( 'google_maps_api_key' );
	$error = '';
	$error_class = '';

	if ( ! $api_key ) {
		$error_class = 'agncy-missing-key';
		$error  = __( 'Please configure Google Maps API Key in the Options > Global options section of your admin area.', 'agncy-companion-plugin' );
	}

	if ( $latlong ) {

		$marker = '/img/marker.svg';

		if ( file_exists( get_template_directory() . $marker ) ) {
			$marker = get_template_directory_uri() . $marker;
		}
		else {
			$marker = '';
		}

		printf( '<div class="agncy-office-map %s" data-latlong="%s" data-zoom="%s" data-marker="%s">%s</div>',
			esc_attr( $error_class ),
			esc_attr( $latlong ),
			esc_attr( $zoom ),
			esc_attr( $marker ),
			esc_html( $error )
		);
	}
}

if ( $image ) {
	$featured_image = ev_get_featured_image( $image_size, $office_id );

	printf( '<img class="agncy-office-image" src="%s">',
		esc_attr( $featured_image )
	);
}

printf( '<p class="agncy-office-title">%s</p>',
	esc_html( get_the_title( $office_id ) )
);

if ( $address ) {
	$address = get_post_meta( $office_id, 'address', true );

	if ( $address ) {
		printf( '<div class="agncy-office-address">%s</div>',
			wp_kses_post( wpautop( $address ) )
		);
	}
}

if ( $contacts ) {
	$meta = get_post_meta( $office_id, 'meta', true );

	if ( ! empty( $meta ) ) {
		echo '<ul class="agncy-office-meta">';

		foreach ( $meta as $m ) {
			$icon = '';
			$value = esc_html( $m[ 'value' ] );

			if ( $m['meta'] == 'email' ) {
				$icon = agncy_companion_load_svg( 'assets/img/email.svg' );
				$value = sprintf( '<a href="mailto:%s" target="_blank">%s</a>', esc_html( $value ), esc_html( $value ) );
			}
			if ( $m['meta'] == 'phone' ) {
				$icon = agncy_companion_load_svg( 'assets/img/phone.svg' );
			}
			if ( $m['meta'] == 'fax' ) {
				$icon = agncy_companion_load_svg( 'assets/img/fax.svg' );
			}
			if ( $m['meta'] == 'skype' ) {
				$icon = agncy_companion_load_svg( 'assets/img/skype.svg' );
				$value = sprintf( '<a href="skype:%s?chat">%s</a>', esc_html( $value ), esc_html( $value ) );
			}

			printf( '<li class="agncy-office-meta-%s">%s %s</li>',
				esc_attr( $m[ 'meta' ] ),
				$icon,
				$value
			);
		}

		echo '</ul>';
	}
}

if ( $page_id ) {
	printf( '<p><a class="agncy-office-view-link" href="%s">%s</a></p>',
		wp_kses_post( get_permalink( $page_id ) ),
		esc_html( __( 'View office', 'agncy-companion-plugin' ) )
	);
}