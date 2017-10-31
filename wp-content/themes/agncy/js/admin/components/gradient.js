( function( $ ) {
	"use strict";

	/**
	 * Gradient component.
	 * Usage:
	 *
	 * <agncy-gradient v-model="" opacity="" id=""></agncy-gradient>
	 */
	Vue.component( 'agncy-gradient', {
		template: '\
			<div class="agncy-gradient">\
				<div class="agncy-g-h">\
				<span class="agncy-f-l">{{ agncy_components.gradient.color }}</span>\
					<span class="agncy-f-l">{{ agncy_components.gradient.location }}</span>\
				</div>\
				<div class="agncy-g-cs" v-for="color in this.value.steps">\
					<agncy-color v-bind:opacity="opacity" v-bind:value="color"></agncy-color>\
					<input type="number" min="0" max="100" v-model="color.position">\
				</div>\
				<span class="agncy-f-l">{{ agncy_components.gradient.direction }}</span>\
				<agncy-graphic-radio v-model="value.direction" class="agncy-f-gr-horz agncy-f-g-direction" v-bind:options="agncy_components.gradient.directions[id]"></agncy-graphic-radio>\
			</div>\
		',
		props: [ "value", "opacity", "id" ],
		data: function () {
			return {}
		}
	} );
} )( jQuery );