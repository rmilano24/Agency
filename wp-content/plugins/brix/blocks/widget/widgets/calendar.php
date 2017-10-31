<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Calendar widget block.
 *
 * @since 1.2.5
 */
class BrixBuilderCalendarWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the recent posts widget content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_widget_id         = 'widget-calendar';
		$this->_widget_class_name = 'WP_Widget_Calendar';
		$this->_widget_class_path = ABSPATH . WPINC . '/widgets/class-wp-widget-calendar.php';
		$this->_widget_css_class  = 'widget_calendar';
		$this->_title             = __( 'Calendar widget', 'brix' );

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
function brix_register_calendar_widget_content_block( $blocks ) {
	$blocks['widget-calendar'] = array(
		'class'       => 'BrixBuilderCalendarWidgetBlock',
		'label'       => __( 'Calendar widget', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget/i/widgets_icon.svg',
		'description' => __( 'A calendar of your site&#8217;s Posts.', 'brix' ),
		'group'       => __( 'WordPress', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_calendar_widget_content_block' );
add_filter( 'brix_block_classes[type:widget-calendar]', 'brix_widget_block_css_classes', 10, 3 );