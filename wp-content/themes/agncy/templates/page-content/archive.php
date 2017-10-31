<div id="main-content" class="agncy-c">

	<div class="agncy-mc">

		<?php printf( '<div class="%s">', esc_attr( implode( ' ', agncy_loop_classes() ) ) ); ?>

			<div class="agncy-mc-w_i">
				<?php if ( have_posts() ) : ?>
					<div class="<?php echo esc_attr( implode( ' ', agncy_blog_loop_wrapper_class() ) ) ?>">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php if ( function_exists( 'ev_get_option' ) ) : ?>

								<?php $archives_loop_style = ev_get_option( 'archives_loop_style' ); ?>

								<?php if ( $archives_loop_style == 'masonry' ) : ?>
									<?php get_template_part( 'content-masonry' ); ?>
								<?php elseif ( $archives_loop_style == 'stream' ) : ?>
									<?php get_template_part( 'content-stream' ); ?>
								<?php else : ?>
									<?php get_template_part( 'content' ); ?>
								<?php endif; ?>

							<?php else : ?>
								<?php get_template_part( 'content' ); ?>
							<?php endif; ?>

						<?php endwhile; ?>

						<?php
							if ( function_exists( 'ev_get_option' ) ) {
								$is_home = is_home() && ev_get_option( 'archives_loop_style' ) == 'masonry';

								if ( $is_home ) {
									echo '<div class="agncy-gs"></div>';
								}
							}
						?>
					</div>

					<?php
						agncy_pagination( array(
							'prev' => '',
							'next' => ''
						) );
					?>
				<?php else : ?>
					<?php get_template_part( 'content-none' ); ?>
				<?php endif; ?>
			</div>

		</div>

	</div>

	<?php get_sidebar(); ?>

</div>