<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce product category shortcodes block class.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductCategoryBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_type = 'woo_product_category';
		$this->_title = __( 'WooCommerce product category', 'brix' );

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
			'handle' => 'category',
			'type' => 'text',
			'label' => __( 'Category slug', 'brix' ),
		);

		$fields[] = array(
			'handle' => 'per_page',
			'type' => 'number',
			'label' => __( 'Number of products', 'brix' ),
			'config' => array(
				'min' => 0
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
				)
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
function brix_register_woo_product_category_block( $blocks ) {
	$blocks['woo_product_category'] = array(
		'class'       => 'BrixBuilderWooProductCategoryBlock',
		'label'       => __( 'WooCommerce product category', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( 'Show multiple products in a category by slug.', 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_product_category_block' );

/**
 * Define the appearance of the WooCommerce products content block in the admin.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_category_content_block_admin_template( $html, $data ) {
	$html = '';

	$category = $data->category;
	$per_page = $data->per_page;
	$columns  = $data->columns;
	$order_by = $data->order_by;
	$order    = $data->order;
	$params   = array();

	if ( ! empty( $per_page ) ) {
		$params[] = 'per_page: ' . $per_page . '';
	}
	if ( ! empty( $columns ) ) {
		$params[] = 'columns: ' . $columns . '';
	}
	if ( ! empty( $order_by ) && ! empty( $order ) ) {
		$params[] = 'orderby: ' . $order_by . '';
		$params[] = 'order: ' . $order . '';
	}


	$html = __( 'Category', 'brix' ) . ': ' . $category;

	$html .= '<br><small>';
		$html .= implode( $params, ' | ' );
	$html .= '</small>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:woo_product_category]', 'brix_woo_product_category_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the WooCommerce products content block when stringified.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_category_content_block_stringified( $html, $data ) {
	$html = '';

	$category = $data->category;
	$per_page = $data->per_page;
	$columns  = $data->columns;
	$order_by = $data->order_by;
	$order    = $data->order;
	$params   = array();

	if ( ! empty( $category ) ) {
		$params[] = 'category="' . $category . '"';
	}

	if ( ! empty( $per_page ) ) {
		$params[] = 'per_page="' . $per_page . '"';
	}
	if ( ! empty( $columns ) ) {
		$params[] = 'columns="' . $columns . '"';
	}
	if ( ! empty( $order_by ) && ! empty( $order ) ) {
		$params[] = 'orderby="' . $order_by . '"';
		$params[] = 'order="' . $order . '"';
	}

	$html = '[product_category ' . implode( $params, ' ' ) . ']';

	return $html;
}

add_filter( 'brix_block_stringified[type:woo_product_category]', 'brix_woo_product_category_content_block_stringified', 10, 2 );

/**
 * Custom template path for the WooCommerce products block.
 *
 * @since 1.2.6
 * @return string
 */
function brix_woo_product_category_block_template_path() {
	return BRIX_PRO_FOLDER . 'woocommerce/shortcodes/product-category/templates/product-category_template.php';
}

add_filter( 'brix_block_master_template_path[type:woo_product_category]', 'brix_woo_product_category_block_template_path' );