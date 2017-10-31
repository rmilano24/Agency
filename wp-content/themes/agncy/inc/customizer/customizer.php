<?php

/* Helpers. */
require_once get_template_directory() . '/inc/customizer/helpers.php';

/* Font families control. */
require_once get_template_directory() . '/inc/customizer/controls/families.php';

/* Colors control. */
require_once get_template_directory() . '/inc/customizer/controls/colors.php';

/**
 * Localize the admin customizer.
 *
 * @since 1.0.0
 * @param array $agncy The localization array.
 * @return array
 */
function agncy_customizer_admin_localize( $agncy ) {
	global $pagenow;

	if ( $pagenow == 'customize.php' ) {
		$js_admin_path = trailingslashit( get_template_directory() . '/js/admin' );

		$font_sources = array(
			array(
				'value' => 'google_fonts',
				'id' => 'source-google_fonts',
				'label' => __( 'Google Fonts', 'agncy' )
			),
			array(
				'value' => 'typekit',
				'id' => 'source-typekit',
				'label' => __( 'Typekit', 'agncy' )
			),
			array(
				'value' => 'custom',
				'id' => 'source-custom',
				'label' => __( 'Custom', 'agncy' )
			)
		);

		$google_fonts_json = json_decode( implode( '', file( $js_admin_path . 'webfonts.json' ) ) );
		$google_fonts = array();

		foreach ( $google_fonts_json->items as $font ) {
			$google_fonts[$font->family] = array(
				'category' => $font->category,
				'variants' => $font->variants,
				'subsets' => $font->subsets,
			);
		}

		$controls = agncy_get_customizer_controls();

		$agncy['customizer'] = array(
			'strings' => array(
				'force_refresh_button' => __( 'Force refresh', 'agncy' ),
				'force_refresh_button_help' => __( 'Re-save customizer data and force the refresh of the imported external CSS file.', 'agncy' )
			),
			'typography'   => array(
				'family_label_ask' => __( 'Give a label to the font family', 'agncy' ),
				'family_default_data' => array(
					'_custom' => true,
					'label' => '',
					'source' => 'google_fonts',
					'google_fonts' => array(
						'font_family'   => '',
						'variants' => '',
						'subsets'  => ''
					),
					'typekit' => array(
						'kitId' => '',
						'font_family' => ''
					),
					'custom' => array(
						'url' => '',
						'font_family' => ''
					),
				),

				'google_fonts' => $google_fonts,
				'font_sources' => $font_sources,
				'structure'    => $controls[ 'agncy_typography' ],
				'data'         => agncy_customizer_get_typography_data()
			),
			'colors' => array(
				'structure'    => $controls[ 'agncy_colors' ],
				'data'         => agncy_customizer_get_colors_data()
			)
		);
	}

	return $agncy;
}

add_filter( 'agncy_admin_localize', 'agncy_customizer_admin_localize' );

/**
 * Return a list of font families defined in the customizer.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_customizer_get_typography_data() {
	$data = agncy_customizer_build_data( 'agncy_typography' );

	return $data;
}

/**
 * Return a list of colors defined in the customizer.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_customizer_get_colors_data() {
	$mod_key = 'agncy_colors';

	/* Grab the previously saved data. */
	$data = json_decode( get_theme_mod( $mod_key ) );

	/* Generated ruleset. */
	$ruleset = require trailingslashit( get_template_directory() . '/config/customize' ) . 'ruleset.php';

	/* Customizer controls definition list. */
	$controls = agncy_get_customizer_controls( $mod_key );

	if ( ! $data ) {
		$data = (object) array();
	}

	foreach ( $controls as $control_key => $control ) {
		foreach ( $control[ 'fields' ] as $field_key => $field_label ) {
			if ( ! isset( $ruleset[ $field_key ] ) ) {
				continue;
			}

			if ( ! isset( $data->$control_key ) ) {
				$data->$control_key = (object) array();
			}

			if ( ! isset( $data->$control_key->$field_key ) ) {
				$data->$control_key->$field_key = (object) array();
			}

			$data->$control_key->$field_key = apply_filters( "agncy_customizer_instance_data[mod:$mod_key]", $data->$control_key->$field_key, $ruleset[ $field_key ] );
		}

		if ( isset( $data->$control_key ) ) {
			foreach ( $data->$control_key as $field_key => $instance ) {
				if ( ! isset( $ruleset[ $field_key ] ) ) {
					unset( $data->$control_key->$field_key );
				}
			}
		}
	}

	foreach ( $data as $control_key => $control ) {
		foreach ( $control as $field_key => $field_data ) {
			if ( isset( $controls[ $control_key ] ) && ! in_array( $field_key, array_keys( $controls[ $control_key ][ 'fields' ] ) ) ) {
				unset( $data->$control_key->$field_key );
			}
		}
	}

	return $data;
}

/**
 * Build the customizer data for the specified customizer mod.
 *
 * @since 1.0.0
 * @param string $mod_key The mod key.
 * @return object
 */
function agncy_customizer_build_data( $mod_key ) {
	/* Grab the previously saved data. */
	$data = json_decode( get_theme_mod( $mod_key ) );

	/* Generated ruleset. */
	$ruleset = require trailingslashit( get_template_directory() . '/config/customize' ) . 'ruleset.php';

	/* Customizer controls definition list. */
	$controls = agncy_get_customizer_controls( $mod_key );

	if ( ! $data ) {
		/* If there's no previously saved data, reset to an empty set. */
		$data = (object) array(
			'global' => (object) array(),
			'instances' => (object) array(),
		);
	}
	else {
		if ( ! isset( $data->global ) || empty( $data->global ) ) {
			$data->global = (object) array();
		}

		if ( ! isset( $data->instances ) || empty( $data->instances ) ) {
			$data->instances = (object) array();
		}
	}

	$data->global = apply_filters( "agncy_customizer_global_data[mod:$mod_key]", $data->global );

	foreach ( $controls as $control_key => $control ) {
		foreach ( $control[ 'fields' ] as $field_key => $field_label ) {
			if ( ! isset( $ruleset[ $field_key ] ) ) {
				continue;
			}

			if ( ! isset( $data->instances->$control_key ) ) {
				$data->instances->$control_key = (object) array();
			}

			if ( ! isset( $data->instances->$control_key->$field_key ) ) {
				$data->instances->$control_key->$field_key = (object) array();
			}

			$data->instances->$control_key->$field_key = apply_filters( "agncy_customizer_instance_data[mod:$mod_key]", $data->instances->$control_key->$field_key, $ruleset[ $field_key ] );
		}

		if ( isset( $data->instances->$control_key ) ) {
			foreach ( $data->instances->$control_key as $field_key => $instance ) {
				if ( ! isset( $ruleset[ $field_key ] ) ) {
					unset( $data->instances->$control_key->$field_key );
				}
			}
		}
	}

	foreach ( $data->instances as $instance_key => $instance_data ) {
		if ( ! isset( $controls[ $instance_key ] ) ) {
			unset( $data->instances->$instance_key );
		}
	}

	return $data;
}

/**
 * Localize customizer data on frontend.
 *
 * @since 1.0.0
 */
function agncy_localize_customizer() {
	$data = array();

	if ( agncy_use_customizer_typography() ) {
		$typography_data = json_decode( get_theme_mod( 'agncy_typography' ) );

		if ( ! $typography_data ) {
			$typography_data = (object) array(
				'global' => apply_filters( "agncy_customizer_global_data[mod:agncy_typography]", (object) array() )
			);
		}

		$data = array(
			'typography' => $typography_data
		);
	}

	$data = apply_filters( 'agncy_localize_customizer', $data );

	wp_localize_script( 'agncy-script', 'agncy_customizer', $data );
}

add_action( 'wp_enqueue_scripts', 'agncy_localize_customizer', 11 );

/**
 * Normalize the theme path.
 *
 * @since 1.0.0
 * @param array $data The customizer localized data.
 * @return array
 */
function agncy_localize_customizer_themepath( $data ) {
	if ( isset( $data[ 'typography' ] ) ) {
		foreach ( $data[ 'typography' ]->global as &$family_data ) {
			if ( $family_data->source == 'custom' ) {
				if ( is_array( $family_data->custom ) ) {
					$family_data->custom = (object) $family_data->custom;
				}

				$family_data->custom->url = str_replace( '%themepath%', get_template_directory_uri(), $family_data->custom->url );
				$family_data->custom->url = str_replace( '%childthemepath%', get_stylesheet_directory_uri(), $family_data->custom->url );
			}
		}
	}

	return $data;
}

add_filter( 'agncy_localize_customizer', 'agncy_localize_customizer_themepath' );

/**
 * Update the customizer data upon updating the theme.
 *
 * @since 1.0.3
 */
function agncy_update_customizer() {
	if ( function_exists( 'agncy_customizer_saved' ) ) {
		agncy_customizer_saved();
	}
}

add_action( 'agncy_updated', 'agncy_update_customizer' );
add_action( 'agncy_demo_installed', 'agncy_update_customizer' );