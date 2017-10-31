<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Builder gallery content block class.
 *
 * @package   Brix
 * @since 	  1.2.9
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderGalleryBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the gallery content block.
	 *
	 * @since 1.2.9
	 */
	public function __construct()
	{
		$this->_type = 'gallery';
		$this->_title = __( 'Gallery', 'brix' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
		add_filter( "brix_block_style_fields[type:{$this->_type}]", array( $this, 'style_fields' ) );
	}

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.2.9
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	public function fields( $fields ) {
        $fields[] = array(
			'type'   => 'brix_media',
			'handle' => 'media',
			'label'  => __( 'Media items', 'brix' ),
			'help' => __( 'A set of media items (images and/or external embeds).', 'brix' )
		);

        $fields[] = array(
			'type'   => 'checkbox',
			'handle' => 'static',
			'label'  => __( 'Static', 'brix' ),
			'help' => __( 'Use the first item as a static placeholder, and open the gallery in a lightbox.', 'brix' ),
			'config' => array(
                'controller' => true,
                'style' => array( 'small', 'switch' )
			),
			'default' => '0'
		);

		$fields[] = array(
			'type'   => 'select',
			'handle' => 'media_image_size',
			'label'  => __( 'Image size', 'brix' ),
			'help' => __( 'The image size used by media items.', 'brix' ),
			'config' => array(
				'data' => brix_get_image_sizes_for_select()
			),
			'default' => 'large'
		);

        $fields[] = array(
			'type'   => 'select',
			'handle' => 'gallery_type',
			'label'  => __( 'Gallery type', 'brix' ),
			'help' => __( 'Determines how media items are laid out in the page.', 'brix' ),
			'config' => array(
                'controller' => true,
				'data' => brix_gallery_types(),
                'visible' => array( 'static' => '0' ),
			),
			'default' => 'simple'
		);

        $fields[] = array(
			'type'   => 'select',
			'handle' => 'columns',
			'label'  => __( 'Columns', 'brix' ),
			'help' => __( 'Number of columns used by the grid.', 'brix' ),
			'config' => array(
                'data' => array(
                    '2' => __( '2 columns', 'brix' ),
                    '3' => __( '3 columns', 'brix' ),
                    '4' => __( '4 columns', 'brix' ),
                    '5' => __( '5 columns', 'brix' ),
                    '6' => __( '6 columns', 'brix' ),
                ),
                'visible' => array( 'gallery_type' => 'grid,masonry' ),
			),
			'default' => '3'
		);

        $fields[] = array(
			'type'   => 'number',
			'handle' => 'number',
			'label'  => __( 'Number of items', 'brix' ),
			'help' => __( 'Number of items shown at a time. Leave empty, or set to <code>-1</code> to display all items.', 'brix' ),
            'config' => array(
                'min' => '-1',
                'visible' => array( 'static' => '0' ),
            ),
			'default' => ''
		);

        $fields[] = array(
			'type'   => 'checkbox',
			'handle' => 'lightbox',
			'label'  => __( 'Open in lightbox', 'brix' ),
			'help' => __( 'Check this to make media items open in a lightbox.', 'brix' ),
			'config' => array(
                'controller' => true,
                'style' => array( 'small', 'switch' ),
                'visible' => array( 'static' => '0' ),
			),
			'default' => '1'
		);

        $fields[] = array(
			'type'   => 'checkbox',
			'handle' => 'captions',
			'label'  => __( 'Display captions', 'brix' ),
			'help' => __( 'Check this to make media items display their captions when open in a lightbox.', 'brix' ),
			'config' => array(
				'visible' => array( 'lightbox' => '1' ),
                'style' => array( 'small', 'switch' )
			),
			'default' => '1'
		);

        // $fields[] = array(
		// 	'type'   => 'multiple_select',
		// 	'handle' => 'captions',
		// 	'label'  => __( 'Display EXIF data', 'brix' ),
		// 	'help' => __( 'Select which EXIF data to display. Leave empty to hide.', 'brix' ),
		// 	'config' => array(
        //         'visible' => array( 'lightbox' => '1' ),
        //         'data' => array(
        //             'created_timestamp' => __( 'Taken on', 'brix' ),
		// 			'camera'            => __( 'Camera', 'brix' ),
		// 			'focal_length'      => __( 'Focal length', 'brix' ),
		// 			'aperture'          => __( 'Aperture', 'brix' ),
		// 			'iso'               => __( 'ISO', 'brix' ),
		// 			'shutter_speed'     => __( 'Shutter speed', 'brix' ),
		// 			'copyright'         => __( 'Copyright', 'brix' ),
		// 			'credit'            => __( 'Credit', 'brix' ),
        //         )
		// 	),
		// 	'default' => ''
		// );

		return $fields;
	}

	/**
	 * Check if the block is "empty".
	 *
	 * @since 1.2.9
	 * @param array $data The content block data.
	 * @return boolean
	 */
	protected function is_empty( $data )
	{
		return ! isset( $data->data->media ) || empty( $data->data->media );
	}

}

/**
 * Add the gallery content block to the registered content blocks.
 *
 * @since 1.2.9
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_gallery_content_block( $blocks ) {
	$blocks['gallery'] = array(
		'class'       => 'BrixBuilderGalleryBlock',
		'label'       => __( 'Gallery', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'blocks/gallery/i/gallery_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'blocks/gallery/i/gallery_icon.svg', // TODO: fare icona
		'description' => __( 'Display a gallery of images.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_gallery_content_block' );

/**
 * Define the appearance of the gallery content block in the admin.
 *
 * @since 1.2.9
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_gallery_content_block_admin_template( $html, $data ) {
    if ( isset( $data->media ) && ! empty( $data->media ) ) {
        $images = json_decode( $data->media );

        if ( count( $images ) > 0 ) {
            $html = '<ul>';
                $html .= sprintf( '<li>%s %s</li>', esc_html( count( $images ) ), esc_html__( 'items', 'brix' ) );

                if ( isset( $data->number ) ) {
                    if ( $data->number > 0 ) {
                        $html .= sprintf( '<li>%s %s</li>', esc_html( $data->number ), esc_html__( 'items shown at a time', 'brix' ) );
                    }
                    elseif ( $data->number == '-1' ) {
                        $html .= sprintf( '<li>%s</li>', esc_html__( 'All items shown', 'brix' ) );
                    }
                    else {
                        $html .= sprintf( '<li>%s</li>', esc_html__( 'No items shown', 'brix' ) );
                    }
                }

				$type = brix_gallery_types();
				$static = isset( $data->static ) ? (bool) $data->static : false;

				if ( $static ) {
					$html .= sprintf( '<li>%s: "%s"</li>', esc_html__( 'Type', 'brix' ), esc_html__( 'Static', 'brix' ) );
				}
				else {
					$html .= sprintf( '<li>%s: "%s"</li>', esc_html__( 'Type', 'brix' ), esc_html( $type[ $data->gallery_type ] ) );
				}

            $html .= '</ul>';
        }
    }

	return $html;
}

add_filter( 'brix_block_admin_template[type:gallery]', 'brix_gallery_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the gallery content block when stringified.
 *
 * @since 1.2.9
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_gallery_content_block_stringified( $html, $data ) {
    if ( isset( $data->media ) && ! empty( $data->media ) ) {
        $images = json_decode( $data->media );

        if ( count( $images ) > 0 ) {
            $html = '';

            foreach ( $images as $media ) {
                $html .= sprintf( '<img src="%s" alt="">',
                    esc_attr( brix_get_image( $media->gallery_item_id, $data->media_image_size ) )
                );
            }
        }
    }

	return $html;
}

add_filter( 'brix_block_stringified[type:gallery]', 'brix_gallery_content_block_stringified', 10, 2 );

/**
 * Custom template path for the gallery block.
 *
 * @since 1.2.9
 * @return string
 */
function brix_gallery_block_template_path() {
	return BRIX_PRO_FOLDER . 'blocks/gallery/templates/gallery_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:gallery]', 'brix_gallery_block_template_path' );

/**
 * Add the required inline styles for the gallery builder block.
 *
 * @since 1.2.9
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_gallery_frontend_block( $block_style, $block, $block_selector ) {
    $gutter = brix_get_gutter();

    if ( $gutter ) {
        $gutter      = str_replace( ',', '.', $gutter );
        $gutter_val  = floatval( $gutter );
    	$gutter_unit = str_replace( $gutter_val, '', $gutter );

        if ( ! $gutter_unit ) {
    		$gutter_unit = 'px';
    	}

        $block_style .= sprintf( '%s .brix-gallery-item{padding:%s}',
            $block_selector,
            ( $gutter_val/2 ) . $gutter_unit,
            ( $gutter_val/2 ) . $gutter_unit
        );
    }

	return $block_style;
}

add_filter( 'brix_block_style[type:gallery]', 'brix_process_gallery_frontend_block', 10, 3 );

/**
 * Register the PhotoSwipe style.
 *
 * @since 1.2.9
 */
function brix_gallery_enqueue_scripts() {
	wp_register_style( 'brix-gallery-photoswipe', BRIX_PRO_ASSETS_URI . 'frontend/css/photoswipe.css' );
	wp_register_style( 'brix-gallery-photoswipe-skin', BRIX_PRO_ASSETS_URI . 'frontend/css/default-skin/default-skin.css', array( 'brix-gallery-photoswipe' ) );
}

add_action( 'wp_enqueue_scripts', 'brix_gallery_enqueue_scripts', 0 );

/**
 * Include resources for the gallery block.
 *
 * @since 1.2.9
 * @param object $data The block data.
 */
function brix_load_gallery_block_resources( $data ) {
	wp_enqueue_style( 'brix-gallery-photoswipe' );
	wp_enqueue_style( 'brix-gallery-photoswipe-skin' );
}

add_action( 'brix_load_block_resources[type:gallery]', 'brix_load_gallery_block_resources' );

/**
 * Render the PhotoSwipe template.
 *
 * @since 1.2.9
 * @param object $data The block data.
 */
function brix_gallery_block_after_render( $data ) {
    if ( $data->_type == 'gallery' ) {
    	add_action( 'wp_footer', 'brix_load_photoswipe_template' );
    }
}

add_action( 'brix_block_after_render', 'brix_gallery_block_after_render' );

/**
 * Load PhotoSwipe template.
 *
 * @since 1.2.9
 */
function brix_load_photoswipe_template() {
    require_once BRIX_PRO_FOLDER . 'blocks/gallery/templates/photoswipe/photoswipe.php';
}

/**
 * Counter block frontend alignment class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_gallery_block_classes( $classes, $data ) {
	$static       = isset( $data->static ) ? (bool) $data->static : false;

	if ( $static ) {
		$classes[] = 'brix-gallery-static';
	}
	else {
		$gallery_type = isset( $data->gallery_type ) ? $data->gallery_type : 'grid';
		$classes[] = 'brix-gallery-container-' . $gallery_type;

		if ( in_array( $gallery_type, array( 'grid', 'masonry' ) ) ) {
			$columns      = isset( $data->columns ) ? absint( $data->columns ) : 3;

			$classes[] = 'brix-gallery-columns-' . $columns;
		}
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:gallery]', 'brix_gallery_block_classes', 10, 2 );

/**
 * Return a list of Gallery types.
 *
 * @since 1.2.10
 * @return array
 */
function brix_gallery_types() {
	return apply_filters( 'brix_gallery_types', array(
		'simple'  => __( 'Simple', 'brix' ),
		'grid'    => __( 'Grid', 'brix' ),
		'masonry' => __( 'Masonry', 'brix' )
	) );
}