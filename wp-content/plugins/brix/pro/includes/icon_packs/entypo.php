<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Include Entypo as an available icon font.
 *
 * @since 1.1.2
 * @param array $fonts
 * @return array
 */
function brix_styles_entypo( $fonts ) {
	$fonts['entypo'] = array(
		'label'   => 'Entypo',
		'name'    => 'entypo',
		'path'    => BRIX_PRO_FOLDER . 'assets/icon_packs/entypo',
		'url'     => BRIX_PRO_URI . 'assets/icon_packs/entypo',
		'prefix'  => 'entypo',
		'mapping' => array()
	);

	return $fonts;
}

add_filter( 'brix_get_icon_fonts', 'brix_styles_entypo' );