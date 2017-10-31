<?php
$accordion     = isset( $data->data ) && isset( $data->data->accordion ) && isset( $data->data->accordion->toggle ) ? $data->data->accordion->toggle : array();
$open          = isset( $data->data ) && isset( $data->data->accordion ) && isset( $data->data->accordion->open ) && $data->data->accordion->open !== '' ? $data->data->accordion->open : false;
$mode          = isset( $data->data ) && isset( $data->data->accordion ) && isset( $data->data->accordion->mode ) ? $data->data->accordion->mode : '';
$icon_position = isset( $data->data ) && isset( $data->data->accordion->icon_position ) ? $data->data->accordion->icon_position : '';

if ( ! empty( $accordion ) ) {
	$accordion = json_decode( $accordion, TRUE );

	if ( $open !== false ) {
		$open = explode( ',', $open );
		$open = array_map( 'trim', $open );
	}

	printf( '<div class="brix-accordion brix-component" role="tablist" data-mode="%s">', esc_attr( $mode ) );

		foreach ( $accordion as $index => $toggle ) {
			if ( empty( $toggle['title'] ) || empty( $toggle['content'] ) ) {
				continue;
			}

			$is_selected = false;

			if ( $open !== false ) {
				if ( $mode === 'toggle' ) {
					$is_selected = in_array( (string) $index, $open );
				}
				else {
					$is_selected = $index == $open[0];
				}
			}

			$toggle_title = $toggle['title'];


			if ( isset( $toggle['decoration'] ) ) {
				$decoration_html = brix_get_decoration( $toggle['decoration'] );

				if ( ! empty( $decoration_html ) ) {
					if ( $icon_position == 'left' ) {
						$toggle_title = $decoration_html . ' ' . $toggle_title;
					} else if ( $icon_position == 'right' ) {
						$toggle_title = $toggle_title . ' ' . $decoration_html;
					}
				}
			}

			$toggle_class = $is_selected ? 'brix-active' : '';
			$toggle_attrs = $is_selected ? '' : 'aria-hidden=true';

			printf( '<div class="brix-toggle %s" %s>', esc_attr( $toggle_class ), esc_attr( $toggle_attrs ) );
				$attrs = $is_selected ? 'aria-selected=true' : '';

				printf( '<div role="tab" id="brix-accordion-%s" class="brix-toggle-trigger brix-toggle-icon-position-%s" aria-controls="brix-accordion-panel-%s" %s>',
					esc_attr( $index ),
					esc_attr( $icon_position ),
					esc_attr( $index ),
					esc_attr( $attrs )
				);
					do_action( 'brix_accordion_before_toggle_trigger_label', $data );
					echo $toggle_title;
				echo '</div>';

				printf( '<div class="brix-toggle-content" role="tabpanel" aria-labelledby="brix-accordion-%s">%s</div>',
					esc_attr( $index ),
					brix_format_text_content( $toggle['content'] )
				);
			echo '</div>';
		}

	echo '</div>';
}