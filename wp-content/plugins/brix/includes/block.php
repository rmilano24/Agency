<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Return a list of block groups.
 *
 * @since 1.2.6
 * @return array
 */
function brix_get_blocks_groups() {
	$groups = array(
		__( 'Content', 'brix' ),
		__( 'WordPress', 'brix' )
	);

	return apply_filters( 'brix_get_blocks_groups', $groups );
}

/**
 * Render the blocks selection modal window.
 *
 * @since 1.0.0
 */
function brix_blocks_modal_load() {
	// $key = 'brix_blocks';
	$blocks = brix_get_blocks();

	echo '<div class="brix-modal-header">';
		echo '<h1>' . esc_html( __( 'Select content block', 'brix' ) ) . '</h1>';
	echo '</div>';

	echo '<div class="brix-modal-blocks-groups">';
		echo '<ul class="brix-block-group">';
			printf( '<li data-group="%s" data-all="1">%s</li>',
				esc_html__( 'All', 'brix' ),
				esc_attr__( 'All', 'brix' )
			);

			foreach ( brix_get_blocks_groups() as $i => $group ) {
				$class = $i ? '' : 'brix-active';

				printf( '<li class="%s" data-group="%s">%s</li>',
					esc_attr( $class ),
					esc_html( $group ),
					esc_attr( $group )
				);
			}
		echo '</ul>';
	echo '</div>';

	printf( '<div class="brix-blocks-selection-wrapper" data-group="%s">', esc_html__( 'All', 'brix' ) );
		echo '<div class="brix-modal-blocks-search">';
			printf( '<input type="text" placeholder="%s">', esc_attr( __( 'Search&hellip;', 'brix' ) ) );
		echo '</div>';

		echo '<ul class="brix-blocks-selection">';
			foreach ( $blocks as $type => $block ) {
				printf( '<li class="brix-found"><a class="brix-select-block" href="#" data-type="%s"><span class="brix-select-block-group screen-reader-text">%s</span><span class="brix-select-block-icon"><img src="%s"></span><span class="brix-select-block-label-wrapper"><span class="brix-select-block-label">%s</span><span class="brix-select-block-description">%s</span></span></a></li>',
					esc_attr( $type ),
					isset( $blocks[$type]['group'] ) ? esc_html( $blocks[$type]['group'] ) : '',
					isset( $blocks[$type]['icon'] ) ? esc_url( $blocks[$type]['icon'] ) : '',
					esc_html( $blocks[$type]['label'] ),
					isset( $blocks[$type]['description'] ) ? esc_html( $blocks[$type]['description'] ) : ''
				);
			}
		echo '</ul>';
	echo '</div>';

	foreach ( brix_get_blocks_groups() as $i => $group ) {
		$blocks = brix_get_blocks( $group );
		$class = $i ? '' : 'brix-active';

		printf( '<div class="brix-blocks-selection-wrapper %s" data-group="%s"><ul class="brix-blocks-selection">', esc_attr( $class ), esc_html( $group ) );
			foreach ( $blocks as $type => $block ) {
				printf( '<li class="brix-found"><a class="brix-select-block" href="#" data-type="%s"><span class="brix-select-block-group screen-reader-text">%s</span><span class="brix-select-block-icon"><img src="%s"></span><span class="brix-select-block-label-wrapper"><span class="brix-select-block-label">%s</span><span class="brix-select-block-description">%s</span></span></a></li>',
					esc_attr( $type ),
					isset( $blocks[$type]['group'] ) ? esc_html( $blocks[$type]['group'] ) : '',
					isset( $blocks[$type]['icon'] ) ? esc_url( $blocks[$type]['icon'] ) : '',
					esc_html( $blocks[$type]['label'] ),
					isset( $blocks[$type]['description'] ) ? esc_html( $blocks[$type]['description'] ) : ''
				);
			}
		echo '</ul></div>';
	}

	die();
}

add_action( 'wp_ajax_brix_blocks_modal_load', 'brix_blocks_modal_load' );

/**
 * Get the available styles for a given block type.
 *
 * @since 1.0.0
 * @param string $type The block type name.
 * @return array Return boolean false if less than two styles are defined.
 */
function brix_block_get_styles( $type ) {
	$default_styles = array(
		'default' => __( 'Default', 'brix' )
	);

	$styles = apply_filters( "brix_block_styles[type:$type]", $default_styles );

	return $styles;
}

/**
 * Add a style to the available styles for a given block type.
 *
 * @since 1.0.0
 * @param array $fields The block options fields.
 * @param string $type The block type name.
 * @return array.
 */
function brix_block_add_style( $fields, $type ) {
	$styles = brix_block_get_styles( $type );

	if ( count( $styles ) > 1 ) {
		$default_style = apply_filters( "brix_block_default_style[type:$type]", 'default' );

		$fields[] = array(
			'handle' => '_style',
			'type'   => 'select',
			'label'  => __( 'Style', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => $styles
			),
			'default' => $default_style
		);
	}

	return $fields;
}

add_filter( 'brix_block_style_fields', 'brix_block_add_style', 10, 2 );

/**
 * Return a list of fields for a specific block type.
 *
 * @since 1.0.0
 * @param string $type The block type.
 * @return array
 */
function brix_block_fields( $type ) {
	$block_default_fields = apply_filters( "brix_block_default_fields", array(), $type );
	$block_default_fields = apply_filters( "brix_block_default_fields[type:{$type}]", $block_default_fields );
	$block_fields = apply_filters( "brix_block_fields[type:{$type}]", array() );

	return array_merge( $block_default_fields, $block_fields );
}

/**
 * Return a list of style fields for a specific block type.
 *
 * @since 1.0.0
 * @param string $type The block type.
 * @return array
 */
function brix_block_style_fields( $type ) {
	$block_style_fields = apply_filters( "brix_block_style_fields", array(), $type );
	$block_style_fields = apply_filters( "brix_block_style_fields[type:{$type}]", $block_style_fields );

	return $block_style_fields;
}

/**
 * Render the blocks selection modal window.
 *
 * @since 1.0.0
 */
function brix_block_modal_load() {
	$key = 'brix_block';
	$data = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? $_POST['data'] : array();
	$type = isset( $data['_type'] ) && ! empty( $data['_type'] ) ? $data['_type'] : false;
	$state = isset( $data['_state'] ) && ! empty( $data['_state'] ) ? $data['_state'] : false;

	if ( ! $type ) {
		die();
	}

	if ( ! $state ) {
		die();
	}

	$blocks = brix_get_blocks();
	$block_class = $blocks[$type]['class'];
	$block = new $block_class();
	$fields = array();

	$block_fields = brix_block_fields( $type );
	$block_style_fields = brix_block_style_fields( $type );

	$fields[] = array(
		'type'   => 'group',
		'handle' => 'general',
		'label'  => __( 'General', 'brix' ),
		'fields' => $block_fields
	);

	$fields[] = array(
		'type' => 'group',
		'handle' => 'block_appearance',
		'label' => __( 'Style', 'brix' ),
		'fields' => $block_style_fields,
	);

	$spacing_fields = array(
		array(
			'handle' => 'spacing_divider',
			'text' => __( 'Spacing', 'brix' ),
			'type' => 'divider',
			'config' => array(
				'style' => 'in_page',
			)
		),
		array(
			'handle' => 'spacing',
			'label'  => array(
				'text' => __( 'Spacing', 'brix' ),
				'type' => 'hidden'
			),
			'type'   => 'brix_spacing',
		),
		array(
			'handle' => 'stick_bottom',
			'label'  => __( 'Attemp to align bottom', 'brix' ),
			'help' => __( 'Force the block to be bottom aligned if the columns are equal in height and there is enough space at the bottom. When the row\'s vertical aligment is set to middle, this option will not be applied.', 'brix' ),
			'type'   => 'checkbox',
			'config' => array(
				'style' => array( 'switch', 'small' )
			)
		),
	);

	$fields[] = array(
		'type' => 'group',
		'handle' => 'block_spacing',
		'label' => __( 'Spacing', 'brix' ),
		'fields' => $spacing_fields
	);

	$fields = apply_filters( 'brix_block', $fields, $type );

	$modal_back_btn = '';

	if ( $state == 'add' ) {
		$modal_back_btn = brix_btn( __( 'Back', 'brix' ), 'action', array(
			'attrs' => array(
				'class' => 'brix-modal-back brix-block-back',
			),
			'size' => 'medium',
			'echo' => false
		) );
	}

	$m = new Brix_Modal( $key, $fields, $data, array(
		'title'          => $block->get_title(),
		'title_controls' => '',
		'footer_content' => $modal_back_btn
	) );

	$m->render();

	die();
}

add_action( 'wp_ajax_brix_block_modal_load', 'brix_block_modal_load' );

/**
 * Process the builder block on frontend adding the required inline styles.
 *
 * @since 1.0.0
 * @param stdClass $block The block object.
 * @param integer $count The block count index.
 * @param string $selector_prefix If set, this string will be prepended to selectors.
 * @return stdClass
 */
function brix_process_frontend_block( $block, $count, $selector_prefix = false ) {
	$block_data     = $block->data;
	$block_type     = $block_data->_type;
	$block_selector = sprintf( '.brix-section-column-block-%s', $count );
	$block_style    = '';

	if ( $selector_prefix !== false ) {
		$block_selector = $selector_prefix . ' ' . $block_selector;
	}

	/* Additional block resources. */
	do_action( "brix_load_block_resources[type:$block_type]", $block_data );

	/* Spacing CSS rules. */
	if ( isset( $block_data->spacing ) ) {
		$block_spacing_style = brix_spacing_style_output( $block_data->spacing, $block_selector );

		brix_fw()->frontend()->add_inline_style( $block_spacing_style );
	}

	/* Attaching the custom block style. */
	$block_style = apply_filters( 'brix_block_style', $block_style, $block, $block_selector );
	$block_style = apply_filters( "brix_block_style[type:$block_type]", $block_style, $block, $block_selector );

	if ( $block_style != '' ) {
		brix_fw()->frontend()->add_inline_style( $block_style );
	}

	/* Custom block CSS classes. */
	$block->data = isset( $block->data ) && ! empty( $block->data ) ? $block->data : new stdClass();
	$block->data->class = isset( $block->data->class ) && ! empty( $block->data->class ) ? (array) $block->data->class : array();
	$block->data->class[] = 'brix-section-column-block-' . $count;

	if ( ! isset( $block->data->attrs ) ) {
		$block->data->attrs = array();
	}

	$block->data->attrs[] = 'data-count=' . $count;

	return $block;
}

add_filter( 'brix_process_frontend_block', 'brix_process_frontend_block', 10, 3 );

/**
 * Get the current style of a block.
 *
 * @since 1.2.7
 * @param object $data The block data.
 * @return string
 */
function brix_block_get_style( $data ) {
	$block_styles = brix_block_get_styles( $data->_type );

	if ( $block_styles ) {
		$style = isset( $data->_style ) ? $data->_style : false;

		if ( $style !== false && isset( $block_styles[$style] ) ) {
			return $style;
		}
	}

	if ( ! empty( $block_styles ) ) {
		return key( $block_styles );
	}

	return '';
}

/**
 * Get a collection of CSS classes to apply to a specific builder block.
 *
 * @since 1.0.0
 * @param stdClass $data The block data.
 * @param stdClass $block The block object.
 * @return array
 */
function brix_block_classes( $data, $block ) {
	$classes = array(
		'brix-section-column-block',
		'brix-section-column-block-' . $data->_type
	);

	$classes[] = 'brix-section-column-block-style-' . brix_block_get_style( $data );

	if ( isset( $data->class ) ) {
		$classes = array_merge( $classes, (array) $data->class );
	}

	$classes = apply_filters( 'brix_block_classes', $classes, $data, $block );
	$classes = apply_filters( "brix_block_classes[type:{$data->_type}]", $classes, $data, $block );

	return $classes;
}

/**
 * Get a collection of attributes to apply to a specific builder block.
 *
 * @since 1.0.0
 * @param stdClass $data The block data.
 * @return array
 */
function brix_block_attrs( $data ) {
	$attrs = array();

	$attrs[] = 'data-style=' . brix_block_get_style( $data );

	if ( isset( $data->attrs ) ) {
		$attrs = array_merge( $attrs, (array) $data->attrs );
	}

	$attrs = apply_filters( 'brix_block_attrs', $attrs, $data );
	$attrs = array_map( 'esc_attr', $attrs );

	return $attrs;
}