<?php
	$handle = $field->handle();
	$value  = $field->value();

	if ( $value && is_string( $value ) ) {
		$value = json_decode( $value );
	}
	else {
		$value = array();
	}

	$placeholder_html = '<a href="%s" target="_blank" data-source="%s" class="brix-image-placeholder brix-media-placeholder">
		<img src="%s" alt="">
		<span class="brix-upload-remove"><span class="screen-reader-text">%s</span></span>
	</a>';

	$embed_placeholder_html = '<div data-source="%s" class="brix-image-placeholder brix-media-embed-placeholder">
		<span class="brix-upload-remove"><span class="screen-reader-text">%s</span></span>
	</div>';
?>

<div class="brix-media-c" data-nonce="<?php echo esc_attr( wp_create_nonce( 'brix_media_embed_modal' ) ); ?>">
	<?php foreach ( $value as $item ) : ?>
		<?php if ( $item->source == 'embed' ) : ?>
			<?php
				printf( $embed_placeholder_html,
					esc_attr( $item->source ),
					esc_html__( 'Remove', 'brix' )
				);
			?>
		<?php else : ?>
			<?php
				$item_edit_url = admin_url( '/upload.php?item=' . $item->gallery_item_id );

				printf( $placeholder_html,
					esc_attr( $item_edit_url ),
					esc_attr( $item->source ),
					esc_attr( brix_get_image( $item->gallery_item_id, 'thumbnail' ) ),
					esc_html__( 'Remove', 'brix' )
				);
			?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

<button type="button" class="brix-btn brix-btn-type-action brix-btn-size-small brix-btn-style-button" data-add-media><?php esc_html_e( 'Media Library', 'brix' ); ?></button>
<button type="button" class="brix-btn brix-btn-type-action brix-btn-size-small brix-btn-style-button" data-add-embed><?php esc_html_e( 'External embed', 'brix' ); ?></button>

<input type="hidden" data-id name="<?php echo esc_attr( $handle ); ?>" value="<?php echo htmlentities( json_encode( $value ) ); ?>">