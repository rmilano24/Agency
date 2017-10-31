<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder html class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderHtmlBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the html.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'html';
		$this->_title = __( 'HTML', 'brix' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
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
			'handle' => 'type',
			'type' => 'select',
			'label' => __( 'Type', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => array(
					'snippet' => __( 'Code snippet', 'brix' ),
					'template' => __( 'Template', 'brix' )
				)
			)
		);

			$fields[] = array(
				'handle' => 'snippet',
				'label'  => array(
					'text' => __( 'Code', 'brix' ),
					'type' => 'block'
 				),
				'type'   => 'textarea',
				'help'	 => __( 'Code will be output as is. Supports shortcodes, but raw PHP is not allowed.', 'brix' ),
				'config' => array(
					'visible' => array( 'type' => 'snippet' ),
					'rows' => 10,
					'full' => true
				)
			);

			$fields[] = array(
				'handle' => 'template',
				'label'  => __( 'Template path', 'brix' ),
				'type'   => 'text',
				'help'	 => __( 'Relative to the current theme, or its parent.', 'brix' ),
				'config' => array(
					'full' => true,
					'visible' => array( 'type' => 'template' )
				)
			);

		return $fields;
	}

}

/**
 * Add the html to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_html_content_block( $blocks ) {
	$blocks['html'] = array(
		'class'       => 'BrixBuilderHtmlBlock',
		'label'       => __( 'HTML', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/html/i/html_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/html/i/html_icon.svg',
		'description' => __( 'Output HTML markup or import a specific template.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_html_content_block' );

/**
 * Define the appearance of the html in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_html_content_block_admin_template( $html, $data ) {
	$block_content = '';
	$type = '';

	if ( isset( $data->type ) ) {
		$type = $data->type;

		if ( $type === 'snippet' && isset( $data->snippet ) ) {
			$block_content = esc_html( __( 'Code snippet', 'brix' ) );
		}
		elseif ( $type === 'template' && isset( $data->template ) ) {
			$block_content = sprintf( __( 'Template: <code>%s</code>', 'brix' ), esc_html( $data->template ) );
		}
	}

	if ( $type && ! empty( $block_content ) ) {
		$html = $block_content;
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:html]', 'brix_html_content_block_admin_template', 10, 2 );