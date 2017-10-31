<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce Recent Reviews widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooRecentReviewsWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_recent_reviews';
		$this->_widget_class_name = 'WC_Widget_Recent_Reviews';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-recent-reviews.php';
		$this->_widget_css_class  = 'woocommerce widget_recent_reviews';
		$this->_title             = __( 'WooCommerce Recent Reviews', 'brix' );

		parent::__construct();
	}

}

/**
 * Add the widget area content block to the registered content blocks.
 *
 * @since 1.2.6
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_woo_recent_reviews_widget_content_block( $blocks ) {
	$blocks['woocommerce_recent_reviews'] = array(
		'class'       => 'BrixBuilderWooRecentReviewsWidgetBlock',
		'label'       => __( 'WooCommerce Recent Reviews widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "Display a list of your most recent reviews on your site.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_recent_reviews_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_recent_reviews]', 'brix_widget_block_css_classes', 10, 3 );