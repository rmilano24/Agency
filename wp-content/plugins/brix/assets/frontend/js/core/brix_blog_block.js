( function( $ ) {
	"use strict";

	var Brix_BlogBuilderBlock = function() {

		( new Brix_LoopBuilderBlock( {
			"handle": "blog",
			"block_selector": ".brix-section-column-block-blog",
			"loop_selector": ".brix-blog-block-loop-wrapper",
			"pagination_selector": ".brix-blog-block-pagination-wrapper"
		} ) );

	};

	Brix_BlogBuilderBlock();
} )( jQuery );