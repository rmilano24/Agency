<?php

/**
 * Add data attributes depending on the selected entrance effect.
 *
 * @since 1.0.0
 * @param array $attrs A list of data attributes.
 * @param object $data The object data.
 * @return array
 */
function agncy_effects_data_attrs( $attrs, $data ) {
	if ( isset( $data->agncy_fx ) && $data->agncy_fx ) {
		$attrs[] = 'data-agncy-effect=' . $data->agncy_fx;
	}

	if ( isset( $data->agncy_fx_animate_svg ) && $data->agncy_fx_animate_svg ) {
		$attrs[] = 'data-agncy-icon-animate=1';
	}

	return $attrs;
}

add_filter( 'brix_block_attrs', 'agncy_effects_data_attrs', 10, 2 );
add_filter( 'brix_section_column_data_attrs', 'agncy_effects_data_attrs', 10, 2 );

/**
 * Add data attributes depending on the selected entrance effect.
 * @since 1.0.0
 * @param array $attrs A list of data attributes.
 * @param object $data The object data.
 * @return array
 */
function agncy_effects_section_data_attrs( $attrs, $data ) {
	if ( isset( $data->data->agncy_fx ) && $data->data->agncy_fx ) {
		$attrs[] = 'data-agncy-effect=' . $data->data->agncy_fx;
	}

	return $attrs;
}

add_filter( 'brix_section_attrs', 'agncy_effects_section_data_attrs', 10, 2 );

/**
 * Get the list of fields that compose the fx functionality.
 *
 * @since 1.0.0
 * @param string $context The context string
 * @return array
 */
function agncy_brix_styles_get_fx() {
	$fx_fields = array();
	$fx_fields[] = array(
		'type' => 'divider',
		'text' => __( 'Effects', 'agncy' ),
		'config' => array(
			'style' => 'in_page',
		)
	);

	$effects = array(
		''                  => __( 'No effect', 'agncy' ),
		'bounceIn'          => __( 'Bounce In', 'agncy' ),
		'bounceInDown'      => __( 'Bounce In Down', 'agncy' ),
		'bounceInLeft'      => __( 'Bounce In Left', 'agncy' ),
		'bounceInRight'     => __( 'Bounce In Right', 'agncy' ),
		'bounceInUp'        => __( 'Bounce In Up', 'agncy' ),
		'fadeIn'            => __( 'Fade In', 'agncy' ),
		'fadeInDown'        => __( 'Fade In Down', 'agncy' ),
		'fadeInDownBig'     => __( 'Fade In Down Big', 'agncy' ),
		'fadeInLeft'        => __( 'Fade In Left', 'agncy' ),
		'fadeInLeftBig'     => __( 'Fade In Left Big', 'agncy' ),
		'fadeInRight'       => __( 'Fade In Right', 'agncy' ),
		'fadeInRightBig'    => __( 'Fade In Right Big', 'agncy' ),
		'fadeInUp'          => __( 'Fade In Up', 'agncy' ),
		'fadeInUpBig'       => __( 'Fade In Up Big', 'agncy' ),
		'flipInX'           => __( 'Flip In X', 'agncy' ),
		'flipInY'           => __( 'Flip In Y', 'agncy' ),
		'lightSpeedIn'      => __( 'Light Speed In', 'agncy' ),
		'rotateIn'          => __( 'Rotate In', 'agncy' ),
		'rotateInDownLeft'  => __( 'Rotate In Down Left', 'agncy' ),
		'rotateInDownRight' => __( 'Rotate In Down Right', 'agncy' ),
		'rotateInUpLeft'    => __( 'Rotate In Up Left', 'agncy' ),
		'rotateInUpRight'   => __( 'Rotate In Up Right', 'agncy' ),
		'rollIn'            => __( 'Roll In',  'agncy' ),
		'zoomIn'            => __( 'Zoom In', 'agncy' ),
		'zoomInDown'        => __( 'Zoom In Down', 'agncy' ),
		'zoomInLeft'        => __( 'Zoom In Left', 'agncy' ),
		'zoomInRight'       => __( 'Zoom In Right', 'agncy' ),
		'zoomInUp'          => __( 'Zoom In Up', 'agncy' ),
		'slideInDown'       => __( 'Slide In Down', 'agncy' ),
		'slideInLeft'       => __( 'Slide In Left', 'agncy' ),
		'slideInRight'      => __( 'Slide In Right', 'agncy' ),
		'slideInUp'         => __( 'Slide In Up', 'agncy' ),
		'bounce'            => __( 'Bounce', 'agncy' ),
		'flash'             => __( 'Flash', 'agncy' ),
		'pulse'             => __( 'Pulse', 'agncy' ),
		'rubberBand'        => __( 'Rubber Band', 'agncy' ),
		'shake'             => __( 'Shake', 'agncy' ),
		'headShake'         => __( 'Head Shake', 'agncy' ),
		'swing'             => __( 'Swing', 'agncy' ),
		'tada'              => __( 'Tada', 'agncy' ),
		'wobble'            => __( 'Wobble', 'agncy' ),
		'jello'             => __( 'Jello', 'agncy' ),
	);

	$fx_fields[] = array(
		'handle' => 'agncy_fx',
		'label' => __( 'Effect', 'agncy' ),
		'help' => __( 'The entrance effect when the element will become visible in the viewport.', 'agncy' ),
		'type' => 'select',
		'config' => array(
			'controller' => true,
			'data' => $effects
		)
	);

	return $fx_fields;
}

add_filter( 'brix_styles_get_fx', 'agncy_brix_styles_get_fx' );

/**
 * Add the animate svg icon field option
 *
 * @since 1.0.0
 * @param  array $fx_fields The array of fields.
 * @return array
 */
function agncy_fx_animate_svg_field( $fx_fields ) {
	$fx_fields[] = array(
		'handle' => 'agncy_fx_animate_svg',
		'label' => __( 'Animate SVG icons', 'agncy' ),
		'help' => __( 'Attempt to animate SVG icons (only works with icons built with the <code>stroke</code> attribute applied on paths).', 'agncy' ),
		'type' => 'checkbox',
		'config' => array(
			'style' => array( 'switch', 'small' )
		),
		'default' => '1'
	);

	return $fx_fields;
}

add_filter( 'brix_styles_get_fx[context:block][type:feature_box]', 'agncy_fx_animate_svg_field' );
add_filter( 'brix_styles_get_fx[context:block][type:icon]', 'agncy_fx_animate_svg_field' );