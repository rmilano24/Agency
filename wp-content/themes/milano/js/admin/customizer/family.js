( function( $ ) {
	"use strict";

	/**
	 * Get a list of Google Fonts to be used in a multiple select.
	 *
	 * @since 1.0.0
	 * @return {object}
	 */
	window.agncy_customizer_google_fonts_for_select = function() {
		var fonts = [];

		$.each( agncy.customizer.typography.google_fonts, function( family ) {
			fonts.push( {
				label: family,
				value: family,
				category: this.category,
				variants: this.variants,
				subsets: this.subsets
			} )
		} );

		return fonts;
	};

	/**
	 * Customizer font family selector component.
	 * Usage:
	 *
	 * <agncy-customizer-font-family v-model=""></agncy-customizer-font-family>
	 */
	window.agncy_customizer_font_family = {
		template: "#agncy-customizer-font-family",
		props: [ "value", "id", "label", "_id" ],
		data: function () {
			return {
				"_variants": "",
				"_subsets": ""
			}
		},
		methods: {
			getFontFamiliesForSelect: function() {
				return window.agncy_customizer_google_fonts_for_select();
			},
			remove: function() {
				window.agncy_customizer_font_families.removeFamily( this._id );
			},
			toggleFamilyInstance: function() {
				$( this.$el ).siblings().removeClass( "agncy-fc-exp" );
				$( this.$el ).toggleClass( "agncy-fc-exp" );
			},
			getFontSources: function() {
				var sources = [],
					self = this;

				$.each( agncy.customizer.typography.font_sources, function() {
					var s = JSON.parse( JSON.stringify( this ) );
					s.id = self.id + "_" + s.id;

					sources.push( s );
				} );

				return sources;
			},
			refreshGoogleFont: function( family ) {
				this.refreshGoogleFontVariants( family );
				this.refreshGoogleFontSubsets( family );
			},
			refreshGoogleFontSubsets: function( family ) {
				var s = $( ".agncy-font-subsets [data-agncy-select-input]", this.$el )[0].selectize,
					val = s.getValue();

				if ( val ) {
					this.$data._subsets = "" + val;
				}

				var select_options = [];

				$.each( this._googleFontSubsets(), function( i, val ) {
					select_options.push( {
						label: val,
						value: val
					} );
				} );

				s.clearOptions();
				s.addOption( select_options );

				if ( family ) {
					s.setValue( this.$data._subsets.split( "," ) );
					this.$data._subsets = "";
				}
			},
			refreshGoogleFontVariants: function( family ) {
				var s = $( ".agncy-font-variants [data-agncy-select-input]", this.$el )[0].selectize,
					val = s.getValue();

				if ( val ) {
					this.$data._variants = "" + val;
				}

				var select_options = [];

				$.each( this._googleFontVariants(), function( i, val ) {
					select_options.push( {
						label: val,
						value: val
					} );
				} );

				s.clearOptions();
				s.addOption( select_options );

				if ( family ) {
					s.setValue( this.$data._variants.split( "," ) );
					this.$data._variants = "";
				}
			},
			_googleFontVariants: function() {
				var fonts = window.agncy.customizer.typography.google_fonts;

				if ( this.value.google_fonts.font_family && typeof fonts[this.value.google_fonts.font_family] !== "undefined" ) {
					return fonts[this.value.google_fonts.font_family].variants;
				}

				return [];
			},
			_googleFontSubsets: function() {
				var fonts = window.agncy.customizer.typography.google_fonts;

				if ( this.value.google_fonts.font_family && typeof fonts[this.value.google_fonts.font_family] !== "undefined" ) {
					return fonts[this.value.google_fonts.font_family].subsets;
				}

				return [];
			},
			normalizeFamilyInstances: function() {
				if ( this.value.source == "google_fonts" ) {
					if ( this.value.google_fonts.variants == "" ) {
						var s = $( ".agncy-font-variants [data-agncy-select-input]", this.$el )[0].selectize;
						s.addItem( "regular" );

						this.$emit( "refresh_google_fonts_variants", {} );
					}
				}
			}
		},
		computed: {
			googleFontVariants: function() {
				return this._googleFontVariants();
			},
			googleFontSubsets: function() {
				return this._googleFontSubsets();
			},
			familyInfo: function() {
				var family = this.value[ this.value.source ].font_family;

				return family;
			}
		},
		updated: function() {
			this.normalizeFamilyInstances();

			this.$emit( "input", this.value );
		}
	};
} )( jQuery );