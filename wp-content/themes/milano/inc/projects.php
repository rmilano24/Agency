<?php

/**
 * Loading the single project header template.
 *
 * @since 1.0.0
 * @param string $template The page header template file path.
 * @return string
 */
function agncy_single_project_page_header( $template ) {
	$template = 'templates/projects/single-project-page-header';

	return $template;
}

add_filter( 'agncy_page_header_template[post_type:agncy_project]', 'agncy_single_project_page_header' );

/**
 * Single project layout classes.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @return array
 */
function agncy_single_project_layout_classes( $classes ) {
	if ( ! is_singular( 'agncy_project' ) ) {
		return $classes;
	}

	$layout = get_post_meta( get_the_ID(), 'layout', true );

	if ( $layout == 'b' ) {
		$skin = get_post_meta( get_the_ID(), 'layout_skin', true );

		if ( ! $skin ) {
			$skin = 'dark';
		}

		$classes[] = 'agncy-ph-skin-' . $skin;
	}

    return $classes;
}

add_filter( 'agncy_layout_classes', 'agncy_single_project_layout_classes' );

/**
 * Return the single project navigation template.
 *
 * @since 1.0.0
 */
function agncy_get_single_project_nav() {
	get_template_part( 'templates/projects/single-project-nav' );
}

/**
 * Show the post navigation if is singular post and option is checked
 *
 * @since 1.0.0
 */
function agncy_single_project_navigation() {
	if ( is_singular( 'agncy_project' ) ) {
		add_action( 'agncy_after_the_content', 'agncy_get_single_project_nav', 30 );
	}
}

add_action( 'wp_head', 'agncy_single_project_navigation' );

/**
 * Check if the navigation between projects must be displayed
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_show_project_nav() {
	if ( is_singular( 'agncy_project' ) ) {
		$show_post_nav = get_post_meta( get_queried_object_id(), 'agncy_project_navigation', true );

		if ( $show_post_nav === false ) {
			$show_post_nav = true;
		}

		return $show_post_nav;
	}

	return false;
}

/**
 * Social sharing block for single projects.
 *
 * @since 1.0.0
 */
function agncy_single_project_share() {
	if ( is_singular( 'agncy_project' ) ) {
		add_action( 'agncy_project_navigation', 'agncy_single_project_share_markup', 20 );
	}
}

add_action( 'wp_head', 'agncy_single_project_share' );

/**
 * Markup for the social sharing block for single posts.
 *
 * @since 1.0.0
 */
function agncy_single_project_share_markup() {
	$featured_image = ev_get_featured_image( 'large', get_the_ID() );
	?>
		<div class="agncy-sp-s">
			<h4><?php echo esc_html__( 'Did you like this project?', 'agncy' ); ?></h4>
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