( function( $ ) {
	"use strict";

	/**
	 * Binds a keydown event based on a subset of allowed keys.
	 *
	 * @param {String} key The key literal name.
	 * @param {Function} callback The event callback function.
	 * @param {Object} config The configuration object.
	 */
	$.ev_key = function( key, callback, config ) {
		if ( typeof callback !== "function" ) {
			throw new Error( "Callback is not a function." );
		}

		config = $.extend( {
			/* Stop event propagation. */
			stop: true,

			/* Custom component namespace. */
			namespace: "ev",

			/* True to keep executing the action. */
			persistent: false
		}, config );

		var map = {
				"enter": 13,
				"left": 37,
				"up": 38,
				"right": 39,
				"down": 40,
				"esc": 27,
				"space": 32
			},
			event = "keydown." + config.namespace;

		if ( config.persistent ) {
			$( window ).on( event, function( e ) {
				if ( map[key] && e.which === map[key] ) {
					callback( e );

					if ( config.stop ) {
						return false;
					}
				}

				return true;
			} );
		}
		else {
			$( window ).one( event, function( e ) {
				if ( map[key] && e.which === map[key] ) {
					callback( e );

					if ( config.stop ) {
						return false;
					}
				}

				return true;
			} );
		}
	};
} )( jQuery );