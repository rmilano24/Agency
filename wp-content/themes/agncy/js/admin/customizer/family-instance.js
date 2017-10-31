( function( $ ) {
	"use strict";

	/**
	 * Customizer font family selector component.
	 * Usage:
	 *
	 * <agncy-customizer-font-family-instance v-model=""></agncy-customizer-font-family-instance>
	 */
	window.agncy_customizer_font_family_instance = {
		template: "#agncy-customizer-font-family-instance",
		props: [ "value", "id", "label", "families", "_defaults" ],
		data: function () {
			return {
			}
		},
		created: function() {
			if ( ! this.value.text_transform ) {
				if ( this._defaults.text_transform ) {
					this.value.text_transform = this._defaults.text_transform;
				}
				else {
					this.value.text_transform = "none";
				}
			}

			if ( typeof this._defaults.letter_spacing === "undefined" || ! this._defaults.letter_spacing ) {
				this._defaults.letter_spacing = "0";
			}

			if ( typeof this._defaults.line_height === "undefined" || ! this._defaults.line_height ) {
				this._defaults.line_height = "1em";
			}

			if ( typeof this._defaults.font_size === "undefined" || ! this._defaults.font_size ) {
				this._defaults.font_size = "1em";
			}

			if ( ! this.value.variant ) {
				if ( this._defaults.variant ) {
					this.value.variant = this._defaults.variant;
				}
				else {
					this.value.variant = "regular";
				}
			}

			if ( ! this.value.font_family && this._defaults.font_family ) {
				this.value.font_family = this._defaults.font_family;
			}
		},
		methods: {
			toggleFamilyInstance: function() {
				$( this.$el ).siblings().removeClass( "agncy-fc-exp" );
				$( this.$el ).toggleClass( "agncy-fc-exp" );
			},
			changeFamily: function() {
				var families = this.families,
					family = this.value.font_family,
					variants = this.getFamilyVariants( families, family ),
					variant = this.value.variant;

				if ( ! family ) {
					return;
				}

				if ( families[family].source === "google_fonts" ) {
					if ( ( variant && variants.indexOf( variant ) === -1 ) || variants.length === 0 ) {
						this.value.variant = "regular";
					}
				}
			},
			getFamilyVariants: function( families, family ) {
				var variants = [];

				if ( ! family ) {
					return variants;
				}

				if ( families[family].source == "google_fonts" ) {
					variants = families[family].google_fonts.variants.split( ',' );

					if ( variants.length === 1 && variants[0] === "" ) {
						variants = [ "regular" ];
					}
				}
				else {
					variants = [
						"100",
						"100italic",
						"200",
						"200italic",
						"300",
						"300italic",
						"regular",
						"italic",
						"500",
						"500italic",
						"600",
						"600italic",
						"700",
						"700italic",
						"800",
						"800italic",
						"900",
						"900italic",
					];
				}

				return variants;
			}
		},
		computed: {
			fontInfo: function() {
				var font_size = "",
					line_height = "";

				if ( this.value.font_size ) {
					font_size = this.value.font_size;
				}
				else {
					if ( this._defaults.font_size ) {
						font_size = this._defaults.font_size;
					}
				}

				if ( this.value.line_height ) {
					line_height = this.value.line_height;
				}
				else {
					if ( this._defaults.line_height ) {
						line_height = this._defaults.line_height;
					}
				}

				return font_size + " / " + line_height;
			}
		},
		updated: function() {
			this.$emit( "input", this.value );
		}
	};
} )( jQuery );