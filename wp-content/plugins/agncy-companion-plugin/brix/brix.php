<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

if ( ! defined( 'BRIX' ) ) {
	return;
}

/* Office content block. */
require_once AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/office/office.php';

/* Jobs content block. */
require_once AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/jobs/jobs.php';

/* Team content block. */
require_once AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/team/team.php';

/* Portfolio content block. */
require_once AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/portfolio/portfolio.php';

/* Project details content block. */
require_once AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/project-details/project_details.php';

/**
 * Add support for Brix for the theme post types.
 *
 * @since 1.0.0
 * @param array $post_types An array of post types.
 * @return array
 */
function agncy_brix_post_types( $post_types ) {
	$post_types[] = 'agncy_project';

    return $post_types;
}

add_filter( 'brix_post_types', 'agncy_brix_post_types' );