<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * RSS widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderRSSWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the RSS widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-rss';
		$this->_widget_class_name = 'WP_Widget_RSS';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-rss.php';
		$this->_widget_css_class  = 'widget_rss';
		$this->_title             = __( 'RSS widget', 'brix' );

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
function brix_register_rss_widget_content_block( $blocks ) {
	$blocks['widget-rss'] = array(
		'class'       => 'BrixBuilderRSSWidgetBlock',
		'label'       => __( 'RSS widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'Entries from any RSS or Atom feed.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_rss_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-rss]', 'brix_widget_block_css_classes', 10, 3 );