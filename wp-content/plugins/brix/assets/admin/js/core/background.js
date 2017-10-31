( function( $ ) {
	"use strict";

	$.brixf.delegate( ".brix-background-radio-attributes ", "click", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-radio-attributes-wrapper" );

		if ( ! $( this ).hasClass( "active" ) ) {
			$( ".active", wrapper ).removeClass( "active" );
			$( this ).addClass( "active" );
		}

		return false;
	} );



	$.brixf.delegate( ".brix-background-color-partial-nav input", "change", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-color-partial-wrapper" );

		wrapper.attr( "data-type", $( ".brix-background-color-partial-nav input:checked", wrapper ).val() );
	} );

	$.brixf.delegate( ".brix-background-overlay-partial-nav input", "change", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-overlay-partial-wrapper" );

		wrapper.attr( "data-type", $( ".brix-background-overlay-partial-nav input:checked", wrapper ).val() );
	} );

	$.brixf.delegate( ".brix-background-image-motion input", "change", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-image-motion" );

		wrapper.attr( "data-type", $( "input:checked", wrapper ).val() );
	} );

	$.brixf.delegate( ".brix-background-radio-attributes[data-value]", "click", "background", function() {
		var value = $( this ).attr( "data-value" ),
			wrapper = $( this ).parents( ".brix-background-radio-attributes-wrapper" ).first();

		$( ".active", wrapper ).removeClass( "active" );
		$( this ).addClass( "active" );
		$( "input[type='hidden']", wrapper ).val( value );

		return false;
	} );

} )( jQuery );