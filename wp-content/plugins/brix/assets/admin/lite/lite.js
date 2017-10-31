( function( $ ) {
	"use strict";

	var Brix_Lite = function() {
		var self = this;

		this.bind = function() {
			/**
			 * Frontend editing call-to-action
			 */
			$( ".brix-frontend-editing" ).each( function() {
				if ( typeof brix_pro !== "undefined" && brix_pro.enabled ) {
					return false;
				}

				var tpl = $.brixf.template( "brix-frontend-editing-promo", {} );

				var items = [
					{
				        src: tpl
				    }
				];

				$( this ).magnificPopup({
					items: items,
					type: 'inline'
				});
			} );

			/**
			 * Promo page galleries.
			 */
			$( ".brix-admin-page-feature-box" ).each( function() {
				var items = [];

				$( "[data-img]", this ).each( function() {
					var src = $( this ).attr( "data-src" ),
						title = $( this ).attr( "data-title" );

					items.push( {
						img: '<img src="' + src + '">',
						description: title
					} );
				} );

				if ( ! items.length ) {
					return;
				}

				$( ".brix-admin-page-feature-box-action", this ).magnificPopup({
					items: items,
					gallery: {
					  enabled: true
					},
					type: 'inline',
					inline: {
						markup: "<div class=\"brix-pro-page-gallery-item\"><div class=\"mfp-close\"></div><span class='mfp-img'></span><div class='mfp-description'></div></div>"
					}
				});
			} );

		};

		this.init = function() {
			$( document ).on( "ready", self.bind );
		};

		this.init();
	};

	var bl = new Brix_Lite();

} )( jQuery );