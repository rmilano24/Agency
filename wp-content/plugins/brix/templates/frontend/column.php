<?php
	$size = str_replace( '/', '-', $data->size );

	$classes = array(
		'brix-section-column',
		'brix-col-' . $size,
	);

	$data_attr = array(
		'data-size=' . $data->size
	);

	$carousel_active = isset( $data->data->enable_column_carousel ) && $data->data->enable_column_carousel != 0;
	$carousel_module       = false;

	if ( $carousel_active ) {
		if ( isset( $data->data->column_carousel_items ) && ! empty( $data->data->column_carousel_items ) ) {
			$data_attr[] = 'data-carousel-module=' . $data->data->column_carousel_items;
			$carousel_module = true;
		}
	}

	$carousel_controls_active  = isset( $data->data->column_carousel_navigation_position ) && $data->data->column_carousel_navigation_position != 'hidden';
	$carousel_dots_active      = isset( $data->data->column_carousel_dots_position ) && $data->data->column_carousel_dots_position != 'hidden';

	$carousel_controls_above   = isset( $data->data->column_carousel_navigation_position ) && $data->data->column_carousel_navigation_position == 'above_carousel';
	$carousel_controls_below   = isset( $data->data->column_carousel_navigation_position ) && $data->data->column_carousel_navigation_position == 'below_carousel';
	$carousel_controls_inside  = isset( $data->data->column_carousel_navigation_position ) && $data->data->column_carousel_navigation_position == 'inside_carousel';
	$carousel_dots_above       = isset( $data->data->column_carousel_dots_position ) && $data->data->column_carousel_dots_position == 'above_carousel';
	$carousel_dots_below       = isset( $data->data->column_carousel_dots_position ) && $data->data->column_carousel_dots_position == 'below_carousel';

	$carousel_navigation_style = isset( $data->data->column_carousel_navigation_style ) && $data->data->column_carousel_navigation_style ? $data->data->column_carousel_navigation_style : '';
	$carousel_navigation_style_arrows = isset( $data->data->column_carousel_navigation_style ) && $data->data->column_carousel_navigation_style == 'arrows';

	if ( isset( $data->data->class ) ) {
		$classes = array_merge( $classes, (array) $data->data->class );
	}

	if ( isset( $data->data->attrs ) ) {
		$data_attr = array_merge( $data_attr, (array) $data->data->attrs );
	}

	if ( isset( $data->data->enable_column_carousel ) && $data->data->enable_column_carousel ) {
		if ( $carousel_controls_active ) {
			$carousel_controls = '<div class="brix-column-carousel-controls-wrapper">';
				$prev_svg = '';
				$next_svg = '';

				if( $carousel_navigation_style_arrows ) {
					$prev_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 54 54" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon fill="#000000" points="53.44 23.44 53.44 30 12.81 30 31.41 48.75 26.72 53.44 0 26.72 26.72 0 31.41 4.69 12.81 23.44"/></g></svg>';
					$next_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 54 54" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon fill="#000000" points="26.72 0 53.44 26.72 26.72 53.44 22.03 48.75 40.63 30 0 30 0 23.44 40.63 23.44 22.03 4.69"/></g></svg>';
				}

				$carousel_controls .= '<a class="brix-column-carousel-prev-arrow" href="#">' . $prev_svg . '<span>' . esc_html( __( 'Previous', 'brix' ) ) . '</span></a>';
				$carousel_controls .= '<a class="brix-column-carousel-next-arrow" href="#">' . $next_svg . '<span>' . esc_html( __( 'Next', 'brix' ) ) . '</span></a>';
			$carousel_controls .= '</div>';
		}

		if ( $carousel_controls_inside ) {
			$classes[] = 'brix-column-carousel-inside-controls';
		}

		if ( $carousel_navigation_style ) {
			$classes[] = 'brix-column-carousel-navigation-style-' . $carousel_navigation_style;
		}

		if ( $carousel_dots_active ) {
			$classes[] = 'brix-column-carousel-dots-position-' . $data->data->column_carousel_dots_position;
		} else {
			$classes[] = 'brix-column-carousel-dots-disabled';
		}

		if ( $carousel_controls_active ) {
			$classes[] = 'brix-column-carousel-controls-position-' . $data->data->column_carousel_navigation_position;
		}
	}

	if ( isset( $data->data->stretch ) && $data->data->stretch == '1' ) {
		$classes[] = 'brix-section-column-stretch';

		if ( isset( $data->data->stretch_column ) && $data->data->stretch_column == '1' ) {
			$classes[] = 'brix-section-column-column-stretch';
		}
	}

	$classes = apply_filters( 'brix_section_column_classes', $classes, $data->data );
	$data_attr = apply_filters( 'brix_section_column_data_attrs', $data_attr, $data->data );

	$column_tag = 'div';

	if ( isset( $data->data->section ) && ! empty( $data->data->section ) ) {
		$column_tag = 'section';
	}
?>

<<?php echo esc_html( $column_tag ); ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo esc_attr( implode( ' ', $data_attr ) ); ?>>

	<?php do_action( 'brix_column_before_render', $data->data ); ?>

	<?php
		if ( isset( $data->data->background ) ) {
			echo brix_background( $data->data, 'column' );
		}
	?>

	<div class="brix-section-column-spacing-wrapper">
		<?php
			$data_attrs = '';

			if ( $carousel_active ) {
				$carousel_options = array(
					'cellAlign'       => 'left',
					'cellSelector'    => '.brix-section-column-block',
					'prevNextButtons' => false,
					'watchCSS'        => true
				);

				if ( $carousel_module ) {
					if ( $data->data->column_carousel_items > 1 ) {
						$carousel_options['groupCells'] = $carousel_module;
					}
					else {
						$carousel_options['cellAlign'] = 'center';
					}
				}

				if ( isset( $data->data->column_carousel_items_initial_index ) ) {
					$carousel_options[ 'initialIndex' ] = absint( $data->data->column_carousel_items_initial_index );
				}

				$carousel_options = apply_filters( 'brix_section_column_carousel_options', $carousel_options, $data->data );
				$carousel_options = json_encode( $carousel_options );

				$data_attrs .= 'data-carousel=' . esc_attr( $carousel_options );
			}
		?>

		<?php
			if ( $carousel_active ) {
				if ( $carousel_controls_active && ( $carousel_controls_above || $carousel_controls_inside ) ) {
					echo $carousel_controls;
				}
			}
		?>

			<div class="brix-section-column-inner-wrapper" <?php echo $data_attrs; ?>><?php
					if ( isset( $data->blocks ) && ! empty( $data->blocks ) ) {
						foreach ( $data->blocks as $block ) {
							brix_template( BRIX_FOLDER . '/templates/frontend/block', array(
								'data' => $block
							) );
						}
					}
				?></div>

		<?php
			if ( $carousel_active ) {
				if ( $carousel_controls_active && $carousel_controls_below ) {
					echo $carousel_controls;
				}
			}
		?>

	</div>

	<?php do_action( 'brix_column_after_render', $data->data ); ?>

</<?php echo esc_html( $column_tag ); ?>>