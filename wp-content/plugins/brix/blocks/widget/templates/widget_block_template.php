<?php

$data = isset( $data->data->instance ) ? json_decode( json_encode( $data->data->instance ), true ) : array();

$class_name = $block->getWidgetClassName();
$class_path = $block->getWidgetClassPath();
$widget_id  = $block->getWidgetId();

if ( ! class_exists( $class_name ) ) {
	require_once( $class_path );
}

$widget = new $class_name();

$before_title = isset( $data['_title_tag'] ) ? '<' . $data['_title_tag'] . ' class="widget-title">' : 'h3';
$after_title = isset( $data['_title_tag'] ) ? '</' . $data['_title_tag'] . '>' : 'h3';

ob_start();
$widget->widget( array(
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => $before_title,
	'after_title'   => $after_title,
	'widget_id'     => $widget_id
), $data );
$markup = ob_get_contents();
ob_end_clean();

echo $markup;