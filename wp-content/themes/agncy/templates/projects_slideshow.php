<?php

$slides = get_post_meta( get_the_ID(), 'agncy_slide', true );

?>


<div class="agncy-p-s">
	<div class="agncy-p-s_w">

		<?php $j=0; foreach ( $slides as $i => $slide ) : ?>
			<?php
				$ref_id = isset( $slide[ 'ref_id' ] ) && ! empty( $slide[ 'ref_id' ] ) ? $slide[ 'ref_id' ] : false;

				$title       = '';
				$subtitle    = '';
				$text        = '';
				$button_text = '';
				$color       = '';
				$link        = '';
				$image_size  = isset( $slide[ 'image_size' ] ) ? $slide[ 'image_size' ] : 'full';
				$image = get_the_post_thumbnail_url( $ref_id, $image_size );

				$use_video   = false;

				if ( $ref_id ) {
					if ( isset( $slide[ 'button_text' ] ) && ! empty( $slide[ 'button_text' ] ) ) {
						$button_text = $slide[ 'button_text' ];
					}
					else {
						$button_text = __( 'View', 'agncy' );
					}

					$color = '';

					if ( function_exists( 'agncy_get_project_color' ) ) {
						$color = agncy_get_project_color( $ref_id );
					}

					$link  = get_permalink( $ref_id );

					$subtitle = '';
					if ( function_exists( 'agncy_get_project_subtitle' ) ) {
						$subtitle = agncy_get_project_subtitle( $ref_id );
					}

					$use_video = isset( $slide[ 'use_video' ] ) && $slide[ 'use_video' ] == '1';

					if ( $use_video ) {
						$video = get_post_meta( $ref_id, 'video', true );

						if ( $video ) {
							if ( agncy_projects_slideshow_is_self_hosted_video( $video ) ) {
								$video = sprintf( '<video src="%s" loop></video>',
									esc_attr( $video )
								);
							}
							else {
								$video = wp_oembed_get( $video );
							}
						}
					}
				}

				if ( empty( $color ) ) {
					$color = '#fafafa';
				}

				if ( isset( $slide[ 'title' ] ) && ! empty( $slide[ 'title' ] ) ) {
					$title = $slide[ 'title' ];
				}
				elseif ( $ref_id ) {
					$title = get_the_title( $ref_id );
				}

				if ( isset( $slide[ 'subtitle' ] ) && ! empty( $slide[ 'subtitle' ] ) ) {
					$subtitle = $slide[ 'subtitle' ];
				}

				if ( isset( $slide[ 'text' ] ) && ! empty( $slide[ 'text' ] ) ) {
					$text = $slide[ 'text' ];
				}

			?>

			<div class="agncy-ps-s <?php echo esc_attr( $j == 0 ? 'agncy-ps-s-in agncy-ps-s-active' : '' ); ?>">
				<div class="agncy-ps-s_w-i">
					<div class="agncy-ps-s_gutter">
						<div class="agncy-ps-s-data">
							<div class="agncy-ps-s-data_w-i">
								<?php if ( ! empty( $subtitle ) ) : ?>
									<div class="agncy-ps-bt_w">
										<p><?php echo esc_html( $subtitle ); ?></p>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $title ) ) : ?>
									<div class="agncy-ps-t_w">
										<h2><?php echo esc_html( $title ); ?></h2>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $text ) ) : ?>
									<div class="agncy-ps-txt_w">
										<p><?php echo wp_kses_post( $text ); ?></p>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $link ) ) : ?>
									<div class="agncy-ps-l_w">
										<a href="<?php echo esc_attr( $link ); ?>" data-color="<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $button_text ); ?></a>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="agncy-ps-s-media_w" style="color:<?php echo esc_attr( $color ); ?>">
							<?php
								$media_classes = array();

								if ( $use_video ) {
									$has_poster_image = ! empty( $image );

									$media_classes[] = 'agncy-ps-s-media-video';

									if ( $has_poster_image ) {
										$media_classes[] = 'agncy-ps-s-media-video-with-pi';
									}
								}
							?>
							<div class="agncy-ps-s-media_w-i <?php echo esc_attr( implode( ' ', $media_classes ) ); ?>">
								<?php if ( $use_video ) : ?>
									<?php
										print $video;

										if ( $has_poster_image ) {
											printf( '<a class="agncy-ps-s-media agncy-ps-s-media-video-poster-image" href="#"><img src="%s" /></a>',
												esc_attr( $image )
											);
										}
									?>
								<?php else : ?>
									<?php
										$image_alt = get_post_meta( get_post_thumbnail_id( $ref_id ), '_wp_attachment_image_alt', true );
									?>
									<?php printf( '<img class="agncy-ps-s-media" alt="%s" src="%s" />', esc_html( $image_alt ), esc_attr( $image ) ); ?>
								<?php endif; ?>
							</div>

							<span class="agncy-ps-s-bg" style="color:<?php echo esc_attr( $color ); ?>"></span>
						</div>
					</div>
				</div>
			</div>

		<?php $j++; endforeach; ?>

	</div>

	<?php if ( count( $slides ) > 1 ): ?>
		<div class="agncy-p-s-nav">
			<div class="agncy-p-s-nav_w-i">
				<button type="button" class="agncy-p-s-nav-button agncy-p-s-nav-button-prev"><?php echo agncy_load_svg( 'img/arrow.svg' ); ?></button>
				<button type="button" class="agncy-p-s-nav-button agncy-p-s-nav-button-next"><?php echo agncy_load_svg( 'img/arrow.svg' ); ?></button>
			</div>
		</div>
	<?php endif; ?>
</div>