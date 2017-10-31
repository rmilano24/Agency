<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Breakpoints field class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBreakpointsField extends Brix_Field {
	/**
	 * Constructor for the breakpoints field class.
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
 * Add the breakpoints field type to the valid registered field types.
 *
 * @since 1.0.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_builder_breakpoints_field_type( $types ) {
	$types['builder_breakpoints'] = 'BrixBreakpointsField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_builder_breakpoints_field_type' );

/**
 * Connect the backend field template file.
 *
 * @since 1.0.0
 * @param string $template The path to the template file.
 * @return string
 */
function brix_breakpoints_field_template( $template ) {
	return BRIX_TEMPLATES_FOLDER . 'fields/builder_breakpoints';
}

add_filter( 'brix_field_template[type:builder_breakpoints]', 'brix_breakpoints_field_template' );

/**
 * Render an breakpoint modal window.
 *
 * @since 1.0.0
 */
function brix_breakpoint_modal_load() {
	$key = 'brix_add_new_breakpoint';
	$data = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? $_POST['data'] : array();

	$fields = array();

	$fields[] = array(
		'type' => 'group',
		'handle' => '_default',
		'label' => __( 'Options', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'label',
				'label'  => _x( 'Label', 'breakpoint label', 'brix' ),
				'help' => __( 'The human readable name of the breakpoint; be brief yet descriptive.', 'brix' ),
				'type'   => 'text',
				'config' => array(
					'full' => true
				)
			),
			array(
				'handle' => 'context',
				'label'  => _x( 'Context', 'breakpoint context', 'brix' ),
				'help' => __( 'Breakpoint category, doesn\'t affect the display.', 'brix' ),
				'type'   => 'select',
				'config' => array(
					'data' => array(
						'desktop' => __( 'Desktop', 'brix' ),
						'tablet' => __( 'Tablet', 'brix' ),
						'mobile' => __( 'Mobile', 'brix' ),
					)
				)
			),
			array(
				'handle' => 'media_query',
				'label'  => _x( 'Media query', 'breakpoint media query', 'brix' ),
				'help' => sprintf(
					__( 'Learn more about <a href="%s">CSS Media Queries</a>.', 'brix' ),
					esc_attr( 'https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries' )
				),
				'type'   => 'text',
				'config' => array(
					'full' => true
				)
			),
			array(
				'handle' => 'gutter',
				'label'  => _x( 'Gutter', 'breakpoint gutter', 'brix' ),
				'type'   => 'text',
				'help'	 => __( 'The gutter is the <strong>horizontal</strong> space between columns. Please note that it is not recommended to express the gutter value in percentage.', 'brix' ),
			),
			array(
				'handle' => 'baseline',
				'label'  => _x( 'Baseline', 'breakpoint baseline', 'brix' ),
				'type'   => 'text',
				'help'	 => __( 'The baseline is the <strong>vertical</strong> space between rows.', 'brix' ),
			),
		)
	);

	$m = new Brix_Modal( $key, $fields, $data, array(
		'title' => __( 'Breakpoint', 'brix' )
	) );

	$m->render();

	die();
}

add_action( 'wp_ajax_brix_breakpoint_modal_load', 'brix_breakpoint_modal_load' );

/**
 * Print localization strings.
 *
 * @since 1.0.0
 */
function brix_breakpoints_i18n_strings() {
	wp_localize_script( 'jquery', 'brix_breakpoints_i18n_strings', array(
		'title' => __( 'Breakpoint', 'brix' ),
		'reset' => _x( 'Reset', 'reset breakpoint', 'brix' ),
		'remove' => _x( 'Remove', 'remove breakpoint', 'brix' ),
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_breakpoints_i18n_strings' );

/**
 * Add custom breakpoints management.
 *
 * @since 1.0.0
 * @param array $breakpoints An array of breakpoints.
 * @return array
 */
function brix_custom_breakpoints( $breakpoints ) {
	$custom_breakpoints = brix_get_option( 'responsive_breakpoints' );

	if ( isset( $custom_breakpoints['breakpoints'] ) ) {
		$custom_breakpoints = json_decode( $custom_breakpoints['breakpoints'], true );

		if ( ! empty( $custom_breakpoints ) ) {
			foreach ( $custom_breakpoints as $id => $breakpoint ) {
				// $breakpoint['builder'] = 1;
				$breakpoints[$id] = $breakpoint;
			}
		}
	}

	return $breakpoints;
}

add_filter( 'brix_breakpoints', 'brix_custom_breakpoints' );