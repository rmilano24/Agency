<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Add the "Advanced" tab to the block editing modal.
 *
 * @since 1.1.3
 * @param array $fields An array of fields.
 * @return array
 */
function brix_pro_blocks_advanced_tab( $fields ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'block_advanced',
		'label' => __( 'Advanced', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'class',
				'label'  => __( 'Class', 'brix' ),
				'help' => __( 'This CSS class name will be associated to the block markup element. Put just the class name here, not any CSS style: use a custom stylesheet to target this element via the class name.', 'brix' ),
				'type'   => 'text'
			),
			// array(
			// 	'handle' => 'section',
			// 	'label'  => __( 'Section', 'brix' ),
			// 	'help'   => __( 'Marking this area with a <code>section</code> tag instead of <code>div</code> will not alter the page appearance but will affect the document structure and the page SEO.', 'brix' ),
			// 	'type'   => 'checkbox'
			// ),
			array(
				'handle' => '_hidden',
				'label'  => __( 'Hide from display', 'brix' ),
				'help' => __( 'When hidden, the block data will still be editable.', 'brix' ),
				'type'   => 'checkbox'
			),
		)
	);

	return $fields;
}

add_filter( 'brix_block', 'brix_pro_blocks_advanced_tab' );

/**
 * Add the "Advanced" tab to the column editing modal.
 *
 * @since 1.1.3
 * @param array $fields An array of fields.
 * @return array
 */
function brix_pro_columns_advanced_tab( $fields ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'column_advanced',
		'label' => __( 'Advanced', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'class',
				'label'  => __( 'Class', 'brix' ),
				'type'   => 'text'
			),
			array(
				'handle' => 'section',
				'label'  => __( 'Section', 'brix' ),
				'help'   => __( 'Marking this area with a <code>section</code> tag instead of <code>div</code> will not alter the page appearance but will affect the document structure and the page SEO.', 'brix' ),
				'type'   => 'checkbox'
			),
		)
	);

	return $fields;
}

add_action( 'brix_column', 'brix_pro_columns_advanced_tab' );

/**
 * Add the "Advanced" tab to the section editing modal.
 *
 * @since 1.1.3
 * @param array $fields An array of fields.
 * @return array
 */
function brix_pro_sections_advanced_tab( $fields ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'section_advanced',
		'label' => __( 'Advanced', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'id',
				'label'  => __( 'Id', 'brix' ),
				'type'   => 'text',
				'help'   => __( "You'll be able to jump directly to this section adding <code>#your-id</code> to the window address.", 'brix' )
			),
			array(
				'handle' => 'class',
				'label'  => __( 'Class', 'brix' ),
				'type'   => 'text'
			),
			array(
				'handle' => 'section',
				'label'  => __( 'Section', 'brix' ),
				'help'   => __( 'Marking this area with a <code>section</code> tag instead of <code>div</code> will not alter the page appearance but will affect the document structure and the page SEO.', 'brix' ),
				'type'   => 'checkbox'
			),
			array(
				'handle' => '_hidden',
				'label'  => __( 'Hide from display', 'brix' ),
				'type'   => 'checkbox'
			),
		)
	);

	return $fields;
}

add_action( 'brix_section', 'brix_pro_sections_advanced_tab' );