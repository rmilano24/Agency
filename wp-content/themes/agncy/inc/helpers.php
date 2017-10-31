<?php

/**
 * Return the theme object.
 *
 * @since 1.0.0
 * @return WP_Theme
 */
function agncy_get_theme() {
	$theme = wp_get_theme();

	if ( is_child_theme() ) {
		$theme = wp_get_theme( $theme->get( 'Template' ) );
	}

	return $theme;
}

/**
 * Read an SVG file and return its contents.
 *
 * @since 1.0.0
 * @param string $path The path to the file relative to the plugins' path.
 * @return string
 */
function agncy_load_svg( $path ) {
	$svg = trailingslashit( get_template_directory() ) . $path;

	if ( file_exists( $svg ) ) {
		return implode( '', file( $svg ) );
	}

	return '';
}

/**
 * Display the contents of an SVG file.
 *
 * @since 1.0.0
 * @param string $path The path to the file relative to the plugins' path.
 */
function agncy_svg( $path ) {
	echo agncy_load_svg( $path );
}

/**
 * Get paginate comments links.
 *
 * @since 1.0.0
 * @param array $arr A configuration array.
 */
function agncy_get_paginate_comments_links( $arr ) {
	ob_start();
	paginate_comments_links( $arr );
	ob_end_clean();
}

/**
 * Get an attachment ID from a URL.
 *
 * @since 1.0.0
 * @param string $attachment_url The file URL.
 * @return integer
 */
function agncy_get_attachment_id( $attachment_url ) {
	global $wpdb;

	$attachment_id = 0;
	$attachment_url = preg_replace( '/(-\d+x\d+)(?=.(jpg|jpeg|png|gif|svg)$)/i', '', $attachment_url );

	$q = $wpdb->prepare( "SELECT ID FROM " . $wpdb->prefix . "posts" . " WHERE guid=%s;", $attachment_url );
	$attachment = $wpdb->get_col( $q );

	if ( isset( $attachment[ 0 ] ) ) {
		$attachment_id = $attachment[ 0 ];
	}

	return $attachment_id;
}

/**
 * Retrieve a video's poster image from a remote service such as YouTube or
 * Vimeo.
 *
 * @since 1.0.3
 * @param string $url The embed URL.
 * @return array
 */
function agncy_get_video_poster_image( $url ) {
	$img_url = '';
	$width = 1280;
	$height = 720;

	if ( strpos( $url, 'youtu' ) !== false ) {
		parse_str( parse_url( $url, PHP_URL_QUERY ), $url_chunks );

		$img_url = sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $url_chunks[ 'v' ] );
	}
	elseif ( strpos( $url, 'vimeo' ) !== false ) {
		$url_chunks = explode( '/', $url );
		$url_code = array_pop( $url_chunks );

		$video_info = wp_remote_get( 'http://vimeo.com/api/v2/video/' . $url_code . '.json' );

		if ( $video_info ) {
			$video_info = wp_remote_retrieve_body( $video_info );

			if ( $video_info && ( $video_info = json_decode( $video_info, true ) ) !== null ) {
				$width = absint( $video_info[ 0 ][ 'width' ] );
				$height = absint( $video_info[ 0 ][ 'height' ] );
				$img_url = $video_info[ 0 ][ 'thumbnail_large' ];
			}
		}
	}

	if ( ! $img_url ) {
		return array();
	}

	return array(
		'url' => $img_url,
		'width' => $width,
		'height' => $height
	);
}


/**
 * Add a series of CSS classes to archives and index loops.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_loop_classes() {
	$classes = array();
	$loop_args = array(
		'agncy_style' => 'classic',
		'agncy_blog_columns' => 3
	);

	if ( is_home() ) {
		if ( function_exists( 'ev_get_option' ) ) {
			$loop_args = array(
				'agncy_style'        => ev_get_option( 'index_loop_style' ),
				'agncy_blog_columns' => ev_get_option( 'index_loop_columns' )
			);
		}
	}
	elseif ( is_archive() ) {
		if ( function_exists( 'ev_get_option' ) ) {
			$loop_args = array(
				'agncy_style'        => ev_get_option( 'archives_loop_style' ),
				'agncy_blog_columns' => ev_get_option( 'archives_loop_columns' )
			);
		}
	}
	else {
		return $classes;
	}

	$loop_args = ( object ) $loop_args;

	if ( function_exists( 'agncy_brix_blog_block_custom_class' ) ) {
		return agncy_brix_blog_block_custom_class( $classes, $loop_args );
	} else {
		if ( isset( $loop_args->agncy_style ) ) {
			if ( ! $loop_args->agncy_style ) {
				$loop_args->agncy_style = 'classic';
			}

			if ( ! $loop_args->agncy_blog_columns ) {
				$loop_args->agncy_blog_columns = 3;
			}

			$classes[] = 'agncy-loop-style-' . $loop_args->agncy_style;
			$classes[] = 'agncy-loop-col-' . $loop_args->agncy_blog_columns;
		}

		return $classes;
	}

	return $classes;
}

/**
 * Create options to manage an archives or index loop.
 *
 * @since 1.0.0
 * @param string $handle The options handle.
 * @return array
 */
function agncy_create_loop_options( $handle ) {
	$label = __( 'Loop layout', 'agncy' );
	$help = __( 'Select the layout of elements in the loop.', 'agncy' );
	$fields = array();

	$fields[] = array(
		'handle' => $handle . '_style_label',
		'text' => $label,
		'type' => 'divider',
		'config' => array(
			'style' => 'in_page'
		)
	);

	$fields[] = array(
		'handle' => $handle . '_style',
		'type' => 'radio',
		'label' => $label,
		'help' => $help,
		'config' => array(
			'data' => array(
				'classic' => __( 'Classic', 'agncy' ),
				'masonry' => __( 'Masonry', 'agncy' ),
				'stream' => __( 'Stream', 'agncy' ),
			),
			'controller' => true
		),
		'default' => ''
	);

		$fields[] = array(
			'handle' => $handle . '_columns',
			'type' => 'select',
			'label' => __( 'Columns', 'agncy' ),
			'config' => array(
				'data' => array(
					'2' => __( '2 Columns', 'agncy' ),
					'3' => __( '3 Columns', 'agncy' ),
					'4' => __( '4 Columns', 'agncy' )
				),
				'visible' => array(  $handle . '_style' => 'masonry' )
			),
			'default' => '3'
		);

	$fields[] = array(
		'handle' => $handle . '_featured_media',
		'type' => 'checkbox',
		'label' => __( 'Featured media', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( $handle . '_style' => 'masonry' ),
			'controller' => true
		),
		'default' => '1'
	);

		$fields[] = array(
			'handle' => $handle . '_featured_image_sizes',
			'type' => 'select',
			'label' => __( 'Image size', 'agncy' ),
			'config' => array(
				'visible' => array( $handle . '_featured_media' => '1' ),
				'data' => agncy_get_image_sizes_for_select()
			)
		);

	$fields[] = array(
		'handle' => $handle . '_excerpt',
		'type' => 'checkbox',
		'label' => __( 'Excerpt', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( $handle . '_style' => 'masonry' )
		),
		'default' => '1'
	);

	$fields[] = array(
		'handle' => $handle . '_read_more',
		'type' => 'checkbox',
		'label' => __( 'Read more', 'agncy' ),
		'config' => array(
			'style' => array( 'switch', 'small' ),
			'visible' => array( $handle . '_style' => 'masonry,stream' )
		),
		'default' => '0'
	);

	return $fields;
}

/**
 * Load the PhotoSwipe template.
 *
 * @since 1.0.0
 */
function agncy_load_photoswipe_template() {
	require_once get_template_directory() . '/templates/photoswipe/photoswipe.php';
}

add_action( 'wp_footer', 'agncy_load_photoswipe_template' );

/**
 * Convert the submit input of a password-protected post to a <button>.
 *
 * @since 1.0.0
 * @param string $html The form HTML.
 * @return string
 */
function agncy_the_password_form( $html ) {
	$html = str_replace( '<input type="submit"', '<button type="submit"', $html );
	$html = str_replace( '/></p></form>', sprintf(
		'>%s</button></p></form>',
		esc_html__( 'Enter', 'agncy' )
	), $html );

    return $html;
}

add_filter( 'the_password_form', 'agncy_the_password_form' );

/**
 * Check if a video is self-hosted.
 *
 * @since 1.0.0
 * @param string $url The video URL.
 * @return boolean
 */
function agncy_projects_slideshow_is_self_hosted_video( $url ) {
	if ( function_exists( 'ev_string_ends_with' ) ) {
		$url = strtolower( $url );

	    return
			ev_string_contains( $url, '.mp4' );
	}

	return false;
}

/**
 * Load external API libraries to manage video playback in the projects
 * slideshow.
 *
 * @since 1.0.0
 */
function agncy_projects_slideshow_load_libs() {
	if ( ! is_page_template( 'template-projects-slideshow.php' ) ) {
		return;
	}

	$slides = get_post_meta( get_the_ID(), 'agncy_slide', true );

	foreach ( (array) $slides as $i => $slide ) {
		$ref_id = isset( $slide[ 'ref_id' ] ) && ! empty( $slide[ 'ref_id' ] ) ? $slide[ 'ref_id' ] : false;

		if ( $ref_id ) {
			$use_video = isset( $slide[ 'use_video' ] ) && $slide[ 'use_video' ] == '1';

			if ( $use_video ) {
				$video = get_post_meta( $ref_id, 'video', true );

				if ( $video ) {
					if ( strpos( $video, 'vimeo.com' ) !== false ) {
						wp_enqueue_script( 'agncy-vimeo', 'https://player.vimeo.com/api/player.js', null, null, true );
					}
				}
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'agncy_projects_slideshow_load_libs' );