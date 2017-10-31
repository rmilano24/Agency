<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce Product Search widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductSearchWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_product_search';
		$this->_widget_class_name = 'WC_Widget_Product_Search';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-product-search.php';
		$this->_widget_css_class  = 'woocommerce widget_product_search';
		$this->_title             = __( 'WooCommerce Product Search', 'brix' );

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
function brix_register_woo_product_search_widget_content_block( $blocks ) {
	$blocks['woocommerce_product_search'] = array(
		'class'       => 'BrixBuilderWooProductSearchWidgetBlock',
		'label'       => __( 'WooCommerce Product Search widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "A Search box for products only.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_product_search_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_product_search]', 'brix_widget_block_css_classes', 10, 3 );