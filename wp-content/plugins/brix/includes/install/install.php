<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Creation of the "Install" admin page.
 *
 * @since 1.0.0
 */
function brix_install_install_page() {
	if ( brix_install_check() ) {
		return;
	}

	$label = apply_filters( 'brix_install_install_page_label', 'Brix' );

	$capability = 'manage_options';
	$slug       = 'brix';
	$callback   = 'brix_install_procedure';

	add_menu_page(
		$label,
		$label,
		$capability,
		$slug,
		$callback,
		BRIX_INSTALL_URI . 'assets/admin/css/i/brix_menu_icon.svg'
	);
}

add_action( 'admin_menu', 'brix_install_install_page', 100 );

/**
 * Welcome page after installation.
 *
 * @since 1.0.0
 */
function brix_install_procedure() {
	echo '<div class="brix-welcome-page">';
		brix_welcome_page();
	echo '</div>';

	/* Declare Brix installed. */
	brix_maybe_install_complete();
}

add_action( 'brix_admin_page_content[page:brix]', 'brix_install_procedure' );