( function( $ ) {
	"use strict";

	// if ( typeof window.Ev_MediaSelector === "undefined" ) {
	// 	return;
	// }

	/**
	 * Image upload component.
	 * Usage:
	 *
	 * <agncy-image v-model="" load-label="" change-label="" remove-label="" preview-size=""></agncy-image>
	 */
	Vue.component( 'agncy-image', {
		template: '\
			<div class="agncy-image">\
				<div class="agncy-f-p_w">\
					<img v-bind:src="value._url" alt="">\
				</div>\
				<input type="hidden" v-model="value.id">\
				<button class="agncy-btn" type="button" v-on:click="this.load">{{ empty ? loadLabel : changeLabel }}</button>\
				<button class="agncy-btn agncy-remove" type="button" v-on:click="this.remove" v-if="empty === false">{{ removeLabel }}</button>\
			</div>\
		',
		props: [ "value", "load-label", "change-label", "remove-label", "preview-size" ],
		data: function () {
			return {}
		},
		computed: {
			empty: function() {
				return this.value.id == "";
			},
			imgUrl: function() {
				var url = this.value._url;

				if ( url != "" ) {
					return url;
				}

				return "";
			}
		},
		methods: {
			emit: function() {
				this.$emit( "input", this.value );
			},
			load: function() {
				var self = this,
					media = new window.Ev_MediaSelector( {
					type: "image",
					multiple: false,
					select: function( selection ) {
						var image_url = "";

						if ( selection.sizes ) {
							if ( selection.sizes[ self.previewSize ] && selection.sizes[ self.previewSize ].url ) {
								image_url = selection.sizes[ self.previewSize ].url;
							}
							else if ( selection.sizes.full && selection.sizes.full.url ) {
								image_url = selection.sizes.full.url;
							}
						}
						else {
							image_url = selection.url;
						}

						self.value.id = selection.id;
						self.value._url = image_url;

						self.emit();
					}
				} );

				media.open( [ self.value.id ] );
			},
			remove: function() {
				this.value.id = "";
				this.value._url = "";

				this.emit();
			}
		}
	} );
} )( jQuery );