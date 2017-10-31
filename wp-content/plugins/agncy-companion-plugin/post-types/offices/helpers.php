<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * A list of meta data that's associated to an office.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_office_metas() {
    $metas = array(
        'email' => __( 'Email', 'agncy-companion-plugin' ),
        'phone' => __( 'Phone', 'agncy-companion-plugin' ),
        'fax'   => __( 'FAX', 'agncy-companion-plugin' ),
        'skype' => __( 'Skype', 'agncy-companion-plugin' ),
    );

    return apply_filters( 'agncy_office_metas', $metas );
}

/**
 * Get offices in a selectable format.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_get_offices_for_select() {
	$items = get_posts(array(
		'paged'          => 1,
		'posts_per_page' => -1,
		'post_type'      => 'agncy_office'
	));

	$options = array();
	$options[0] = '--';

	if ( count( $items > 0 ) ) {
		foreach ( $items as $item ) {
			$options[ $item->ID ] = $item->post_title;
		}
	}

	return $options;
}