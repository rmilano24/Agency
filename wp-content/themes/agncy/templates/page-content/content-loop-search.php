<div id="main-content" class="agncy-c">

	<div class="agncy-mc">

		<div class="agncy-s-l">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content-search' ); ?>
				<?php endwhile; ?>

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

		<div class="agncy-s-l-f">
			<p><?php esc_html_e( 'Didn\'t you find what you were looking for?', 'agncy' ); ?></p>
			<?php get_search_form(); ?>
		</div>

	</div>

	<?php get_sidebar(); ?>

</div>