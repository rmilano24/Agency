<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_widget_area_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty widget area selected.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:widget_area]", 'brix_widget_area_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_text_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty text block.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:text]", 'brix_text_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_button_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty button label.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:button]", 'brix_button_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_tabs_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty tabs.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:tabs]", 'brix_tabs_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_accordion_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty accordion.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:accordion]", 'brix_accordion_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_feature_box_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty feature box.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:feature_box]", 'brix_feature_box_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_list_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty lists.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:list]", 'brix_list_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_team_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty team member data.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:team]", 'brix_team_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_icon_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty icon.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:icon]", 'brix_icon_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_progress_bar_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty progress data.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:progress_bar]", 'brix_progress_bar_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_counter_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty counter value.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:counter]", 'brix_counter_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_image_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty image.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:image]", 'brix_image_block_empty_render' );

/**
 * Define the message that is output when the block is empty and we're in live
 * editing mode.
 *
 * @since 1.2
 * @param string $markup The block empty markup.
 * @return string
 */
function brix_post_block_empty_render( $markup ) {
	if ( brix_is_frontend_editing() ) {
		$markup .= __( 'Empty post.', 'brix' );
	}

	return $markup;
}

add_filter( "brix_block_empty_render[type:single_post]", 'brix_post_block_empty_render' );