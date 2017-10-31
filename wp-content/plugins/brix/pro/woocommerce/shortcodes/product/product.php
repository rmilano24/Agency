<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce product block class.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_type = 'woo_product';
		$this->_title = __( 'WooCommerce product', 'brix' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
		add_filter( "brix_block_style_fields[type:{$this->_type}]", array( $this, 'style_fields' ) );
	}

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.2.6
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	public function fields( $fields )
	{
		$fields[] = array(
			'handle' => 'type',
			'type' => 'select',
			'label' => __( 'Choose the type', 'brix' ),
			'config' => array(
				'data' => array(
					'id' => __( 'ID', 'brix' ),
					'sku' => __( 'SKU', 'brix' ),
				),
				'controller' => true,
			)
		);

			$fields[] = array(
				'handle' => 'id',
				'type' => 'text',
				'label' => __( 'Product ID', 'brix' ),
				'config' => array(
					'visible' => array( 'type' => 'id' )
				)
			);

			$fields[] = array(
				'handle' => 'sku',
				'type' => 'text',
				'label' => __( 'Product SKU', 'brix' ),
				'config' => array(
					'visible' => array( 'type' => 'sku' )
				)
			);

		return $fields;
	}
}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.2.6
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_woo_product_block( $blocks ) {
	$blocks['woo_product'] = array(
		'class'       => 'BrixBuilderWooProductBlock',
		'label'       => __( 'WooCommerce Product', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( 'Show a single product by ID or SKU.', 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_product_block' );

/**
 * Define the appearance of the WooCommerce page content block in the admin.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_content_block_admin_template( $html, $data ) {
	$html = '';

	$type = $data->type;
	$params = '';
	if ( $type == 'id' ) {
		$params = 'id: ' . $data->id . '';
	} else if ( $type == 'sku' ) {
		$params = 'sk: ' . $data->sku . '';
	}

	if ( $type == 'id' ) {
		$html = __( 'Product ID', 'brix' );
	} else if ( $type == 'sku') {
		$html = __( 'Product SKU', 'brix' );
	}

	$html .= '<br><small>';
		$html .= $params;
	$html .= '</small>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:woo_product]', 'brix_woo_product_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the WooCommerce page content block when stringified.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_content_block_stringified( $html, $data ) {
	$html = '';

	$type   = $data->type;
	$params = '';

	if ( $type == 'id' ) {
		$params = 'id="' . $data->id . '"';
	} else if ( $type == 'sku' ) {
		$params = 'sku="' . $data->sku . '"';
	}

	$html = '[product ' . $params . ']';

	return $html;
}

add_filter( 'brix_block_stringified[type:woo_product]', 'brix_woo_product_content_block_stringified', 10, 2 );

/**
 * Custom template path for the WooCommerce page block.
 *
 * @since 1.2.6
 * @return string
 */
function brix_woo_product_block_template_path() {
	return BRIX_PRO_FOLDER . 'woocommerce/shortcodes/product/templates/product_template.php';
}

add_filter( 'brix_block_master_template_path[type:woo_product]', 'brix_woo_product_block_template_path' );