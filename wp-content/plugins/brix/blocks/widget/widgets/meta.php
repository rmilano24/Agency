<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Meta widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderMetaWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the meta widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-meta';
		$this->_widget_class_name = 'WP_Widget_Meta';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-meta.php';
		$this->_widget_css_class  = 'widget_meta';
		$this->_title             = __( 'Meta widget', 'brix' );

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
function brix_register_meta_widget_content_block( $blocks ) {
	$blocks['widget-meta'] = array(
		'class'       => 'BrixBuilderMetaWidgetBlock',
		'label'       => __( 'Meta widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'Login, RSS, &amp; WordPress.org links.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_meta_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-meta]', 'brix_widget_block_css_classes', 10, 3 );