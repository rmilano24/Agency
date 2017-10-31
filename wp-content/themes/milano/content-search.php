<?php
/**
 * Template of a single search result.
 */

$post_type = get_post_type( get_the_ID() );

if ( $post_type == 'agncy_team_member' ) {
	$post_type = __( 'Team member', 'agncy' );
}
else if ( $post_type == 'agncy_project' ) {
	$post_type = __( 'Project', 'agncy' );
}
else if ( $post_type == 'agncy_office' ) {
	$post_type = __( 'Office', 'agncy' );
}
else if ( $post_type == 'agncy_job' ) {
	$post_type = __( 'Job', 'agncy' );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<footer>
		<?php echo esc_html( $post_type ); ?>
	</footer>

	<header>
		<h2 <?php agncy_post_title_attrs(); ?>>
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php echo agncy_get_post_title(); ?>
			</a>
		</h2>
	</header>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>
</article>