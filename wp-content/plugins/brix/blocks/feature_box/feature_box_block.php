<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder feature box block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderFeatureBoxBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the feature box block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'feature_box';
		$this->_title = __( 'Feature box', 'brix' );

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
			'handle' => 'layout',
			'type' => 'radio',
			'label' => __( 'Layout', 'brix' ),
			'config' => array(
				'style' => 'graphic',
				'data' => array(
					'left_inline'  => BRIX_URI . 'blocks/feature_box/i/left_inline.svg',
					'left_fixed'   => BRIX_URI . 'blocks/feature_box/i/left_fixed.svg',
					'right_inline' => BRIX_URI . 'blocks/feature_box/i/right_inline.svg',
					'right_fixed'  => BRIX_URI . 'blocks/feature_box/i/right_fixed.svg',
					'centered'     => BRIX_URI . 'blocks/feature_box/i/centered.svg',
				)
			)
		);

		$fields[] = brix_icon_bundle( 'decoration', __( 'Icon', 'brix' ), array( 'modal' => false ) );

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
				'rows' => 16
			)
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
		$feature_empty = false;
		$content       = $data->data->content;
		$decoration    = $data->data->decoration;

		if ( empty( $content ) ) {
			if ( $decoration->type == 'icon' && empty( $decoration->icon->icon ) ) {
				$feature_empty = true;
			}
			if ( $decoration->type == 'image' && empty( $decoration->image->desktop[1]->id ) ) {
				$feature_empty = true;
			}
		}

		return $feature_empty;
	}

}

/**
 * Add the feature box block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_feature_box_content_block( $blocks ) {
	$blocks['feature_box'] = array(
		'class'       => 'BrixBuilderFeatureBoxBlock',
		'label'       => __( 'Feature box', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/feature_box/i/feature_box_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/feature_box/i/feature_box_icon.svg',
		'description' => __( 'Engaging text boxes with icon.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_feature_box_content_block' );

/**
 * Define the appearance of the feature box block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_feature_box_content_block_admin_template( $html, $data ) {
	if ( isset( $data->content ) && ! empty( $data->content ) ) {
		if ( isset( $data->_style ) ) {
			$html .= '<span class="brix-block-style-label">' . __( 'Style', 'brix' ) . ': <strong>' . $data->_style . '</strong></span>';
		}

		$block_admin_classes = brix_feature_box_alignment_class( array(), $data );

		$content = sprintf( '<div class="%s">', esc_attr( implode( ' ', $block_admin_classes ) ) );

			$content .= brix_get_decoration( $data->decoration );

			$content .= '<div class="brix-block-feature-box-content-wrapper">';
				$content .= '<div class="brix-block-content">';
					$content .= brix_format_text_content( $data->content );
				$content .= '</div>';
			$content .= '</div>';

		$content .= '</div>';

		$html .= $content;
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:feature_box]', 'brix_feature_box_content_block_admin_template', 10, 2 );

/**
 * Feature box block frontend alignment class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_feature_box_alignment_class( $classes, $data ) {
	if ( brix_current_theme_supports( 'block_skin' ) && isset( $data->alternate_skin ) && $data->alternate_skin == '1' ) {
		$classes[] = 'brix-alternate-skin';
	}

	if ( isset( $data->layout ) && ! empty( $data->layout ) ) {
		$classes[] = 'brix-block-feature-box-layout-' . $data->layout;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:feature_box]', 'brix_feature_box_alignment_class', 10, 2 );

/**
 * Define the appearance of the feature box content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_feature_box_content_block_stringified( $html, $data ) {
	$html = '';

	if ( isset( $data->decoration ) ) {
		$html .= brix_get_decoration( $data->decoration );
	}

	if ( isset( $data->content ) && ! empty( $data->content ) ) {
		$html .= $data->content;
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:feature_box]', 'brix_feature_box_content_block_stringified', 10, 2 );

/**
 * Add the required inline styles for the icon of the feature box builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_feature_box_icon_frontend_block( $block_style, $block, $block_selector ) {
	$decoration = isset( $block->data ) && isset( $block->data->decoration ) ? $block->data->decoration : '';
	$icon       = isset( $block->data ) && isset( $block->data->decoration->icon ) ? $block->data->decoration->icon : '';

	if ( $decoration->type === 'icon' ) {
		$block_style .= brix_icon_style( $block_selector, $icon );
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:feature_box]', 'brix_process_feature_box_icon_frontend_block', 10, 3 );