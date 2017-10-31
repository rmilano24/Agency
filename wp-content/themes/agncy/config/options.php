<?php

/**
 * Creation of the global options page.
 *
 * @since 1.0.0
 */
function agncy_global_option_page() {
    if ( ! function_exists( 'ev_fw' ) ) {
        return;
    }

	$args = array(
		'group' => 'agncy',
		'vertical' => true
	);

	$global_fields = apply_filters( 'agncy_global_fields', array() );

	ev_fw()->admin()->add_submenu_page( 'agncy', 'agncy-global', __( 'Options', 'agncy' ), $global_fields, $args );
}

add_action( 'init', 'agncy_global_option_page', 5 );

/* Options. */
require_once get_template_directory() . '/config/options/header.php';
require_once get_template_directory() . '/config/options/footer.php';
require_once get_template_directory() . '/config/options/general.php';
require_once get_template_directory() . '/config/options/pages.php';
require_once get_template_directory() . '/config/options/search.php';
require_once get_template_directory() . '/config/options/blog.php';
require_once get_template_directory() . '/config/options/single-post.php';
require_once get_template_directory() . '/config/options/social.php';