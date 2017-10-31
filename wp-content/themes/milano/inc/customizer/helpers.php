<?php

/**
 * Set to true if the theme must generate and use CSS from the Customizer mods.
 *
 * @since 1.0.2
 * @return boolean
 */
function agncy_use_customizer() {
	return apply_filters( 'agncy_use_customizer', true );
}

/**
 * Set to true if the theme must generate and use CSS from the Customizer mods
 * regarding typography.
 *
 * @since 1.0.4
 * @return boolean
 */
function agncy_use_customizer_typography() {
	return apply_filters( 'agncy_use_customizer_typography', agncy_use_customizer() );
}

/**
 * Output a CSS rule.
 *
 * @since 1.0.0
 * @param string $instance The instance value.
 * @param string $ruleset The CSS ruleset definition.
 * @param object $mods The customization object.
 * @return string
 */
function agncy_customizer_print_css_rule( $instance, $ruleset, $mods ) {
	$output = array();

	$text_props = array(
		'letter-spacing',
		'text-transform',
		'variant'       ,
		'_font-family'  ,
		'font-family'   ,
		'line-height'   ,
		'font-size'     ,
		// 'color'            => '',
		// 'background-color' => '',
		// 'border-color'     => '',
		// 'fill'             => '',
	);

	foreach ( $ruleset[ 'selectors' ] as $selector => $rules ) {
		$selector_rules = '';
		$media = false;

		foreach ( $text_props as $prop ) {
			$_key = str_replace( '-', '_', $prop );

			if ( isset( $instance->$_key ) && $instance->$_key !== '' ) {
				if ( ! isset( $rules[ $prop ] ) ) {
					$rules[ $prop ] = $instance->$_key;
				}
			}
		}

		if ( isset( $rules[ '_type' ] ) && $rules[ '_type' ] != 'desktop' ) {
			$media = $rules[ '_type' ];
		}

		if ( $media ) {
			$selector = $media . '{' . $selector;
		}

		foreach ( $rules as $rule => $value ) {
			$instance_rule = str_replace( '-', '_', $rule );

			switch ( $rule ) {
				case 'color':
				case 'background-color':
				case 'border-color':
				case 'fill':
					if ( isset( $instance->color ) && $instance->color ) {
						$selector_rules .= $rule . ':' . $instance->color . ';';
					}
					break;
				case 'text-transform':
					if ( isset( $instance->$instance_rule ) && $instance->$instance_rule !== 'none' ) {
						$selector_rules .= $rule . ':' . $instance->$instance_rule . ';';
					}
					break;
				case 'variant':
					if ( ! isset( $instance->variant ) || $value == $instance->variant ) {
						break;
					}

					switch ( $instance->variant ) {
						case 'regular':
							$selector_rules .= 'font-style:normal;font-weight:normal;';
							break;
						case 'bold':
							$selector_rules .= 'font-style:normal;font-weight:bold;';
							break;
						case 'italic':
							$selector_rules .= 'font-style:italic;font-weight:normal;';
							break;
						case 'bolditalic':
							$selector_rules .= 'font-style:italic;font-weight:bold;';
							break;
						default:
							$font_style = strpos( $instance->variant, 'italic' ) !== false ? 'italic' : 'normal';
							$font_weight = str_replace( 'italic', '', $instance->variant );

							$selector_rules .= "font-style:$font_style;font-weight:$font_weight;";
							break;
					}
					break;
				case '_font-family':
					if ( ! isset( $instance->font_family ) ) {
						break;
					}

					$value = $instance->font_family;

					if ( isset( $mods->global->$value ) ) {
						$global_font_family = $mods->global->$value;
					}
					else {
						$global_font_family = $mods->global->primary;
					}

					$global_font_family_source = $global_font_family->source;
					$alternate_font_family = '';

					switch ( $global_font_family_source ) {
						case 'google_fonts':
						case 'typekit':
						case 'custom':
							$alternate_font_family = $global_font_family->$global_font_family_source->font_family;
							break;
						default:
							break;
					}

					if ( $alternate_font_family && isset( $rules[ 'font-family' ] ) && $rules[ 'font-family' ] != $alternate_font_family ) {
						$selector_rules .= 'font-family:"' . $alternate_font_family . '";';
					}
					break;
				case 'font-family':
					break;
				case 'font_family':
					break;
				case '_type':
					break;
				default:
					if ( isset( $instance->$instance_rule ) && $instance->$instance_rule !== '' ) {
						$selector_rules .= $rule . ':' . $instance->$instance_rule . ';';
					}
					break;
			}
		}

		$selector_rules = trim( $selector_rules, ';' );

		if ( $media ) {
			$output[] = array( $selector_rules, $selector );
		}
		else {
			$found = false;

			foreach ( $output as $i => &$o ) {
				if ( $o[ 0 ] == $selector_rules ) {
					$o[ 1 ] .= ',' . $selector;
					$found = true;

					break;
				}
			}

			if ( ! $found ) {
				$output[] = array( $selector_rules, $selector );
			}
		}
	}

	if ( $output ) {
		$css = '';

		foreach ( $output as $i => $o ) {
			if ( ! $o[ 0 ] ) {
				continue;
			}

			$is_media = strpos( $o[ 1 ], '@media' ) !== false;

			if ( $is_media ) {
				$css .= $o[ 1 ] . '{' . $o[ 0 ] . '}}';
			}
			else {
				$css .= $o[ 1 ] . '{' . $o[ 0 ] . '}';
			}
		}

		return $css;
	}

	return '';
}

/**
 * Get the customizer output.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_customizer_output() {
	$mods_keys = array(
		'agncy_typography',
		'agncy_colors'
	);

	/* Generated ruleset. */
	$ruleset = require trailingslashit( get_template_directory() . '/config/customize' ) . 'ruleset.php';

	$style = '';
	$selectors = array();
	$controls = agncy_get_customizer_controls();

	foreach ( $mods_keys as $mod ) {
		$mods = json_decode( get_theme_mod( $mod ) );

		if ( ! $mods ) {
			continue;
		}

		foreach ( $controls[ $mod ] as $control_key => $control ) {
			foreach ( $control[ 'fields' ] as $field_key => $field_label ) {
				if ( ! isset( $ruleset[ $field_key ] ) ) {
					continue;
				}

				switch ( $mod ) {
					case 'agncy_typography':
						if ( ! isset( $mods->instances->$control_key ) || ! isset( $mods->instances->$control_key->$field_key ) ) {
							continue;
						}

						$style .= agncy_customizer_print_css_rule( $mods->instances->$control_key->$field_key, $ruleset[ $field_key ], $mods );

						break;
					case 'agncy_colors':
					default:
						if ( ! isset( $mods->$control_key ) || ! isset( $mods->$control_key->$field_key ) ) {
							continue;
						}

						$style .= agncy_customizer_print_css_rule( $mods->$control_key->$field_key, $ruleset[ $field_key ], $mods );
						break;
				}
			}
		}
	}

	return $style;
}

/**
 * Add the customizer style as an inline style block.
 *
 * @since 1.0.0
 */
function agncy_customizer_add_inline_style() {
	if ( function_exists( 'ev_fw' ) ) {
		ev_fw()->frontend()->add_inline_style( agncy_get_customizer_output() );
	}
}

/**
 * Output the styles modified in the theme customizer.
 *
 * @since 1.0.0
 */
function agncy_customizer_output() {
	if ( ! agncy_use_customizer() ) {
		return;
	}

	$external_file = ! is_customize_preview() && apply_filters( 'agncy_customizer_use_external_file', true );

	if ( ! $external_file ) {
		agncy_customizer_add_inline_style();
	}
	else {
		do_action( 'agncy_customizer_external_file' );
	}
}

add_action( 'wp_head', 'agncy_customizer_output', 5 );