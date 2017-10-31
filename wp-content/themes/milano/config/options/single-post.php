<?php

/**
 * Create a meta box for the 'Options' of the 'post' post type.
 *
 * @since 1.0.0
 */
function agncy_post_templates_options() {
	if ( ! function_exists( 'ev_fw' ) ) {
		return;
	}

	$fields = array(
		array(
			'type'   => 'group',
			'handle' => '_general',
			'label'  => __( 'Options', 'agncy' ),
			'fields' => agncy_post_options_fields()
		),
		array(
			'type'   => 'group',
			'handle' => '_sidebar',
			'label'  => __( 'Sidebar', 'agncy' ),
			'fields' => agncy_sidebar_fields( 'post' )
		)
	);

	ev_fw()->admin()->add_meta_box( 'ag_post_options', __( 'Options', 'agncy' ), 'post', $fields );
}

add_action( 'init', 'agncy_post_templates_options' );

/**
 * Return a set of options for the single post.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_post_options_fields() {
	$fields = array();

	$fields[] = array(
		'handle' => 'agncy_post_options_divider',
		'type' => 'divider',
		'text' => __( 'Post options', 'agncy' )
	);

	$fields[] = array(
		'handle' => 'agncy_full_width_image',
		'type' => 'checkbox',
		'label' => __( 'Full width featured image', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'controller' => true
		),
	);

	$fields[] = array(
		'handle' => 'agncy_page_featured_image_sizes',
		'type' => 'select',
		'label' => __( 'Image size', 'agncy' ),
		'config' => array(
			'data' => function_exists( 'agncy_get_image_sizes_for_select' ) ? agncy_get_image_sizes_for_select() : array()
		)
	);

	$fields[] = array(
		'handle' => 'agncy_post_share',
		'type' => 'checkbox',
		'label' => __( 'Display share block', 'agncy' ),
		'help' => __( 'Check this to display share links.', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' )
		)
	);

	$fields[] = array(
		'handle' => 'agncy_post_navigation',
		'type' => 'checkbox',
		'label' => __( 'Display posts navigation', 'agncy' ),
		'help' => __( 'Check this to display navigation links to the previous and next posts.', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' )
		),
		'default' => '1'
	);

	$fields[] = array(
		'handle' => 'agncy_post_related',
		'type' => 'checkbox',
		'label' => __( 'Display related posts', 'agncy' ),
		'help' => __( 'Check this to display the related posts.', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' )
		)
	);

	return $fields;
}