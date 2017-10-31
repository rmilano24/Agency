<?php
$style = $field->config( 'style' );
$data = $field->config( 'data' );

if ( $field->_responsive ) {
	$default_data = array();

	if ( $breakpoint !== 'desktop' ) {
		$default_data = array(
			'' => __( 'Inherit', 'brix' )
		);
	}

	$data = array_merge(
		$default_data,
		$field->config( 'data' )
	);
}

brix_select( $handle, $data, $value, $style );