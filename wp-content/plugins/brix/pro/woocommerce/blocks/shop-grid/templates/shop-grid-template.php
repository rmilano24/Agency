<?php

if ( $data->_query->have_posts() ) {
	$columns = isset( $data->data->columns ) ? absint( $data->data->columns ) : 4; // TODO: gestire clearfix a mano

	printf( '<div class="woocommerce columns-%s">', esc_attr( $columns ) );

		global $wp_query;

		$old_wp_query = $wp_query;
		$wp_query = $data->_query->get_original_query();

		do_action( 'woocommerce_before_shop_loop' );

		$wp_query = $old_wp_query;

		printf( '<div class="%s" %s><ul class="products">',
			esc_attr( implode( ' ', apply_filters( 'brix_woocommerce_shop_grid_loop_wrapper_class', array( 'brix-woo-shop-grid-block-loop-wrapper' ) ) ) ),
			implode( ' ', apply_filters( 'brix_woocommerce_shop_grid_loop_wrapper_data_attrs', array(), $data->data ) )
		);

			while ( $data->_query->have_posts() ) {
				$data->_query->the_post();

				$path = apply_filters( 'brix_woocommerce_shop_grid_builder_block_item_template', BRIX_FOLDER . 'pro/woocommerce/blocks/shop-grid/templates/product-item', $data->data );

				brix_template( $path, array(
					'data' => $data->data,
					'brix_woocommerce_shop_grid_builder_block_item_template' => true,
					'query' => $data->_query
				) );
			}

			do_action( 'brix_woocommerce_shop_grid_after_loop', $data );

		echo '</ul></div>';

	echo '</div>';
}

wp_reset_postdata();

$show_pagination = apply_filters( 'brix_woocommerce_shop_grid_builder_block_show_pagination', ! empty( $data->data->paginate ), $data->data );

if ( $show_pagination ) {
	$max_num_pages = $data->_query->_query->max_num_pages;
	$paged = $data->_query->get_current_page();
	$show_next = $paged < $max_num_pages;
	$show_prev = $paged > 1;

	printf( '<div class="brix-woo-shop-grid-block-pagination-wrapper">' );

		if ( $data->data->paginate == 'ajax_append' ) {
			if ( $show_next ) {
				printf( '<a class="brix-block-load-more" href="%s" data-load-more>%s</a>',
					esc_url( get_pagenum_link( $paged + 1 ) ),
					esc_html( __( 'Load more', 'brix' ) )
				);
			}
		}
		else {
			ob_start();

			if ( $show_prev ) {
				echo '<div class="brix-block-prev-link">';
					previous_posts_link( __( 'Previous', 'brix' ), $max_num_pages );
				echo '</div>';
			}

			if ( $show_next ) {
				echo '<div class="brix-block-next-link">';
					next_posts_link( __( 'Next', 'brix' ), $max_num_pages );
				echo '</div>';
			}

			$pagination = ob_get_contents();
			ob_end_clean();

			$pagination = apply_filters( 'brix_woocommerce_shop_grid_pagination_template', $pagination, $data->_query );

			if ( $data->data->paginate == 'ajax_reload' ) {
				if ( isset( $_POST['brix_woo-shop-grid_ajax_pagination'] ) || $paged == 1 ) {
					echo $pagination;
				}
			}
			else {
				echo $pagination;
			}
		}

	echo '</div>';
}