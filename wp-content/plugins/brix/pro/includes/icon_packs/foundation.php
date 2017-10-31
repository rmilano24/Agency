<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Include Foundation icons as an available icon font.
 *
 * @since 1.1.2
 * @param array $fonts
 * @return array
 */
function brix_styles_foundation( $fonts ) {
	$fonts['foundation'] = array(
		'label'   => 'Foundation',
		'name'    => 'foundation',
		'path'    => BRIX_PRO_FOLDER . 'assets/icon_packs/foundation',
		'url'     => BRIX_PRO_URI . 'assets/icon_packs/foundation',
		'prefix'  => 'fi',
		'mapping' => array()
	);

	return $fonts;
}

add_filter( 'brix_get_icon_fonts', 'brix_styles_foundation' );