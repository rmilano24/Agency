<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Custom menu widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderCustomMenuWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the custom menu widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-nav_menu';
		$this->_widget_class_name = 'WP_Nav_Menu_Widget';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-nav-menu-widget.php';
		$this->_widget_css_class  = 'widget_nav_menu';
		$this->_title             = __( 'Custom menu widget', 'brix' );

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
function brix_register_custom_menu_widget_content_block( $blocks ) {
	$blocks['widget-nav_menu'] = array(
		'class'       => 'BrixBuilderCustomMenuWidgetBlock',
		'label'       => __( 'Custom menu widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'Add a custom menu.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_custom_menu_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-nav_menu]', 'brix_widget_block_css_classes', 10, 3 );