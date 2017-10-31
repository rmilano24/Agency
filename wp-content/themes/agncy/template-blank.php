<?php
/**
 * @package Agncy
 * @since Agncy 1.0.0
 *
 * Template name: Blank – no header, no footer
 */
?>
<?php get_header( 'blank' ); ?>

<?php
	/**
	 * Function that is entitled to display the page content.
	 * Defined in: inc/page-content.php
	 *
	 * Eg. add_action( 'agncy_page_content', 'your_custom_function' );
	 */
	agncy_page_content();
?>

<?php get_footer( 'blank' ); ?>