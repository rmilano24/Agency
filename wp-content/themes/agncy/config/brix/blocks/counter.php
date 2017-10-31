<?php

/**
 * Custom styles for the gallery builder block in Agncy.
 *
 * @since 1.0.0
 * @param array $styles The gallery builder block styles.
 * @return array
 */
function agncy_brix_styles_counter_block_styles( $styles ) {
	$new_styles            = array();
	$new_styles['default'] = __( 'Standard', 'agncy' );
	$new_styles['boxed-border']   = __( 'Boxed with border', 'agncy' );
	$new_styles['boxed-filled']   = __( 'Boxed filled', 'agncy' );

	return $new_styles;
}

add_filter( 'brix_block_styles[type:counter]', 'agncy_brix_styles_counter_block_styles' );