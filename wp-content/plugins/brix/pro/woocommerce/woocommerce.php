<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Query WooCommerce activation
 */
function brix_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

if ( ! brix_is_woocommerce_activated() ) {
	return;
}

/**
 * Add the WooCommerce group to the blocks selection modal.
 *
 * @param  array $groups The list of block groups
 * @return array
 */
function brix_woocommerce_blocks_group( $groups ) {
	$groups[] = __( 'WooCommerce', 'brix' );

	return $groups;
}

add_filter( 'brix_get_blocks_groups', 'brix_woocommerce_blocks_group' );

/**
 * WooCommerce widgets
 */

/* Cart widget block. */
require_once dirname( __FILE__ ) . '/widgets/cart.php';

/* Product Search widget block. */
require_once dirname( __FILE__ ) . '/widgets/product-search.php';

/* Product Search widget block. */
require_once dirname( __FILE__ ) . '/widgets/recently-viewed.php';

/* Top rated products widget block. */
require_once dirname( __FILE__ ) . '/widgets/top-rated-products.php';

/* Recent Reviews widget block. */
require_once dirname( __FILE__ ) . '/widgets/recent-reviews.php';

/* Product categories widget block. */
require_once dirname( __FILE__ ) . '/widgets/product-categories.php';

/* Products widget block. */
require_once dirname( __FILE__ ) . '/widgets/products.php';

/* Tag Cloud widget block. */
require_once dirname( __FILE__ ) . '/widgets/product-tag-cloud.php';

/**
 * WooCommerce shortcodes
 */

/* Page shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/page/page.php';

/* Products shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/products/products.php';

/* Product shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/product/product.php';

/* Add to cart shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/add-to-cart/add-to-cart.php';

/* Product category shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/product-category/product-category.php';

/* Product categories shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/product-categories/product-categories.php';

/* Product attribute shortcode block. */
require_once dirname( __FILE__ ) . '/shortcodes/product-attribute/product-attribute.php';

/**
 * WooCommerce blocks
 */

/* Shop grid block. */
require_once dirname( __FILE__ ) . '/blocks/shop-grid/shop-grid.php';
