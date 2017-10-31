<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * WooCommerce product categories shortcodes block class.
 *
 * @since 1.2.6
 */
class BrixBuilderWooProductCategoriesBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the text content block.
	 *
	 * @since 1.2.6
	 */
	public function __construct()
	{
		$this->_type = 'woo_product_categories';
		$this->_title = __( 'WooCommerce product categories', 'brix' );

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
			'handle' => 'number',
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
			'handle' => 'hide_empty',
			'type' => 'checkbox',
			'label' => __( 'Hide empty', 'brix' ),
		);

		$fields[] = array(
			'handle' => 'parent',
			'type' => 'text',
			'help' => __( 'Set the parent parameter to 0 to only display top level categories.', 'brix' ),
			'label' => __( 'Parent', 'brix' ),
		);

		$fields[] = array(
			'handle' => 'ids',
			'type' => 'text',
			'help' => __( 'Set IDs to a comma separated list of category IDs to only show those.', 'brix' ),
			'label' => __( 'IDs', 'brix' ),
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
function brix_register_woo_product_categories_block( $blocks ) {
	$blocks['woo_product_categories'] = array(
		'class'       => 'BrixBuilderWooProductCategoriesBlock',
		'label'       => __( 'WooCommerce product categories', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'woocommerce/i/woo_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'woocommerce/i/woo_icon.svg',
		'description' => __( 'Display product categories loop.', 'brix' ),
		'group'       => __( 'WooCommerce', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_woo_product_categories_block' );

/**
 * Define the appearance of the WooCommerce products content block in the admin.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_categories_content_block_admin_template( $html, $data ) {
	$html = '';

	$number     = $data->number;
	$columns    = $data->columns;
	$hide_empty = $data->hide_empty;
	$parent     = $data->parent;
	$ids        = $data->ids;
	$order_by   = $data->order_by;
	$order      = $data->order;

	$params     = array();

	if ( ! empty( $number ) ) {
		$params[] = 'number: ' . $number . '';
	}

	$params[] = 'columns: ' . $columns . '';
	$params[] = 'hide_empty: ' . $hide_empty . '';

	if ( $parent != '' ) {
		$params[] = 'parent: ' . $parent . '';
	}
	if ( ! empty( $ids ) ) {
		$params[] = 'ids: ' . $ids . '';
	}

	if ( ! empty( $order_by ) && ! empty( $order ) ) {
		$params[] = 'orderby: ' . $order_by . '';
		$params[] = 'order: ' . $order . '';
	}

	$html = __( 'Product categories', 'brix' );
	$html .= '<br><small>';
		$html .= implode( $params, ' | ' );
	$html .= '</small>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:woo_product_categories]', 'brix_woo_product_categories_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the WooCommerce products content block when stringified.
 *
 * @since 1.2.6
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_woo_product_categories_content_block_stringified( $html, $data ) {
	$html = '';

	$number     = $data->number;
	$columns    = $data->columns;
	$hide_empty = $data->hide_empty;
	$parent     = $data->parent;
	$ids        = $data->ids;
	$order_by   = $data->order_by;
	$order      = $data->order;

	$params     = array();

	if ( ! empty( $number ) ) {
		$params[] = 'number="' . $number . '"';
	}

	$params[] = 'columns="' . $columns . '"';
	$params[] = 'hide_empty="' . $hide_empty . '"';

	if ( $parent != '' ) {
		$params[] = 'parent="' . $parent . '"';
	}
	if ( ! empty( $ids ) ) {
		$params[] = 'ids="' . $ids . '"';
	}

	if ( ! empty( $order_by ) && ! empty( $order ) ) {
		$params[] = 'orderby="' . $order_by . '"';
		$params[] = 'order="' . $order . '"';
	}

	$html = '[product_categories ' . implode( $params, ' ' ) . ']';

	return $html;
}

add_filter( 'brix_block_stringified[type:woo_product_categories]', 'brix_woo_product_categories_content_block_stringified', 10, 2 );

/**
 * Custom template path for the WooCommerce products block.
 *
 * @since 1.2.6
 * @return string
 */
function brix_woo_product_categories_block_template_path() {
	return BRIX_PRO_FOLDER . 'woocommerce/shortcodes/product-categories/templates/product-categories_template.php';
}

add_filter( 'brix_block_master_template_path[type:woo_product_categories]', 'brix_woo_product_categories_block_template_path' );