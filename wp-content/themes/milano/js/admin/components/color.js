( function( $ ) {
	"use strict";

	/**
	 * Color component.
	 * Usage:
	 *
	 * <agncy-color v-model="" opacity=""></agncy-color>
	 */
	Vue.component( 'agncy-color', {
		template: '\
			<div class="agncy-color">\
				<input v-bind:value="value.color" v-bind:data-opacity="value.opacity" type="text">\
			</div>\
		',
		props: [ "value", "opacity" ],
		data: function() {
			return {
				color: "",
				opacity: ""
			}
		},
		mounted: function() {
			var self = this,
				input = $( "input", this.$el ),
				opacity = self.opacity == true || self.opacity === "true",
				format = opacity ? "rgb" : "hex";

			input.minicolors( {
				opacity: opacity,
				format: format,
				position: "top left",
				change: function( v, o ) {
					self.value.color = v;
					self.value.opacity = o;

					self.emit();
				}
			} );
		},
		methods: {
			emit: function() {
				this.$emit( "input", this.value );
			}
		}
	} );
} )( jQuery );