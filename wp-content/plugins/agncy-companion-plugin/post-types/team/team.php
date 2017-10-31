<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/* Team helpers. */
require_once dirname( __FILE__ ) . '/helpers.php';

/**
 * Register the Team Member Custom Post Type.
 *
 * @since 1.0.0
 */
function agncy_team_member() {
	$labels = array(
		'name'                  => _x( 'Team Members', 'Post Type General Name', 'agncy-companion-plugin' ),
		'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'agncy-companion-plugin' ),
		'menu_name'             => __( 'Team', 'agncy-companion-plugin' ),
		'name_admin_bar'        => __( 'Team Member', 'agncy-companion-plugin' ),
		'archives'              => __( 'Team Member Archives', 'agncy-companion-plugin' ),
		'attributes'            => __( 'Team Member Attributes', 'agncy-companion-plugin' ),
		'parent_item_colon'     => __( 'Parent Team Member:', 'agncy-companion-plugin' ),
		'all_items'             => __( 'All Team Members', 'agncy-companion-plugin' ),
		'add_new_item'          => __( 'Add New Item', 'agncy-companion-plugin' ),
		'add_new'               => __( 'Add New Team Member', 'agncy-companion-plugin' ),
		'new_item'              => __( 'New Team Member', 'agncy-companion-plugin' ),
		'edit_item'             => __( 'Edit Team Member', 'agncy-companion-plugin' ),
		'update_item'           => __( 'Update Team Member', 'agncy-companion-plugin' ),
		'view_item'             => __( 'View Team Member', 'agncy-companion-plugin' ),
		'view_items'            => __( 'View Team Members', 'agncy-companion-plugin' ),
		'search_items'          => __( 'Search Team Member', 'agncy-companion-plugin' ),
		'not_found'             => __( 'Not found', 'agncy-companion-plugin' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'agncy-companion-plugin' ),
		'featured_image'        => __( 'Team Member Photo', 'agncy-companion-plugin' ),
		'set_featured_image'    => __( 'Set Team Member photo', 'agncy-companion-plugin' ),
		'remove_featured_image' => __( 'Remove Team Member photo', 'agncy-companion-plugin' ),
		'use_featured_image'    => __( 'Use as Team Member photo', 'agncy-companion-plugin' ),
		'insert_into_item'      => __( 'Insert into item', 'agncy-companion-plugin' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'agncy-companion-plugin' ),
		'items_list'            => __( 'Items list', 'agncy-companion-plugin' ),
		'items_list_navigation' => __( 'Items list navigation', 'agncy-companion-plugin' ),
		'filter_items_list'     => __( 'Filter items list', 'agncy-companion-plugin' ),
	);

    $rewrite = array(
		'slug'                  => apply_filters( 'agncy_team_member_slug', 'team' ),
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);

	$args = array(
		'label'                 => __( 'Team Member', 'agncy-companion-plugin' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-groups',
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

	register_post_type( 'agncy_team_member', $args );
}

add_action( 'init', 'agncy_team_member', 1 );

/**
 * Create a meta box for the project data.
 *
 * @since 1.0.0
 */
function agncy_team_member_data_meta_box() {
	ev_fw()->admin()->add_meta_box( 'agncy_team_member_data', __( 'Data', 'agncy-companion-plugin' ), 'agncy_team_member', array(
		array(
			'type' => 'text',
			'handle' => 'role',
			'label' => __( 'Role', 'agncy-companion-plugin' ),
			'help' => __( 'The role of the team member in the company.', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true
			)
		),
		array(
			'type' => 'checkbox',
			'handle' => 'has_bio',
			'label' => __( 'Bio', 'agncy-companion-plugin' ),
			'help' => __( 'In team blocks, display a link to the single team member page.', 'agncy-companion-plugin' ),
			'config' => array(
				'style' => array( 'switch', 'small' ),
				'full' => true
			),
			'default' => true
		),
		array(
			'type' => 'image',
			'handle' => 'image',
			'label' => __( 'Image', 'agncy-companion-plugin' ),
			'help' => __( 'Used in team loops.', 'agncy-companion-plugin' ),
		),
		// array(
		// 	'type' => 'bundle',
		// 	'handle' => 'meta',
		// 	'label' => __( 'Social links', 'agncy-companion-plugin' ),
		// 	'help' => __( 'A set of social links for the team member.', 'agncy-companion-plugin' ),
		// 	'repeatable' => array(
		// 		'sortable' => true
		// 	),
		// 	'config' => array(
		// 		'style' => 'grid',
		// 	),
		// 	'fields' => array(
		// 		array(
		// 			'type' => 'select',
		// 			'handle' => 'service',
		// 			'label' => array(
		// 				'text' => __( 'Service', 'agncy-companion-plugin' ),
		// 				'type' => 'hidden'
		// 			),
		// 			'size' => 'large',
		// 			'config' => array(
		// 				'data' => agncy_team_member_social_networks()
		// 			)
		// 		),
		// 		array(
		// 			'type' => 'text',
		// 			'handle' => 'url',
		// 			'label' => array(
		// 				'text' => __( 'URL', 'agncy-companion-plugin' ),
		// 				'type' => 'hidden'
		// 			),
		// 			'size' => 'large',
		// 			'config' => array(
		// 				'full' => true
		// 			)
		// 		)
		// 	)
		// )
	) );
}

add_action( 'admin_init', 'agncy_team_member_data_meta_box', 1 );