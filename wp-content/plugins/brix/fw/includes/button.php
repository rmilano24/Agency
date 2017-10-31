<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Render a button.
 *
 * @param string $text The button text.
 * @param string $type The button type.
 * @param array $config The button configuration.
 * @return string
 */
function brix_btn( $text, $type, $config = array() ) {
	$config = wp_parse_args( $config, array(
		'size'  => 'small',
		'style' => 'button',
		'icon'  => false,
		'hide_text' => false,
		'attrs' => array(),
		'echo'	=> true
	) );

	$html = '';

	/* Type */
	if ( ! isset( $config['attrs']['type'] ) ) {
		$config['attrs']['type'] = 'button';
	}

	/* Classes */
	if ( ! isset( $config['attrs']['class'] ) ) {
		$config['attrs']['class'] = '';
	}

	if ( $config['icon'] ) {
		$config['attrs']['class'] .= ' brix-btn-with-icon';
	}

	$config['attrs']['class'] .= ' brix-btn';
	$config['attrs']['class'] .= ' brix-btn-type-' . $type;
	$config['attrs']['class'] .= ' brix-btn-size-' . $config['size'];
	$config['attrs']['class'] .= ' brix-btn-style-' . $config['style'];

	/* Attributes parsing */
	$attrs = '';

	foreach ( $config['attrs'] as $attr => $value ) {
		$attrs .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
	}

	$html .= sprintf( '<button %s>', $attrs );

		if ( $config['icon'] ) {
			$html .= sprintf( '<i data-icon="%s"></i>', esc_attr( $config['icon'] ) );
		}

		$text_class = '';

		if ( $config['hide_text'] === true ) {
			$text_class = 'screen-reader-text';
		}

		$html .= sprintf( '<span class="%s">%s</span>',
			esc_attr( $text_class ),
			esc_html( $text )
		);

	$html .= '</button>';

	if ( $config['echo'] ) {
		echo $html;
	}

	return $html;
}
