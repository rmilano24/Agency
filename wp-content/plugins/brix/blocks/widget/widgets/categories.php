<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Categories widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderCategoriesWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the categories widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-categories';
		$this->_widget_class_name = 'WP_Widget_Categories';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-categories.php';
		$this->_widget_css_class  = 'widget_categories';
		$this->_title             = __( 'Categories widget', 'brix' );

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
function brix_register_categories_widget_content_block( $blocks ) {
	$blocks['widget-categories'] = array(
		'class'       => 'BrixBuilderCategoriesWidgetBlock',
		'label'       => __( 'Categories widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'A list or dropdown of categories.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_categories_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-categories]', 'brix_widget_block_css_classes', 10, 3 );