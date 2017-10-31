<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Icon field class.
 *
 * @package   BrixFramework
 * @since 	  0.1.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_IconField extends Brix_Field {

	/**
	 * Constructor for the icon field class.
	 *
	 * @since 0.1.0
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = '';
		}

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array(
				'modal' => true,
			);
		}

		// $data['config'] = wp_parse_args( $data['config'], array(
		// 	'size' => ''
		// ) );

		parent::__construct( $data );
	}
}

/**
 * Add the icon field type to the valid registered field types.
 *
 * @since 0.1.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_icon_field_type( $types ) {
	$types['icon'] = 'Brix_IconField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_icon_field_type' );

/**
 * Localize the icon field.
 *
 * @since 0.1.0
 */
function brix_icon_field_i18n() {
	wp_localize_script( 'jquery', 'brix_icon_field', array(
		'0' => _x( 'Nothing found', 'no icons found', 'brix' ),
		'1' => _x( '%s found', 'one icon found', 'brix' ),
		'2' => _x( '%s found', 'multiple icons found', 'brix' ),
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_icon_field_i18n' );