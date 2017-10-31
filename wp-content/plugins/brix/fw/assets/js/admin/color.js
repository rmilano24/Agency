( function( $ ) {
	"use strict";

	/**
	 * RGB(a) to Hex.
	 */
	function _rgb2hex( rgb ){
		rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);

		return (rgb && rgb.length === 4) ? "#" +
			("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
			("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
			("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
	}

	/**
	 * RGB(a) to Hex.
	 */
	function _rgb2opacity( rgb ){
		rgb = rgb.replace( "rgba(", "" );
		rgb = rgb.replace( ")", "" );
		rgb = rgb.replace( " ", "" );
		rgb = rgb.split( "," );

		return rgb[rgb.length-1];
	}

	/**
	 * Select a color from the palette.
	 */
	$.brixf.delegate( ".brix-color-preset", "click", "color", function() {
		var wrapper = $( this ).parents( ".brix-color-presets-manager-wrapper" ).first(),
			hex_input = $( "[data-hex-value-input]", wrapper );

		if ( $( this ).hasClass( "brix-selected" ) ) {
			$( this ).removeClass( "brix-selected" );

			hex_input.val( "" );
		}
		else {
			$( ".brix-selected", wrapper ).removeClass( "brix-selected" );
			$( this ).addClass( "brix-selected" );

			hex_input.val( $( this ).attr( "data-hex" ) );
		}

		return false;
	} );

	/**
	 * Delete a color preset.
	 */
	$.brixf.delegate( "[data-color-delete-preset]", "click", "color", function() {
		var ctrl = $( this ),
			preview = $( this ).parents( ".brix-color-preset" ).first(),
			id = preview.attr( "data-id" ),
			wrapper = $( this ).parents( ".brix-color-user-presets" ).first(),
			outer_wrapper = $( this ).parents( ".brix-color-presets-wrapper" ).first();

		if ( id ) {
			preview.remove();
			window.brix_seek_and_destroy_tooltips();

			if ( ! $( ".brix-color-preset", outer_wrapper ).length ) {
				$( "body" ).removeClass( "brix-has-color-presets" );
			}

			if ( ! $( ".brix-color-preset", wrapper ).length ) {
				wrapper.removeClass( "brix-color-has-user-presets" );
			}
			else {
				wrapper.addClass( "brix-color-has-user-presets" );
			}

			brix_framework.color.presets = _.without(
				brix_framework.color.presets,
				_.findWhere( brix_framework.color.presets, { user: true, id: id } )
			);

			$.post(
				ajaxurl,
				{
					action: "brix_color_delete_preset",
					nonce: ctrl.attr( "data-nonce" ),
					id: id,
				},
				function( response ) {
				}
			);
		}

		return false;
	} );

	/**
	 * Save a color preset.
	 */
	$.brixf.delegate( "[data-color-save-preset]", "click", "color", function() {
		var ctrl = $( this ),
			wrapper = ctrl.parents( ".brix-color-wrapper" ).first(),
			input = $( ".brix-color-input", wrapper ),
			hex = input.val();

		if ( hex ) {
			var preset_name = prompt( brix_framework.color.new_preset_name );

			if ( ! preset_name ) {
				return false;
			}

			brix_idle_button( ctrl );

			if ( ! brix_framework.color.presets.user ) {
				brix_framework.color.presets.user = [];
			}

			$.post(
				ajaxurl,
				{
					action: "brix_color_save_preset",
					nonce: ctrl.attr( "data-nonce" ),
					hex: hex,
					name: preset_name,
					id: brix_framework.color.presets.user.length + 1
				},
				function( response ) {
					if ( ! brix_framework.color.presets.user ) {
						brix_framework.color.presets.user = [];
					}

					brix_framework.color.presets.user.push( {
						user: true,
						hex: hex,
						label: preset_name
					} );

					$( "body" ).addClass( "brix-has-color-presets" );

					brix_unidle_button( ctrl );
				}
			);
		}

		return false;
	} );

	/**
	 * Display the color presets selection modal.
	 */
	$.brixf.delegate( "[brix-data-color-presets]", "click", "color", function() {
		var key = "brix-color-presets",
			ctrl = $( this ),
			wrapper = ctrl.parents( ".brix-color-wrapper" ).first(),
			input = $( ".brix-color-input", wrapper ),
			opacity = input.attr( "data-opacity" ),
			opacity_input = $( "[data-input-color-opacity]", wrapper ),
			data = {
				"hex": input.val()
			};

		var modal = new $.brixf.modal( key, data, {
			simple: true,

			save: function( data, after_save, nonce ) {
				if ( data["hex"] ) {
					if ( opacity ) {
						if ( data["hex"].indexOf( "#" ) === -1 ) {
							opacity_input.val( _rgb2opacity( data["hex"] ) );
						}
						else {
							opacity_input.val( "1" );
						}

						input.attr( "data-opacity", opacity_input.val() );
					}

					if ( ! opacity && data["hex"].indexOf( "rgba(" ) !== -1 ) {
						data["hex"] = _rgb2hex( data["hex"] );
					}

					input
						.val( data["hex"] )
						.trigger( "keyup" );
				}
			}
		} );

		modal.open( function( content, key, _data ) {
			var modal_data = {
				"action": "brix_color_presets_modal_load",
				"nonce": ctrl.attr( "data-nonce" ),
				"data": _data
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

					setTimeout( function() {
						$.brixf.ui.build();
					}, 1 );
				}
			);
		} );

		return false;
	} );

	/**
	 * Adding the color component to the UI building queue.
	 */
	$.brixf.ui.add( "input.brix-color-input", function() {
		$( this ).each( function() {
			var input = $( this ),
				wrapper = input.parents( ".brix-color-wrapper" ).first(),
				opacity = input.attr( "data-opacity" ),
				options = {
					control: "wheel",
					change: function( value, op ) {
						input.css( "border-color", value );

						if ( opacity !== undefined ) {
							$( "[data-input-color-opacity]", wrapper ).val( op );
						}

						if ( value !== '' ) {
							wrapper.addClass( "brix-color-can-be-saved" );
						}
						else {
							wrapper.removeClass( "brix-color-can-be-saved" );
						}
					}
				};

			if ( opacity !== undefined ) {
				options.opacity = true;
				options.format = "rgb";
			}

			$( this )
				.minicolors( options )
				.trigger( "change" );
		} );
	} );

} )( jQuery );
