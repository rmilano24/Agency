<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/* Offices helpers. */
require_once dirname( __FILE__ ) . '/helpers.php';

/**
 * Register the Office Custom Post Type.
 *
 * @since 1.0.0
 */
function agncy_office() {
	$labels = array(
		'name'                  => _x( 'Offices', 'Post Type General Name', 'agncy-companion-plugin' ),
		'singular_name'         => _x( 'Office', 'Post Type Singular Name', 'agncy-companion-plugin' ),
		'menu_name'             => __( 'Offices', 'agncy-companion-plugin' ),
		'name_admin_bar'        => __( 'Office', 'agncy-companion-plugin' ),
		'archives'              => __( 'Office Archives', 'agncy-companion-plugin' ),
		'attributes'            => __( 'Office Attributes', 'agncy-companion-plugin' ),
		'parent_item_colon'     => __( 'Parent Office:', 'agncy-companion-plugin' ),
		'all_items'             => __( 'All Offices', 'agncy-companion-plugin' ),
		'add_new_item'          => __( 'Add New Item', 'agncy-companion-plugin' ),
		'add_new'               => __( 'Add New Office', 'agncy-companion-plugin' ),
		'new_item'              => __( 'New Office', 'agncy-companion-plugin' ),
		'edit_item'             => __( 'Edit Office', 'agncy-companion-plugin' ),
		'update_item'           => __( 'Update Office', 'agncy-companion-plugin' ),
		'view_item'             => __( 'View Office', 'agncy-companion-plugin' ),
		'view_items'            => __( 'View Offices', 'agncy-companion-plugin' ),
		'search_items'          => __( 'Search Office', 'agncy-companion-plugin' ),
		'not_found'             => __( 'Not found', 'agncy-companion-plugin' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'agncy-companion-plugin' ),
		'featured_image'        => __( 'Office Image', 'agncy-companion-plugin' ),
		'set_featured_image'    => __( 'Set office image', 'agncy-companion-plugin' ),
		'remove_featured_image' => __( 'Remove office image', 'agncy-companion-plugin' ),
		'use_featured_image'    => __( 'Use as office image', 'agncy-companion-plugin' ),
		'insert_into_item'      => __( 'Insert into item', 'agncy-companion-plugin' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'agncy-companion-plugin' ),
		'items_list'            => __( 'Items list', 'agncy-companion-plugin' ),
		'items_list_navigation' => __( 'Items list navigation', 'agncy-companion-plugin' ),
		'filter_items_list'     => __( 'Filter items list', 'agncy-companion-plugin' ),
	);

    $rewrite = array(
		'slug'                  => apply_filters( 'agncy_office_slug', 'office' ),
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);

	$args = array(
		'label'                 => __( 'Office', 'agncy-companion-plugin' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-building',
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

	register_post_type( 'agncy_office', $args );
}

add_action( 'init', 'agncy_office', 1 );

/**
 * Offices taxonomy used for internal linking of the agency CPTs.
 *
 * @since 1.0.0
 */
function agncy_office_create_linking_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Offices', 'Taxonomy General Name', 'agncy-companion-plugin' ),
		'singular_name'              => _x( 'Offices', 'Taxonomy Singular Name', 'agncy-companion-plugin' ),
		'menu_name'                  => __( 'Offices', 'agncy-companion-plugin' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'show_in_quick_edit'		 => false
	);

	register_taxonomy( 'agncy_office_taxonomy', array( 'agncy_office', 'agncy_job' ), $args );
}

add_action( 'init', 'agncy_office_create_linking_taxonomy', 1 );

/**
 * Save a corresponding office category whenever we save a new office CPT.
 *
 * @since 1.0.0
 * @param integer $post_id The post ID.
 */
function agncy_save_office_category( $post_id ) {
	if ( 'agncy_office' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( empty( $_POST ) ) {
		return;
	}

	$office_title = get_the_title( $post_id );
	$term = term_exists( $office_title, 'agncy_office_taxonomy' );
	$term_id = 0;

	if ( ! $term ) {
		$term = wp_insert_term( $office_title, 'agncy_office_taxonomy' );
		$term_id = $term[ 'term_id' ];
	}
	else {
		$term = get_term( $term[ 'term_id' ] );
		$term_id = $term->term_id;
	}

	wp_set_object_terms( $post_id, array( $term_id ), 'agncy_office_taxonomy' );
}

add_action( 'save_post', 'agncy_save_office_category' );

/**
 * Delete the corresponding office category whenever we delete an office CPT.
 *
 * @since 1.0.0
 * @param integer $post_id The post ID.
 */
function agncy_delete_office_category( $post_id ) {
	global $post_type;

	if ( 'agncy_office' !== $post_type ) {
		return;
	}

	$office_title = get_the_title( $post_id );
	$term = term_exists( $office_title, 'agncy_office_taxonomy' );

	if ( $term ) {
		wp_delete_term( $term[ 'term_id' ], 'agncy_office_taxonomy' );
	}
}

add_action( 'before_delete_post', 'agncy_delete_office_category' );

/**
 * Create a meta box for the project data.
 *
 * @since 1.0.0
 */
function agncy_office_data_meta_box() {
	ev_fw()->admin()->add_meta_box( 'agncy_office_data', __( 'Data', 'agncy-companion-plugin' ), 'agncy_office', array(
		array(
			'type'   => 'textarea',
			'handle' => 'address',
			'label'  => __( 'Address', 'agncy-companion-plugin' ),
			'help' => __( 'Full address of the office.', 'agncy-companion-plugin' ),
			'config' => array(
				'rich' => true,
                'full' => true
			)
		),
        array(
			'type'   => 'text',
			'handle' => 'latlong',
			'label'  => __( 'Coordinates', 'agncy-companion-plugin' ),
			'help' => __( 'Latitude & longitude of the office phisical location.', 'agncy-companion-plugin' ),
			'config' => array(
				'full' => true
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
						'data' => agncy_office_metas()
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
		)
	) );
}

add_action( 'admin_init', 'agncy_office_data_meta_box', 1 );