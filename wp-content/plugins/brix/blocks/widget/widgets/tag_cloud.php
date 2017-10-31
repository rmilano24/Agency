<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Tag cloud widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderTagCloudWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the Tag cloud widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-tag_cloud';
		$this->_widget_class_name = 'WP_Widget_Tag_Cloud';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-tag-cloud.php';
		$this->_widget_css_class  = 'widget_tag_cloud';
		$this->_title             = __( 'Tag Cloud widget', 'brix' );

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
function brix_register_tag_cloud_widget_content_block( $blocks ) {
	$blocks['widget-tag_cloud'] = array(
		'class'       => 'BrixBuilderTagCloudWidgetBlock',
		'label'       => __( 'Tag Cloud widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'A cloud of your most used tags.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_tag_cloud_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-tag_cloud]', 'brix_widget_block_css_classes', 10, 3 );