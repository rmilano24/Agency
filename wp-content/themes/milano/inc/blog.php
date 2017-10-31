<?php

/**
 * Return the entry date element for the current post in a loop.
 *
 * @since 1.0.0
 * @param string $classes An optional set of CSS classes to be passed to the time element.
 * @return string
 */
function agncy_get_entry_date( $classes = '' ) {
	$time_string = '<time class="entry-date published updated ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time><time class="updated screen-reader-text' . esc_attr( $classes ) . '" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	return $time_string;
}

/**
 * Display the entry date element for the current post in a loop.
 *
 * @since 1.0.0
 * @param string $classes An optional set of CSS classes to be passed to the time element.
 */
function agncy_entry_date( $classes = '' ) {
	echo agncy_get_entry_date( $classes );
}

/**
 * Return the post author element for the current post in a loop.
 *
 * @since 1.0.0
 * @param boolean $avatar Set to true to display the author avatar.
 * @return string
 */
function agncy_get_post_author( $avatar = false, $only_avatar = false ) {
	global $post;

	$post_author = '';

	if ( $post ) {
		$author_id  = $post->post_author;

		if ( ! $author_id ) {
			return '';
		}

		$avatar_img = '';

		if ( $avatar == true ) {
			$avatar_img = get_avatar( $author_id, 80 );
		}

		if ( $only_avatar != true ) {
			$post_author = '<span class="author vcard"><a class="url fn" href="%1$s">%2$s<span>%3$s</span></a></span>';

			$post_author = sprintf( $post_author,
				esc_url( get_author_posts_url( $author_id ) ),
				$avatar_img,
				esc_html( get_the_author_meta( 'display_name', $author_id ) )
			);
		} else {
			$post_author = $avatar_img;
		}
	}

	return $post_author;
}

/**
 * Display the post author element for the current post in a loop.
 *
 * @since 1.0.0
 */
function agncy_post_author( $avatar = false ) {
	echo agncy_get_post_author( $avatar );
}

/**
 * Display custom classes and attributes for the element that should define the
 * title of an entry in a loop.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 */
function agncy_post_title_attrs( $classes = array() ) {
	$classes = (array) $classes;

	$classes[] = 'entry-title';
	$classes = apply_filters( 'agncy_post_title_classes', $classes );

	if ( is_sticky() ) {
		$classes[] = 'agncy-sticky';
	}

	$attrs = array();
	$attrs = apply_filters( 'agncy_post_title_attrs', $attrs );

	printf( 'class="%s" %s',
		implode( ' ', array_map( 'esc_attr', $classes ) ),
		implode( ' ', array_map( 'esc_attr', $attrs ) )
	);
}

/**
 * Determine whether blog/site has more than one category.
 *
 * @since 1.0.0
 * @return bool True of there is more than one category, false otherwise.
 */
function agncy_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'agncy_categories' ) ) ) {
		/* Create an array of all the categories that are attached to posts. */
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			/* We only need to know if there is more than one category. */
			'number'     => 2,
		) );

		/* Count the number of categories that are attached to the posts. */
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'agncy_categories', $all_the_cool_cats );
	}

	return $all_the_cool_cats > 1;
}

/**
 * Refresh whether blog/site has more than one category.
 *
 * @since 1.0.0
 */
function agncy_refresh_categorized_transient() {
	delete_transient( 'agncy_categories' );
	agncy_categorized_blog();
}

/**
 * Get the link URL in the post format link.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_post_format_link_link() {
	if ( preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) ) {
		return esc_url_raw( $matches[1] );
	}

	return '';
}

/**
 * Get the post format specific content for a blog post.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_post_format_content( $image_size = 'full' ) {
	$format = get_post_format();
	$post_format_content = '';
	$partial_post_format_content = '';

	$accepted_formats = array(
		'',
		'image',
		'gallery',
		'video',
		'audio',
		'link',
		'quote'
	);

	if ( ! in_array( $format, $accepted_formats ) ) {
		return '';
	}

	$formats = array(
		'audio' => array(
			'shortcodes' => array(
				'audio'
			),
			'oembed_providers' => array(
				'https://embed.spotify.com/oembed/',
				'https://soundcloud.com/oembed',
				'https://www.mixcloud.com/oembed'
			)
		),
		'video' => array(
			'shortcodes' => array(
				'video'
			),
			'oembed_providers' => array(
				'https://www.youtube.com/oembed',
				'https://vimeo.com/api/oembed.{format}',
				'https://www.dailymotion.com/services/oembed',
				'http://www.hulu.com/api/oembed.{format}',
				'https://wordpress.tv/oembed/',
				'https://vine.co/oembed.{format}',
				'http://www.collegehumor.com/oembed.{format}',
				'http://www.ted.com/talks/oembed.{format}',
				'https://www.ted.com/services/v1/oembed.{format}',
				'https://animoto.com/oembeds/create',
			)
		),
		'status' => array(
			'oembed_providers' => array(
				'https://publish.twitter.com/oembed',
				'https://vine.co/oembed.{format}',
				'https://api.instagram.com/oembed',
			)
		)
	);

	if ( has_post_thumbnail() && $format != 'gallery' ) {
		$post_format_content .= agncy_get_lazy_image_markup( get_post_thumbnail_id(), array(
			'classes'       => '',
			'size'          => $image_size,
			'link'          => (object) array( 'url' => get_the_permalink() ),
			'caption'       => function_exists( 'ev_get_image_caption' ) ? ev_get_image_caption( get_post_thumbnail_id() ) : '',
			'caption_class' => ''
		) );
	}

	if ( ! isset( $formats[$format] ) ) {
		$matches = array();
		$allowed_html = array(
			'blockquote' => array(),
			'cite' => array(),
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
		);

		if ( $format === 'link' ) {
			$post_format_link = agncy_get_post_format_link_link();

			if ( $post_format_link ) {
				$post_format_content .= sprintf( '<a href="%s" target="_blank">%s</a>',
					esc_attr( $post_format_link ),
					esc_html( $post_format_link )
				);
			}
		}
		elseif ( $format === 'quote' ) {
			unset( $allowed_html['a'] );

			if ( preg_match( '/<blockquote(.*)>((.|\n)*)<\/blockquote>/is', get_the_content(), $matches ) ) {
				$post_format_content .= wp_kses( $matches[0], $allowed_html );
			}
		}
		elseif ( $format === 'gallery' ) {
			$galleries = get_post_galleries_images();

			if ( ! isset( $galleries[ 0 ] ) ) {
				return;
			}

			$gallery = $galleries[0];
			$opts = json_encode( array(
				'draggable'       => false,
				'cellAlign'       => 'left',
				'cellSelector'    => '.agncy-lpf-s',
				'prevNextButtons' => false,
				'pageDots'        => false,
				'bgLazyLoad'      => true
			) );

			if ( ! empty( $gallery ) ) {
				$post_format_content .= '<div class="agncy-lpf-ec">';

					$post_format_content .= '<div class="agncy-pfgallery-cw">';
						$post_format_content .= '<a class="agncy-pfgallery-p-arr agncy-pfgallery-disabled" href="#"></a>';
						$post_format_content .= '<a class="agncy-pfgallery-n-arr" href="#"></a>';
					$post_format_content .= '</div>';

					$post_format_content .= sprintf( '<div class="agncy-lpf-c" data-carousel=%s>', htmlentities( $opts ) );

					foreach ( $gallery as $img_url ) {
						$post_format_content .= sprintf( '<div class="agncy-lpf-s" data-flickity-bg-lazyload="%s"></div>', esc_attr( $img_url ) );
					}

					$post_format_content .= '</div>';
				$post_format_content .= '</div>';
			}
		}
	}
	else {
		$content            = get_the_content();
		$shortcodes_pattern = get_shortcode_regex();
		$oembed_pattern     = '|^(\s*)(https?://[^\s"]+)(\s*)$|im';
		$found              = false;

		/* Check for shortcodes first. */
		$matches = array();
		preg_match_all( "/$shortcodes_pattern/s", $content, $matches );

		if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {
			if ( isset( $formats[$format]['shortcodes'] ) ) {
				foreach ( $formats[$format]['shortcodes'] as $shortcode ) {
					$index = array_search( $shortcode, $matches[2] );

					if ( $index !== false ) {
						$found = true;

						if ( $format == 'video' && has_post_thumbnail() ) {
							$partial_post_format_content .= do_shortcode( $matches[0][$index] );
						}
						else {
							$post_format_content .= do_shortcode( $matches[0][$index] );
						}

						break;
					}
				}
			}
		}

		if ( ! $found ) {
			/* Check for embeds. */
			$matches = array();
			$oembed_pattern = '|^(\s*)(https?://[^\s"]+)(\s*)$|im';
			preg_match_all( $oembed_pattern, $content, $matches );


			if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {
				if ( isset( $formats[$format]['oembed_providers'] ) ) {
					require_once( ABSPATH . WPINC . '/class-oembed.php' );
					$oembed = _wp_oembed_get_object();

					foreach ( $matches[0] as $url ) {
						foreach ( $oembed->providers as $matchmask => $data ) {
							list( $providerurl, $regex ) = $data;

							if ( ! $regex ) {
								$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
								$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
							}

							if ( in_array( $providerurl, $formats[$format]['oembed_providers'] ) ) {
								if ( preg_match( $matchmask, $url ) ) {
									$found = true;
									$url = trim( $url );

									if ( $format == 'video' && has_post_thumbnail() ) {
										$partial_post_format_content .= wp_oembed_get( $url );
									}
									else {
										$post_format_content .= wp_oembed_get( $url );
									}

									break;
								}
							}
						}

						if ( $found ) {
							break;
						}
					}
				}
			}
		}

		if ( ! empty( $partial_post_format_content ) && $format == 'video' && has_post_thumbnail() ) {
			$post_format_content .= '<script type="text/template" class="agncy-entry-video-content">';
				$post_format_content .= $partial_post_format_content;
			$post_format_content .= '</script>';
		}
	}

	return apply_filters( 'agncy_get_post_format_content', $post_format_content );
}

/**
 * Get the post categories if the theme is using more than one category.
 *
 * @since 1.0.0
 * @param string $separator Used between categories.
 * @return string
 */
function agncy_get_post_categories( $separator = ', ' ) {
	$categories_list = get_the_category_list( $separator );

	if ( $categories_list ) {
		return $categories_list;
	}

	return '';
}

/**
 * Get the post tags.
 *
 * @since 1.0.0
 * @param string $separator Used between tags.
 * @return string
 */
function agncy_get_post_tags( $separator = ', ' ) {
	$tag_list = get_the_tag_list( '', $separator, '' );

	if ( $tag_list ) {
		return $tag_list;
	}

	return '';
}

/**
 * Get the posts navigation markup.
 *
 * @since 1.0.0
 * @param array $config Configuration array.
 * @return string
 */
function agncy_get_pagination( $config = array() ) {
	global $wp_query;

	$config = wp_parse_args( $config, array(
		'query' => $wp_query,
		'title' => __( 'Posts navigation', 'agncy' ),
		'range' => 1,
		'prev'  => '&lsaquo;',
		'next'  => '&rsaquo;',
	) );

	extract( $config );

	$allowed_html = array(
		'span' => array(
			'class' => array(),
		)
	);

	/* Total number of pages. */
	$pages = $pages = $query->max_num_pages ? absint( $query->max_num_pages ) : 1;

	if ( $pages <= 1 ) {
		return '';
	}

	$html = '';

	/* Current page. */
	$paged = 1;

	if ( get_query_var( 'paged' ) ) {
		$paged = absint( get_query_var( 'paged' ) );
	}
	elseif ( get_query_var( 'page' ) ) {
		$paged = absint( get_query_var( 'page' ) );
	}

	/* Link back to the first page. */
	$show_first = true;

	/* Link to the last page. */
	$show_last = true;

	/* Link to the next page. */
	$show_next = $paged < $pages;

	/* Link to the previous page. */
	$show_prev = $paged > 1;

	$html .= '<nav class="navigation pagination">';
		if ( ! empty( $config['title'] ) ) {
			$html .= sprintf( '<h2 class="screen-reader-text">%s</h2>', esc_html( $config['title'] ) );
		}

		$link = '<a class="%s page-numbers" title="%s" href="%s">%s</a>';
		$current = '<span class="%s page-numbers" title="%s" href="%s">%s</span>';

		$html .= '<div class="nav-links">';
			if ( $show_next ) {
				$html .= sprintf( '<a class="next page-numbers" title="%s" href="%s">%s</a>',
					esc_attr( __( 'Go to the next page', 'agncy' ) ),
					esc_attr( get_pagenum_link( $paged + 1 ) ),
					esc_html( $next )
				);
			}

			if ( $show_first ) {
				$show_first_class = '';
				$show_first_html = $link;

				if ( 1 == $paged ) {
					$show_first_class = 'current';
					$show_first_html = $current;
				}

				$html .= sprintf( $show_first_html,
					esc_attr( $show_first_class ),
					esc_attr( __( 'Go to the first page', 'agncy' ) ),
					esc_attr( get_pagenum_link( 1 ) ),
					esc_html( 1 )
				);
			}

			if ( $paged - $range > 2 ) {
				$html .= '<span class="page-numbers dots">&hellip;</span>';
			}

			for ( $i = 2; $i < $pages; $i++ ) {
				if ( $i <= $paged + $range && $i >= $paged - $range ) {
					$number_class = '';
					$number_html = $link;

					if ( $i == $paged ) {
						$number_class = 'current';
						$number_html = $current;
					}

					$text = sprintf( wp_kses( __( '<span class="meta-nav screen-reader-text">Page </span>%s', 'agncy' ), $allowed_html ), $i );

					$html .= sprintf( $number_html,
						esc_attr( $number_class ),
						esc_attr( sprintf( __( 'Go to page number %s', 'agncy' ), $i ) ),
						esc_attr( get_pagenum_link( $i ) ),
						wp_kses_post( $text ),
						esc_html( $i )
					);
				}
			}

			if ( $paged < $pages - $range - 1 ) {
				$html .= '<span class="page-numbers dots">&hellip;</span>';
			}

			if ( $show_last ) {
				$show_last_class = '';
				$show_last_html = $link;

				if ( $pages == $paged ) {
					$show_last_class = 'current';
					$show_last_html = $current;
				}

				$html .= sprintf( $show_last_html,
					esc_attr( $show_last_class ),
					esc_attr( __( 'Go to the last page', 'agncy' ) ),
					esc_attr( get_pagenum_link( $pages ) ),
					esc_html( $pages )
				);
			}

			if ( $show_prev ) {
				$html .= sprintf( '<a class="prev page-numbers" title="%s" href="%s">%s</a>',
					esc_attr( __( 'Go to the previous page', 'agncy' ) ),
					esc_attr( get_pagenum_link( $paged - 1 ) ),
					esc_html( $prev )
				);
			}
		$html .= '</div>';
	$html .= '</nav>';

	return $html;
}

/**
 * Display the posts navigation.
 *
 * @since 1.0.0
 * @param array $config Configuration array.
 */
function agncy_pagination( $config = array() ) {
	$html = agncy_get_pagination( $config );

	if ( $html ) {
		ob_start();
		posts_nav_link( ' &#183; ', 'previous page', 'next page' );
		ob_end_clean();

		echo '<div class="agncy-page-navigation">';
			echo wp_kses_post( $html );
		echo '</div>';
	}
}

/**
 * Return the single post title.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_post_title() {
	$post_title = get_the_title();

	if ( empty ( $post_title ) ) {
		$post_title = __( 'No title', 'agncy' );
	}

	return $post_title;
}

/**
 * Display the comments count for the current entry.
 *
 * @since 1.0.0
 * @param boolean $numeric True if the output should be numeric.
 */
function agncy_comments_number( $numeric = false ) {
	global $post;

	if ( $numeric ) {
		comments_number( '0', '1', '%' );
	}
	else {
		comments_number( esc_html__( 'No comments', 'agncy' ), esc_html__( '1 comment', 'agncy' ), esc_html__( '% comments', 'agncy' ) );
	}
}

/**
 * Display the post tags.
 *
 * @since 1.0.0
 */
function agncy_post_tags() {
	$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'agncy' ) );

	if ( $tags_list ) {
		printf( '<div class="agncy-sp-tl">%1$s</div>',
			wp_kses_post( $tags_list )
		);
	}
}

/**
 * Display the post categories
 *
 * @since 1.0.0
 */
function agncy_post_categories() {
	$categories = agncy_get_post_categories();

	if ( $categories ) {
		printf( '<div class="agncy-entry-cl">%1$s</div>',
			wp_kses_post( $categories )
		);
	}
}

/**
 * Display the image meta attachment link.
 *
 * @since 1.0.0
 */
function agncy_image_meta() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$metadata = wp_get_attachment_metadata();
		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'agncy' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}
}

/**
 * Add a specific class to blog loops.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @return array
 */
function agncy_blog_loop_wrapper_class( $classes = array() ) {
	$classes[] = 'agncy-b-l-w';

	return $classes;
}

add_filter( 'brix_blog_loop_wrapper_class', 'agncy_blog_loop_wrapper_class' );

/**
 * Get the style used by posts in a loop.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return string
 */
function agncy_loop_post_style( $block_data = false ) {
	$style = '';

	if ( $block_data && isset( $block_data->agncy_style ) ) {
		$style = $block_data->agncy_style;
	}

	if ( ! $style ) {
		$style = 'default';
	}

	return apply_filters( 'agncy_loop_post_style', $style );
}

/**
 * Checks whether we should display the featured media in posts loops.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return boolean
 */
function agncy_blog_loop_display_featured_media( $block_data = false ) {
	$display_featured_media = true;
	$index_option = true;
	$archives_option = true;

	if ( function_exists( 'ev_get_option' ) ) {
		$index_option = ev_get_option( 'index_loop_featured_media' );
		$archives_option = ev_get_option( 'archives_loop_featured_media' );
	}

	if ( $block_data && isset( $block_data->agncy_featured_media ) ) {
		$display_featured_media = (bool) $block_data->agncy_featured_media;
	} elseif ( is_home() ) {
		$display_featured_media = (bool) $index_option;
	} elseif ( is_archive() ) {
		$display_featured_media = (bool) $archives_option;
	}

	return (bool) apply_filters( 'agncy_display_loop_featured_media', $display_featured_media );
}

/**
 * Return the image size to be used in the block loop.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return string
 */
function agncy_blog_loop_featured_media_size( $block_data = false ) {
	$featured_media_size = 'large';
	$index_option = '';
	$archives_option = '';

	if ( function_exists( 'ev_get_option' ) ) {
		$index_option = ev_get_option( 'index_loop_featured_image_sizes' );
		$archives_option = ev_get_option( 'archives_loop_featured_image_sizes' );
	}

	if ( $block_data && isset( $block_data->agncy_featured_image_sizes ) ) {
		$featured_media_size =  $block_data->agncy_featured_image_sizes;
	} elseif ( is_home() ) {
		$featured_media_size = $index_option;
	} elseif ( is_archive() ) {
		$featured_media_size = $archives_option;
	}

	return $featured_media_size;
}

/**
 * Checks whether we should display the excerpt in posts loops.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return boolean
 */
function agncy_blog_loop_display_excerpt( $block_data = false ) {
	$format = get_post_format();
	$index_option = false;
	$archives_option = false;

	if ( function_exists( 'ev_get_option' ) ) {
		$index_option = ev_get_option( 'index_loop_excerpt' );
		$archives_option = ev_get_option( 'archives_loop_excerpt' );
	}
	$display_excerpt = false;

	if ( $block_data && isset( $block_data->agncy_excerpt ) ) {
		$display_excerpt = (bool) $block_data->agncy_excerpt;
	} elseif ( is_home() ) {
		$display_excerpt = (bool) $index_option;
	} elseif ( is_archive() ) {
		$display_excerpt = (bool) $archives_option;
	}

	return (bool) apply_filters( 'agncy_display_loop_excerpt', $display_excerpt );
}

/**
 * Checks whether we should display the read more button in posts loops.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return boolean
 */
function agncy_blog_loop_display_read_more( $block_data = false ) {
	$display_read_more = false;
	$index_option = false;
	$archives_option = false;

	if ( function_exists( 'ev_get_option' ) ) {
		$index_option = ev_get_option( 'index_loop_read_more' );
		$archives_option = ev_get_option( 'archives_loop_read_more' );
	}

	if ( $block_data && isset( $block_data->agncy_read_more ) ) {
		$display_read_more = (bool) $block_data->agncy_read_more;
	} elseif ( is_home() ) {
		$display_read_more = (bool) $index_option;
	} elseif ( is_archive() ) {
		$display_read_more = (bool) $archives_option;
	}

	return (bool) apply_filters( 'agncy_display_loop_read_more', $display_read_more );
}


/**
 * Related posts loop
 *
 * @since 1.0.0
 */
function agncy_related_posts() {
	$args = array(
		'posts_per_page' => 3,
		'category__in' => wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) ),
		'orderby' => 'rand',
		'order'=> 'ID',
		'post__not_in' => array( get_the_ID() ),
	);

	$relatedQuery = new WP_Query( $args );

	if( $relatedQuery->have_posts() ) {
	?>
	<div class="agncy-sp-related">

		<h2><?php echo __( 'Related articles', 'agncy' ); ?></h2>

		<?php
			while( $relatedQuery->have_posts() )
			{
				$relatedQuery->the_post();

				echo '<div class="agncy-sp-sr">';
					agncy_entry_date();
					printf( '<a class="agncy-sp-sr-title" href="%s"><h5>%s</h5></a>', get_the_permalink(), agncy_get_post_title() );
					printf( '<a class="agncy-sp-sr-readmore" href="%s">%s</a>', get_the_permalink(), __( 'Read more', 'agncy' ) );
				echo '</div>';

			}
			wp_reset_postdata();
		?>

	</div>
	<?php }
}

/**
 * Return an icon for the sticky post
 *
 * @since 1.0.0
 */
function agncy_sticky_icon() {
	if ( is_sticky() ) {
		echo agncy_load_svg( 'img/pin.svg' );
	}
}