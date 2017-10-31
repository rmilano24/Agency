<?php

/**
 * Declare the options to manage the page preloader component.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_footer_options( $fields ) {
	$footer_presets = array(
		'columns' => get_template_directory_uri() . '/config/i/footer_preset_cols.svg',
		'a'       => get_template_directory_uri() . '/config/i/footer_preset_a.svg',
	);

	$header_layouts = array(
		'a' => get_template_directory_uri() . '/config/i/header_a_icon.svg',
		'b' => get_template_directory_uri() . '/config/i/header_b_icon.svg',
	);

	$footer_presets = apply_filters( 'agncy_footer_presets', $footer_presets );

	$footer_layouts = array(
		'disabled'                => __( 'Disabled', 'agncy' ),
		'o-h|o-h'                 => '1/2 &#8213; 1/2',
		'o-h|o-frt|o-frt'         => '1/2 &#8213; 1/4 &#8213; 1/4',
		'o-trd|o-trd|o-trd'       => '1/3 &#8213; 1/3 &#8213; 1/3',
		'o-frt|o-frt|o-frt|o-frt' => '1/4 &#8213; 1/4 &#8213; 1/4 &#8213; 1/4',
	);

	$footer_layouts = apply_filters( 'agncy_footer_layouts', $footer_layouts );

	$fields[] = array(
		'handle' => 'agncy_footer',
		'label' => __( 'Footer', 'agncy' ),
		'type' => 'group',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Appearance', 'agncy' ),
			),
			array(
				'handle' => 'fixed_footer',
				'label' => __( 'Fixed reveal', 'agncy' ),
				'type' => 'checkbox',
				'help' => __( 'The footer will be revealed upon scrolling down the page.', 'agncy' ),
				'config' => array(
					'style' => array( 'switch', 'small' )
				),
				'default' => '0'
			),
			array(
				'handle' => 'footer_layout_skin',
				'label' => __( 'Background skin', 'agncy' ),
				'help' => __( 'Select the color theme used in the footer area.', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'data' => array(
						'light' => __( 'Dark background, light text', 'agncy' ),
						'dark' => __( 'Light background, dark text', 'agncy' ),
					),
				)
			),
			array(
				'type' => 'divider',
				'text' => __( 'Layout', 'agncy' ),
			),
			array(
				'handle' => 'footer_presets',
				'label' => __( 'Preset', 'agncy' ),
				'type' => 'radio',
				'help' => __( 'Select a particular footer preset.', 'agncy' ),
				'config' => array(
					'style' => 'graphic',
					'data' => $footer_presets,
					'controller' => true
				)
			),

			array(
				'handle' => 'footer_preset_widget_area_col_1',
				'label' => __( 'Main footer widget area', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_preset_widget_area_col_2',
				'label' => __( 'Footer column 1', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_preset_widget_area_col_3',
				'label' => __( 'Footer column 2', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_preset_widget_area_col_4',
				'label' => __( 'Footer column 3', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_preset_widget_area_col_5',
				'label' => __( 'Copyright widget area left column', 'agncy' ),
				'help' => __( 'Displayed under the footer columns.', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_preset_widget_area_col_6',
				'label' => __( 'Copyright widget area right column', 'agncy' ),
				'help' => __( 'Displayed under the footer columns.', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_presets' => 'a' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),

			array(
				'handle' => 'footer_layout',
				'label' => __( 'Layout', 'agncy' ),
				'type' => 'select',
				'help' => __( 'Select a particular footer layout.', 'agncy' ),
				'config' => array(
					'data' => $footer_layouts,
					'controller' => true,
					'visible' => array( 'footer_presets' => 'columns' )
				)
			),
			array(
				'handle' => 'footer_widget_area_col_1',
				'label' => __( 'Widget area column 1', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_layout' => '!=disabled' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_widget_area_col_2',
				'label' => __( 'Widget area column 2', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_layout' => '!=disabled' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_widget_area_col_3',
				'label' => __( 'Widget area column 3', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_layout' => 'o-h|o-frt|o-frt,o-trd|o-trd|o-trd,o-frt|o-frt|o-frt|o-frt' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
			array(
				'handle' => 'footer_widget_area_col_4',
				'label' => __( 'Widget area column 4', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'visible' => array( 'footer_layout' => 'o-frt|o-frt|o-frt|o-frt' ),
					'data' => agncy_get_widget_areas_for_select()
				)
			),
		)
	);

	return $fields;
}

add_filter( 'agncy_global_fields', 'agncy_footer_options' );