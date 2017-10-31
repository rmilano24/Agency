<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Add a breakpoint definition.
 *
 * @since 0.1.0
 * @param int $order The breakpoint order.
 * @param string $key The breakpoint key.
 * @param string $label The breakpoint label.
 * @param string $context The breakpoint context (eg. 'mobile', 'tablet' or 'desktop').
 * @param string $media_query The breakpoint associated CSS media query.
 */
function brix_add_breakpoint( $order, $key, $label, $context, $media_query = '' ) {
	brix_fw()->media()->add_breakpoint( $order, $key, $label, $context, $media_query );
}

/**
 * Return a list of the defined breakpoints.
 *
 * @since 0.1.0
 * @return array
 */
function brix_get_breakpoints() {
	return brix_fw()->media()->get_breakpoints();
}

/**
 * Return a single defined breakpoint label.
 *
 * @since 0.1.0
 * @param string $breakpoint The breakpoint key.
 * @return string
 */
function brix_get_breakpoint_label( $key ) {
	$breakpoints = brix_get_breakpoints();

	if ( isset( $breakpoints[$key] ) && isset( $breakpoints[$key]['label'] ) ) {
		return $breakpoints[$key]['label'];
	}

	return $key;
}

/**
 * Add a density definition.
 *
 * @since 0.1.0
 * @param int|float $density The density coefficient.
 * @param string $label The density label.
 */
function brix_add_density( $density, $label = '' ) {
	brix_fw()->media()->add_density( $density, $label );
}

/**
 * Return a list of the defined densities.
 *
 * @since 0.1.0
 * @return array
 */
function brix_get_densities() {
	return brix_fw()->media()->get_densities();
}

/**
 * Return a single defined density label.
 *
 * @since 0.1.0
 * @param string $density The density value.
 * @return string
 */
function brix_get_density_label( $density ) {
	$densities = brix_get_densities();

	if ( isset( $densities[$density] ) ) {
		return $densities[$density];
	}

	return false;
}