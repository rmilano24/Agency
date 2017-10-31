<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce Products widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductsWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_products';
		$this->_widget_class_name = 'WC_Widget_Products';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-products.php';
		$this->_widget_css_class  = 'woocommerce widget_products';
		$this->_title             = __( 'WooCommerce Products', 'brix' );

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
function brix_register_woo_products_widget_content_block( $blocks ) {
	$blocks['woocommerce_products'] = array(
		'class'       => 'BrixBuilderWooProductsWidgetBlock',
		'label'       => __( 'WooCommerce Products widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "Display a list of your products on your site.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_products_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_products]', 'brix_widget_block_css_classes', 10, 3 );