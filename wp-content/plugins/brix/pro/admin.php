<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Check if the registration details have been inserted.
 *
 * @since 1.1.2
 * @return boolean
 */
function brix_check_registration_page_details() {
	$maintenance = get_option( 'brix_registration' );

	$purchase_code  = isset( $maintenance['purchase_code'] ) ? $maintenance['purchase_code'] : '';
	$purchase_email = isset( $maintenance['purchase_email'] ) ? $maintenance['purchase_email'] : '';

	if ( empty( $purchase_code ) || empty( $purchase_email ) ) {
		return false;
	}

	return true;
}

/**
 * Output the form that allows to save the purchase code and purchase email
 * information in order to attempt to link the current domain to the updates provider.
 *
 * @since 1.0.0
 */
function brix_registration_page_details() {
	echo '<form method="post" action="" id="brix-domain-link" class="brix-domain-link-wrapper">';
		wp_nonce_field( 'brix_registration', 'ev' );

		$maintenance = get_option( 'brix_registration' );

		$purchase_code  = isset( $maintenance['purchase_code'] ) ? $maintenance['purchase_code'] : '';
		$purchase_email = isset( $maintenance['purchase_email'] ) ? $maintenance['purchase_email'] : '';

		echo '<div class="brix-form-purchase-code">';
			printf( '<label for="purchase_code">%s</label>', esc_html( __( 'Purchase code', 'brix' ) ) );
			printf( '<input type="text" name="purchase_code" id="purchase_code" value="%s">', esc_attr( $purchase_code ) );
		echo '</div>';

		echo '<div class="brix-form-purchase-email">';
			printf( '<label for="purchase_email">%s</label>', esc_html( __( 'Purchase email', 'brix' ) ) );
			printf( '<input type="text" name="purchase_email" id="purchase_email" value="%s">', esc_attr( $purchase_email ) );
		echo '</div>';

		echo '<button type="submit" class="brix-btn brix-btn-type-save brix-btn-size-medium brix-btn-style-button"><span class="">' . esc_html__( 'Save', 'brix' ) . '</span></button>';

		echo '<p class="brix-result"></p>';
	echo '</form>';
}
/**
 * AJAX action that saves the purchase code and purchase email information and
 * attempts to link the current domain to the updates provider.
 *
 * @since 1.0.0
 */
function brix_registration_page_save() {
	if ( ! brix_is_post_nonce_valid( 'brix_registration' ) ) {
		die( 'Invalid nonce.' );
	}

	$purchase_code  = isset( $_POST['purchase_code'] ) ? sanitize_text_field( $_POST['purchase_code'] ) : '';
	$purchase_email = isset( $_POST['purchase_email'] ) ? sanitize_text_field( $_POST['purchase_email'] ) : '';

	$maintenance                    = array();
	$maintenance['purchase_code']   = $purchase_code;
	$maintenance['purchase_email']  = $purchase_email;

	update_option( 'brix_registration', $maintenance );

	/* Attempt to link the current domain to the updates provider. */
	$domain_link_attempt = BrixBuilderUpdater::instance()->linkDomain();

	$response = array(
		'type' => 'success',
		'message' => ''
	);

	if ( empty( $domain_link_attempt ) ) {
		$response['type'] = 'warning';
		$response['message'] = __( 'Data saved correctly, but the domain was not linked.', 'brix' );
	}
	else {
		if ( is_wp_error( $domain_link_attempt ) ) {
			$response['type'] = 'error';
			$response['message'] = $domain_link_attempt->get_error_message();

			if ( empty( $response['message'] ) ) {
				$response['message'] = __( 'We were\'t able to link your domain. Please check your info, and try again.', 'brix' );
			}
		}
		else {
			$response['message'] = $domain_link_attempt;
		}
	}

	die( json_encode( $response ) );
}

add_action( 'wp_ajax_brix_registration_page_save', 'brix_registration_page_save' );

/**
 * Function that outputs the contents of the dashboard widget.
 *
 * @since 1.1.1
 */
function brix_dashboard_widget_function( $post, $callback_args ) {
	require_once ABSPATH . 'wp-admin/includes/dashboard.php';

	$feeds = array(
		'brix' => array(
			'link' => 'https://www.brixbuilder.com/',
			'url' => 'https://www.brixbuilder.com/feed/',
			'title'        => '',
			'items'        => 3,
			'show_summary' => 1,
			'show_author'  => 0,
			'show_date'    => 1,
		),
	);

	$logo = BrixBuilder::instance()->i18n_strings( 'logo_svg' );
	$logo .= BrixBuilder::instance()->i18n_strings( 'logo_name_svg' );

	printf( '<a class="brix-logo" href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
		esc_attr( 'https://www.brixbuilder.com/blog/' ),
		$logo
	);

	wp_dashboard_cached_rss_widget( 'brix_dashboard_feed', 'wp_dashboard_primary_output', $feeds );
}

/**
 * Function used in the action hook.
 *
 * @since 1.1.1.
 */
function brix_add_dashboard_widgets() {
	wp_add_dashboard_widget( 'brix_dashboard_widget', __( "What's new on Brix&#8230;", 'brix' ), 'brix_dashboard_widget_function' );
}

add_action( 'wp_dashboard_setup', 'brix_add_dashboard_widgets' );

/**
 * AJAX function used in the action hook.
 *
 * @since 1.1.1.
 */
function brix_dashboard_widget() {
	brix_dashboard_widget_function( '', '' );

	die();
}

add_action( 'wp_ajax_brix_dashboard_widget', 'brix_dashboard_widget' );

/**
 * Process the request to force an updates check.
 *
 * @since 1.1.1
*/
function brix_trigger_force_updates_check() {
	if( ! isset( $_GET['action'] ) || 'brix_force_updates_check' != $_GET['action'] ) {
		return;
	}

	if( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	set_site_transient( 'update_plugins', null );

	wp_safe_redirect( network_admin_url( 'update-core.php' ) ); exit;

}

add_action( 'admin_init', 'brix_trigger_force_updates_check' );

/**
 * Display a notice to warn the user if the registration credentials haven't yet
 * been linked to the purchased copy of the plugin.
 *
 * @since 1.1.2
 */
function brix_auto_update_notice() {
	if ( ! brix_is_supported() ) {
		return;
	}

	$register_url = admin_url( 'admin.php?page=brix_registration' );

	printf( '<div class="brix-auto-update-notice">%s <a href="%s">%s</a></div>',
		esc_html__( 'Register your copy in order to receive automatic updates!', 'brix' ),
		esc_attr( $register_url ),
		esc_html__( 'Register now', 'brix' )
	);
}

if ( ! brix_check_registration_page_details() ) {
	/* Display the notice only if registration details haven't been filled. */
	add_action( 'brix_admin_page_preheading', 'brix_auto_update_notice' );
}

/**
 * Declare administration pages.
 *
 * @since 1.1.3
 */
function brix_admin_pro_pages() {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! class_exists( 'Brix_Framework' ) ) {
		return;
	}

	if ( ! brix_install_check() ) {
		return;
	}

	if ( brix_is_supported() ) {
		$support_submenu_page = new Brix_BrixSubmenuPage( 'brix', 'brix_support', __( 'Support', 'brix' ), array(), array(
			'group' => 'brix'
		) );

		$registration_submenu_page = new Brix_BrixSubmenuPage( 'brix', 'brix_registration', __( 'Registration', 'brix' ), array(), array(
			'group' => 'brix'
		) );
	}
}

add_action( 'init', 'brix_admin_pro_pages', 51 );

/**
 * Add scripts and styles on admin.
 *
 * @since 1.0.0
 */
function brix_pro_add_admin_assets() {
	$suffix = brix_get_scripts_suffix();

	/* Builder admin pro styles. */
	brix_fw()->admin()->add_style( 'brix-pro-admin-style', BRIX_PRO_URI . 'assets/admin/css/style.css' );

	/* Main builder JavaScript controller. */
	brix_fw()->admin()->add_script( 'brix-pro-admin-script', BRIX_PRO_URI . 'assets/admin/js/min/builder.pro.' . $suffix . '.js', array( 'brix-admin-script' ) );
}

/* Add scripts and styles on admin. */
add_action( 'admin_init', 'brix_pro_add_admin_assets' );

/**
 * Localize pro scripts.
 *
 * @since 1.2
 */
function brix_pro_admin_enqueue_scripts() {
	wp_localize_script( 'brix-pro-admin-script', 'brix_pro', array(
		'enabled' => true
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_pro_admin_enqueue_scripts', 21 );

/**
 * Add meta information to the plugin row in the Plugins screen in WordPress admin.
 *
 * @since 1.0.0
 * @param array  $plugin_meta An array of the plugin's metadata,
 *                            including the version, author,
 *                            author URI, and plugin URI.
 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
 * @param array  $plugin_data An array of plugin data.
 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
 *                            'Drop-ins', 'Search'.
 * @return array
 */
function brix_pro_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status )
{
	$framework_data = get_plugin_data( BRIX_PLUGIN_FILE );
	$check = array( 'Name', 'PluginURI', 'AuthorName', 'AuthorURI', 'Version', 'TextDomain' );

	/* If this is not our plugin, exit. */
	foreach ( $check as $key ) {
		if ( $plugin_data[$key] !== $framework_data[$key] ) {
			return $plugin_meta;
		}
	}

	$builder_changelog_url = BRIX_CHANGELOG_URI;

	if ( $builder_changelog_url !== '' ) {
		$plugin_meta[] = sprintf( '<a target="_blank" rel="noopener noreferrer" data-changelog href="%s">%s</a>',
			esc_attr( $builder_changelog_url ),
			esc_html( __( 'Changelog', 'brix' ) )
		);
	}

	return $plugin_meta;
}

add_action( 'plugin_row_meta', 'brix_pro_plugin_row_meta', 10, 4 );

/**
 * Admin body classes.
 *
 * @since 1.2
 * @param string $classes The body classes.
 * @return string
 */
function brix_pro_admin_body_class( $classes ) {
	$classes .= ' brix-pro-enabled';

	return $classes;
}

add_filter( 'admin_body_class', 'brix_pro_admin_body_class' );