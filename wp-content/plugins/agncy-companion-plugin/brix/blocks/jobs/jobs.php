<?php

/**
 * Builder jobs content block class.
 *
* @since 1.0.0
 */
class AgncyJobBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'agncy_job';
		$this->_title = __( 'Job', 'agncy-companion-plugin' );

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
            'label' => __( 'Job', 'agncy-companion-plugin' ),
            'handle' => 'office_id',
            'help' => __( 'The office to pick up data from.', 'agncy-companion-plugin' ),
            'config' => array(
                'controller' => true,
                'data' => agncy_get_offices_for_select()
            )
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
function agncy_register_job_content_block( $blocks ) {
	$blocks['agncy_job'] = array(
		'class'       => 'AgncyJobBlock',
		'label'       => __( 'Job Listings', 'agncy-companion-plugin' ),
		'icon'        => AGNCY_COMPANION_PLUGIN_URI . 'brix/blocks/jobs/i/job_icon.svg',
		'icon_path'   => AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/jobs/i/job_icon.svg',
		'description' => __( 'Display jobs from an office.', 'agncy-companion-plugin' ),
		'group'       => __( 'Content', 'agncy-companion-plugin' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'agncy_register_job_content_block' );

/**
 * Define the appearance of the testimonial content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_job_content_block_admin_template( $html, $data ) {
	$html        = '';

    $office_id = isset( $data->office_id ) && $data->office_id ? $data->office_id : 0;

    if ( $office_id && get_post( $office_id ) ) {
        $html .= sprintf(
            __( 'Displaying all job liststings from the %s office.', 'agncy-companion-plugin' ),
            get_the_title( $office_id )
        );
    }
    else {
        $html .= __( 'Displaying all the job liststings.', 'agncy-companion-plugin' );
    }

	return $html;
}

add_filter( 'brix_block_admin_template[type:agncy_job]', 'agncy_job_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the job content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_job_content_block_stringified( $html, $data ) {
	$html = '';

	return $html;
}

add_filter( 'brix_block_stringified[type:agncy_job]', 'agncy_job_content_block_stringified', 10, 2 );

/**
 * Custom template path for the job block.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_job_block_template_path() {
	return AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/jobs/templates/jobs_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:agncy_job]', 'agncy_job_block_template_path' );