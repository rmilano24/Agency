<?php

$class_name = $field->config( 'class_name' );
$class_path = $field->config( 'class_path' );
$widget_id = $field->config( 'id' );

if ( ! class_exists( $class_name ) ) {
	require_once( $class_path );
}

$widget = new $class_name();
$data = $field->value();

ob_start();
$widget->form( $data );
$markup = ob_get_contents();
ob_end_clean();

$markup = str_replace( $widget_id . '[]', 'instance', $markup );
$markup = str_replace( 'widget-instance', 'instance', $markup );

if ( strstr( $markup, '[title]' ) !== false ) {
	if ( ! isset( $data['_title_tag'] ) ) {
		$data['_title_tag'] = 'h3';
	}

	echo '<p>';
		printf( '<label>%s</label> ', esc_html__( 'Title tag:', 'brix' ) );
		brix_select( 'instance[_title_tag]', array(
			'p' => __( 'Paragraph', 'brix' ),
			'h1' => __( 'H1', 'brix' ),
			'h2' => __( 'H2', 'brix' ),
			'h3' => __( 'H3', 'brix' ),
			'h4' => __( 'H4', 'brix' ),
			'h5' => __( 'H5', 'brix' ),
			'h6' => __( 'H6', 'brix' ),
		), $data['_title_tag'] );
	echo '</p>';
}

echo $markup;