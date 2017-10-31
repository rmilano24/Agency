<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce page block class.
 *
 * @since 1.2.6
 */
class BrixBuilderWooPageBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_type = 'woo_page';
		$this->_title = __( 'WooCommerce page', 'brix' );

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
			'handle' => 'woo_page_type',
			'type' => 'select',
			'label' => __( 'Page type', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => array(
					'cart'           => __( 'Cart', 'brix' ),
					'checkout'       => __( 'Checkout', 'brix' ),
					'order_tracking' => __( 'Order Tracking', 'brix' ),
					'my_account'     => __( 'My Account', 'brix' ),
				)
			)
		);

			$fields[] = array(
				'handle' => 'order_count',
				'type' => 'text',
				'label'  => __( 'Order count', 'brix' ),
				'help' => __( 'Specify the number of orders to show. By default, it’s set to 15 (use -1 to display all orders.)', 'brix' ),
				'config' => array(
					'visible' => array( 'woo_page_type' => 'my_account' ),
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
function brix_register_woo_page_block( $blocks ) {
	$blocks['woo_page'] = array(
		'class'       => 'BrixBuilderWooPageBlock',
		'label'       => __( 'WooCommerce Page', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( 'Display a WooCommerce page.', 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_page_block' );

/**
 * Define the appearance of the WooCommerce page content block in the admin.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_page_content_block_admin_template( $html, $data ) {
	$html = '';
	$page_type = $data->woo_page_type;
	$order_count = $data->order_count;

	if ( $page_type == 'cart' ) {
		$html .= __( 'Cart page', 'brix' );
	} else if ( $page_type == 'checkout' ) {
		$html .= __( 'Checkout page', 'brix' );
	} else if ( $page_type == 'order_tracking' ) {
		$html .= __( 'Order tracking page', 'brix' );
	} else if ( $page_type == 'my_account' ) {
		$html .= __( 'My Account page', 'brix' );

		if ( ! empty( $data->order_count ) ) {
			$order_count = $data->order_count;
			$html .= '<br><small>';
				$html .= sprintf( 'displaying %s orders', $order_count );
			$html .= '</small>';
		}
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:woo_page]', 'brix_woo_page_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the WooCommerce page content block when stringified.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_page_content_block_stringified( $html, $data ) {
	$html = '';

	$page_type = $data->woo_page_type;
	$order_count = $data->order_count;

	if ( $page_type == 'cart' ) {
		$html = '[woocommerce_'. $page_type . ']';
	} else if ( $page_type == 'checkout' ) {
		$html = '[woocommerce_'. $page_type . ']';
	} else if ( $page_type == 'order_tracking' ) {
		$html = '[woocommerce_'. $page_type . ']';
	} else if ( $page_type == 'my_account' ) {
		$params = '';
		$order_count = '';

		if ( ! empty( $data->order_count ) ) {
			$order_count = $data->order_count;

			$params = ' order_count="' . $order_count . '"';
		}

		$html = '[woocommerce_'. $page_type . $params . ']';
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:woo_page]', 'brix_woo_page_content_block_stringified', 10, 2 );

/**
 * Custom template path for the WooCommerce page block.
 *
 * @since 1.2.6
 * @return string
 */
function brix_woo_page_block_template_path() {
	return BRIX_PRO_FOLDER . 'woocommerce/shortcodes/page/templates/woo_page_template.php';
}

add_filter( 'brix_block_master_template_path[type:woo_page]', 'brix_woo_page_block_template_path' );