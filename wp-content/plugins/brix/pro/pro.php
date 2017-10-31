<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/* Pro functionalities constant. */
define( 'BRIX_PRO', true );

/* Pro folder. */
define( 'BRIX_PRO_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

/* Pro folder. */
define( 'BRIX_PRO_URI', trailingslashit( BRIX_URI . 'pro' ) );

/* Pro assets URI. */
define( 'BRIX_PRO_ASSETS_URI', trailingslashit( BRIX_URI . 'pro/assets' ) );

/* Updater. */
require_once( BRIX_PRO_FOLDER . 'updater/updater.php' );

/* Admin pages and utilities. */
require_once( BRIX_PRO_FOLDER . 'admin.php' );

/* Frontend resources. */
require_once( BRIX_PRO_FOLDER . 'frontend.php' );

/* Welcome page. */
require_once( BRIX_PRO_FOLDER . 'includes/pages/welcome.php' );

/* Registration page. */
require_once( BRIX_PRO_FOLDER . 'includes/pages/registration.php' );

/* Support page. */
require_once( BRIX_PRO_FOLDER . 'includes/pages/support.php' );

/* Icon packs. */
require_once( BRIX_PRO_FOLDER . 'includes/icons.php' );

/* Rows editing. */
require_once( BRIX_PRO_FOLDER . 'row-editing.php' );

/* Responsive features. */
require_once( BRIX_PRO_FOLDER . 'responsive.php' );

/* User roles features. */
require_once( BRIX_PRO_FOLDER . 'user_roles.php' );

/* Background features. */
require_once( BRIX_PRO_FOLDER . 'background.php' );

/* Advanced tab for blocks, columns and sections. */
require_once( BRIX_PRO_FOLDER . 'advanced_tab.php' );

/* Section appearance. */
require_once( BRIX_PRO_FOLDER . 'section_appearance.php' );

/* Fields. */
require_once( BRIX_PRO_FOLDER . 'blocks/list/list_block.php' );
require_once BRIX_PRO_FOLDER . 'blocks/gallery/fields/brix_media.php';

/* Progress bar block. */
require_once( BRIX_PRO_FOLDER . 'blocks/progress_bar/progress_bar_block.php' );

/* Counter block. */
require_once( BRIX_PRO_FOLDER . 'blocks/counter/counter_block.php' );

/* Team member block. */
require_once( BRIX_PRO_FOLDER . 'blocks/team/team_block.php' );

/* Gallery block. */
require_once( BRIX_PRO_FOLDER . 'blocks/gallery/gallery_block.php' );

/* Column carousel. */
require_once( BRIX_PRO_FOLDER . 'carousel.php' );

/* Templates. */
require_once( BRIX_PRO_FOLDER . 'templates.php' );

/* Dummy templates. */
require_once( BRIX_PRO_FOLDER . 'dummy-templates.php' );

/* Frontend editing. */
require_once( BRIX_PRO_FOLDER . 'frontend_editing/frontend_editing.php' );

/* Blocks utilities. */
require_once( BRIX_PRO_FOLDER . 'blocks/blocks.php' );

/* WooCommerce integration */
require_once( BRIX_PRO_FOLDER . 'woocommerce/woocommerce.php' );

/**
 * Load additional styles for Pro version
 *
 * @since 1.2.7
 */
function brix_pro_load_styles() {
	/* Styles */
	if ( BRIX_STYLES ) {
		require_once( BRIX_PRO_FOLDER . 'styles/styles.php' );
	}
}

add_action( 'init', 'brix_pro_load_styles', 11 );