<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Pages widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderPagesWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the Pages widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-pages';
		$this->_widget_class_name = 'WP_Widget_Pages';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-pages.php';
		$this->_widget_css_class  = 'widget_pages';
		$this->_title             = __( 'Pages widget', 'brix' );

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
function brix_register_pages_widget_content_block( $blocks ) {
	$blocks['widget-pages'] = array(
		'class'       => 'BrixBuilderPagesWidgetBlock',
		'label'       => __( 'Pages widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'A list of your site&#8217;s Pages.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_pages_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-pages]', 'brix_widget_block_css_classes', 10, 3 );