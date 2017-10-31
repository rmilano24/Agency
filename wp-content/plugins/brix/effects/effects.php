<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Add the effects group in the column editing modal.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function brix_add_column_effects( $fields ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'column_fx',
		'label' => __( 'Effects', 'brix' ),
		'fields' => brix_styles_get_fx( 'column' )
	);

	return $fields;
}

add_filter( 'brix_column', 'brix_add_column_effects' );

/**
 * Add the effects group in the section editing modal.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @return array
 */
function brix_add_section_effects( $fields ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'section_fx',
		'label' => __( 'Effects', 'brix' ),
		'fields' => brix_styles_get_fx( 'section' )
	);

	return $fields;
}

add_filter( 'brix_section', 'brix_add_section_effects' );

/**
 * Add the effects group in the block editing modal.
 *
 * @since 1.0.0
 * @param array $fields An array of fields.
 * @param object $type The block type.
 * @return array
 */
function brix_styles_add_effect_field( $fields, $type ) {
	$fields[] = array(
		'type' => 'group',
		'handle' => 'block_fx',
		'label' => __( 'Effects', 'brix' ),
		'fields' => brix_styles_get_fx( 'block', $type )
	);

	return $fields;
}

add_filter( 'brix_block', 'brix_styles_add_effect_field', 10, 2 );

/**
 * Get the list of fields that compose the fx functionality.
 *
 * @since 1.0.0
 * @param string $context The context string.
 * @param object $type The object type.
 * @return array
 */
function brix_styles_get_fx( $context = '', $type = false ) {
	$fx_fields = array();

	$fx_fields[] = array(
		'type' => 'divider',
		'text' => __( 'Effects', 'brix' ),
		'config' => array(
			'style' => 'in_page',
		)
	);

	$fx_fields[] = array(
		'handle' => 'fx',
		'label' => __( 'Effect', 'brix' ),
		'help' => __( 'The entrance effect when the element will become visible in the viewport.', 'brix' ),
		'type' => 'select',
		'config' => array(
			'controller' => true,
			'data' => array(
				''                        => __( 'No effect', 'brix' ),
				'brix-fade'         => __( 'Fade in', 'brix' ),
				'brix-slide-top'    => __( 'Fade and slide from top', 'brix' ),
				'brix-slide-right'  => __( 'Fade and slide from right', 'brix' ),
				'brix-slide-bottom' => __( 'Fade and slide from bottom', 'brix' ),
				'brix-slide-left'   => __( 'Fade and slide from left', 'brix' ),
			)
		)
	);

	$fx_fields[] = array(
		'handle' => 'fx_speed',
		'label' => __( 'Transition speed', 'brix' ),
		'help' => __( 'Expressed in seconds.', 'brix' ),
		'type' => 'number',
		'config' => array(
			'min' => 0,
			'visible' => array( 'fx' => array(
				'brix-fade',
				'brix-slide-top',
				'brix-slide-right',
				'brix-slide-bottom',
				'brix-slide-left'
			) )
		)
	);

	$fx_fields[] = array(
		'handle' => 'fx_delay',
		'label' => __( 'Transition delay', 'brix' ),
		'help' => __( 'Expressed in seconds.', 'brix' ),
		'type' => 'number',
		'config' => array(
			'min' => 0,
			'visible' => array( 'fx' => array(
				'brix-fade',
				'brix-slide-top',
				'brix-slide-right',
				'brix-slide-bottom',
				'brix-slide-left'
			) )
		)
	);

	$fx_fields[] = array(
		'handle' => 'fx_easing',
		'label' => __( 'Easing', 'brix' ),
		'help' => __( 'The easing of the transition effect.', 'brix' ),
		'type' => 'select',
		'config' => array(
			'data' => array(
				'linear'            => __( 'Linear', 'brix' ),
				'ease'              => __( 'Ease', 'brix' ),
				'ease-in-quad'      => __( 'Ease-in-quad', 'brix' ),
				'ease-in-cubic'     => __( 'Ease-in-cubic', 'brix' ),
				'ease-in-quart'     => __( 'Ease-in-quart', 'brix' ),
				'ease-in-quint'     => __( 'Ease-in-quint', 'brix' ),
				'ease-in-sine'      => __( 'Ease-in-sine', 'brix' ),
				'ease-in-expo'      => __( 'Ease-in-expo', 'brix' ),
				'ease-in-circ'      => __( 'Ease-in-circ', 'brix' ),
				'ease-in-back'      => __( 'Ease-in-back', 'brix' ),
				'ease-out-quad'     => __( 'Ease-out-quad', 'brix' ),
				'ease-out-cubic'    => __( 'Ease-out-cubic', 'brix' ),
				'ease-out-quart'    => __( 'Ease-out-quart', 'brix' ),
				'ease-out-quint'    => __( 'Ease-out-quint', 'brix' ),
				'ease-out-sine'     => __( 'Ease-out-sine', 'brix' ),
				'ease-out-expo'     => __( 'Ease-out-expo', 'brix' ),
				'ease-out-circ'     => __( 'Ease-out-circ', 'brix' ),
				'ease-out-back'     => __( 'Ease-out-back', 'brix' ),
				'ease-in-out-quad'  => __( 'Ease-in-out-quad', 'brix' ),
				'ease-in-out-cubic' => __( 'Ease-in-out-cubic', 'brix' ),
				'ease-in-out-quart' => __( 'Ease-in-out-quart', 'brix' ),
				'ease-in-out-quint' => __( 'Ease-in-out-quint', 'brix' ),
				'ease-in-out-sine'  => __( 'Ease-in-out-sine', 'brix' ),
				'ease-in-out-expo'  => __( 'Ease-in-out-expo', 'brix' ),
				'ease-in-out-circ'  => __( 'Ease-in-out-circ', 'brix' ),
				'ease-in-out-back'  => __( 'Ease-in-out-back', 'brix' )
			),
			'visible' => array( 'fx' => array(
				'brix-fade',
				'brix-slide-top',
				'brix-slide-right',
				'brix-slide-bottom',
				'brix-slide-left'
			) )
		)
	);

	$fx_fields = apply_filters( 'brix_styles_get_fx', $fx_fields );

	if ( ! empty( $context ) ) {
		$fx_fields = apply_filters( "brix_styles_get_fx[context:$context]", $fx_fields );

		if ( $context === 'block' && $type ) {
			$fx_fields = apply_filters( "brix_styles_get_fx[context:block][type:$type]", $fx_fields );
		}
	}

	return $fx_fields;
}

/**
 * Get the CSS style for the easing effect.
 *
 * @since 1.0.0
 * @param string $selector The CSS selector.
 * @param string $fx_easing The fx easing object.
 * @return string
 */
function brix_styles_get_fx_easing_style( $selector, $fx_easing ) {
	if ( $fx_easing == 'ease-in-quad' ) { $fx_easing = 'cubic-bezier(0.550,  0.085, 0.680, 0.530)'; }
	elseif ( $fx_easing == 'ease-in-cubic' ) { $fx_easing = 'cubic-bezier(0.550,  0.055, 0.675, 0.190)'; }
	elseif ( $fx_easing == 'ease-in-quart' ) { $fx_easing = 'cubic-bezier(0.895,  0.030, 0.685, 0.220)'; }
	elseif ( $fx_easing == 'ease-in-quint' ) { $fx_easing = 'cubic-bezier(0.755,  0.050, 0.855, 0.060)'; }
	elseif ( $fx_easing == 'ease-in-sine' ) { $fx_easing = 'cubic-bezier(0.470,  0.000, 0.745, 0.715)'; }
	elseif ( $fx_easing == 'ease-in-expo' ) { $fx_easing = 'cubic-bezier(0.950,  0.050, 0.795, 0.035)'; }
	elseif ( $fx_easing == 'ease-in-circ' ) { $fx_easing = 'cubic-bezier(0.600,  0.040, 0.980, 0.335)'; }
	elseif ( $fx_easing == 'ease-in-back' ) { $fx_easing = 'cubic-bezier(0.600, -0.280, 0.735, 0.045)'; }
	elseif ( $fx_easing == 'ease-out-quad' ) { $fx_easing = 'cubic-bezier(0.250,  0.460, 0.450, 0.940)'; }
	elseif ( $fx_easing == 'ease-out-cubic' ) { $fx_easing = 'cubic-bezier(0.215,  0.610, 0.355, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-quart' ) { $fx_easing = 'cubic-bezier(0.165,  0.840, 0.440, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-quint' ) { $fx_easing = 'cubic-bezier(0.230,  1.000, 0.320, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-sine' ) { $fx_easing = 'cubic-bezier(0.390,  0.575, 0.565, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-expo' ) { $fx_easing = 'cubic-bezier(0.190,  1.000, 0.220, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-circ' ) { $fx_easing = 'cubic-bezier(0.075,  0.820, 0.165, 1.000)'; }
	elseif ( $fx_easing == 'ease-out-back' ) { $fx_easing = 'cubic-bezier(0.175,  0.885, 0.320, 1.275)'; }
	elseif ( $fx_easing == 'ease-in-out-quad' ) { $fx_easing = 'cubic-bezier(0.455,  0.030, 0.515, 0.955)'; }
	elseif ( $fx_easing == 'ease-in-out-cubic' ) { $fx_easing = 'cubic-bezier(0.645,  0.045, 0.355, 1.000)'; }
	elseif ( $fx_easing == 'ease-in-out-quart' ) { $fx_easing = 'cubic-bezier(0.770,  0.000, 0.175, 1.000)'; }
	elseif ( $fx_easing == 'ease-in-out-quint' ) { $fx_easing = 'cubic-bezier(0.860,  0.000, 0.070, 1.000)'; }
	elseif ( $fx_easing == 'ease-in-out-sine' ) { $fx_easing = 'cubic-bezier(0.445,  0.050, 0.550, 0.950)'; }
	elseif ( $fx_easing == 'ease-in-out-expo' ) { $fx_easing = 'cubic-bezier(1.000,  0.000, 0.000, 1.000)'; }
	elseif ( $fx_easing == 'ease-in-out-circ' ) { $fx_easing = 'cubic-bezier(0.785,  0.135, 0.150, 0.860)'; }
	elseif ( $fx_easing == 'ease-in-out-back' ) { $fx_easing = 'cubic-bezier(0.680, -0.550, 0.265, 1.550)'; }

	$fx_easing_style = $selector . '{';
		$fx_easing_style .= '-webkit-transition-timing-function:' . $fx_easing . ';';
		$fx_easing_style .= '-moz-transition-timing-function:' . $fx_easing . ';';
		$fx_easing_style .= '-o-transition-timing-function:' . $fx_easing . ';';
		$fx_easing_style .= 'transition-timing-function:' . $fx_easing . ';';
	$fx_easing_style .= '}';

	return $fx_easing_style;
}

/**
 * Print the CSS styles that produce the entrance effects.
 *
 * @since 1.0.0
 * @param string $selector The CSS selector.
 * @param stdClass $data The builder data.
 */
function brix_fx_styles( $selector, $data ) {
	if ( ! isset( $data->fx ) || empty( $data->fx ) ) {
		return;
	}

	/* FX speed style */
	if ( isset( $data->fx_speed ) && ! empty( $data->fx_speed ) ) {
		$fx_speed_style = '';
		$fx_speed = $data->fx_speed;

		if ( ! empty( $fx_speed ) ) {
			$fx_speed_style = $selector . '{';
				$fx_speed_style .= '-webkit-transition-duration:' . $fx_speed . 's;';
				$fx_speed_style .= '-moz-transition-duration:' . $fx_speed . 's;';
				$fx_speed_style .= '-o-transition-duration:' . $fx_speed . 's;';
				$fx_speed_style .= 'transition-duration:' . $fx_speed . 's;';
			$fx_speed_style .= '}';
		}

		brix_fw()->frontend()->add_inline_style( $fx_speed_style );
	}

	/* FX delay style */
	if ( isset( $data->fx_delay ) && ! empty( $data->fx_delay ) ) {
		$fx_delay_style = '';
		$fx_delay = $data->fx_delay;

		if ( ! empty( $fx_delay ) ) {
			$fx_delay_style = $selector . '{';
				$fx_delay_style .= '-webkit-transition-delay:' . $fx_delay . 's;';
				$fx_delay_style .= '-moz-transition-delay:' . $fx_delay . 's;';
				$fx_delay_style .= '-o-transition-delay:' . $fx_delay . 's;';
				$fx_delay_style .= 'transition-delay:' . $fx_delay . 's;';
			$fx_delay_style .= '}';
		}

		brix_fw()->frontend()->add_inline_style( $fx_delay_style );
	}

	/* Easing */
	if ( isset( $data->fx_easing ) && ! empty( $data->fx_easing ) ) {
		$fx_easing_style = '';
		$fx_easing = $data->fx_easing;

		if ( ! empty( $fx_easing ) ) {
			$fx_easing_style = brix_styles_get_fx_easing_style( $selector, $fx_easing );
		}

		brix_fw()->frontend()->add_inline_style( $fx_easing_style );
	}
}

/**
 * Print the CSS classes that produce the entrance effects.
 *
 * @since 1.0.0
 * @param stdClass $data The builder data.
 */
function brix_fx_classes( &$data ) {
	if ( isset( $data->fx ) ) {
		$fx = $data->fx;

		if ( ! empty( $fx ) ) {
			$data->class[] = $fx;
			$data->class[] = 'brix-entrance-effect';

			if ( $fx != 'brix-fade' ) {
				$data->class[] = 'brix-slide-effect';
			}
		}
	}
}

/**
 * Process the builder block on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $block The block object.
 * @param integer $count The block count index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_styles_process_frontend_block_effects( $block, $count, $selector_prefix = false ) {
	$block_selector = sprintf( '.brix-section-column-block-%s', $count );

	/* Column data. */
	$block->data = isset( $block->data ) && ! empty( $block->data ) ? $block->data : new stdClass();
	$block->data->class = isset( $block->data->class ) && ! empty( $block->data->class ) ? (array) $block->data->class : array();

	if ( $selector_prefix !== false ) {
		$block_selector = $selector_prefix . ' ' . $block_selector;
	}

	/* FX styles. */
	brix_fx_styles( $block_selector, $block->data );

	/* FX classes. */
	brix_fx_classes( $block->data );

	return $block;
}

add_filter( 'brix_process_frontend_block', 'brix_styles_process_frontend_block_effects', 10, 3 );

/**
 * Process the builder column on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $column The column object.
 * @param integer $count The column count index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_styles_process_frontend_column_effects( $column, $count, $selector_prefix = false ) {
	$column_selector = sprintf( '.brix-section-column-%s', $count );

	/* Column data. */
	$column->data = isset( $column->data ) && ! empty( $column->data ) ? $column->data : new stdClass();
	$column->data->class = isset( $column->data->class ) && ! empty( $column->data->class ) ? (array) $column->data->class : array();

	if ( $selector_prefix !== false ) {
		$column_selector = $selector_prefix . ' ' . $column_selector;
	}

	/* FX styles. */
	brix_fx_styles( $column_selector, $column->data );

	/* FX classes. */
	brix_fx_classes( $column->data );

	return $column;
}

add_filter( 'brix_process_frontend_column', 'brix_styles_process_frontend_column_effects', 10, 3 );

/**
 * Process the builder section on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $section The section object.
 * @param integer $index The section index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_styles_process_frontend_section_effects( $section, $index, $selector_prefix = false ) {
	$section_selector = sprintf( '.brix-section-%s', $index );
	$section_width    = isset( $section->data->data->section_width ) ? $section->data->data->section_width : 'boxed';

	if ( $selector_prefix !== false ) {
		$section_selector = $selector_prefix . ' ' . $section_selector;
	}

	/* Section setup. */
	$section->data = isset( $section->data ) && ! empty( $section->data ) ? $section->data : new stdClass();

	if ( empty( $section->data->data ) ) {
		$section->data->data = new stdClass();
	}

	$section->data->data->class = isset( $section->data->data->class ) && ! empty( $section->data->data->class ) ? (array) $section->data->data->class : array();

	/* FX styles. */
	brix_fx_styles( $section_selector, $section->data->data );

	/* FX classes. */
	brix_fx_classes( $section->data->data );

	return $section;
}

add_filter( 'brix_process_frontend_section', 'brix_styles_process_frontend_section_effects', 10, 3 );