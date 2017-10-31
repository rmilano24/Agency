( function( $ ) {
	"use strict";

	if ( $( ".agncy-l-ov" ).length && window.innerWidth > agncy_mobile_threshold_value() ) {
		window.brix_extended_section_offset = 300;

		if ( $( "body" ).is( ".rtl" ) ) {
			window.brix_extended_section_offset_position = "right";
		}
	}

	/**
	 * Elements inview effects.
	 */
	$( document ).on( "brix_block_inview brix_column_inview brix_section_inview", "[data-agncy-effect]", function() {
		$( this ).addClass( "animated " + $( this ).attr( "data-agncy-effect" ) );
	} );

	/**
	 * SVG animation.
	 */
	$( document ).on( "brix-icon-loaded", ".brix-section-column-block[data-agncy-icon-animate] .brix-icon-wrapper .brix-icon", function() {
		if ( $( "[stroke]", this ).length ) {
			new Vivus( this, {
				duration: 100,
				pathTimingFunction: Vivus.EASE,
				animTimingFunction: Vivus.EASE
			} );
		}
	} );

	/**
	 * Make video backgrounds enter smoothly.
	 */
	$( window ).on( "brix_ready", function() {
		$( ".brix-background-type-video video" ).on( "canplay", function() {
			$( this ).addClass( "agncy-video-loaded" );
		} );
	} );

	/**
	 * Get thumbnail bounds for Brix galleries.
	 */
	window.Brix_Gallery_getThumbBoundsFn = function( index, items ) {
		// find thumbnail element
		var thumbnail = $( "img", items[ index ]._thumbnail ).get( 0 );

		// get window scroll Y
		var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
		// optionally get horizontal scroll

		// get position of element relative to viewport
		var rect = thumbnail.getBoundingClientRect(),
			top = rect.top;

		if ( $( "body" ).hasClass( "admin-bar" ) ) {
			top -= $( "#wpadminbar" ).outerHeight();
		}

		// w = width
		return {x:rect.left, y:top + pageYScroll, w:rect.width};
	};

} )( jQuery );