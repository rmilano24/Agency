( function( $ ) {
	"use strict";

	/**
	 * Switch between the available icon sets.
	 */
	$.brixf.delegate( ".brix-selected-icon-wrapper", "click", "icon", function() {
		var key = "brix-icon",
			ctrl = $( this ),
			field = ctrl.parents( ".brix-field" ).first(),
			selected_wrapper = $( ".brix-selected-icon-wrapper", field ),
			data = {
				"url": $( "[data-preview]", field ).attr( "src" ),
				"prefix": $( "[data-prefix]", field ).val(),
				"set": $( "[data-set]", field ).val(),
				"icon": $( "[data-icon]", field ).val(),
				"color": $( "[data-color]", field ).val(),
				"size": $( "[data-size]", field ).val(),
				"config": {
					modal: ctrl.attr( "data-use-modal" )
				}
			};

		var modal = new $.brixf.modal( key, data, {
			simple: true,

			save: function( data, after_save, nonce ) {
				$( "[data-preview]", field ).attr( "src", data["url"] );
				$( "[data-prefix]", field ).val( data["prefix"] );
				$( "[data-set]", field ).val( data["set"] );
				$( "[data-icon]", field ).val( data["icon"] );
				$( "[data-color]", field ).val( data["color"]["color"] );
				$( "[data-size]", field ).val( data["size"] );

				if ( data["icon"] ) {
					selected_wrapper.removeClass( "brix-empty" );
				}
				else {
					selected_wrapper.addClass( "brix-empty" );
				}
			}
		} );

		modal.open( function( content, key, _data ) {
			var modal_data = {
				"action": "brix_icon_modal_load",
				"data": _data,
				"nonce": ctrl.attr( "data-nonce" )
			};

			var origin = ".brix-modal-container[data-key='" + key + "']";
			$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

			$.post(
				ajaxurl,
				modal_data,
				function( response ) {
					response = $( response );

					$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
					content.html( response );

					$( "[data-icon-search]", content ).focus();

					setTimeout( function() {
						$.brixf.ui.build();
					}, 1 );
				}
			);
		} );

		return false;
	} );

	/**
	 * Remove the currently selected icon.
	 */
	$.brixf.delegate( ".brix-icon-remove", "click", "icon", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			selected_wrapper = $( ".brix-selected-icon-wrapper", field );

		selected_wrapper.addClass( "brix-empty" );

		$( "[data-preview]", field ).attr( "src", "" );
		$( "[data-prefix]", field ).val( "" );
		$( "[data-set]", field ).val( "" );
		$( "[data-icon]", field ).val( "" );
		$( "[data-color]", field ).val( "" );
		$( "[data-size]", field ).val( "" );

		$( "[data-preview]", field ).attr( "class", "brix-icon brix-component" )
			.css( "color", "" );

		return false;
	} );

	/**
	 * Prevent hitting the Enter key when searching through icons.
	 */
	$.brixf.delegate( "input[data-icon-search]", "keydown", "icon", function( e ) {
		if ( e.which == 13 ) {
			return false;
		}
	} );

	$.brixf.delegate( ".brix-icon-set h2", "click", "icon", function() {
		var set = $( this ).parents( ".brix-icon-set" );

		if ( set.hasClass( "brix-on" ) ) {
			set.removeClass( "brix-on" );
		}
		else {
			set.siblings().removeClass( "brix-on" );
			set.addClass( "brix-on" );

			$( "img[data-src]", set ).each( function() {
				$( this ).attr( "src", $( this ).attr( "data-src" ) );
				$( this ).removeAttr( "data-src" );
			} );
		}

		return false;
	} );

	/**
	 * Search through available icons.
	 */
	$.brixf.delegate( "input[data-icon-search]", "keyup", "icon", function() {
		var wrapper = $( this ).parents( ".brix-icon-sets-external-wrapper" ).first(),
			search = $( this ).val(),
			icons = $( ".brix-icon", wrapper ),
			sets = $( ".brix-icon-set", wrapper );

		if ( search != "" && search.length >= 2 ) {
			$( ".brix-icon-sets", wrapper ).addClass( "brix-searching" );
		}
		else {
			$( ".brix-icon-sets", wrapper ).removeClass( "brix-searching" );
		}

		sets.removeClass( "brix-on" );
		$( ".brix-found" ).removeClass( "brix-found" );

		if ( search.length >= 2 ) {
			icons = icons.filter( '[data-icon-stripped*="' + search + '"],[data-icon-aliases*="' + search + '"]' );

			icons.each( function() {
				$( this ).parents( ".brix-icon-set-icon" ).first().addClass( "brix-found" );
			} );

			$( ".brix-icon-set", wrapper ).each( function() {
				var found_icons = $( ".brix-found", this ).length;

				$( ".brix-icon-set-count", this ).html( found_icons );

				if ( found_icons ) {
					$( this ).addClass( "brix-on" );

					$( ".brix-found img[data-src]", this ).each( function() {
						$( this ).attr( "src", $( this ).attr( "data-src" ) );
						$( this ).removeAttr( "data-src" );
					} );
				}
			} );
		}
		else {
			$( ".brix-icon-set", wrapper ).each( function() {
				$( ".brix-icon-set-count", this ).html( $( "[data-icon-stripped]", this ).length );
			} );
		}

	} );

	$.brixf.delegate( ".brix-icon-preview-toggle", "click", "icon", function() {
		var toggle = $( this ),
			wrapper = $( this ).parents( ".brix-icon-sets-external-wrapper" ).first();

		wrapper.toggleClass( "brix-icon-controls-active" );

		return false;
	} );

	/**
	 * Select an icon.
	 */
	$.brixf.delegate( ".brix-icon-sets .brix-icon-set-icon", "click", "icon", function() {
		var icon = $( "img", this ),
			icon_wrapper = $( this ),
			wrapper = $( this ).parents( ".brix-icon-sets-external-wrapper" ).first(),
			icons = $( ".brix-icon-set-icon", wrapper );

		if ( icon.attr( "src" ) === "" ) {
			return false;
		}

		icons.removeClass( "brix-selected" );
		icon_wrapper.addClass( "brix-selected" );
		// $( ".brix-icon-sets", wrapper ).removeClass( "brix-searching" );

		$( "[data-icon-url]", wrapper ).val( icon.attr( "src" ) );
		$( "[data-icon-prefix]", wrapper ).val( icon.attr( "data-prefix" ) );
		$( "[data-icon-set]", wrapper ).val( icon.attr( "data-set" ) );
		$( "[data-icon-name]", wrapper ).val( icon.attr( "data-icon-name" ) );

		$( ".brix-icon-preview-wrapper img", wrapper ).attr( "src", icon.attr( "src" ) );
		$( ".brix-icon-preview-wrapper .brix-icon-name", wrapper ).html( icon.attr( "data-icon-name" ) );
		$( ".brix-icon-preview-wrapper .brix-icon-font-set", wrapper ).html( icon.attr( "data-set-name" ) );
		$( ".brix-icon-preview-wrapper", wrapper ).addClass( 'brix-icon-selected' );
		$( ".brix-icon-preview-wrapper .brix-icon-preview-data", wrapper ).removeClass( 'brix-empty-icon' );
		$( ".brix-icon-preview-wrapper .brix-icon-wrapper", wrapper ).removeClass( 'brix-empty-icon' );

		// $( "input[data-icon-search]", wrapper ).val( "" );

		$( ".brix-icon-set", wrapper ).each( function() {
			var icons_count = 0,
				search = $( "[data-icon-search]", wrapper ).val();

			if ( search != '' ) {
				icons_count = $( ".brix-found", this ).length;
			}
			else {
				icons_count = $( ".brix-icon", this ).length;
			}

			$( ".brix-icon-set-count", this ).html( icons_count );
		} );
	} );

} )( jQuery );