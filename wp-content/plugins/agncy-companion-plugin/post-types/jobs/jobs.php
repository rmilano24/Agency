<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/* Jobs helpers. */
require_once dirname( __FILE__ ) . '/helpers.php';

/**
 * Register the Job Custom Post Type.
 *
 * @since 1.0.0
 */
function agncy_job() {
	$labels = array(
		'name'                  => _x( 'Jobs', 'Post Type General Name', 'agncy-companion-plugin' ),
		'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'agncy-companion-plugin' ),
		'menu_name'             => __( 'Jobs', 'agncy-companion-plugin' ),
		'name_admin_bar'        => __( 'Job', 'agncy-companion-plugin' ),
		'archives'              => __( 'Job Archives', 'agncy-companion-plugin' ),
		'attributes'            => __( 'Job Attributes', 'agncy-companion-plugin' ),
		'parent_item_colon'     => __( 'Parent Job:', 'agncy-companion-plugin' ),
		'all_items'             => __( 'All Jobs', 'agncy-companion-plugin' ),
		'add_new_item'          => __( 'Add New Item', 'agncy-companion-plugin' ),
		'add_new'               => __( 'Add New Job', 'agncy-companion-plugin' ),
		'new_item'              => __( 'New Job', 'agncy-companion-plugin' ),
		'edit_item'             => __( 'Edit Job', 'agncy-companion-plugin' ),
		'update_item'           => __( 'Update Job', 'agncy-companion-plugin' ),
		'view_item'             => __( 'View Job', 'agncy-companion-plugin' ),
		'view_items'            => __( 'View Jobs', 'agncy-companion-plugin' ),
		'search_items'          => __( 'Search Job', 'agncy-companion-plugin' ),
		'not_found'             => __( 'Not found', 'agncy-companion-plugin' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'agncy-companion-plugin' ),
		'featured_image'        => __( 'Featured Image', 'agncy-companion-plugin' ),
		'set_featured_image'    => __( 'Set featured image', 'agncy-companion-plugin' ),
		'remove_featured_image' => __( 'Remove featured image', 'agncy-companion-plugin' ),
		'use_featured_image'    => __( 'Use as featured image', 'agncy-companion-plugin' ),
		'insert_into_item'      => __( 'Insert into item', 'agncy-companion-plugin' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'agncy-companion-plugin' ),
		'items_list'            => __( 'Items list', 'agncy-companion-plugin' ),
		'items_list_navigation' => __( 'Items list navigation', 'agncy-companion-plugin' ),
		'filter_items_list'     => __( 'Filter items list', 'agncy-companion-plugin' ),
	);

    $rewrite = array(
		'slug'                  => apply_filters( 'agncy_job_slug', 'job' ),
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);

	$args = array(
		'label'                 => __( 'Job', 'agncy-companion-plugin' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-media-spreadsheet',
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

	register_post_type( 'agncy_job', $args );
}

add_action( 'init', 'agncy_job', 1 );

/**
 * Create a meta box for the project data.
 *
 * @since 1.0.0
 */
function agncy_job_data_meta_box() {
	ev_fw()->admin()->add_meta_box( 'agncy_job_data', __( 'Data', 'agncy-companion-plugin' ), 'agncy_job', array(
		array(
			'type' => 'text',
			'handle' => 'url',
			'label' => __( 'External URL', 'agncy-companion-plugin' ),
			'help' => __( 'External page to link to for the job opening. Filling this field, will prevent users from accessing the standard single job page.', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true
			)
		),
	) );
}

add_action( 'admin_init', 'agncy_job_data_meta_box', 1 );