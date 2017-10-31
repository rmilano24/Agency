<?php if ( ! defined( 'EV_FW' ) ) die( 'Forbidden' );

/**
 * Dominant color field class.
 *
 */

class Agncy_DominantColorField extends Ev_Field {

	/**
	 * Constructor for the dominant color field class.
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
		) );

		parent::__construct( $data );
	}
}

/**
 * Add the dominant color field type to the valid registered field types.
 *
 * @since 0.1.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function agncy_register_dominant_color_field_type( $types ) {
	$types['agncy_dominant_color'] = 'Agncy_DominantColorField';

	return $types;
}

add_filter( 'ev_field_types', 'agncy_register_dominant_color_field_type' );

/**
 * Path to the field template file.
 *
 * @since 1.0.0
 * @param string $template The template path.
 * @return string
 */
function agncy_dominant_color_field_template( $template ) {
    $template = AGNCY_COMPANION_PLUGIN_FOLDER . 'post-types/projects/templates/dominant_color_field.php';

    return $template;
}

add_filter( 'ev_field_template[type:agncy_dominant_color]', 'agncy_dominant_color_field_template' );