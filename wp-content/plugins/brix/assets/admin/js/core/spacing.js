( function( $ ) {
	"use strict";

	/**
	 * Toggle simplified controls.
	 */
	$.brixf.delegate( "input[data-advanced]", "change", "spacing", function() {
		var breakpoint = $( this ).parents( ".brix-breakpoint" ).first(),
			maybe_simplify = $( ".brix-maybe-simplify", breakpoint );

		if ( ! $( this ).prop( "checked" ) ) {
			maybe_simplify.attr( "disabled", "" );
		}
		else {
			maybe_simplify.removeAttr( "disabled" );
		}
	} );

} )( jQuery );