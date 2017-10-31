<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder image content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderImageBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the image content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'image';
		$this->_title = __( 'Image', 'brix' );

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
			'handle' => 'source',
			'type' => 'select',
			'label' => __( 'Image source', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => array(
					'image'    => __( 'Media library', 'brix' ),
					'external' => __( 'External link', 'brix' )
				)
			)
		);

		$fields[] = array(
			'handle' => 'image',
			'type' => 'image',
			'label'  => __( 'Image', 'brix' ),
			'config' => array(
				'visible' => array( 'source' => 'image' ),
				'image_size' => true
			)
		);

		$fields[] = array(
			'handle' => 'image_url',
			'type' => 'text',
			'label' => __( 'External link', 'brix' ),
			'config' => array(
				'full' => true,
				'visible' => array( 'source' => 'external' )
			)
		);

		$fields[] = array(
			'handle' => 'image_alignment',
			'type' => 'select',
			'label' => __( 'Image alignment', 'brix' ),
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

		$fields[] = array(
			'handle' => 'image_media_caption',
			'type' => 'select',
			'label' => __( 'Display image caption', 'brix' ),
			'config' => array(
				'data' => array(
					'disabled' => __( 'Disabled', 'brix' ),
					'media' => __( 'Media caption', 'brix' ),
					'custom' => __( 'Custom caption', 'brix' ),
				),
				'controller' => true,
				'visible' => array( 'source' => 'image' )
			)
		);

			$fields[] = array(
				'handle' => 'image_media_custom_caption',
				'type' => 'textarea',
				'label' => __( 'Caption', 'brix' ),
				'config' => array(
					'visible' => array( 'image_media_caption' => 'custom' ),
					'rows' => 8,
					'full' => true,
					'rich' => true
				)
			);

		$fields[] = array(
			'handle' => 'image_external_caption',
			'type' => 'select',
			'label' => __( 'Display image caption', 'brix' ),
			'config' => array(
				'data' => array(
					'disabled' => __( 'Disabled', 'brix' ),
					'custom' => __( 'Custom caption', 'brix' ),
				),
				'controller' => true,
				'visible' => array( 'source' => 'external' )
			)
		);

			$fields[] = array(
				'handle' => 'image_external_custom_caption',
				'type' => 'textarea',
				'label' => __( 'Caption', 'brix' ),
				'config' => array(
					'visible' => array( 'image_external_caption' => 'custom' ),
					'rows' => 8,
					'full' => true,
					'rich' => true
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
		$image_empty = false;

		if ( isset( $data->data->source ) && $data->data->source == 'image' ) {
			if ( isset( $data->data->image ) && empty( $data->data->image->desktop[1]->id ) ) {
				$image_empty = true;
			}
		}

		if ( isset( $data->data->source ) && $data->data->source == 'external' ) {
			if ( isset( $data->data->image_url ) && empty( $data->data->image_url ) ) {
				$image_empty = true;
			}
		}

		return $image_empty;
	}

}

/**
 * Add the image content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_image_content_block( $blocks ) {
	$blocks['image'] = array(
		'class'       => 'BrixBuilderImageBlock',
		'label'       => __( 'Image', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/image/i/image_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/image/i/image_icon.svg',
		'description' => __( 'Upload image and apply effects.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_image_content_block' );

/**
 * Define the appearance of the image content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_image_content_block_admin_template( $html, $data ) {
	$img = '';

	if ( isset( $data->source ) && $data->source == 'image' ) {
		if ( isset( $data->image ) && ! empty( $data->image->desktop[1]->id ) ) {
			$img = brix_get_image( $data->image->desktop[1]->id, 'medium' );
		}
	}
	else if ( isset( $data->source ) && $data->source == 'external' ) {
		if ( isset( $data->image_url ) && ! empty( $data->image_url ) ) {
			$img = $data->image_url;
		}
	}

	if ( $img ) {
		$html = '<img src="' . esc_url( $img ) . '">';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:image]', 'brix_image_content_block_admin_template', 10, 2 );

/**
 * Image block frontend alignment class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_image_alignment_class( $classes, $data ) {
	if ( isset( $data->image_alignment ) && ! empty( $data->image_alignment->desktop ) ) {
		$classes[] = 'brix-block-image-alignment-' . $data->image_alignment->desktop;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:image]', 'brix_image_alignment_class', 10, 2 );

/**
 * Define the appearance of the image content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_image_content_block_stringified( $html, $data ) {
	$img = '';
	$alt = '';

	if ( isset( $data->source ) && $data->source == 'image' ) {
		if ( isset( $data->image ) && ! empty( $data->image->desktop[1]->id ) ) {
			$img = brix_get_image( $data->image->desktop[1]->id, 'medium' );
			$alt = get_post_meta( $data->image->desktop[1]->id, '_wp_attachment_image_alt', true );
		}
	}
	else if ( isset( $data->source ) && $data->source == 'external' ) {
		if ( isset( $data->image_url ) && ! empty( $data->image_url ) ) {
			$img = $data->image_url;
		}
	}

	if ( $img ) {
		$html = sprintf( '<img src="%s" alt="%s">', esc_url( $img ), esc_attr( $alt ) );
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:image]', 'brix_image_content_block_stringified', 10, 2 );

/**
 * Add the required inline styles for the icon of the image builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_image_default_frontend_block( $block_style, $block, $block_selector ) {
	$image_alignment = isset( $block->data ) && isset( $block->data->image_alignment ) ? $block->data->image_alignment : '';

	if ( $image_alignment ) {
		if ( is_string( $image_alignment ) ) {
			$block_style .= brix_block_image_inline_alignment_style( $block_selector, $image_alignment );
		}
		else {
			$breakpoints = brix_breakpoints();

			foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
				if ( isset( $image_alignment->$breakpoint_key ) && ! empty( $image_alignment->$breakpoint_key ) ) {
					$breakpoint_style = brix_block_image_inline_alignment_style( $block_selector, $image_alignment->$breakpoint_key );

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

add_filter( 'brix_block_style[type:image]', 'brix_styles_process_image_default_frontend_block', 10, 3 );

/**
 * Image block inline style.
 *
 * @since 1.0.0
 * @param string $block_selector The block CSS selector.
 * @param string $image_alignment The image alignment
 * @return string
 */
function brix_block_image_inline_alignment_style( $block_selector, $image_alignment ) {
	$block_style = '';

	$block_style .= $block_selector . '.brix-section-column-block-image {';
		$block_style .= 'text-align:' . $image_alignment;
	$block_style .= '}';

	switch ( $image_alignment ) {
		case 'center':
			$block_style .= $block_selector . '.brix-section-column-block-image figure {';
				$block_style .= 'align-items: center;';
			$block_style .= '}';
			break;
		case 'right':
			$block_style .= $block_selector . '.brix-section-column-block-image figure {';
				$block_style .= 'align-items: flex-end;';
			$block_style .= '}';
			break;
		case 'left':
		default:
			$block_style .= $block_selector . '.brix-section-column-block-image figure {';
				$block_style .= 'align-items: flex-start;';
			$block_style .= '}';
			break;
	}

	return $block_style;
}