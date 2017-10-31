( function( $ ) {
	"use strict";

	/**
	 * Customizer color component.
	 * Usage:
	 *
	 * <agncy-customizer-color v-model=""></agncy-customizer-color>
	 */
	Vue.component( "agncy-customizer-color", {
		template: "#agncy-customizer-color",
		props: [ "value", "_key", "_group" ],
		data: function () {
			return {}
		},
		methods: {
			updateColor: function( v ) {
				this.value = v;

				delete this.value.opacity;

				this.$emit( "input", this.value );
			},
		}
	} );
} )( jQuery );