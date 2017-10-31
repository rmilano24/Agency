<?php
/**
 * The template for displaying comments
 *
 * @package Agncy
 * @since Agncy 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments">

	<?php if ( have_comments() ) : ?>
		<h2 class="agncy-c-t">
			<?php echo esc_html__( 'Join the discussion', 'agncy' ); ?>
		</h2>
		<p class="agncy-c-st">
			<?php
				printf( _nx( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'agncy' ),
					number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</p>

		<ol class="agncy-c-cl">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 60,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_pagination( array(
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous', 'agncy' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'agncy' ) . '</span>',
		) ); ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="agncy-c-cc"><?php esc_html_e( 'Comments are closed.', 'agncy' ); ?></p>
	<?php endif; ?>

	<?php comment_form(
		array(
			'submit_button' => '<button name="%1$s" id="%2$s" class="%3$s">%4$s</button>',
		)
	); ?>

</div><!-- .comments-area -->
