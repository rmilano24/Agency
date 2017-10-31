<?php
	$image_upload_class = '';
	$image_url = '';
	$data_attrs = array(
		"data-thumb-size=$thumb_size"
	);

	if ( ! empty( $id ) ) {
		$image_upload_class = 'brix-image-uploaded';
	}

	if ( $multiple ) {
		$data_attrs[] = 'data-multiple';

		if ( $sortable ) {
			$data_attrs[] = 'data-sortable';
		}

		$id = explode( ',', $id );
	}

	$placeholder_html = '<div class="brix-image-placeholder">
		<img data-id="%s" src="%s" alt="">
		<a href="#" class="brix-upload-remove"><span class="screen-reader-text">%s</span></a>
	</div>';

?>

<div class="brix-upload brix-image-upload <?php echo esc_attr( $image_upload_class ); ?>" <?php echo esc_attr( implode( ' ', $data_attrs ) ); ?>>
	<?php if ( count( $densities ) > 1 && ! $multiple ) : ?>
		<p class="brix-density-label"><?php echo esc_html( brix_get_density_label( $density ) ); ?></p>
	<?php endif; ?>

	<?php if ( count( $breakpoints ) > 1 && ! $multiple ) : ?>
		<p class="brix-breakpoint-label"><?php echo esc_html( brix_get_breakpoint_label( $breakpoint ) ); ?></p>
	<?php endif; ?>

	<div class="brix-image-placeholder-container">
		<?php if ( $multiple ) : ?>
			<?php foreach ( $id as $_id ) : ?>
				<?php
					$image_url = brix_get_image( $_id, $thumb_size );

					printf(
						$placeholder_html,
						esc_attr( $_id ),
						esc_url( $image_url ),
						esc_attr( $thumb_size ),
						esc_html( __( 'Remove', 'brix' ) )
					);
				?>
			<?php endforeach; ?>
		<?php else : ?>
			<?php
				$image_url = brix_get_image( $id, $thumb_size );

				printf(
					$placeholder_html,
					esc_attr( $id ),
					esc_url( $image_url ),
					esc_attr( $thumb_size ),
					esc_html( __( 'Remove', 'brix' ) )
				);
			?>
		<?php endif; ?>
	</div>

	<div class="brix-image-upload-action">
		<?php
			brix_btn(
				__( 'Edit', 'brix' ),
				'action',
				array(
					'attrs' => array(
						'class'     => 'brix-edit-action',
					),
					'style' => 'text',
					'size'  => 'medium'
				)
			);
		?>

		<?php
			brix_btn(
				__( 'Upload', 'brix' ),
				'action',
				array(
					'attrs' => array(
						'class'     => 'brix-upload-action',
					),
					'style' => 'text',
					'size'  => 'medium'
				)
			);
		?>

		<?php if ( $multiple ) : ?>
			<?php
				brix_btn(
					__( 'Remove all', 'brix' ),
					'delete',
					array(
						'attrs' => array(
							'class'     => 'brix-remove-all-action',
						),
						'style' => 'text',
						'size'  => 'medium'
					)
				);
			?>
		<?php endif; ?>
	</div>

	<input type="hidden" data-id name="<?php echo esc_attr( $handle ); ?>[<?php echo esc_attr( $breakpoint ); ?>][<?php echo esc_attr( $density ); ?>][id]" value="<?php echo esc_attr( implode( ',', (array) $id ) ); ?>">
</div>