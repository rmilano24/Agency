<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce Recent Products widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooRecentlyViewedWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_recently_viewed_products';
		$this->_widget_class_name = 'WC_Widget_Recently_Viewed';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-recently-viewed.php';
		$this->_widget_css_class  = 'woocommerce widget_recently_viewed_products';
		$this->_title             = __( 'WooCommerce Recently Viewed', 'brix' );

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
function brix_register_woo_recently_viewed_widget_content_block( $blocks ) {
	$blocks['woocommerce_recently_viewed_products'] = array(
		'class'       => 'BrixBuilderWooRecentlyViewedWidgetBlock',
		'label'       => __( 'WooCommerce Recently Viewed widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "Display a list of recently viewed products.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_recently_viewed_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_recently_viewed_products]', 'brix_widget_block_css_classes', 10, 3 );