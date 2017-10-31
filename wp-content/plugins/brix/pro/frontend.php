<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Add scripts and styles on frontend.
 *
 * @since 1.0.0
 */
function brix_pro_add_frontend_assets() {
	$suffix = brix_get_scripts_suffix();

	/* Main builder frontend style. */
	brix_fw()->frontend()->add_style( 'brix-pro-frontend-style', BRIX_PRO_URI . 'assets/frontend/css/style.css', array( 'brix-style' ) );

	/* Main builder JavaScript controller. */
	$deps = apply_filters( 'brix_pro_frontend_script_dependencies', array( 'brix-script' ) );

	brix_fw()->frontend()->add_script( 'brix-pro-frontend-script', BRIX_PRO_URI . 'assets/frontend/js/min/builder.pro.' . $suffix . '.js', $deps );
}

/* Add scripts and styles on frontend. */
add_action( 'init', 'brix_pro_add_frontend_assets' );