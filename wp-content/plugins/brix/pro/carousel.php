<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Additional options for the carousel.
 *
 * @param  array $fields The carousel options fields
 * @return array
 */
function brix_column_carousel_pro_options( $fields ) {
	$module_index = brix_array_find( $fields, 'handle:column_carousel_items_width', null, 'ids' );

	$list = new Brix_List( $fields );

	$sizes = array(
		1 => __( '1 element', 'brix' ),
		2 => __( '2 elements', 'brix' ),
		3 => __( '3 elements', 'brix' ),
		4 => __( '4 elements', 'brix' ),
		5 => __( '5 elements', 'brix' ),
		6 => __( '6 elements', 'brix' ),
	);

	$responsive_sizes = $sizes;
	$responsive_sizes[0] = __( 'Disable', 'brix' );

	$media_queries = array();

	foreach ( brix_breakpoints() as $breakpoint_key => $breakpoint_value ) {
		if ( $breakpoint_key === 'desktop' ) {
			continue;
		}

		$media_queries[$breakpoint_key] = $breakpoint_value['label'];
	}

	$responsive_help = sprintf( __( 'The number of items shown at a time, according to a specific media query. You can define custom media queries <a href="%s">here</a>.', 'brix' ),
		esc_attr( admin_url( 'admin.php?page=brix' ) )
	);

	$pro_options = array(
		array(
			'handle' => 'column_carousel_responsive',
			'label'  => __( 'Responsive', 'brix' ),
			'help' => $responsive_help,
			'type'   => 'bundle',
			'fields' => array(
				array(
					'handle' => 'media_query',
					'label'  => array(
						'text' => __( 'Media query', 'brix' ),
						'type' => 'hidden'
					),
					'type'   => 'select',
					'size' => 'large',
					'config' => array(
						'data' => $media_queries
					),
				),
				array(
					'handle' => 'items',
					'label'  => array(
						'text' => __( 'Items', 'brix' ),
						'type' => 'hidden'
					),
					'type'   => 'select',
					'size' => 'large',
					'config' => array(
						'data' => $responsive_sizes
					),
				),
			),
			'config' => array(
				'visible' => array( 'enable_column_carousel' => '1' ),
				'style' => 'grid',
			),
			'repeatable' => true
		),
		array(
			'handle' => 'enable_column_carousel_infinite_loop',
			'label'  => __( 'Infinite loop', 'brix' ),
			'type'   => 'checkbox',
			'help'   => __( 'If checked, at the end of items, wrap-around to the other end for infinite scrolling.', 'brix' ),
			'config' => array(
				'visible' => array( 'enable_column_carousel' => '1' ),
			)
		),
		array(
			'handle' => 'enable_column_carousel_autoplay',
			'label'  => __( 'Autoplay', 'brix' ),
			'type'   => 'number',
			'help'   => __( 'Expressed in milliseconds. If not empty, the carousel will autoplay and advance at the specified rate.', 'brix' ),
			'config' => array(
				'visible' => array( 'enable_column_carousel' => '1' ),
			)
		),
		array(
			'handle' => 'enable_column_carousel_fluid_height',
			'label'  => __( 'Fluid height', 'brix' ),
			'help' => __( 'By enabling fluid height, the carousel will resize itself according to the height of the items.', 'brix' ),
			'type'   => 'checkbox',
			'config' => array(
				'visible' => array( 'enable_column_carousel' => '1' ),
			)
		),
	);

	foreach ( $pro_options as $index => $option ) {
		$list->insert_at(
			$option,
			$module_index[0] + $index + 1
		);
	}

	$fields = $list->get_all();

	return $fields;
}

add_filter( 'brix_column_carousel_fields', 'brix_column_carousel_pro_options', 10, 1 );

/**
 * Return a list of data attributes depending on pro features.
 *
 * @since 1.1.3
 * @param array $data_attr An array of data-attributes.
 * @param stdClass $data The column data object.
 * @return array
 */
function brix_column_pro_data_attributes( $data_attr, $data ) {
	$carousel_active = isset( $data->enable_column_carousel ) && $data->enable_column_carousel != 0;

	if ( ! $carousel_active ) {
		return $data_attr;
	}

	if ( isset( $data->enable_column_carousel_infinite_loop ) && ! empty( $data->enable_column_carousel_infinite_loop ) ) {
		$data_attr[] = 'data-carousel-infinite-loop=true';
	}

	if ( isset( $data->enable_column_carousel_autoplay ) && ! empty( $data->enable_column_carousel_autoplay ) ) {
		$data_attr[] = 'data-carousel-autoplay=' . $data->enable_column_carousel_autoplay;
	}

	if ( isset( $data->enable_column_carousel_fluid_height ) && ! empty( $data->enable_column_carousel_fluid_height ) ) {
		$data_attr[] = 'data-carousel-fluid-height=' . $data->enable_column_carousel_fluid_height;
	}

	return $data_attr;
}

add_filter( 'brix_section_column_data_attrs', 'brix_column_pro_data_attributes', 10, 2 );

/**
 * Return a list of carousel options depending on pro features.
 *
 * @since 1.1.3
 * @param array $options An array of carousel options.
 * @param stdClass $data The column data object.
 * @return array
 */
function brix_column_pro_carousel_options( $options, $data ) {
	$carousel_active = isset( $data->enable_column_carousel ) && $data->enable_column_carousel != 0;

	if ( ! $carousel_active ) {
		return $options;
	}

	if ( isset( $data->enable_column_carousel_infinite_loop ) && ! empty( $data->enable_column_carousel_infinite_loop ) ) {
		$options['wrapAround'] = true;
	}

	if ( isset( $data->enable_column_carousel_autoplay ) && ! empty( $data->enable_column_carousel_autoplay ) ) {
		$options['autoPlay'] = absint( $data->enable_column_carousel_autoplay );
	}

	if ( isset( $data->enable_column_carousel_fluid_height ) && ! empty( $data->enable_column_carousel_fluid_height ) ) {
		$options['adaptiveHeight'] = true;
	}

	return $options;
}

add_filter( 'brix_section_column_carousel_options', 'brix_column_pro_carousel_options', 10, 2 );

/**
 * Process the builder column on frontend adding the required inline styles
 * depending on pro functionality.
 *
 * @since 1.1.3
 * @param stdClass $column The column object.
 * @param integer $count The column count index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_process_frontend_column_pro( $column, $count, $selector_prefix = false ) {
	/* Include the carousel script if the column is in carousel mode */
	if ( isset( $column->data->enable_column_carousel ) && $column->data->enable_column_carousel == '1' ) {
		if ( isset( $column->data->column_carousel_responsive ) && ! empty( $column->data->column_carousel_responsive ) ) {
			$breakpoints = brix_breakpoints();
			$column_selector = sprintf( '.brix-section-column-%s', $count );

			if ( $selector_prefix !== false ) {
				$column_selector = $selector_prefix . ' ' . $column_selector;
			}

			$carousel_responsive_style = '';

			foreach ( $column->data->column_carousel_responsive as $step ) {
				if ( isset( $breakpoints[$step->media_query] ) ) {
					$media_query = $breakpoints[$step->media_query]['media_query'];
					$items = absint( $step->items );

					if ( $items > 0 ) {
						$width = 100 / $items;

						$carousel_responsive_style .= $media_query . '{';
							$carousel_responsive_style .= $column_selector . '.brix-section-column-carousel .brix-section-column-block{width:' . $width . '%}';
						$carousel_responsive_style .= '}';
					}
					else {
						$carousel_responsive_style .= $media_query . '{';
							$carousel_responsive_style .= $column_selector . ' .brix-section-column-inner-wrapper:after{content: "";}';
							$carousel_responsive_style .= $column_selector . '.brix-section-column-carousel .brix-section-column-block{width:100%;}';
						$carousel_responsive_style .= '}';
					}
				}
			}

			brix_fw()->frontend()->add_inline_style( $carousel_responsive_style );
		}
	}

	return $column;
}

add_filter( 'brix_process_frontend_column', 'brix_process_frontend_column_pro', 10, 3 );