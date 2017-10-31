<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Date field class.
 *
 * @package   BrixFramework
 * @since 	  0.1.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_DateField extends Brix_Field {

	/**
	 * Constructor for the date field class.
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
			$data['config'] = array();
		}

		$data['config'] = wp_parse_args( $data['config'], array(
			'style'  => '',
			'size'   => '',
			'format' => 'yy-mm-dd'
		) );

		parent::__construct( $data );
	}
}

/**
 * Add the date field type to the valid registered field types.
 *
 * @since 0.1.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_date_field_type( $types ) {
	$types['date'] = 'Brix_DateField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_date_field_type' );

/**
 * Localize the date field.
 *
 * @since 0.1.0
 */
function brix_date_field_i18n() {
	wp_localize_script( 'jquery', 'brix_date_field', array(
		'dayNames' => array(
			__( 'Sunday' ),
			__( 'Monday' ),
			__( 'Tuesday' ),
			__( 'Wednesday' ),
			__( 'Thursday' ),
			__( 'Friday' ),
			__( 'Saturday' )
		),
		'dayNamesShort' => array(
			_x( 'Su', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'Mo', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'Tu', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'We', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'Th', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'Fr', 'jquery ui datepicker short day name', 'brix' ),
			_x( 'Sa', 'jquery ui datepicker short day name', 'brix' )
		),
		'monthNames' => array(
			__( 'January' ),
			__( 'February' ),
			__( 'March' ),
			__( 'April' ),
			__( 'May' ),
			__( 'June' ),
			__( 'July' ),
			__( 'August' ),
			__( 'September' ),
			__( 'October' ),
			__( 'November' ),
			__( 'December' )
		),
		'monthNamesShort' => array(
			__( 'Jan' ),
			__( 'Feb' ),
			__( 'Mar' ),
			__( 'Apr' ),
			__( 'May' ),
			__( 'Jun' ),
			__( 'Jul' ),
			__( 'Aug' ),
			__( 'Sep' ),
			__( 'Oct' ),
			__( 'Nov' ),
			__( 'Dec' )
		),
		'prevText' => _x( 'Prev', 'jquery ui datepicker prev text', 'brix' ),
		'nextText' => _x( 'Next', 'jquery ui datepicker next text', 'brix' )
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_date_field_i18n' );