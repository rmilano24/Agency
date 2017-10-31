<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Plugin Name: Agncy Companion Plugin
 * Description: Agncy theme companion plugin.
 * Version: 1.0.0
 * Plugin URI:
 * Author: Evolve Themes
 * Author URI: https://justevolve.it/
 * License: GPL2
 * Text Domain: agncy-companion-plugin
 * Domain Path: /languages/
 *
 * Agncy Companion Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Agncy Companion Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @package   AgncyCompanionPlugin
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2017, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Boot the Absolute plugin.
 *
 * @since 1.0.0
 */
function agncy_companion_boot() {
	/* Main plugin constant. */
	define( 'AGNCY_COMPANION', true );

	/* Main plugin folder constant. */
	define( 'AGNCY_COMPANION_PLUGIN_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

	/* Main plugin URI constant. */
	define( 'AGNCY_COMPANION_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

	/* Load the text domain for plugin files. */
	load_plugin_textdomain( 'agncy-companion-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/* Admin assets. */
	add_action( 'admin_enqueue_scripts', 'agncy_companion_enqueue_admin', 15 );

	/* Frontend assets. */
	add_action( 'wp_enqueue_scripts', 'agncy_companion_enqueue_frontend' );

	/* Global helpers. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'helpers.php' );

	/* Sidebars. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'sidebars/sidebars.php' );

	/* Offices. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'post-types/offices/offices.php' );

	/* Team. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'post-types/team/team.php' );

	/* Jobs. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'post-types/jobs/jobs.php' );

	/* Projects. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'post-types/projects/projects.php' );

	/* Customizer. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'customizer/customizer.php' );

	/* Brix. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/brix.php' );

	/* Widgets. */
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'widgets/social_widget.php' );
	require_once( AGNCY_COMPANION_PLUGIN_FOLDER . 'widgets/office_widget.php' );
}

/**
 * Enqueue admin resources.
 *
 * @since 1.0.0
 */
function agncy_companion_enqueue_admin() {
	/* Style. */
	wp_enqueue_style( 'agncy-companion-css', AGNCY_COMPANION_PLUGIN_URI . 'assets/css/admin.css' );

	/* Script. */
	wp_enqueue_script( 'agncy-companion-js', AGNCY_COMPANION_PLUGIN_URI . 'assets/js/min/admin.js', array( 'ev-admin' ), null, true );
}

/**
 * Enqueue frontend resources.
 *
 * @since 1.0.0
 */
function agncy_companion_enqueue_frontend() {
	/* Script. */
	wp_enqueue_script( 'agncy-companion-js', AGNCY_COMPANION_PLUGIN_URI . 'assets/js/min/frontend.js', array( 'jquery' ), null, true );
}

/**
 * Load the Absolute plugin.
 *
 * @since 1.0.0
 */
function agncy_companion_load() {
	if ( ! class_exists( 'Ev_Framework' ) ) {
		return;
	}

	agncy_companion_boot();
}

add_action( 'plugins_loaded', 'agncy_companion_load', 100 );