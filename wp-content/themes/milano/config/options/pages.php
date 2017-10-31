<?php

/**
 * Create a meta box for the 'Options' of the 'page' post type.
 *
 * @since 1.0.0
 */
function agncy_page_templates_options() {
	if ( ! function_exists( 'ev_fw' ) ) {
		return;
	}

	$fields = array(
		array(
			'type'   => 'group',
			'handle' => '_general',
			'label'  => __( 'Options', 'agncy' ),
			'fields' => agncy_page_options_fields()
		),
		array(
			'type'   => 'group',
			'handle' => '_sidebar',
			'label'  => __( 'Sidebar', 'agncy' ),
			'fields' => agncy_sidebar_fields( 'page' )
		)
	);

	$fields = apply_filters( 'agncy_page_templates_options', $fields );

	ev_fw()->admin()->add_meta_box( 'ag_page_options', __( 'Options', 'agncy' ), 'page', $fields );
}

add_action( 'init', 'agncy_page_templates_options' );

/**
 * Page option fields.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_page_options_fields() {
	$fields = apply_filters( 'agncy_page_options_fields', array() );

	$fields[] = array(
		'handle' => 'agncy_options_divider',
		'type' => 'divider',
		'text' => __( 'Options', 'agncy' )
	);

	$fields[] = array(
		'handle' => 'agncy_before_title',
		'type' => 'text',
		'label' => __( 'Subtitle', 'agncy' ),
		'help' => __( 'Placed before the page title.', 'agncy' ),
		'config' => array(
			'full' => true
		),
	);

	$fields[] = array(
		'handle' => 'agncy_disable_page_header',
		'type' => 'checkbox',
		'label' => __( 'Disable page header', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'controller' => true
		),
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
		'handle' => 'agncy_full_width_page_content',
		'type' => 'checkbox',
		'label' => __( 'Full width page content', 'agncy' ),
		'help' => __( 'Extend the page content width.', 'agncy' ),
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

	return $fields;
}

/**
 * Projects slideshow options.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_projects_slideshow_options( $fields ) {
	$fields = array();

	$fields[] = array(
		'type' => 'group',
		'label' => __( 'Project slides', 'agncy' ),
		'handle' => 'agncy_projects',
		'fields' => array(
			array(
				'handle' => 'agncy_projects_divider',
				'type' => 'divider',
				'text' => __( 'Project slides', 'agncy' ),
			),
			array(
				'type' => 'bundle',
				'handle' => 'agncy_slide',
				'label' => array(
					'type' => 'hidden',
					'text' => __( 'Slide', 'agncy' ),
				),
				'repeatable' => array(
					'sortable' => true
				),
				'fields' => array(
					array(
						'handle' => 'ref_id',
						'type' => 'select',
						'label' => __( 'Project', 'agncy' ),
						'help' => __( 'The project the slide will link to.', 'agncy' ),
						'config' => array(
							'data' => agncy_get_projects_for_select()
						)
					),
					array(
						'handle' => 'use_video',
						'type' => 'select',
						'label' => __( 'Use video', 'agncy' ),
						'help' => __( 'If the project has a video, use it instead of the project image.', 'agncy' ),
						'config' => array(
							'data' => array(
								'0' => __( 'No', 'agncy' ),
								'1' => __( 'Yes', 'agncy' ),
							)
						)
					),
					array(
						'handle' => 'image_size',
						'type' => 'select',
						'label' => __( 'Image size', 'agncy' ),
						'help' => __( 'Image size for the project image.', 'agncy' ),
						'config' => array(
							'data' => agncy_get_image_sizes_for_select()
						)
					),
					array(
						'handle' => 'title',
						'type' => 'text',
						'label' => __( 'Title', 'agncy' ),
						'help' => __( 'The title of the slide.', 'agncy' ),
						'config' => array(
							'full' => true
						)
					),
					array(
						'handle' => 'subtitle',
						'type' => 'text',
						'label' => __( 'Subtitle', 'agncy' ),
						'help' => __( 'The subtitle of the slide.', 'agncy' ),
						'config' => array(
							'full' => true
						)
					),
					array(
						'handle' => 'text',
						'type' => 'textarea',
						'label' => __( 'Text', 'agncy' ),
						'help' => __( 'The text of the slide.', 'agncy' ),
						'config' => array(
							'full' => true,
							'rich' => true,
							'rows' => 4
						)
					),
					array(
						'handle' => 'button_text',
						'type' => 'text',
						'help' => __( 'Filling this text will make a button show up linking to the project.', 'agncy' ),
						'label' => __( 'Call-to-action text', 'agncy' ),
						'config' => array(
							'full' => true
						)
					),
				)
			)
		)
	);

	$fields[] = array(
		'type' => 'group',
		'label' => __( 'Page options', 'agncy' ),
		'handle' => 'agncy_projects_page_options',
		'fields' => array(
			array(
				'handle' => 'agncy_full_width_page_content',
				'type' => 'checkbox',
				'label' => __( 'Full width page content', 'agncy' ),
				'help' => __( 'Extend the page content width.', 'agncy' ),
				'config' => array(
					'style' => array( 'switch', 'small' ),
					'controller' => true
				),
			)
		)
	);

	return $fields;
}

add_filter( "ev[post_type:page][template:template-projects-slideshow.php][metabox:ag_page_options]", 'agncy_projects_slideshow_options' );