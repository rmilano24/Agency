<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Transport field class.
 *
 * @package   Brix
 * @since 	  1.2.5
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixWidgetTransportField extends Brix_Field {

	/**
	 * Constructor for the builder_transport field class.
	 *
	 * @since 1.2.5
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = array();
		}

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array(
				'markup' => ''
			);
		}

		$data['config'] = wp_parse_args( $data['config'], array() );

		parent::__construct( $data );
	}
}

/**
 * Add the brix_widget_transport field type to the valid registered field types.
 *
 * @since 1.2.5
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_brix_widget_transport_field_type( $types ) {
	$types['brix_widget_transport'] = 'BrixWidgetTransportField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_brix_widget_transport_field_type' );

/**
 * Connect the backend field template file.
 *
 * @since 1.2.5
 * @param string $template The path to the template file.
 * @return string
 */
function brix_widget_transport_field_template( $template ) {
	return BRIX_TEMPLATES_FOLDER . 'fields/brix_widget_transport';
}

add_filter( 'brix_field_template[type:brix_widget_transport]', 'brix_widget_transport_field_template' );