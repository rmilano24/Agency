<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Accordion field class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Brix_AccordionField extends Brix_Field {

	/**
	 * Constructor for the accordion field class.
	 *
	 * @since 1.0.0
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = array();
		}

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array();
		}

		parent::__construct( $data );
	}
}

/**
 * Add the accordion field type to the valid registered field types.
 *
 * @since 1.0.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_accordion_field_type( $types ) {
	$types['accordion'] = 'Brix_AccordionField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_accordion_field_type' );

/**
 * Connect the backend field template file.
 *
 * @since 1.0.0
 * @param string $template The path to the template file.
 * @return string
 */
function brix_accordion_field_template( $template ) {
	return BRIX_TEMPLATES_FOLDER . 'fields/accordion';
}

add_filter( 'brix_field_template[type:accordion]', 'brix_accordion_field_template' );

/**
 * Render an accordion modal window.
 *
 * @since 1.0.0
 */
function brix_toggle_modal_load() {
	$key = 'brix_add_new_accordion';
	$data = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? $_POST['data'] : array();

	$fields = array();

	$fields[] = array(
		'type' => 'group',
		'handle' => '_default',
		'label' => __( 'Options', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'title',
				'label'  => __( 'Title', 'brix' ),
				'type'   => 'text',
				'config' => array(
					'full' => true
				)
			),
			array(
				'handle' => 'content',
				'label'  => __( 'Content', 'brix' ),
				'type'   => 'textarea',
				'config' => array(
					'rich' => true,
					'rows' => 8
				)
			),
			brix_icon_bundle( 'decoration', __( 'Decoration', 'brix' ) ),
		)
	);

	$m = new Brix_Modal( $key, $fields, $data, array(
		'title' => __( 'Accordion', 'brix' )
	) );

	$m->render();

	die();
}

add_action( 'wp_ajax_brix_toggle_modal_load', 'brix_toggle_modal_load' );
