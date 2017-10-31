<?php
/**
 * Plugin Name: Agncy Demos Importer
 * Description: Import Agncy demos.
 * Author: Evolve Themes
 * Author URI: https://justevolve.it
 * Version: 1.0.0
 * Text Domain: agncy-demos-importer
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @see WordPress Importer: https://wordpress.org/plugins/wordpress-importer/
*/

/* Base path for the importer. */
define( 'AGNCY_DEMOS_IMPORTER_BASEPATH', trailingslashit( dirname( __FILE__ ) ) );

/* Base URL for the importer. */
define( 'AGNCY_DEMOS_IMPORTER_URI', plugin_dir_url( __FILE__ ) );

/* Migration tasks. */
require_once dirname( __FILE__ ) . '/migrations.php';

/**
 * Declare the plugin as "loaded".
 *
 * @since 1.0.0
 */
function agncy_demos_imported_loaded() {
	define( 'AGNCY_DEMOS_IMPORTER_LOADED', true );

	/* Importing demos. */
	require_once AGNCY_DEMOS_IMPORTER_BASEPATH . 'demos/agency/agency.php';
}

add_action( 'plugins_loaded', 'agncy_demos_imported_loaded' );

/**
 * Initialize the importer when actually importing a Agncy demo.
 *
 * @since 1.0.0
 */
function agncy_demos_importer_init() {
	if ( ! defined( 'AGNCY_DEMOS_IMPORTER' ) ) {
		return;
	}

	require_once dirname( __FILE__ ) . '/classes.php';
	load_plugin_textdomain( 'agncy-demos-importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Get the information about the available theme demos.
 *
 * @since 1.0.0
 * @param string $demo The name of the demo to retrieve.
 * @return array
 */
function agncy_demos( $demo = false ) {
	$demos = array();

	if ( defined( 'AGNCY_DEMOS_IMPORTER_LOADED' ) && AGNCY_DEMOS_IMPORTER_LOADED ) {
		$demos = apply_filters( 'agncy_demos', $demos );
	}

	if ( $demo ) {
		if ( isset( $demos[ $demo ] ) ) {
			return $demos[ $demo ];
		}

		return false;
	}

	return $demos;
}

/**
 * Alter the raw post data when importing a dummy XML.
 *
 * @since 1.0.0
 * @param array $post The post data.
 * @return array
 */
function agncy_demo_alter_post_data_raw( $post ) {
	if ( ! defined( 'AGNCY_DEMOS_IMPORTER' ) ) {
		return $post;
	}

	if ( isset( $post[ 'attachment_url' ] ) ) {
		$post[ 'attachment_url' ] = str_replace( '%importer%', AGNCY_DEMOS_IMPORTER_URI, $post[ 'attachment_url' ] );
		$post[ 'attachment_url' ] = str_replace( '%theme%', trailingslashit( get_template_directory_uri() ), $post[ 'attachment_url' ] );
		$post[ 'attachment_url' ] = str_replace( '://localhost', '://127.0.0.1', $post[ 'attachment_url' ] );
	}

	$post = apply_filters( 'agncy_demo_alter_post_data_raw', $post );

	return $post;
}

add_filter( 'wp_import_post_data_raw', 'agncy_demo_alter_post_data_raw' );

/**
 * Install theme dummy content and options.
 *
 * @since 1.0.0
 */
function agncy_demo_install() {
	if ( empty( $_POST ) ) {
		die( '0' );
	}

	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'agncy_demo_install' );

	if ( ! $is_valid_nonce ) {
		die( '0' );
	}

	$demo_name = sanitize_text_field( $_POST[ 'demo' ] );

	if ( ! $demo_name ) {
		die( '0' );
	}

	/* Actual demo installation. */
	agncy_demo_perform_install( $demo_name );

	die( '1' );
}

add_action( 'wp_ajax_agncy_demo_install', 'agncy_demo_install' );

/**
 * Perform the demo installation.
 *
 * @since 1.0.0
 * @param string $demo_name The demo name.
 */
function agncy_demo_perform_install( $demo_name ) {
	$demo = agncy_demos( $demo_name );

	if ( ! $demo ) {
		return false;
	}

	/* Fired before the installation process has started. */
	do_action( 'agncy_demo_pre_install' );

	$options_imported = false;
	$mods_imported    = false;
	$dummy_imported   = false;

	/* Data. */
	if ( isset( $demo[ 'data' ] ) && file_exists( $demo[ 'data' ] ) ) {
		$data = implode( '', file ( $demo[ 'data' ] ) );
		$data = json_decode( base64_decode( $data ), true );

		/* Options. */
		update_option( ev_get_options_key(), $data[ 'options' ] );
		$options_imported = true;

		/* Mods. */
		$theme = get_option( 'stylesheet' );
		update_option( "theme_mods_$theme", $data[ 'mods' ] );

		$mods_imported = true;

		/* Custom sidebars. */
		update_option( 'agncy_sidebars', $data[ 'sidebars' ] );

		/* Widgets. */
		$sidebars_widgets = (array) get_option( 'sidebars_widgets' );

		if ( isset( $data[ 'widgets' ] ) ) {
			foreach ( $data[ 'widgets' ] as $widget ) {
				/* Save the widget data. */
				$widget_class = get_option( 'widget_' . $widget[ 'type' ] );

				if ( ! $widget_class ) {
					$widget_class = array(
						'1' => array(),
						'_multiwidget' => '1'
					);
				}

				$widget_index = count( $widget_class ) + 1;
				$widget_class[ $widget_index ] = $widget[ 'data' ];
				update_option( 'widget_' . $widget[ 'type' ], $widget_class );

				/* Store the widget in the sidebar. */
				if ( ! isset( $sidebars_widgets[ $widget[ 'sidebar' ] ] ) ) {
					$sidebars_widgets[ $widget[ 'sidebar' ] ] = array();
				}

				$sidebars_widgets[ $widget[ 'sidebar' ] ][] = $widget[ 'type' ] . '-' . $widget_index;
			}
		}

		update_option( 'sidebars_widgets', $sidebars_widgets );
	}

	/* Dummy content. */
	if ( isset( $demo[ 'dummy' ] ) ) {
		if ( ! defined( 'AGNCY_DEMOS_IMPORTER' ) ) {
			define( 'AGNCY_DEMOS_IMPORTER', true );
		}

		if ( function_exists( 'agncy_demos_importer_init' ) ) {
			agncy_demos_importer_init();

			if ( class_exists( 'WP_Import' ) ) {
				add_filter( 'http_request_host_is_external', 'agncy_demo_importer_host_is_external', 999 );
				$dummy = (array) $demo[ 'dummy' ];
				$wp_importer = null;

				ob_start();
					foreach ( $dummy as $d ) {
						if ( file_exists( $d ) ) {
							$wp_importer = new WP_Import();
							$wp_importer->fetch_attachments = true;
							$wp_importer->import( $d );
						}
					}
				ob_end_clean();

				remove_filter( 'http_request_host_is_external', 'agncy_demo_importer_host_is_external', 999 );
				add_action( 'shutdown', 'flush_rewrite_rules' );

				$dummy_imported = true;
			}
		}
	}

	/* Fired when the installation process has ended. */
	do_action( 'agncy_demo_installed', $options_imported, $mods_imported, $dummy_imported );

	return true;
}

/**
 * Temporarily allow HTTP requests from our own host.
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_demo_importer_host_is_external() {
	return true;
}

/* Refresh blog categorization on install. */
// add_action( 'agncy_demo_installed', 'agncy_refresh_categorized_transient' );