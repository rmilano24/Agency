( function( $ ) {
	"use strict";

	/**
	 * Slider.
	 */
	var Agncy_Slider = function() {

		var self = this;

		/**
		 * Slider container.
		 */
		this.container = $( ".agncy-p-s_w" );

		/**
		 * Animation status.
		 */
		this.animating = false;

		/**
		 * Slider transition speed.
		 */
		this.speed = 1200;

		/**
		 * Slide active slide.
		 */
		this.in_class = "agncy-ps-s-in";

		/**
		 * Slide out slide.
		 */
		this.out_class = "agncy-ps-s-out";

		/**
		 * Active slide.
		 */
		this.active_class = "agncy-ps-s-active";

		/**
		 * Hammer instance.
		 */
		this.hammer = null;

		/**
		 * Go to the previous slide.
		 */
		this.prev = function() {
			if ( self.animating ) {
				return false;
			}

			var prev_slide = self.prev_slide();

			if ( ! prev_slide.length ) {
				return false;
			}

			// self.pauseAllVideos();

			self.animating = true;

			self.container.removeClass( "agncy-ps-forwards" );
			self.container.addClass( "agncy-ps-backwards" );

			var active_slide = self.active_slide();

			active_slide.addClass( self.out_class );
			active_slide.removeClass( self.active_class );
			prev_slide.addClass( self.in_class );

			setTimeout( function() {
				prev_slide.addClass( self.active_class );
				active_slide.removeClass( self.out_class );
				active_slide.removeClass( self.in_class );

				if ( prev_slide.prev().length === 0 ) {
					$( ".agncy-ps-s", self.container ).last().insertBefore( prev_slide );
				}

				// self.settle();

				self.animating = false;
			}, self.speed );

			return false;
		};

		/**
		 * Go to the next slide.
		 */
		this.next = function() {
			if ( self.animating ) {
				return false;
			}

			var next_slide = self.next_slide();

			if ( ! next_slide.length ) {
				return false;
			}

			// self.pauseAllVideos();

			self.animating = true;

			self.container.removeClass( "agncy-ps-backwards" );
			self.container.addClass( "agncy-ps-forwards" );

			var active_slide = self.active_slide();

			active_slide.addClass( self.out_class );
			active_slide.removeClass( self.active_class );
			next_slide.addClass( self.in_class );

			setTimeout( function() {
				next_slide.addClass( self.active_class );
				active_slide.removeClass( self.out_class );
				active_slide.removeClass( self.in_class );

				if ( next_slide.next().length === 0 ) {
					$( ".agncy-ps-s", self.container ).first().insertAfter( next_slide );
				}

				// self.settle();

				self.animating = false;
			}, self.speed );

			return false;
		};

		/**
		 * Pause all videos in the slideshow.
		 */
		this.pauseAllVideos = function() {
			$( "video, iframe" ).each( function() {
				switch ( this.tagName.toLowerCase() ) {
					case "iframe":
						if ( $( this ).attr( "src" ).indexOf( "player.vimeo.com" ) !== -1 ) {
							( new Vimeo.Player( this ) ).unload();
						}
						// else if ( $( this ).attr( "src" ).indexOf( "youtu" ) !== -1 ) {
						// 	var player = null;

						// 	if ( $( this ).data( "player" ) ) {
						// 		player = $( this ).data( "player" );
						// 	}
						// 	else {
						// 		$( this ).data( "player", new YT.Player( "player" ) );
						// 		player = $( this ).data( "player" );
						// 	}

						// 	player.pauseVideo();
						// }

						break;
					case "video":
						this.pause();

						break;
				}
			} );
		};

		/**
		 * Settle on a slide.
		 */
		this.settle = function() {
			if ( self.isMobile() ) {
				return;
			}

			$( "video, iframe", self.active_slide() ).each( function() {
				switch ( this.tagName.toLowerCase() ) {
					case "iframe":
						if ( $( this ).attr( "src" ).indexOf( "player.vimeo.com" ) !== -1 ) {
							( new Vimeo.Player( this ) ).play();
						}
						// else if ( $( this ).attr( "src" ).indexOf( "youtu" ) !== -1 ) {
						// 	var player = $( this ).data( "player" );

						// 	player.playVideo();
						// }

						break;
					case "video":
						this.play();

						break;
				}
			} );
		};

		/**
		 * Open a single image file in a lightbox.
		 */
		this.open_video = function( e ) {
			e.preventDefault();

			var href = $( this ).attr( "href" ),
				prev = $( this ).prev(),
				html = prev.get( 0 ).outerHTML;

			var pswpElement = document.querySelectorAll( ".pswp" )[0],
				items = [ {
					html: html
				} ];

			var options = {
					index: 0,
					closeOnScroll: false,
					tapToClose: false,
					shareButtons: false
				};

			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );

			gallery.listen( "close", self.settle );
			gallery.listen( "afterChange", function() {
				$( ".pswp video" ).get( 0 ).play();
			} );

			self.pauseAllVideos();
			gallery.init();

			return false;
		};

		/**
		 * Get the next slide.
		 */
		this.next_slide = function() {
			return self.active_slide().next();
		};

		/**
		 * Get the previous slide.
		 */
		this.prev_slide = function() {
			return self.active_slide().prev();
		};

		/**
		 * Get the active slide.
		 */
		this.active_slide = function() {
			return $( "." + self.in_class, self.container );
		};

		/**
		 * Events binding.
		 */
		this.bind = function() {
			$( ".agncy-p-s-nav-button-prev" ).on( "click", self.prev );
			$( ".agncy-p-s-nav-button-next" ).on( "click", self.next );

			$.ev_key( "right", self.next, { persistent: true } );
			$.ev_key( "left", self.prev, { persistent: true } );

			self.hammer = new Hammer( self.container.get( 0 ) );
			self.hammer.on( "swipeleft", self.next );
			self.hammer.on( "swiperight", self.prev );

			$( document ).on( "click", ".agncy-ps-s-media-video-poster-image", self.open_video );

			$( window ).on( "resize", self.setup );
			$( document ).on( "ready", self.setup );
			// $( document ).on( "ready", self.settle );
		};

		/**
		 * Check if we're on a mobile device.
		 */
		this.isMobile = function() {
			return $( window ).width() <= 768 || $( "body.agncy-mobile" ).length;
		};

		/**
		 * Setup scene.
		 */
		this.setup = function() {
			$( ".agncy-p-s" ).css( "height", window.agncy_header_height_css() );

			if ( self.isMobile() ) {
				self.pauseAllVideos();
			}
		};

		/**
		 * Init.
		 */
		this.init = function() {
			this.bind();

			var slides_count = $( ".agncy-ps-s", self.container ).length;

			if ( slides_count > 2 ) {
				$( ".agncy-ps-s", self.container ).last().insertBefore( $( ".agncy-ps-s", self.container ).first() );
			}
			else if ( slides_count == 2 ) {
				var last_slide_html = $( ".agncy-ps-s", self.container ).last()[ 0 ].outerHTML,
					first_slide_html = $( ".agncy-ps-s", self.container ).first()[ 0 ].outerHTML;

				$( ".agncy-ps-s", self.container ).first().before( last_slide_html );
				$( ".agncy-ps-s", self.container ).last().after( first_slide_html );

				$( ".agncy-ps-s", self.container ).last()
					.removeClass( self.active_class )
					.removeClass( self.in_class );
			}
		};

		this.init();

	};

	if ( $( ".agncy-p-s_w" ).length ) {
		( new Agncy_Slider() );
	}

} )( jQuery );