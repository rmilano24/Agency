( function( $ ) {
	"use strict";

	var AgncyFonts = function() {

		var self = this;

		/**
		 * The key used in the preloader page stack.
		 *
		 * @type {String}
		 */
		this.preloader_key = "fonts";

		/**
		 * WebFont loader configuration object.
		 *
		 * @type {Object}
		 */
		this.config = {
			/**
			 * All fonts have loaded callback.
			 *
			 * @since 1.0.0
			 */
			active: function() {
				window.agncy_preloader.complete( self.preloader_key );
			}
		};

		/**
		 * Load a font from a custom upload.
		 *
		 * @since 1.0.0
		 * @param {Object} font The font object.
		 */
		this.load_custom_font = function( font ) {
			if ( typeof self.config.custom === "undefined" ) {
				self.config.custom = {
					families: [],
					urls: []
				};
			}

			if ( font.custom.url ) {
				self.config.custom.families.push( font.custom.font_family );
				self.config.custom.urls.push( font.custom.url );
			}
		};

		/**
		 * Load a font from the Typekit service.
		 *
		 * @since 1.0.0
		 * @param {Object} font The font object.
		 */
		this.load_typekit_font = function( font ) {
			if ( typeof self.config.typekit === "undefined" ) {
				self.config.typekit = {
					id: ""
				};
			}

			if ( font.typekit.kitId ) {
				self.config.typekit.id = font.typekit.kitId;
			}
		};

		/**
		 * Load a font from the Google Fonts service.
		 *
		 * @since 1.0.0
		 * @param {Object} font The font object.
		 */
		this.load_google_font = function( font ) {
			if ( typeof self.config.google === "undefined" ) {
				self.config.google = {
					families: []
				};
			}

			var load = font.google_fonts.font_family;

			if ( font.google_fonts.variants ) {
				load += ":" + font.google_fonts.variants;
			}

			if ( font.google_fonts.subsets ) {
				load += ":" + font.google_fonts.subsets;
			}

			self.config.google.families.push( load );
		};

		/**
		 * Load the required fonts.
		 */
		this.init = function() {
			if ( typeof agncy_customizer === 'undefined' || ! agncy_customizer.typography || agncy_customizer.typography.global.length === 0 ) {
				self.config.active();

				return;
			}

			$.each( agncy_customizer.typography.global, function() {
				switch ( this.source ) {
					case "google_fonts":
						self.load_google_font( this );
						break;
					case "typekit":
						self.load_typekit_font( this );
						break;
					case "custom":
						self.load_custom_font( this );
						break;
					default:
						break;
				}
			} );

			WebFont.load( this.config );
		};

		/**
		 * Bind the loading of the required fonts.
		 */
		this.bind = function() {
			window.agncy_preloader.add( self.preloader_key );
		};

		$( document ).on( "ready", function() {
			self.init();
		} );

		this.bind();

	};

	( new AgncyFonts() );

} )( jQuery );