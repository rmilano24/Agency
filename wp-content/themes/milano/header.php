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
		<link rel="apple-touch-icon" sizes="180x180" href="https://designbymilano.com/wp-content/themes/milano/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="https://designbymilano.com/wp-content/themes/milano/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="https://designbymilano.com/wp-content/themes/milano/favicon-16x16.png">
		<link rel="manifest" href="https://designbymilano.com/wp-content/themes/milano/manifest.json">
		<link rel="mask-icon" href="https://designbymilano.com/wp-content/themes/milano/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">
		<link rel='stylesheet' id='agncy-style-css'  href='http://localhost:8888/agency/wp-content/themes/milano/milano.css' type='text/css' media='all' />
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
