( function( $ ) {
	"use strict";

	/**
	 * Remove a toggle.
	 */
	$.brixf.delegate( ".brix-accordion-block-toggle-remove", "click", "accordion_modal", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			trigger = $( this ).parents( ".brix-accordion-block-toggle-trigger" ),
			index = trigger.index(),
			input = $( "[data-accordion-value]", field ),
			accordion_value = $.parseJSON( input.val() );

		trigger.remove();

		accordion_value.splice( index, 1 );

		input.val( JSON.stringify( accordion_value ) );

		return false;
	} );

	/**
	 * Preview a toggle.
	 */
	$.brixf.delegate( ".brix-accordion-block-toggle-preview", "click", "accordion_modal", function() {
		var trigger = $( this ).parents( ".brix-accordion-block-toggle-trigger" ).first(),
			bundle = $( this ).parents( ".brix-block-accordion" ).first(),
			triggers = $( ".brix-accordion-block-toggle-trigger", bundle ),
			mode = $( "[data-handle='mode'] select[name]", bundle ).val();

		if ( mode !== "toggle" ) {
			if ( trigger.hasClass( "brix-preview" ) ) {
				trigger.removeClass( "brix-preview" );
			}
			else {
				triggers.removeClass( "brix-preview" );
				trigger.addClass( "brix-preview" );
			}
		}
		else {
			trigger.toggleClass( "brix-preview" );
		}

		return false;
	} );

	/**
	 * Edit a toggle.
	 */
	$.brixf.delegate( ".brix-accordion-block-toggle-title", "click", "accordion_modal", function() {
		var trigger_title = $( this ),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-accordion-value]", field ),
			index = $( this ).parents( ".brix-accordion-block-toggle-trigger" ).index(),
			accordion_value = $.parseJSON( input.val() );

		if ( window.brix_edit_toggle_modal ) {
			delete window.brix_edit_toggle_modal;
		}

		window.brix_edit_toggle_modal = new BrixBuilderModal(
			"brix_edit_toggle",
			"brix_toggle_modal_load",
			accordion_value[index] ? accordion_value[index] : {},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_toggle_i18n_strings.title;

				if ( data.title && data.title != "" ) {
					title = data.title;
				}

				trigger_title.html( title );

				accordion_value[index] = data;

				input.val( JSON.stringify( accordion_value ) );
			}
		);

		return false;
	} );

	/**
	 * Add a toggle.
	 */
	$.brixf.delegate( ".brix-add-new-toggle", "click", "accordion_modal", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			accordion_container = $( ".brix-accordion-container > ul", field ),
			add_new_toggle_el = $( ".brix-accordion-container > ul .brix-add-new-toggle-wrapper", field ),
			input = $( "[data-accordion-value]", field ),
			accordion_value = $.parseJSON( input.val() );

		if ( window.brix_add_new_toggle_modal ) {
			delete window.brix_add_new_toggle_modal;
		}

		window.brix_add_new_toggle_modal = new BrixBuilderModal(
			"brix_add_new_toggle",
			"brix_toggle_modal_load",
			{},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_toggle_i18n_strings.title,
					content = "";

				if ( data.title && data.title != "" ) {
					title = data.title;
				}

				if ( data.content && data.content != "" ) {
					content = data.content;
				}

				accordion_value.push( data );

				var html = '<li class="brix-accordion-block-toggle-trigger">';
					html += '<span class="brix-accordion-block-toggle-preview"></span>';
					html += '<div class="brix-accordion-block-content-wrapper">';
						html += '<span class="brix-accordion-block-toggle-index">' + (accordion_value.length - 1) + '</span>';
						html += '<span class="brix-accordion-block-toggle-title">' + title + '</span>';
						html += '<span class="brix-accordion-block-toggle-content">' + content + '</span>';
					html += '</div>';
					html += '<span class="brix-accordion-block-toggle-remove"></span>';
				html += '</li>';

				add_new_toggle_el.before( html );

				input.val( JSON.stringify( accordion_value ) );
			}
		);

		return false;
	} );

	/**
	 * Boot the block UI.
	 */
	$.brixf.ui.add( ".brix-accordion-container", function() {
		$( this ).each( function() {
			var field = $( this ).parents( ".brix-field" ).first(),
			accordion_container = $( ".brix-accordion-container > ul", field ),
			input = $( "[data-accordion-value]", field ),
			accordion_value = {},
			position_from = 0,
			position_to = 0;

			accordion_container.sortable( {
				items: "li:not(.brix-add-new-toggle-wrapper)",
				tolerance: "pointer",
				start: function( event, ui ) {
					accordion_value = $.parseJSON( input.val() );
					position_from = ui.item.index();
				},
				stop: function( event, ui ) {
					var old_accordion_value = $.extend( true, {}, accordion_value );

					position_to = ui.item.index();

					accordion_value[position_to] = old_accordion_value[position_from];
					accordion_value[position_from] = old_accordion_value[position_to];

					$( ".brix-accordion-block-toggle-index", field ).each( function( i ) { $( this ).html( i ) } );

					input.val( JSON.stringify( accordion_value ) );
				}
			} );
		} );
	} );

} )( jQuery );
