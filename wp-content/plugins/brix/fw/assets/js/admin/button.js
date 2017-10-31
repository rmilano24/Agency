( function( $ ) {
	"use strict";

	var idle_class = "brix-btn-idle";

	/**
	 * Set a button to idle.
	 */
	window.brix_idle_button = function( btn ) {
		$( btn )
			.addClass( idle_class )
			.attr( "disabled", "disabled" )
			.trigger( "start.brix_button" );
	}

	/**
	 * Handle the button response.
	 */
	function brix_btn_handle_response( btn, response ) {
		var tooltip = false;

		$( btn ).addClass( "brix-btn-complete" );

		if ( response ) {
			$( btn )
				.addClass( "brix-btn-" + response.type );

			tooltip = brix_create_tooltip( btn, response.message, {
				class: "brix-tooltip-response-" + response.type
			} );
		}

		setTimeout( function() {
			$( btn ).removeAttr( "data-title" );
			$( btn ).removeClass( "brix-btn-complete" );

			if ( response ) {
				if ( tooltip ) {
					brix_tooltip_destroy( tooltip );
				}

				$( btn ).removeClass( "brix-btn-" + response.type );
			}
		}, 1500 );
	}

	/**
	 * Unidle a button.
	 */
	window.brix_unidle_button = function( btn, response ) {
		var s = $( "body" ).get( 0 ).style,
			transitionSupport = "transition" in s || "WebkitTransition" in s || "MozTransition" in s || "msTransition" in s || "OTransition" in s;

		if ( transitionSupport ) {
			var event_string = "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev";

			$( btn ).on( event_string, function( e ) {
				$( btn ).off( event_string );

				brix_btn_handle_response( btn, response );
			} );
		}
		else {
			brix_btn_handle_response( btn, response );
		}

		$( btn )
			.removeClass( idle_class )
			.removeAttr( "disabled" )
			.trigger( "done.brix_button" );
	}

	/**
	 * When clicking a button with an AJAX action attached to it, set it to idle.
	 */
	// $.brixf.delegate( ".brix-btn[data-callback]", "click", "brix_button", function() {
	// 	brix_idle_button( this );
	// } );

	/**
	 * After executing the AJAX action attached to a button, unidle it.
	 */
	// $( document ).on( "done.brix_button", ".brix-btn[data-callback]", function() {
	// 	brix_unidle_button( this );
	// } );

} )( jQuery );