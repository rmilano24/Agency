<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce cart widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooCartWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_widget_cart';
		$this->_widget_class_name = 'WC_Widget_Cart';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-cart.php';
		$this->_widget_css_class  = 'woocommerce widget_shopping_cart';
		$this->_title             = __( 'WooCommerce Cart', 'brix' );

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
function brix_register_woo_cart_widget_content_block( $blocks ) {
	$blocks['woocommerce_widget_cart'] = array(
		'class'       => 'BrixBuilderWooCartWidgetBlock',
		'label'       => __( 'WooCommerce Cart widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "Display the user's Cart.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_cart_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_widget_cart]', 'brix_widget_block_css_classes', 10, 3 );