<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder list block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderListBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the list block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'list';
		$this->_title = __( 'List', 'brix' );

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
			'handle' => 'master_icon',
			'label'  => __( 'Master icon', 'brix' ),
			'type'   => 'icon'
		);

		$list_types = apply_filters( 'brix_list_block_list_types', array(
			'simple' => __( 'Simple list', 'brix' ),
		) );

		$fields[] = array(
			'handle' => 'list_type',
			'label'  => __( 'List type', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'controller' => true,
				'data' => $list_types
			)
		);

		$fields[] = array(
			'handle' => 'list_alignment',
			'type' => 'select',
			'label' => __( 'List elements alignment', 'brix' ),
			'config' => array(
				'data' => array(
					'left'  => __( 'Left', 'brix' ),
					'center'  => __( 'Center', 'brix' ),
					'right' => __( 'Right', 'brix' )
				)
			)
		);

		$fields[] = array(
			'handle' => 'icon_position',
			'type' => 'select',
			'label' => __( 'Icon position', 'brix' ),
			'config' => array(
				'data' => array(
					'left'  => __( 'Left', 'brix' ),
					'right' => __( 'Right', 'brix' )
				)
			)
		);

		$fields[] = array(
			'handle' => 'list_divider',
			'text'  => __( 'Elements', 'brix' ),
			'type'   => 'divider',
			'config' => array(
				'style' => 'in_page',
			)
		);

		$fields[] = array(
			'handle' => 'simple_list',
			'text'  => __( 'List elements', 'brix' ),
			'type'   => 'textarea',
			'config' => array(
				'rows' => 12,
				'visible' => array( 'list_type' => 'simple' ),
				'full' => true
			)
		);

		$fields = apply_filters( 'brix_list_block_fields', $fields );

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
		$simple_list   = isset( $data->data->simple_list ) ? $data->data->simple_list : '';
		$advanced_list = isset( $data->data->advanced_list ) ? $data->data->advanced_list : '';

		$list_empty = false;

		if ( $data->data->list_type == 'simple' ) {
			if ( empty( $simple_list ) ) {
				$list_empty = true;
			}
		}

		if ( $data->data->list_type == 'advanced' ) {
			if ( empty( $advanced_list ) ) {
				$list_empty = true;
			}
		}

		return $list_empty;
	}

}

/**
 * Add the list block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_list_content_block( $blocks ) {
	$blocks['list'] = array(
		'class'       => 'BrixBuilderListBlock',
		'label'       => __( 'List', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/list/i/list_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/list/i/list_icon.svg',
		'description' => __( 'Display unordered list with icons.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_list_content_block' );

/**
 * Define the appearance of the list block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_list_content_block_admin_template( $html, $data ) {
	if ( isset( $data->size ) ) {
		$html .= '<span class="brix-block-style-label">' . __( 'Size', 'brix' ) . ': ';
			$html .= '<strong>' . $data->size . '</strong>';
		$html .= '</span>';
	}

	$icon = '- ';

	$alignment = '';

	if ( isset( $data->list_alignment ) ) {
		$alignment = 'brix-block-list-alignment-' . $data->list_alignment;
	}

	$html .= sprintf( '<span class="brix-block-sublabel-list-wrapper %s">', $alignment );

		if ( isset( $data->list_type ) && $data->list_type == 'simple' ) {
			if ( isset( $data->simple_list ) && ! empty( $data->simple_list ) ) {
				if ( isset( $data->master_icon ) && ! empty( $data->master_icon->icon ) ) {
					$icon = brix_get_icon( $data->master_icon->icon ) . ' ';
				}

				$list_elements = explode( "\n", $data->simple_list );

				$html .= '<span class="brix-block-sublabel-list">';
					foreach ( $list_elements as $element ) {
						if ( isset( $data->icon_position ) && $data->icon_position == 'right' ) {
							$html .= '<span class="brix-list-icon-right">' . esc_html( $element ) . $icon . '</span>';
						} else {
							$html .= '<span class="brix-list-icon-left">' . $icon . esc_html( $element ) . '</span>';
						}
					}
				$html .= '</span>';
			}
		}
		else if ( isset( $data->list_type ) && $data->list_type == 'advanced' ) {
			if ( isset( $data->advanced_list ) && ! empty( $data->advanced_list ) ) {
				$html .= '<span class="brix-block-sublabel-list">';
					foreach ( $data->advanced_list as $element ) {
						if ( ! empty( $element->icon->icon ) ) {
							$icon = brix_get_icon( $element->icon->icon ) . ' ';
						}
						else if ( isset( $data->master_icon ) && ! empty( $data->master_icon->icon ) ) {
							$icon = brix_get_icon( $data->master_icon->icon ) . ' ';
						}

						$text = isset( $element->title->text ) ? $element->title->text : '';

						if ( isset( $data->icon_position ) && $data->icon_position == 'right' ) {
							$html .= '<span>' . esc_html( $text ) . $icon . '</span>';
						} else {
							$html .= '<span>' . $icon . esc_html( $text ) . '</span>';
						}
					}
				$html .= '</span>';
			}
		}

	$html .= '</span>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:list]', 'brix_list_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the list content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_list_content_block_stringified( $html, $data ) {
	if ( ! isset( $data->list_type ) ) {
		return '';
	}

	if ( $data->list_type == 'simple' ) {
		if ( isset( $data->simple_list ) && ! empty( $data->simple_list ) ) {
			$list_elements = explode( "\n", $data->simple_list );

			$html = '<ul>';
				foreach ( $list_elements as $element ) {
					$html .= '<li>' . $element . '</li>';
				}
			$html .= '</ul>';
		}
	}
	else if ( $data->list_type == 'advanced' ) {
		if ( isset( $data->advanced_list ) && ! empty( $data->advanced_list ) ) {
			$html = '<ul>';
				foreach ( $data->advanced_list as $element ) {
					$text = isset( $element->title->text ) ? $element->title->text : '';

					$html .= '<li>' . esc_html( $text ) . '</li>';
				}
			$html .= '</ul>';
		}
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:list]', 'brix_list_content_block_stringified', 10, 2 );

/**
 * Add a specific CSS class to the list block bundle field.
 *
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_list_builder_block_class( $classes, $data ) {
	$size      = isset( $data ) && isset( $data->size ) ? $data->size : 'default';
	$alignment = isset( $data ) && isset( $data->list_alignment ) ? $data->list_alignment : 'left';

	$classes[] = 'brix-list-alignment-' . $alignment;
	$classes[] = 'brix-list-size-' . $size;

	return $classes;
}

add_filter( 'brix_block_classes[type:list]', 'brix_list_builder_block_class', 10, 2 );

/**
 * Add the required inline styles for the icon of the list builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_list_icon_frontend_block( $block_style, $block, $block_selector ) {
	$master_icon      = isset( $block->data ) && isset( $block->data->master_icon ) ? $block->data->master_icon : '';
	$advanced_list    = isset( $block->data ) && isset( $block->data->advanced_list ) ? $block->data->advanced_list : '';

	if ( $master_icon ) {
		$block_style .= brix_icon_style( $block_selector, $master_icon );
	}

	if ( $advanced_list ) {
		foreach ( $advanced_list as $index => $element ) {
			$icon = isset( $element->icon ) ? $element->icon : '';

			if ( $icon ) {
				$list_selector = $block_selector . ' .brix-block-list-' . $index;
				$block_style .= brix_icon_style( $list_selector, $icon );
			}
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:list]', 'brix_process_list_icon_frontend_block', 10, 3 );