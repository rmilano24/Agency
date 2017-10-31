<?php

agncy_portfolio_filter( $data );

echo '<div class="brix-agncy_portfolio-block-loop-wrapper">';

if ( $data->_query->have_posts() ) {
	while ( $data->_query->have_posts() ) {
		$data->_query->the_post();

		$path = apply_filters( 'brix_agncy_portfolio_builder_block_post_template', AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/portfolio/templates/item', $data->data );

		brix_template( $path, array(
			'data'                                             => $data->data,
			'brix_agncy_portfolio_builder_block_post_template' => true,
			'index'                                            => $data->_query->current_post
		) );
	}
}
else {
	printf( '<div class="brix-agncy-portfolio-no-results"><p>%s</p></div>',
		esc_html__( 'No projects found.', 'agncy-companion-plugin' )
	);
}

echo '</div>';

wp_reset_postdata();

if ( ! empty( $data->data->paginate ) ) {
	$max_num_pages = $data->_query->_query->max_num_pages;
	$paged = $data->_query->get_current_page();
	$show_next = $paged < $max_num_pages;
	$show_prev = $paged > 1;

	printf( '<div class="brix-agncy_portfolio-block-pagination-wrapper">' );

		if ( $data->data->paginate == 'ajax_append' ) {
			if ( $show_next ) {
				printf( '<a class="brix-block-load-more" href="%s" data-load-more>%s</a>',
					esc_url( get_pagenum_link( $paged + 1 ) ),
					esc_html( __( 'Load more', 'agncy-companion-plugin' ) )
				);
			}
		}
		elseif ( $data->data->paginate == 'ajax_reload' ) {
			if ( isset( $_POST['brix_agncy_portfolio_ajax_pagination'] ) || $paged == 1 ) {
				if ( ! isset( $data->data->paginate_type ) || $data->data->paginate_type == 'prevnext' ) {
					if ( $show_prev ) {
						echo '<div class="brix-block-prev-link">';
							previous_posts_link( __( 'Previous', 'agncy-companion-plugin' ), $max_num_pages );
						echo '</div>';
					}

					if ( $show_next ) {
						echo '<div class="brix-block-next-link">';
							next_posts_link( __( 'Next', 'agncy-companion-plugin' ), $max_num_pages );
						echo '</div>';
					}
				}
				elseif ( isset( $data->data->paginate_type ) && $data->data->paginate_type == 'numeric' && function_exists( 'agncy_pagination' ) ) {
					agncy_pagination( array(
						'query' => $data->_query,
						'prev' => '',
						'next' => ''
					) );
				}
			}
		}
		else {
			if ( ! isset( $data->data->paginate_type ) || $data->data->paginate_type == 'prevnext' ) {
				if ( $show_prev ) {
					echo '<div class="brix-block-prev-link">';
						previous_posts_link( __( 'Previous', 'agncy-companion-plugin' ), $max_num_pages );
					echo '</div>';
				}

				if ( $show_next ) {
					echo '<div class="brix-block-next-link">';
						next_posts_link( __( 'Next', 'agncy-companion-plugin' ), $max_num_pages );
					echo '</div>';
				}
			}
			elseif ( isset( $data->data->paginate_type ) && $data->data->paginate_type == 'numeric' && function_exists( 'agncy_pagination' ) ) {
				agncy_pagination( array(
					'query' => $data->_query,
					'prev' => '',
					'next' => ''
				) );
			}
		}

	echo '</div>';
}