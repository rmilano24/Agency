<?php

/**
 * Setting sanitization callback function.
 *
 * @since 1.0.0
 * @param array $array The data array.
 * @return array
 */
function agncy_add_setting_sanitize_callback( $array ) {
	return $array;
}

/**
 * Register the customizer.
 *
 * @since 1.0.0
 * @param object $wp_customize The Customizer instance.
 */
function agncy_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'agncy', array(
		'title' => __( 'Agncy', 'agncy' ),
		'description' => '',
		'priority' => 160,
	) );

	$wp_customize->add_section( 'agncy_colors', array(
		'title' => __( 'Colors', 'agncy' ),
		'description' => '',
		'panel' => 'agncy'
	) );

	$wp_customize->add_setting( 'agncy_colors', array(
		'sanitize_callback' => 'agncy_add_setting_sanitize_callback'
	) );

	$wp_customize->add_control( new Agncy_Customize_Colors_Control( $wp_customize, 'agncy_colors', array(
		'label' => __( 'Colors', 'agncy' ),
		'section' => 'agncy_colors',
	) ) );

	$wp_customize->add_section( 'agncy_typography', array(
		'title'       => __( 'Typography', 'agncy' ),
		'description' => __( 'The font families used in the theme are shared across a number of different uses. You can pick a different family, change the source of the family, or change the association of a family with a particular typographic element.', 'agncy' ),
		'panel'       => 'agncy'
	) );

	$wp_customize->add_setting( 'agncy_typography', array(
		'sanitize_callback' => 'agncy_add_setting_sanitize_callback'
	) );

	$wp_customize->add_control( new Agncy_Customize_FontFamilies_Control( $wp_customize, 'agncy_typography', array(
		'label' => __( 'Font families', 'agncy' ),
		'section' => 'agncy_typography',
	) ) );
}

add_action( 'customize_register', 'agncy_customize_register' );

/**
 * Get the controls used in the customizer;
 *
 * @since 1.0.0
 * @param string $key The subkey to retrieve.
 * @return array
 */
function agncy_get_customizer_controls( $key = false ) {
	$sections = array(
		'agncy_colors' => array(
			'general' => array(
				'label'       => esc_html__( 'Colors', 'agncy' ),
				'description' => '',
				'fields'      => array(
					'logo_color'       => esc_html__( 'Logo', 'agncy' ),
					'tagline_color'    => esc_html__( 'Tagline', 'agncy' ),
					'text_color'       => esc_html__( 'Text', 'agncy' ),
					'text_light_color' => esc_html__( 'Light text', 'agncy' ),
					'headings_color'   => esc_html__( 'Headings', 'agncy' ),
					'links_color'      => esc_html__( 'Links', 'agncy' ),
					'lighter_details'  => esc_html__( 'Lighter details', 'agncy' ),
					'preloader_color'  => esc_html__( 'Preloader', 'agncy' ),
				)
			),
			'footer' => array(
				'label'       => esc_html__( 'Footer', 'agncy' ),
				'description' => '',
				'fields' => array(
					'footer_background_color' => esc_html__( 'Footer background', 'agncy' ),
				)
			),
		),
		'agncy_typography' => array(
			'main' => array(
				'label'       => esc_html__( 'Main text', 'agncy' ),
				'description' => '',
				'fields'      => array(
					'logo'         => esc_html__( 'Logo', 'agncy' ),
					'text'         => esc_html__( 'Text', 'agncy' ),
					'text_small'   => esc_html__( 'Small text', 'agncy' ),
					'text_smaller' => esc_html__( 'Smaller text', 'agncy' ),
					'text_big'     => esc_html__( 'Big text', 'agncy' ),
					'quote'        => esc_html__( 'Quote', 'agncy' ),
					'header_bar'   => esc_html__( 'Header bar', 'agncy' ),
				)
			),
			'nav' => array(
				'label'       => esc_html__( 'Navigation', 'agncy' ),
				'description' => '',
				'fields'      => array(
					'main_nav'        => esc_html__( 'Main nav', 'agncy' ),
					'main_nav_mobile' => esc_html__( 'Mobile - Main nav', 'agncy' ),
					'main_nav_drawer' => esc_html__( 'Drawer - Main nav', 'agncy' ),
				)
			),
			'headings' => array(
				'label'       => esc_html__( 'Headings', 'agncy' ),
				'description' => '',
				'fields'      => array(
					'h1'            => esc_html__( 'Heading 1', 'agncy' ),
					'h1_mobile'     => esc_html__( 'Mobile - Heading 1', 'agncy' ),
					'h1_big'        => esc_html__( 'Heading 1 Big', 'agncy' ),
					'h1_big_mobile' => esc_html__( 'Mobile - Heading 1 Big', 'agncy' ),
					'h2'            => esc_html__( 'Heading 2', 'agncy' ),
					'h3'            => esc_html__( 'Heading 3', 'agncy' ),
					'h4'            => esc_html__( 'Heading 4', 'agncy' ),
					'h5'            => esc_html__( 'Heading 5', 'agncy' ),
					'h6'            => esc_html__( 'Heading 6', 'agncy' ),
					'subheading'    => esc_html__( 'Sub heading', 'agncy' ),
				)
			),
		),
	);

	$sections = apply_filters( 'agncy_get_customizer_controls', $sections );

	if ( $key && isset( $sections[ $key ] ) ) {
		return $sections[ $key ];
	}

	return $sections;
}