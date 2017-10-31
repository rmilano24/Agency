<?php

/**
 * Enqueue the admin assets required by the theme.
 *
 * @since 1.0.0
 */
function agncy_admin_enqueue_assets() {
	/* Localize admin scripts. */
	$agncy = array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	);

	/* URIs. */
	$js_admin_uri = trailingslashit( get_template_directory_uri() . '/js/admin' );
	$css_admin_uri = trailingslashit( get_template_directory_uri() . '/inc/admin/css' );

	/* Scripts. */
	wp_enqueue_script( 'agncy-admin', $js_admin_uri . 'min/agncy.min.js', array( 'jquery' ), null, true );

	/* Styles */
	wp_enqueue_style( 'agncy-admin-css', $css_admin_uri . 'admin.css' );

	/* Localize the admin script. */
	wp_localize_script( 'jquery', 'agncy', apply_filters( 'agncy_admin_localize', $agncy ) );
}

add_action( 'admin_enqueue_scripts', 'agncy_admin_enqueue_assets' );

/**
 * Localize auto updates data.
 *
 * @since 1.0.0
 * @param array $agncy An array of data.
 * @return array
 */
function agncy_localize_auto_updates( $agncy ) {
	$agncy[ 'auto_updates' ] = (bool) get_option( 'agncy_auto_updates' );

	return $agncy;
}

add_filter( 'agncy_admin_localize', 'agncy_localize_auto_updates' );

/**
 * AJAX request to unlink auto updates to the theme.
 *
 * @since 1.0.0
 */
function agncy_unlink_auto_updates() {
	$generic_error = __( 'There was an error disconnecting your account. Try again.', 'agncy' );

	if ( empty( $_POST ) ) {
		die( $generic_error );
	}

	$is_valid_nonce = isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'agncy_unlink_auto_updates' );

	if ( ! $is_valid_nonce ) {
		die( $generic_error );
	}

	delete_option( 'agncy_auto_updates' );

	/* Clean up updater data. */
	EvolveThemeUpdater::instance()->cleanup();

	die( '1' );
}

add_action( 'wp_ajax_agncy_unlink_auto_updates', 'agncy_unlink_auto_updates' );

/**
 * AJAX request to link auto updates to the theme.
 *
 * @since 1.0.0
 */
function agncy_link_auto_updates() {
	$generic_error = __( 'Please enter a valid Envato authentication code.', 'agncy' );

	if ( empty( $_POST ) || ! isset( $_POST[ 'code' ] ) ) {
		die( $generic_error );
	}

	$is_valid_nonce = isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'agncy_link_auto_updates' );

	if ( ! $is_valid_nonce ) {
		die( $generic_error );
	}

	$code = sanitize_text_field( $_POST['code'] );

	if ( empty( $code ) ) {
		die( $generic_error );
	}

	/* Instantiate the updater class. */
	$updater = EvolveThemeUpdater::instance();

	/* Check for authentication. */
	try {
		$updater->maybe_authenticate( $code );

		if ( $updater->is_authenticated() === false ) {
			die( __( "Couldn't find the theme among your purchases. Did you connect the right account?", 'agncy' ) );
		}

		update_option( 'agncy_auto_updates', 1 );

		die( '1' );
	}
	catch ( Exception $e ) {
		die( $generic_error );
	}
}

add_action( 'wp_ajax_agncy_link_auto_updates', 'agncy_link_auto_updates' );

/**
 * Get the system status report.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_system_status() {
	$status = array();

	/* Data. */
	$memory = WP_MEMORY_LIMIT;
	$memory_codex_url = 'https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP';

	if ( function_exists( 'memory_get_usage' ) ) {
		$system_memory = @ini_get( 'memory_limit' );
		$memory        = max( intval( $memory ), intval( $system_memory ) );
		$memory		   .= 'M';
	}

	$debug = defined( 'WP_DEBUG' ) && WP_DEBUG;
	$debug_codex_url = 'https://codex.wordpress.org/Editing_wp-config.php#Debug';

	$php_version = phpversion();
	$php_post_max_size = ini_get( 'post_max_size' );
	$php_time_limit = ini_get( 'max_execution_time' );
	$php_max_input_vars = ini_get( 'max_input_vars' );
	$php_max_file_upload = agncy_max_file_upload_in_bytes();

	$plugins = array();
	$active_plugins = get_option( 'active_plugins' );
	$all_plugins = get_plugins();

	foreach ( $active_plugins as $active_plugin ) {
		$plugins[ $all_plugins[$active_plugin]['Name'] ] = $all_plugins[$active_plugin]['Version'];
	}

	if ( is_multisite() ) {
		$network_active_plugins = get_site_option( 'active_sitewide_plugins' );

		foreach ( $network_active_plugins as $active_plugin => $last_updated ) {
			$plugins[ $all_plugins[$active_plugin]['Name'] ] = $all_plugins[$active_plugin]['Version'];
		}
	}

	$theme = agncy_get_theme();
	$theme_version = $theme->get( 'Version' );

	/* WordPress checks. */
	$status['wp_version'] = array(
		'value' => get_bloginfo( 'version' ),
	);

	$status['wp_multisite'] = array(
		'value' => is_multisite() ? __( 'On', 'agncy' ) : __( 'Off', 'agncy' ),
	);

	$status['wp_memory_limit'] = array(
		'value' => $memory,
		'warning' => intval( $memory ) < 64 ? sprintf( __( 'We recommend setting memory to at least 64MB. See <a href="%s" target="_blank" rel="noopener noreferrer">official WordPress documentation</a>.', 'agncy' ), esc_attr( $memory_codex_url ) ) : ''
	);

	$status['wp_debug'] = array(
		'value' => $debug ? __( 'On', 'agncy' ) : __( 'Off', 'agncy' ),
		'warning' => $debug ? sprintf( __( 'Debug mode is not suitable for production use, you might want to turn it off. See <a href="%s" target="_blank" rel="noopener noreferrer">official WordPress documentation</a>.', 'agncy' ), esc_attr( $debug_codex_url ) ) : ''
	);

	/* Server-side checks. */
	$status['server'] = array(
		'value' => $_SERVER['SERVER_SOFTWARE']
	);

	$status['php_version'] = array(
		'value' => $php_version,
		'warning' => version_compare( $php_version, '5.6', '<' ) ? __( 'Although the theme is compatible with PHP 5.2+, we recommend a minimum PHP version of 5.6.', 'agncy' ) : ''
	);

	$status['php_post_max_size'] = array(
		'value' => $php_post_max_size
	);

	$status['php_time_limit'] = array(
		'value' => sprintf( _x( '%s seconds', 'php time limit', 'agncy' ), $php_time_limit )
	);

	$status['php_max_input_vars'] = array(
		'value' => $php_max_input_vars
	);

	$status['php_max_file_upload'] = array(
		'value' => $php_max_file_upload
	);

	/* Theme checks. */
	$status['theme_version'] = array(
		'value' => $theme_version
	);

	$status['theme_child'] = array(
		'value' => is_child_theme() ? __( 'On', 'agncy' ) : __( 'Off', 'agncy' ),
	);

	/* Error count. */
	$status['_errors'] = 0;

	foreach ( $status as $check ) {
		if ( isset( $check['warning'] ) && ! empty( $check['warning'] ) ) {
			$status['_errors']++;
		}
	}

	return $status;
}

/**
 * Get the theme status.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_theme_status() {
	$status = '';

	if ( get_option( 'agncy_first_install' ) === '1' || get_option( 'agncy_first_install' ) === false ) {
		// First install
		$status = 'first_install';
	}
	elseif ( get_option( 'agncy_updated' ) === '1' ) {
		// Updated
		$status = 'updated';
	}

	return $status;
}

/**
 * Clear the theme status.
 *
 * @since 1.0.0
 */
function agncy_clear_theme_status() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	if ( isset( $_GET['page'] ) && ( $_GET['page'] === 'agncy' || $_GET['page'] === 'agncy-install' || $_GET['page'] === 'tgmpa-install-plugins' ) ) {
		return;
	}

	update_option( 'agncy_first_install', '0' );
	delete_option( 'agncy_updated' );
}

add_action( 'admin_footer', 'agncy_clear_theme_status' );

/**
 * Return a bytes value for a size string.
 *
 * @since 1.0.0
 * @param string $val The size string.
 * @return integer
 */
function agncy_return_bytes( $val ) {
	$val = trim( $val );
	$last = strtolower( $val[strlen( $val ) - 1] );

	switch( $last ) {
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}

	return absint( $val );
}

/**
 * Get the computed value of Max File Upload.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_max_file_upload_in_bytes() {
	$max_file_upload = wp_max_upload_size();
	$max_file_upload = ( $max_file_upload / (1024*1024) ) . 'M';

	return $max_file_upload;
}

/**
 * Return a list of alert from the theme.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_alerts() {
	$has_alerts = false;
	$alerts = array(
		'plugins' => array(
			'update' => array(),
			'not_active' => array(),
		),
		'theme' => array(
			'update' => false
		)
	);

	if ( current_user_can( 'activate_plugins' ) ) {
		/* Plugin related alerts. */
		$tgm = TGM_Plugin_Activation::get_instance();

		foreach ( agncy_plugins_configuration() as $plugin ) {
			if ( false !== $tgm->does_plugin_have_update( $plugin['slug'] ) && $tgm->can_plugin_update( $plugin['slug'] ) ) {
				$alerts['plugins']['update'][] = $plugin['name'];
				$has_alerts = true;
			}
			elseif ( ! $tgm->is_plugin_active( $plugin['slug'] ) && $tgm->can_plugin_activate( $plugin['slug'] ) ) {
				if ( isset( $plugin['required'] ) && $plugin['required'] ) {
					$alerts['plugins']['not_active'][] = $plugin['name'];
					$has_alerts = true;
				}
			}
		}
	}

	if ( current_user_can( 'install_themes' ) ) {
		$alerts['theme']['update'] = agncy_has_updates();

		if ( $alerts['theme']['update'] !== false ) {
			$has_alerts = true;
		}
	}

	if ( ! $has_alerts ) {
		$alerts = false;
	}

	return $alerts;
}

/**
 * Check if the theme has updates available.
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_has_updates() {
	$current_theme = wp_get_theme();
	$current_stylesheet = get_stylesheet();

	if ( is_child_theme() ) {
		$current_stylesheet = $current_theme->get( 'Template' );
	}

	$theme_updates = get_site_transient( 'update_themes' );

	if ( isset( $theme_updates->response ) && isset( $theme_updates->response[ $current_stylesheet ] ) && ! empty( $theme_updates->response[ $current_stylesheet ] ) ) {
		return $theme_updates->response[ $current_stylesheet ]['new_version'];
	}

	return false;
}

/**
 * Display a warning badge in the admin WordPress menu.
 *
 * @since 1.0.0
 */
function agncy_admin_menu_notice_badges() {
	global $menu;

	foreach ( $menu as $k => &$item ) {
		if ( isset( $item[2] ) && $item[2] === 'agncy' ) {
			$alerts = agncy_alerts();

			if ( $alerts !== false ) {
				$item[0] .= ' ' . sprintf( '<span title="%s" class="awaiting-mod count-%s"><span class="pending-count">%s</span></span>',
					esc_attr( __( 'Update needed!', 'agncy' ) ),
					esc_attr( '!' ),
					esc_html( '!' )
				);
			}

			$is_layout_editing = ( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'agncy_layout' ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'agncy_layout' );

			if ( $is_layout_editing ) {
				$item[4] .= ' wp-has-current-submenu';
			}
		}
	}
}

add_action( 'admin_menu', 'agncy_admin_menu_notice_badges', 999 );

/**
 * Display admin notices regarding the update of plugins needed by the theme.
 *
 * @since 1.0.3
 * @param boolean $force Set to true to force the display of the notices.
 */
function agncy_show_plugin_admin_notices( $force = false ) {
	$agncy_alerts = agncy_alerts();

	if ( $agncy_alerts === false ) {
		return;
	}

	global $pagenow;

	$is_about_page = $pagenow == 'admin.php' && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'agncy';
	$is_tgmpa_page = $pagenow == 'admin.php' && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'tgmpa-install-plugins';

	if ( ( $is_about_page || $is_tgmpa_page ) && ! $force ) {
		return;
	}

	$tgmpa = TGM_Plugin_Activation::get_instance()->get_tgmpa_url();

	if ( ! empty( $agncy_alerts['plugins']['not_active'] ) || ! empty( $agncy_alerts['plugins']['update'] ) ) : ?>
		<div class="agncy-ap-n agncy-ap-n-alert">
			<h4><?php esc_html_e( 'Agncy: plugins action needed!', 'agncy' ); ?></h4>

			<?php if ( ! empty( $agncy_alerts['plugins']['not_active'] ) ) : ?>
				<p><?php esc_html_e( 'Here is a list of plugins that must be activated:', 'agncy' ); ?></p>
				<ul>
					<?php foreach ( $agncy_alerts['plugins']['not_active'] as $plugin_name ) : ?>
						<li><?php echo esc_html( $plugin_name ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ! empty( $agncy_alerts['plugins']['update'] ) ) : ?>
				<p><?php esc_html_e( 'Here is a list of plugins that must be updated:', 'agncy' ); ?></p>
				<ul>
					<?php foreach ( $agncy_alerts['plugins']['update'] as $plugin_name ) : ?>
						<li><?php echo esc_html( $plugin_name ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<a class="agncy-ap-n-btn agncy-ap-btn agncy-ap-btn-fill" href="<?php echo esc_attr( $tgmpa ); ?>"><?php esc_html_e( 'Manage plugins', 'agncy' ); ?></a>
		</div>
	<?php endif;
}

add_action( 'admin_notices', 'agncy_show_plugin_admin_notices' );
