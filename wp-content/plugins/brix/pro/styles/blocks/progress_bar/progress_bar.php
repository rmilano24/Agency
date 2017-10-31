<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_progress_bar_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'bar_size',
		'type' => 'select',
		'label' => __( 'Progress bar', 'brix' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Default', 'brix' ),
				'small'   => __( 'Small', 'brix' ),
				'large'   => __( 'Large', 'brix' ),
				'custom'  => __( 'Custom', 'brix' )
			)
		)
	);

		$fields[] = array(
			'handle' => 'bar_size_custom',
			'type' => 'text',
			'label' => __( 'Progress bar size', 'brix' ),
			'config' => array(
				'visible' => array( 'bar_size' => 'custom' ),
			)
		);

	$fields[] = array(
		'handle' => 'bar_style',
		'type' => 'select',
		'label' => __( 'Border shape', 'brix' ),
		'config' => array(
			'data' => array(
				'default' => __( 'Squared borders', 'brix' ),
				'rounded' => __( 'Rounded borders', 'brix' ),
			)
		)
	);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:progress_bar]', 'brix_styles_progress_bar_block_fields' );

/**
 * Add the required inline styles for the team builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_progress_bar_frontend_block( $block_style, $block, $block_selector ) {
	$size        = isset( $block->data ) && isset( $block->data->bar_size ) ? $block->data->bar_size : 'default';
	$size_custom = isset( $block->data ) && isset( $block->data->bar_size_custom ) ? $block->data->bar_size_custom : '';
	$bar_style   = isset( $block->data ) && isset( $block->data->bar_style ) ? $block->data->bar_style : 'default';

	if ( $size != 'default' ) {
		$block_style .= $block_selector . ' .brix-progress-bar-line-external-wrapper {';
			if ( $size == 'small' ) {
				$block_style .= 'height: 2px;';
			}
			else if ( $size == 'large' ) {
				$block_style .= 'height: 8px;';
			}
			else if ( $size == 'custom' && $size_custom != '' ) {
				if ( is_numeric( $size_custom ) ) {
					$size_custom .= 'px';
				}

				$block_style .= 'height:' . $size_custom . ';';
			}
		$block_style .= '}';
	}

	if ( $bar_style == 'rounded' ) {
		$block_style .= $block_selector . ' .brix-progress-bar-line, ' . $block_selector . ' .brix-progress-bar-line-external-wrapper {';
			$block_style .= 'border-radius: 10em;';
		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:progress_bar]', 'brix_styles_process_progress_bar_frontend_block', 10, 3 );