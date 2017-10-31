<?php

/**
 * Creation of the search options page.
 *
 * @since 1.0.0
 */
function agncy_search_option_page() {
    if ( ! function_exists( 'ev_fw' ) ) {
        return;
    }

	$args = array(
		'group' => 'agncy',
		'vertical' => true
	);

	$fields = array();

	$search_fields = array(
		'type'   => 'group',
		'label'  => __( 'Global', 'agncy' ),
		'handle' => '_global_search',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Search', 'agncy' )
			),
		)
	);

	$search_fields['fields'] = apply_filters( 'agncy_search_fields', $search_fields['fields'] );
	$fields[] = $search_fields;

	ev_fw()->admin()->add_submenu_page( 'agncy', 'agncy-search', __( 'Search', 'agncy' ), $fields, $args );
}

add_action( 'init', 'agncy_search_option_page' );

/**
 * Search fields.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_search_fields( $fields ) {
	if ( ! function_exists( 'agncy_create_sidebar_options' ) ) {
		return $fields;
	}

	$fields = array_merge( $fields, agncy_create_sidebar_options( 'search_sidebar' ) );

	return $fields;
}

add_filter( 'agncy_search_fields', 'agncy_search_fields' );