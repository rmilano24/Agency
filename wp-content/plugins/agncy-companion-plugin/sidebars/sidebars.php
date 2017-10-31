<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/* Helpers. */
require_once dirname( __FILE__ ) . '/helpers.php';

/**
 * Output the markup to manage sidebars.
 *
 * @since 1.0.0
 */
function agncy_sidebars_manager_markup() {
	require_once( dirname( __FILE__ ) . '/templates/manager.php' );
}

add_action( 'widgets_admin_page', 'agncy_sidebars_manager_markup' );

/**
 * Localize sidebars data.
 *
 * @since 1.0.0
 */
function agncy_sidebars_localize() {
	$data = array(
		'error_sidebar_no_name' => __( 'Please add a name for the sidebar', 'agncy-companion-plugin' ),
		'error_sidebar_remove' => __( 'Are you sure?', 'agncy-companion-plugin' ),
	);

	wp_localize_script( 'agncy-companion-js', 'agncy_sidebars', $data );
}

add_action( 'admin_enqueue_scripts', 'agncy_sidebars_localize', 16 );