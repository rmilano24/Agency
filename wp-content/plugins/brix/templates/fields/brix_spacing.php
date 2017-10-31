<?php

$value = $field->value();
$disable = $field->config( 'disable' );

if ( empty( $disable ) ) {
	$disable = array();
}

$has_breakpoints = $field->config( 'breakpoints' );
$breakpoints = brix_breakpoints();

if ( ! $has_breakpoints ) {
	$breakpoints = array( 'desktop' => $breakpoints['desktop'] );
}

$margin_props = array(
	'margin_top',
	'margin_right',
	'margin_bottom',
	'margin_left',
);

$padding_props = array(
	'padding_top',
	'padding_right',
	'padding_bottom',
	'padding_left',
);

echo '<table class="brix-spacing-properties">';
	echo '<thead>';
		echo '<tr>';
			printf( '<th>%s</th>', __( 'Context', 'brix' ) );
			printf( '<th colspan="4">%s</th>', __( 'Margin', 'brix' ) );
			echo '<th class="brix-spacing-spacer"></th>';
			printf( '<th colspan="4">%s</th>', __( 'Padding', 'brix' ) );
			echo '<th class="brix-spacing-spacer"></th>';
			printf( '<th colspan="4">%s</th>', __( 'Advanced', 'brix' ) );
		echo '</tr>';
		echo '<tr>';
			echo '<th></th>';
			printf( '<th><span class="brix-spacing-top"><span>%s</span></span></th>', __( 'Top', 'brix' ) );
			printf( '<th><span class="brix-spacing-right"><span>%s</span></span></th>', __( 'Right', 'brix' ) );
			printf( '<th><span class="brix-spacing-bottom"><span>%s</span></span></th>', __( 'Bottom', 'brix' ) );
			printf( '<th><span class="brix-spacing-left"><span>%s</span></span></th>', __( 'Left', 'brix' ) );
			echo '<th></th>';
			printf( '<th><span class="brix-spacing-top"><span>%s</span></span></th>', __( 'Top', 'brix' ) );
			printf( '<th><span class="brix-spacing-right"><span>%s</span></span></th>', __( 'Right', 'brix' ) );
			printf( '<th><span class="brix-spacing-bottom"><span>%s</span></span></th>', __( 'Bottom', 'brix' ) );
			printf( '<th><span class="brix-spacing-left"><span>%s</span></span></th>', __( 'Left', 'brix' ) );
			echo '<th colspan="2"></th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			$handle = $field->handle() . "[$breakpoint_key]";
			$v = array();

			$advanced = isset( $value[$breakpoint_key]['advanced'] ) ? $value[$breakpoint_key]['advanced'] : false;

			$v['margin_top']    = isset( $value[$breakpoint_key]['margin_top'] ) ? $value[$breakpoint_key]['margin_top'] : '';
			$v['margin_right']  = isset( $value[$breakpoint_key]['margin_right'] ) ? $value[$breakpoint_key]['margin_right'] : '';
			$v['margin_bottom'] = isset( $value[$breakpoint_key]['margin_bottom'] ) ? $value[$breakpoint_key]['margin_bottom'] : '';
			$v['margin_left']   = isset( $value[$breakpoint_key]['margin_left'] ) ? $value[$breakpoint_key]['margin_left'] : '';

			$v['padding_top']    = isset( $value[$breakpoint_key]['padding_top'] ) ? $value[$breakpoint_key]['padding_top'] : '';
			$v['padding_right']  = isset( $value[$breakpoint_key]['padding_right'] ) ? $value[$breakpoint_key]['padding_right'] : '';
			$v['padding_bottom'] = isset( $value[$breakpoint_key]['padding_bottom'] ) ? $value[$breakpoint_key]['padding_bottom'] : '';
			$v['padding_left']   = isset( $value[$breakpoint_key]['padding_left'] ) ? $value[$breakpoint_key]['padding_left'] : '';

			echo '<tr class="brix-breakpoint">';
				printf( '<td class="brix-spacing-media-label">%s</td>', $breakpoint['label'] );

				foreach ( $margin_props as $prop ) {
					$class  = ''; // str_replace( '_', '-', $prop );
					$field_handle = $handle . '[' . $prop . ']';
					$attrs  = '';

					if ( in_array( $prop, $disable ) ) {
						$attrs .= ' disabled data-disabled';
					}

					if ( in_array( $prop, array( 'margin_left', 'margin_bottom' ) ) ) {
						if ( ! $advanced ) {
							$attrs .= ' disabled';
						}

						$class .= ' brix-maybe-simplify';
					}

					printf( '<td class="%s"><input %s class="brix-field-text-style-small %s" id="%s" type="text" size="5" name="%s" value="%s" /></td>',
						esc_attr( str_replace( '_', '-', $prop ) ),
						esc_attr( $attrs ),
						esc_attr( $class ),
						esc_attr( $field_handle ),
						esc_attr( $field_handle ),
						esc_attr( $v[$prop] )
					);
				}

				echo '<td></td>';

				foreach ( $padding_props as $prop ) {
					$class  = ''; // str_replace( '_', '-', $prop );
					$field_handle = $handle . '[' . $prop . ']';
					$attrs  = '';

					if ( in_array( $prop, $disable ) ) {
						$attrs .= ' disabled data-disabled';
					}

					if ( in_array( $prop, array( 'padding_left', 'padding_bottom' ) ) ) {
						if ( ! $advanced ) {
							$attrs .= ' disabled';
						}

						$class .= ' brix-maybe-simplify';
					}

					printf( '<td class="%s"><input %s class="brix-field-text-style-small %s" id="%s" type="text" size="5" name="%s" value="%s" /></td>',
						esc_attr( str_replace( '_', '-', $prop ) ),
						esc_attr( $attrs ),
						esc_attr( $class ),
						esc_attr( $field_handle ),
						esc_attr( $field_handle ),
						esc_attr( $v[$prop] )
					);
				}

				echo '<td></td>';

				echo '<td>';
					brix_checkbox(
						$handle . '[advanced]',
						$advanced,
						array( 'switch', 'small' ),
						array( 'data-advanced' )
					);

					printf( '<label for="%s[advanced]"><span class="screen-reader-text">%s</span></label>',
						esc_attr( $handle ),
						esc_html( __( 'Advanced controls', 'brix' ) )
					);
				echo '</td>';
			echo '</tr>';
		}
	echo '</tbody>';
echo '</table>';

echo '<div class="brix-spacing-helper">';
echo '</div>';