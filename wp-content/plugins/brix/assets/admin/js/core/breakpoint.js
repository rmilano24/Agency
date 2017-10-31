( function( $ ) {
	"use strict";

	/**
	 * Populate the media query select for full width layouts.
	 */
	window.brix_populate_full_width_media_select = function( field ) {
		var full_width_media_select = $( ".brix-full-width-media-query select", field ),
			input = $( "[data-breakpoints-value]", field ),
			breakpoints_value = $.parseJSON( input.val() );

		var prbrix_val = full_width_media_select.val();
		var breakpoints = $( ".brix-breakpoint[data-id]", field );

		full_width_media_select.html( "" );

		var selected = '';

		if ( "" === prbrix_val ) {
			selected = "selected";
		}

		full_width_media_select.append( "<option " + selected + " value=''>-</option>" );
		selected = '';

		breakpoints.each( function() {
			var id = $( this ).attr( "data-id" ),
				label = $( ".brix-custom-breakpoint-label", this ).text();

			if ( id === prbrix_val ) {
				selected = "selected";
			}

			full_width_media_select.append( "<option " + selected + " value='" + id + "'>" + label + "</option>" );
		} );

		if ( breakpoints.filter( "[data-id='" + prbrix_val + "']" ).length === 0 ) {
			full_width_media_select.val( "" );
		}
		else {
			full_width_media_select.val( prbrix_val );
		}
	};

	/**
	 * Reset a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoint-reset", "click", "breakpoint", function() {
		var breakpoint = $( this ).parents( ".brix-breakpoint" ).first(),
			id = breakpoint.attr( "data-id" ),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-breakpoints-value]", field ),
			trigger_title = $( ".brix-custom-breakpoint-label", breakpoint ).first(),
			trigger_media = $( ".brix-custom-breakpoint-media-query", breakpoint ).first(),
			breakpoints_value = $.parseJSON( input.val() );

		delete breakpoints_value[id];
		$( this ).remove();

		input.val( JSON.stringify( breakpoints_value ) );

		if ( brix_breakpoints[id] ) {
			delete brix_breakpoints[id].gutter;
			delete brix_breakpoints[id].baseline;

			var original = $.parseJSON( breakpoint.attr( "data-original" ) );

			trigger_media.html( original.media_query );
			trigger_title.html( original.label );
		}

		brix_populate_full_width_media_select( field );

		return false;
	} );

	/**
	 * Edit a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoints-container .brix-breakpoint", "click", "breakpoint", function() {
		var breakpoint = $( this ),
			trigger_title = $( ".brix-custom-breakpoint-label", this ).first(),
			trigger_media = $( ".brix-custom-breakpoint-media-query", this ).first(),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-breakpoints-value]", field ),
			index = $( this ).index(),
			id = $( this ).attr( "data-id" ),
			breakpoints_value = $.parseJSON( input.val() ),
			full_width_media_select = $( ".brix-full-width-media-query", field ),
			custom = true;

		if ( window.brix_edit_breakpoint_modal ) {
			delete window.brix_edit_breakpoint_modal;
		}

		var value = {},
			reset = false;

		if ( brix_breakpoints[id] ) {
			reset = true;
		}

		if ( breakpoints_value[id] ) {
			value = breakpoints_value[id];
		}
		else if ( brix_breakpoints[id] ) {
			value = brix_breakpoints[id];
			custom = false;
		}

		window.brix_edit_breakpoint_modal = new BrixBuilderModal(
			"brix_edit_breakpoint",
			"brix_breakpoint_modal_load",
			value,
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_breakpoints_i18n_strings.title;

				if ( data.label && data.label != "" ) {
					title = data.label;
				}

				trigger_title.html( title );
				trigger_media.html( data.media_query );

				$( ".brix-breakpoint-reset", breakpoint ).remove();

				if ( breakpoint.attr( "data-original" ) ) {
					data._original = $.parseJSON( breakpoint.attr( "data-original" ) );
				}

				if ( reset && ! custom ) {
					trigger_title.after( '<button class="brix-breakpoint-reset brix-btn brix-btn-type-action brix-btn-size-small brix-btn-style-text" type="button"><span class="">' + brix_breakpoints_i18n_strings.reset + '</span></button>' );
				}

				if ( custom ) {
					data.custom = custom;
				}

				breakpoints_value[id] = data;

				input.val( JSON.stringify( breakpoints_value ) );

				brix_populate_full_width_media_select( field );
			}
		);

		return false;
	} );

} )( jQuery );
