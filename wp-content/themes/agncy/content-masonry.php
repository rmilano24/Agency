<?php

/**
 * Template of a single post in a loop.
 *
 * This template can be used by loop pages such as the index page, or archives,
 * and is also used by the Brix blog content block.
 */

/* Check if we're loading a Brix blog block. */
$is_brix_block = isset( $brix_blog_builder_block_post_template ) && $brix_blog_builder_block_post_template === true;
$block_data = $is_brix_block ? $data : false;

/* Retrieve the current post's format. */
$format = get_post_format();

/* The image size of the featured image being displayed. */
$featured_image_size = agncy_blog_loop_featured_media_size( $block_data );

/* Retrieve the current post's content that's realative to its specific format. */
$post_format_content = agncy_get_post_format_content( $featured_image_size );

/* Set to true to display the post's featured media. */
$display_featured_media = agncy_blog_loop_display_featured_media( $block_data );

/* Set to true to display the post's excerpt. */
$display_excerpt = agncy_blog_loop_display_excerpt( $block_data );

/* Set to true to display the post's read more button. */
$display_read_more = agncy_blog_loop_display_read_more( $block_data );

/* Post style. */
$style = agncy_loop_post_style( $block_data );

/* Custom post classes. */
$post_classes = 'agncy-loop-item-style-' . $style;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>

	<header>
		<?php agncy_post_categories(); ?>
		<h2 <?php agncy_post_title_attrs(); ?>>
			<?php agncy_sticky_icon(); ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php echo agncy_get_post_title(); ?>
			</a>
		</h2>
	</header>

	<?php if ( $format == 'quote' && ! empty( $post_format_content ) ) : ?>

		<div class="agncy-pfq agncy-e-m">
			<?php print $post_format_content; ?>
		</div>
	<?php elseif ( $format == 'link' && ! empty( $post_format_content ) ) : ?>

		<div class="agncy-pfl">
			<?php print $post_format_content; ?>
		</div>

	<?php else : ?>

		<?php if ( $display_featured_media && ! empty( $post_format_content ) ) : ?>
			<div class="agncy-e-m">
				<?php print $post_format_content; ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<div class="agncy-e-c-pm">
		<?php echo esc_html__( 'Posted by', 'agncy' ); ?> <?php agncy_post_author(); ?>,
		<?php echo esc_html__( 'on', 'agncy' ); ?> <?php agncy_entry_date(); ?>
	</div>

	<div class="agncy-e-c-pc">
		<div class="entry-content">
			<?php if ( $display_excerpt ) : ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
		</div>

		<?php if ( $display_read_more ) : ?>
			<p class="agncy-rm">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php esc_html_e( 'Read more', 'agncy' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>

</article>