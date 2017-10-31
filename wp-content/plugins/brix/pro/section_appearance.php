<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Add specific fields to the section appearance.
 *
 * @since 1.1.3
 * @param array $fields An array of fields.
 * @return array
 */
function brix_section_appearance_fields( $fields ) {
	$fields[] = array(
		'handle' => '_fluid',
		'type' => 'checkbox',
		'label'  => __( 'Subsections are equally tall', 'brix' ),
		'help' => __( 'If active, the content areas that compose the section will have the same height.', 'brix' ),
		'config' => array(
			'style' => 'switch',
		)
	);

	return $fields;
}

add_filter( 'brix_section_appearance_fields', 'brix_section_appearance_fields' );


/**
 * Add more combinations for subsections.
 *
 * @since 1.0.0
 * @return array
 */
function brix_special_sections( $layouts ) {
	$layouts['1/2s 1/2']      = BRIX_PRO_ASSETS_URI . 'i/1-2s_1-2.svg';
	$layouts['1/2 1/2s']      = BRIX_PRO_ASSETS_URI . 'i/1-2_1-2s.svg';
	$layouts['1/4s 3/4']      = BRIX_PRO_ASSETS_URI . 'i/1-4s_3-4.svg';
	$layouts['3/4 1/4s']      = BRIX_PRO_ASSETS_URI . 'i/3-4_1-4s.svg';
	$layouts['1/4s 2/4 1/4s'] = BRIX_PRO_ASSETS_URI . 'i/1-4s_2-4_1-4s.svg';
	$layouts['1/3s 1/3 1/3s'] = BRIX_PRO_ASSETS_URI . 'i/1-3s_1-3_1-3s.svg';

	return $layouts;
}

add_filter( 'brix_section_layouts', 'brix_special_sections' );