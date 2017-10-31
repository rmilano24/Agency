<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder icon class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderIconBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the icon.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'icon';
		$this->_title = __( 'Icon', 'brix' );

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
		$fields[] = brix_icon_bundle( 'icon', __( 'Icon', 'brix' ) );

		$fields[] = array(
			'handle' => 'icon_alignment',
			'type' => 'select',
			'label' => __( 'Alignment', 'brix' ),
			'config' => array(
				'data' => array(
					'left'  => __( 'Left', 'brix' ),
					'center'  => __( 'Center', 'brix' ),
					'right' => __( 'Right', 'brix' )
				)
			)
		);

		$fields[] = brix_link_bundle( 'link', __( 'Link', 'brix' ) );

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
		$icon_empty = false;
		$icon_data = $data->data->icon;

		if ( isset( $icon_data->type ) && $icon_data->type == 'image' ) {
			if ( isset( $icon_data->image ) && empty( $icon_data->image->desktop[1]->id ) ) {
				$icon_empty = true;
			}
		}

		if ( isset( $icon_data->type ) && $icon_data->type == 'icon' ) {
			if ( isset( $icon_data->icon->icon ) && empty( $icon_data->icon->icon ) ) {
				$icon_empty = true;
			}
		}

		return $icon_empty;
	}

}

/**
 * Add the icon to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_icon_content_block( $blocks ) {
	$blocks['icon'] = array(
		'class'       => 'BrixBuilderIconBlock',
		'label'       => __( 'Icon', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/icon/i/icon_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/icon/i/icon_icon.svg',
		'description' => __( 'Display an icon.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_icon_content_block' );

/**
 * Define the appearance of the icon in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_icon_content_block_admin_template( $html, $data ) {
	$icon = brix_get_decoration( $data->icon );
	$block_admin_classes = brix_icon_builder_block_class( array(), $data );

	$html = sprintf( '<div class="%s">', esc_attr( implode( ' ', $block_admin_classes ) ) );
		$html .= $icon;
	$html .= '</div>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:icon]', 'brix_icon_content_block_admin_template', 10, 2 );

/**
 * Add a specific CSS class to the icon block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_icon_builder_block_class( $classes, $data ) {
	$alignment = isset( $data ) && isset( $data->icon_alignment ) ? $data->icon_alignment : 'left';

	$classes[] = 'brix-icon-alignment-' . $alignment;

	return $classes;
}

add_filter( 'brix_block_classes[type:icon]', 'brix_icon_builder_block_class', 10, 2 );

/**
 * Define the appearance of the icon content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_icon_content_block_stringified( $html, $data ) {
	$html = '';

	if ( isset( $data->icon ) ) {
		$html .= brix_get_decoration( $data->icon );
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:icon]', 'brix_icon_content_block_stringified', 10, 2 );

/**
 * Add the required inline styles for the list builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_icon_frontend_block( $block_style, $block, $block_selector ) {
	$icon_alignment = isset( $block->data ) && isset( $block->data->icon_alignment ) ? $block->data->icon_alignment : 'left';
	$icon           = isset( $block->data ) && isset( $block->data->icon->icon ) ? $block->data->icon->icon : '';
	$decoration     = isset( $block->data ) && isset( $block->data->icon ) ? $block->data->icon : '';

	if ( $icon_alignment ) {

		if ( $icon_alignment == 'center' ) {
			$block_style .= $block_selector . ' {';
				$block_style .= 'text-align:' . $icon_alignment . ';';
			$block_style .= '}';
		}

		if ( $icon_alignment == 'right' ) {
			$block_style .= $block_selector . ' {';
				$block_style .= 'text-align:' . $icon_alignment . ';';
			$block_style .= '}';
		}
	}

	if ( $decoration->type === 'icon' ) {
		$block_style .= brix_icon_style( $block_selector, $icon );
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:icon]', 'brix_process_icon_frontend_block', 10, 3 );