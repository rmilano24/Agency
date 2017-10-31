<?php

if ( ! defined( 'BRIX' ) ) {
	return;
}

/* Disable default Brix styles. */
add_filter( 'brix_enable_styles', '__return_false' );

/* Disable the Team content block. */
remove_filter( 'brix_get_blocks', 'brix_register_team_content_block' );

/* Enable support for the alternate skin for blocks. */
if ( function_exists( 'brix_add_theme_support' ) ) {
	brix_add_theme_support( 'block_skin' );
}

$config_base_folder = trailingslashit( get_template_directory() . '/config' );
$brix_config_base_folder = trailingslashit( $config_base_folder . 'brix' );

/* Effects */
require_once $brix_config_base_folder . 'effects.php';
/* Blog content block. */
require_once $brix_config_base_folder . 'blocks/blog.php';
/* Button content block */
require_once $brix_config_base_folder . 'blocks/button.php';
/* Divider content block */
require_once $brix_config_base_folder . 'blocks/divider.php';
/* Accordion content block */
require_once $brix_config_base_folder . 'blocks/accordion.php';
/* Gallery content block */
require_once $brix_config_base_folder . 'blocks/gallery.php';
/* Counter content block */
require_once $brix_config_base_folder . 'blocks/counter.php';
/* Image content block */
require_once $brix_config_base_folder . 'blocks/image.php';
/* Feature box content block */
require_once $brix_config_base_folder . 'blocks/feature-box.php';

/* Remove the Photoswipe css skin from Brix */
remove_action( 'wp_enqueue_scripts', 'brix_gallery_enqueue_scripts', 0 );

/**
 * Suppress global options.
 *
 * @since 1.0.0
 * @param array $options An array of options.
 * @return array
 */
function agncy_brix_global_options( $options ) {
	foreach ( $options as $i => $group ) {
		if ( $group[ 'handle' ] === 'brix-global-options' ) {
			unset( $options[ $i ] );
		}
	}

	return $options;
}

add_filter( 'brix_global_options', 'agncy_brix_global_options' );

/* Suppress the support page. */
add_filter( 'brix_is_supported', '__return_false' );

/* Suppress the documentation page. */
add_filter( 'brix_is_documented', '__return_false' );

/* Suppress the updates system. */
add_filter( 'brix_can_update', '__return_false' );

/**
 * Setup Brix to be used with agncy.
 *
 * @since 1.0.0
 */
function agncy_brix_setup() {
	$options_key = 'brix_agncy';
	$options = get_option( $options_key );

	if ( ! $options ) {
		$options = array();
	}

	$options[ 'container' ] = '';
	$options[ 'gutter' ]    = '';
	$options[ 'baseline' ]  = '';
	$options[ 'responsive_breakpoints' ] = array(
		'full_width_media_query' => 'mobile',
		'breakpoints'            => ''
	);

	update_option( $options_key, $options );
}

add_action( 'admin_init', 'agncy_brix_setup' );