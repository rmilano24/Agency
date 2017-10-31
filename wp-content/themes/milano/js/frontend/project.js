( function( $ ) {
	"use strict";

	if ( ! $( "body" ).hasClass( "single-agncy_project" ) ) {
		return;
	}

	/**
	 * Open a single image file in a lightbox.
	 */
	function _open_featured_video( e ) {
		e.preventDefault();

		var href = $( this ).attr( "href" );

		var pswpElement = document.querySelectorAll( ".pswp" )[0],
			items = [ {
				html: $( "#agncy-fi-video" ).html()
			} ];

		var options = {
			index: 0,
			closeOnScroll: false,
			tapToClose: false,
			shareButtons: false
		};

		var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );

		gallery.listen( "afterChange", function() {
			$( ".pswp video" ).get( 0 ).play();
		} );

		gallery.init();

		return false;
	};

	function _put_featured_in_place() {
		var el = $( ".agncy-ph-sp-fi" ),
			el_clone = $( ".agncy-ph-sp-fi_cloned" ),
			left = el.offset().left,
			top = el.offset().top,
			right = $( window ).width() - ( left + el.outerWidth() ),
			bottom = $( window ).height() - ( top + el.outerHeight() );

		el_clone
			.css(
				{
					"top": top,
					"left": left,
					"right": right,
					"bottom": bottom
				} );

		setTimeout( function() {
			el_clone.remove();
			$( "body" ).addClass( "single-agncy_project-loaded" );
		}, 1000 );
	};

	if ( $( "body.agncy-single-project-type-c" ).length ) {
		setTimeout( function() {
			_put_featured_in_place();
		}, 1500 );

		function _setup() {
			$( ".agncy-ph-sp" ).css( "height", window.agncy_header_height_css() );
		}

		$( window ).on( "resize", _setup );
		$( document ).on( "ready", _setup );
	}
	else {
		$( "body" ).addClass( "single-agncy_project-loaded" );
	}

	$( document ).on( "click", ".agncy-ph-sp-fi-video > a, .agncy-ph-sp-open-video", _open_featured_video );

} )( jQuery );