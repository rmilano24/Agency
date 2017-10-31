( function( $ ) {
	"use strict";

	var Brix_Gallery = function() {

		var self = this;

		/**
		 * Gallery items.
		 */
		this.items = [];

		/**
		 * Initialize the lightbox component.
		 */
		this.init_lightbox = function() {
			var gallery = $( this ).parents( ".brix-gallery-container" ).first(),
				media_item = $( this ),
				pswpElement = document.querySelectorAll('.brix-gallery-pswp')[0],
				options = {
					index: media_item.index(),
					closeOnScroll: false,
					tapToClose: false,
					shareButtons: false,
					getThumbBoundsFn: function( index ) {
						if ( typeof window.Brix_Gallery_getThumbBoundsFn === "function" ) {
							return window.Brix_Gallery_getThumbBoundsFn( index, self.items );
						}

						return null;
					}
				};

			self.items = [];

			$( ".brix-gallery-item", gallery ).each( function() {
				var item_data = JSON.parse( $( this ).attr( "data-data" ) );
				item_data._thumbnail = $( ".brix-image", this ).first().get( 0 );

				self.items.push( item_data );
			} );

			var pswp_gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, self.items, options );

			gallery.trigger( "brix_gallery_setup", [ pswp_gallery ] );

			pswp_gallery.init();

			return false;
		};

		/**
		 * Adjust the gallery.
		 */
		this.adjust = function() {
			$( ".brix-gallery-container" ).fitVids();
		};

		/**
		 * Initialize the Masonry gallery.
		 */
		this.init_masonry = function() {
			var options = {
				transitionDuration: 0
			};

			var wrapper = $( this );

			wrapper.on( "layoutComplete", self.adjust );
		};

		/**
		 * Load a single gallery image.
		 */
		this.loaded_image_masonry = function() {
			var options = {
				transitionDuration: 0
			};

			var wrapper = $( this ).parents( ".brix-gallery-container " ).first();

			wrapper.isotope( options );

			setTimeout( function() {
				if ( wrapper.data( "isotope" ) ) {
					wrapper.isotope();
				}
			}, 20 );
		};

		/**
		 * Reload the Masonry gallery.
		 */
		this.reload_masonry = function() {
			var container = $( this );

			if ( container.data( "isotope" ) ) {
				container.isotope( 'reloadItems' );

				setTimeout( function() {
					container.isotope();
				}, 10 );
			}
		};

		/**
		 * Load more items in the gallery.
		 */
		this.load_more = function() {
			var button = $( this ),
				href = $( this ).attr( "data-href" ),
				block = $( this ).parents( ".brix-section-column-block-gallery" ).first(),
				block_count = block.attr( "data-count" ),
				container = $( ".brix-gallery-container", block ),
				type = container.attr( "data-gallery-type" );

			if ( container.hasClass( "brix-gallery-loading" ) ) {
				return false;
			}

			container.addClass( "brix-gallery-loading" );
			button.attr( "disabled", "disabled" );

			$.ajax( {
				type: "GET",
				url: href,
				success: function( resp ) {
					var resp_block = $( ".brix-section-column-block[data-count='" + block_count + "']", resp );

					$.brix_load_images( $( "img", resp_block ), {
						all: function() {
							// $( ".brix-gallery-item", resp_block ).appendTo( container );
							$( ".brix-gallery-item", resp_block ).insertAfter( $( ".brix-gallery-item", container ).last() );

							setTimeout( function() {
								window.brix.load_images( container );
							}, 1 );

							if ( $( ".brix-gallery-load-more", resp_block ).length === 0 ) {
								$( ".brix-gallery-load-more", block ).parent().remove();
							}
							else {
								$( ".brix-gallery-load-more", block ).replaceWith( $( ".brix-gallery-load-more", resp_block ) );
								button.removeAttr( "disabled" );
							}

							container.trigger( "brix_gallery_reload_" + type );
							container.removeClass( "brix-gallery-loading" );
						}
					} );
				}
			} );

			return false;
		}

		/**
		 * Event binding.
		 */
		this.bind = function() {
			/* Initialize the lightbox. */
			$( document ).on( "click", ".brix-gallery-container .brix-gallery-lightbox", self.init_lightbox );

			/* Load more items in the gallery. */
			$( document ).on( "click", ".brix-gallery-load-more", self.load_more );

			/* Reload the Masonry gallery. */
			$( document ).on( "brix_gallery_init_masonry", ".brix-gallery-container", self.init_masonry );
			$( document ).on( "brix_gallery_reload_masonry", ".brix-gallery-container", self.reload_masonry );
			$( document ).on( "brix-img-loaded", ".brix-gallery-container-masonry .brix-block-preloaded-img", self.loaded_image_masonry );
		};

		this.bind();

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			$( ".brix-gallery-container" ).each( function() {
				var type = $( this ).attr( "data-gallery-type" );

				$( this ).trigger( "brix_gallery_init_" + type );
			} );
		};

		this.init();

	};

	$( window ).on( "brix_ready", function() {
		( new Brix_Gallery() );
	} );
} )( jQuery );