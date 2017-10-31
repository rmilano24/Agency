<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="brix-entry-featured-image">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		</figure>
	<?php endif; ?>

	<?php brix_entry_date(); ?>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>
</article>