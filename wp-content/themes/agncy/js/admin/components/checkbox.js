( function( $ ) {
	"use strict";

	/**
	 * Checkbox component.
	 * Usage:
	 *
	 * <agncy-checkbox v-model="" label="" id=""></agncy-checkbox>
	 */
	Vue.component( 'agncy-checkbox', {
		template: '\
			<div class="agncy-checkbox">\
				<input type="checkbox" v-bind:id="id" v-bind:checked="value == true" v-on:change="updateValue( $event.target.checked )">\
				<label v-bind:for="id"><span class="screen-reader-text">{{ label }}</span></label>\
			</div>\
		',
		props: [ "value", "id", "label" ],
		data: function () {
			return {}
		},
		methods: {
			updateValue: function( v ) {
				this.value = ( v === true );

				this.$emit( "input", this.value );
			}
		}
	} );
} )( jQuery );