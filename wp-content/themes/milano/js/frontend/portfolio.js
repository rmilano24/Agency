( function( $ ) {
	"use strict";

	if ( typeof Brix_LoopBuilderBlock === 'undefined' ) {
		return;
	}

	var AgncyPortfolio = function() {

		var self = this;

		/**
		 * Loop controller.
		 */
		this.loop_controller = new Brix_LoopBuilderBlock( {
			"handle": "agncy_portfolio",
			"block_selector": ".brix-section-column-block-agncy_portfolio",
			"loop_selector": ".brix-agncy_portfolio-block-loop-wrapper",
			"pagination_selector": ".brix-agncy_portfolio-block-pagination-wrapper",

			"reload_selector": ""
		} );

		/**
		 * The CSS selector of the loop wrapper.
		 */
		this.loop_wrapper_selector = ".brix-agncy_portfolio-block-loop-wrapper";

		/**
		 * Animation staggering factor.
		 */
		this.stagger = agncy_extend_option( 50, "agncy_portfolio_reveal_elements_stagger" );

		/**
		 * Current filter URL.
		 */
		this.current_url = window.location.href.split( "?" ).pop();

		/**
		 * The loop styles.
		 */
		this.styles = {
			"masonry": {
				options: agncy_extend_options( {
					transitionDuration: 0
				}, "agncy_portfolio_masonry_options" ),
				init: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						var block = loop.parents( ".brix-section-column-block" ).first();

						block.off( "brix-agncy_portfolio-block-loading" );
						block.on( "brix-agncy_portfolio-block-loading", function( event, type ) {
							if ( type == "reload" ) {
								self.hide_elements( loop );
							}
						} );

						loop.on( "arrangeComplete", function() {
							self.reveal_elements( loop );
						} );

						if ( loop.data( "isotope" ) ) {
							loop.isotope( "reloadItems" );

							// $.agncy_load_images( $( "img", loop ), {
							// 	all: function() {
							// 		setTimeout( function() {
							// 			loop.isotope();
							// 		}, 20 );
							// 	}
							// } );
						}

						$( ".brix-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ), function() {
								setTimeout( function() {
									if ( loop.data( "isotope" ) ) {
										loop.isotope();
									}
									else {
										loop.isotope( self.styles.masonry.options );
									}
								}, 20 );
							} );
						} );

						// $( loop ).one( "inview", function( event, isInView ) {
						// 	if ( isInView ) {
						// 		$.agncy_load_images( $( "img", loop ), {
						// 			all: function() {
						// 				loop.isotope( self.styles.masonry.options );

						// 				setTimeout( function() {
						// 					if ( loop.data( "isotope" ) ) {
						// 						loop.isotope();
						// 					}
						// 				}, 20 );
						// 			}
						// 		} );
						// 	}
						// } );
					} );
				},
				append: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						if ( loop.data( "isotope" ) ) {
							loop.isotope( "reloadItems" );
							loop.isotope();
						}

						$( ".brix-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ), function() {
								setTimeout( function() {
									if ( loop.data( "isotope" ) ) {
										loop.isotope();
									}
									else {
										loop.isotope( self.styles.masonry.options );
									}
								}, 20 );
							} );
						} );

						// $.agncy_load_images( $( "img", loop ), {
						// 	all: function() {
						// 		loop.isotope();
						// 	}
						// } );
					} );
				}
			}
		};

		/**
		 * Depending on the style, initialize the block layout for items reload.
		 */
		this.dispatch_reload = function( event, block ) {
			self.styles.masonry.init( block );
		};

		/**
		 * Depending on the style, initialize the block layout for items to be
		 * appended.
		 */
		this.dispatch_append = function( event, block ) {
			self.styles.masonry.append( block );
		};

		/**
		 * Reveal newly added elements.
		 */
		this.reveal_elements = function( loop ) {
			$( ".agncy-ajax-in", loop ).each( function( i, el ) {
				setTimeout( function() {
					$( el ).removeClass( "agncy-ajax-in" );
				}, self.stagger * i );
			} );
		};

		/**
		 * Hide old elements.
		 */
		this.hide_elements = function( loop ) {
			$( "> *", loop ).each( function( i, el ) {
				setTimeout( function() {
					$( el ).addClass( "agncy-ajax-out" );
				}, self.stagger * i );
			} );
		};

		/**
		 * Initialize all loops in page.
		 */
		this.filter_placeholder = function() {
			var filter 		= $( this ).parents( ".agncy-p-f-d" ).first(),
				wrapper 	= $( this ).parents( ".agncy-p-f-w_i" ).first(),
				placeholder = $( "span[data-agncy-placeholder]", wrapper ).first(),
				token 		= wrapper.attr( "data-token" );

			if ( typeof $( this ).attr( "data-default" ) === "undefined" ) {
				wrapper.attr( "data-value", $( this ).text() );
			}
			else {
				wrapper.attr( "data-value", "" );
			}

			placeholder.html( $( this ).text() );

			var page_params = {};

			$( "[data-token]", filter ).each( function() {
				var tk = $( this ).attr( "data-token" ),
					vl = $( this ).attr( "data-value" );

				if ( vl ) {
					page_params[ tk ] = vl;
				}
			} );

			var url = "";

			if ( window.location.search ) {
				url = window.location.href + "&";
			}
			else {
				url = window.location.origin + window.location.pathname + "?";
			}

			if ( page_params ) {
				url += $.param( page_params );
			}

			self.loop_controller.reload.apply( this, [ url ] );

			$( ".agncy-p-f-active", wrapper ).removeClass( "agncy-p-f-active" );
			$( this ).addClass( "agncy-p-f-active" );

            filter.trigger( "agncy-filter" );

			return false;
		};

		/**
		 * Current item highlight state.
		 */
		this.adjust_current_item = function() {
			var filter = $( this ).parents( ".agncy-p-f" ).first(),
				wrapper = $( this ).parents( ".agncy-p-f-w_i" ).first();

			$( ".agncy-p-f-active", wrapper ).removeClass( "agncy-p-f-active" );
			$( this ).addClass( "agncy-p-f-active" );

			if ( $( this ).parents( ".agncy-p-f-sl" ).length ) {
				var ul = $( this ).parents( ".agncy-p-f-sl" ).first(),
					parent_li = ul.parent();

				$( "> a", parent_li ).addClass( "agncy-p-f-active" );
			}

			return false;
		};

        /**
         * Toggle the visibilty of the portfolio filter.
s         */
        this.filter_toggle = function() {
            $( this ).toggleClass( "agncy-p-f-d-toggle-open" );

            return false;
        };

        /**
         * Update the filter label value when filtering through items.
s         */
        this.update_filter_label = function() {
            var str = [],
                block = $( this ).parents( ".brix-section-column-block" ).first();

            $( ".agncy-p-f-active", block ).each( function() {
                var text = $( this ).text(),
                    token = $( this ).parents( "[data-token]" ).first();

                if ( text !== token.find( "[data-default]" ).first().text() ) {
                    str.push( text );
                }
            } );

            str = str.join( ", " );

            if ( str ) {
                $( ".agncy-p-f-d-toggle span", block ).html( str );
            }
            else {
                $( ".agncy-p-f-d-toggle span[data-default-label]", block ).html(
                    $( ".agncy-p-f-d-toggle span[data-default-label]", block ).attr( "data-default-label" )
                );
            }
        };

		/**
		 * Initialize all loops in page.
		 */
		this.init = function() {
			var have_loops = false;

			/**
			 * Initialize masonry loops.
			 */
			self.styles.masonry.init();
			have_loops = true;

			if ( have_loops ) {
				/* Handle AJAX reload events. */
				$( window ).on( "brix_agncy_portfolio_block_reload", self.dispatch_reload );

				/* Handle AJAX append events. */
				$( window ).on( "brix_agncy_portfolio_block_append", self.dispatch_append );
			}

			/**
			 * Adjust the filter placeholder.
			 */
			$( document ).on( "click", ".agncy-p-f-d a", self.filter_placeholder );

			/**
			 * Adjust the current item highlight.
			 */
			$( document ).on( "click", ".agncy-p-f a", self.adjust_current_item );

            /**
             * Toggle the filter.
             */
            $( document ).on( "click", ".agncy-p-f-d-toggle", self.filter_toggle );

            /**
             * Filter event.
             */
            $( document ).on( "agncy-filter", ".agncy-p-f-d", self.update_filter_label );

            $( document ).on( "inview", ".agncy-p-i", function() {
            	$( this ).addClass( 'agncy-p-i-inview' );
            } );
		};

		/**
		 * Initialize all loops in page.
		 */
		$( window ).on( "brix_ready", self.init );

	};

	( new AgncyPortfolio() );

} )( jQuery );