<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce products shortcodes block class.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductsBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_type = 'woo_products';
		$this->_title = __( 'WooCommerce products list', 'brix' );

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
			'handle' => 'woo_products_type',
			'type' => 'select',
			'label' => __( 'List type', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => array(
					'recent_products'       => __( 'Recent products', 'brix' ),
					'featured_products'     => __( 'Featured products', 'brix' ),
					'best_selling_products' => __( 'Best selling products', 'brix' ),
					'sale_products'         => __( 'Sale products', 'brix' ),
					'related_products'      => __( 'Related products', 'brix' ),
					'top_rated_products'    => __( 'Top rated products', 'brix' ),
					'products'              => __( 'Multiple products', 'brix' ),
				)
			)
		);

			$fields[] = array(
				'handle' => 'per_page',
				'type' => 'number',
				'label' => __( 'Number of products', 'brix' ),
				'config' => array(
					'min' => 0,
					'visible' => array( 'woo_products_type' => '!=products' )
				)
			);

			$fields[] = array(
				'handle' => 'multiple',
				'type' => 'select',
				'label' => __( 'Choose the type', 'brix' ),
				'config' => array(
					'data' => array(
						'ids' => __( 'IDs', 'brix' ),
						'skus' => __( 'SKUs', 'brix' ),
					),
					'controller' => true,
					'visible' => array( 'woo_products_type' => 'products' )
				)
			);

				$fields[] = array(
					'handle' => 'ids',
					'type' => 'text',
					'label' => __( 'Products IDs', 'brix' ),
					'help' => __( 'Comma separated values', 'brix' ),
					'config' => array(
						'visible' => array( 'multiple' => 'ids' )
					)
				);

				$fields[] = array(
					'handle' => 'skus',
					'type' => 'text',
					'label' => __( 'Products SKUs', 'brix' ),
					'help' => __( 'Comma separated values', 'brix' ),
					'config' => array(
						'visible' => array( 'multiple' => 'skus' )
					)
				);

			$fields[] = array(
				'handle' => 'columns',
				'type' => 'select',
				'label' => __( 'Columns', 'brix' ),
				'config' => array(
					'data' => array(
						'1' => __( '1 Column', 'brix' ),
						'2' => __( '2 Columns', 'brix' ),
						'3' => __( '3 Columns', 'brix' ),
						'4' => __( '4 Columns', 'brix' ),
						'5' => __( '5 Columns', 'brix' ),
						'6' => __( '6 Columns', 'brix' ),
					),
					'visible' => array( 'woo_products_type' => '!=related_products' )
				)
			);

			$fields[] = array(
				'handle' => 'order_by',
				'type' => 'select',
				'label' => __( 'Order by', 'brix' ),
				'config' => array(
					'controller' => true,
					'data' => array(
						'none'          => __( 'No order', 'brix' ),
						'ID'            => __( 'Post ID', 'brix' ),
						'author'        => __( 'Author', 'brix' ),
						'title'         => __( 'Title', 'brix' ),
						'name'          => __( 'Post name', 'brix' ),
						'type'          => __( 'Post Type', 'brix' ),
						'date'          => __( 'Date', 'brix' ),
						'modified'      => __( 'Dast modified date', 'brix' ),
						'parent'        => __( 'Post/page parent ID', 'brix' ),
						'rand'          => __( 'Random order', 'brix' ),
						'comment_count' => __( 'Number of comments', 'brix' ),
						'relevance'     => __( 'Relevance', 'brix' ),
						'menu_order'    => __( 'Page Order', 'brix' ),
					),
				)
			);

				$fields[] = array(
					'handle' => 'order',
					'type' => 'select',
					'label' => __( 'Order', 'brix' ),
					'config' => array(
						'data' => array(
							'asc'  => __( 'Ascending', 'brix' ),
							'desc' => __( 'Descending', 'brix' ),
						),
						'visible' => array( 'order_by' => '!=none' )
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
function brix_register_woo_products_block( $blocks ) {
	$blocks['woo_products'] = array(
		'class'       => 'BrixBuilderWooProductsBlock',
		'label'       => __( 'WooCommerce products list', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( 'Display a list of WooCommerce products.', 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_products_block' );

/**
 * Define the appearance of the WooCommerce products content block in the admin.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_products_content_block_admin_template( $html, $data ) {
	$html = '';

	$type     = $data->woo_products_type;
	$per_page = $data->per_page;
	$columns  = $data->columns;
	$order_by = $data->order_by;
	$order    = $data->order;
	$multiple = $data->multiple;
	$ids      = $data->ids;
	$skus     = $data->skus;
	$params   = array();

	if ( $type != 'products' ) {
		$params[] = 'per_page: ' . $per_page . '';
	}

	if ( $type == 'products' ) {
		if ( $multiple == 'ids' && ! empty( $ids ) ) {
			$params[] = 'ids: ' . $ids . '';
		} else if ( $multiple == 'skus' && ! empty( $skus ) ) {
			$params[] = 'skus: ' . $skus . '';
		}
	}

	$params[] = 'columns: ' . $columns . '';
	$params[] = 'orderby: ' . $order_by . '';
	$params[] = 'order: ' . $order . '';

	if ( $type == 'recent_products' ) {
		$html = __( 'Recent products', 'brix' );
	}
	else if ( $type == 'featured_products' ) {
		$html = __( 'Featured products', 'brix' );
	}
	else if ( $type == 'best_selling_products' ) {
		$html = __( 'Best selling products', 'brix' );
	}
	else if ( $type == 'sale_products' ) {
		$html = __( 'Sale products', 'brix' );
	}
	else if ( $type == 'related_products' ) {
		$html = __( 'Related products', 'brix' );
	}
	else if ( $type == 'top_rated_products' ) {
		$html = __( 'Top rated products', 'brix' );
	}
	else if ( $type == 'products' ) {
		$html = __( 'Multiple products', 'brix' );
	}

	$html .= '<br><small>';
		$html .= implode( $params, ' | ' );
	$html .= '</small>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:woo_products]', 'brix_woo_products_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the WooCommerce products content block when stringified.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_products_content_block_stringified( $html, $data ) {
	$html = '';

	$shortcode_type = $data->woo_products_type;
	$per_page       = $data->per_page;
	$columns        = $data->columns;
	$order_by       = $data->order_by;
	$order          = $data->order;
	$ids            = $data->ids;
	$skus           = $data->skus;
	$multiple       = $data->multiple;

	$params         = array();

	if ( ! empty( $shortcode_type ) ) {
		if ( $shortcode_type != 'products' ) {
			$params[] = 'per_page="' . $per_page . '"';
		}

		if ( $shortcode_type == 'products' ) {
			if ( $multiple == 'ids' && ! empty( $ids ) ) {
				$params[] = 'ids="' . $ids . '"';
			} else if ( $multiple == 'skus' && ! empty( $skus ) ) {
				$params[] = 'skus="' . $skus . '"';
			}
		}
	}

	if ( ! empty( $columns ) ) {
		$params[] = 'columns="' . $columns . '"';
	}
	if ( ! empty( $order_by ) && ! empty( $order ) ) {
		$params[] = 'orderby="' . $order_by . '"';
		$params[] = 'order="' . $order . '"';
	}

	if ( ! empty( $shortcode_type ) ) {
		$html = '[' . $shortcode_type . ' ' . implode( $params, ' ' ) . ']';
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:woo_products]', 'brix_woo_products_content_block_stringified', 10, 2 );

/**
 * Custom template path for the WooCommerce products block.
 *
 * @since 1.2.6
 * @return string
 */
function brix_woo_products_block_template_path() {
	return BRIX_PRO_FOLDER . 'woocommerce/shortcodes/products/templates/woo_products_template.php';
}

add_filter( 'brix_block_master_template_path[type:woo_products]', 'brix_woo_products_block_template_path' );