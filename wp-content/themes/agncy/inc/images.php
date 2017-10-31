<?php

/**
 * Get the proper markup for an image to be lazy-loaded.
 *
 * @since 1.2.9
 * @param integer|string $url Image ID or URL.
 * @param array $args Arguments.
 * @return string
 */
function agncy_get_lazy_image_markup( $url, $args = array() ) {
	if ( ! $url ) {
		return '';
	}

	/* If we're specifying an ID, declare the image as internal (uploaded through the Media Library). */
	$external = ! is_numeric( $url );

	/* CSS classes added to the <img> element. */
	$classes = isset( $args[ 'classes' ] ) ? implode( ' ', (array) $args[ 'classes' ] ) : '';
	$classes .= ' agncy-block-preloaded-img';
	$classes = apply_filters( 'agncy_lazy_image_classes', $classes );

	/* Image placeholder. */
	$placeholder = isset( $args[ 'placeholder' ] ) ? $args[ 'placeholder' ] : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
	$placeholder = apply_filters( 'agncy_lazy_image_placeholder', $placeholder );

	/* Image width. */
	$width = isset( $args[ 'width' ] ) ? intval( $args[ 'width' ] ) : '';

	/* Image height. */
	$height = isset( $args[ 'height' ] ) ? intval( $args[ 'height' ] ) : '';

	/* Image 'alt' attribute. */
	$alt = isset( $args[ 'alt' ] ) ? $args[ 'alt' ] : '';

	/* Image custom attributes. */
	$attrs = isset( $args[ 'attrs' ] ) ? (array) $args[ 'attrs' ] : array();

	/* Image link. */
	$link = isset( $args[ 'link' ] ) ? $args[ 'link' ] : '';

	/* Image caption. */
	$caption = isset( $args[ 'caption' ] ) ? $args[ 'caption' ] : '';

	/* Image caption class. */
	$caption_class = isset( $args[ 'caption_class' ] ) ? $args[ 'caption_class' ] : '';

	if ( ! $external ) {
		/* Image size. */
		$size = isset( $args[ 'size' ] ) ? $args[ 'size' ] : 'full';

		$alt = get_post_meta( $url, '_wp_attachment_image_alt', true );
		$meta = wp_get_attachment_metadata( $url );

		if ( $size != 'full' && isset( $meta[ 'sizes' ][ $size ] ) ) {
			if ( ! $width ) {
				$width = $meta[ 'sizes' ][ $size ]['width'];
			}

			if ( ! $height ) {
				$height = $meta[ 'sizes' ][ $size ]['height'];
			}
		}
		else {
			if ( ! $width && isset( $meta[ 'width' ] ) ) {
				$width = $meta[ 'width' ];
			}

			if ( ! $height && isset( $meta[ 'height' ] ) ) {
				$height = $meta[ 'height' ];
			}
		}

		if ( $width ) {
			$attrs[ 'width' ] = $width;
		}

		if ( $height ) {
			$attrs[ 'height' ] = $height;
		}

		/* Get the actual image URL. */
		if ( function_exists( 'ev_get_image' ) ) {
			$url = ev_get_image( $url, $size );
		} else {
			$url = wp_get_attachment_url( $url );
		}
	}

	$placeholder_element_attrs = '';

	if ( intval( $height ) > 0 ) {
		$percent_height = $height * 100 / $width;

		$placeholder_element_attrs .= 'style="width:' . esc_attr( $width ) . 'px;padding-top:' . esc_attr( $percent_height ) . '%;max-width:100%;display:block"';
	}

	$attrs_markup = '';

	foreach ( $attrs as $a => $v ) {
		if ( $v !== '' ) {
			$attrs_markup .= ' ' . $a . '="' . esc_attr( $v ) . '"';
		}
	}

	$img_html = sprintf(
		'<span class="agncy-image-wrapper"><img data-src="%s" src="%s" class="%s" %s /><b %s></b></span>',
		esc_url( $url ),
		esc_attr( $placeholder ),
		esc_attr( $classes ),
		$attrs_markup,
		$placeholder_element_attrs
	);

	if ( $caption ) {
		if ( $caption_class ) {
			$img_html .= sprintf( '<figcaption class="%s">%s</figcaption>',
				esc_attr( $caption_class ),
				wp_kses_post( $caption )
			);
		}
		else {
			$img_html .= sprintf( '<figcaption>%s</figcaption>', wp_kses_post( $caption ) );
		}
	}

	if ( $link && isset( $link->url ) && ! empty( $link->url ) ) {
		$img_html = agncy_link( $link, $img_html, false );
	}

	$html = sprintf( '<figure class="%s">%s</figure>',
		esc_attr( agncy_get_image_figure_class() ),
		$img_html
	);

	return $html;
}

/**
 * Print a link.
 *
 * @since 0.4.0
 * @param array $data The link data.
 * @param string $content The link content.
 * @param boolean $echo Set to true to echo the link.
 * @return string
 */
function agncy_link( $data, $content, $echo = true ) {
	$data = (array) $data;

	if ( ! isset( $data['url'] ) || empty( $data['url'] ) ) {
		if ( $echo ) {
			print $content;
		}

		return $content;
	}

	$link = '';

	$link .= agncy_link_open( $data, false );
		$link .= $content;
	$link .= agncy_link_close( false );

	if ( $echo ) {
		print $link;
	}

	return $link;
}

/**
 * Image <figure> CSS class.
 *
 * @since 1.2.9
 * @return string
 */
function agncy_get_image_figure_class() {
	$class = 'agncy-image';

    return apply_filters( 'agncy_image_figure_class', $class );
}

/**
 * Print a link opening tag.
 *
 * @since 0.4.0
 * @param array $data The link data.
 * @param boolean $echo Set to true to echo the link.
 * @return string
 */
function agncy_link_open( $data, $echo = true ) {
	$data = (array) $data;

	if ( ! isset( $data['url'] ) || empty( $data['url'] ) ) {
		return '';
	}

	$url    = isset( $data['url'] ) ? $data['url'] : '';
	$target = isset( $data['target'] ) ? $data['target'] : '';
	$rel    = isset( $data['rel'] ) ? $data['rel'] : '';
	$title  = isset( $data['title'] ) ? $data['title'] : '';
	$class  = isset( $data['class'] ) ? $data['class'] : '';

	if ( is_numeric( $url ) ) {
		$url = get_permalink( $url );
	}

	if ( $target == '_blank' ) {
		$rel .= ' noopener noreferrer';
	}

	$link = sprintf( '<a href="%s"', esc_attr( $url ) );

	if ( $target ) {
		$link .= sprintf( ' target="%s"', esc_attr( $target ) );
	}

	if ( $rel ) {
		$link .= sprintf( ' rel="%s"', esc_attr( $rel ) );
	}

	if ( $title ) {
		$link .= sprintf( ' title="%s"', esc_attr( $title ) );
	}

	if ( $class ) {
		$link .= sprintf( ' class="%s"', esc_attr( $class ) );
	}

	$link .= '>';

	if ( $echo ) {
		print $link;
	}

	return $link;
}

/**
 * Print a link closing tag.
 *
 * @since 0.4.0
 * @param boolean $echo Set to true to echo the link.
 * @return string
 */
function agncy_link_close( $echo = true ) {
	$link = '</a>';

	if ( $echo ) {
		print $link;
	}

	return $link;
}