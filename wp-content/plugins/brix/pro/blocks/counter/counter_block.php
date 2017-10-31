<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder counter content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderCounterBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the counter content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'counter';
		$this->_title = __( 'Counter', 'brix' );

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
	public function fields( $fields ) {
		$fields[] = array(
			'handle' => 'alignment',
			'label'  => __( 'Alignment', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'data' => array(
					'left' => __( 'Left', 'brix' ),
					'center' => __( 'Center', 'brix' ),
					'right' => __( 'Right', 'brix' ),
				)
			)
		);

		$fields[] = array(
			'handle' => 'value',
			'label'  => __( 'Value', 'brix' ),
			'type'   => 'number'
		);

		$fields[] = array(
			'handle' => 'icon',
			'label'  => __( 'Icon', 'brix' ),
			'type'   => 'icon'
		);

		$fields[] = array(
			'handle' => 'prefix',
			'label'  => __( 'Prefix', 'brix' ),
			'type'   => 'text'
		);

		$fields[] = array(
			'handle' => 'suffix',
			'label'  => __( 'Suffix', 'brix' ),
			'type'   => 'text'
		);

		$fields[] = array(
			'handle' => 'label',
			'label'  => __( 'Label', 'brix' ),
			'type'   => 'text'
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
		return empty( $data->data->value );
	}

}

/**
 * Add the counter content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_counter_content_block( $blocks ) {
	$blocks['counter'] = array(
		'class'       => 'BrixBuilderCounterBlock',
		'label'       => __( 'Counter', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'blocks/counter/i/counter_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'blocks/counter/i/counter_icon.svg',
		'description' => __( 'Display an animated counter.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_counter_content_block' );

/**
 * Define the appearance of the counter content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_counter_content_block_admin_template( $html, $data ) {
	$block_admin_classes = brix_counter_block_alignment_class( array(), $data );
	$block_admin_classes[] = 'brix-counter-block-wrapper';

	$html = sprintf( '<div class="%s">', esc_attr( implode( ' ', $block_admin_classes ) ) );

		if ( isset( $data->icon ) && ! empty( $data->icon ) ) {
			$html .= brix_get_icon( $data->icon->icon );
		}

		$html .= '<p class="brix-counter-value-wrapper">';

			if ( isset( $data->prefix ) && ! empty( $data->prefix ) ) {
				$html .= sprintf( '<span class="brix-counter-prefix">%s</span>', esc_html( $data->prefix ) );
			}

			if ( isset( $data->value ) && ! empty( $data->value ) ) {
				$html .= sprintf( '<span class="brix-counter-value">%s</span>', esc_html( $data->value ) );
			}

			if ( isset( $data->suffix ) && ! empty( $data->suffix ) ) {
				$html .= sprintf( '<span class="brix-counter-suffix">%s</span>', esc_html( $data->suffix ) );
			}

		$html .= '</p>';

		if ( isset( $data->label ) && ! empty( $data->label ) ) {
			$html .= sprintf( '<p class="brix-counter-label">%s</p>', esc_html( $data->label ) );
		}

	$html .= '</div>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:counter]', 'brix_counter_content_block_admin_template', 10, 2 );

/**
 * Counter block frontend alignment class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_counter_block_alignment_class( $classes, $data ) {
	if ( $data->_type == 'counter' && isset( $data->alignment ) && ! empty( $data->alignment ) ) {
		$classes[] = ' brix-counter-alignment-' . $data->alignment;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:counter]', 'brix_counter_block_alignment_class', 10, 2 );

/**
 * Define the appearance of the counter content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_counter_content_block_stringified( $html, $data ) {
	if ( isset( $data->value ) && ! empty( $data->value ) ) {
		$html = $data->prefix . ' ' . $data->value . ' ' . $data->suffix;

		if ( $data->label ) {
			$html .= "<br><br>";
			$html .= $data->label;
		}
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:counter]', 'brix_counter_content_block_stringified', 10, 2 );

/**
 * Custom template path for the counter block.
 *
 * @since 1.1.3
 * @return string
 */
function brix_counter_block_template_path() {
	return BRIX_PRO_FOLDER . 'blocks/counter/templates/counter_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:counter]', 'brix_counter_block_template_path' );

/**
 * Add the required inline styles for the counter builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_counter_frontend_block( $block_style, $block, $block_selector ) {
	$icon = isset( $block->data ) && isset( $block->data->icon ) ? $block->data->icon : '';

	if ( $icon ) {
		$block_style .= brix_icon_style( $block_selector, $icon );
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:counter]', 'brix_process_counter_frontend_block', 10, 3 );