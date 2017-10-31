<?php

/**
 * Check plugin status.
 *
 * @since 1.0.0
 * @param string $path Path to the plugin main file.
 * @param string $slug The plugin slug.
 * @return string|boolean
 */
function agncy_install_check_plugin_status( $path, $slug = '' ) {
	$tgm = TGM_Plugin_Activation::get_instance();

	$is_active = ( $slug && $tgm->is_plugin_active( $slug ) ) || is_plugin_active( $path );

	if ( $is_active ) {
		if ( false !== $tgm->does_plugin_have_update( $slug ) && $tgm->can_plugin_update( $slug ) ) {
			return 'update_needed';
		}

		return 'active';
	}

	if ( file_exists( WP_PLUGIN_DIR . '/' . $path ) ) {
		return 'installed';
	}

	return false;
}

/**
 * AJAX request to check if a plugin is currently installed and active.
 *
 * @since 1.0.0
 */
function agncy_install_check_plugin_active() {
	if ( empty( $_POST ) || ! isset( $_POST['path'] ) ) {
		die( 0 );
	}

	$path = sanitize_text_field( $_POST['path'] );
	$status = agncy_install_check_plugin_status( $path );
	$active = intval( $status === 'active' );

	die( $active . '' );
}

add_action( 'wp_ajax_agncy_install_check_plugin_active', 'agncy_install_check_plugin_active' );

/**
 * Check if we're in the theme install page.
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_is_install_page() {
	$is_install_page = isset( $_GET['page'] ) && $_GET['page'] == 'agncy-install';

	return $is_install_page;
}

/**
 * Redirect to the "Getting Started" page if the theme is being installed.
 *
 * @since 1.0.0
 */
function agncy_maybe_install() {
	global $pagenow;

	$is_install_page    = agncy_is_install_page();
	$has_been_activated = $pagenow === 'themes.php' && isset( $_GET['activated'] );

	if ( $has_been_activated && ! is_customize_preview() && ! $is_install_page ) {
		do_action( 'agncy_maybe_install' );

		$first_install = agncy_theme_status() == 'first_install';

		if ( ! $first_install ) {
			wp_safe_redirect( agncy_admin_about_page_url() );
		}
		else {
			update_option( 'agncy_first_install', '1' );

			wp_safe_redirect( admin_url( 'themes.php?page=agncy-install' ) );
		}

		die();
	}
}

add_action( 'after_setup_theme', 'agncy_maybe_install' );

/**
 * Check if the theme is correctly installed.
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_installed() {
	$installed = true;

	foreach ( agncy_plugins_configuration() as $plugin ) {
		$slug  = $plugin['slug'];
		$path  = $plugin['path'];
		$status = agncy_install_check_plugin_status( $path, $slug );

		if ( $status !== 'active' ) {
			$installed = false;

			break;
		}
	}

	$installed = apply_filters( 'agncy_installed', $installed );

	return $installed;
}

/**
 * Optionally load the theme installer.
 *
 * @since 1.0.0
 */
function agncy_load_installer() {
	if ( agncy_installed() ) {
		/* Bail immediately if the theme has already been installed. */
		return;
	}

	$inc_folder = trailingslashit( get_template_directory() . '/inc' );

	require_once $inc_folder . 'installer/installer_admin.php';
}

add_action( 'admin_menu', 'agncy_load_installer', 0 );