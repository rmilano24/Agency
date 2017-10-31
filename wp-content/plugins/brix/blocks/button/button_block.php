<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder button class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderButtonBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the button.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'button';
		$this->_title = __( 'Button', 'brix' );

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
			'handle' => 'label',
			'label'  => __( 'Label', 'brix' ),
			'type'   => 'text',
			'config' => array(
				'full' => true
			)
		);

		$fields[] = brix_link_bundle( 'link', __( 'Link', 'brix' ) );

		$fields[] = array(
			'handle' => 'button_alignment',
			'type' => 'select',
			'label' => __( 'Alignment', 'brix' ),
			'config' => array(
				'responsive' => true,
				'data' => array(
					'left'  => __( 'Left', 'brix' ),
					'center'  => __( 'Center', 'brix' ),
					'right' => __( 'Right', 'brix' )
				),
			),
			'default' => array(
				'desktop' => 'left',
				'mobile' => 'center'
			)
		);

		$fields[] = brix_icon_bundle( 'icon', __( 'Icon', 'brix' ) );

		$fields[] = array(
			'handle' => 'icon_position',
			'type' => 'select',
			'label' => __( 'Icon position', 'brix' ),
			'config' => array(
				'data' => array(
					'left'  => __( 'Left', 'brix' ),
					'right' => __( 'Right', 'brix' )
				),
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
		return empty( $data->data->label );
	}

}

/**
 * Add the button to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_button_content_block( $blocks ) {
	$blocks['button'] = array(
		'class'       => 'BrixBuilderButtonBlock',
		'label'       => __( 'Button', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/button/i/button_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/button/i/button_icon.svg',
		'description' => __( 'Add a call to action button.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_button_content_block' );

/**
 * Add a specific CSS class to the button block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_button_builder_block_class( $classes, $data ) {
	return $classes;
}

add_filter( 'brix_block_classes[type:button]', 'brix_button_builder_block_class', 10, 2 );

/**
 * Define the appearance of the button in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_button_content_block_admin_template( $html, $data ) {
	$block_admin_classes = brix_button_builder_block_class( array(), $data );

	if ( isset( $data->button_alignment ) && ! empty( $data->button_alignment->desktop ) )  {
		$block_admin_classes[] = 'brix-button-alignment-' . $data->button_alignment->desktop;
	}

	if ( isset( $data->label ) && ! empty( $data->label ) ) {

		$style = '';

		if ( isset( $data->button_shape ) && $data->button_shape == 'rounded' ) {
			if ( isset( $data->shape_border ) ) {
				$style = 'style="border-radius:' . $data->shape_border . ';"';
			}
		}

		$html = sprintf( '<div class="%s">', esc_attr( implode( ' ', $block_admin_classes ) ) );
			$html .= '<div class="brix-block-button-element" ' . $style . '>';
				$html .= esc_html( $data->label );
			$html .= '</div>';
		$html .= '</div>';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:button]', 'brix_button_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the button content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_button_content_block_stringified( $html, $data ) {
	$label  = isset( $data->label ) && ! empty( $data->label ) ? $data->label : '';
	$href   = isset( $data->link->url ) && ! empty( $data->link->url ) ? $data->link->url : '';
	$rel    = isset( $data->link->rel ) && ! empty( $data->link->rel ) ? $data->link->rel : '';
	$title  = isset( $data->link->title ) && ! empty( $data->link->title ) ? $data->link->title : '';
	$target = isset( $data->link->target ) && ! empty( $data->link->target ) ? $data->link->target : '';

	if ( ! empty( $href ) ) {
		$href = 'href="' . esc_attr( $href ) . '"';
	}

	if ( ! empty( $rel ) ) {
		$rel = 'rel="' . esc_attr( $rel ) . '"';
	}

	if ( ! empty( $title ) ) {
		$title = 'title="' . esc_attr( $title ) . '"';
	}

	if ( ! empty( $target ) && $target == '_blank' ) {
		$target = 'target="' . esc_attr( $target ) . '"';
	}

	if ( ! empty( $label ) ) {
		$html = sprintf( '<a %s %s %s %s>%s</a>', $href, $rel, $title, $target, $label );
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:button]', 'brix_button_content_block_stringified', 10, 2 );

/**
 * Add the required inline styles for the icon of the button builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_button_default_frontend_block( $block_style, $block, $block_selector ) {
	$icon = isset( $block->data ) && isset( $block->data->icon->icon ) ? $block->data->icon->icon : '';
	$button_alignment = isset( $block->data ) && isset( $block->data->button_alignment ) ? $block->data->button_alignment : '';

	if ( $icon ) {
		$block_style .= brix_icon_style( $block_selector, $icon );
	}

	if ( $button_alignment ) {
		if ( is_string( $button_alignment ) ) {
			$block_style .= brix_block_inline_alignment_style( $block_selector, $button_alignment );
		}
		else {
			$breakpoints = brix_breakpoints();

			foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
				if ( isset( $button_alignment->$breakpoint_key ) && ! empty( $button_alignment->$breakpoint_key ) ) {
					$breakpoint_style = brix_block_inline_alignment_style( $block_selector, $button_alignment->$breakpoint_key );

					if ( ! empty( $breakpoint_style ) ) {
						if ( $breakpoint[ 'media_query' ] ) {
							$block_style .= $breakpoint[ 'media_query' ] . '{' . $breakpoint_style . '}';
						}
						else {
							$block_style .= $breakpoint_style;
						}
					}
				}
			}
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:button]', 'brix_styles_process_button_default_frontend_block', 10, 3 );

/**
 * Button block inline style.
 *
 * @since 1.0.0
 * @param string $block_selector The block CSS selector.
 * @param string $button_alignment The button alignment
 * @return string
 */
function brix_block_inline_alignment_style( $block_selector, $button_alignment ) {
	$block_style = '';

	$block_style .= $block_selector . '{';
		$block_style .= 'text-align:' . $button_alignment;
	$block_style .= '}';

	switch ( $button_alignment ) {
		case 'center':
			break;
		case 'right':
			$block_style .= $block_selector . ' .brix-block-button-inner-wrapper {';
				$block_style .= 'justify-content: flex-end;';
			$block_style .= '}';
			break;
		case 'left':
		default:
			$block_style .= $block_selector . ' .brix-block-button-inner-wrapper {';
				$block_style .= 'justify-content: flex-start;';
			$block_style .= '}';
			break;
	}

	return $block_style;
}