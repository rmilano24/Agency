( function( $ ) {
	"use strict";

	var s = null;

	/**
	 * Parallax backgrounds.
	 */
	function brix_parallax() {
		if ( $( ".brix-background-parallax" ).length == 0 ) {
			return;
		}

		if ( $( "body" ).hasClass( "brix-mobile" ) ) {
			return;
		}

		$( ".brix-background-parallax" ).each( function() {
			var self = this,
				shift = 0;

			if ( $( this ).hasClass( "brix-background-video-container" ) ) {
				var parallaxes = $.parseJSON( $( self ).attr( "data-parallax" ) );

				if ( typeof parallaxes["desktop"] !== "undefined" ) {
					shift = parseInt( parallaxes["desktop"], 10 );
				}
			}
			else {
				$.each( brix_env.breakpoints, function( breakpoint_key ) {
					var mq = this.media_query;

					if ( ! mq ) {
						mq = "@media screen";
					}

					var media = mq.replace( "@media ", "" );

					if ( window.matchMedia( media ).matches ) {
						var parallaxes = $.parseJSON( $( self ).attr( "data-parallax" ) );

						if ( typeof parallaxes[breakpoint_key] !== "undefined" ) {
							shift = parseInt( parallaxes[breakpoint_key], 10 );
						}
						else {
							shift = 0;
						}
					}
				} );
			}

			$( this ).attr( 'data-top-bottom', 'transform:translate3d(0px, ' + ( shift ) + 'px, 0px)' );
			$( this ).attr( 'data-bottom-top', 'transform:translate3d(0px, ' + ( shift*-1 ) + 'px, 0px)' );

			var styles = {
				top: -shift + 'px',
				bottom: -shift + 'px'
			};

			$( this ).css( styles );
		} );

		if ( s === null ) {
			var skrollr_options = window.brix.extend_options( {
				smoothScrolling: false,
				forceHeight: false,
				mobileCheck: function() {
					return false;
				}
			}, "brix_skrollr_options" );

			s = skrollr.init( skrollr_options ).refresh();
		}
		else {
			s.refresh();
		}
	};

	$( window ).on( "resize.brix", function() {
		if ( s !== null ) {
			s.refresh();
		}
	} );

	$( ".brix-builder" ).on( "adjust.brix", function() {
		brix_parallax();
	} );

	/**
	 * Counter block.
	 */
	$( document ).on( "brix_block_inview", ".brix-section-column-block-counter", function() {
		var block = $( this );

		if ( ! $( block ).data( "brix_counter" ) ) {
			var value = $( ".brix-counter-value", block ),
				endValue = $( value ).attr( "data-value" );

			if ( typeof endValue === "undefined" ) {
				return;
			}

			var decimals = endValue.split( "." )[1] ? endValue.split( "." )[1].length : 0,
				decimals_separator = ( 1.1 ).toLocaleString().substring( 1, 2 ),
				thousands_separator = decimals_separator == "." ? "," : ".";

			var options = {
					useEasing : true,
					useGrouping : true,
					separator : thousands_separator,
					decimal : decimals_separator,
					prefix : '',
					suffix : ''
				},
				counter = new CountUp( value.get( 0 ), 0, endValue, decimals, 2, options );

			$( block ).data( "brix_counter", counter );
		}

		$( block ).data( "brix_counter" ).start();
	} );

} )( jQuery );