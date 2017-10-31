( function( $ ) {
	"use strict";

	$.brix_transitions = {};

	/**
	 * Check for transition support.
	 *
	 * @return {Boolean}
	 */
	$.brix_transitions.checkSupport = function() {
		var s = $( "body" ).get( 0 ).style,
			transitionSupport = "transition" in s || "WebkitTransition" in s || "MozTransition" in s || "msTransition" in s || "OTransition" in s;

		return transitionSupport;
	};

	/**
	 * Binds a callback upon finishing a CSS transition.
	 *
	 * @param {String|Object} element The DOM element or its CSS selector.
	 * @param {Function} callback Function to be executed at the end of the transition.
	 */
	$.brix_transitions.callback = function( element, callback ) {
		var mode = typeof element;

		/**
		 * Transition end callback.
		 *
		 * @param  {Object} obj The transitioning object.
		 * @param  {Object} e The event object.
		 */
		function evTransition( obj, e ) {
			obj = $( obj );

			if ( obj.get( 0 ) !== e.target ) {
				return false;
			}

			if ( typeof callback === "function" ) {
				callback( $( obj ), e );
			}

			return false;
		};

		var event_string = "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev";

		if ( ! $.brix_transitions.checkSupport() ) {
			if ( typeof callback === "function" ) {
				$( element ).each( function() {
					callback( $( this ) );
				} );
			}
		}
		else {
			switch ( mode ) {
				case "object":
					$( element ).each( function() {
						$( this ).one( event_string, function( e ) {
							return evTransition( this, e );
						} );
					} );

					break;
				case "string":
					$( document ).on( event_string, element, function( e ) {
						return evTransition( this, e );
					} );

					break;
				default:
					break;
			}
		}
	};
} )( jQuery );