( function( $ ) {
	"use strict";

	/**
	 * Boot the tabs component.
	 *
	 * @param {Object|String} element The tabs container CSS selector or the container object.
	 * @param {Object} config The configuration object.
	 */
	$.brixf.tabs = function( element, config ) {
		config = $.extend( {
			/* Custom component namespace. */
			namespace: "tabs",

			/* Tab nav CSS class. */
			navClass: ".brix-tabs-nav",

			/* Tab triggers CSS class. */
			triggersClass: ".brix-tab-trigger",

			/* Tabs containers CSS class. */
			panelsContainerClass: ".brix-tab-container",

			/* Tabs CSS class. */
			tabsClass: ".brix-tab",

			/* Active status CSS class. */
			activeClass: "brix-active",

			/* Event trigger. */
			eventTrigger: "click" //focus?
		}, config );

		/**
		 * Handle the tab navigation click event.
		 *
		 * @param  {Object} root The tabs container object.
		 * @param  {Object} trigger The tab trigger that has triggered the event.
		 * @return {Boolean}
		 */
		function evTabs( root, trigger ) {
			var container = null,
				triggerEl = $( trigger );

			if ( typeof element === "object" ) {
				container = $( root );
			}
			else if ( typeof element === "string" ) {
				container = triggerEl.parents( root ).first();
			}

			var nav = $( "> " + config.navClass, container ),
				triggers = $( config.triggersClass, nav ),
				panels = $( "> " + config.panelsContainerClass, container ),
				tabs = $( "> " + config.tabsClass, panels ),
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
					$.brixf.history.pushQueryString( triggerEl, "tab" );
					break;
				default:
					break;
			}

			/* Pre-switch hook. */
			container.trigger( $.brixf.resolveEventName( "switch", config.namespace ) );

			triggers
				.removeClass( config.activeClass )
				.removeAttr( "aria-selected" );

			tabs
				.removeClass( config.activeClass )
				.attr( "aria-hidden", "true" );

			triggerEl
				.addClass( config.activeClass )
				.attr( "aria-selected", "true" );

			tabs.eq( index )
				.addClass( config.activeClass )
				.removeAttr( "aria-hidden" );

			/* Post-switch hook. */
			container.trigger( $.brixf.resolveEventName( "switched", config.namespace ) );

			return false;
		};

		if ( typeof element === "object" ) {
			$.brixf.on( config.triggersClass, config.eventTrigger, config.namespace, function() {
				return evTabs( element, this );
			} );
		}
		else if ( typeof element === "string" ) {
			$.brixf.delegate( element + " " + config.triggersClass, config.eventTrigger, config.namespace, function() {
				return evTabs( element, this );
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