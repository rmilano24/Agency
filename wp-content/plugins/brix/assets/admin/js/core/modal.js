( function( $ ) {
	"use strict";

	/**
	 * Cleanup function for builder modals.
	 */
	$( window ).on( "brix-modal-close", function() {
		$( ".popper" ).remove();
	} );

	/**
	 * Builder components editing modal.
	 *
	 * @param {String}   key      The modal key.
	 * @param {String}   action   The modal AJAX action.
	 * @param {Object}   data     The data object to be passed to the modal.
	 * @param {Function} callback The function to be called upon saving.
	 * @param {Function} startup_callback The function to be called when opening the modal.
	 * @param {Boolean}  wait     Set to a function to wait for the save function to be complete before closing the modal.
	 */
	window.BrixBuilderModal = function( key, action, data, callback, startup_callback, wait ) {
		var modal = new $.brixf.modal( key, data, {
			wait: wait,
			class: "brix-modal",
			save: function( data, after_save, nonce ) {
				if ( callback ) {
					callback( data, after_save, nonce );
				}
			}
		} );

		modal.open( function( content, key, _data ) {
			var modal_data = {
				"action": action,
				"data": _data
			};

			var origin = ".brix-modal-container[data-key='" + key + "']";
			$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

			$.post(
				ajaxurl,
				modal_data,
				function( response ) {
					$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
					content.html( response );

					setTimeout( function() {
						$.brixf.ui.build();

						if ( startup_callback ) {
							startup_callback();
						}
					}, 1 );
				}
			);
		} );

		return modal;
	};

} )( jQuery );
