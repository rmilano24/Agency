<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * A list of meta data that's associated to an office.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_team_member_social_networks() {
    $social = array(
        'facebook' => __( 'Facebook', 'agncy-companion-plugin' ),
        'twitter' => __( 'Twitter', 'agncy-companion-plugin' ),
    );

    return apply_filters( 'agncy_team_member_social_networks', $social );
}