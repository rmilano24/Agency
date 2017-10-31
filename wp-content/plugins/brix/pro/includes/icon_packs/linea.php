<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Include Linea icons as an available icon font.
 *
 * @since 1.1.3
 * @param array $fonts
 * @return array
 */
function brix_styles_linea( $fonts ) {
	$fonts['linea'] = array(
		'label'   => 'Linea',
		'name'    => 'linea',
		'path'    => BRIX_PRO_FOLDER . 'assets/icon_packs/linea',
		'url'     => BRIX_PRO_URI . 'assets/icon_packs/linea',
		'prefix'  => 'linea',
		'mapping' => array()
	);

	return $fonts;
}

add_filter( 'brix_get_icon_fonts', 'brix_styles_linea' );


/**
 * Define the frontend style for the linea icons
 *
 * @since 1.2
 * @param  string $style The frontend style
 * @param  array $icon the icon data array
 * @return string
 */
function brix_linea_icon_style( $style, $icon ) {
	if ( $icon['set'] == 'linea' ) {
		if ( $icon['color'] ) {
			$style = 'stroke:' . $icon['color'] . ';';
		} else {
			$style = 'stroke:currentColor;';
		}
	}

	return $style;
}

add_filter( 'brix_icon_style', 'brix_linea_icon_style', 10, 2 );