( function( $ ) {
	"use strict";

	$( document ).on( "submit", "#agncy-register-widget-area", function() {
		if ( typeof $( this ).serializeObject === "function" ) {
			var data = $( this ).serializeObject();

			if ( ! data.name ) {
				alert( agncy_sidebars.error_sidebar_no_name );

				return false;
			}

			return true;
		}

		return false;
	} );

	$( document ).on( "submit", "#agncy-remove-widget-area", function() {
		var confirmation = confirm( agncy_sidebars.error_sidebar_remove );

		return confirmation;
	} );
} )( jQuery );