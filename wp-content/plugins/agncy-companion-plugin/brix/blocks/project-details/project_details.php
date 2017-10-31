<?php

/**
 * Builder project details content block class.
 *
* @since 1.0.0
 */
class AgncyProjectDetailsBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'agncy_project_details';
		$this->_title = __( 'Project details', 'agncy-companion-plugin' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
		add_filter( "brix_block_style_fields[type:{$this->_type}]", array( $this, 'style_fields' ) );
	}

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.0.0
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	public function fields( $fields )
	{
		$fields[] = array(
			'type' => 'multiple_select',
			'handle' => 'metas',
			'label' => __( 'Metas', 'agncy-companion-plugin' ),
			'help' => __( 'Multiple metas will be separated by commas.', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => array_merge( agncy_projects_metas(), array( '_category' => __( 'Category', 'agncy-companion-plugin' ) ) ),
			)
		);

		$fields[] = array(
            'type' => 'select',
            'label' => __( 'Display', 'agncy-companion-plugin' ),
            'handle' => 'display',
            'help' => __( 'Visual alignment of meta details.', 'agncy-companion-plugin' ),
            'config' => array(
                'data' => array(
					'horizontal' => __( 'Horizontal', 'agncy-companion-plugin' ),
					'vertical' => __( 'Vertical', 'agncy-companion-plugin' ),
				)
            ),
        );

		return $fields;
	}

}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function agncy_register_project_details_content_block( $blocks ) {
	$blocks['agncy_project_details'] = array(
		'class'       => 'AgncyProjectDetailsBlock',
		'label'       => __( 'Project details', 'agncy-companion-plugin' ),
		'icon'        => AGNCY_COMPANION_PLUGIN_URI . 'brix/blocks/project-details/i/project_details_icon.svg',
		'icon_path'   => AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/project-details/i/project_details_icon.svg',
		'description' => __( 'Display single project details.', 'agncy-companion-plugin' ),
		'group'       => __( 'Content', 'agncy-companion-plugin' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'agncy_register_project_details_content_block' );

/**
 * Define the appearance of the project details content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_project_details_content_block_admin_template( $html, $data ) {
	$html = '';
	$metas = explode( ',', $data->metas );

	$project_metas = agncy_projects_metas();
	$project_metas[ '_category' ] = __( 'Category', 'agncy-companion-plugin' );

	if ( $metas ) {
		$html .= '<ul>';

		foreach ( $metas as $meta ) {
				$html .= '<li>' . esc_attr( $project_metas[ $meta ] ) . '</li>';
		}

		$html .= '</ul>';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:agncy_project_details]', 'agncy_project_details_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the project details content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_project_details_content_block_stringified( $html, $data ) {
	$html = '';

	return $html;
}

add_filter( 'brix_block_stringified[type:agncy_project_details]', 'agncy_project_details_content_block_stringified', 10, 2 );

/**
 * Custom template path for the job block.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_project_details_block_template_path() {
	return AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/project-details/templates/project_details_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:agncy_project_details]', 'agncy_project_details_block_template_path' );