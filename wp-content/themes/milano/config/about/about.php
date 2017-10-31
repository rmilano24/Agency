<?php

/**
 * Localize the About page on admin.
 *
 * @since 1.0.0
 * @param array $agncy The localization array.
 * @return array
 */
function agncy_admin_about_localize( $agncy ) {
	global $pagenow;

	if ( $pagenow === 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'agncy' ) {
		// $auto_updates = get_option( 'agncy_auto_updates' );

		// $agncy['auto_updates'] = array(
		// 	'username' => isset( $auto_updates['username'] ) ? $auto_updates['username'] : '',
		// 	'apikey' => isset( $auto_updates['apikey'] ) ? $auto_updates['apikey'] : '',
		// );

		$agncy['system_status'] = agncy_system_status();
		$agncy['theme_status'] = agncy_theme_status();

		if ( function_exists( 'agncy_demos' ) ) {
			$agncy['demos'] = agncy_demos();
		} else {
			$agncy['demos'] = array();
		}
	}

	return $agncy;
}

add_filter( 'agncy_admin_localize', 'agncy_admin_about_localize' );

/**
 * Creation of the "About" theme page.
 *
 * @since 1.0.0
 */
function agncy_admin_about_page_creation() {
	$label      = 'Agncy';
	$capability = 'manage_options';
	$slug       = 'agncy';
	$callback   = 'agncy_admin_about_page_template';
	$add_menu_callback = 'add_' . 'menu' . '_page';

	call_user_func(
		$add_menu_callback,
		$label,
		$label,
		$capability,
		$slug,
		$callback
	);
}

add_action( 'admin_menu', 'agncy_admin_about_page_creation', 1 );

/**
 * Render the admin About page.
 *
 * @since 1.0.0
 */
function agncy_admin_about_page_template() {
	get_template_part( 'config/about/about-page' );
}

/**
 * Return the complete URL to the About page.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_admin_about_page_url() {
	return admin_url( 'admin.php?page=agncy' );
}

/**
 * Enqueue the admin assets required by the about page.
 *
 * @since 1.0.0
 */
function agncy_about_page_enqueue_assets() {
	$about_page_uri = trailingslashit( get_template_directory_uri() . '/config/about' );

	/* Stylesheet. */
	wp_enqueue_style( 'agncy-about-page-style', $about_page_uri . 'css/about.css' );

	global $pagenow;

	if ( $pagenow === 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'agncy' ) {
		$config_base_folder = trailingslashit( get_template_directory_uri() . '/config' );

		wp_enqueue_script( 'agncy-admin-about', $config_base_folder . 'about/js/about.js', array( 'agncy-admin' ), null, true );
	}
}

add_action( 'admin_enqueue_scripts', 'agncy_about_page_enqueue_assets' );