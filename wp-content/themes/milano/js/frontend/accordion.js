( function( $ ) {
	"use strict";

	/**
	 * Initialize accordions.
	 */
	$( document ).on( "init.brixf.accordion", ".brix-accordion", function() {
		$( ".brix-toggle.brix-active" ).addClass( "brix-accordion-in" );
		$( ".brix-toggle:not(.brix-active) .brix-toggle-content" ).slideUp( 0 );
	} );

	/**
	 * Pre-switch hook.
	 */
	$( document ).on( "switch.brixf.accordion", ".brix-accordion", function( e, trigger ) {
		var column = $( this ).parents( ".brix-section-column" ).first(),
			isSafariOrFirefox = $( "body" ).hasClass( 'brix-ua-safari' ) || $( "body" ).hasClass( 'brix-ua-firefox' ),
			mobile = $( "body" ).hasClass( "brix-mobile" );

		if ( mobile || isSafariOrFirefox ) {
			column.css( "height", "" );
		}

		if ( $( this ).attr( "data-mode" ) !== "toggle" ) {
			var panels = $( ".brix-toggle-content", this ),
				toggles = $( ".brix-toggle", this ),
				subsection = $( this ).parents( ".brix-subsection" ).first();

			panels.stop( true, true ).slideUp( {
				duration: 250,
				easing: "easeInOutCubic",
				complete: function() {
					window.brix.adjust();

					if ( isSafariOrFirefox ) {
						subsection[0].style.float='none';
						subsection[0].offsetHeight;
						subsection[0].style.float='';
					}
				}
			} );

			$( ".brix-accordion-in", this ).removeClass( "brix-accordion-in" );
		}
		else {
			var toggle = $( trigger ).parents( ".brix-toggle" ).first(),
				panel = $( ".brix-toggle-content", toggle );

			panel.stop( true, true ).slideUp( {
				duration: 250,
				easing: "easeInOutCubic",
				complete: function() {
					window.brix.adjust();
				}
			} );

			toggle.removeClass( "brix-accordion-in" );
		}
	} );

	/**
	 * Post-switch hook.
	 */
	$( document ).on( "switched.brixf.accordion", ".brix-accordion", function() {
		var panels = $( ".brix-active .brix-toggle-content", this ),
			toggles = $( ".brix-toggle", this );

		setTimeout( function() {
			toggles.filter( ".brix-active" ).addClass( "brix-accordion-in" );
		}, 1 );

		panels.stop( true, true ).slideDown( {
			duration: 250,
			easing: "easeInOutCubic",
			complete: function() {
				window.brix.adjust();
			}
		} );
	} );

} )( jQuery );