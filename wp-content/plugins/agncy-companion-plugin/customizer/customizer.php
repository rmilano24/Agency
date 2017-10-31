<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Enqueue the customizer style as an external stylesheet. If the external
 * stylesheet isn't there, falls back to an inline style block.
 *
 * @since 1.0.0
 */
function agncy_customizer_enqueue_external_style() {
	$upload_dir = wp_upload_dir();

	if ( file_exists( $upload_dir[ 'basedir' ] . '/agncy/customizer.css' ) ) {
		$customizer_file = $upload_dir[ 'baseurl' ] . '/agncy/customizer.css';
		$customizer_file_path = $upload_dir[ 'basedir' ] . '/agncy/customizer.css';
		$modified_time = filectime( $customizer_file_path );

		wp_enqueue_style( 'agncy-customizer', $customizer_file, array( 'agncy-style' ), $modified_time );
	}
	else {
		agncy_customizer_add_inline_style();
	}
}

add_action( 'agncy_customizer_external_file', 'agncy_customizer_enqueue_external_style' );

/**
 * Whenever the customizer is saved, store the theme modifications in a specific
 * file in the uploads folder.
 *
 * @since 1.0.0
 */
function agncy_customizer_saved() {
	if ( ! function_exists( 'agncy_get_customizer_output' ) ) {
		return;
	}

	$upload_dir = wp_upload_dir();
	$target_folder = $upload_dir[ 'basedir' ] . '/agncy';
	$target_file = $target_folder . '/customizer.css';
	$file_created = false;

	if ( is_writable( $upload_dir[ 'basedir' ] ) ) {
		if ( ! file_exists( $target_folder ) ) {
			@mkdir( $target_folder, 0755, true );
		}

		@unlink( $target_file );

		$style = agncy_get_customizer_output();

		@file_put_contents( $target_file, $style );
		@chmod( $target_file, 0644 );
	}

}

add_action( 'customize_save_after', 'agncy_customizer_saved' );