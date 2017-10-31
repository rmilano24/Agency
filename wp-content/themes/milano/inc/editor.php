<?php

/**
 * Custom formats to be used in TinyMCE.
 *
 * @since 1.0.0
 * @param array $formats An array of formats.
 * @return array
 */
function agncy_ev_editr_formats( $formats ) {

	// Text

	$formats[] = array(
		'text' => __( 'Big text', 'agncy' ),
		'class' => 'agncy-t-big',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Small text', 'agncy' ),
		'class' => 'agncy-t-small',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Smaller text', 'agncy' ),
		'class' => 'agncy-t-smaller',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Light text', 'agncy' ),
		'class' => 'agncy-t-light',
		'type' => 'inline'
	);

	// Quote

	$formats[] = array(
		'text' => __( 'Pull quote left', 'agncy' ),
		'class' => 'agncy-pullquote-left',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Pull quote right', 'agncy' ),
		'class' => 'agncy-pullquote-right',
		'type' => 'inline'
	);

	// Links

	$formats[] = array(
		'text' => __( 'Strikethrough link', 'agncy' ),
		'class' => 'agncy-strikethrough',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Strikethrough simple link', 'agncy' ),
		'class' => 'agncy-strikethrough-simple',
		'type' => 'inline'
	);

	$formats[] = array(
		'text' => __( 'Button link', 'agncy' ),
		'class' => 'agncy-button',
		'type' => 'inline'
	);

	// Divider

	$formats[] = array(
		'text' => __( 'Divider full width', 'agncy' ),
		'class' => 'agncy-divider-sz-full',
		'type' => 'block'
	);

	$formats[] = array(
		'text' => __( 'Divider medium width', 'agncy' ),
		'class' => 'agncy-divider-sz-medium',
		'type' => 'block'
	);

	$formats[] = array(
		'text' => __( 'Divider small width', 'agncy' ),
		'class' => 'agncy-divider-sz-small',
		'type' => 'block'
	);

	// General

	$formats[] = array(
		'text' => __( 'Split paragraph', 'agncy' ),
		'class' => 'agncy-text-2-cols',
		'type' => 'block'
	);

	return $formats;
}

add_filter( 'ev_editr_formats', 'agncy_ev_editr_formats' );