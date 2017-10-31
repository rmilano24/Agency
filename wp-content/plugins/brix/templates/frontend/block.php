<?php
	$blocks = brix_get_blocks();

	if ( ! array_key_exists( $data->data->_type, $blocks ) ) {
		return;
	}

	if ( isset( $data->data->_hidden ) && $data->data->_hidden == '1' ) {
		return;
	}

	$block_class = $blocks[$data->data->_type]['class'];
	$block_object = new $block_class();

	$attrs = brix_block_attrs( $data->data );
	$classes = brix_block_classes( $data->data, $block_object );

	if ( isset( $data->data->stick_bottom ) && $data->data->stick_bottom == '1' ) {
		$classes[] = 'brix-block-stick';
	}

	$block_tag = 'div';

	if ( isset( $data->data->section ) && ! empty( $data->data->section ) ) {
		$block_tag = 'section';
	}
?>

<<?php echo esc_html( $block_tag ); ?> <?php echo esc_attr( implode( ' ', $attrs ) ); ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
<div class="brix-block-external-wrapper">

	<?php
		do_action( 'brix_block_before_render', $data->data );

		$block_object->render( $data );

		do_action( 'brix_block_after_render', $data->data );
	?>
</div>
</<?php echo esc_html( $block_tag ); ?>>