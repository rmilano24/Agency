( function( $ ) {
	"use strict";

	function Brix_ColumnCarousel() {

		var self = this;

		/**
		 * Adjust navigation classes.
		 */
		this.adjust_nav = function( carousel ) {
			var column = $( carousel ).parents( ".brix-section-column-carousel" ).first(),
				selected = $( ".is-selected", column ),
				next_arrow = $( ".brix-column-carousel-next-arrow", column ),
				prbrix_arrow = $( ".brix-column-carousel-prev-arrow", column );

			next_arrow.removeClass( "brix-column-carousel-control-disabled" );
			prbrix_arrow.removeClass( "brix-column-carousel-control-disabled" );

			if ( ! column.attr( 'data-carousel-infinite-loop' ) ) {
				if ( selected.last().next().length === 0 ) {
					next_arrow.addClass( "brix-column-carousel-control-disabled" );
				}

				if ( selected.first().prev().length === 0 ) {
					prbrix_arrow.addClass( "brix-column-carousel-control-disabled" );
				}
			}
		};

		/**
		 * Adjust carousel size.
		 */
		this.adjust_size = function( carousel ) {
			var column = $( carousel ).parents( ".brix-section-column" ).first(),
				module = parseInt( column.attr( "data-carousel-module" ), 10 ),
				loop = column.attr( "data-carousel-infinite-loop" ) == "true",
				fluid = column.attr( "data-carousel-fluid-height" ) == "1",
				selected = $( ".is-selected", carousel );

			$( ".flickity-viewport", carousel ).css( "height", "" );

			if ( loop && fluid && module > 1 ) {
				if ( selected.is( ":last-child" ) ) {
					var max = 0;

					$( ".brix-section-column-block:nth-child(-n+" + ( module - 1 ) + "), .is-selected", carousel ).each( function() {
						var block_height = $( this ).outerHeight();

						if ( block_height > max ) {
							max = block_height;
						}
					} );

					$( ".flickity-viewport", carousel ).css( "height", max );
				}
			}
			else {
				var max = 0;

				selected.each( function() {
					var block_height = $( this ).outerHeight();

					if ( block_height > max ) {
						max = block_height;
					}
				} );

				$( ".flickity-viewport", carousel ).css( "height", max );
			}
		};

		/**
		 * Advance.
		 */
		this.next = function( carousel ) {
			carousel.flickity( 'next' );

			return false;
		};

		/**
		 * Go back.
		 */
		this.prev = function( carousel ) {
			carousel.flickity( 'previous' );

			return false;
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			$( document ).on( "click.brix_column_carousel", ".brix-column-carousel-prev-arrow", function() {
				var column = $( this ).parents( ".brix-section-column-carousel" ).first(),
					carousel = $( ".brix-section-column-inner-wrapper", column ).first();

				return self.prev( carousel );
			} );

			$( document ).on( "click.brix_column_carousel", ".brix-column-carousel-next-arrow", function() {
				var column = $( this ).parents( ".brix-section-column-carousel" ).first(),
					carousel = $( ".brix-section-column-inner-wrapper", column ).first();

				return self.next( carousel );
			} );

			$( document ).on( "select.flickity", ".brix-section-column-carousel .brix-section-column-inner-wrapper", function() {
				self.adjust_nav( this );
				self.adjust_size( this );
			} );

			$( document ).on( "settle.flickity", ".brix-section-column-carousel .brix-section-column-inner-wrapper", function() {
				$( this ).flickity( "resize" );
			} );

			$( window ).on( "load", function() {
				$( ".brix-section-column-inner-wrapper.flickity-enabled" ).flickity( "resize" );
			} );

			$( window ).on( "resize", function() {
				var resizable_selector = ".brix-section-row:not( .brix-section-column-height-fluid ) .brix-section-column:not( [data-carousel-fluid-height] ) .brix-section-column-inner-wrapper .flickity-viewport";

				$( resizable_selector ).each( function() {
					var wrapper = $( this ).parents( ".brix-section-column-inner-wrapper" ).first();

					if ( wrapper.data( "flickity" ) ) {
						var column = $( wrapper ).parents( ".brix-section-column" ),
							fluid = column.attr( "data-carousel-fluid-height" ) == "1";

						if ( fluid ) {
							$( this ).css( "height", "" );
						}

						setTimeout( function() {
							wrapper.flickity( "resize" );
						}, 1 );
					}

					// self.adjust_size( wrapper );
				} );
			} );

			/**
			 * Boot the UI.
			 */
			$.brixf.ui.add( "[data-carousel]", function() {
				$( this ).each( function() {
					$( this ).flickity( $.parseJSON( $( this ).attr( "data-carousel" ) ) );
				} );
			} );
		};

		/**
		 * Component initialization.
		 */
		this.init = function() {
			this.bind();
			this.adjust_nav();
		};

		this.init();

	}

	var carousel = new Brix_ColumnCarousel();

} )( jQuery );