<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Recent comments widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderRecentCommentsWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the recent comments widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-recent-comments';
		$this->_widget_class_name = 'WP_Widget_Recent_Comments';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-recent-comments.php';
		$this->_widget_css_class  = 'widget_recent_comments';
		$this->_title             = __( 'Recent Comments widget', 'brix' );

		parent::__construct();
	}

}

/**
 * Add the widget area content block to the registered content blocks.
 *
 * @since 1.2.5
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_recent_comments_widget_content_block( $blocks ) {
	$blocks['widget-recent-comments'] = array(
		'class'       => 'BrixBuilderRecentCommentsWidgetBlock',
		'label'       => __( 'Recent comments widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'Your site&#8217;s most recent comments.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_recent_comments_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-recent-comments]', 'brix_widget_block_css_classes', 10, 3 );