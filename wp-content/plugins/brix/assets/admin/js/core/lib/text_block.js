( function( $ ) {
	"use strict";

	/**
	 * Boot the block UI.
	 */
	$.brixf.ui.add( '[data-handle="alternate_skin"]', function() {
		$( this ).each( function() {
			var label = $( "label[for='alternate_skin']", this ),
				checkbox = $( "#alternate_skin", this ),
				editor_field = $( this ).next(),
				editor = $( "iframe", editor_field );

			label.on( "click", function() {
				var doc = editor[0].contentDocument || editor[0].contentWindow.document,
					body = doc.querySelectorAll( "body" );

				$( body ).toggleClass( "brix-skin-alternate" );
			} );

			if ( checkbox[0].checked && editor[0] ) {
				var doc = editor[0].contentDocument || editor[0].contentWindow.document,
					body = doc.querySelectorAll( "body" );

				$( body ).addClass( "brix-skin-alternate" );
			}
		} );
	} );

} )( jQuery );