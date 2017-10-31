<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Custom fields for the advanced list.
 *
 * @since 1.1.3
 * @param array $fields An array of fields.
 * @return array
 */
function brix_list_block_advanced_list_fields( $fields ) {
	$fields[] = array(
		'handle' => 'advanced_list',
		'label' => array(
			'text' => __( 'List elements', 'brix' ),
			'type' => 'hidden'
		),
		'type' => 'bundle',
		'repeatable' => array(
			'sortable' => true
		),
		'config' => array(
			'visible' => array( 'list_type' => 'advanced' ),
		),
		'fields' => array(
			array(
				'handle' => 'title',
				'label' => __( 'Text', 'brix' ),
				'type' => 'text',
				'size' => 'two-thirds',
				'config' => array(
					'full' => true,
					'link' => true
				)
			),
			array(
				'handle' => 'icon',
				'label' => __( 'Icon', 'brix' ),
				'type' => 'icon',
				'size' => 'one-third'
			),
		)
	);

	return $fields;
}

add_filter( 'brix_list_block_fields', 'brix_list_block_advanced_list_fields' );

/**
 * Add the Advanced List type to the types of lists available.
 *
 * @since 1.1.3
 * @param array $types An array of list types.
 * @return array
 */
function brix_pro_advanced_list_type( $types ) {
	$types['advanced'] = __( 'Advanced list', 'brix' );

	return $types;
}

add_filter( 'brix_list_block_list_types', 'brix_pro_advanced_list_type' );