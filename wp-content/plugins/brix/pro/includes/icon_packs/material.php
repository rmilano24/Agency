<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Include Material design icons as an available icon font.
 *
 * @since 1.1.2
 * @param array $fonts
 * @return array
 */
function brix_styles_material( $fonts ) {
	$fonts['material'] = array(
		'label'   => 'Material design',
		'name'    => 'material',
		'path'    => BRIX_PRO_FOLDER . 'assets/icon_packs/material',
		'url'     => BRIX_PRO_URI . 'assets/icon_packs/material',
		'prefix'  => 'ic',
		'mapping' => array()
	);

	return $fonts;
}

add_filter( 'brix_get_icon_fonts', 'brix_styles_material' );