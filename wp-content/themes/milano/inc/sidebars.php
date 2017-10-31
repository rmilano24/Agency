<?php

/**
 * Get the currently registered widget areas.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_widget_areas() {
	global $wp_registered_sidebars;

	$sidebars = array();

	foreach( $wp_registered_sidebars as $sidebar ) {
		$sidebars[ $sidebar[ 'id' ] ] = $sidebar[ 'name' ];
	}

	return $sidebars;
}

/**
 * Get the currently registered widget areas for select.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_widget_areas_for_select() {
	$sidebars = array(
		'0' => '--'
	);

	foreach( agncy_get_widget_areas() as $id => $name ) {
		$sidebars[ $id ] = $name;
	}

	return $sidebars;
}

/**
 * Return the sidebar used for the current page.
 *
 * @since 1.0.0
 * @return integer
 */
function agncy_get_page_sidebar() {
	$sidebar = array(
		'sidebar' => '',
		'position' => 'right'
	);

	if ( is_singular() ) {
		$post_type = get_post_type();
		$post_id = get_queried_object_id();

		if ( function_exists( 'ev_get_post_meta' ) ) {
			$sidebar[ 'sidebar' ] = ev_get_post_meta( $post_id, 'agncy_sidebar_name', true );
			$sidebar[ 'position' ] = ev_get_post_meta( $post_id, 'agncy_sidebar_position', true );
		}

		if ( function_exists( 'ev_get_option' ) ) {
			if ( $sidebar[ 'sidebar' ] === false || $sidebar[ 'sidebar' ] === 'inherit' ) {
				$sidebar[ 'sidebar' ] = ev_get_option( $post_type . '_single_sidebar' );
			}
		}

		if ( function_exists( 'ev_get_option' ) ) {
			if ( $sidebar[ 'position' ] === false || $sidebar[ 'position' ] === 'inherit' ) {
				$sidebar[ 'position' ] = ev_get_option( $post_type . '_single_sidebar_position' );
			}
		}
	}
	elseif ( is_archive() ) {
		if ( function_exists( 'ev_get_option' ) ) {
			$sidebar[ 'sidebar' ] = ev_get_option( 'archives_sidebar' );
			$sidebar[ 'position' ] = ev_get_option( 'archives_sidebar_position' );
		}
	}
	elseif ( is_search() ) {
		if ( function_exists( 'ev_get_option' ) ) {
			$sidebar[ 'sidebar' ] = ev_get_option( 'search_sidebar' );
			$sidebar[ 'position' ] = ev_get_option( 'search_sidebar_position' );
		}
	}
	elseif ( is_home() ) {
		if ( function_exists( 'ev_get_option' ) ) {
			$sidebar[ 'sidebar' ] = ev_get_option( 'index_sidebar' );
			$sidebar[ 'position' ] = ev_get_option( 'index_sidebar_position' );
		}
	}

	$sidebar = apply_filters( 'agncy_get_page_sidebar', $sidebar );

	if ( ! $sidebar[ 'sidebar' ] ) {
		return false;
	}

	return $sidebar;
}

/**
 * Return a set of options that manage the sidebar to be displayed.
 *
 * @since 1.0.0
 * @param string $context The screen context.
 * @return array
 */
function agncy_sidebar_fields( $context ) {
	$fields = array();

	$fields[] = array(
		'handle' => 'agncy_sidebar_divider',
		'type' => 'divider',
		'text' => __( 'Sidebar', 'agncy' )
	);

	$sidebars = array(
		'inherit' => __( 'Inherit from post type default', 'agncy' ),
		'0'       => __( 'No sidebar', 'agncy' )
	);

	$sidebars = array_merge( $sidebars, agncy_get_widget_areas() );

	$fields[] = array(
		'handle' => 'agncy_sidebar_name',
		'type' => 'select',
		'label' => __( 'Name', 'agncy' ),
		'help' => __( 'Select which widget area to display.', 'agncy' ),
		'config' => array(
			'data' => $sidebars,
			'controller' => true
		)
	);

		$fields[] = array(
			'handle' => 'agncy_sidebar_position',
			'type' => 'select',
			'label' => __( 'Position', 'agncy' ),
			'help' => __( 'Content will be displayed on the opposite side.', 'agncy' ),
			'config' => array(
				'data' => array(
					'inherit' => __( 'Inherit from post type default', 'agncy' ),
					'left' => __( 'Left', 'agncy' ),
					'right' => __( 'Right', 'agncy' )
				),
				'visible' => array( 'agncy_sidebar_name' => '!=0' )
			),
			'default' => 'inherit'
		);

	return $fields;
}