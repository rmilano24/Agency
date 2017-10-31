<?php

/**
 * Return the footer markup.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_footer_layout() {
	$footer_presets = '';
	$footer_layout  = '';
	$footer         = '';

	if ( function_exists( 'ev_get_option' ) ) {
		$footer_presets = ev_get_option( 'footer_presets' );
		$footer_layout  = ev_get_option( 'footer_layout' );
	}

	if ( $footer_presets == 'columns' ) {
		if ( $footer_layout != 'disabled' ) {
			$footer = '<div class="agncy-f-w_i">';

				$footer_layout = explode( '|', $footer_layout );

				foreach ( $footer_layout as $i => $col ) {

					$footer .= '<div class="agncy-f-c agncy-f-c_' . esc_attr( $col ) . '">';
						$widget_area = ev_get_option( 'footer_widget_area_col_' . ( $i+1 ) );

						ob_start();
						dynamic_sidebar( $widget_area );
						$footer .= ob_get_contents();
						ob_end_clean();

					$footer .= '</div>';

				}

			$footer .= '</div>';
		}
	} else {
		get_template_part( 'templates/footer/presets/footer-' . $footer_presets );
	}

	return $footer;
}