( function( $ ) {
	"use strict";

	var agncy_customizer_done_loading = false;

	var load_agncy_customizer = function() {
		if ( agncy_customizer_done_loading ) {
			return;
		}

		agncy_customizer_done_loading = true;

		/**
		 * Object cloning.
		 */
		function _ag_clone( obj, extend ) {
			var clone = JSON.parse( JSON.stringify( obj ) );

			if ( extend ) {
				clone = $.extend( {}, clone, extend );
			}

			return clone;
		};

		/**
		 * Force refresh button.
		 */
		$( "#sub-accordion-panel-agncy" ).find( "li" ).first().after( '<li class="agncy-force-refresh">\
				<button type="button" class="button button-primary" data-agncy-customizer-force-refresh>' + agncy.customizer.strings.force_refresh_button + '</button>\
				<p>' + agncy.customizer.strings.force_refresh_button_help + '</p>\
			</li>' );

		/**
		 * Force refresh button action.
		 */
		$( document ).on( "click", "[data-agncy-customizer-force-refresh]", function() {
			$( "#save" )
				.prop( "disabled", false )
				.trigger( "click" );

			return false;
		} );

		/**
		 * Colors.
		 */
		window.agncy_customizer_colors = new Vue( {
			el: '#sub-accordion-section-agncy_colors .agncy-colors',
			data: _ag_clone( agncy.customizer.colors.data ),
			methods: {
				updateControl: function( v ) {
					var $control = $( this.$el ).parents( ".customize-control" ).first(),
						_data = _ag_clone( this.$data );

					$( "[data-customize-setting-link]", $control )
						.val( JSON.stringify( _data ) )
						.trigger( "change" );
				}
			}
		} );

		/**
		 * Typography.
		 */
		window.agncy_customizer_font_families = new Vue( {
			el: '#sub-accordion-section-agncy_typography .agncy-font_families',
			data: _ag_clone( agncy.customizer.typography.data ),
			components: {
				"agncy-customizer-font-family-instance": window.agncy_customizer_font_family_instance,
				"agncy-customizer-font-family": window.agncy_customizer_font_family
			},
			methods: {
				addFamily: function() {
					var d = new Date().getTime(),
						label = prompt( agncy.customizer.typography.family_label_ask , "" ),
						id = "font-family-" + d,
						family_data = _ag_clone( agncy.customizer.typography.family_default_data, {
							"label": label
						} );

					Vue.set( this.global, id, family_data );
				},
				removeFamily: function( id ) {
					var self = this;

					Vue.delete( this.global, id );

					$.each( this.$data.instances, function( group, instance ) {
						$.each( instance, function( key, obj ) {
							if ( typeof self.$data.global[ obj.font_family ] === "undefined" ) {
								Vue.set( obj, "font_family", "primary" );
							}
						} );
					} );
				},
				getFamilyLabel: function( id ) {
					var label = this.global[id].label;

					if ( label ) {
						return label;
					}

					return id;
				},
				getInstanceLabel: function( key ) {
					return agncy.customizer.typography.structure[key].label;
				},
				getInstanceDescription: function( key ) {
					return agncy.customizer.typography.structure[key].description;
				},
				getInstanceSubLabel: function( key, kf ) {
					return agncy.customizer.typography.structure[key].fields[kf];
				},
				refreshGoogleFontVariants: function( instances ) {
					var self = this;

					$.each( instances, function( group, instance ) {
						$.each( instance, function( key, obj ) {
							if ( obj.font_family && self.$data.global[ obj.font_family ].source == "google_fonts" ) {
								Vue.set( obj, "variant", "regular" );
							}
						} );
					} );

					$( ".agncy-fi-v" ).each( function() {
						if ( $( this ).children().length === 1 ) {
							$( this ).val( $( this ).children().first().attr( "value" ) );
						}
					} );
				},
				updateControl: function( v ) {
					var $control = $( this.$el ).parents( ".customize-control" ).first(),
						_data = _ag_clone( this.$data );

					$.each( _data.instances, function() {
						$.each( this, function() {
							delete this._defaults;
						} );
					} );

					$( "[data-customize-setting-link]", $control )
						.val( JSON.stringify( _data ) )
						.trigger( "change" );
				}
			}
		} );

	};

	if ( typeof wp.customize !== "undefined" ) {
		wp.customize.bind( "pane-contents-reflowed", load_agncy_customizer, false );
	}

} )( jQuery );