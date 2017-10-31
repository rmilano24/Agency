<?php

/**
 * Enqueue the admin assets required by the theme installer.
 *
 * @since 1.0.0
 */
function agncy_installer_enqueue_assets() {
	if ( ! agncy_is_install_page() ) {
		return;
	}

	$installer_uri = trailingslashit( get_template_directory_uri() . '/inc/installer/assets' );

	/* Stylesheet. */
	wp_enqueue_style( 'agncy-installer-style', $installer_uri . 'css/installer.css' );

	/* Script. */
	wp_enqueue_script( 'agncy-installer-nprogress', $installer_uri . 'js/nprogress.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'agncy-installer-script', $installer_uri . 'js/installer.js', array( 'agncy-admin', 'agncy-installer-nprogress' ), null, true );

	/* Localize scripts. */
	$agncy_installer_localized = array(
		'admin_url' => admin_url(),
		'welcome_url' => agncy_admin_about_page_url(),
		'plugins'   => array(),
		'plugins_error' => sprintf( __( 'Something went wrong with the installation of one of the required plugins. Please install them through <a href="%s">this page</a>, or manually.', 'agncy' ),
			esc_attr( TGM_Plugin_Activation::get_instance()->get_tgmpa_url() )
		)
	);

	/* Localize plugins. */
	foreach ( agncy_plugins_configuration() as $plugin ) {
		$slug  = $plugin['slug'];
		$label = $plugin['name'];
		$path  = $plugin['path'];

		if ( isset( $plugin['required'] ) && $plugin['required'] ) {
			$agncy_installer_localized['plugins'][$slug] = array(
				'label'        => $label,
				'path'         => $path,
				'action'       => agncy_install_plugin_action_url( $path, $slug ),
				'action_label' => agncy_install_plugin_action_label( $path, $slug, $label )
			);
		}
	}

	wp_localize_script( 'agncy-installer-script', 'agncy_installer_localized', $agncy_installer_localized );
}

add_action( 'admin_enqueue_scripts', 'agncy_installer_enqueue_assets' );

/**
 * Creation of the "Install" theme page.
 *
 * @since 1.0.0
 */
function agncy_install_page_creation() {
	$label = esc_html__( 'Getting started', 'agncy' );

	$capability = 'manage_options';
	$slug       = 'agncy-install';
	$callback   = 'agncy_install_page';

	add_theme_page(
		$label,
		$label,
		$capability,
		$slug,
		$callback
	);
}

add_action( 'admin_menu', 'agncy_install_page_creation', 100 );

/**
 * Render the installer admin page.
 *
 * @since 1.0.0
 */
function agncy_install_page() {
	get_template_part( 'inc/installer/page' );
}

/**
 * Get the URL for the installed copy of TGMPA.
 *
 * @since 1.0.0
 * @param string $slug The plugin slug.
 * @param string $install_type The action type.
 * @return string
 */
function agncy_install_plugin_url( $slug, $install_type ) {
	$tgmpa = TGM_Plugin_Activation::get_instance();

	$url = wp_nonce_url(
		add_query_arg(
			array(
				'plugin'                 => urlencode( $slug ),
				'tgmpa-' . $install_type => $install_type . '-plugin',
			),
			$tgmpa->get_tgmpa_url()
		),
		'tgmpa-' . $install_type,
		'tgmpa-nonce'
	);

	$url = html_entity_decode( $url );

	return $url;
}

/**
 * Return the appropriate action URL depending on the plugin status.
 *
 * @since 1.0.0
 * @param string $path The plugin path.
 * @param string $slug The plugin slug.
 * @return string
 */
function agncy_install_plugin_action_url( $path, $slug ) {
	$status = agncy_install_check_plugin_status( $path, $slug );

	switch ( $status ) {
		case 'update_needed':
			return agncy_install_plugin_url( $slug, 'update' );
			break;
		case 'installed':
			return agncy_install_plugin_url( $slug, 'activate' );
			break;
		case 'active':
			return '';
			break;
		default:
			return agncy_install_plugin_url( $slug, 'install' );
			break;
	}
}

/**
 * Return the appropriate action label depending on the plugin status.
 *
 * @since 1.0.0
 * @param string $path The plugin path.
 * @param string $slug The plugin slug.
 * @param string $label The plugin label.
 * @return string
 */
function agncy_install_plugin_action_label( $path, $slug, $label ) {
	$status = agncy_install_check_plugin_status( $path, $slug );

	switch ( $status ) {
		case 'update_needed':
			return sprintf(
				_x( 'Updating %s', 'plugin update label', 'agncy' ),
				$label
			);
		case 'installed':
			return sprintf(
				_x( 'Activating %s', 'plugin activate label', 'agncy' ),
				$label
			);
		case 'active':
			return sprintf(
				__( 'The %s plugin is already active', 'agncy' ),
				$label
			);
		default:
			return sprintf(
				_x( 'Installing %s', 'plugin install label', 'agncy' ),
				$label
			);
	}

	return '';
}

/**
 * Remove the installation page from the menu.
 *
 * @since 1.0.0
 */
function agncy_install_remove_install_page() {
	global $submenu;

	foreach ( $submenu['themes.php'] as $k => $m ) {
		if ( isset( $m[2] ) && $m[2] == 'agncy-install' ) {
			unset( $submenu['themes.php'][$k] );
		}
	}
}

add_action( 'admin_menu', 'agncy_install_remove_install_page', 999 );