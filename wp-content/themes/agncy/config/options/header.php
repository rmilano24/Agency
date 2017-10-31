<?php

/**
 * Declare the options to manage the page preloader component.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function agncy_header_options( $fields ) {
	$header_layouts = array(
		'a' => get_template_directory_uri() . '/config/i/header_a_icon.svg',
		'b' => get_template_directory_uri() . '/config/i/header_b_icon.svg',
	);

	$header_layouts = apply_filters( 'agncy_header_layouts', $header_layouts );

	$fields[] = array(
		'handle' => 'agncy_header',
		'label' => __( 'Header', 'agncy' ),
		'type' => 'group',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Layout', 'agncy' ),
			),
			array(
				'handle' => 'header_layout',
				'label' => __( 'Layout', 'agncy' ),
				'type' => 'radio',
				'help' => __( 'Select a particular header layout.', 'agncy' ),
				'config' => array(
					'style' => 'graphic',
					'controller' => true,
					'data' => $header_layouts,
				)
			),
				array(
					'handle' => 'header_layout_b_widget_area_1',
					'label' => __( 'Widget area', 'agncy' ),
					'type' => 'select',
					'config' => array(
						'visible' => array( 'header_layout' => 'b' ),
						'data' => agncy_get_widget_areas_for_select()
					)
				),
				array(
					'type' => 'divider',
					'text' => __( 'Background', 'agncy' ),
					'config' => array(
						'style' => 'in_page',
						'visible' => array( 'header_layout' => 'b' ),
					)
				),
				array(
					'handle' => 'header_layout_b_background_image',
					'label' => __( 'Background image', 'agncy' ),
					'type' => 'image',
					'config' => array(
						'visible' => array( 'header_layout' => 'b' ),
					)
				),
				array(
					'handle' => 'header_layout_b_background_overlay',
					'label' => __( 'Background overlay', 'agncy' ),
					'type' => 'color',
					'config' => array(
						'opacity' => true,
						'visible' => array( 'header_layout' => 'b' ),
					)
				),
				array(
					'handle' => 'header_layout_b_skin',
					'label' => __( 'Background skin', 'agncy' ),
					'type' => 'select',
					'config' => array(
						'data' => array(
							'light' => __( 'Dark background, light text', 'agncy' ),
							'dark' => __( 'Light background, dark text', 'agncy' ),
						),
						'visible' => array( 'header_layout' => 'b' ),
					)
				),
				array(
					'handle' => 'header_layout_a_widget_area_1',
					'label' => __( 'Widget area #1', 'agncy' ),
					'help' => __( 'Select the widget area to be used in the first column of the header.', 'agncy' ),
					'type' => 'select',
					'config' => array(
						'visible' => array( 'header_layout' => 'a' ),
						'data' => agncy_get_widget_areas_for_select()
					)
				),
				array(
					'handle' => 'header_layout_a_widget_area_2',
					'label' => __( 'Widget area #2', 'agncy' ),
					'help' => __( 'Select the widget area to be used in the second column of the header.', 'agncy' ),
					'type' => 'select',
					'config' => array(
						'visible' => array( 'header_layout' => 'a' ),
						'data' => agncy_get_widget_areas_for_select()
					)
				),

			array(
				'type' => 'divider',
				'text' => __( 'Logo', 'agncy' ),
			),
			array(
				'handle' => 'logo',
				'label' => __( 'Main logo', 'agncy' ),
				'type' => 'image',
				'help' => __( 'The website logo.', 'agncy' ),
				'config' => array(
					'breakpoints' => true
				),
			),
			array(
				'handle' => 'logo_white',
				'label' => __( 'Main logo white', 'agncy' ),
				'type' => 'image',
				'help' => __( 'The website logo in a light variant to be used when there is a dark background.', 'agncy' ),
				'config' => array(
					'breakpoints' => true
				),
			),
			array(
				'handle' => 'show_tagline',
				'label' => __( 'Show tagline', 'agncy' ),
				'type' => 'checkbox',
				'help' => __( 'Show the site tagline below the site title.', 'agncy' ),
				'config' => array(
					'style' => array( 'switch', 'small' )
				),
				'default' => '0'
			),

			array(
				'type' => 'divider',
				'text' => __( 'Mobile', 'agncy' ),
			),
			array(
				'handle' => 'mobile_drawer_skin',
				'label' => __( 'Mobile drawer skin', 'agncy' ),
				'help' => __( 'The colors combination used for the mobile menu panel.', 'agncy' ),
				'type' => 'select',
				'config' => array(
					'data' => array(
						'light' => __( 'Dark background, light text', 'agncy' ),
						'dark' => __( 'Light background, dark text', 'agncy' ),
					),
				)
			),
		)
	);

	return $fields;
}

add_filter( 'agncy_global_fields', 'agncy_header_options' );

/**
 * Add the customizer style as an inline style block.
 *
 * @since 1.0.0
 */
function agncy_header_b_inline_style() {
	if ( function_exists( 'ev_fw' ) ) {
		$header_layout = ev_get_option( 'header_layout' );

		if ( $header_layout != 'b' ) {
			return;
		}

		$style = '';

		$background_image = ev_get_option( 'header_layout_b_background_image' );
		$drawer_skin = ev_get_option( 'header_layout_b_skin' );

		if ( isset( $background_image[ 'desktop' ] ) && ! empty( $background_image[ 'desktop' ][ 1 ][ 'id' ] ) ) {
			$style .= sprintf(
				'.agncy-drawer-opening .agncy-h-drawer-bg{background-image:url(%s)}',
				ev_get_image( $background_image[ 'desktop' ][ 1 ][ 'id' ], 'full' )
			);
		} else {
			$color = '';
			if ( $drawer_skin == 'light' ) {
				$color = '#000';
			} elseif ( $drawer_skin == 'dark' ) {
				$color = '#fff';
			}

			$style .= sprintf(
				'.agncy-drawer-opening .agncy-h-drawer-bg{background-color:%s;color:%s}',
				$color,
				$color
			);
		}

		$background_overlay = ev_get_option( 'header_layout_b_background_overlay' );

		if ( isset( $background_overlay[ 'color' ] ) && ! empty( $background_overlay[ 'color' ] ) ) {
			$style .= sprintf(
				'.agncy-drawer-opening .agncy-h-drawer-bg{color:%s}',
				$background_overlay[ 'color' ]
			);
		}

		if ( $style ) {
			ev_fw()->frontend()->add_inline_style( $style );
		}
	}
}

add_action( 'wp_head', 'agncy_header_b_inline_style', 5 );

/**
 * Header mobile inline style.
 *
 * @since 1.0.0
 */
function agncy_header_mobile_inline_style() {
	if ( function_exists( 'ev_fw' ) ) {
		$style = '';
		$color = '';

		$drawer_skin = ev_get_option( 'mobile_drawer_skin' );

		if ( $drawer_skin == 'light' ) {
			$color = '#000';
		} elseif ( $drawer_skin == 'dark' ) {
			$color = '#fff';
		}

		if ( ! empty( $color ) ) {
			$style .= sprintf(
				'.agncy-h-m-drawer .agncy-h-m-drawer-bg{background-color:%s}',
				$color,
				$color
			);
		}

		$background_overlay = ev_get_option( 'header_layout_b_background_overlay' );

		if ( isset( $background_overlay[ 'color' ] ) && ! empty( $background_overlay[ 'color' ] ) ) {
			$style .= sprintf(
				'.agncy-drawer-opening .agncy-h-drawer-bg{color:%s}',
				$background_overlay[ 'color' ]
			);
		}

		if ( $style ) {
			ev_fw()->frontend()->add_inline_style( $style );
		}
	}
}
add_action( 'wp_head', 'agncy_header_mobile_inline_style', 5 );