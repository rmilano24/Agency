<?php

/**
 * Get the type of header selected for the current page/site.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_get_header_type() {
	$layout = '';

	if ( function_exists( 'ev_get_option' ) ) {
		$layout = ev_get_option( 'header_layout' );
	}

	if ( empty( $layout ) ) {
		$layout = 'a';
	}

	return $layout;
}

/**
 * Display the site header according to its type and the page context.
 *
 * @since 1.0.0
 */
function agncy_header() {
	$key = 'agncy_header';
	$type = agncy_get_header_type();

	if ( ! function_exists( 'ev_do_action' ) ) {
		do_action( "${key}_before" );
		do_action( $key );
		do_action( "${key}[type:${type}]" );
		do_action( "${key}_after" );

		return;
	}

	/* Typically <div class="agncy-h">. */
	ev_do_action( "${key}_before" );

		/* Actual header contents. */
		ev_do_action( $key, array(
			'type' => $type
		) );

	/* Typically </div>. */
	ev_do_action( "${key}_after" );
}

/**
 * Open the header element.
 *
 * @since 1.0.0
 */
function agncy_open_header() {
	$header_classes = 'agncy-h';
	$header_classes = apply_filters( 'agncy_header_classes', explode( ' ', $header_classes ) );
	$header_type = agncy_get_header_type();

	if ( $header_type ) {
		$header_classes[] = 'agncy-h-type-' . $header_type;
	}

	printf( '<div class="%s">',
		esc_attr( implode( ' ', $header_classes ) )
	);
}

add_action( 'agncy_header_before', 'agncy_open_header', 1 );

/**
 * Close the header element.
 *
 * @since 1.0.0
 */
function agncy_close_header() {
	echo '</div>';
}

add_action( 'agncy_header_after', 'agncy_close_header', 999 );

/**
 * Display the site's logo.
 *
 * @since 1.0.0
 */
function agncy_logo( $nolink = false, $breakpoint = 'desktop' ) {
	$logo_class        = $tagline_class = '';
	$site_name         = get_bloginfo( 'name', true );
	$site_desc         = get_bloginfo( 'description', true );
	$show_tagline      = false;
	$main_logo_option  = '';
	$light_logo_option = '';
	if ( function_exists( 'ev_get_option' ) ) {
		$show_tagline      = ev_get_option( 'show_tagline' );
		$main_logo_option  = ev_get_option( 'logo' );
		$light_logo_option = ev_get_option( 'logo_white' );
	}
	$main_logo         = isset( $main_logo_option[$breakpoint][1]['id'] ) && ! empty( $main_logo_option[$breakpoint][1]['id'] ) ? $main_logo_option[$breakpoint][1]['id'] : false;
	$light_logo        = isset( $light_logo_option[$breakpoint][1]['id'] ) && ! empty( $light_logo_option[$breakpoint][1]['id'] ) ? $light_logo_option[$breakpoint][1]['id'] : false;

	echo '<div class="agncy-logo">';
		$site_title = '';

		if ( $main_logo ) {
			$main_logo  = sprintf( '<span class="agncy-ml agncy-ml-d">%s</span>', wp_get_attachment_image( $main_logo, 'full' ) );
			$main_logo .= sprintf( '<span class="agncy-ml agncy-ml-l">%s</span>', wp_get_attachment_image( $light_logo, 'full' ) );

			$site_title = sprintf( '<span class="screen-reader-text">%s</span>%s', esc_html( $site_name ), wp_kses_post( $main_logo ) );
		}
		else {
			$site_title = esc_html( $site_name );
		}

		if ( ( is_front_page() || is_home() ) && $nolink == false ) {
			printf( '<h1 class="agncy-site-title %s"><a href="%s">%s</a></h1>', esc_attr( $logo_class ), esc_url( home_url( '/' ) ), $site_title );
		}
		else {
			printf( '<p class="agncy-site-title %s"><a href="%s">%s</a></p>', esc_attr( $logo_class ), esc_url( home_url( '/' ) ), $site_title );
		}

		if ( $show_tagline ) {
			if ( ! empty( $site_desc ) || is_customize_preview() ) {
				printf( '<p class="agncy-site-description %s">%s</p>', esc_attr( $tagline_class ), esc_html( $site_desc ) );
			}
		}

	echo '</div>';
}

/**
 * Display the site's navigation.
 *
 * @since 1.0.0
 * @param string $name The menu name.
 * @param string $classes The menu classes.
 */
function agncy_nav( $name = 'primary', $classes = '' ) {
	// Menu output
	$menu_markup = '';

	if ( $name ) {
		ob_start();
		wp_nav_menu( array( 'theme_location' => $name ) );
		$menu_markup = ob_get_contents();
		ob_end_clean();
	}

	$menu_class = 'agncy-mn-n';
	$menu_class = apply_filters( 'agncy_menu_class', $menu_class );

	if ( $menu_markup ) {
		$menu_markup = '<nav class="' . esc_attr( $menu_class ) . '">' . $menu_markup . '</nav>';
	}

	// Menu wrapper output
	ob_start();

	do_action( 'agncy_nav_menu_before', $name );
	do_action( "agncy_nav_menu_before[menu:$name]", $name );

	print $menu_markup;

	do_action( 'agncy_nav_menu_after', $name );
	do_action( "agncy_nav_menu_after[menu:$name]", $name );

	$menu_wrapper_markup = ob_get_contents();
	ob_end_clean();

	if ( ! $menu_wrapper_markup ) {
		return;
	}
?>
	<div class="agncy-mn <?php echo esc_attr( $classes ); ?>">
		<?php print $menu_wrapper_markup; ?>
	</div>
<?php
}

/**
 * Get the home page about text
 *
 * @since 1.0.0
 * @return string
 */
function agncy_header_home_about() {
	$header_about = ev_get_option( 'header_home_about' );

	if ( ! empty( $header_about ) && is_home() ) {
		echo '<div class="agncy-h-hd">';
			echo wp_kses_post( $header_about );
		echo '</div>';
	}
}