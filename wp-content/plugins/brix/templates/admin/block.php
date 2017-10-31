<?php
	$blocks = brix_get_blocks();
	$block_data = '';
	$block_label = '';
	$block_sublabel = '';
	$block_icon = '';
	$block_classes = array(
		'brix-block',
		'brix-draggable'
	);

	if ( isset( $data->data ) ) {
		$block_data = $data->data;

		$block_classes[] = 'brix-block-' . $block_data->_type;

		if ( ! array_key_exists( $block_data->_type, $blocks ) ) {
			$block_classes[] = 'brix-block-invalid';
			$block_label = sprintf( __( 'Non existing "%s" block.', 'brix' ), $block_data->_type );
		}
		else {
			$block_label = $blocks[$block_data->_type]['label'];
			$block_sublabel = brix_get_block_admin_template( $block_data->_type, $block_data );
			$block_icon = isset( $blocks[$block_data->_type]['icon'] ) ? $blocks[$block_data->_type]['icon'] : '';
		}

		if ( isset( $block_data->_hidden ) && $block_data->_hidden == '1' ) {
			$block_classes[] = 'brix-hidden';
		}
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>" data-type="<?php echo esc_attr( $block_data->_type ); ?>" data-data="<?php echo htmlentities( json_encode( $block_data ) ); ?>" data-stringified="<?php echo htmlentities( brix_get_block_stringified( $block_data->_type, $block_data ) ); ?>">

	<div class="brix-block-inner-wrapper">
		<i class="brix-block-type-icon">
			<?php if ( $block_icon ) : ?>
				<img src="<?php echo esc_url( $block_icon ); ?>">
			<?php endif; ?>
		</i>
		<div class="brix-block-label-wrapper">
			<p class="brix-block-type-label"><?php echo esc_html( $block_label ); ?></p>
			<?php if ( ! empty( $block_sublabel ) ) : ?>
				<div class="brix-block-type-sublabel">
					<?php echo $block_sublabel; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="brix-block-action-toolbar">
		<span class="brix-block-edit brix-tooltip" data-title="<?php esc_attr_e( 'Edit', 'brix' ); ?>"></span>
		<span class="brix-duplicate brix-block-duplicate brix-tooltip" data-title="<?php esc_attr_e( 'Clone', 'brix' ); ?>"></span>
		<span class="brix-remove brix-block-remove"></span>
	</div>
</div>
