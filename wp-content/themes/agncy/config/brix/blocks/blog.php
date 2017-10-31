<?php

/**
 * Add additional options to the blog content block.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_brix_blog_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'agncy_blog_divider',
		'text'  => __( 'Style options', 'agncy' ),
		'type'   => 'divider',
	);

	$fields[] = array(
		'handle' => 'agncy_style',
		'type' => 'radio',
		'label' => __( 'Style', 'agncy' ),
		'config' => array(
			'data' => array(
				'classic' => __( 'Classic', 'agncy' ),
				'masonry' => __( 'Masonry', 'agncy' ),
				'stream' => __( 'Stream', 'agncy' ),
			),
			'controller' => true
		),
		'default' => 'classic'
	);

		$fields[] = array(
			'handle' => 'agncy_blog_columns',
			'type' => 'select',
			'label' => __( 'Columns', 'agncy' ),
			'config' => array(
				'data' => array(
					'2' => __( '2 Columns', 'agncy' ),
					'3' => __( '3 Columns', 'agncy' ),
					'4' => __( '4 Columns', 'agncy' )
				),
				'visible' => array( 'agncy_style' => 'masonry' )
			),
			'default' => '3'
		);

	$fields[] = array(
		'handle' => 'agncy_featured_media',
		'type' => 'checkbox',
		'label' => __( 'Featured media', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( 'agncy_style' => 'masonry' ),
			'controller' => true
		),
		'default' => '1'
	);

		$fields[] = array(
			'handle' => 'agncy_featured_image_sizes',
			'type' => 'select',
			'label' => __( 'Image size', 'agncy' ),
			'config' => array(
				'visible' => array( 'agncy_featured_media' => '1' ),
				'data' => agncy_get_image_sizes_for_select()
			)
		);

	$fields[] = array(
		'handle' => 'agncy_excerpt',
		'type' => 'checkbox',
		'label' => __( 'Excerpt', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( 'agncy_style' => 'masonry' )
		),
		'default' => '1'
	);

	$fields[] = array(
		'handle' => 'agncy_read_more',
		'type' => 'checkbox',
		'label' => __( 'Read more', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( 'agncy_style' => 'masonry,stream' )
		),
		'default' => '0'
	);

	return $fields;
}

add_filter( 'brix_block_fields[type:blog]', 'agncy_brix_blog_block_fields', 11 );

/**
 * Load an alternative blog block template.
 *
 * @since 1.0.0
 * @param string $template The template path.
 * @param array $data The template data.
 * @return string
 */
function agncy_brix_blog_block_post_template( $template, $data ) {
	$style = $data->agncy_style;

	if ( file_exists( get_template_directory() . '/content-' . $style . '.php' ) ) {
		$template = get_template_directory() . '/content-' . $style . '.php';
	}
	else {
		$template = get_template_directory() . '/content.php';
	}

	return $template;
}

add_filter( 'brix_blog_builder_block_post_template', 'agncy_brix_blog_block_post_template', 10, 2 );

/**
 * Blog block frontend class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function agncy_brix_blog_block_custom_class( $classes, $data ) {
	if ( isset( $data->agncy_style ) ) {
		$style = $data->agncy_style;

		if ( ! $style ) {
			$style = 'classic';
		}

		if ( isset( $data->agncy_blog_columns ) && $style == 'masonry' ) {
			if ( empty( $data->agncy_blog_columns ) ) {
				$data->agncy_blog_columns = 3;
			}

			$classes[] = 'agncy-loop-col-' . $data->agncy_blog_columns;
		}

		if ( isset( $data->paginate ) && isset( $data->agncy_blog_pagination_alignment ) ) {
			if ( $data->paginate == 'static' || $data->paginate == 'ajax_reload' ) {
				$pag_align = $data->agncy_blog_pagination_alignment;

				$classes[] = 'agncy-pag-al-' . $data->agncy_blog_pagination_alignment;
			}
		}

		$classes[] = 'agncy-loop-style-' . $style;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:blog]', 'agncy_brix_blog_block_custom_class', 10, 2 );

/**
 * Append a grid sizer element to the blog masonry.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_blog_masonry_sizer( $data ) {
	if ( $data->data->agncy_style !== 'masonry' ) {
		return;
	}

	echo '<div class="agncy-gs"></div>';
}

add_action( 'brix_blog_after_loop', 'agncy_blog_masonry_sizer' );
