<?php

/**
 * Declare the options to manage the page preloader component.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_global_options( $fields ) {
	$fields[] = array(
		'handle' => 'agncy_global',
		'label' => __( 'Global options', 'agncy' ),
		'type' => 'group',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Global options', 'agncy' ),
			),
			array(
				'handle' => 'google_maps_api_key',
				'label' => __( 'Google Maps API key', 'agncy' ),
				'help' => sprintf(
					__( 'Refer to <a href="%s" target="_blank">this page</a> to get an API key.', 'agncy' ),
					esc_attr( 'https://developers.google.com/maps/documentation/javascript/get-api-key' )
				),
				'type' => 'text',
				'config' => array(
					'full' => true
				)
			),
		)
	);

	return $fields;
}

add_filter( 'agncy_global_fields', 'agncy_global_options' );