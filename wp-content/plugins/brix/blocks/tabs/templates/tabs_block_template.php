<?php
$tabs = isset( $data->data ) && isset( $data->data->tabs ) && isset( $data->data->tabs->tabs ) ? $data->data->tabs->tabs : array();
$tabs_nav_alignment = isset( $data->data ) && isset( $data->data->tabs->tabs_nav_alignment ) ? $data->data->tabs->tabs_nav_alignment : '';

if ( ! empty( $tabs ) ) {
	$orientation = isset( $data->data->tabs->orientation ) ? $data->data->tabs->orientation : '';
	$tabs = json_decode( $tabs, TRUE );

	$tabs_class = array(
		'brix-tabs',
		'brix-component',
		'brix-tabs-' . $orientation
	);

	printf( '<div class="%s">', esc_attr( implode( ' ', apply_filters( 'brix_tabs_block_class', $tabs_class ) ) ) );

		printf( '<ul class="brix-tabs-nav brix-nav-alignment-%s brix-%s" role="tablist">', esc_attr( $tabs_nav_alignment ), esc_attr( $orientation ) );
			foreach ( $tabs as $index => $tab ) {
				if ( empty( $tab['content'] ) ) {
					continue;
				}

				$tab_title = $tab['title'];
				$icon_position = isset( $tab['icon_position'] ) && ! empty( $tab['icon_position'] ) ? $tab['icon_position'] : '';
				$icon_position_class = '';

				if ( $icon_position ) {
					$icon_position_class = 'brix-tab-trigger-icon-position-' . $icon_position;
				}

				if ( empty( $tab_title ) ) {
					$tab_title = __( 'Untitled tab', 'brix' );
				}

				if ( isset( $tab['decoration'] ) ) {
					$decoration_html = brix_get_decoration( $tab['decoration'] );

					if ( ! empty( $decoration_html ) ) {
						if ( $icon_position == 'left' || $icon_position == 'top' ) {
							$tab_title = $decoration_html . ' ' . $tab_title;
						} else if ( $icon_position == 'right' ) {
							$tab_title = $tab_title . ' ' . $decoration_html;
						}
					}
				}

				$tab_trigger_class = $index === 0 ? 'brix-active' : '';

				printf( '<li class="%s"><span id="brix-tab-%s" role="tab" aria-controls="brix-tab-panel-%s" class="brix-tab-trigger %s" href="#">%s</span></li>',
					esc_attr( $icon_position_class ),
					'',
					esc_attr( $index ),
					esc_attr( $tab_trigger_class ),
					$tab_title
				);
			}
		echo '</ul>';

		echo '<div class="brix-tab-container">';
			foreach ( $tabs as $index => $tab ) {
				if ( empty( $tab['content'] ) ) {
					continue;
				}

				$tab_class = $index === 0 ? 'brix-active' : '';

				printf( '<div aria-labelledby="brix-tab-%s" id="brix-tab-panel-%s" class="brix-tab %s" role="tabpanel">%s</div>',
					esc_attr( $index ),
					'',
					esc_attr( $tab_class ),
					'<div class="brix-block-content">' . brix_format_text_content( $tab['content'] ) . '</div>'
				);
			}
		echo '</div>';

	echo '</div>';
}