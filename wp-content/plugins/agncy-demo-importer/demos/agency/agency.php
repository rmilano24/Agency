<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Declare availability of the Blog demo.
 *
 * @since 1.0.0
 * @param array $demos An array of theme demos.
 * @return array
 */
function agncy_blog_demo( $demos ) {
	$demo_base = trailingslashit( dirname( __FILE__ ) );

	$demos[ 'agency' ] = array(
		'name'        => 'agency',
		'label'       => __( 'Agency', 'agncy-demos-importer' ),
		'description' => '',
		'preview'     => AGNCY_DEMOS_IMPORTER_URI . '/demos/agency/preview.jpg',
		'url'         => 'http://www.thbthemes.com/agncy/',
		'dummy'       => array(
			$demo_base . 'dummy_content.xml',
		),
		'data'		  => $demo_base . 'data.txt'
	);

	return $demos;
}

add_filter( 'agncy_demos', 'agncy_blog_demo' );