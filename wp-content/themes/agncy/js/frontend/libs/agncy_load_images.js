( function( $ ) {
	"use strict";

	if ( typeof $.agncy_load_images !== "undefined" ) {
		return;
	}

	/**
	 * Images preloader. Fires a callback when a given image, all images in a
	 * container or a background images applied to a page element have been
	 * loaded.
	 *
	 * @param  {String|Object} element A selector string or a DOM element.
	 * @param  {Object} config The configuration object.
	 */
	$.agncy_load_images = function( element, config ) {
		element = $( element );

		config = $.extend( {}, {
			/* Set to true to wait for the image/all the images to be loaded before firing the callbacks. */
			preload: true,

			/* The HTML attribute that stores the image URL. Used with "data-" prepended as well for lazy loading. */
			attr: "src",

			/* The HTML data attribute ("data-" prepended) that stores the image URL for lazy loading. */
			background_attr: "bg",

			/* The HTML attribute that stores the high definition image URL. Used with "data-" prepended as well for lazy loading. */
			retina_attr: "srcset",

			/* The HTML attribute that stores the high definition background image URL. Used with "data-" prepended as well for lazy loading. */
			retina_background_attr: "srcset-bg",

			/* Callback fired when all the images have been loaded. */
			all: function() {},

			/* Callback fired whenever an image is loaded. */
			single: function() {}
		}, config );

		if ( ! element.length ) {
			config.all( [] );

			return;
		}

		var instance = {
			images: [],
			loaded: 0,

			parse_densities: function( srcset ) {
				var src = "";

				if ( typeof srcset === "object" ) {
					if ( srcset[window.devicePixelRatio] ) {
						src = srcset[window.devicePixelRatio];
					}
					else {
						for ( var density in srcset ) {
							if ( density < window.devicePixelRatio ) {
								src = srcset[density];
							}
						}
					}
				}
				else {
					src = srcset;
				}

				return src;
			},

			get_src: function( obj ) {
				obj = $( obj );

				var src = "",
					lazy = false;

				if ( obj.is( "img" ) ) {
					src = obj.attr( "src" );
					lazy = ( ! src || src === "" || src === "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" ) && obj.data( config.attr ) && obj.data( config.attr ) !== "";

					if ( lazy ) {
						src = obj.data( config.attr );
					}

					/* Check if alternative sizes have been provided for different screen densities. */
					if ( obj.data( config.retina_attr ) && obj.data( config.retina_attr ) !== "" ) {
						var srcset = instance.parse_densities( obj.data( config.retina_attr ) );

						if ( srcset !== "" ) {
							src = srcset;
							lazy = true;
						}
					}
				}
				else {
					var background_image = obj.css( "background-image" ).replace( "url(", "" ).replace( ")", "" ),
						background_image_lazy = obj.data( config.background_attr );

					if ( background_image != "none" ) {
						src = background_image;
					}

					if ( background_image_lazy != "" ) {
						src = background_image_lazy;
						lazy = true;
					}

					/* Check if alternative sizes have been provided for different screen densities. */
					if ( obj.data( config.retina_background_attr ) && obj.data( config.retina_background_attr ) !== "" ) {
						var srcset = instance.parse_densities( obj.data( config.retina_background_attr ) );

						if ( srcset !== "" ) {
							src = srcset;
							lazy = true;
						}
					}
				}

				return {
					"element": obj,
					"src": src,
					"lazy": lazy
				};
			},

			load_callback: function( data ) {
				var object = data.element;

				this.loaded++;

				if ( data.src !== undefined ) {
					if ( object.is( "img" ) && data.lazy ) {
						$( object ).attr( "src", data.src );
					}
					else if ( ! object.is( "img" ) ) {
						$( object ).css( "background-image", "url(" + data.src + ")" );
					}
				}

				config.single( object );

				if ( this.loaded === this.images.length ) {
					config.all( this.images );
				}
			}
		};

		element.each( function() {
			instance.images.push( $( this ) );
		} );

		$.each( instance.images, function() {
			var self = this,
				data = instance.get_src( self );

			if ( config.preload ) {
				if ( data.src !== undefined ) {
					$( "<img />" )
						.one( "load error", function() {
							instance.load_callback( data );
						} )
						.attr( "src", data.src );
				}
				else {
					instance.load_callback( data );
				}
			}
			else {
				instance.load_callback( data );
			}
		} );
	};
} )( jQuery );