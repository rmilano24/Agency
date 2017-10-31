<?php

if ( ! isset( $content_width ) ) {
	/* Set the content width based on the theme's design and stylesheet. */
	$content_width = 1160;
}

/**
 * Enqueue the scripts and styles that are required by the theme.
 *
 * @since 1.0.0
 */
function agncy_assets() {
	if ( is_child_theme() ) {
		/* Including the parent theme stylesheet in the event that we're using a child theme. */
		wp_enqueue_style( 'agncy-parent-style', get_template_directory_uri() . '/style.css' );
	}

	/* Main stylesheet. */
	wp_enqueue_style( 'agncy-style', get_stylesheet_uri(), array( 'wp-mediaelement' ) );

	/* Preloader style. */
	agncy_preloader_inline_style();

	/* Main script. */
	wp_enqueue_script( 'agncy-preloader', get_template_directory_uri() . '/js/frontend/preloader/preloader.js', array(), null, false );
	wp_enqueue_script( 'agncy-script', get_template_directory_uri() . '/js/frontend/min/agncy.min.js', array( 'jquery', 'wp-mediaelement' ), null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		/* If we're in a single post page with comments ON, include the threaded comments JavaScript helper. */
		wp_enqueue_script( 'comment-reply' );
	}
}

/* Adds the scripts and styles to the page. */
add_action( 'wp_enqueue_scripts', 'agncy_assets' );

/**
 * Add styling for the TinyMCE editor on admin.
 *
 * @since 1.0.0
 */
add_editor_style();

/**
 * Theme setup
 */
function agncy_theme_setup() {
	/*
	 * Make theme available for translation.
	 *
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'agncy', get_template_directory() . '/languages' );

	/* Add default posts and comments RSS feed links to head. */
	add_theme_support( 'automatic-feed-links' );

	/* Add support for widgets. */
	add_theme_support( 'widgets' );

	/*
	 * Let WordPress manage the document title.
	 *
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/* This theme uses wp_nav_menu() in one location. */
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'agncy' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat'
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}

add_action( 'after_setup_theme', 'agncy_theme_setup' );

if ( function_exists( 'ev_add_density' ) ) {
	/* Add support for high density screens. */
	ev_add_density( '1.5', esc_html__( 'High density', 'agncy' ) );
}

if ( function_exists( 'ev_add_breakpoint' ) ) {
	/* Add support for breakpoints */
	ev_add_breakpoint( 1, 'mobile', _x( 'Mobile', 'responsive breakpoint', 'agncy' ), 'mobile', '@media screen and (max-width: 768px)' );
}

/**
 * Register the theme sidebars.
 *
 * @since 1.0.0
 */
function agncy_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Widget Area', 'agncy' ),
		'id'            => 'main-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'agncy' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}

add_action( 'widgets_init', 'agncy_widgets_init' );

/**
 * Localize frontend data.
 *
 * @since 1.0.0
 */
function agncy_localize_frontend() {
	$data = apply_filters( 'agncy_localize_frontend', array() );

	wp_localize_script( 'agncy-script', 'agncy', $data );
}

add_action( 'wp_enqueue_scripts', 'agncy_localize_frontend', 11 );

/**
 * CSS classes applied to the body of the page.
 *
 * @since 1.0.0
 * @param array $class An array of CSS classes.
 * @return array
 */
function agncy_body_class( $class ) {
	if ( wp_is_mobile() ) {
		$class[] = 'agncy-mobile';
	}

	if ( is_singular( 'agncy_project' ) ) {
		$layout = '';

		if ( function_exists( 'agncy_get_project_layout' ) ) {
			$layout = agncy_get_project_layout();
		}

		$class[] = 'agncy-single-project-type-' . $layout;
	}

	$agncy_full_width_image = get_post_meta( get_the_ID(), 'agncy_full_width_image', true );

	if ( isset( $agncy_full_width_image ) && $agncy_full_width_image == true ) {
		$class[] = 'agncy-ph-fi-full';
	}

	$agncy_full_width_page_content = get_post_meta( get_the_ID(), 'agncy_full_width_page_content', true );

	if ( isset( $agncy_full_width_page_content ) && $agncy_full_width_page_content == true ) {
		$class[] = 'agncy-c-full';
	}

	$prj_layout_full_width_page_content = get_post_meta( get_the_ID(), 'layout_full_width_page_content', true );

	if ( isset( $prj_layout_full_width_page_content ) && $prj_layout_full_width_page_content == true && is_singular( 'agncy_project' ) ) {
		$class[] = 'agncy-c-full';
	}

	$fixed_footer = false;

	if ( function_exists( 'ev_get_option' ) ) {
		$fixed_footer = ev_get_option( 'fixed_footer' );
	}

	if ( $fixed_footer == true ) {
		$class[] = 'agncy-fixed-footer';
	}

	return $class;
}

add_filter( 'body_class', 'agncy_body_class' );

/**
 * Get the saved value for the color of the preloader curtain.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_preloader_color() {
    $colors = json_decode( get_theme_mod( 'agncy_colors' ) );

	if ( isset( $colors->general->preloader_color ) ) {
		return $colors->general->preloader_color->color;
	}

	return '#fafafa';
}

/**
 * Preloader style.
 *
 * @since 1.0.0
 * @return [type] [description]
 */
function agncy_preloader_inline_style() {
	$color = agncy_preloader_color();

	$inline_style = sprintf( '.agncy-preloader[style=""]:before,.agncy-e-m a:before,.agncy-drawer:after{background-color:%s;color:%s}',
		$color,
		$color
	);

	$inline_style .= implode( '', file( get_template_directory() . '/preloader.css' ) );

	if ( function_exists( 'ev_fw' ) ) {
		ev_fw()->frontend()->add_inline_style( $inline_style );
	}
	elseif ( function_exists( 'wp_add_inline_style' ) ) {
		wp_add_inline_style( 'agncy-style', $inline_style );
	}
}

/**
 * Localize the preloader on frontend.
 *
 * @since 1.0.0
 * @param array $data The localization data.
 * @return array
 */
function agncy_preloader_localize( $data ) {
	$data[ 'preloader' ] = array(
		'color' => agncy_preloader_color()
	);

    return $data;
}

add_filter( 'agncy_localize_frontend', 'agncy_preloader_localize' );