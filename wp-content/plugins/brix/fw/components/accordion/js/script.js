( function( $ ) {
	"use strict";

	/**
	 * Boot the accordion component.
	 *
	 * @param {Object|String} element The accordion container CSS selector or the container object.
	 * @param {Object} config The configuration object.
	 */
	$.brixf.accordion = function( element, config ) {
		config = $.extend( {
			/* Custom component namespace. */
			namespace: "accordion",

			/* Accordion CSS class. */
			accordionClass: ".brix-accordion",

			/* Toggle CSS class. */
			toggleClass: ".brix-toggle",

			/* Toggle triggers CSS class. */
			triggersClass: ".brix-toggle-trigger",

			/* Toggle contents CSS class. */
			contentClass: ".brix-toggle-content",

			/* Active status CSS class. */
			activeClass: "brix-active",

			/* Event trigger. */
			eventTrigger: "click",

			/* Mode */
			mode: false
		}, config );

		/**
		 * Handle the accordion navigation click event.
		 *
		 * @param  {Object} root The accordion container object.
		 * @param  {Object} trigger The accordion trigger that has triggered the event.
		 * @return {Boolean}
		 */
		function evAccordion( root, trigger ) {
			var container = null,
				triggerEl = $( trigger );

			if ( typeof element === "object" || typeof root === "object" ) {
				container = $( root );
			}
			else if ( typeof element === "string" ) {
				container = triggerEl.parents( root ).first();
			}

			var mode = config.mode !== false ? config.mode : container.attr( "data-mode" ),
				triggers = $( config.triggersClass, container ),
				toggles = $( config.toggleClass, container ),
				index = 0;

			triggers.each( function( i, a ) {
				if ( this === trigger ) {
					index = i;
				}
			} );

			switch ( container.data( "push" ) ) {
				case "hash":
					$.brixf.history.pushHash( triggerEl );
					break;
				case "querystring":
					$.brixf.history.pushQueryString( triggerEl, "toggle" );
					break;
				default:
					break;
			}

			/* Pre-switch hook. */
			container.trigger( $.brixf.resolveEventName( "switch", config.namespace ), [ trigger ] );

			if ( mode !== "toggle" ) {
				if ( triggerEl.attr( "aria-selected" ) == "true" ) {
					triggerEl.removeAttr( "aria-selected" );
					toggles.eq( index ).removeClass( config.activeClass ).attr( "aria-hidden", "true" );
				}
				else {
					triggers.removeAttr( "aria-selected" );
					triggerEl.attr( "aria-selected", "true" );
					toggles.removeClass( config.activeClass ).attr( "aria-hidden", "true" );
					toggles.eq( index ).addClass( config.activeClass ).removeAttr( "aria-hidden" );
				}
			}
			else {
				if ( triggerEl.attr( "aria-selected" ) == "true" ) {
					triggerEl.removeAttr( "aria-selected" );
					toggles.eq( index ).removeClass( config.activeClass ).attr( "aria-hidden", "true" );
				}
				else {
					triggerEl.attr( "aria-selected", "true" );
					toggles.eq( index ).addClass( config.activeClass ).removeAttr( "aria-hidden" );
				}
			}

			/* Post-switch hook. */
			container.trigger( $.brixf.resolveEventName( "switched", config.namespace ), [ trigger ] );
			window.brix.adjust();

			setTimeout( function() {
				$( window ).trigger( "resize" );
			}, 100 );

			return false;
		};

		if ( typeof element === "object" ) {
			$.brixf.on( config.triggersClass, config.eventTrigger, config.namespace, function() {
				return evAccordion( element, this );
			} );
		}
		else if ( typeof element === "string" ) {
			$.brixf.undelegate( config.eventTrigger, config.namespace );
			$.brixf.delegate( element + " " + config.triggersClass, config.eventTrigger, config.namespace, function() {
				return evAccordion( $( this ).parents( element ), this );
			} );
		}

		/* Init hook. */
		$( element ).trigger( $.brixf.resolveEventName( "init", config.namespace ) );

		if ( window.location.hash != "" ) {
			var triggersSelector = config.triggersClass + "[href='" + window.location.hash + "']";
			triggersSelector += "," + config.triggersClass + "[data-target='" + window.location.hash + "']";

			$( triggersSelector ).trigger( $.brixf.resolveEventName( config.eventTrigger, config.namespace ) );
		}
		// else {
		// 	var triggersSelector = config.triggersClass;

		// 	$( triggersSelector ).first().trigger( $.brixf.resolveEventName( config.eventTrigger, config.namespace ) );
		// }
	};
} )( jQuery );