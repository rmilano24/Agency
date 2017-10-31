<?php

/**
 * Add support for line breaks in text widgets.
 *
 * @since 1.0.0
 * @param string $widget_text The widget text.
 * @param array $instance The widget configuration.
 * @param WP_Widget $widget The widget instance.
 * @return string
 */
function agncy_widget_text( $widget_text, $instance ) {
	if ( isset( $instance[ 'filter' ] ) && ! $instance[ 'filter' ] ) {
		$widget_text = nl2br( $widget_text );
	}

	return $widget_text;
}

add_filter( 'widget_text', 'agncy_widget_text', 10, 2 );
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Setting the default behavior for sidebars when the required plugins are not
 * active.
 *
 * @since 1.0.0
 * @param array $sidebar An array of sidebar data.
 * @return array
 */
function agncy_default_sidebar_behavior( $sidebar ) {
	if ( ! function_exists( 'ev_fw' ) || ! function_exists( 'agncy_register_sidebars' ) ) {
		$sidebar[ 'position' ] = 'right';

		if ( is_active_sidebar( 'main-sidebar' ) ) {
			$sidebar[ 'sidebar' ] = 'main-sidebar';
		}
	}

	return $sidebar;
}

add_filter( 'agncy_get_page_sidebar', 'agncy_default_sidebar_behavior' );