<?php

if ( ! class_exists( 'EvolveThemeUpdater' ) ) {
	require_once get_template_directory() . '/inc/updater/theme-updater.php';
}

/**
 * Envato API client ID.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_envato_api_client_id() {
	return 'agncy-updater-4gvlvpph';
}

/**
 * Envato API client secret.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_envato_api_client_secret() {
	return 'EJHXcfSONHKdC1PslI1kura94QtcTevX';
}

/**
 * String identifier for the product.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_envato_api_product_key() {
	return 'agncy';
}

/* Instantiate the updater class. */
$updater = EvolveThemeUpdater::instance(
	agncy_get_envato_api_client_id(),
	agncy_get_envato_api_client_secret(),
	agncy_get_envato_api_product_key()
);

/* Perform additional actions to successfully install the theme. */
add_filter( 'upgrader_post_install', array( $updater, 'postInstall' ), 10, 3 );

/* Update checker. */
add_filter( 'pre_set_site_transient_update_themes', array( $updater, 'check' ) );