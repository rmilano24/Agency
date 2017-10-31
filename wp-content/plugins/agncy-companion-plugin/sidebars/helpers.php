<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Return a list of user-defined sidebars.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_sidebars() {
	$sidebars = get_option( 'agncy_sidebars' );

	if ( empty( $sidebars ) ) {
		$sidebars = array();
	}

	return $sidebars;
}

/**
 * Register a user-defined sidebar.
 *
 * @since 1.0.0
 */
function agncy_save_sidebar() {
	if ( ! ev_is_post_nonce_valid( 'agncy_register_sidebar', '_wpnonce' ) ) {
		return;
	}

	$name = sanitize_text_field( $_POST['name'] );

	if ( empty( $name ) ) {
		return;
	}

	$id = md5( time() . $name );

	$sidebars = agncy_get_sidebars();
	$sidebars[$id] = $name;

	update_option( 'agncy_sidebars', $sidebars );
}

/**
 * Remove a user-defined sidebar.
 *
 * @since 1.0.0
 */
function agncy_remove_sidebar() {
	if ( ! ev_is_post_nonce_valid( 'agncy_remove_sidebar', '_wpnonce' ) ) {
		return;
	}

	$id = sanitize_text_field( $_POST['sidebar_id'] );

	$sidebars = agncy_get_sidebars();
	unset( $sidebars[$id] );

	update_option( 'agncy_sidebars', $sidebars );
}

/**
 * Register user-defined sidebars.
 *
 * @since 1.0.0
 */
function agncy_register_sidebars() {
	if ( ! empty( $_POST ) ) {
		agncy_remove_sidebar();
		agncy_save_sidebar();
	}

	$sidebars = agncy_get_sidebars();

	$args = array(
		'name'          => '',
		'id'            => '',
		'description'   => '',
		'class'         => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	);

	foreach ( $sidebars as $id => $name ) {
		$args['name'] = $name;
		$args['id'] = $id;

		register_sidebar( $args );
	}
}

add_action( 'widgets_init', 'agncy_register_sidebars' );

/**
 * Create the option to control sidebar in a given context.
 *
 * @since 1.0.0
 * @param string $handle The option handle key.
 * @param string $label The option label.
 * @param string $help The option help text.
 * @return array
 */
function agncy_create_sidebar_options( $handle, $label = '', $help = '' ) {
	if ( empty( $label ) ) {
		$label = __( 'Sidebar', 'agncy-companion-plugin' );
	}

	if ( empty( $help ) ) {
		$help = __( 'Select which sidebar to use in this context.', 'agncy-companion-plugin' );
	}

	$sidebars_data = array();
	$sidebars_data['0'] = __( 'No sidebar', 'agncy-companion-plugin' );
	$sidebars_data = array_merge( $sidebars_data, agncy_get_widget_areas() );

	$positions = array(
		'0'     => __( 'Inherit from language', 'agncy-companion-plugin' ),
		'right' => __( 'Right', 'agncy-companion-plugin' ),
		'left'  => __( 'Left', 'agncy-companion-plugin' ),
	);

	return array(
		array(
			'handle' => $handle . '_divider_label',
			'text' => $label,
			'type' => 'divider',
			'config' => array(
				'style' => 'in_page'
			)
		),
		array(
			'label' => $label,
			'help' => $help,
			'handle' => $handle,
			'type' => 'select',
			'config' => array(
				'controller' => true,
				'data' => $sidebars_data
			)
		),
		array(
			'type' => 'select',
			'handle' => $handle . '_position',
			'label' => __( 'Position', 'agncy-companion-plugin' ),
			'help' => __( 'Content will be displayed on the opposite side.', 'agncy-companion-plugin' ),
			'config' => array(
				'visible' => array( $handle => '!=0' ),
				'data' => $positions
			),
			'default' => '0'
		)
	);
}