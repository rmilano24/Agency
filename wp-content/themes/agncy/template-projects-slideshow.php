<?php
/**
 * @package Agncy
 * @since Agncy 1.0.0
 *
 * Template Name: Projects Slideshow
 */
?>
<?php get_header(); ?>

<?php
	$slides = get_post_meta( get_the_ID(), 'agncy_slide', true );

	if ( ! empty( $slides ) ) {
		get_template_part( 'templates/projects_slideshow' );
	}

	/**
	 * Function that is entitled to display the page content.
	 * Defined in: inc/page-content.php
	 *
	 * Eg. add_action( 'agncy_page_content', 'your_custom_function' );
	 */
	agncy_page_content();
?>

<?php get_footer(); ?>