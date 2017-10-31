<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Get the available combinations for subsections.
 *
 * @since 1.0.0
 * @return array
 */
function brix_section_layouts() {
	$layouts = array(
		'1/1'           => BRIX_ADMIN_ASSETS_URI . 'css/i/sect_1-1.svg',
		'1/3s 2/3'      => BRIX_ADMIN_ASSETS_URI . 'css/i/1-3s_2-3.svg',
		'2/3 1/3s'      => BRIX_ADMIN_ASSETS_URI . 'css/i/2-3_1-3s.svg',
	);

	$layouts = apply_filters( 'brix_section_layouts', $layouts );

	return $layouts;
}

/**
 * Render the section modal window.
 *
 * @since 1.0.0
 */
function brix_section_modal_load() {
	$key = 'brix_section';
	$data = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? $_POST['data'] : array();

	$appearance_fields = array(
		array(
			'handle' => 'section_width',
			'type' => 'radio',
			'label'  => __( 'Width', 'brix' ),
			'help' => __( 'Define how much should be the section width related to the page content.', 'brix' ),
			'config' => array(
				'style' => 'graphic',
				'data' => array(
					'boxed'             => BRIX_ADMIN_ASSETS_URI . 'css/i/boxed.svg',
					'extended-boxed'    => BRIX_ADMIN_ASSETS_URI . 'css/i/stretched.svg',
					'extended-extended' => BRIX_ADMIN_ASSETS_URI . 'css/i/full_stretched.svg',
				)
			)
		),
		array(
			'handle' => 'section_layout',
			'type' => 'radio',
			'label'  => __( 'Content layout', 'brix' ),
			'help' => __( 'Define the distinct content areas that compose the section.', 'brix' ),
			'config' => array(
				'style' => 'graphic',
				'data' =>brix_section_layouts()
			)
		),
		array(
			'handle' => '_full_height',
			'type' => 'checkbox',
			'label'  => __( '100% viewport height', 'brix' ),
			'help' => __( 'If active, the section minimum height is equal to the browser viewport height.', 'brix' ),
			'config' => array(
				'style' => 'switch',
				'controller' => true
			)
		),
			array(
				'handle' => '_full_height_rows_alignment',
				'type' => 'radio',
				'label'  => __( 'Rows alignment', 'brix' ),
				'help' => __( '', 'brix' ),
				'config' => array(
					'visible' => array( '_full_height' => '1' ),
					'data' => array(
						'rows-top' => __( 'Top', 'brix' ),
						'rows-middle' => __( 'Middle', 'brix' ),
						'rows-bottom' => __( 'Bottom', 'brix' ),
					)
				)
			)
	);

	$appearance_fields = apply_filters( 'brix_section_appearance_fields', $appearance_fields );

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
		)
	);

	$background_fields = brix_get_background( 'section' );

	$fields = array(
		array(
			'type' => 'group',
			'handle' => 'section_appearance',
			'label' => __( 'Appearance', 'brix' ),
			'fields' => $appearance_fields
		),
		array(
			'type' => 'group',
			'handle' => 'section_background',
			'label' => __( 'Background', 'brix' ),
			'fields' => $background_fields
		),
		array(
			'type' => 'group',
			'handle' => 'section_spacing',
			'label' => __( 'Spacing', 'brix' ),
			'fields' => $spacing_fields
		),
	);

	$fields = apply_filters( 'brix_section', $fields );

	$m = new Brix_Modal( $key, $fields, $data, array(
		'title' => __( 'Edit section', 'brix' )
	) );

	$m->render();

	die();
}

add_action( 'wp_ajax_brix_section_modal_load', 'brix_section_modal_load' );

/**
 * Process the builder section on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $section The section object.
 * @param integer $index The section index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_process_frontend_section( $section, $index, $selector_prefix = false ) {
	$section_selector = sprintf( '.brix-section-%s', $index );
	$section_style    = '';
	$section_width    = isset( $section->data->data->section_width ) ? $section->data->data->section_width : 'boxed';

	if ( $selector_prefix !== false ) {
		$section_selector = $selector_prefix . ' ' . $section_selector;
	}

	/* Section setup. */
	$section->data = isset( $section->data ) && ! empty( $section->data ) ? $section->data : new stdClass();

	if ( empty( $section->data->data ) ) {
		$section->data->data = new stdClass();
	}

	$section->data->data->class = isset( $section->data->data->class ) && ! empty( $section->data->data->class ) ? (array) $section->data->data->class : array();
	$section->data->data->attrs = isset( $section->data->data->attrs ) && ! empty( $section->data->data->attrs ) ? (array) $section->data->data->attrs : array();
	$section->data->data->id = isset( $section->data->data->id ) && ! empty( $section->data->data->id ) ? $section->data->data->id : '';

	/* Section width rules. */
	if ( isset( $section_width ) ) {
		$section_width_style = '';
		$container_width = brix_get_container();

		if ( $container_width !== false && $container_width !== '' ) {
			$container_width = str_replace( ',', '.', $container_width );

			$container_width_val    = floatval( $container_width );
			$container_width_unit   = str_replace( $container_width_val, '', $container_width );

			if ( ! $container_width_unit ) {
				$container_width_unit = 'px';
			}

			if ( $section_width == 'boxed' ) {
				$section_width_style .= $section_selector . '{max-width:' . $container_width_val . $container_width_unit . '}';
			}

			if ( $section_width == 'extended-boxed' ) {
				$section_width_style .= $section_selector . ' .brix-section-inner-wrapper{max-width:' . $container_width_val . $container_width_unit . '}';
			}

			brix_fw()->frontend()->add_inline_style( $section_width_style );
		}
	}

	/* Spacing CSS rules. */
	if ( isset( $section->data->data->spacing ) ) {
		$section_spacing_style = brix_spacing_style_output( $section->data->data->spacing, $section_selector );

		brix_fw()->frontend()->add_inline_style( $section_spacing_style );
	}

	/* Background. */
	if ( isset( $section->data->data->background ) && ! empty( $section->data->data->background ) ) {
		$section->data->data->class[] = 'brix-section-w-background';

		$section_background_selector = $section_selector . '.brix-inview .brix-section-background-wrapper';
		$section_background_style = brix_background_style( $section->data->data, $section_background_selector );
		brix_fw()->frontend()->add_inline_style( $section_background_style );

		$section_background_overlay_selector = $section_selector . ' .brix-section-background-wrapper .brix-background-overlay';
		$section_background_overlay_style = brix_background_overlay_style( $section->data->data, $section_background_overlay_selector );
		brix_fw()->frontend()->add_inline_style( $section_background_overlay_style );
	}

	/* Attaching the custom section style. */
	if ( $section_style != '' ) {
		$section_style_css = $section_selector . '{' . $section_style . '}';

		brix_fw()->frontend()->add_inline_style( $section_style_css );
	}

	/* Custom section CSS classes. */
	$section->data->data->class[] = 'brix-section-' . $index;

	/* Check if the section is empty. */
	$section_empty = true;

	if ( isset( $section->data ) && isset( $section->data->layout ) ) {
		foreach ( $section->data->layout as $layout ) {
			if ( isset( $layout->rows ) ) {
				foreach ( $layout->rows as $row ) {
					if ( isset( $row->columns ) && ! empty( $row->columns ) ) {
						$section_empty = false;
						break;
					}

					if ( ! $section_empty ) {
						break;
					}
				}
			}

			if ( ! $section_empty ) {
				break;
			}
		}
	}

	if ( $section_empty ) {
		$section->data->data->class[] = 'brix-empty';
	}

	if ( $section_width ) {
		$section->data->data->class[] = 'brix-' . $section_width;
	}

	return $section;
}

add_filter( 'brix_process_frontend_section', 'brix_process_frontend_section', 10, 3 );

/**
 * Add section classes.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @param stdClass $data The section data object.
 * @return array
 */
function brix_section_classes( $classes, $data ) {
	if ( isset( $data->data->_fluid ) && $data->data->_fluid == '0' ) {
		$classes[] = 'brix-fluid-section';
	}

	if ( isset( $data->data->_full_height ) && $data->data->_full_height == '1' ) {
		$classes[] = 'brix-full-height-section';

		if ( isset( $data->data->_full_height_rows_alignment ) ) {
			$classes[] = 'brix-full-height-' . $data->data->_full_height_rows_alignment;
		}
	}


	return $classes;
}

add_filter( 'brix_section_classes', 'brix_section_classes', 10, 2 );