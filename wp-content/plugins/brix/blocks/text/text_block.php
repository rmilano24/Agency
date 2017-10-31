<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder text content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderTextBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'text';
		$this->_title = __( 'Text', 'brix' );

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
		if ( brix_current_theme_supports( 'block_skin' ) ) {
			$fields[] = array(
				'handle'  => 'alternate_skin',
				'type'    => 'checkbox',
				'label'   => __( 'Alternate skin', 'brix' ),
				'help' => __( 'If checked, the color of the contents of the block will be inverted.', 'brix' ),
				'config' => array(
					'style' => array( 'switch', 'small' )
				),
				'default' => '0'
			);
		}

		$fields[] = array(
			'handle' => 'content',
			'type' => 'textarea',
			'label'  => array(
				'type' => 'block',
				'text' => __( 'Content', 'brix' )
			),
			'config' => array(
				'rich' => true,
				'rows' => 24
			)
		);

		$fields[] = array(
			'handle'  => 'fit_vids',
			'type'    => 'checkbox',
			'label'   => __( 'Fit embeds', 'brix' ),
			'help' => __( 'If checked, embeds will take the whole horizontal space available.', 'brix' ),
			'config' => array(
				'style' => array( 'switch', 'small' )
			),
			'default' => '1'
		);

		return $fields;
	}

	/**
	 * Check if the block is "empty".
	 *
	 * @since 1.2
	 * @param array $data The content block data.
	 * @return boolean
	 */
	protected function is_empty( $data )
	{
		return empty( $data->data->content );
	}

}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_text_content_block( $blocks ) {
	$blocks['text'] = array(
		'class'       => 'BrixBuilderTextBlock',
		'label'       => __( 'Text', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/text/i/text_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/text/i/text_icon.svg',
		'description' => __( 'A rich text area that supports embeds and shortcodes.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_text_content_block' );

/**
 * Add custom classes to the text content block, depending on its data.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @param stdClass $data The block data.
 * @return array
 */
function brix_text_content_block_classes( $classes, $data ) {
	if ( brix_current_theme_supports( 'block_skin' ) && isset( $data->alternate_skin ) && $data->alternate_skin == '1' ) {
		$classes[] = 'brix-alternate-skin';
	}

	if ( isset( $data->fit_vids ) && $data->fit_vids == '1' ) {
		$classes[] = 'brix-fit-vids';
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:text]', 'brix_text_content_block_classes', 10, 2 );

/**
 * Define the appearance of the text content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_text_content_block_admin_template( $html, $data ) {
	if ( isset( $data->content ) && ! empty( $data->content ) ) {
		$html = brix_format_text_content( $data->content, array(
			'shortcodes' => false
		) );
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:text]', 'brix_text_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the text content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_text_content_block_stringified( $html, $data ) {
	if ( isset( $data->content ) && ! empty( $data->content ) ) {
		$html = $data->content;
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:text]', 'brix_text_content_block_stringified', 10, 2 );