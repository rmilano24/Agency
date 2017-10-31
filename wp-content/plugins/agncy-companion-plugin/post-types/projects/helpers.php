<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Get a list of layouts to be used in project pages.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_project_layouts_for_select() {
	$layouts = array(
		'a' => AGNCY_COMPANION_PLUGIN_URI . '/assets/img/project_a_icon.svg',
		'b' => AGNCY_COMPANION_PLUGIN_URI . '/assets/img/project_b_icon.svg',
		'c' => AGNCY_COMPANION_PLUGIN_URI . '/assets/img/project_c_icon.svg',
	);

	return apply_filters( 'agncy_project_layouts', $layouts );
}

/**
 * Return a list of meta definitions that are associated to the project.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_projects_metas() {
	$metas = array(
		'_year'     => __( 'Year', 'agncy-companion-plugin' ),
		'_client'   => __( 'Client', 'agncy-companion-plugin' ),
		'_role'     => __( 'Role', 'agncy-companion-plugin' ),
	);

	$metas = apply_filters( 'agncy_projects_metas', $metas );

	return $metas;
}

/**
 * Get a list of categories used by projects.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_projects_get_categories() {
	$cats = array();
	$terms = get_terms( array(
		'taxonomy' => 'agncy_project_category',
		'hide_empty' => false,
	) );

	foreach ( $terms as $term ) {
		$cats[ $term->term_id ] = sprintf( __( 'Project Category: %s', 'agncy-companion-plugin' ), $term->name );
	}

	return $cats;
}

/**
 * Add custom post classes to project in loops.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @param object $data The block data.
 * @param integer $post_id The project ID.
 * @param integer $index The index of the project in the loop.
 * @return array
 */
function agncy_project_item_classes( $classes, $data, $post_id, $index ) {
	$loop_style       = isset( $data->agncy_style ) ? $data->agncy_style : 'grid';
	$item_style       = isset( $data->agncy_portfolio_items_grid_style ) ? $data->agncy_portfolio_items_grid_style : '';
	$metro_item_style = isset( $data->agncy_portfolio_items_metro_style ) ? $data->agncy_portfolio_items_metro_style : '';

	$classes[] = 'agncy-p-i';

	if ( $loop_style == 'grid' || $loop_style == 'masonry' ) {
		$classes[] = 'agncy-gc-i';
	}

	return $classes;
}

add_filter( 'brix_agncy_project_item_classes', 'agncy_project_item_classes', 10, 4 );

/**
 * Custom data attribute for the portfolio content block.
 *
 * @since 1.0.0
 * @param array $attrs An array of attributes.
 * @param object $data The data object.
 * @return array
 */
function agncy_brix_portfolio_block_attrs( $attrs, $data ) {
	if ( $data->_type != 'agncy_portfolio' ) {
		return $attrs;
	}

	if ( $data->paginate ) {
		$attrs[] = 'data-agncy-paginate=' . $data->paginate;
	}

	return $attrs;
}

add_filter( 'brix_block_attrs', 'agncy_brix_portfolio_block_attrs', 10, 2 );

/**
 * Bypass the project permalink if we're using an external URL.
 *
 * @since 1.0.0
 * @param string $post_link The regular post URL.
 * @param WP_Post $post The post object.
 * @return string
 */
function agncy_project_post_link( $post_link, $post, $leavename, $sample ) {
	if ( $post->post_type != 'agncy_project' ) {
		return $post_link;
	}

	$url = get_post_meta( $post->ID, 'url', true );

	if ( ! empty( $url ) ) {
		$post_link = $url;
	}

	return $post_link;
}

add_filter( 'post_type_link', 'agncy_project_post_link', 10, 4 );

/**
 * Custom HTML style used for preloader.
 *
 * @since 1.0.0
 * @param string $style The CSS style.
 * @return string
 */
function agncy_project_html_style( $style ) {
	if ( is_singular( 'agncy_project' ) ) {
		$project_color = agncy_get_project_color();

		$color = $project_color;

		$style = sprintf( 'color:%s;background-color:%s;',
			$color,
			$color
		);
	}

	return $style;
}

add_filter( 'agncy_html_style', 'agncy_project_html_style' );

/**
 * Return the markup that contains a list of categories to be used in the filter.
 *
 * @since 1.0.0
 * @param object $data The block data.
 * @return string
 */
function agncy_portfolio_filter_categories_list( $data ) {
	$terms_args = array(
		'taxonomy'   => 'agncy_project_category',
		'title_li'   => '',
		'include'    => array(),
		'exclude'    => array(),
		'hide_empty' => true,
		'parent'     => 0
	);

	add_filter( 'term_link', 'agncy_portfolio_filter_term_link', 10, 3 );

	$first_level_args = $terms_args;

	if ( isset( $data->data->include_taxonomy ) ) {
		$first_level_args[ 'include' ] = str_replace( 'agncy_project_category:', '', $data->data->include_taxonomy );
		$first_level_args[ 'include' ] = explode( ',', $first_level_args[ 'include' ] );
		$first_level_args[ 'orderby' ] = 'include';
	}

	if ( isset( $data->data->exclude_taxonomy ) ) {
		$first_level_args[ 'exclude' ] = str_replace( 'agncy_project_category:', '', $data->data->exclude_taxonomy );
		$first_level_args[ 'exclude' ] = explode( ',', $first_level_args[ 'exclude' ] );
	}

	$first_level_terms = get_terms( $first_level_args );

	if ( empty( $first_level_terms ) ) {
		return false;
	}

	$html = '';

	$html .= '<ul>';
		$html .= '<li>';
			$html .= sprintf( '<a class="agncy-p-f-active" href="%s" data-default>%s</a>',
				esc_url( get_permalink( get_queried_object_id() ) ),
				esc_html__( 'All', 'agncy-companion-plugin' )
			);
		$html .= '</li>';

		foreach ( $first_level_terms as $term ) {
			$html .= '<li>';
				$html .= sprintf( '<a href="%s">%s</a>',
					esc_url( get_term_link( $term->term_id ) ),
					esc_html( $term->name )
				);

				if ( ! isset( $data->data->filter_hierarchical ) || ! $data->data->filter_hierarchical ) {
					continue;
				}

				$second_level_terms = get_terms( wp_parse_args( array(
					'parent' => $term->term_id
				), $terms_args ) );

				if ( $second_level_terms ) {
					$html .= '<ul class="agncy-p-f-sl">';

						foreach ( $second_level_terms as $term ) {
							$html .= '<li>';
								$html .= sprintf( '<a href="%s">%s</a>',
									esc_url( get_term_link( $term->term_id ) ),
									esc_html( $term->name )
								);
							$html .= '</li>';
						}

					$html .= '</ul>';
				}

			$html .= '</li>';
		}
	$html .= '</ul>';

	return $html;
}

/**
 * Return the markup that contains a list of meta values to be used in the filter.
 *
 * @since 1.0.0
 * @param string $meta The meta being filtered.
 * @param object $data The block data.
 * @return string
 */
function agncy_portfolio_filter_metas_list( $meta, $data ) {
	$html = '';
	$metas = array();

	$loop_query = clone $data->_query;
	$loop_query->set_query_arg( 'posts_per_page', -1 );

	if ( $loop_query->have_posts() ) {
		while ( $loop_query->have_posts() ) {
			$loop_query->the_post();

			$post_metas = get_post_meta( get_the_ID(), $meta );

			$metas = array_merge( $metas, $post_metas );
		}

		$metas = array_unique( $metas );
	}

	if ( ! empty( $metas ) ) {
		sort( $metas );

		if ( $meta == 'year' ) {
			$metas = array_reverse( $metas );
		}

		$html .= '<ul>';

		$html .= sprintf( '<li><a class="agncy-p-f-active" href="%s" data-default>%s</a></li>',
			esc_url( remove_query_arg( $meta ) ),
			esc_html__( 'All', 'agncy-companion-plugin' )
		);

		foreach ( $metas as $_meta ) {
			$meta_url = remove_query_arg( $meta );
			$meta_url = add_query_arg( $meta, $_meta );

			$html .= sprintf( '<li><a href="%s">%s</a></li>',
				esc_url( $meta_url ),
				esc_html( $_meta )
			);
		}

		$html .= '</ul>';
	}

	return $html;
}

/**
 * Display the portfolio filter.
 *
 * @since 1.0.0
 * @param object $data The portfolio data.
 */
function agncy_portfolio_filter( $data ) {
	if ( $data->data->display_filter == 'none' ) {
		return;
	}

	switch ( $data->data->display_filter ) {
		case 'custom':
			if ( empty( $data->data->custom_filter ) ) {
				return;
			}

			$custom_filters = explode( ',', $data->data->custom_filter );
			$filter_classes = 'agncy-p-f';

			if ( count( $custom_filters ) == 1 ) {
				$filter_classes .= ' agncy-p-f-single';
			}

			printf( '<div class="%s">', esc_attr( $filter_classes ) );

				echo '<div class="agncy-p-f-d-toggle">';
					printf( '%s <span data-default-label="%s">%s</span>',
						esc_html__( 'Filtering by', 'agncy-companion-plugin' ),
						esc_attr__( 'All', 'agncy-companion-plugin' ),
						esc_html__( 'All', 'agncy-companion-plugin' )
					);
					echo '<div class="agncy-p-f-toggle-icn"></div>';
				echo '</div>';

				echo '<div class="agncy-p-f-d">';
					foreach ( $custom_filters as $filter ) {
						switch ( $filter ) {
							case 'categories':
								$categories_list = agncy_portfolio_filter_categories_list( $data );

								if ( empty( $categories_list ) ) {
									continue;
								}

								echo '<div class="agncy-p-f-w_i" data-token="project-category" data-value="">';
									printf( '<span>%s</span>',
										// esc_html__( 'Filter by:', 'agncy-companion-plugin' )
										esc_html__( 'Category', 'agncy-companion-plugin' )
									);

									print $categories_list;
								echo '</div>';

								break;
							default:
								$project_metas = agncy_projects_metas();

								if ( array_key_exists( $filter, $project_metas ) ) {
									$meta_list = agncy_portfolio_filter_metas_list( $filter, $data );

									if ( empty( $meta_list ) ) {
										continue;
									}

									printf( '<div class="agncy-p-f-w_i" data-token="%s" data-value="">', esc_attr( $filter ) );
										printf( '<span>%s</span>',
											esc_html( $project_metas[ $filter ] )
										);

										print $meta_list;
									echo '</div>';
								}

								break;
						}
					}
				echo '</div>';

			echo '</div>';

			break;
		default:
			break;
	}
}

/**
 * Adjust the term link when called in portfolio filter.
 *
 * @since 1.0.0
 * @param string $termlink The term link.
 * @param object $term The term.
 * @param string $taxonomy The taxonomy slug.
 * @return string
 */
function agncy_portfolio_filter_term_link( $termlink, $term, $taxonomy ) {
	if ( $taxonomy == 'agncy_project_category' ) {
		$termlink = remove_query_arg( 'project-category' );
		$termlink = add_query_arg( 'project-category', $term->slug );
	}

	return $termlink;
}

/**
 * Return the image size to be used in the portfolio loop.
 *
 * @since 1.0.0
 * @param $block_data object The block data.
 * @return string
 */
function agncy_portfolio_featured_media_size( $block_data = false ) {
	$featured_media_size = 'large';

	if ( $block_data && isset( $block_data->agncy_portfolio_featured_image_sizes ) ) {
		$featured_media_size =  $block_data->agncy_portfolio_featured_image_sizes;
	}

	return $featured_media_size;
}

/**
 * Get the color associated to a project.
 *
 * @since 1.0.0
 * @param integer $project_id The project ID.
 * @return string
 */
function agncy_get_project_color( $project_id = false ) {
	if ( ! $project_id ) {
		$project_id = get_the_ID();
	}

	$color = get_post_meta( $project_id, 'color', true );

	if ( empty( $color ) ) {
		$color = '#fafafa';
	}

	return $color;
}

/**
 * Get projects in a selectable format.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_projects_for_select() {
	$items = get_posts(array(
		'paged'          => 1,
		'posts_per_page' => -1,
		'post_type'      => 'agncy_project'
	));

	$options = array();
	$options[0] = '--';

	if( count( $items > 0 ) ) {
		foreach ( $items as $item ) {
			$options[ $item->ID ] = $item->post_title;
		}
	}

	return $options;
}

/**
 * Get the single project layout value.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_project_layout() {
	$project_layout = get_post_meta( get_the_ID(), 'layout', false );
	$layout = isset( $project_layout ) ? $project_layout[0] : 'a';

	return $layout;
}

/**
 * Get a subtitle for the project.
 *
 * @since 1.0.0
 * @param integer $project_id The project ID.
 * @return string
 */
function agncy_get_project_subtitle( $project_id = false ) {
	if ( ! $project_id ) {
		$project_id = get_the_ID();
	}

	$subtitle = get_post_meta( $project_id, 'subtitle', true );

	if ( ! $subtitle ) {
		return '';
	}

	$subtitle_html = '';

	switch ( $subtitle ) {
		case 'categories':
			$taxonomy = 'agncy_project_category';
			$terms = wp_get_object_terms( $project_id, $taxonomy, array( 'fields' => 'names' ) );

			$subtitle_html = implode( ', ', $terms );

			break;
		case 'metas':
			$metas = explode( ',', get_post_meta( $project_id, 'subtitle_metas', true ) );

			if ( ! empty( $metas ) ) {
				$metas_data = array();
				$project_metas_defs = agncy_projects_metas();
				$project_metas = get_post_meta( $project_id, 'meta', true );

				if ( $project_metas ) {
					foreach ( $project_metas as $meta ) {
						if ( ! isset( $metas_data[ $meta[ 'meta' ] ] ) ) {
							$metas_data[ $meta[ 'meta' ] ] = array();
						}

						$metas_data[ $meta[ 'meta' ] ][] = $meta[ 'value' ];
					}
				}

				if ( count( $metas ) === 1 ) {
					$subtitle_html = implode( ', ', $metas_data[ $metas[ 0 ] ] );
				}
				else {
					$multiple_metas_data = array();

					foreach ( $metas as $meta ) {
						if ( isset( $metas_data[ $meta ] ) ) {
							$multiple_metas_data[] = $project_metas_defs[ $meta ] . ': ' . implode( ', ', $metas_data[ $meta ] );
						}
					}

					$subtitle_html = implode( '. ', $multiple_metas_data ) . '.';
				}
			}

			break;
		case 'text':
		default:
			$subtitle_html = get_post_meta( $project_id, 'subtitle_text', true );
			break;
	}

	$subtitle_html = apply_filters( 'agncy_project_subtitle', $subtitle_html );

	return wp_kses_post( $subtitle_html );
}