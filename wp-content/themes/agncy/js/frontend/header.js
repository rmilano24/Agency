( function( $ ) {
	"use strict";

	/**
	 * Force element redraw.
	 */
	function _redraw( element ) {
		$( element ).hide( 0, function() {
			$( this ).show();
		} );
	};

	/**
	 * Header.
	 */
	var Agncy_Header = function() {

		var self = this;

		/**
		 * The key used in the preloader page stack.
		 *
		 * @type {String}
		 */
		this.preloader_key = "header";

		/**
		 * The header element.
		 *
		 * @type {jQuery}
		 */
		this.header = $( ".agncy-h" );

		/**
		 * The header height.
		 *
		 * @type {Number}
		 */
		this.header_height = self.header.outerHeight();

		/**
		 * Cached version of the header markup;
		 *
		 * @type {String}
		 */
		this.header_cache = "";

		this.drawer_cache = "";

		/**
		 * The current breakpoint state.
		 *
		 * @type {String}
		 */
		this.breakpoint = "desktop";

		/**
		 * Bind the preloading event.
		 *
		 * @since 1.0.0
		 */
		this.bind = function() {
			window.agncy_preloader.add( self.preloader_key );

			$( document ).on( "ready", this.init );
		};

		/**
		 * Change the header layout upon window resizing.
		 *
		 * @since 1.0.0
		 */
		this.resize = function() {
			if ( $( "body.agncy-mobile").length == 1 ) {
				return;
			}

			var mobile_header = $( "#agncy-hm" ),
				mobile_header_html = mobile_header.length ? mobile_header.html() : "";

			// if ( ! mobile_header.length ) {
			// 	return;
			// }

			mobile_header = $( "<div>" + mobile_header_html + "</div>" );

			var threshold = agncy_mobile_threshold_value();

			if ( window.innerWidth <= threshold ) {
				if ( self.breakpoint === "desktop" ) {
					self.header_cache = $( ".agncy-h-hl" )[ 0 ].outerHTML;
					self.drawer_cache = $( ".agncy-h-drawer" ).length ? $( ".agncy-h-drawer" )[ 0 ].outerHTML : "";

					if ( mobile_header_html ) {
						$( ".agncy-h-hl" ).replaceWith( $( ".agncy-h-hlm", mobile_header ) );
						$( ".agncy-h-m-drawer", mobile_header ).insertAfter( $( ".agncy-h-hlm" ) );
					}
					else {
						if ( $( "#agncy-hm" ).length ) {
							$( ".agncy-h-hl" ).html( "" );
						}
					}

					$( ".agncy-h-drawer" ).remove();

					self.breakpoint = "mobile";
				}
			}
			else {
				if ( self.breakpoint === "mobile" ) {
					$( ".agncy-h-hlm" ).replaceWith( $( self.header_cache ) );

					if ( self.drawer_cache ) {
						$( ".agncy-h-hl" ).after( self.drawer_cache );
					}

					$( ".agncy-h-m-drawer" ).remove();

					self.breakpoint = "desktop";
				}
			}
		};

		/**
		 * Toggle the mobile drawer navigation.
		 *
		 * @since 1.0.0
		 */
		this.toggle_mobile_drawer_navigation = function() {
			var menu = $( ".agncy-h-m-drawer" ),
				menu_class = "agncy-mn-open",
				menu_is_open = menu.hasClass( menu_class ),
				namespace = "agncy_drawer_nav";

			if ( menu_is_open ) {
				$( window ).off( "keydown." + namespace );

				$.ev_transitions.callback( menu, function() {
					// self.restore( menu );
					menu.hide();

					// $( ".agncy-s-o-sp" ).remove();
					// $( ".agncy-mn-n .agncy-left, .agncy-mn-n .agncy-right, .agncy-mn-n .agncy-out" ).removeClass( "agncy-left agncy-right agncy-out" );
					// $( ".agncy-out-left,.agncy-out-right,.agncy-li-out" ).removeClass( "agncy-out-left agncy-out-right agncy-li-out" );
				} );

				menu.removeClass( menu_class );
			}
			else {
				menu.show();
				// self.move( menu, "before", ".agncy-l" );

				$.ev_key( "esc", self.toggle_mobile_drawer_navigation, { "namespace": namespace } );

				setTimeout( function() {
					menu.addClass( menu_class );
				}, 100 );
			}

			return false;
		};

		/**
		 * Move an element to a different location.
		 *
		 * @since 1.0.0
		 * @param {jQuery} element The DOM element to move.
		 * @param {String} destination The type of operation to perform: "before", "after", "append".
		 * @param {jQuery} target The target element.
		 */
		this.move = function( element, destination, target ) {
			var origin = {};

			if ( element.prev().length ) {
				origin = {
					type: "next",
					el: element.prev()
				};
			}
			else {
				origin = {
					type: "parent",
					el: element.parent()
				};
			}

			$( element ).data( "agncy-origin", origin );

			var element = $( element ).detach();

			switch ( destination ) {
				case "before":
					$( target ).before( element );
					break;
				case "after":
					$( target ).after( element );
					break;
				case "append":
				default:
					$( target ).append( element );
					break;
			}

			// _redraw( target );
		};

		/**
		 * Restore an element to its original location.
		 *
		 * @since 1.0.0
		 * @param {jQuery} element The DOM element to move.
		 * @param {String} destination The type of operation to perform: "before", "after", "append".
		 * @param {jQuery} target The target element.
		 */
		this.restore = function( element ) {
			var origin = element.data( "agncy-origin" );

			if ( ! origin ) {
				return;
			}

			element = element.detach();

			switch ( origin.type ) {
				case "next":
					$( origin.el ).after( element );
					break;
				case "parent":
					$( element ).appendTo( origin.el );
					break;
				default:
					break;
			}
		};

		/**
		 * Initialize the component.
		 *
		 * @since 1.0.0
		 */
		this.init = function() {
			/* Toggle mobile drawer navigation. */
			// $( document ).on( "click", ".agncy-h-mn-t", self.toggle_mobile_drawer_navigation );
            // $( document ).on( "click", ".agncy-mh-drawer-close", self.toggle_mobile_drawer_navigation );

			/* Change the header layout upon window resizing. */
			$( window ).on( "resize", self.resize );
			self.resize();

			/* Header preloading. */
			$.agncy_load_images( $( "img", self.header ), {
				all: function() {
					window.agncy_preloader.complete( self.preloader_key );
				}
			} );
		};

		this.bind();

	};

	window.agncy.header = new Agncy_Header();

} )( jQuery );