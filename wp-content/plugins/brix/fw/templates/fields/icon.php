<?php
	$value        = $field->value();
	$handle       = $field->handle();
	$use_modal    = intval( $field->config( 'modal' ) );
	$set          = isset( $value['set'] ) ? $value['set'] : '';
	$icon         = isset( $value['icon'] ) ? $value['icon'] : '';
	$prefix       = isset( $value['prefix'] ) ? $value['prefix'] : '';
	$color        = isset( $value['color'] ) ? $value['color'] : '';
	$size         = isset( $value['size'] ) ? $value['size'] : '';
	$url          = '';

	$icon_fonts = brix_get_icon_fonts();
	$icon_set = isset( $icon_fonts[$set] ) ? $icon_fonts[$set] : false;

	if ( $icon_set ) {
		foreach ( $icon_set['mapping'] as $file ) {
			if ( $file['name'] === $icon ) {
				$url = $file['url'];
				break;
			}
		}
	}

	$wrapper_class = '';

	if ( ! $use_modal ) {
		$wrapper_class .= ' brix-icon-inline';
	}

	if ( empty( $icon ) ) {
		$wrapper_class .= ' brix-empty';
	}

?>

<div class="brix-selected-icon-wrapper <?php echo esc_attr( $wrapper_class ); ?>" data-use-modal="<?php echo esc_attr( $use_modal ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'brix_icon' ) ); ?>">
	<img data-preview class="brix-icon" src="<?php echo esc_attr( $url ); ?>">
	<span class="brix-remove brix-icon-remove"></span>
</div>

<input type="hidden" data-prefix name="<?php echo esc_attr( $handle ); ?>[prefix]" value="<?php echo esc_attr( $prefix ); ?>">
<input type="hidden" data-set name="<?php echo esc_attr( $handle ); ?>[set]" value="<?php echo esc_attr( $set ); ?>">
<input type="hidden" data-icon name="<?php echo esc_attr( $handle ); ?>[icon]" value="<?php echo esc_attr( $icon ); ?>">

<?php if ( $use_modal ) : ?>
	<input type="hidden" data-size name="<?php echo esc_attr( $handle ); ?>[size]" value="<?php echo esc_attr( $size ); ?>">
	<input type="hidden" data-color name="<?php echo esc_attr( $handle ); ?>[color]" value="<?php echo esc_attr( $color ); ?>">
<?php else : ?>
	<div class="brix-icon-inline-fields-wrapper">
		<span>
			<label class="brix-label"><?php echo esc_html( __( 'Color', 'brix' ) ); ?></label>
			<?php echo brix_color( $handle, $color, false, false, false ); ?>
		</span>
		<span>
			<label class="brix-label"><?php echo esc_html( __( 'Size', 'brix' ) ); ?></label>
			<input type="text" data-size name="<?php echo esc_attr( $handle ); ?>[size]" value="<?php echo esc_attr( $size ); ?>">
		</span>
	</div>
<?php endif; ?>