( function( $ ) {
	"use strict";

	/**
	 * Remove a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoint-remove", "click", "breakpoint", function() {
		var breakpoint = $( this ).parents( ".brix-breakpoint" ).first(),
			id = breakpoint.attr( "data-id" ),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-breakpoints-value]", field ),
			breakpoints_value = $.parseJSON( input.val() );

		breakpoint.remove();

		delete breakpoints_value[id];

		if ( brix_breakpoints[id] ) {
			delete brix_breakpoints[id];
		}

		input.val( JSON.stringify( breakpoints_value ) );

		brix_populate_full_width_media_select( field );

		return false;
	} );

	/**
	 * Add a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoint-add", "click", "breakpoint", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			breakpoints_container = $( ".brix-breakpoints-container", field ),
			input = $( "[data-breakpoints-value]", field ),
			breakpoints_value = $.parseJSON( input.val() ),
			full_width_media_select = $( ".brix-full-width-media-query", field );

		if ( window.brix_add_new_breakpoint_modal ) {
			delete window.brix_add_new_breakpoint_modal;
		}

		window.brix_add_new_breakpoint_modal = new BrixBuilderModal(
			"brix_add_new_breakpoint",
			"brix_breakpoint_modal_load",
			{},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_breakpoints_i18n_strings.title;

				if ( data.label && data.label != "" ) {
					title = data.label;
				}

				data.custom = true;
				// data.builder = true;

				var id = 'custom_breakpoint_' + ( Date.now() / 1000 | 0 );

				breakpoints_value[id] = data;

				var html = '<div class="brix-breakpoint" data-id="' + id + '">';
						html += '<span class="brix-custom-breakpoint-label">' + title + '</span>';
						html += '<span class="brix-custom-breakpoint-media-query">' + data.media_query + '</span>';

						html += '<button class="brix-breakpoint-remove brix-btn brix-btn-type-action brix-btn-size-small brix-btn-style-text" type="button"><span class="">' + brix_breakpoints_i18n_strings.remove + '</span></button>';
					html += '</div>';

				breakpoints_container.append( html );

				input.val( JSON.stringify( breakpoints_value ) );

				brix_populate_full_width_media_select( field );
			}
		);

		return false;
	} );
} )( jQuery );