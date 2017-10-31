<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Output the builder contents.
 *
 * @since 1.0.0
 * @param integer $page_id If set, will pick up builder contents from the specified page.
 */
function brix( $page_id = false ) {
	echo BrixBuilder::instance()->render_frontend( '', $page_id );
}

/**
 * Check if Brix is officially supported.
 *
 * @since 1.2.7
 * @return boolean
 */
function brix_is_supported() {
	return apply_filters( 'brix_is_supported', true );
}

/**
 * Check if Brix is officially documented.
 *
 * @since 1.2.7
 * @return boolean
 */
function brix_is_documented() {
	return apply_filters( 'brix_is_documented', true );
}

/**
 * Get the included scripts suffix.
 *
 * @since 1.1.3
 * @return string
 */
function brix_get_scripts_suffix() {
	$suffix = defined( 'BRIX_DEBUG_SCRIPTS' ) && BRIX_DEBUG_SCRIPTS ? 'compact' : 'min';

	return $suffix;
}

/**
 * Return an array of defined content blocks.
 *
 * @since 1.0.0
 * @param string $group The block group.
 * @return array
 */
function brix_get_blocks( $group = false ) {
	$blocks = apply_filters( 'brix_get_blocks', array() );

	if ( $group ) {
		foreach ( $blocks as $type => $block ) {
			if ( ! isset( $blocks[$type]['group'] ) || $blocks[$type]['group'] !== $group ) {
				unset( $blocks[$type] );
			}
		}
	}

	return $blocks;
}

/**
 * Check if a builder component's appearance settings have been tweaked.
 *
 * @since 1.0.0
 * @param array $data The component appearance data.
 * @return boolean
 */
function brix_has_appearance( $data ) {
	if ( isset( $data->_vertical_alignment ) && ( ! in_array( $data->_vertical_alignment, array( '', 'top' ) ) ) ) {
		return true;
	}

	if ( isset( $data->background ) && $data->background != '' ) {
		return true;
	}

	if ( isset( $data->spacing ) ) {
		$breakpoints = brix_get_breakpoints();

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			if ( isset( $data->spacing->$breakpoint_key ) ) {
				foreach ( $data->spacing->$breakpoint_key as $k => $v ) {
					if ( ! empty( $v ) ) {
						return true;
					}
				}
			}
		}
	}

	if ( isset( $data->enable_column_carousel ) && $data->enable_column_carousel == '1' ) {
		return true;
	}

	return false;
}

/**
 * Translate a CSS rule key to a valid CSS rule name.
 *
 * @since 1.0.0
 * @param string $key The CSS rule key.
 * @return string
 */
function brix_css_rule_mapping( $key ) {
	switch ( $key ) {
		case 'margin_top':
			return 'margin-top';
			break;
		case 'margin_right':
			return 'margin-right';
			break;
		case 'margin_bottom':
			return 'margin-bottom';
			break;
		case 'margin_left':
			return 'margin-left';
			break;
		case 'padding_top':
			return 'padding-top';
			break;
		case 'padding_right':
			return 'padding-right';
			break;
		case 'padding_bottom':
			return 'padding-bottom';
			break;
		case 'padding_left':
			return 'padding-left';
			break;
		default:
			return false;
	}

	return false;
}

/**
 * Get the list of fields that compose the background functionality.
 *
 * @since 1.0.0
 * @param string $context The context string
 * @return array
 */
function brix_get_background( $context = '' ) {
	$background_fields = array();

	// $background_fields[] = array(
	// 	'handle' => 'background_divider',
	// 	'text' => __( 'Background', 'brix' ),
	// 	'type' => 'divider',
	// 	'config' => array(
	// 		'style' => 'in_page',
	// 	)
	// );

	$background_fields[] = array(
		'handle' => 'background',
		'label'  => array(
			'text' => __( 'Background', 'brix' ),
			'type' => 'block'
		),
		'type'   => 'radio',
		'config' => array(
			'class' => 'brix-background-type-wrapper',
			'controller' => true,
			'style' => 'graphic',
			'data' => array(
				''      => BRIX_ADMIN_ASSETS_URI . 'css/i/background/disabled.svg',
				'image' => BRIX_ADMIN_ASSETS_URI . 'css/i/background/image.svg',
				'video' => BRIX_ADMIN_ASSETS_URI . 'css/i/background/video.svg'
			)
		)
	);

	$background_fields[] = array(
		'handle' => 'background_image',
		'label' => array(
			'text' => __( 'Color and image', 'brix' ),
			'type' => 'hidden'
		),
		'type' => 'brix_background',
		'config' => array(
			'visible' => array( 'background' => 'image' )
		),
	);

	$background_fields[] = array(
		'handle' => 'background_video',
		'label' => array(
			'text' => __( 'Video', 'brix' ),
			'type' => 'hidden'
		),
		'type' => 'brix_video_background',
		'config' => array(
			'visible' => array( 'background' => 'video' )
		),
	);

	$background_fields = apply_filters( 'brix_get_background', $background_fields );

	if ( ! empty( $context ) ) {
		$background_fields = apply_filters( "brix_get_background[context:$context]", $background_fields );
	}

	return $background_fields;
}

/**
 * Search among post type entries.
 *
 * @since 1.0.0
 * @param search $search The search string.
 * @param array $args The arguments of the search query.
 * @return array
 */
function brix_search_items( $search, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'post_type' => 'post',
	) );

	$args['s'] = $search;
	$results = array();
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$results[] = array(
				'id' => get_the_ID(),
				'text' => html_entity_decode( get_the_title() )
			);
		}
	}

	return $results;
}

/**
 * Format the text contents.
 *
 * @since 1.0.0
 * @param string $content The text content.
 * @param array $config The text content configuration.
 * @return string
 */
function brix_format_text_content( $content, $config = array() ) {
	$config = wp_parse_args( $config, array(
		'embed' => true,
		'shortcodes' => false
	) );

	extract( $config );

	global $wp_embed;

	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = convert_chars( $content );

	if ( $embed ) {
		$content = $wp_embed->autoembed( $content );
	}

	$content = wpautop( $content );

	if ( $shortcodes ) {
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
	}

	return $content;
}

/**
 * Output a bundle that defines a link control.
 *
 * @since 1.0.0
 * @param string $handle The bundle handle.
 * @param string $label The bundle label.
 * @return array
 */
function brix_link_bundle( $handle, $label ) {
	$link_bundle = array(
		'type' => 'bundle',
		'label' => $label,
		'handle' => $handle,
		'fields' => array(
			array(
				'handle' => 'url',
				'label'  => __( 'URL', 'brix' ),
				'type'   => 'text',
				'config' => array(
					'full' => true
				)
			),
			array(
				'handle' => 'target',
				'label'  => __( 'Open in...', 'brix' ),
				'type'   => 'select',
				'config' => array(
					'data' => array(
						'' => __( 'Same tab', 'brix' ),
						'_blank' => __( 'New tab', 'brix' ),
					)
				)
			),
			array(
				'handle' => 'rel',
				'label'  => __( 'Rel attribute', 'brix' ),
				'type'   => 'text'
			),
			array(
				'handle' => 'title',
				'label'  => __( 'Title attribute', 'brix' ),
				'type'   => 'text'
			)
		)
	);

	$link_bundle = apply_filters( 'brix_link_bundle', $link_bundle );

	return $link_bundle;
}

/**
 * Output a bundle that defines an icon control.
 *
 * @since 1.0.0
 * @param string $handle The bundle handle.
 * @param string $label The bundle label.
 * @return array
 */
function brix_icon_bundle( $handle, $label, $config = array() ) {
	$config = wp_parse_args( $config, array(
		'modal' => true,
	) );

	$icon_config = array(
		'modal' => $config[ 'modal' ],
	);

	$icon_bundle = array(
		'type' => 'bundle',
		'label' => $label,
		'handle' => $handle,
		'help' => __( 'A decorative element that can either be an icon or an image from the Media Library or an external source.', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'type',
				'type' => 'select',
				'label' => __( 'Type', 'brix' ),
				'config' => array(
					'controller' => true,
					'data' => array(
						'icon' => __( 'Icon', 'brix' ),
						'image' => __( 'Image', 'brix' )
					)
				)
			),
			array(
				'handle' => 'icon',
				'type' => 'icon',
				'label' => __( 'Icon', 'brix' ),
				'config' => array_merge(
					array(
						'visible' => array( 'type' => 'icon' )
					),
					$icon_config
				)
			),
			array(
				'handle' => 'image_source',
				'type' => 'select',
				'label' => __( 'Image source', 'brix' ),
				'config' => array(
					'controller' => true,
					'visible' => array( 'type' => 'image' ),
					'data' => array(
						'image'    => __( 'Media library', 'brix' ),
						'external' => __( 'External link', 'brix' )
					)
				)
			),
			array(
				'handle' => 'image',
				'type' => 'image',
				'label' => __( 'Image', 'brix' ),
				'config' => array(
					'visible' => array( 'image_source' => 'image' ),
					'density' => true,
					'image_size' => true
				)
			),
			array(
				'handle' => 'image_url',
				'type' => 'text',
				'label' => __( 'External link', 'brix' ),
				'help' => __( 'Specify the full URL to the image.', 'brix' ),
				'config' => array(
					'full' => true,
					'visible' => array( 'image_source' => 'external' )
				)
			),
			array(
				'handle' => 'width',
				'type' => 'text',
				'label' => __( 'Width', 'brix' ),
				'help' => __( 'The desired image width, expressed in pixels.', 'brix' ),
				'config' => array(
					'visible' => array( 'type' => 'image' )
				)
			),
			array(
				'handle' => 'height',
				'type' => 'text',
				'label' => __( 'Height', 'brix' ),
				'help' => __( 'The desired image height, expressed in pixels.', 'brix' ),
				'config' => array(
					'visible' => array( 'type' => 'image' )
				)
			),
		)
	);

	$icon_bundle = apply_filters( 'brix_icon_bundle', $icon_bundle );

	return $icon_bundle;
}

/**
 * Get a list of breakpoints managed by the builder.
 *
 * @since 1.0.0
 * @return array
 */
function brix_breakpoints() {
	$breakpoints = brix_get_breakpoints();
	$breakpoints = apply_filters( 'brix_breakpoints', $breakpoints );

	return $breakpoints;
}

/**
 * Unprotect Brix metas in order for posts to be duplicated or translated.
 *
 * @since 1.1.1
 * @param boolean $protected Set to false to unprotect.
 * @param string $meta_key The meta key.
 * @param string $meta_key The Post Type.
 * @return boolean
 */
function brix_is_protected_meta( $protected, $meta_key, $meta_type ) {
	global $pagenow;

	$is_post_page = $pagenow === 'post.php' || $pagenow === 'post-new.php';
	$keys = array(
		'_brix_used',
		'_brix'
	);

	if ( $meta_type === 'post' || ! $meta_type ) {
		if ( in_array( $meta_key, $keys ) ) {
			/* If it's a Brix key, let's protect it in the page editing screen (fixes Safari rendering bug with serialized data). */
			return did_action( 'admin_head' );
		}
	}

	return $protected;
}

add_filter( 'is_protected_meta', 'brix_is_protected_meta', 10, 3 );

/**
 * Display the markup required to display a background for a builder elements.
 *
 * @since 1.0.0
 * @param stdClass $background_data The background data.
 * @param string $context The background context.
 * @return string
 */
function brix_background( $background_data, $context = '' ) {
	if ( ! $background_data ) {
		return '';
	}

	if ( ! isset( $background_data->background ) || empty( $background_data->background ) ) {
		return '';
	}

	$markup = '<div class="brix-' . esc_attr( $context ) . '-background-wrapper brix-background-wrapper brix-background-type-' . esc_attr( $background_data->background ) .'">';

		// TODO: put a guard to check if the background has been specified
		$markup .= '<span class="brix-background-overlay"></span>';

		if ( $background_data->background === 'image' ) {
			$markup .= brix_background_image( $background_data->background_image );
		}
		elseif ( $background_data->background === 'video' ) {
			$markup .= brix_background_video( $background_data->background_video );
		}

	$markup .= '</div>';

	return $markup;
}

/**
 * Return the overlay inner style.
 *
 * @since 1.1.1
 * @param array $data The overlay data.
 * @param string $selector The overlay selector.
 * @param string $media The media query being used.
 * @return string
 */
function brix_background_overlay_inner_style( $data, $selector, $media = '' ) {
	$background_overlay_style = '';
	$rules             = array();
	$background_color  = '';
	$background_image  = '';
	$background_repeat = '';
	$gradient_styles   = array();

	if ( isset( $data->overlay_type ) && $data->overlay_type == 'solid' ) {
		/* Overlay solid color */
		if ( isset( $data->overlay->color->color ) && ! empty( $data->overlay->color->color ) ) {
			$background_color = $data->overlay->color->color;

			if ( ! brix_string_contains( $data->overlay->color->color, 'rgba' ) ) {
				$background_color = brix_string_ensure_left( $data->overlay->color->color, '#' );
			}

			$rules[] = array(
				'name' => 'background',
				'value' => $background_color
			);
		}
	}
	elseif ( isset( $data->overlay_type ) && $data->overlay_type == 'gradient' ) {
		/* Overlay gradient. */
		if ( isset( $data->overlay->gradient ) && ! empty( $data->overlay->gradient ) ) {
			$gradient_styles = brix_background_gradient_style( $data->overlay->gradient );

			foreach ( $gradient_styles as $gradient_style ) {
				$rules[] = array(
					'name' => 'background',
					'value' => $gradient_style
				);
			}
		}
	}
	elseif ( isset( $data->overlay_type ) && $data->overlay_type == 'image' ) {
		/* Overlay image. */
		if ( isset( $data->overlay->image )
		  && isset( $data->overlay->image->desktop )
		  && isset( $data->overlay->image->desktop[1] )
		  && isset( $data->overlay->image->desktop[1]->id )
		  && ! empty( $data->overlay->image->desktop[1]->id ) ) {
			$background_image = 'url(' . brix_get_image( $data->overlay->image->desktop[1]->id, $data->overlay->image->desktop[1]->image_size ) . ')';

			if ( isset( $data->overlay->image->repeat ) ) {
				$background_repeat = $data->overlay->image->repeat;
			}

			$rules[] = array(
				'name' => 'background',
				'value' => 'transparent ' . $background_image . ' ' . $background_repeat
			);

			if ( ! empty( $data->overlay->image->size ) ) {
				$rules[] = array(
					'name' => 'background-size',
					'value' => $data->overlay->image->size
				);
			}
		}
	}

	if ( ! empty( $rules ) ) {
		if ( $media ) {
			$background_overlay_style .= $media . '{';
		}

		$background_overlay_style .= $selector . '{';
			foreach ( $rules as $rule ) {
				$background_overlay_style .= $rule['name'] . ':' . trim( $rule['value'] ) . ';';
			}
		$background_overlay_style .= '}';

		if ( $media ) {
			$background_overlay_style .= '}';
		}
	}

	return $background_overlay_style;
}

/**
 * Return the markup for the background overlay.
 *
 * @since 1.0.0
 * @param array $data The section data.
 * @return string
 */
function brix_background_overlay_style( $data, $selector = '' ) {
	if ( ! $data ) {
		return '';
	}

	$background_overlay_style = '';
	$breakpoints = brix_breakpoints();
	$background_type = isset( $data->background ) ? $data->background : false;

	if ( $background_type === 'image' && isset( $data->background_image->desktop ) ) {
		foreach ( $data->background_image as $breakpoint_key => $breakpoint_data ) {
			$media = isset( $breakpoints[$breakpoint_key] ) ? $breakpoints[$breakpoint_key]['media_query'] : '';

			$background_overlay_style .= brix_background_overlay_inner_style( $breakpoint_data, $selector, $media );
		}
	}
	elseif ( $background_type === 'video' && isset( $data->background_video ) ) {
		$background_overlay_style .= brix_background_overlay_inner_style( $data->background_video, $selector, '' );
	}

	$background_overlay_style = apply_filters( 'brix_background_overlay_style', $background_overlay_style, $data );

	return $background_overlay_style;
}

/**
 * Return the markup for an image background.
 *
 * @since 1.0.0
 * @param array $data The section data.
 * @return string
 */
function brix_background_image( $data ) {
	$markup = '';

	$parallax = false;
	$parallax_data = array();
	$shift = '';

	if ( isset( $data->desktop ) ) {
		foreach ( $data as $breakpoint_key => $breakpoint_data ) {
			$breakpoint_is_parallax = isset( $breakpoint_data->image ) && isset( $breakpoint_data->image->attachment ) && $breakpoint_data->image->attachment === 'parallax';
			$breakpoint_is_inherit = isset( $breakpoint_data->inherit ) && $breakpoint_data->inherit;

			if ( $breakpoint_is_parallax ) {
				$parallax = true;

				if ( ! empty( $breakpoint_data->image->shift ) && ! $breakpoint_is_inherit ) {
					$shift = $breakpoint_data->image->shift;
				}
			}
			elseif ( isset( $breakpoint_data->inherit ) && $breakpoint_data->inherit ) {
				if ( ! empty( $data->desktop->image->shift ) ) {
					$shift = $data->desktop->image->shift;
				}
			}

			if ( $shift ) {
				$parallax_data[$breakpoint_key] = $shift;
			}
		}
	}
	else {
		if ( isset( $data->image ) && isset( $data->image->attachment ) ) {
			$parallax = $data->image->attachment === 'parallax' && ! empty( $data->image->shift );

			$shift = $data->image->shift;
			$parallax_data['desktop'] = $shift;
		}
	}

	$background_classes = array(
		'brix-background-image'
	);

	$data_attrs = '';

	if ( $parallax ) {
		$background_classes[] = 'brix-background-parallax';

		$data_attrs .= ' data-parallax="' . esc_attr( json_encode( $parallax_data ) ) . '"';
	}

	$markup .= sprintf( '<div class="%s" %s></div>',
		esc_attr( implode( ' ', $background_classes ) ),
		$data_attrs
	);

	return $markup;
}

/**
 * Return the markup for a video background.
 *
 * @since 1.0.0
 * @param array $data The section data.
 * @return string
 */
function brix_background_video( $data ) {
	if ( isset( $data->video_data ) ) {
		$data = $data->video_data;
	}

	$video = '';
	$markup = '';
	$parallax = false;
	$parallax_data = array();
	$mobile_disabled = isset( $data->mobile_disabled ) && $data->mobile_disabled;

	if ( isset( $data->url ) && ! empty( $data->url ) ) {
		$is_vimeo = strstr( $data->url, 'vimeo' ) !== false;
		$is_youtube = strstr( $data->url, 'youtu' ) !== false;

		if ( $is_vimeo || $is_youtube ) {
			global $wp_embed;
			$video = $wp_embed->autoembed( $data->url );
		}
		else {
			$video_markup = apply_filters( 'brix_background_video_markup', '<video preload="none" loop muted><source src="%s" type="video/mp4"></video>' );
			$video = sprintf( $video_markup, esc_attr( $data->url ) );
		}

		if ( wp_is_mobile() && $mobile_disabled ) {
			$video = '';
		}
	}

	$poster_image = isset( $data->poster_image ) && isset( $data->poster_image->desktop ) && isset( $data->poster_image->desktop[1] ) && isset( $data->poster_image->desktop[1]->id ) && ! empty( $data->poster_image->desktop[1]->id ) ? true : false;

	if ( ! $video && ! $poster_image ) {
		return '';
	}

	$background_classes = array(
		'brix-background-video-container',
		'brix-background-image',
		// 'brix-fit-vids'
	);

	$data_attrs = '';

	if ( $data->attachment === 'parallax' ) {
		$background_classes[] = 'brix-background-parallax';
		$shift = $data->shift;

		$parallax_data['desktop'] = $shift;
		$data_attrs .= ' data-parallax="' . esc_attr( json_encode( $parallax_data ) ) . '"';
	}

	if ( $mobile_disabled ) {
		$data_attrs .= ' data-mobile-disabled="1"';
	}

	$markup .= sprintf( '<div class="%s" %s>' . $video . '</div>',
		esc_attr( implode( ' ', $background_classes ) ),
		$data_attrs
	);

	return $markup;
}

/**
 * Return the style to generate a gradient.
 *
 * @since 1.1.1
 * @param array $data The gradient data.
 * @return array
 */
function brix_background_gradient_style( $data ) {
	$direction = 'left';
	$reverse = $data->reverse;
	$gradient_style = array();

	switch ( $data->direction ) {
		case 'radial':
			break;
		case 'diagonal_up':
			$direction = '45deg';
			break;
		case 'diagonal_down':
			$direction = '135deg';
			break;
		case 'vertical':
			$direction = '180deg';
			break;
		case 'horizontal':
		default:
			$direction = '90deg';
			break;
	}

	$stops = array();
	$color_stops = array();
	$data = (array) $data;
	$colors = array();

	foreach ( $data as $stop_key => $stop ) {
		if ( ! brix_string_starts_with( $stop_key, 'stop_' ) ) {
			continue;
		}

		$colors[] = $stop->color->color;
	}

	if ( $reverse ) {
		$colors = array_reverse( $colors );
	}

	$j = 0;
	foreach ( $data as $stop_key => $stop ) {
		if ( ! brix_string_starts_with( $stop_key, 'stop_' ) ) {
			continue;
		}

		$stops[] = $colors[ $j ] . ' ' . $stop->location . '%';

		$j++;
	}

	$data = (object) $data;

	if ( $data->direction === 'radial' ) {
		$gradient_style[] = sprintf( '-webkit-radial-gradient(%s,%s,%s)',
			'center',
			'ellipse cover',
			implode( ',', $stops )
		);

		$gradient_style[] = sprintf( 'radial-gradient(%s,%s)',
			'ellipse at center',
			implode( ',', $stops )
		);
	}
	else {
		$gradient_style[] = sprintf( '-webkit-linear-gradient(%s,%s)',
			$direction,
			implode( ',', $stops )
		);

		$gradient_style[] = sprintf( 'linear-gradient(%s,%s)',
			$direction,
			implode( ',', $stops )
		);
	}

	return $gradient_style;
}

/**
 * Output the background style associated to sections and columns.
 *
 * @since 1.0.0
 * @param stdClass $background_data The background data object.
 * @param string $selector The background CSS selector.
 * @return string
 */
function brix_background_style( $data, $selector = '' ) {
	if ( ! $data ) {
		return '';
	}

	$background_style = '';
	$breakpoints = brix_breakpoints();
	$background_type = $data->background;

	if ( $background_type == 'image' ) {
		if ( ! isset( $data->background_image->desktop ) ) {
			$advanced = false;
			$d = $data->background_image;
			$data->background_image = new stdClass();
			$data->background_image->desktop = $d;
		}
		else {
			$advanced = (bool) $data->background_image->edit_responsive;
		}

		foreach ( $data->background_image as $breakpoint_key => $_data ) {
			if ( $breakpoint_key === 'desktop' ) {
				$inherit = false;
			}
			else {
				$inherit = isset( $_data->inherit ) ? (bool) $_data->inherit : true;
			}

			if ( ! $advanced && $breakpoint_key !== 'desktop' ) {
				continue;
			}

			if ( $inherit ) {
				continue;
			}

			$media = isset( $breakpoints[$breakpoint_key] ) ? $breakpoints[$breakpoint_key]['media_query'] : '';
			$color_type = isset( $_data->color_type ) ? $_data->color_type : 'solid';
			$rules = array();
			$image_rules = array();

			$background_color = '';
			$background_image = '';
			$background_repeat = '';
			$background_position = '';
			$gradient_styles = array();

			if ( $color_type == 'solid' ) {
				/* Background color. */
				if ( isset( $_data->color ) && isset( $_data->color->color ) && ! empty( $_data->color->color ) ) {
					$background_color = brix_string_ensure_left( $_data->color->color, '#' );
				}
			}
			elseif ( $color_type == 'gradient' ) {
				/* Background gradient. */
				if ( isset( $_data->gradient ) && ! empty( $_data->gradient ) ) {
					$gradient_styles = brix_background_gradient_style( $_data->gradient );
				}
			}

			if ( isset( $_data->image ) && isset( $_data->image->desktop ) && isset( $_data->image->desktop[1] ) && isset( $_data->image->desktop[1]->id ) && ! empty( $_data->image->desktop[1]->id ) ) {
				$background_image = 'url(' . brix_get_image( $_data->image->desktop[1]->id, $_data->image->desktop[1]->image_size ) . ')';

				if ( isset( $_data->image->position ) ) {
					$background_position = $_data->image->position;
				}

				if ( isset( $_data->image->repeat ) ) {
					$background_repeat = $_data->image->repeat;
				}
			}

			if ( $background_color ) {
				$rules[] = array(
					'name' => 'background',
					'value' => $background_color
				);
			}

			if ( $background_image ) {
				$image_rules[] = array(
					'name' => 'background',
					'value' => $background_image . ' ' . $background_position . ' ' . $background_repeat
				);

				if ( isset( $_data->image->size ) && ! empty( $_data->image->size ) ) {
					$image_rules[] = array(
						'name' => 'background-size',
						'value' => $_data->image->size
					);
				}

				if ( isset( $_data->image->attachment ) && $_data->image->attachment === 'fixed' ) {
					$image_rules[] = array(
						'name' => 'background-attachment',
						'value' => $_data->image->attachment
					);
				}
			}

			if ( $gradient_styles ) {
				foreach ( $gradient_styles as $gradient_style ) {
					$rules[] = array(
						'name' => 'background',
						'value' => $gradient_style
					);
				}
			}

			if ( ! empty( $rules ) || ! empty( $image_rules ) ) {
				if ( $media ) {
					$background_style .= $media . '{';
				}

				if ( ! empty( $rules ) ) {
					$background_style .= $selector . '{';
						foreach ( $rules as $rule ) {
							$background_style .= $rule['name'] . ':' . trim( $rule['value'] ) . ';';
						}
					$background_style .= '}';
				}

				if ( ! empty( $image_rules ) ) {
					$background_style .= $selector . ' .brix-background-image{';
						foreach ( $image_rules as $rule ) {
							$background_style .= $rule['name'] . ':' . trim( $rule['value'] ) . ';';
						}
					$background_style .= '}';
				}
				else {
					$background_style .= $selector . ' .brix-background-image{';
						$background_style .= 'background-image:none;';
					$background_style .= '}';
				}

				if ( $media ) {
					$background_style .= '}';
				}
			} else if ( empty( $image_rules ) ) {
				if ( $media ) {
					$background_style .= $media . '{';

						$background_style .= $selector . ' .brix-background-image{';
							$background_style .= 'background-image:none;';
						$background_style .= '}';

					$background_style .= '}';
				}
			}
		}
	}
	elseif ( $background_type === 'video' ) {
		$_data = $data->background_video;

		if ( isset( $_data->video_data ) ) {
			$_data = $_data->video_data;
		}

		$rules = array();

		if ( isset( $_data->poster_image ) && isset( $_data->poster_image->desktop ) && isset( $_data->poster_image->desktop[1] ) && isset( $_data->poster_image->desktop[1]->id ) && ! empty( $_data->poster_image->desktop[1]->id ) ) {
			$background_image = 'url(' . brix_get_image( $_data->poster_image->desktop[1]->id, $_data->poster_image->desktop[1]->image_size ) . ')';

			$rules[] = array(
				'name' => 'background-image',
				'value' => $background_image
			);
		}

		if ( ! empty( $rules ) ) {
			$background_style .= $selector . '{';
				foreach ( $rules as $rule ) {
					$background_style .= $rule['name'] . ':' . trim( $rule['value'] ) . ';';
				}
			$background_style .= '}';
		}
	}

	$background_style = apply_filters( 'brix_background_style', $background_style, $data );

	return $background_style;
}

/**
 * Get the browser name.
 *
 * @since 1.2.8
 * @return string
 */
function brix_get_browser_name() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'opera';
	elseif (strpos($user_agent, 'Edge')) return 'edge';
	elseif (strpos($user_agent, 'Chrome')) return 'chrome';
	elseif (strpos($user_agent, 'Safari')) return 'safari';
	elseif (strpos($user_agent, 'Firefox')) return 'firefox';
	elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'iexplorer';

	return 'other';
}

/**
 * Image <figure> CSS class.
 *
 * @since 1.2.9
 * @return string
 */
function brix_get_image_figure_class() {
	$class = 'brix-image';

    return apply_filters( 'brix_image_figure_class', $class );
}

/**
 * Get the proper markup for an image to be lazy-loaded.
 *
 * @since 1.2.9
 * @param integer|string $url Image ID or URL.
 * @param array $args Arguments.
 * @return string
 */
function brix_get_lazy_image_markup( $url, $args = array() ) {
	if ( ! $url ) {
		return '';
	}

	/* If we're specifying an ID, declare the image as internal (uploaded through the Media Library). */
	$external = ! is_numeric( $url );

	/* CSS classes added to the <img> element. */
	$classes = isset( $args[ 'classes' ] ) ? implode( ' ', (array) $args[ 'classes' ] ) : '';
	$classes .= ' brix-block-preloaded-img';
	$classes = apply_filters( 'brix_lazy_image_classes', $classes );

	/* Image placeholder. */
	$placeholder = isset( $args[ 'placeholder' ] ) ? $args[ 'placeholder' ] : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
	$placeholder = apply_filters( 'brix_lazy_image_placeholder', $placeholder );

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
		$url = brix_get_image( $url, $size );
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
		'<span class="brix-image-wrapper"><img data-src="%s" src="%s" class="%s" %s /><b %s></b></span>',
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
		$img_html = brix_link( $link, $img_html, false );
	}

	$html = sprintf( '<figure class="%s">%s</figure>',
		esc_attr( brix_get_image_figure_class() ),
		$img_html
	);

	return $html;
}

/**
 * Retrieve a video's poster image from a remote service such as YouTube or
 * Vimeo.
 *
 * @since 1.2.9
 * @param string $url The embed URL.
 * @return array
 */
function brix_get_video_poster_image( $url ) {
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
 * Check if the current theme supports a particular Brix feature.
 *
 * @since 1.2.15
 * @param string $feature The feature string.
 * @return boolean
 */
function brix_current_theme_supports( $feature ) {
	return BrixBuilder::instance()->has_support( $feature );
}

/**
 * Mark the current theme to support a particular Brix feature.
 *
 * @since 1.2.15
 * @param string $feature The feature string.
 */
function brix_add_theme_support( $feature ) {
	return BrixBuilder::instance()->add_support( $feature );
}