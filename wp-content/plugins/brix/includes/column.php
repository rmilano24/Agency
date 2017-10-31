<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Render the column modal window.
 *
 * @since 1.0.0
 */
function brix_column_modal_load() {
	$key = 'brix_column';
	$data = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? $_POST['data'] : array();

	$appearance_fields = apply_filters( 'brix_column_appearance_fields', array() );

	$background_fields = brix_get_background( 'column' );

	$spacing_fields = array(
		array(
			'handle' => 'spacing_divider',
			'text' => __( 'Spacing', 'brix' ),
			'type' => 'divider',
			'config' => array(
				'style' => 'in_page',
			)
		),
		array(
			'handle' => 'spacing',
			'label'  => array(
				'text' => __( 'Spacing', 'brix' ),
				'type' => 'hidden'
			),
			'type'   => 'brix_spacing',
		),
		array(
			'handle' => 'stretch',
			'label'  => __( 'Stretch background', 'brix' ),
			'type'   => 'checkbox',
			'help'   => __( 'If the column is the first or last of its row, and the containing section width is stretched, attempt to stretch its background boundaries to the edges.', 'brix' ),
			'config' => array(
				'controller' => true,
				'style' => array( 'switch', 'small' )
			)
		),
		array(
			'handle' => 'stretch_column',
			'label'  => __( 'Stretch column', 'brix' ),
			'type'   => 'checkbox',
			'help'   => __( 'If the column is the first or last of its row, and the containing section width is stretched, attempt to stretch the column boundaries to the edges.', 'brix' ),
			'config' => array(
				'style' => array( 'switch', 'small' ),
				'visible' => array( 'stretch' => '1' )
			)
		)
	);

	$sizes = array(
		1 => __( '1 element', 'brix' ),
		2 => __( '2 elements', 'brix' ),
		3 => __( '3 elements', 'brix' ),
		4 => __( '4 elements', 'brix' ),
		5 => __( '5 elements', 'brix' ),
		6 => __( '6 elements', 'brix' ),
	);

	$widths = array(
		1 => __( 'Full width', 'brix' ),
		2 => __( '50%', 'brix' ),
		3 => __( '33.3%', 'brix' ),
		4 => __( '25%', 'brix' ),
		5 => __( '20%', 'brix' ),
	);

	$carousel_fields = array(
		array(
			'handle' => 'enable_column_carousel',
			'label'  => __( 'Show as carousel', 'brix' ),
			'help' => __( 'Blocks contained in this column will become the elements of the carousel.', 'brix' ),
			'type'   => 'checkbox',
			'config' => array(
				'controller' => true
			)
		),
		array(
			'handle' => 'column_carousel_items',
			'label'  => __( 'Module', 'brix' ),
			'help' => __( 'The number of items shown at a time.', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'controller' => true,
				'visible' => array( 'enable_column_carousel' => '1' ),
				'data' => $sizes
			),
		),
		array(
			'handle' => 'column_carousel_items_initial_index',
			'label'  => __( 'Initial index', 'brix' ),
			'help' => __( 'Zero-based index of the initial selected item.', 'brix' ),
			'type'   => 'number',
			'config' => array(
				'min' => 0
			)
		),
		array(
			'handle' => 'column_carousel_items_width',
			'label'  => __( 'Elements width', 'brix' ),
			'help' => __( 'The width of the elements shown.', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'visible' => array( 'column_carousel_items' => '1' ),
				'data' => $widths
			),
		),
		array(
			'type' => 'divider',
			'text' => __( 'Navigation arrows', 'brix' ),
			'config' => array(
				'style' => 'in_page',
				'visible' => array( 'enable_column_carousel' => '1' ),
			)
		),
		array(
			'handle' => 'column_carousel_navigation_position',
			'label'  => __( 'Display', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'controller' => true,
				'visible' => array( 'enable_column_carousel' => '1' ),
				'data' => array(
					'hidden'          => __( 'Do not display', 'brix' ),
					'below_carousel'  => __( 'Below the carousel', 'brix' ),
					'above_carousel'  => __( 'Above the carousel', 'brix' ),
					'inside_carousel' => __( 'Inside carousel', 'brix' ),
				)
			),
			'default' => 'hidden'
		),
		array(
			'type' => 'divider',
			'text' => __( 'Bullets navigation', 'brix' ),
			'config' => array(
				'style' => 'in_page',
				'visible' => array( 'enable_column_carousel' => '1' ),
			)
		),
		array(
			'handle' => 'column_carousel_dots_position',
			'label'  => __( 'Position', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'controller' => true,
				'visible' => array( 'enable_column_carousel' => '1' ),
				'data' => array(
					'hidden'          => __( 'Do not display', 'brix' ),
					'below_carousel'  => __( 'Below the carousel', 'brix' ),
					'above_carousel'  => __( 'Above the carousel', 'brix' ),
				),
			),
			'default' => 'hidden'
		),
	);

	$carousel_fields = apply_filters( 'brix_column_carousel_fields', $carousel_fields );

	$fields = array(
		array(
			'type' => 'group',
			'handle' => 'column_appearance',
			'label' => __( 'Appearance', 'brix' ),
			'fields' => $appearance_fields
		),
		array(
			'type' => 'group',
			'handle' => 'column_background',
			'label' => __( 'Background', 'brix' ),
			'fields' => $background_fields
		),
		array(
			'type' => 'group',
			'handle' => 'column_spacing',
			'label' => __( 'Spacing', 'brix' ),
			'fields' => $spacing_fields
		),
		array(
			'type' => 'group',
			'handle' => 'column_carousel',
			'label' => __( 'Carousel', 'brix' ),
			'fields' => $carousel_fields
		),
	);

	$fields = apply_filters( 'brix_column', $fields );

	$m = new Brix_Modal( $key, $fields, $data, array(
		'title' => __( 'Edit column', 'brix' )
	) );

	$m->render();

	die();
}

add_action( 'wp_ajax_brix_column_modal_load', 'brix_column_modal_load' );

/**
 * Process the builder column on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $column The column object.
 * @param integer $count The column count index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_process_frontend_column( $column, $count, $selector_prefix = false ) {
	$column_selector = sprintf( '.brix-section-column-%s', $count );
	$column_style    = '';

	/* Column data. */
	$column->data = isset( $column->data ) && ! empty( $column->data ) ? $column->data : new stdClass();
	$column->data->class = isset( $column->data->class ) && ! empty( $column->data->class ) ? (array) $column->data->class : array();

	if ( $selector_prefix !== false ) {
		$column_selector = $selector_prefix . ' ' . $column_selector;
	}

	/* Spacing CSS rules. */
	if ( isset( $column->data->spacing ) ) {
		$column_spacing_style = brix_spacing_style_output( $column->data->spacing, $column_selector . ' .brix-section-column-spacing-wrapper' );
		$column_spacing_style .= brix_spacing_style_output( $column->data->spacing, $column_selector . ' .brix-column-background-wrapper' );

		brix_fw()->frontend()->add_inline_style( $column_spacing_style );
	}

	/* Background. */
	if ( isset( $column->data->background ) && ! empty( $column->data->background ) ) {
		$column->data->class[] = 'brix-section-column-w-background';

		$column_background_selector = $column_selector . '.brix-inview .brix-column-background-wrapper';
		$column_background_style = brix_background_style( $column->data, $column_background_selector );
		brix_fw()->frontend()->add_inline_style( $column_background_style );

		$column_background_selector = $column_selector . ' .brix-column-background-wrapper .brix-background-overlay';
		$column_background_overlay_style = brix_background_overlay_style( $column->data, $column_background_selector );
		brix_fw()->frontend()->add_inline_style( $column_background_overlay_style );
	}

	/* Responsive.*/
	if ( isset( $column->data->_responsive ) ) {
		$column_responsive_style = '';
		$breakpoints = brix_breakpoints();

		foreach ( $column->data->_responsive as $breakpoint_key => $data ) {
			if ( isset( $breakpoints[$breakpoint_key] ) && isset( $breakpoints[$breakpoint_key]['media_query'] ) && ! empty( $breakpoints[$breakpoint_key]['media_query'] ) ) {
				if ( $data->size === 'hidden' ) {
					$column_responsive_style .= $breakpoints[$breakpoint_key]['media_query'] . '{';
						$column_responsive_style .= $column_selector . '{display:none}';
					$column_responsive_style .= '}';
				}
				else {
					$frac = explode( '/', $data->size );

					if ( isset( $frac[0] ) && isset( $frac[1] ) ) {
						$num = absint( $frac[0] );
						$den = absint( $frac[1] );

						$width = $num * 100 / $den;

						if ( ! empty( $width ) ) {
							$column_responsive_style .= $breakpoints[$breakpoint_key]['media_query'] . '{';
								$_crs = 'width:' . $width . '%;'; /* display:block; */

								if ( $width == 100 ) {
									$_crs .= 'min-height:0;';
								}

								$column_responsive_style .= $column_selector . '{' . $_crs . '}';
							$column_responsive_style .= '}';
						}
					}
				}
			}
		}

		brix_fw()->frontend()->add_inline_style( $column_responsive_style );
	}

	/* Custom column CSS classes. */
	$column->data->class[] = 'brix-section-column-' . $count;

	/* Check for vertical align support. */
	if ( isset( $column->data->_vertical_alignment ) ) {
		$column->data->class[] = 'brix-section-column-vertical-alignment-' . $column->data->_vertical_alignment;
	}

	/* Include the carousel script if the column is in carousel mode */
	if ( isset( $column->data->enable_column_carousel ) && $column->data->enable_column_carousel == '1' ) {
		brix_fw()->frontend()->add_script( 'brix-carousel', BRIX_URI . 'assets/frontend/js/core/brix_carousel.js', array( 'brix-flickity' ), '1.0' );

		/* Add a column class for the carousel functionality */
		$column->data->class[] = 'brix-section-column-carousel';

		if ( isset( $column->data->column_carousel_items ) ) {
			$column->data->class[] = 'brix-section-column-carousel-item-' . $column->data->column_carousel_items;

			if ( $column->data->column_carousel_items == 1 && isset( $column->data->column_carousel_items_width ) && $column->data->column_carousel_items_width != '1' ) {
				$column->data->class[] = 'brix-section-column-carousel-item-width-' . $column->data->column_carousel_items_width;
			}
		}
	}

	/* Attaching the custom column style. */
	if ( $column_style != '' ) {
		$column_style_css = $column_selector . '{' . $column_style . '}';

		brix_fw()->frontend()->add_inline_style( $column_style_css );
	}

	/* Check if the column is empty. */
	$column_empty = ! isset( $column->blocks ) || empty( $column->blocks );

	if ( $column_empty ) {
		$column->data->class[] = 'brix-empty';
	}

	$column->data->attrs[] = 'data-count=' . $count;

	return $column;
}

add_filter( 'brix_process_frontend_column', 'brix_process_frontend_column', 10, 3 );

/**
 * Add the carousel navigation and dots styles
 *
 * @param  array $fields The carousel options fields
 * @return array
 */
function brix_column_carousel_styles( $fields ) {
	$navigation_style = brix_array_find( $fields, 'handle:column_carousel_navigation_position', null, 'ids' );

	$list = new Brix_List( $fields );

	$list->insert_at(
		array(
			'handle' => 'column_carousel_navigation_style',
			'label'  => __( 'Style', 'brix' ),
			'type'   => 'select',
			'config' => array(
				'data' => array(
					'arrows'  => __( 'Arrows', 'brix' ),
					'no-style' => __( 'No style', 'brix' ),
				),
				'visible' => array( 'column_carousel_navigation_position' => 'below_carousel,above_carousel,inside_carousel' ),
			)
		),
		$navigation_style[0] + 1
	);

	$fields = $list->get_all();

	return $fields;
}

add_filter( 'brix_column_carousel_fields', 'brix_column_carousel_styles', 10, 1 );