( function( $ ) {
	"use strict";

	/**
	 * Get the current builder container.
	 */
	window.brix_parent = function( el, parent_selector ) {
		return $( el ).parents( parent_selector ).first();
	};

	/**
	 * Get the current builder container.
	 */
	window.brix_box = function( el ) {
		return brix_parent( el, ".brix-box" );
	};

	/**
	 * Slide to include a particular element in the viewport.
	 */
	window.brix_maybe_scroll = function( element, callback ) {
		element = $( element ).get( 0 );

		var rect = element.getBoundingClientRect(),
			in_viewport =
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= ( window.innerHeight || document.documentElement.clientHeight ) &&
				rect.right <= ( window.innerWidth || document.documentElement.clientWidth );

		if ( in_viewport ) {
			if ( callback ) {
				callback();
			}

			return;
		}

		$( element ).scrollintoview( {
			duration: 400,
			easing: "easeInOutCubic",
			direction: "vertical",
			offset: 40,
			complete: callback
		} );
	};

	/**
	 * Reduce fraction.
	 */
	window._brix_reduce_fraction = function( numerator, denominator ) {
		var gcd = function gcd( a,b ){
			return b ? gcd( b, a%b ) : a;
		};

		gcd = gcd( numerator, denominator );

		return numerator/gcd + "/" + denominator/gcd;
	};

	/**
	 * Swap DOM elements.
	 */
	window._brix_swap_elements = function( elm1, elm2 ) {
		var parent1, next1,
			parent2, next2;

		parent1 = elm1.parentNode;
		next1   = elm1.nextSibling;
		parent2 = elm2.parentNode;
		next2   = elm2.nextSibling;

		parent1.insertBefore( elm2, next1 );
		parent2.insertBefore( elm1, next2 );
	};

	/**
	 * Color HEX to RGB.
	 */
	function brix_hex_to_rgb( hex ) {
	    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
	    hex = hex.replace(shorthandRegex, function( m, r, g, b ) {
	        return r + r + g + g + b + b;
	    });

	    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );

	    return result ? {
	        r: parseInt( result[1], 16 ),
	        g: parseInt( result[2], 16 ),
	        b: parseInt( result[3], 16 )
	    } : null;
	};

	/**
	 * Get color YIQ.
	 */
	function brix_get_color_yiq( rgb ) {
		return ( ( rgb.r * 299 ) + ( rgb.g * 587 ) + ( rgb.b * 114 ) ) / 1000;
	};

	/**
	 * Check if a color is bright.
	 */
	window.brix_is_color_bright = function( hex ) {
		var rgb = brix_hex_to_rgb( hex ),
			threshold = 204; // #ccc

		return brix_get_color_yiq( rgb ) > threshold;
	};

	/**
	 * Select all text in an element.
	 */
	window.brix_select_all_text = function( element ) {
		element = $( element ).get( 0 );

		var doc = document,
		    text = element,
		    range,
		    selection;
		if (doc.body.createTextRange) {
		    range = document.body.createTextRange();
		    range.moveToElementText(text);
		    range.select();
		} else if (window.getSelection) {
		    selection = window.getSelection();
		    range = document.createRange();
		    range.selectNodeContents(text);
		    selection.removeAllRanges();
		    selection.addRange(range);
		}
	}

	$( document ).on( "click", "#brix-status-report", function() {
		window.brix_select_all_text( this );
	} );

} )( jQuery );