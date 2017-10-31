<header>
	<?php brix_entry_date(); ?>

	<h1 class="entry-title">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php the_title(); ?>
		</a>
	</h1>
</header>

<div class="entry-content">
	<?php the_excerpt(); ?>
</div>