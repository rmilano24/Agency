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

	var AgncyBlog = function() {

		var self = this;

		/**
		 * The CSS selector of the loop wrapper.
		 */
		this.loop_wrapper_selector = ".agncy-b-l-w";

		/**
		 * Animation staggering factor.
		 */
		this.stagger = agncy_extend_option( 30, "agncy_blog_reveal_elements_stagger" );

		/**
		 * The key used in the preloader page stack.
		 *
		 * @type {String}
		 */
		this.preloader_key = "blog";

		/**
		 * The loop styles.
		 */
		this.styles = {
			"classic": {
				init: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( ".agncy-loop-style-classic " + self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						var block = loop.parents( ".brix-section-column-block" ).first();

						block.off( "brix-blog-block-loading" );
						block.on( "brix-blog-block-loading", function( event, type ) {
							if ( type == "reload" ) {
								self.hide_elements( loop );
							}
						} );

						$( ".agncy-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ) );
						} );

						self.reveal_elements( loop );
					} );
				},
				append: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( ".agncy-loop-style-classic " + self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						$( ".agncy-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ) );
						} );

						self.reveal_elements( loop );
					} );
				}
			},
			"stream": {
				init: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( ".agncy-loop-style-stream " + self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						var block = loop.parents( ".brix-section-column-block" ).first();

						block.off( "brix-blog-block-loading" );
						block.on( "brix-blog-block-loading", function( event, type ) {
							if ( type == "reload" ) {
								self.hide_elements( loop );
							}
						} );

						$( ".agncy-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ) );
						} );

						self.reveal_elements( loop );
					} );
				},
				append: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( ".agncy-loop-style-stream " + self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						$( ".agncy-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ) );
						} );

						self.reveal_elements( loop );
					} );
				}
			},
			"masonry": {
				options: agncy_extend_options( {
					transitionDuration: 0,
					itemSelector: ".hentry",
				}, "agncy_blog_masonry_options" ),
				init: function() {
					var loops;

					if ( arguments.length ) {
						loops = $( self.loop_wrapper_selector, $( arguments[0] ) );
					}
					else {
						loops = $( ".agncy-loop-style-masonry " + self.loop_wrapper_selector );
					}

					loops.each( function( i, loop ) {
						loop = $( loop );

						var block = loop.parents( ".brix-section-column-block" ).first();

						if ( block.attr( "data-agncy-paginate" ) == "ajax_reload" ) {
							block.on( "brix-blog-block-loading", function() {
								self.hide_elements( loop );
							} );
						}

						if ( loop.data( "isotope" ) ) {
							loop.isotope( "reloadItems" );
						}

						loop.on( "arrangeComplete", function() {
							self.reveal_elements( loop );
						} );

						$( ".agncy-image", loop ).on( "inview", function() {
							agncy_load_image( $( this ) );
						} );

						$.agncy_load_images( $( "img", loop ), {
							all: function() {
								loop.fitVids();
								loop.isotope( self.styles.masonry.options );
								self.reveal_elements( loop );

								window.agncy_preloader.complete( self.preloader_key );
							}
						} );
					} );

				},
				append: function() {
					var loops = $( ".agncy-loop-style-masonry " + self.loop_wrapper_selector );

					loops.each( function( i, loop ) {
						loop = $( loop );

						if ( loop.data( "isotope" ) ) {
							loop.isotope( "reloadItems" );
							loop.isotope();
						}

						$.agncy_load_images( $( "img", loop ), {
							all: function() {
								loop.fitVids();

								loop.isotope();
							}
						} );
					} );
				}
			}
		};

		/**
		 * Open a video in modal.
		 */
		this.open_video = function( e ) {
			e.preventDefault();

			var post = $( this ).parents( ".hentry" ).first(),
				pswpElement = document.querySelectorAll( ".pswp" )[0],
				items = [ {
					html: $( ".agncy-entry-video-content", post ).html()
				} ],
				options = {
					index: 0,
					closeOnScroll: false,
					tapToClose: false,
					shareButtons: false
				};

			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );

			gallery.init();

			return false;
		};

		/**
		 * Go to the previous image in a post format gallery.
		 */
		this.gallery_prev = function() {
			var media_container = $( this ).parents( ".agncy-e-m" ).first(),
				flickity = $( ".agncy-lpf-c", media_container ).data( "flickity" );

			flickity.previous();

			return false;
		};

		/**
		 * Go to the next image in a post format gallery.
		 */
		this.gallery_next = function() {
			var media_container = $( this ).parents( ".agncy-e-m" ).first(),
				flickity = $( ".agncy-lpf-c", media_container ).data( "flickity" );

			flickity.next();

			return false;
		};

		/**
		 * Adjust isotope after the gallery has changed position.
		 */
		this.gallery_adjust_isotope = function() {
			var loop = $( this ).parents( ".agncy-b-l-w" ).first();

			if ( loop.data( "isotope" ) ) {
				loop.isotope( "layout" );
			}

			var media_container = $( this ).parents( ".agncy-e-m" ).first(),
				flickity = $( ".agncy-lpf-c", media_container ).data( "flickity" );

			if ( typeof flickity !== "undefined" ) {
				$( ".agncy-pfgallery-disabled", media_container ).removeClass( "agncy-pfgallery-disabled" );

				if ( flickity.selectedIndex == 0 ) {
					$( ".agncy-pfgallery-p-arr", media_container ).addClass( "agncy-pfgallery-disabled" );
				}
				else if ( flickity.selectedIndex == flickity.cells.length - 1 ) {
					$( ".agncy-pfgallery-n-arr", media_container ).addClass( "agncy-pfgallery-disabled" );
				}
			}
		};

		/**
		 * Recursively check the height of an element.
		 */
		this.check_height = function( el ) {
			var height = 0,
				interval = setInterval( function() {
					height = $( el ).outerHeight();

					if ( height ) {
						clearInterval( interval );
						$( el ).trigger( "agncy_height_set", [ height ] );
					}
				}, 100 );

			return height;
		};

		/**
		 * Find the max height of a set of elements.
		 */
		this.max_height = function( els ) {
			var max = 0;

			$( els ).each( function() {
				if ( $( this ).outerHeight() > max ) {
					max = $( this ).outerHeight();
				}
			} );

			return max;
		}

		/**
		 * Start galleries.
		 */
		this.start_galleries = function( loop ) {
			$( ".agncy-lpf-c", loop ).each( function() {
				$( this ).flickity( JSON.parse( $( this ).attr( "data-carousel" ) ) );

				if ( $( this ).parents( ".agncy-loop-style-carousel" ).length ) {
					var carousel = $( ".agncy-b-l-w", $( this ).parents( ".agncy-loop-style-carousel" ) );

					$( this ).on( "agncy_height_set", function( event, height ) {
						var height = self.max_height( $( ".is-selected", carousel ) );

						$( ".flickity-viewport", carousel ).first().css( "height", height );
					} );

				}

				if ( loop.data( 'isotope' ) ) {
					$( this ).on( "agncy_height_set", function() {
						loop.isotope('layout');
					} );
				}

				self.check_height( this )
			} );
		};

		/**
		 * Depending on the style, initialize the block layout for items reload.
		 */
		this.dispatch_reload = function( event, block ) {
			if ( block.is( ".agncy-loop-style-masonry" ) ) {
				self.styles.masonry.init( block );
			}
			else if ( block.is( ".agncy-loop-style-classic" ) ) {
				self.styles.classic.init( block );
			}
			else if ( block.is( ".agncy-loop-style-stream" ) ) {
				self.styles.stream.init( block );
			}

			self.ajax_adjustments();
		};

		/**
		 * Depending on the style, initialize the block layout for items to be
		 * appended.
		 */
		this.dispatch_append = function( event, block ) {
			if ( block.is( ".agncy-loop-style-masonry" ) ) {
				self.styles.masonry.append( block );
			}
			else if ( block.is( ".agncy-loop-style-classic" ) ) {
				self.styles.classic.append( block );
			}
			else if ( block.is( ".agncy-loop-style-stream" ) ) {
				self.styles.stream.append( block );
			}

			self.ajax_adjustments();
		};

		/**
		 * Adjustments made when adding content via AJAX.
		 */
		this.ajax_adjustments = function() {
			$( "video, audio" ).mediaelementplayer( { alwaysShowControls: true } );
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

			self.start_galleries( loop );
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
		this.init = function() {
			var have_loops = false;

			/**
			 * Initialize masonry loops.
			 */
			if ( $( ".agncy-loop-style-masonry" ).length ) {
				window.agncy_preloader.add( self.preloader_key );

				self.styles.masonry.init();
				have_loops = true;
			}

			/**
			 * Initialize classic loops.
			 */
			if ( $( ".agncy-loop-style-classic" ).length ) {
				self.styles.classic.init();
				have_loops = true;
			}

			/**
			 * Initialize stream loops.
			 */
			if ( $( ".agncy-loop-style-stream" ).length ) {
				self.styles.stream.init();
				have_loops = true;
			}

			if ( have_loops ) {
				/* Handle AJAX reload events. */
				$( window ).on( "brix_blog_block_reload", self.dispatch_reload );

				/* Handle AJAX append events. */
				$( window ).on( "brix_blog_block_append", self.dispatch_append );
			}

			/* Video popups. */
			$( document ).on( "click", ".format-video.has-post-thumbnail .agncy-image > a", self.open_video );

			/* Post format galleries. */
			$( document ).on( "click", ".agncy-pfgallery-p-arr", self.gallery_prev );
			$( document ).on( "click", ".agncy-pfgallery-n-arr", self.gallery_next );
			$( document ).on( "select.flickity", '.agncy-lpf-c', self.gallery_adjust_isotope );

			// $( document ).on( "inview", ".brix-blog-block-loop-wrapper .hentry", function() {
			// 	$( this ).addClass( 'agncy-l-i-inview' );
   //          } );
		};

		/**
		 * Initialize all loops in page.
		 */
		// document.documentElement.addEventListener( "agncy-p-loaded", function() {
		//
		// } );

		self.init();

		// if ( $( "body" ).hasClass( "brix-active" ) ) {
		// 	$( window ).on( "brix_ready", self.init );
		// }
		// else {
		// 	$( document ).on( "ready", self.init );
		// }
	};

	( new AgncyBlog() );

} )( jQuery );