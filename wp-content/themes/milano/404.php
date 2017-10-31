<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Agncy
 * @since Agncy 1.0.0
 */

get_header(); ?>

<div id="main-content" class="agncy-c">
	<div class="agncy-mc">
		<div class="agncy-404-c">

			<p class="agncy-404-s"><?php esc_html_e( 'Error 404', 'agncy' ); ?></p>
			<h1 class="agncy-404-h"><?php esc_html_e( 'Page not found', 'agncy' ); ?></h1>

			<p class="agncy-404-d">
				<?php echo wp_kses_post( __( 'We are sorry, but the page you are looking for does not exist.<br>Please check entered address and try again or go to home page.', 'agncy' ) ); ?><br />
				<?php printf( '<a href="%s">%s</a>', home_url(), esc_html__( 'Back to home page', 'agncy' ) ); ?>
			</p>

		</div>
	</div>
</div>

<?php get_footer(); ?>