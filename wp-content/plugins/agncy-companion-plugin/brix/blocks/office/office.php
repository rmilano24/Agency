<?php

/**
 * Builder office content block class.
 *
* @since 1.0.0
 */
class AgncyOfficeBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'agncy_office';
		$this->_title = __( 'Office', 'agncy-companion-plugin' );

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
            'label' => __( 'Office', 'agncy-companion-plugin' ),
            'handle' => 'office_id',
            'help' => __( 'The office to pick up data from.', 'agncy-companion-plugin' ),
            'config' => array(
                'controller' => true,
                'data' => agncy_get_offices_for_select()
            )
        );

		$fields[] = array(
            'type' => 'select',
            'label' => __( 'Media', 'agncy-companion-plugin' ),
            'help' => __( 'Select which media to display above the office info.', 'agncy-companion-plugin' ),
            'handle' => 'media',
            'config' => array(
                'controller' => true,
				'visible' => array( 'office_id' => '!=0' ),
                'data' => array(
					'0'     => '--',
					'image' => __( 'Office image', 'agncy-companion-plugin' ),
					'map'   => __( 'Office map', 'agncy-companion-plugin' )
				)
            )
        );

        $fields[] = array(
        	'type' => 'select',
        	'label' => __( 'Image size', 'agncy-companion-plugin' ),
        	'handle' => 'image_size',
        	'help' => __( 'The image size of the office image.', 'agncy-companion-plugin' ),
        	'config' => array(
        	    'data' => agncy_get_image_sizes_for_select(),
        	    'visible' => array( 'media' => 'image' ),
        	),
        	'default' => 'full'
        );

        $fields[] = array(
            'type' => 'number',
            'label' => __( 'Map zoom', 'agncy-companion-plugin' ),
            'handle' => 'map_zoom',
            'help' => __( 'Display a map to the office (office coordinates must be set).', 'agncy-companion-plugin' ),
            'default' => true,
            'default' => 14,
            'config' => array(
                'visible' => array( 'media' => 'map' ),
                'min' => '1',
                'max' => '16',
            )
        );

        $fields[] = array(
            'type' => 'checkbox',
            'label' => __( 'Show address', 'agncy-companion-plugin' ),
            'handle' => 'address',
            'help' => __( 'Display the textual address the office.', 'agncy-companion-plugin' ),
            'default' => true,
            'config' => array(
                'visible' => array( 'office_id' => '!=0' ),
                'style' => array( 'switch', 'small' )
            )
        );

        $fields[] = array(
			'type' => 'checkbox',
			'handle' => 'contacts',
			'label' => __( 'Show contacts', 'agncy-companion-plugin' ),
			'help' => __( 'Display the contact information for the office.', 'agncy-companion-plugin' ),
            'config' => array(
                'visible' => array( 'office_id' => '!=0' ),
				'style' => array( 'switch', 'small' )
			)
		);

        $fields[] = array(
			'type' => 'select',
			'handle' => 'page_id',
			'label' => __( 'Link to page', 'agncy-companion-plugin' ),
			'help' => __( 'Select a page you want to link to within the website.', 'agncy-companion-plugin' ),
			'config' => array(
                'visible' => array( 'office_id' => '!=0' ),
				'data' => agncy_get_pages_for_select()
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
function agncy_register_office_content_block( $blocks ) {
	$blocks['agncy_office'] = array(
		'class'       => 'AgncyOfficeBlock',
		'label'       => __( 'Office', 'agncy-companion-plugin' ),
		'icon'        => AGNCY_COMPANION_PLUGIN_URI . 'brix/blocks/office/i/office_icon.svg',
		'icon_path'   => AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/office/i/office_icon.svg',
		'description' => __( 'Display data from an office.', 'agncy-companion-plugin' ),
		'group'       => __( 'Content', 'agncy-companion-plugin' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'agncy_register_office_content_block' );

/**
 * Define the appearance of the testimonial content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_office_content_block_admin_template( $html, $data ) {
	$html        = '';

	$office_id = isset( $data->office_id ) && $data->office_id ? $data->office_id : 0;

    if ( $office_id && get_post( $office_id ) ) {
        $html .= sprintf(
            __( '%s office.', 'agncy-companion-plugin' ),
            get_the_title( $office_id )
        );
    }

	return $html;
}

add_filter( 'brix_block_admin_template[type:agncy_office]', 'agncy_office_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the office content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_office_content_block_stringified( $html, $data ) {
	$html = '';

	return $html;
}

add_filter( 'brix_block_stringified[type:agncy_office]', 'agncy_office_content_block_stringified', 10, 2 );

/**
 * Custom template path for the office block.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_office_block_template_path() {
	return AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/office/templates/office_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:agncy_office]', 'agncy_office_block_template_path' );

/**
 * Load map resources.
 *
 * @since 1.0.0
 */
function agncy_office_block_resources( $data ) {
	$api_key = ev_get_option( 'google_maps_api_key' );

	if ( empty( $api_key ) ) {
		return;
	}

    if ( $data->media != 'map' ) {
        return;
    }

    if ( $data->office_id ) {
        $latlong = get_post_meta( $data->office_id, 'latlong', true );

        if ( ! $latlong ) {
            return;
        }
    }

    wp_enqueue_script( 'agncy-google-maps', "//maps.googleapis.com/maps/api/js?key=$api_key&callback=agncy_office_init_map", array( 'agncy-companion-js' ), null, true );
}

add_action( 'brix_load_block_resources[type:agncy_office]', 'agncy_office_block_resources' );