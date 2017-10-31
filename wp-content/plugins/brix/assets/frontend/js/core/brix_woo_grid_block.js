( function( $ ) {
	"use strict";

	function Brix_WooGridBuilderBlock() {

		( new Brix_LoopBuilderBlock( {
			"handle": "woo-shop-grid",
			"block_selector": ".brix-section-column-block-woo_shop_grid",
			"loop_selector": ".brix-woo-shop-grid-block-loop-wrapper .products",
			"pagination_selector": ".brix-woo-shop-grid-block-pagination-wrapper",
			"replacements": ".woocommerce-result-count"
		} ) );

	};

	Brix_WooGridBuilderBlock();
} )( jQuery );