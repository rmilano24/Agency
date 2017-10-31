<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Search widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderSearchWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the search widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-search';
		$this->_widget_class_name = 'WP_Widget_Search';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-search.php';
		$this->_widget_css_class  = 'widget_search';
		$this->_title             = __( 'Search widget', 'brix' );

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
function brix_register_search_widget_content_block( $blocks ) {
	$blocks['widget-search'] = array(
		'class'       => 'BrixBuilderSearchWidgetBlock',
		'label'       => __( 'Search widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'A search form for your site.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_search_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-search]', 'brix_widget_block_css_classes', 10, 3 );