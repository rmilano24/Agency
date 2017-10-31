<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Return the list of fields that compose the style of the content block.
 *
 * @since 1.0.0
 * @param array $fields A list of fields that compose the style of the content block.
 * @return array
 */
function brix_styles_team_block_fields( $fields ) {
	$fields[] = array(
		'handle' => 'image_shape',
		'type' => 'select',
		'label' => __( 'Image shape', 'brix' ),
		'config' => array(
			'controller' => true,
			'data' => array(
				'default' => __( 'Default', 'brix' ),
				'circle' => __( 'Circle', 'brix' ),
				'rounded' => __( 'Rounded corners', 'brix' )
			)
		)
	);

		$fields[] = array(
			'handle' => 'image_radius',
			'type' => 'text',
			'label' => __( 'Image shape radius', 'brix' ),
			'config' => array(
				'visible' => array( 'image_shape' => 'rounded' )
			)
		);

	return $fields;
}

add_filter( 'brix_block_style_fields[type:team]', 'brix_styles_team_block_fields' );

/**
 * Get the builder addons team image shape.
 * @since 1.0.0
 * @param  array $data The block data object
 * @return string
 */
function brix_styles_team_get_image_shape( $data ) {
	$shape = isset( $data ) && isset( $data->image_shape ) ? $data->image_shape : 'default';

	return $shape;
}

/**
 * Get the builder addons team image radius.
 *
 * @since 1.0.0
 * @param  array $data The block data object.
 * @return string
 */
function brix_styles_team_get_image_radius( $data ) {
	$radius = isset( $data ) && isset( $data->image_radius ) ? $data->image_radius : '';

	if ( is_numeric( $radius ) ) {
		$radius .= 'px';
	}

	return $radius;
}

/**
 * Admin template image style.
 *
 * @since 1.0.0
 * @param array $data The block data object.
 * @param string $image_style The default image style.
 * @return string
 */
function brix_styles_team_content_block_admin_template_image_style( $image_style, $data ) {
	$image_shape  = brix_styles_team_get_image_shape( $data );
	$image_radius = brix_styles_team_get_image_radius( $data );

	if ( $image_shape == 'rounded' && $image_radius != '' ) {
		$image_style = 'border-radius:' . $image_radius . '';
	} else if ( $image_shape == 'circle' ) {
		$image_style = 'border-radius:500em';
	}

	return $image_style;
}

add_filter( 'brix_team_content_block_admin_template_image_style', 'brix_styles_team_content_block_admin_template_image_style', 10, 2 );

/**
 * Add the required inline styles for the team builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_styles_process_team_frontend_block( $block_style, $block, $block_selector ) {
	$image_shape  = brix_styles_team_get_image_shape( $block->data );
	$image_radius = brix_styles_team_get_image_radius( $block->data );

	if ( $image_shape != 'default' ) {
		$block_style .= $block_selector . ' .brix-team-block-picture img {';
			if ( $image_shape == 'circle' ) {
				$block_style .= 'border-radius: 50%;';
			}
			else if ( $image_shape == 'rounded' && $image_radius != '' ) {
				$block_style .= 'border-radius:' . $image_radius . ';';
			}
		$block_style .= '}';
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:team]', 'brix_styles_process_team_frontend_block', 10, 3 );

/**
 * Add allowed styles for the team member block.
 *
 * @since 1.0.0
 * @param array $styles An array of CSS properties.
 * @return array
 */
function brix_styles_team_allowed_html_style( $styles ) {
    $styles[] = 'border-radius';

    return $styles;
}

add_filter( 'safe_style_css', 'brix_styles_team_allowed_html_style' );