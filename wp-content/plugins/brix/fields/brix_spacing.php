<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Spacing field class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixSpacingField extends Brix_Field {

	/**
	 * Constructor for the builder_spacing field class.
	 *
	 * @since 1.0.0
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = array(
				'desktop' => array(
					'margin_top'     => '',
					'margin_right'   => '',
					'margin_bottom'  => '',
					'margin_left'    => '',
					'padding_top'    => '',
					'padding_right'  => '',
					'padding_bottom' => '',
					'padding_left'   => '',
					'advanced'		 => 0
				)
			);
		}

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array();
		}

		$data['config'] = wp_parse_args( $data['config'], array(
			'breakpoints' => true
		) );

		parent::__construct( $data );
	}
}

/**
 * Add the builder_spacing field type to the valid registered field types.
 *
 * @since 1.0.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_builder_spacing_field_type( $types ) {
	$types['brix_spacing'] = 'BrixSpacingField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_builder_spacing_field_type' );

/**
 * Connect the backend field template file.
 *
 * @since 1.0.0
 * @param string $template The path to the template file.
 * @return string
 */
function brix_spacing_field_template( $template ) {
	return BRIX_TEMPLATES_FOLDER . 'fields/brix_spacing';
}

add_filter( 'brix_field_template[type:brix_spacing]', 'brix_spacing_field_template' );

/**
 * Output the builder spacing CSS.
 *
 * @since 1.0.0
 * @param stdClass $spacing The spacing object.
 * @param string $selector The spacing element selector.
 * @return string
 */
function brix_spacing_style_output( $spacing, $selector ) {
	$spacing_style = '';
	$breakpoints = brix_breakpoints();

	foreach ( $spacing as $breakpoint_key => $rule ) {
		$style = '';

		if ( isset( $rule->advanced ) && $rule->advanced == 0 ) {
			$rule->margin_bottom  = $rule->margin_top;
			$rule->padding_bottom = $rule->padding_top;

			if ( isset( $rule->margin_right ) ) {
				$rule->margin_left    = $rule->margin_right;
			}

			$rule->padding_left   = $rule->padding_right;
		}

		foreach( $rule as $k => $v ) {
			if ( $v !== '' && $r = brix_css_rule_mapping( $k ) ) {
				if ( is_numeric( $v ) ) {
					$v .= 'px';
				}

				$v = str_replace( ',', '.', $v );

				$style .= sprintf( '%s:%s;', $r, $v );
			}
		}

		if ( $style && isset( $breakpoints[$breakpoint_key] ) ) {
			$media = $breakpoints[$breakpoint_key]['media_query'];

			if ( $media ) {
				$spacing_style .= $breakpoints[$breakpoint_key]['media_query'] . '{';
			}

				$spacing_style .= $selector . '{' . $style . '}';

			if ( $media ) {
				$spacing_style .= '}';
			}
		}
	}

	return $spacing_style;
}