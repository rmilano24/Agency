<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Add an update notice to the admin static notices queue.
 *
 * @since 0.1.0
 * @param string $message The notice message.
 */
function brix_updated_notice( $message ) {
	brix_fw()->admin()->add_notice( $message, 'updated' );
}

/**
 * Add an error notice to the admin static notices queue.
 *
 * @since 0.1.0
 * @param string $message The notice message.
 */
function brix_error_notice( $message ) {
	brix_fw()->admin()->add_notice( $message, 'error' );
}

/**
 * Add a static notice to the admin static notices queue.
 *
 * @since 0.1.0
 * @param string $message The notice message.
 */
function brix_static_notice( $message ) {
	brix_fw()->admin()->add_notice( $message, 'brix-static-notice' );
}

/**
 * Add an update nag notice to the admin static notices queue.
 *
 * @since 0.1.0
 * @param string $message The notice message.
 */
function brix_update_nag_notice( $message ) {
	brix_fw()->admin()->add_notice( $message, 'update-nag' );
}