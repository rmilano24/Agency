<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Render the Registration page content.
 *
 * @since 1.1.2
 */
function brix_registration_page() {
	echo '<div class="brix-admin-page-content">';
		brix_registration_page_content();
	echo '</div>';
}

add_action( 'brix_admin_page_content[page:brix_registration]', 'brix_registration_page' );

/**
 * Render the Registration page.
 *
 * @since 1.1.2
 */
function brix_registration_page_content() {
	echo '<div class="brix-admin-page-col brix-admin-page-col-left">';
		brix_registration_page_details_help();
	echo '</div>';

	echo '<div class="brix-admin-page-col brix-admin-page-col-right">';
		brix_registration_page_details();
	echo '</div>';
}

/**
 * Updates help text.
 *
 * @since 1.0.0
 */
function brix_registration_page_details_help() {
	echo '<div class="brix-maintenance-details-help">';
		echo '<h2>' . esc_html( __( 'Stay up to date', 'brix' ) ) . '</h2>';
		echo '<p>' . esc_html( __( 'Brix is updated regularly, bringing in new features, providing security enhancements, fixing bugs, and generally be compatible with the latest and greatest WordPress version available.', 'brix' ) ) . '</p>';
		echo '<p>' . wp_kses_post( __( 'Enable the automatic update system by inserting your <strong>Purchase Code</strong> and <strong>email</strong>.', 'brix' ) ) . '</p>';
		echo '<p>' . wp_kses_post( __( 'You are entitled to Brix updates if you have a valid support account over at <a href="https://justevolve.it" target="_blank" rel="noopener noreferrer">Evolve Shop</a>. If your support account expires, you can renew your plan with additional months of support, re-gaining the ability to download updates.', 'brix' ) ) . '</p>';
	echo '</div>';
}