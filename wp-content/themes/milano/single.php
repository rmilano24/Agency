<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Agncy
 * @since Agncy 1.0.0
 */

get_header(); ?>

<?php
	/**
	 * Function that is entitled to display the page content.
	 * Defined in: inc/page-content.php
	 *
	 * Eg. add_action( 'agncy_page_content', 'your_custom_function' );
	 */
	agncy_page_content();
?>

<?php get_footer(); ?>