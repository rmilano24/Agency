<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Background field class.
 *
 * @package   Brix
 * @since 	  1.1.1
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBackgroundField extends Brix_Field {

	/**
	 * Constructor for the builder_background field class.
	 *
	 * @since 1.1.1
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = array(
				'responsive' => 0,
				'desktop' => array(
					'color' => '',
					'color_type' => ''
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
 * Add the builder_background field type to the valid registered field types.
 *
 * @since 1.1.1
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_builder_background_field_type( $types ) {
	$types['brix_background'] = 'BrixBackgroundField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_builder_background_field_type' );

/**
 * Connect the backend field template file.
 *
 * @since 1.1.1
 * @param string $template The path to the template file.
 * @return string
 */
function brix_background_field_template( $template ) {
	return BRIX_TEMPLATES_FOLDER . 'fields/brix_background';
}

add_filter( 'brix_field_template[type:brix_background]', 'brix_background_field_template' );