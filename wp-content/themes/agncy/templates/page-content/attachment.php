<div id="main-content" class="agncy-c">

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="agncy-mc">

			<?php do_action( 'agncy_before_the_content' ); ?>

				<div class="agncy-mc-w_i agncy-mc-content">
					<?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>

					<?php if ( has_excerpt() ) : ?>
						<div class="agncy-att-c">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>

					<div class="agncy-att-md">
						<h2><?php echo the_title(); ?></h2>
						<p><?php echo agncy_post_author(); ?></p>
						<p><?php echo agncy_entry_date(); ?></p>
						<p><?php echo agncy_image_meta(); ?></p>
					</div>
				</div>

			<?php do_action( 'agncy_after_the_content' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					echo '<div class="agncy-mc-comments">';
						echo '<div class="agncy-mc-comments_wi">';
							comments_template();
						echo '</div>';
					echo '</div>';
				endif;
			?>

		</div>
	<?php endwhile; ?>

</div>