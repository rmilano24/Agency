( function( $ ) {
	"use strict";

	/**
	 * Popup help.
	 */
	$.brixf.delegate( ".brix-help-popup .brix-help-handle", "click", "field", function() {
		var data = {},
			key = "brix-help-popup",
			text = $( ".brix-help-popup-text", this ).html();

		var modal = new $.brixf.modal( key, data, {
			simple: true,
		} );

		modal.open( function( content, key, _data ) {
			content.html( text );
		} );

		return false;
	} );

	/**
	 * Breakpoint switch.
	 */
	$.brixf.delegate( "[data-breakpoint-key]", "change", "field", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			breakpoint = $( this ).val(),
			breakpoints = $( "[data-field-breakpoint]", field );

		breakpoints.removeClass( "brix-active-breakpoint" );
		breakpoints.filter( "[data-field-breakpoint='" + breakpoint + "']" ).addClass( "brix-active-breakpoint" );

		return false;
	} );

	/**
	 * Get the input field of a controller.
	 */
	function brix_controller_field_get( field ) {
		var type = field.attr( "data-type" );

		switch ( type ) {
			case 'radio':
				return $( "input[type='radio'][name]:checked", field ).first();
				break;
			case 'select':
				return $( "select[name]", field ).first();
				break;
			case 'checkbox':
				return $( "input[type='checkbox'][name]", field ).first();
				break;
			default:
				break;
		}

		return false;
	}

	/**
	 * Handle a slave field display.
	 */
	function brix_handle_slave_field_display( field ) {
		var container = field.parents( ".brix-tab-container" ).first();

		if ( field.parents( ".brix-field-bundle" ).length ) {
			container = field.parents( ".brix-field-bundle" ).first();
		}

		var ctrl_key = field.attr( "data-slave" ),
			ctrl = $( "[data-controller='" + ctrl_key + "']", container ),
			ctrl_field = brix_controller_field_get( ctrl );

		if ( ! ctrl_field ) {
			return;
		}

		var ctrl_value = ctrl_field.val();

		if ( ctrl_field.is( "[type='checkbox']" ) ) {
			ctrl_value = ctrl_field.is( ":checked" ) ? '1' : '0';
		}

		var expected_value = field.attr( "data-controller-value" );

		if ( expected_value.indexOf( ',' ) != -1 ) {
			expected_value = expected_value.split( ',' );

			if ( ( expected_value.indexOf( ctrl_value ) == -1 ) || ctrl.hasClass( "brix-hidden" ) ) {
				field.addClass( "brix-hidden" );
				$( "[data-slave='" + field.attr( "data-controller" ) + "']", container ).addClass( "brix-hidden" );
			}
			else {
				field.removeClass( "brix-hidden" );
				$( "[data-slave='" + field.attr( "data-controller" ) + "']", container ).removeClass( "brix-hidden" );
			}
		}
		else {
			var check = true;

			if ( expected_value.indexOf( "!=" ) !== -1 ) {
				expected_value = expected_value.replace( "!=", "" );
				check = expected_value == ctrl_value;
			}
			else {
				check = expected_value != ctrl_value;
			}

			if ( check || ctrl.hasClass( "brix-hidden" ) ) {
				field.addClass( "brix-hidden" );
				$( "[data-slave='" + field.attr( "data-controller" ) + "']", container ).addClass( "brix-hidden" );
			}
			else {
				field.removeClass( "brix-hidden" );
				$( "[data-slave='" + field.attr( "data-controller" ) + "']", container ).removeClass( "brix-hidden" );
			}
		}

		if ( field.is( "[data-controller]" ) ) {
			brix_controller_field_get( field ).trigger( "change" );
		}
	}

	/**
	 * Building the UI for hidden fields.
	 */
	$.brixf.ui.add( "[data-slave]", function() {
		$( this ).each( function() {
			brix_handle_slave_field_display( $( this ) );
		} );
	} );

	/**
	 * Handle the change event of controller fields.
	 */
	$.brixf.delegate( "[data-controller] :input", "change", "field", function() {
		var field = $( this ).parents( ".brix-field" ).first();

		if ( $( this ).is( brix_controller_field_get( field ) ) ) {
			var container = field.parents( ".brix-tab-container" ).first();

			if ( field.parents( ".brix-field-bundle" ).length ) {
				container = field.parents( ".brix-field-bundle" ).first();
			}

			var controller_value = $( this ).val(),
				controller = $( this ).parents( "[data-controller]" ).first(),
				controller_key = controller.attr( "data-controller" ),
				slaves = $( "[data-slave='" + controller_key + "']", container );

			slaves.each( function() {
				brix_handle_slave_field_display( $( this ) );
			} );

			var last_slave = slaves.not( ".brix-hidden" ).last(),
				tabs = $( this ).parents( ".brix-tabs" ).first();

			if ( last_slave.length && tabs.length && tabs.css( "overflow-y" ) ) {
				var scroll = last_slave.position().top + last_slave.outerHeight() * 2;

				tabs.get( 0 ).scrollTop = scroll;
			}
		}
	} );
} )( jQuery );