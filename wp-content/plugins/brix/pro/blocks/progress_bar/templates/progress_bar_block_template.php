<?php

$progress_data = isset( $data->data ) && isset( $data->data->data ) ? $data->data->data : array();

$value_display = isset( $data->data->show_value ) ? $data->data->show_value : '';
$animate       = isset( $data->data->animate ) ? $data->data->animate : '';

$classes = array();

if ( $value_display != 0 ) {
	$classes[] = 'brix-progress-bar-display-value';
}

if ( $animate != 0 ) {
	$classes[] = 'brix-progress-bar-animated';
}

if ( ! empty( $progress_data ) ) {
	printf( '<div class="brix-progress-bar-external-wrapper %s">', esc_attr( implode( ' ', $classes ) ) );
		foreach ( $progress_data as $data ) {
			$bar_container_style = '';
			$bar_style = '';

			if ( isset( $data->value ) && ! empty( $data->value ) ) {
				$width = $data->value;

				if ( intval( $width ) > 100 ) {
					$width = 100;
				}
				elseif ( intval( $width ) < 0 ) {
					$width = 0;
				}

				$bar_container_style = 'width:' . $width . '%';
			}

			if ( isset( $data->color ) && ! empty( $data->color ) ) {
				$bar_style = 'background-color:' . $data->color->color;
			}

			echo '<div class="brix-progress-bar-wrapper">';

				printf( '<span class="brix-progress-bar-label">%s <span class="brix-progress-bar-value">%s%%</span></span>', esc_html( $data->label ), esc_html( $data->value ) );

				echo '<span class="brix-progress-bar-line-external-wrapper">';
					printf( '<span class="brix-progress-bar-line-wrapper" style="%s">', esc_attr( $bar_container_style ) );
					printf( '<span class="brix-progress-bar-line" style="%s"></span>', esc_attr( $bar_style ) );
					echo '</span>';
				echo '</span>';

			echo '</div>';
		}

	echo '</div>';
}