<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder widget content block class.
 *
 * @package   Brix
 * @since 	  1.2.5
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderWidgetBlock extends BrixBuilderBlock {

	/**
	 * The ID of the widget.
	 *
	 * @var string
	 */
	protected $_widget_id = '';

	/**
	 * The name of the class that handles the widget instance.
	 *
	 * @var string
	 */
	protected $_widget_class_name = '';

	/**
	 * The path to the file that declares the class that handles the widget instance.
	 *
	 * @var string
	 */
	protected $_widget_class_path = '';

	/**
	 * The CSS class to be applied to the content block depending on the widget.
	 *
	 * @var string
	 */
	protected $_widget_css_class = '';

	/**
	 * Constructor for the widget area content block.
	 *
	 * @since 1.2.5
	 */
	public function __construct()
	{
		$this->_type = $this->_widget_id;

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
		add_filter( "brix_block_master_template_path[type:{$this->_type}]", array( $this, 'block_template_path' ) );
	}

	/**
	 * Get the widget ID.
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public function getWidgetId()
	{
		return $this->_widget_id;
	}

	/**
	 * Get the widget class name.
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public function getWidgetClassName()
	{
		return $this->_widget_class_name;
	}

	/**
	 * Get the widget class path.
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public function getWidgetClassPath()
	{
		return $this->_widget_class_path;
	}

	/**
	 * Get the widget CSS class.
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public function getWidgetCSSClass()
	{
		return $this->_widget_css_class;
	}

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.2.5
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	public function fields( $fields )
	{
		$fields[] = array(
			'type' => 'brix_widget_transport',
			'handle' => 'instance',
			'label' => '',
			'config' => array(
				'id'         => $this->_widget_id,
				'class_name' => $this->_widget_class_name,
				'class_path' => $this->_widget_class_path,
			)
		);

		return $fields;
	}

	/**
	 * Check if the block is "empty".
	 *
	 * @since 1.2.5
	 * @param array $data The content block data.
	 * @return boolean
	 */
	protected function is_empty( $data )
	{
		return false;
	}

	/**
	 * Custom template path for the team block.
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public function block_template_path() {
		return BRIX_FOLDER . 'blocks/widget/templates/widget_block_template.php';
	}

}

/**
 * Add custom classes to the widget content block, depending on its data.
 *
 * @since 1.2.5
 * @param array $classes An array of CSS classes.
 * @param stdClass $data The block data.
 * @param stdClass $block The block object.
 * @return array
 */
function brix_widget_block_css_classes( $classes, $data, $block ) {
	$classes[] = 'widget';

	if ( $block->getWidgetCSSClass() ) {
		$classes[] = $block->getWidgetCSSClass();
	}

	return $classes;
}

/* Recent posts widget block. */
require_once dirname( __FILE__ ) . '/widgets/recent_posts.php';

/* Calendar widget block. */
require_once dirname( __FILE__ ) . '/widgets/calendar.php';

/* Custom menu widget block. */
require_once dirname( __FILE__ ) . '/widgets/custom_menu.php';

/* Meta widget block. */
require_once dirname( __FILE__ ) . '/widgets/meta.php';

/* Search widget block. */
require_once dirname( __FILE__ ) . '/widgets/search.php';

/* Recent comments widget block. */
require_once dirname( __FILE__ ) . '/widgets/recent_comments.php';

/* Archives widget block. */
require_once dirname( __FILE__ ) . '/widgets/archives.php';

/* Categories widget block. */
require_once dirname( __FILE__ ) . '/widgets/categories.php';

/* Pages widget block. */
require_once dirname( __FILE__ ) . '/widgets/pages.php';

/* RSS widget block. */
require_once dirname( __FILE__ ) . '/widgets/rss.php';

/* Tag cloud widget block. */
require_once dirname( __FILE__ ) . '/widgets/tag_cloud.php';