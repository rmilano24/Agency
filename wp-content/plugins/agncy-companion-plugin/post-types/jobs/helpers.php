<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Bypass the job permalink if we're using an external URL.
 *
 * @since 1.0.0
 * @param string $post_link The regular post URL.
 * @param WP_Post $post The post object.
 * @return string
 */
function agncy_job_post_link( $post_link, $post, $leavename, $sample ) {
    if ( $post->post_type != 'agncy_job' ) {
        return $post_link;
    }

    $url = get_post_meta( $post->ID, 'url', true );

    if ( ! empty( $url ) ) {
        $post_link = $url;
    }

    return $post_link;
}

add_filter( 'post_type_link', 'agncy_job_post_link', 10, 4 );