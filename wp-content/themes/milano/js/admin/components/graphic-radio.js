( function( $ ) {
	"use strict";

	/**
	 * Graphic radio component.
	 * Usage:
	 *
	 * <agncy-graphic-radio v-model="" options="" class=""></agncy-graphic-radio>
	 */
	Vue.component( 'agncy-graphic-radio', {
		template: '\
			<div class="agncy-graphic-radio" v-bind:class="this.class">\
				<div v-bind:data-value="option.value" v-for="option in this.options" v-if="isOptionVisible( option.value )">\
					<input v-bind:checked="value === option.value" v-on:change="updateValue( $event.target.value )" v-bind:id="option.id" v-bind:class="getInputClass( option.label )" type="radio" v-bind:value="option.value">\
					<label v-bind:for="option.id" v-html="option.label"></label>\
				</div>\
			</div>\
		',
		props: [ "value", "options", "class", "visibility" ],
		data: function () {
			return {}
		},
		methods: {
			isOptionVisible: function( v ) {
				if ( typeof this.visibility !== "undefined" && typeof this.visibility[ v ] !== "undefined" ) {
					return this.visibility[ v ];
				}

				return true;
			},
			getInputClass: function( label ) {
				var classname = [ "agncy-f-gr" ],
					isHTML = RegExp.prototype.test.bind(/(<([^>]+)>)/i);

				if ( ! isHTML( label ) ) {
					classname.push( "agncy-f-gr-t" );
				}

				return classname.join( " " );
			},
			updateValue: function( v ) {
				this.value = v;

				this.$emit( "input", this.value );
				this.$emit( "change", this.value );
			}
		}
	} );
} )( jQuery );