<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce Tag Cloud widget block.
 *
 * @since 1.2.6
 */
class BrixBuilderWooTagCloudWidgetBlock extends BrixBuilderWidgetBlock {

	/**
	 * Constructor for the widget content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_widget_id         = 'woocommerce_product_tag_cloud';
		$this->_widget_class_name = 'WC_Widget_Product_Tag_Cloud';
		$this->_widget_class_path = plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/widgets/class-wc-widget-product-tag-cloud.php';
		$this->_widget_css_class  = 'woocommerce widget_product_tag_cloud';
		$this->_title             = __( 'WooCommerce Product Tags', 'brix' );

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
function brix_register_woo_tag_cloud_widget_content_block( $blocks ) {
	$blocks['woocommerce_product_tag_cloud'] = array(
		'class'       => 'BrixBuilderWooTagCloudWidgetBlock',
		'label'       => __( 'WooCommerce Product Tags widget', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( "Your most used product tags in cloud format.", 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_tag_cloud_widget_content_block' );
add_filter( 'brix_block_classes[type:woocommerce_product_tag_cloud]', 'brix_widget_block_css_classes', 10, 3 );