<?php

/**
 * Builder divider block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderDividerBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the divider block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'divider';
		$this->_title = __( 'Divider', 'brix' );

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
		return $fields;
	}

}

/**
 * Add the divider content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_divider_block( $blocks ) {
	$blocks['divider'] = array(
		'class'       => 'BrixBuilderDividerBlock',
		'label'       => __( 'Divider', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/divider/i/divider_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/divider/i/divider_icon.svg',
		'description' => __( 'An horizontal spacer element.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_divider_block' );

/**
 * Define the appearance of the divider block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_divider_content_block_admin_template( $html, $data ) {
	$class = '';

	if ( isset( $data->_style ) ) {
		$class = 'brix-divider-block-style-' . $data->_style;
	}

	$html = '<span class="brix-divider-block-divider ' . $class . '"></span>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:divider]', 'brix_divider_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the divider content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_divider_content_block_stringified( $html, $data ) {
	$html = '<hr>';

	return $html;
}

add_filter( 'brix_block_stringified[type:divider]', 'brix_divider_content_block_stringified', 10, 2 );