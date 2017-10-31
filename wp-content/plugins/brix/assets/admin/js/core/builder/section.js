( function( $ ) {
	"use strict";

	window.brix_section = new function() {

		var self = this;

		/**
		 * Configuration.
		 */
		this.config = {
			slide: {
				duration: 200,
				easing: "easeInOutCubic"
			}
		};

		/**
		 * Check if the section's appearance has been tweaked.
		 */
		this.has_appearance = function( data ) {
			if ( data["background"] && data["background"] !== "" ) {
				return true;
			}

			if ( data["spacing"] ) {
				for ( var i in data["spacing"] ) {
					for ( var j in data["spacing"][i] ) {
						if ( data["spacing"][i][j] != "" && data["spacing"][i][j] != "0" ) {
							return true;
						}
					}
				}
			}

			return false;
		};

		/**
		 * Section setup.
		 */
		this.setup = function( section, data ) {
			if ( ! data ) {
				return;
			}

			if ( self.has_appearance( data ) ) {
				section.addClass( "brix-has-appearance" );
			}
			else {
				section.removeClass( "brix-has-appearance" );
			}

			section.removeClass( "brix-section-bright-text" );

			$( ".brix-section-background", section )
				.removeClass( "brix-background-image" )
				.removeClass( "brix-background-video" )
				.removeClass( "brix-background-color" )
				.removeClass( "brix-background-gradient" )
				.css( "background-color", "" );

			var background_title = '';
			// $( ".brix-section-background", section ).removeClass( "brix-tooltip" );
			$( ".brix-section-background", section ).removeAttr( "data-title" );

			if ( data.background && data.background != "" ) {
				var bg_image = data.background_image.desktop;

				if ( data.background == "image" && typeof bg_image !== "undefined" && bg_image.image && bg_image.image["desktop"]["1"].id != "" ) {
					$( ".brix-section-background", section )
						.addClass( "brix-background-image" );

					background_title = brix_i18n_strings.background_image;
				}
				else if ( data.background == "video" && data.background_video && data.background_video.url != "" ) {
					$( ".brix-section-background", section )
						.addClass( "brix-background-video" );

					background_title = brix_i18n_strings.background_video;
				}
				else if ( typeof bg_image !== "undefined" && bg_image.color_type && bg_image.color_type == "gradient" ) {
					$( ".brix-section-background", section )
						.addClass( "brix-background-gradient" );

					background_title = brix_i18n_strings.background_gradient;
				}
				else if ( typeof bg_image !== "undefined" && bg_image.color_type && bg_image.color_type == "solid" && bg_image.color && bg_image.color.color != "" ) {
					$( ".brix-section-background", section )
						.addClass( "brix-background-color");

					$( ".brix-section-background span", section )
						.css( "background-color", bg_image.color.color );

					if ( ! brix_is_color_bright( bg_image.color.color ) ) {
						section.addClass( "brix-section-bright-text" );
					}

					background_title = brix_i18n_strings.background_color;
				}
			}

			if ( background_title != '' ) {
				$( ".brix-section-background", section ).attr( "data-title", background_title );
				// $( ".brix-section-background", section ).addClass( "brix-tooltip" );
			}

			if ( data._hidden && data._hidden == "1" ) {
				section.addClass( "brix-hidden" );
			}
			else {
				section.removeClass( "brix-hidden" );
			}

			$( ".brix-section-width-label", section ).html( brix_i18n_strings["section_width_" + data.section_width] );

			if ( data.section_layout ) {
				self.change_layout( section, data.section_layout.split( " " ) );
			}
		};

		/**
		 * Get the maximum number of columns a subsection can contain.
		 */
		this.column_limit = function( subsection ) {
			var size = subsection.attr( "data-size" ),
				den = parseInt( size.split( "/" )[1], 10 ),
				num = parseInt( size.split( "/" )[0], 10 );

			return 12 * num / den;
		};

		/**
		 * Duplicate a section.
		 */
		this.duplicate = function( section ) {
			var sect_height = section.outerHeight(),
				spacer = $( "<div class='brix-section-spacer'></div>" );

			spacer
				.css( { height: sect_height } )
				.insertAfter( section );

			brix_maybe_scroll( spacer, function() {
				var new_section = section.clone( false ),
					box = brix_box( section );

				new_section.addClass( "brix-adding" );

				spacer
					.after( new_section )
					.remove();

				brix_controller.adjust( box );

				setTimeout( function() {
					new_section.removeClass( "brix-adding" );
					window.brix_controller.refresh( box );

					window.brix_controller.save_state();
				}, 1 );
			} );
		};

		/**
		 * Remove a section.
		 */
		this.remove = function( section ) {
			var box = brix_box( section ),
				is_backend_editing = $( "body" ).hasClass( "brix-is-backend-editing" ),
				duration = ! is_backend_editing ? 0 : this.config.slide.duration,
				num_rows = $( ".brix-section-row", section ).length,
				redraw = $( ".brix-section-extra-wrapper", section ).hasClass( "brix-section-empty" ) ? false : true;

			section.slideUp( {
				"duration": duration,
				"easing": this.config.slide.easing,
				"complete": function() {
					section.remove();

					window.brix_controller.refresh( box, redraw );

					window.brix_controller.save_state();
				}
			} );
		};

		/**
		 * Add a row to a specific subsection.
		 */
		this.add_row = function( subsection ) {
			var box = brix_box( subsection ),
				is_backend_editing = $( "body" ).hasClass( "brix-is-backend-editing" ),
				adding_guard = "brix-adding-row";

			if ( is_backend_editing ) {
				if ( subsection.data( adding_guard ) ) {
					return false;
				}

				subsection.data( adding_guard, true );
			}

			var section = brix_parent( subsection, ".brix-section" ),
				html = $( $.brixf.template( "brix-row", {} ) ),
				index = typeof window.brix_add_row_index !== "undefined" ? window.brix_add_row_index : null;

			if ( index === null ) {
				$( ".brix-subsection-rows-wrapper", subsection )
					.append( html );
			}
			else {
				$( ".brix-section-row" ).eq( index ).after( html );
			}

			delete window.brix_add_row_index;

			if ( is_backend_editing ) {
				html.hide();
				html.slideDown( {
					"duration": self.config.slide.duration,
					"easing": self.config.slide.easing,
					"complete": function() {
						brix_maybe_scroll( html );
						subsection.data( adding_guard, false );
					}
				} );
			}

			self.adjust( section );
		};

		/**
		 * Evaluate a section layout.
		 */
		this.adjust = function( section ) {
			var subsections = $( ".brix-subsection", section ),
				wrapper = $( ".brix-section-extra-wrapper", section );

			wrapper.removeClass( "brix-section-empty" );

			if ( ! $( ".brix-section-column", section ).length ) {
				wrapper.addClass( "brix-section-empty" );
			}

			var move_up_ctrl = $( ".brix-section-move-up", section ),
				move_down_ctrl = $( ".brix-section-move-down", section );

			move_up_ctrl.attr( "disabled", "disabled" );
			move_down_ctrl.attr( "disabled", "disabled" );

			if ( section.prev( ".brix-section" ).length ) {
				move_up_ctrl.removeAttr( "disabled" );
			}

			if ( section.next( ".brix-section" ).length ) {
				move_down_ctrl.removeAttr( "disabled" );
			}

			subsections.each( function() {
				var subsection = $( this );

				var rows = $( ".brix-section-row", subsection ),
					last_row = rows.last(),
					no_rows_class = "brix-rows-layout-empty",
					empty_row_class = "brix-row-layout-empty";

				subsection.removeClass( no_rows_class );
				subsection.removeClass( "brix-rows-pending" );

				rows.each( function() {
					var row = $( this );

					row.removeClass( empty_row_class );

					if ( ! $( ".brix-section-column", row ).length ) {
						row.addClass( empty_row_class );
					}

					brix_row.setup( row );
				} );

				if ( last_row.length ) {
					if ( ! $( ".brix-section-column", subsection ).length ) {
						subsection.addClass( no_rows_class );
					}

					if ( ! $( ".brix-section-column", last_row ).length ) {
						subsection.addClass( "brix-rows-pending" );
					}
				}
				else {
					brix_section.add_row( subsection );
				}
			} );
		};

		/**
		 * Change a section layout.
		 */
		this.change_layout = function( section, layout ) {
			section = $( ".brix-section-inner-content-wrapper", section ).first();

			var box = brix_box( section ),
				standard_subsection = $( ".brix-subsection-type-standard", section ).clone(),
				special_subsections = $( ".brix-subsection-type-special", section ).clone();

			var standard_subsection_html = standard_subsection.html() + "",
				special_subsections_html = [];

			special_subsections.each( function() {
				special_subsections_html.push( $( this ).html() + "" );
			} );

			section.html( "" );

			$.each( layout, function( i ) {
				var type = this.indexOf( "s" ) !== -1 ? "special" : "standard",
					size = this.replace( "s", "" );

				var html = $( $.brixf.template( "brix-js-section", {
					type: type,
					size: size
				} ) );

				if ( type === "special" ) {
					var row_wrapper = $( $.brixf.template( "brix-js-row", {} ) ),
						row_layout = "1/1";

					var panel = $( ".brix-section-row-bar-panel", row_wrapper ).first();

					$( "[data-layout]", panel ).removeClass( "brix-current" );
					$( "[data-layout='" + row_layout  + "']", panel ).addClass( "brix-current" );

					$( ".brix-subsection-rows-wrapper", html ).append( row_wrapper );
					brix_row.change_layout( row_wrapper, [ row_layout ], true );
				}

				section.append( html );
			} );

			// $( ".brix-subsection-type-standard", section ).html( standard_subsection_html );
			$( ".brix-subsection-type-standard", section )[0].innerHTML = standard_subsection_html;

			if ( special_subsections.length ) {
				// Switching from a layout with special sections...
				var special_blocks = $( ".brix-block", special_subsections );

				if ( $( ".brix-subsection-type-special", section ).length ) {
					// ... to a layout also with special sections.
					$.each( special_subsections_html, function( i ) {
						if ( $( ".brix-subsection-type-special", section ).eq( i ).length ) {
							// We've found a matching special section with the same index.
							var sect = $( ".brix-subsection-type-special", section ).eq( i );

							$( ".brix-subsection-rows-wrapper", sect )[0].innerHTML = this;
						}
						else {
							// We haven't found a matching special section with the same index, so where putting everything inside the first special section that we have.
							var blocks = $( ".brix-block", this );

							if ( blocks.length ) {
								var sect = $( ".brix-subsection-type-special", section ).first(),
									special_row = $( ".brix-section-row", sect ).first();

								$( ".brix-section-column-inner-wrapper", special_row ).first()
									.append( blocks );
							}
						}
					} );
				}
				else {
					// ... to a layout without special sections.
					$( ".brix-section-column-inner-wrapper", section ).first()
						.append( special_blocks );
				}
			}

			$( ".brix-section-row", section ).each( function() {
				brix_row.check_layout( $( this ), layout, true );
			} );

			brix_controller.refresh( brix_parent( section, ".brix-section" ) );
		};

		/**
		 * Move a section up.
		 */
		this.move_up = function( section ) {
			var prbrix_section = section.prev( ".brix-section" ),
				box = brix_box( section );

			if ( ! prbrix_section.length ) {
				return;
			}

			section.addClass( "brix-adding" );

			_brix_swap_elements( section[0], prbrix_section[0] );

			setTimeout( function() {
				section.removeClass( "brix-adding" );
				self.adjust( section );
				self.adjust( prbrix_section );

				window.brix_controller.refresh( box );

				window.brix_controller.save_state();
			}, 1 );
		};

		/**
		 * Move a section down.
		 */
		this.move_down = function( section ) {
			var next_section = section.next( ".brix-section" ),
				box = brix_box( section );

			if ( ! next_section.length ) {
				return;
			}

			section.addClass( "brix-adding" );

			_brix_swap_elements( section[0], next_section[0] );

			setTimeout( function() {
				section.removeClass( "brix-adding" );
				self.adjust( section );
				self.adjust( next_section );

				window.brix_controller.refresh( box );

				window.brix_controller.save_state();
			}, 1 );
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			/**
			 * Move up.
			 */
			$( document ).on( "click.brix", ".brix-section-move-up", function() {
				self.move_up( brix_parent( this, ".brix-section" ) );

				return false;
			} );

			/**
			 * Move down.
			 */
			$( document ).on( "click.brix", ".brix-section-move-down", function() {
				self.move_down( brix_parent( this, ".brix-section" ) );

				return false;
			} );

			/**
			 * Duplicate a section.
			 */
			$( document ).on( "click.brix", ".brix-section-duplicate", function() {
				brix_section.duplicate( brix_parent( this, ".brix-section" ) );

				return false;
			} );

			/**
			 * Append a new row to the current section.
			 */
			$( document ).on( "click.brix", ".brix-add-new-row", function() {
				brix_section.add_row( brix_parent( this, ".brix-subsection" ) );

				return false;
			} );

			/**
			 * Remove a section.
			 */
			$( document ).on( "click.brix", ".brix-remove.brix-section-remove", function() {
				brix_section.remove( brix_parent( this, ".brix-section" ) );

				return false;
			} );

			/**
			 * Section data edit.
			 */
			$( document ).on( "click.brix", ".brix-section-edit", function() {
				var section = brix_parent( this, ".brix-section" ),
					data = $.parseJSON( section.attr( "data-data" ) ),
					box = brix_box( section );

				var modal = new BrixBuilderModal(
					"brix_section",
					"brix_section_modal_load",
					data,
					function( data ) {
						if ( data.ev ) {
							delete data.ev;
						}

						if ( data._wp_http_referer ) {
							delete data._wp_http_referer;
						}

						self.setup( section, data );

						section.attr( "data-data", JSON.stringify( data ) );

						window.brix_controller.refresh( box );

						window.brix_controller.save_state();
					}
				);

				return false;
			} );
		};

		/**
		 * Component initialization.
		 */
		this.init = function() {
			this.bind();
		}

		this.init();

	};

} )( jQuery );