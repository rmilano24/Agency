( function( $ ) {
	"use strict";

	/**
	 * Force element redraw.
	 */
	function _redraw( element ) {
		$( element ).hide( 0, function() {
			$( this ).show();
		} );
	}

	/**
	 * Overlay menu component.
	 */
	var Agncy_OverlayMenu = function() {
		var self = this,
			stagger = 100,
			out_left_class = "agncy-out-left",
			out_right_class = "agncy-out-right",
			li_out_class = "agncy-li-out",
			direction = "forward",
			animating = false;

		/**
		 * Open drawer.
		 */
		$( document ).on( "click", ".agncy-drawer-trigger", function() {
			var is_mobile = $( this ).hasClass( "agncy-h-mn-t" );

			$( "body" ).addClass( "agncy-drawer-opening" );

			setTimeout( function() {
				$( "body" ).addClass( "agncy-drawer-open" );
			}, 1000 );

			return false;
		} );

		/**
		 * Close drawer.
		 */
		$( document ).on( "click", ".agncy-drawer-close", function() {
			$( "body" ).addClass( "agncy-drawer-closing" );

 			setTimeout( function() {
				$( "body" ).addClass( "agncy-drawer-closed" );
				$( "body" ).removeClass( "agncy-drawer-open" );

				setTimeout( function() {
	 				$( "body" ).removeClass( "agncy-drawer-closing" );
	 				$( "body" ).removeClass( "agncy-drawer-closed" );
					$( "body" ).removeClass( "agncy-drawer-opening" );
				}, 1000 );
			}, 1000 );

 			return false;
 		} );

		/**
		 * Declare panel transition.
		 */
		this.declare_panel_transition = function() {
			var elems = ".agncy-s-o-sp-h, .agncy-s-o-sp-c > li";

			$.ev_transitions.callback( elems, function( el ) {
				var $sub_menu = $( el ).parents( '.agncy-s-o-sp' ).first(),
					$lis	  = $( elems, $sub_menu );

				if ( direction === "backward" ) {
					var $sub_menu_prev = $sub_menu.prev();

					if ( $( el ).is( $lis.first() ) ) {
						$sub_menu.addClass( out_right_class );
						$sub_menu_prev.removeClass( out_left_class );

						$( document ).off( "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev", elems );
					}
				}
				else {
					if ( $( el ).is( $lis.last() ) ) {
						animating = false;

						$( document ).off( "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev", elems );
					}
				}
			} );
		};

		/**
		 * Boot submenu.
		 */
		this.add_submenu_marker = function() {
			$( ".agncy-mn-n .menu-item-has-children a, .agncy-mn-n .page_item_has_children a" ).append( ' <span><svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7"><path fill="#000000" fill-rule="evenodd" d="M0,3.49999994 C0,3.64438014 0.0464069301,3.76297618 0.13922277,3.855794 C0.232038611,3.94860984 0.350635639,3.99501578 0.495015835,3.99501578 L10.2975672,3.99501578 L8.1937494,6.12977218 C7.9462395,6.35665535 7.94108405,6.58869198 8.1782791,6.82588802 C8.41547712,7.06308405 8.64751276,7.05792762 8.87439592,6.81041871 L11.8444909,3.8403237 C11.9063682,3.79907321 11.9476187,3.74750786 11.9682454,3.68563162 C11.9888711,3.6237544 11.999184,3.56187717 11.999184,3.49999994 C11.999184,3.43812371 11.9888711,3.37624648 11.9682454,3.31436925 C11.9476187,3.25249202 11.9063682,3.20092767 11.8444909,3.15967717 L8.87439592,0.189582165 C8.64751276,-0.0579277327 8.41547712,-0.0630841683 8.1782791,0.174111868 C7.94108405,0.411309885 7.9462395,0.643345525 8.1937494,0.87022869 L10.2975672,3.00498411 L0.495015835,3.00498411 C0.350635639,3.00498411 0.232038611,3.05139104 0.13922277,3.14420688 C0.0464069301,3.23702371 0,3.35561975 0,3.49999994 Z"/></svg></span>' );
		};

		/**
		 * Expand submenu.
		 */
		$( document ).on( "click", ".agncy-mn-n .menu-item-has-children a > span, .agncy-mn-n .page_item_has_children a > span,.agncy-mn-n .menu-item-has-children a[href='#'], .agncy-mn-n .page_item_has_children a[href='#'],.agncy-mn-n .menu-item-has-children a:not([href]), .agncy-mn-n .page_item_has_children a:not([href])", function() {
			if ( animating ) {
				return false;
			}

			self.declare_panel_transition();

			direction = "forward";
			animating = true;

			var $li          = $( this ).parents( 'li' ).first(),
				$text        = $li.find( '> a' ).text(),
				$sub_menu    = $li.find( '> ul' ).html(),
				$parent_ul   = $li.parents( 'ul' ).first(),
				$parent      = $parent_ul.parent(),
				$main_menu   = $( this ).parents( '.agncy-mn-n' ).find( "> div" ).first(),
				$template    = $( $( "#agncy-overlay-nav" ).html() );

			$( ".agncy-s-o-sp-h span", $template ).html( $text );
			$( ".agncy-s-o-sp-c", $template ).html( $sub_menu );

			var $lis = $( ".agncy-s-o-sp-h, .agncy-s-o-sp-c > li", $template );

			$lis.addClass( li_out_class );
			$template.appendTo( '.agncy-mn-n' );
			_redraw( $template );

			$( ".agncy-mn-n" ).css( "min-height", $( ".agncy-s-o-sp" ).last().outerHeight() );

			$template.removeClass( out_right_class );

			$lis.each( function( i ) {
				var li = $( this );

				setTimeout( function() {
					li.removeClass( li_out_class );
				}, stagger * i );
			} );

			$main_menu.addClass( out_left_class );
			$parent.addClass( out_left_class );

			return false;
		});

		/**
		 * Go back from submenu.
		 */
		$( document ).on( "click", ".agncy-s-o-sp-h", function() {
			if ( animating ) {
				return false;
			}

			self.declare_panel_transition();

			direction = "backward";
			animating = true;

			var $sub_menu      = $( this ).parents( '.agncy-s-o-sp' ).first(),
				$lis		   = $( ".agncy-s-o-sp-h, .agncy-s-o-sp-c > li", $sub_menu ),
				$sub_menu_prev = $sub_menu.prev();

			$lis = $( $lis.get().reverse() );

			$lis.each( function( i ) {
				var li = $( this );

				setTimeout( function() {
					li.addClass( li_out_class );

					if ( i == $lis.length - 1 ) {
						if ( $( ".agncy-s-o-sp" ).length == 1 ) {
							$( ".agncy-mn-n" ).css( "min-height", $( ".agncy-mn-n ul.menu" ).outerHeight() );

						}
						else {
							$( ".agncy-mn-n" ).css( "min-height", $( ".agncy-s-o-sp" ).last().prev().outerHeight() );
						}
					}
				}, stagger * i );
			} );

			return false;
		} );

		$.ev_transitions.callback( ".agncy-s-o-sp", function( el ) {
			if ( direction === "backward" ) {
				if ( $( el ).hasClass( out_right_class ) ) {
					$( el ).remove();
				}

				animating = false;
			}
		} );

		this.add_submenu_marker();
	};

	$( document ).on( "click", ".agncy-mn-d-s-panel .agncy-mn-n .menu-item-has-children a > span, .agncy-mn-d-s-panel .agncy-mn-n .page_item_has_children a > span", function() {
		var $li = $( this ).parents( 'li' ).first();

		if ( $li.hasClass( "agncy-active" ) ) {
			$li.removeClass( "agncy-active" );
			$( ".agncy-active", $li ).removeClass( "agncy-active" );
		}
		else {
			$li.addClass( "agncy-active" );
		}

		return false;
	} );

	$( window ).on( "resize", function() {
		$( ".agncy-mn-n" ).css( "min-height", $( ".agncy-s-o-sp" ).last().outerHeight() );
	} );

	( new Agncy_OverlayMenu() );

} )( jQuery );