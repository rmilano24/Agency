( function( $ ) {
	"use strict";

	/**
	 * Removes an appended admin message.
	 */
	$.brixf.delegate( ".brix-close-persistent-message", "click", "messages", function() {
		$( this ).parents( '.brix-persistent-message' ).first().remove();

		return false;
	} );

	/**
	 * Triggers the saving action on the current tab.
	 *
	 * @param  {String} tab The current tab slug.
	 * @return {Boolean}
	 */
	window.brix_save_options_tab = function( tab ) {
		$.brixSaveRichTextareas( tab );

		var form = $( "form", tab ).first(),
			action = $( ".brix-btn-type-save[data-callback]", form ).first(),
			data = form.serialize().replace( /%5B%5D/g, '[]' ),
			nonce = $( "#ev" ).val();

		data += "&action=" + action.attr( "data-callback" );
		data += "&nonce=" + nonce;

		brix_idle_button( action );

		$.post(
			form.attr( "action" ),
			data,
			function( response ) {
				brix_unidle_button( action, response );
			},
			'json'
		);

		return false;
	};

	/**
	 * Hooks to the submit event of admin pages forms in order to trigger their
	 * saving action.
	 */
	$.brixf.on( ".brix-admin-page .brix-tab > form", "submit", "save-options-tab", function() {
		var tab = $( this ).parents( ".brix-tab" ).first();

		window.brix_save_options_tab( tab );

		return false;
	} );

} )( jQuery );