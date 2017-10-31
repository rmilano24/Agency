<div id="main-content" class="agncy-c">

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="agncy-mc">

			<?php do_action( 'agncy_before_the_content' ); ?>

				<div class="agncy-mc-w_i agncy-mc-content">

					<?php do_action( 'agncy_the_content_start' ); ?>

					<?php
						the_content( sprintf(
							esc_html__( 'Continue reading %s', 'agncy' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>

					<?php
						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'agncy' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'agncy' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) );
					?>

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

	<?php get_sidebar(); ?>

</div>