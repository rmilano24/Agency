<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Recent posts widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderRecentPostsWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the recent posts widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-recent-posts';
		$this->_widget_class_name = 'WP_Widget_Recent_Posts';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-recent-posts.php';
		$this->_widget_css_class  = 'widget_recent_entries';
		$this->_title             = __( 'Recent Posts widget', 'brix' );

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
function brix_register_recent_posts_widget_content_block( $blocks ) {
	$blocks['widget-recent-posts'] = array(
		'class'       => 'BrixBuilderRecentPostsWidgetBlock',
		'label'       => __( 'Recent Posts widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'Your site&#8217;s most recent Posts.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_recent_posts_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-recent-posts]', 'brix_widget_block_css_classes', 10, 3 );