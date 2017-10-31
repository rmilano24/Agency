( function( $ ) {
	"use strict";

	/**
	 * Initialize tabs.
	 */
	$( document ).on( "init.brixf.tabs", ".brix-tabs", function() {
		$( ".brix-tab.brix-active" ).addClass( "brix-tab-in" );
	} );

	/**
	 * Pre-switch hook.
	 */
	$( document ).on( "switch.brixf.tabs", ".brix-tabs", function() {
		var panels = $( "> .brix-tab-container", this ),
			tabs = $( "> .brix-tab", panels );

		panels.css( "height", $( ".brix-active", panels ).outerHeight() );
		tabs.removeClass( "brix-tab-in" );
	} );

	/**
	 * Post-switch hook.
	 */
	$( document ).on( "switched.brixf.tabs", ".brix-tabs", function() {
		var panels = $( "> .brix-tab-container", this ),
			tabs = $( "> .brix-tab", panels );

		$.ev_transitions.callback( panels, function( el ) {
			panels.css( "height", "" );
		} );

		setTimeout( function() {
			tabs.filter( ".brix-active" ).addClass( "brix-tab-in" );
		}, 1 );

		panels.css( "height", $( ".brix-active", panels ).outerHeight() );
	} );

} )( jQuery );