<?php

/**
 * Add the reply icon to the comment reply link
 *
 * @since 1.0.0
 * @param  array $args Comment reply link arguments
 */
function agncy_reply_link_icon( $args ) {
	$icon = agncy_load_svg( 'img/reply.svg' );
	$args['reply_text'] = '<span class="screen-reader-text">' . $args['reply_text'] . '</span>' . $icon;

	return $args;
}

add_filter( 'comment_reply_link_args', 'agncy_reply_link_icon' );
