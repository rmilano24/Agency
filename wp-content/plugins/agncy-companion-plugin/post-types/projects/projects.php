<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/* Projects helpers. */
require_once dirname( __FILE__ ) . '/helpers.php';

/* Custom fields. */
require_once dirname( __FILE__ ) . '/dominant_color_field.php';

/**
 * Register the Job Custom Post Type.
 *
 * @since 1.0.0
 */
function agncy_project() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'agncy-companion-plugin' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'agncy-companion-plugin' ),
		'menu_name'             => __( 'Projects', 'agncy-companion-plugin' ),
		'name_admin_bar'        => __( 'Project', 'agncy-companion-plugin' ),
		'archives'              => __( 'Project Archives', 'agncy-companion-plugin' ),
		'attributes'            => __( 'Project Attributes', 'agncy-companion-plugin' ),
		'parent_item_colon'     => __( 'Parent Project:', 'agncy-companion-plugin' ),
		'all_items'             => __( 'All Projects', 'agncy-companion-plugin' ),
		'add_new_item'          => __( 'Add New Item', 'agncy-companion-plugin' ),
		'add_new'               => __( 'Add New Project', 'agncy-companion-plugin' ),
		'new_item'              => __( 'New Project', 'agncy-companion-plugin' ),
		'edit_item'             => __( 'Edit Project', 'agncy-companion-plugin' ),
		'update_item'           => __( 'Update Project', 'agncy-companion-plugin' ),
		'view_item'             => __( 'View Project', 'agncy-companion-plugin' ),
		'view_items'            => __( 'View Projects', 'agncy-companion-plugin' ),
		'search_items'          => __( 'Search Project', 'agncy-companion-plugin' ),
		'not_found'             => __( 'Not found', 'agncy-companion-plugin' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'agncy-companion-plugin' ),
		'featured_image'        => __( 'Project Image', 'agncy-companion-plugin' ),
		'set_featured_image'    => __( 'Set project image', 'agncy-companion-plugin' ),
		'remove_featured_image' => __( 'Remove project image', 'agncy-companion-plugin' ),
		'use_featured_image'    => __( 'Use as project image', 'agncy-companion-plugin' ),
		'insert_into_item'      => __( 'Insert into item', 'agncy-companion-plugin' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'agncy-companion-plugin' ),
		'items_list'            => __( 'Items list', 'agncy-companion-plugin' ),
		'items_list_navigation' => __( 'Items list navigation', 'agncy-companion-plugin' ),
		'filter_items_list'     => __( 'Filter items list', 'agncy-companion-plugin' ),
	);

    $rewrite = array(
		'slug'                  => apply_filters( 'agncy_project_slug', 'project' ),
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);

	$args = array(
		'label'                 => __( 'Project', 'agncy-companion-plugin' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-images-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
        'show_in_rest'          => true,
	);

	register_post_type( 'agncy_project', $args );
}

add_action( 'init', 'agncy_project', 1 );

/**
 * Register the custom taxonomy for projects.
 *
 * @since 1.0.0
 */
function agncy_create_project_category() {
	$labels = array(
		'name'                       => _x( 'Project Categories', 'Taxonomy General Name', 'agncy-companion-plugin' ),
		'singular_name'              => _x( 'Project Category', 'Taxonomy Singular Name', 'agncy-companion-plugin' ),
		'menu_name'                  => __( 'Project Categories', 'agncy-companion-plugin' ),
		'all_items'                  => __( 'All Items', 'agncy-companion-plugin' ),
		'parent_item'                => __( 'Parent Item', 'agncy-companion-plugin' ),
		'parent_item_colon'          => __( 'Parent Item:', 'agncy-companion-plugin' ),
		'new_item_name'              => __( 'New Item Name', 'agncy-companion-plugin' ),
		'add_new_item'               => __( 'Add New Item', 'agncy-companion-plugin' ),
		'edit_item'                  => __( 'Edit Item', 'agncy-companion-plugin' ),
		'update_item'                => __( 'Update Item', 'agncy-companion-plugin' ),
		'view_item'                  => __( 'View Item', 'agncy-companion-plugin' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'agncy-companion-plugin' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'agncy-companion-plugin' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'agncy-companion-plugin' ),
		'popular_items'              => __( 'Popular Items', 'agncy-companion-plugin' ),
		'search_items'               => __( 'Search Items', 'agncy-companion-plugin' ),
		'not_found'                  => __( 'Not Found', 'agncy-companion-plugin' ),
		'no_terms'                   => __( 'No items', 'agncy-companion-plugin' ),
		'items_list'                 => __( 'Items list', 'agncy-companion-plugin' ),
		'items_list_navigation'      => __( 'Items list navigation', 'agncy-companion-plugin' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);

	register_taxonomy( 'agncy_project_category', array( 'agncy_project' ), $args );
}

add_action( 'init', 'agncy_create_project_category', 1 );

/**
 * Create a meta box for the project data.
 *
 * @since 1.0.0
 */
function agncy_project_data_meta_box() {
	ev_fw()->admin()->add_meta_box( 'agncy_project_data', __( 'Data', 'agncy-companion-plugin' ), 'agncy_project', array(
		array(
			'type' => 'agncy_dominant_color',
			'handle' => 'color',
			'label' => __( 'Color', 'agncy-companion-plugin' ),
			'help' => __( 'Accent color for the project. Suggested colors are extracted from the selected project image.', 'agncy-companion-plugin' ),
		),
		array(
			'type' => 'radio',
			'handle' => 'layout',
			'label' => __( 'Layout', 'agncy-companion-plugin' ),
			'help' => __( 'The layout for the project page.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => 'graphic',
				'controller' => true,
				'data' => agncy_get_project_layouts_for_select()
			)
		),
		array(
			'handle' => 'layout_skin',
			'label' => __( 'Header skin', 'agncy-companion-plugin' ),
			'type' => 'select',
			'config' => array(
				'data' => array(
					'dark' => __( 'Dark text', 'agncy-companion-plugin' ),
					'light' => __( 'Light text', 'agncy-companion-plugin' ),
				),
				'visible' => array( 'layout' => 'b' ),
			)
		),
		array(
			'handle' => 'layout_full_width_page_content',
			'type' => 'checkbox',
			'label' => __( 'Full width page content', 'agncy-companion-plugin' ),
			'help' => __( 'Extend the page content width.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => array( 'switch', 'small' ),
			),
		),
		array(
			'handle' => 'header_featured_image_size',
			'label' => __( 'Featured image size', 'agncy-companion-plugin' ),
			'help' => __( 'Image size used for the project image used in the header.', 'agncy-companion-plugin' ),
			'type' => 'select',
			'config' => array(
				'data' => agncy_get_image_sizes_for_select(),
			)
		),
		array(
			'type' => 'text',
			'handle' => 'video',
			'label' => __( 'Video', 'agncy-companion-plugin' ),
			'help' => __( 'URL to a video of the project.', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true
			)
		),
		array(
			'type' => 'checkbox',
			'handle' => 'use_video',
			'label' => __( 'Use video instead of featured image', 'agncy-companion-plugin' ),
			'help' => __( 'In the single project page, use the video instead of the featured image.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => array( 'small', 'switch' )
			)
		),
		array(
			'type' => 'bundle',
			'handle' => 'meta',
			'label' => __( 'Meta data', 'agncy-companion-plugin' ),
			'help' => __( 'A set of key/value pairs.', 'agncy-companion-plugin' ),
			'repeatable' => array(
				'sortable' => true
			),
			'config' => array(
				'style' => 'grid',
			),
			'fields' => array(
				array(
					'type' => 'select',
					'handle' => 'meta',
					'label' => array(
						'text' => __( 'Meta', 'agncy-companion-plugin' ),
						'type' => 'hidden'
					),
					'size' => 'large',
					'config' => array(
						'data' => agncy_projects_metas()
					)
				),
				array(
					'type' => 'text',
					'handle' => 'value',
					'label' => array(
						'text' => __( 'Value', 'agncy-companion-plugin' ),
						'type' => 'hidden'
					),
					'size' => 'large',
					'config' => array(
						'full' => true
					)
				)
			)
		),
		array(
			'type' => 'select',
			'handle' => 'subtitle',
			'label' => __( 'Subtitle', 'agncy-companion-plugin' ),
			'help' => __( 'Displayed under the project title.', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => array(
					'0'          => __( 'Do not display', 'agncy-companion-plugin' ),
					'categories' => __( 'Project categories', 'agncy-companion-plugin' ),
					'metas'      => __( 'Project metas', 'agncy-companion-plugin' ),
					'text'       => __( 'Text', 'agncy-companion-plugin' )
				),
				'controller' => true
			)
		),
		array(
			'type' => 'multiple_select',
			'handle' => 'subtitle_metas',
			'label' => __( 'Subtitle metas', 'agncy-companion-plugin' ),
			'help' => __( 'Multiple metas will be separated by commas.', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => agncy_projects_metas(),
				'visible' => array( 'subtitle' => 'metas' )
			)
		),
		array(
			'type' => 'text',
			'handle' => 'subtitle_text',
			'label' => __( 'Subtitle text', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true,
				'visible' => array( 'subtitle' => 'text' )
			)
		),
		array(
			'handle' => 'agncy_project_share',
			'type' => 'checkbox',
			'label' => __( 'Display share block', 'agncy-companion-plugin' ),
			'help' => __( 'Check this to display share links.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => array( 'switch', 'small' )
			),
			'default' => 0
		),
		array(
			'handle' => 'agncy_project_navigation',
			'type' => 'checkbox',
			'label' => __( 'Display projects navigation', 'agncy-companion-plugin' ),
			'help' => __( 'Check this to display navigation links to the previous and next projects.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => array( 'switch', 'small' )
			),
			'default' => '1'
		),
		array(
			'type' => 'text',
			'handle' => 'url',
			'label' => __( 'External URL', 'agncy-companion-plugin' ),
			'help' => __( 'External page to link to for the project. Filling this field, will prevent users from accessing the standard single project page.', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true
			)
		),
	) );
}

add_action( 'admin_init', 'agncy_project_data_meta_box', 1 );

/**
 * Normalize project meta data.
 *
 * @since 1.0.0
 * @param integer $post_id The post ID.
 */
function agncy_project_save_meta_data( $post_id ) {
	if ( ! ev_user_can_save( $post_id, 'ev_meta_box' ) ) {
		return;
	}

	global $post_type;

	if ( $post_type != 'agncy_project' ) {
		return;
	}

	$project_meta = (array) get_post_meta( $post_id, 'meta', true );
	$metas = agncy_projects_metas();

	foreach ( $metas as $pm_key => $pm_label ) {
		delete_post_meta( $post_id, $pm_key );
	}


	foreach ( $project_meta as $pm ) {
		if ( isset( $pm[ 'meta' ] ) ) {
			add_post_meta( $post_id, $pm[ 'meta' ], $pm[ 'value' ] );
		}
	}

}

add_action( 'save_post', 'agncy_project_save_meta_data', 100 );