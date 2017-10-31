<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Divider field class.
 *
 * @package   BrixFramework
 * @since 	  0.1.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_DividerField extends Brix_Field {

	/**
	 * The divider text.
	 *
	 * @var string
	 */
	protected $_text = '';

	/**
	 * Constructor for the divider field class.
	 *
	 * @since 0.1.0
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		$this->_text = $data['text'];

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array();
		}

		$data['config'] = wp_parse_args( $data['config'], array(
			'style' => 'section_break'
		) );

		parent::__construct( $data );
	}

	/**
	 * Return the divider text.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function text()
	{
		return $this->_text;
	}

	/**
	 * Return the divider style.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function style()
	{
		return $this->_data['config']['style'];
	}

	/**
	 * Validate the field declaration structure.
	 *
	 * @static
	 * @since 0.1.0
	 * @param array $field The field declaration structure.
	 * @return boolean
	 */
	public static function validate_structure( $field )
	{
		$messages = array();

		if ( ! array_key_exists( 'text', $field ) || empty( $field['text'] ) ) {
			/* Ensuring that the field has a valid text. */
			$messages[] = 'Field: missing text parameter.';
		}
		elseif ( array_key_exists( 'config', $field ) && array_key_exists( 'style', $field['config'] ) ) {
			$allowed_styles = array(
				// Section break
				'section_break',
				// In page divider
				'in_page'
			);
			$allowed_styles = apply_filters( 'brix_divider_field_styles', $allowed_styles );

			if ( empty( $field['config']['style'] ) || ! in_array( $field['config']['style'], $allowed_styles ) ) {
				/* Ensuring that the field has a valid value for its style, if any. */
				$messages[] = sprintf( 'Field "%s": invalid style parameter.', $field['type'] );
			}
		}

		return apply_filters( "brix_field_validate_structure[type:divider]", $messages, $field );
	}

}

/**
 * Add a specific class to the divider field according to its style.
 *
 * @since 0.1.0
 * @param array $types An array of CSS classes.
 * @return array
 */
function brix_divider_field_classes( $classes, $field ) {
	$classes[] = 'brix-divider-style-' . $field->style();

	return $classes;
}

add_filter( 'brix_field_classes[type:divider]', 'brix_divider_field_classes', 10, 2 );

/**
 * Add the divider field type to the valid registered field types.
 *
 * @since 0.1.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_divider_field_type( $types ) {
	$types['divider'] = 'Brix_DividerField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_divider_field_type' );

/**
 * Add the divider field type to list of field types that must be ignored when
 * saving a meta box or a page.
 *
 * @since 0.1.0
 * @param array $types An array containing the field types that must be ignored when saving a meta box or a page.
 * @return array
 */
function brix_skip_divider_on_saving( $types ) {
	$types[] = 'divider';

	return $types;
}

add_filter( 'brix_skip_on_saving_field_types', 'brix_skip_divider_on_saving' );