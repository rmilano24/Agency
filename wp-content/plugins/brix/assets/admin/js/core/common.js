( function( $ ) {
	"use strict";

	var BrixDashboardWidget = function() {
		var widget = $( "#brix_dashboard_widget" );

		if ( ! widget.length ) {
			return;
		}

		var widget_visible = $( "#brix_dashboard_widget-hide" )[0].checked;

		if ( ! widget_visible ) {
			return;
		}

		$( window ).on( "load", function() {
			$.ajax( {
				type: "GET",
				url: ajaxurl,
				data: {
					dataType: "html",
					action: 'brix_dashboard_widget'
				},
				success: function( resp ) {
					$( ".inside", widget ).html( resp );
				}
			} );
		} );
	};

	var dashboard_widget = new BrixDashboardWidget();

	/**
	 * Attempt to link the current domain to the updates provider.
	 */
	var domain_link_submitting = false;

	$( document ).on( "submit", "#brix-domain-link", function() {
		if ( domain_link_submitting ) {
			return false;
		}

		domain_link_submitting = true;

		var form = $( this ),
			nonce = $( "#ev", form ).val(),
			purchase_code = $( "#purchase_code", form ).val(),
			purchase_email = $( "#purchase_email", form ).val(),
			result = $( ".brix-result", form ),
			submit = form.find( '[type="submit"]');

		submit.addClass( 'brix-btn-idle' );
		submit.attr( "disabled", "disabled" );

		result.html( "" );
		result.removeClass( "brix-error brix-success" );

		$.post(
			ajaxurl,
			{
				"action"        : "brix_registration_page_save",
				"purchase_code" : purchase_code,
				"purchase_email": purchase_email,
				"nonce"         : nonce
			},
			function( response ) {
				response = $.parseJSON( response );

				if ( response.type === "error" || response.type === "success" ) {
					result.addClass( "brix-" + response.type )
					result.html( response.message );
				}

				submit.removeClass( 'brix-btn-idle' );
				submit.removeAttr( "disabled" );

				domain_link_submitting = false;
			}
		);

		return false;
	} );

} )( jQuery );