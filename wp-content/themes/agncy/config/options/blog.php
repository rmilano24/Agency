<?php

/**
 * Creation of the blog options page.
 *
 * @since 1.0.0
 */
function agncy_blog_option_page() {
    if ( ! function_exists( 'ev_fw' ) ) {
        return;
    }

	$args = array(
		'group' => 'agncy',
		'vertical' => true
	);

	$fields = array();

	$index_fields = array(
		'type'   => 'group',
		'label'  => __( 'Index', 'agncy' ),
		'handle' => '_blog_index',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Index', 'agncy' )
			),
		)
	);

	$index_fields['fields'] = apply_filters( 'agncy_post_index_fields', $index_fields['fields'] );
	$fields[] = $index_fields;

	$archives_fields = array(
		'type'   => 'group',
		'label'  => __( 'Archives', 'agncy' ),
		'handle' => '_blog_archive',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Archives', 'agncy' )
			),
		)
	);

	$archives_fields['fields'] = apply_filters( 'agncy_post_archives_fields', $archives_fields['fields'] );
	$fields[] = $archives_fields;

	$single_fields = array(
		'type'   => 'group',
		'label'  => __( 'Single', 'agncy' ),
		'handle' => '_blog_single',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Single', 'agncy' )
			),
		)
	);

	$single_fields['fields'] = apply_filters( 'agncy_post_single_fields', $single_fields['fields'] );
	$fields[] = $single_fields;

	ev_fw()->admin()->add_submenu_page( 'agncy', 'agncy-blog', __( 'Blog', 'agncy' ), $fields, $args );
}

add_action( 'init', 'agncy_blog_option_page' );

/**
 * Blog index fields.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_post_index_fields( $fields ) {
	if ( ! function_exists( 'agncy_create_sidebar_options' ) ) {
		return $fields;
	}

	$fields = array_merge( $fields, agncy_create_loop_options( 'index_loop' ) );
	$fields = array_merge( $fields, agncy_create_sidebar_options( 'index_sidebar' ) );

	return $fields;
}

add_filter( 'agncy_post_index_fields', 'agncy_post_index_fields' );

/**
 * Blog archives fields.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_post_archives_fields( $fields ) {
	if ( ! function_exists( 'agncy_create_sidebar_options' ) ) {
		return $fields;
	}

	$fields = array_merge( $fields, agncy_create_loop_options( 'archives_loop' ) );
	$fields = array_merge( $fields, agncy_create_sidebar_options( 'archives_sidebar' ) );

	return $fields;
}

add_filter( 'agncy_post_archives_fields', 'agncy_post_archives_fields' );

/**
 * Blog single fields.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_post_single_fields( $fields ) {
	if ( ! function_exists( 'agncy_create_sidebar_options' ) ) {
		return $fields;
	}

	$fields = array_merge( $fields, agncy_create_sidebar_options( 'post_single_sidebar' ) );

	return $fields;
}

add_filter( 'agncy_post_single_fields', 'agncy_post_single_fields' );