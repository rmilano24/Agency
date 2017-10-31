<?php

function agncy_single_post_meta() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
?>
	<div class="agncy-sp-pm">
		<div class="agncy-sp-pm_w-i">
			<?php echo esc_html__( 'Posted by', 'agncy' ); ?> <?php agncy_post_author(); ?>
			<?php echo esc_html__( 'on', 'agncy' ); ?> <?php agncy_entry_date(); ?>
		</div>
	</div>
<?php
}

add_action( 'agncy_before_the_content', 'agncy_single_post_meta' );

/**
 * Single post tags.
 *
 * @since 1.0.0
 */
function agncy_single_post_tags() {
	agncy_post_tags();
}

add_action( 'agncy_after_the_content', 'agncy_single_post_tags' );

/**
 * Markup for the social sharing block for single posts.
 *
 * @since 1.0.0
 */
function agncy_single_post_share_markup() {
	$featured_image = false;
	if ( function_exists( 'ev_get_featured_image' ) ) {
		$featured_image = ev_get_featured_image( 'large', get_the_ID() );
	}
	?>
		<div class="agncy-sp-s">
			<h4><?php echo esc_html__( 'Did you like this article?', 'agncy' ); ?></h4>
			<p><?php echo esc_html__( 'Share it on these social networks!', 'agncy' ); ?></p>

			<ul class="agncy-sp-s-list">
				<li class="agncy-sp-s-facebook"><a target="_blank" href="<?php echo  esc_url('https://www.facebook.com/share.php?u='. get_permalink()); ?>"><?php echo agncy_load_svg( 'img/facebook.svg' ); ?></a></li>
				<li class="agncy-sp-s-google"><a target="_blank" href="<?php echo esc_url('https://plus.google.com/share?url='.urlencode(get_permalink())); ?>" class="share_gplus"><?php echo agncy_load_svg( 'img/googleplus.svg' ); ?></a></li>
				<?php
					if ( $featured_image ) {
						echo '<li class="agncy-sp-s-pinterest"><a target="_blank" href="'. esc_url('https://pinterest.com/pin/create/button/?url='. get_permalink() .'&media='. $featured_image) .'">' . agncy_load_svg( 'img/pinterest.svg' ) . '</a></li>';
					}
				?>
				<li class="agncy-sp-s-twitter"><a target="_blank" href="<?php echo esc_url('https://twitter.com/intent/tweet?text='. get_the_title() .'&amp;url='. get_permalink()); ?>"><?php echo agncy_load_svg( 'img/twitter.svg' ); ?></a></li>
			</ul>
		</div>
	<?php
}

/**
 * Social sharing block for single posts.
 *
 * @since 1.0.0
 */
function agncy_single_post_share() {
	if ( is_singular( 'post' ) ) {
		$show_share = get_post_meta( get_queried_object_id(), 'agncy_post_share', true );

		if ( $show_share ) {
			add_action( 'agncy_post_navigation', 'agncy_single_post_share_markup', 20 );
		}
	}
}

add_action( 'wp_head', 'agncy_single_post_share' );

/**
 * Return the single post navigation template.
 *
 * @since 1.0.0
 */
function agncy_get_single_post_nav() {
	get_template_part( 'templates/single-post/single-post-nav' );
}

/**
 * Check if the post nav must be displayed
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_show_post_nav() {
	if ( is_singular( 'post' ) ) {
		$show_post_nav = get_post_meta( get_queried_object_id(), 'agncy_post_navigation', true );

		if ( $show_post_nav === false ) {
			$show_post_nav = '1';
		}

		return $show_post_nav;
	}

	return false;
}

/**
 * Show the post navigation if is singular post and option is checked
 *
 * @since 1.0.0
 */
function agncy_single_post_navigation() {
	if ( is_singular( 'post' ) ) {
		add_action( 'agncy_after_the_content', 'agncy_get_single_post_nav', 30 );
	}
}

add_action( 'wp_head', 'agncy_single_post_navigation' );

/**
 * Show the related posts if is singular post and option is checked
 *
 * @since 1.0.0
 */
function agncy_single_post_related() {
	if ( is_singular( 'post' ) ) {
		$show_related = get_post_meta( get_queried_object_id(), 'agncy_post_related', true );

		if ( $show_related ) {
			agncy_related_posts();
		}
	}
}

add_action( 'agncy_after_the_content', 'agncy_single_post_related', 50 );