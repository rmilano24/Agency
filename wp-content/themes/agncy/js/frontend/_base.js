( function( $ ) {
	"use strict";

	/**
	 * Extend an options object directly from the global namespace.
	 */
	window.agncy_extend_options = function( options, key ) {
		if ( typeof window[key] !== "undefined" ) {
			options = $.extend( {}, options, window[key] );
		}

		return options;
	};

	/**
	 * Extend an option parameter directly from the global namespace.
	 */
	window.agncy_extend_option = function( option, key ) {
		if ( typeof window[key] !== "undefined" ) {
			option = window[key];
		}

		return option;
	};

	/**
	 * Load an image.
	 */
	window.agncy_load_image = function( figure, all_callback ) {
		$.agncy_load_images( $( "img", figure ), {
			single: function( obj ) {
				figure.addClass( "agncy-img-reveal" );

				obj.trigger( "agncy-img-loaded" );
				obj.trigger( "brix-img-loaded" );
				obj.addClass( "agncy-img-loaded brix-img-loaded" );

				obj.next().remove();
			},
			all: all_callback
		} );
	};

	/**
	 * Set the mobile threshold value for a screen width to be identified as
	 * mobile.
	 */
	window.agncy_mobile_threshold_value = function() {
		var threshold = 768;

		return agncy_extend_option( threshold, "agncy_mobile_threshold" );
	};

	/**
	 * Top offset CSS.
	 */
	window.agncy_header_height_css = function() {
		var hh = $( ".agncy-h" ).outerHeight(),
			ah = $( "#wpadminbar" ).length ? $( "#wpadminbar" ).outerHeight() : 0;

		hh += ah;

		return "calc( 100vh - " + hh + "px )";
	};

	/**
	 * Photoswipe.
	 */
	var AngcyPhotoswipe = function() {

		var self = this;

		/**
		 * Check if an URL points to an image file.
		 */
		this.is_img = function( url ) {
			return( url.match(/\.(jpeg|jpg|gif|png)$/) != null );
		};

		/**
		 * Gallery items.
		 */
		this.items = [];

		/**
		 * Determine the element coordinates.
		 */
		this.getThumbBoundsFn = function( index ) {
		    // find thumbnail element
		    var thumbnail = $( "img", self.items[ index ]._thumbnail );

		    // get window scroll Y
		    var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
		    // optionally get horizontal scroll

		    // get position of element relative to viewport
		    var rect = thumbnail.getBoundingClientRect(),
		    	top = rect.top;

		    if ( $( "body" ).hasClass( "admin-bar" ) ) {
		    	top -= $( "#wpadminbar" ).outerHeight();
		    }

		    // w = width
		    return {x:rect.left, y:top + pageYScroll, w:rect.width};
		};

		/**
		 * Open a single image file in a lightbox.
		 */
		this.open_single = function() {
			var href = $( this ).attr( "href" );

			if ( ! self.is_img( href ) ) {
				return;
			}

			var pswpElement = document.querySelectorAll( ".pswp" )[0],
				ratio = parseInt( $( "img", this ).attr( "width" ), 10 ) / parseInt( $( "img", this ).attr( "height" ), 10 ),
				max_width = $( window ).width() * 0.8;

			self.items = [ {
				src: href,
				msrc: href,
				w: max_width,
				h: max_width * 1/ratio,
				_thumbnail: this
			} ];

			var options = {
					index: 0,
					closeOnScroll: false,
					tapToClose: false,
					shareButtons: false,
					getThumbBoundsFn: self.getThumbBoundsFn
				};

			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, self.items, options );

			gallery.init();

			return false;
		};

		/**
		 * Open a gallery in a lightbox.
		 */
		this.open_gallery = function() {
			var href = $( this ).attr( "href" ),
				gallery = $( this ).parents( ".gallery,.tiled-gallery" ).first(),
				max_width = $( window ).width() * 0.8,
				index = 0;

			self.items = [];

			$( ".gallery-icon > a, .tiled-gallery-item > a", gallery ).each( function() {
				var thumb = this,
					href = $( this ).attr( "href" ),
					ratio = parseInt( $( "img", this ).attr( "width" ), 10 ) / parseInt( $( "img", this ).attr( "height" ), 10 );

				if ( ! self.is_img( href ) ) {
					return;
				}

				self.items.push( {
					src: href,
					msrc: href,
					w: max_width,
					h: max_width * 1/ratio,
					_thumbnail: thumb
				} );
			} );

			$( ".gallery-item, .tiled-gallery-item", gallery ).each( function( i ) {
				var link = $( "a", this ).first();

				if ( link.attr( "href" ) == href ) {
					index = i;

					return false;
				}
			} );

			var pswpElement = document.querySelectorAll( ".pswp" )[0],
				options = {
					index: index,
					closeOnScroll: false,
					tapToClose: false,
					shareButtons: false,
					getThumbBoundsFn: self.getThumbBoundsFn
				};

			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, self.items, options );

			gallery.init();

			return false;
		};

		this.init = function() {
			$( document ).on( "click", ".agncy-mc-content p > a:has( img ), .agncy-mc-content figure > a:has( img )", self.open_single );
			$( document ).on( "click", ".gallery-icon > a, .tiled-gallery-item > a", self.open_gallery );
		};

		this.init();

	};

	( new AngcyPhotoswipe() );

	/**
	 * Handle video embeds.
	 */
	var AgncyEmbeds = function() {
		var self = this;

		this.handle = function() {
			$( ".agncy-c" ).fitVids();
		};

		this.init = function() {
			$( window ).on( "resize", this.handle );

			$( document ).on( "ready", function() {
				setTimeout( function() {
					self.handle();
				}, 500 );
			} );

			this.handle();
		};

		this.init();
	};

	( new AgncyEmbeds() );

	/**
	 * Toggle self-hosted videos playing status.
	 */
	$( document ).on( "click", "video", function() {
		if ( this.paused ) {
			this.play();
		}
		else {
			this.pause();
		}
	} );

} )( jQuery );