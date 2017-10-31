( function( $ ) {
	"use strict";

	/**
	 * Adding the rich textarea component to the UI building queue.
	 */
	window.brixf_ui_rich_textareas = 0;

	$.brixf.ui.add( "textarea.brix-rich", function() {
		$( this ).each( function() {
			window.brixf_ui_rich_textareas++;

			var id = $( this ).attr( "id" );

			id = id.replace( /\[/g, "_" );
			id = id.replace( /\]/g, "_" );

			$( this ).attr( "id", id + "-" + window.brixf_ui_rich_textareas );

			// wp.editor.initialize( $( this ).attr( "id" ), wp.editor.getDefaultSettings() );
			$( this ).wp_editor();
		} );
	} );

} )( jQuery );