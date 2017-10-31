( function( $ ) {
	"use strict";

	$.brixf.delegate( '[data-key="brix_block"] [data-controller="type"]', "change", "html_block", function() {
		var field = $( this ),
			tab = field.parents( ".brix-tab" ).first(),
			snippet = $( "#snippet", tab ).next( ".CodeMirror" ).first()[0];

		if ( typeof snippet.CodeMirror !== "undefined" ) {
			snippet.CodeMirror.refresh();
		}
	} );

	/**
	 * Boot the block UI.
	 */
	$.brixf.ui.add( '[data-key="brix_block"] #snippet', function() {
		$( this ).each( function() {
			var textarea = this,
				myCodeMirror = CodeMirror.fromTextArea( textarea, {
					theme: "material",
					mode: "htmlmixed",
					indentWithTabs: true,
					lineNumbers: true
				} );

			myCodeMirror.on( "change", function( instance, changeObj ) {
				$( textarea ).text( myCodeMirror.getValue() );
			} );

			$( window ).on( "resize", function() {
				myCodeMirror.refresh();
			} );
		} );
	} );

} )( jQuery );