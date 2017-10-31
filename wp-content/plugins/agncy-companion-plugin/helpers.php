<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Check if the current theme is Agncy or a child theme.
 *
 * @since 1.0.0
 * @return boolean
 */
function agncy_theme_check() {
	if ( defined( 'AGNCY_THEME_ACTIVE' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a module has been enabled.
 *
 * @since 1.0.2
 * @param string $module The name of the module.
 * @return boolean
 */
function agncy_check_module( $module ) {
    return apply_filters( "agncy_check_module[module:$module]", true );
}

/**
 * Alter the admin heading page title for the theme option pages.
 *
 * @since 1.0.0
 * @param string $title The page title.
 * @return string
 */
function agncy_admin_page_heading_title( $title ) {
	$title = 'Agncy';

	return $title;
}

add_filter( 'ev_admin_page_heading_title[group:agncy]', 'agncy_admin_page_heading_title' );

/**
 * Read an SVG file and return its contents.
 *
 * @since 1.0.0
 * @param string $path The path to the file relative to the plugins' path.
 * @return string
 */
function agncy_companion_load_svg( $path ) {
	$svg = AGNCY_COMPANION_PLUGIN_FOLDER . $path;

	if ( file_exists( $svg ) ) {
		return implode( '', file( $svg ) );
	}

	return '';
}

/**
 * Display the contents of an SVG file.
 *
 * @since 1.0.0
 * @param string $path The path to the file relative to the plugins' path.
 */
function agncy_companion_svg( $path ) {
	echo agncy_companion_load_svg( $path );
}

/**
 * Localize component data.
 *
 * @since 1.0.0
 */
function agncy_components_localize() {
	$data = apply_filters( 'agncy_components_localize', array() );

	wp_localize_script( 'agncy-companion-js', 'agncy_components', $data );
}

add_action( 'admin_enqueue_scripts', 'agncy_components_localize', 16 );

/**
 * Get a list of the defined image sizes.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_image_sizes() {
	$sizes = array();
	global $_wp_additional_image_sizes;

	$sizes = array(
		'full' => array(
			'width'  => true,
			'height' => true,
			'crop'   => false,
			'label' => __( 'Full size', 'agncy-companion-plugin' )
		),
		'large' => array(
			'width'  => intval( get_option( 'large_size_w' ) ),
			'height' => intval( get_option( 'large_size_h' ) ),
			'crop'   => false,
			'label' => __( 'Large', 'agncy-companion-plugin' )
		),
		'medium' => array(
			'width'  => intval( get_option( 'medium_size_w' ) ),
			'height' => intval( get_option( 'medium_size_h' ) ),
			'crop'   => false,
			'label' => __( 'Medium', 'agncy-companion-plugin' )
		),
		'thumbnail' => array(
			'width'  => intval( get_option( 'thumbnail_size_w' ) ),
			'height' => intval( get_option( 'thumbnail_size_h' ) ),
			'crop'   => (bool) get_option( 'thumbnail_crop' ),
			'label' => __( 'Thumbnail', 'agncy-companion-plugin' )
		),
	);

	if ( $_wp_additional_image_sizes ) {
		foreach ( $_wp_additional_image_sizes as $handle => $size ) {
			$sizes[$handle] = $size;
		}
	}

	return apply_filters( 'agncy_image_sizes', $sizes );
}

/**
 * Return an array of image sizes to be used in a select.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_image_sizes_for_select() {
	$image_sizes = agncy_get_image_sizes();
	$sizes = array();

	foreach ( $image_sizes as $size => $data ) {
		$label = isset( $data['label'] ) ? $data['label'] : $size;
		$sizes[$size] = $label;
	}

	return $sizes;
}

/**
 * Get pages from a specific page template, in a selectable format.
 *
 * @since 1.0.0
 * @param string $page_template The page template.
 * @return array
 */
function agncy_get_pages_for_select( $page_template = '' ) {
	$items = get_posts(array(
		'paged'          => 1,
		'posts_per_page' => -1,
		'post_type'      => 'page'
	));

	$options = array();
	$options[0] = '--';

	if( count( $items > 0 ) ) {
		foreach ( $items as $item ) {
			$add = true;

			if ( ! empty( $page_template ) ) {
				$page_template_name = get_post_meta( $item->ID, '_wp_page_template', true );

				if ( $page_template_name == $page_template ) {
					$options[ $item->ID ] = $item->post_title;
				}
			}
			else {
				$options[ $item->ID ] = $item->post_title;
			}
		}
	}

	return $options;
}

/**
 * Custom shortcode that returns the logo for the website.
 *
 * @since 1.0.0
 * @param array $attrs The shortcode attributes.
 * @return string
 */
function agncy_logo_shortcode( $attrs ) {
	$logo_option = 'logo';

	if ( isset( $attrs[ 'skin' ] ) && in_array( $attrs[ 'skin' ], array( 'light', 'white' ) ) ) {
		$logo_option = 'logo_white';
	}

	$html = '';
	$main_logo_option = ev_get_option( $logo_option );
	$main_logo        = isset( $main_logo_option['desktop'][1]['id'] ) && ! empty( $main_logo_option['desktop'][1]['id'] ) ? $main_logo_option['desktop'][1]['id'] : false;

	if ( $logo_option == 'logo_white' && ! $main_logo ) {
		$main_logo_option = ev_get_option( 'logo' );
		$main_logo        = isset( $main_logo_option['desktop'][1]['id'] ) && ! empty( $main_logo_option['desktop'][1]['id'] ) ? $main_logo_option['desktop'][1]['id'] : false;
	}

	$html .= '<p class="agncy-site-title-shortcode">';
		$html .= sprintf( '<a href="%s" rel="home">', site_url() );

			if ( ! $main_logo ) {
				$html .= get_bloginfo( 'name' );
			}
			else {
				$html .= wp_get_attachment_image( $main_logo, 'full' );
			}

		$html .= '</a>';
	$html .= '</p>';

	return $html;
}

add_shortcode( 'agncy_logo', 'agncy_logo_shortcode' );