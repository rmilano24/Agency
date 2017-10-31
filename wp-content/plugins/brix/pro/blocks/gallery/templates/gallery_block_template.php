<?php

$media_items = isset( $data->data->media ) ? json_decode( $data->data->media ) : array();
$total_media_items = count( $media_items );

if ( $total_media_items === 0 ) {
	return;
}

$static = isset( $data->data->static ) ? (bool) $data->data->static : false;

if ( $static ) {
	$data->data->gallery_type = 'static';
}

$media_item_class        = brix_get_image_figure_class();
$img_src_attr            = 'data-src';
$image_size              = isset( $data->data->media_image_size ) ? $data->data->media_image_size : 'large';
$gallery_type            = isset( $data->data->gallery_type ) ? $data->data->gallery_type : 'grid';
$lightbox                = isset( $data->data->lightbox ) ? (bool) $data->data->lightbox : true;
$captions                = isset( $data->data->captions ) ? (bool) $data->data->captions : true;
$columns                 = isset( $data->data->columns ) ? absint( $data->data->columns ) : 3;
$number                  = isset( $data->data->number ) ? intval( $data->data->number ) : -1;
$gallery_page            = 1;
$media_item_link_class   = '';
$gallery_container_class = '';

$media_item_link_class   = apply_filters( 'brix_gallery_block_media_item_class', $media_item_link_class, $data->data );
$gallery_container_class   = apply_filters( 'brix_gallery_block_media_items_container_class', $gallery_container_class, $data->data );

if ( $static ) {
	$lightbox = true;
	$captions = true;
}

//     $gallery_container_class .= ' brix-gallery-static';
// else {
//     $gallery_container_class .= ' brix-gallery-container-' . $gallery_type;

//     if ( in_array( $gallery_type, array( 'grid', 'masonry' ) ) ) {
//         $gallery_container_class .= ' brix-gallery-columns-' . $columns;
//     }
// }

if ( $lightbox ) {
	$media_item_link_class .= ' brix-gallery-lightbox';
}

if ( $number > 0 && ! $static ) {
	$gallery_page = isset( $_GET[ 'brix_gallery_page' ] ) ? absint( $_GET[ 'brix_gallery_page' ] ) : 1;

	$media_items = array_chunk( $media_items, $number );
	$media_items = isset( $media_items[ $gallery_page - 1 ] ) ? $media_items[ $gallery_page - 1 ] : array();
}

$gallery_data = array();

foreach ( $media_items as $media ) {
	switch ( $media->source ) {
		case 'media':
			$media_metadata = wp_get_attachment_metadata( $media->gallery_item_id );

			if ( ! $media_metadata ) {
				$media_data = array();
			}
			else {
				$media_data = array(
					'src' => brix_get_image( $media->gallery_item_id ),
					'w' => absint( $media_metadata[ 'width' ] ),
					'h' => absint( $media_metadata[ 'height' ] ),
				);
			}


			if ( $captions ) {
				if ( function_exists( 'wp_get_attachment_caption' ) ) {
					$media_data[ 'title' ] = wp_get_attachment_caption( $media->gallery_item_id );
				}
			}

			$gallery_data[] = $media_data;

			break;
		case 'embed':
			$gallery_data[] = array(
				'html' => wp_oembed_get( $media->url )
			);

			break;
		default:
			break;
	}
}

?>

<div class="brix-gallery-container <?php echo esc_attr( $gallery_container_class ); ?>" data-gallery-type="<?php echo esc_attr( $gallery_type ); ?>">

	<?php do_action( 'brix_gallery_container_start', $data ); ?>

	<?php foreach ( $media_items as $i => $media_item ) : ?>
		<?php
			if ( ! isset( $gallery_data[ $i ] ) ) {
				continue;
			}
		?>
		<?php if ( $media_item->source === 'media' ) : ?>
			<?php

				$img_url_full = brix_get_image( $media_item->gallery_item_id, 'full' );

			?>
			<div class="brix-gallery-item <?php echo esc_attr( $media_item_link_class ); ?>" data-data="<?php echo htmlentities( json_encode( $gallery_data[ $i ] ) ); ?>">

				<?php do_action( 'brix_gallery_item_start', $data, $media_item ); ?>

				<?php
					if ( ! $static || $i === 0 ) {
						echo brix_get_lazy_image_markup( $media_item->gallery_item_id, array(
							'classes' => array( 'brix-block-gallery-image-img' ),
							'size' => $image_size
						) );
					}
				?>

				<?php do_action( 'brix_gallery_item_end' ); ?>

			</div>

		<?php else : ?>

			<?php if ( $media_item->url ) : ?>
				<?php
					$img_url = '';

					if ( $lightbox ) {
						$width = 1280;
						$height = 720;

						$poster_image = brix_get_video_poster_image( $media_item->url );

						if ( ! empty( $poster_image ) ) {
							$img_url = $poster_image[ 'url' ];

							if ( $poster_image[ 'width' ] ) {
								$width = $poster_image[ 'width' ];
							}

							if ( $poster_image[ 'height' ] ) {
								$height = $poster_image[ 'height' ];
							}
						}
					}

					if ( $img_url ) {
						?>
							<div class="brix-gallery-item <?php echo esc_attr( $media_item_link_class ); ?>" data-data="<?php echo htmlentities( json_encode( $gallery_data[ $i ] ) ); ?>">
								<?php do_action( 'brix_gallery_item_start', $data, $media_item ); ?>

								<?php
									if ( ! $static || $i === 0 ) {
										echo brix_get_lazy_image_markup( $img_url, array(
											'classes' => array( 'brix-block-gallery-image-img' ),
											'width' => $width,
											'height' => $height
										) );
									}
								?>

								<?php do_action( 'brix_gallery_item_end' ); ?>
							</div>
						<?php
					}
					else {
						?>
							<div class="brix-gallery-item <?php echo esc_attr( $media_item_link_class ); ?>" data-data="<?php echo htmlentities( json_encode( $gallery_data[ $i ] ) ); ?>">
								<?php do_action( 'brix_gallery_item_start', $data, $media_item ); ?>

								<?php
									if ( ! $static || $i === 0 ) {
										print wp_oembed_get( $media_item->url );
									}
								?>

								<?php do_action( 'brix_gallery_item_end' ); ?>
							</div>
						<?php
					}

				?>
			<?php endif; ?>

		<?php endif; ?>
	<?php endforeach; ?>

	<?php do_action( 'brix_gallery_container_end', $data ); ?>

</div>

<?php if ( apply_filters( 'brix_gallery_show_load_more', true, $data ) == true ) : ?>
	<?php if ( $number > 0 && ! $static && $number * $gallery_page < $total_media_items ) : ?>
		<?php
			$url = add_query_arg( 'brix_gallery_page', $gallery_page + 1 );

			printf( '<p><button type="button" class="brix-gallery-load-more" data-href="%s"><span>%s</span><span>%s</span></button></p>',
				esc_url( $url ),
				esc_html__( 'Show more', 'brix' ),
				esc_html__( 'Loading', 'brix' )
			);
		?>
	<?php endif; ?>
<?php endif; ?>