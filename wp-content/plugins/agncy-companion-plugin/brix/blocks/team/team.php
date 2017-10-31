<?php

if ( ! defined( 'BRIX' ) ) {
	return;
}

/**
 * Builder team content block class.
 *
* @since 1.0.0
 */
class AgncyTeamBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'agncy_team';
		$this->_title = __( 'Team', 'agncy-companion-plugin' );

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
            'type' => 'select',
            'label' => __( 'Columns', 'agncy-companion-plugin' ),
            'handle' => 'columns',
            'help' => __( 'Number of columns the team grid will be arranged into.', 'agncy-companion-plugin' ),
            'config' => array(
                'data' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
				)
            ),
			'default' => 3
        );

		$fields[] = array(
			'type' => 'select',
			'label' => __( 'Image size', 'agncy-companion-plugin' ),
			'handle' => 'image_size',
			'help' => __( 'The image size of the team member images in the loop.', 'agncy-companion-plugin' ),
			'config' => array(
			    'data' => agncy_get_image_sizes_for_select()
			),
			'default' => 'full'
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
function agncy_register_team_content_block( $blocks ) {
	$blocks['agncy_team'] = array(
		'class'       => 'AgncyTeamBlock',
		'label'       => __( 'Team', 'agncy-companion-plugin' ),
		'icon'        => AGNCY_COMPANION_PLUGIN_URI . 'brix/blocks/team/i/team_icon.svg',
		'icon_path'   => AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/team/i/team_icon.svg',
		'description' => __( 'Display team members.', 'agncy-companion-plugin' ),
		'group'       => __( 'Content', 'agncy-companion-plugin' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'agncy_register_team_content_block' );

/**
 * Define the appearance of the team content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_team_content_block_admin_template( $html, $data ) {
	$html        = '';

	return $html;
}

add_filter( 'brix_block_admin_template[type:agncy_team]', 'agncy_team_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the team content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_team_content_block_stringified( $html, $data ) {
	$html = '';

	return $html;
}

add_filter( 'brix_block_stringified[type:agncy_team]', 'agncy_team_content_block_stringified', 10, 2 );

/**
 * Custom template path for the job block.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_team_block_template_path() {
	return AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/team/templates/team_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:agncy_team]', 'agncy_team_block_template_path' );