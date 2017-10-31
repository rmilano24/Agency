<?php

$widget_area = isset( $data->data->widget_area ) ? $data->data->widget_area : false;

if ( is_active_sidebar( $widget_area ) ) {
	echo '<div class="widget-area" role="complementary">';
		dynamic_sidebar( $widget_area );
	echo '</div>';
}