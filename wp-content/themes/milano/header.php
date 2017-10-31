<?php
/**
 * @package Agncy
 * @since Agncy 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php agncy_html_class(); ?>" style="<?php agncy_html_style(); ?>">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>

		<?php wp_head(); ?>
		<link rel='stylesheet' id='agncy-style-css'  href='http://milanoagency.wpengine.com/agency/wp-content/themes/agncy/milano.css' type='text/css' media='all' />
	</head>

	<body <?php body_class(); ?>>
		<a class="screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'agncy' ); ?></a>

		<div class="<?php agncy_layout_class(); ?>">

		<?php get_template_part( 'templates/header/overlay-nav' ); ?>

		<?php
			/**
			 * Function that is entitled to display header markup.
			 * Defined in: inc/header.php
			 *
			 * Eg. add_action( 'agncy_header', 'your_custom_function' );
			 */
			agncy_header();
		?>
