( function( $ ) {
	"use strict";

	window.brix_row = new function() {

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
		 * Duplicate a row.
		 */
		this.duplicate = function( row ) {
			var section = brix_parent( row, ".brix-section" ),
				new_row = row.clone( false );

			new_row
				.insertAfter( row );

			window.brix_controller.refresh( section );

			window.brix_controller.save_state();
		};

		/**
		 * Row setup.
		 */
		this.setup = function( row ) {
			var columns = $( ".brix-section-column", row ),
				can_merge_class = "brix-column-can-merge",
				can_split_class = "brix-column-can-split";

			columns
				.removeClass( can_merge_class )
				.removeClass( can_split_class );

			columns.each( function() {
				var column = $( this );

				if ( brix_column.can_merge( column ) ) {
					column.addClass( can_merge_class );
				}

				if ( brix_column.can_split( column ) ) {
					column.addClass( can_split_class );
				}
			} );
		};

		/**
		 * Remove a row.
		 */
		this.remove = function( row ) {
			var section = brix_parent( row, ".brix-section" ),
				is_backend_editing = $( "body" ).hasClass( "brix-is-backend-editing" ),
				duration = ! is_backend_editing ? 0 : this.config.slide.duration,
				box = brix_box( row ),
				redraw = row.hasClass( "brix-row-layout-empty" ) ? false : true;

			row.slideUp( {
				"duration": duration,
				"easing": this.config.slide.easing,
				"complete": function() {
					row.remove();
					brix_section.adjust( section );

					window.brix_controller.refresh( box, redraw );
				}
			} );
		};

		/**
		 * Change the equalization for the entire row.
		 */
		this.change_row_equalization = function( row, fluid ) {
			var section = brix_parent( row, ".brix-section" ),
				row_data = $.parseJSON( $( row ).attr( "data-data" ) );

			row_data = $.extend( true, {}, row_data );

			if ( ! row_data["_fluid"] ) {
				row_data["_fluid"] = 0;
			}

			row_data["_fluid"] = parseInt( fluid, 10 );

			$( row ).attr( "data-data", JSON.stringify( row_data ) );

			window.brix_controller.refresh( section );
		};

		/**
		 * Change the vertical alignment for a specific column.
		 */
		this.change_vertical_alignment = function( column, alignment ) {
			var column_data = $.parseJSON( column.attr( "data-data" ) ),
				section = brix_parent( column, ".brix-section" );

			column_data = $.extend( true, {}, column_data );

			if ( ! column_data["_vertical_alignment"] ) {
				column_data["_vertical_alignment"] = "";
			}

			column_data["_vertical_alignment"] = alignment;

			column.attr( "data-data", JSON.stringify( column_data ) );

			window.brix_controller.refresh( section );
		};

		/**
		 * Vertical alignment row interface.
		 */
		this.edit_vertical_alignment = function( row ) {
			var columns = $( ".brix-section-column", row ),
				html = "",
				columns_html = "",
				layout = $( ".brix-section-row-layout" );

			layout.removeClass( "brix-responsive-breakpoint-selected" );

			var row_data = $.parseJSON( $( row ).attr( "data-data" ) );
			row_data = $.extend( true, {}, row_data );

			var is_fluid = typeof row_data["_fluid"] !== "undefined" ? row_data["_fluid"] : 0;

			columns.each( function( index ) {
				var data = $.parseJSON( $( this ).attr( "data-data" ) ),
					vertical_alignment = "top",
					size = $( this ).attr( "data-size" ),
					column_class = "brix-col-" + size.replace( "/", "-" );

				if ( typeof data !== "undefined" ) {
					if ( ! data ) {
						data = {};
					}

					if ( data["_vertical_alignment"] ) {
						vertical_alignment = data["_vertical_alignment"];
					}
				}

				columns_html += "<div class='brix-vertical-alignment-column " + column_class + "' data-row-vertical-alignment-column='" + index + "'>";
					columns_html += "<div class='brix-vertical-alignment-column-equalizer'>";
						columns_html += "<span class='brix-vertical-alignment-variant " + ( vertical_alignment === 'top' ? 'brix-current' : '' ) + "' data-variant='top'></span>";
						columns_html += "<span class='brix-vertical-alignment-variant " + ( vertical_alignment === 'middle' ? 'brix-current' : '' ) + "' data-variant='middle'></span>";
						columns_html += "<span class='brix-vertical-alignment-variant " + ( vertical_alignment === 'bottom' ? 'brix-current' : '' ) + "' data-variant='bottom'></span>";
					columns_html += "</div>";
				columns_html += "</div>";
			} );

			html += "<div class='brix-vertical-alignment-equal-heights-select'>";
				html += '<span class="brix-checkbox-wrapper">';
					html += '<input type="checkbox" value="" ' + ( is_fluid ? '' : 'checked' ) + '>';
					html += '<label class="brix-vertical-alignment-equal-heights-label"></label>';
				html += '</span>';
				html += '<label class="brix-vertical-alignment-equal-heights-label">' + brix_i18n_strings.equal_height_columns + '</label>';
			html += "</div>";

			html += "<span>" + brix_i18n_strings.choose_vertical_alignment + "</span>";

			html += columns_html;

			$( ".brix-section-row-layout-vertical-alignment-columns-wrapper", row ).html( html );

			layout.attr( "data-panel", "vertical-alignment" );

			$( ".brix-section-row-inner-wrapper", row ).sortable( "disable" );
		};

		/**
		 * Responsive row interface.
		 */
		this.edit_responsive = function( row ) {
			var columns = $( ".brix-section-column", row ),
				layout = $( ".brix-section-row-layout" );

			var html = "";

			html += "<div class='brix-responsive-breakpoints-select'>";
				html += "<span class='brix-select-wrapper'><select data-row-responsive-breakpoint>";
					html += "<option value=''>" + brix_i18n_strings.responsive_select_breakpoint + "</option>";

					$.each( brix_breakpoints, function( key, breakpoint ) {
						if ( key === "desktop" ) {
							return;
						}

						html += "<option value='" + key + "'>" + breakpoint.label + "</option>";
					} );

				html += "</select></span>";
				html += '<span></span>';
			html += "</div>";

			columns.each( function( index ) {
				var size = $( this ).attr( "data-size" ),
					column_class = "brix-col-" + size.replace( "/", "-" ),
					column_data = $.parseJSON( $( this ).attr( "data-data" ) );

				html += "<div class='brix-responsive-column " + column_class + "'>";
					html += "<span class='brix-responsive-column-select-wrapper'><select data-row-responsive-column='" + index + "'>";
						html += "<option value=''>" + brix_i18n_strings.responsive_inherit + "</option>";
						html += "<option value='1/1'>1/1</option>";
						html += "<option value='1/2'>1/2</option>";
						html += "<option value='1/3'>1/3</option>";
						html += "<option value='2/3'>2/3</option>";
						html += "<option value='1/4'>1/4</option>";
						html += "<option value='3/4'>3/4</option>";
						html += "<option value='hidden'>" + brix_i18n_strings.responsive_hide + "</option>";
					html += "</select><span class='brix-placeholder'></span></span>";
				html += "</div>";
			} );

			$( ".brix-section-row-layout-responsive-columns-wrapper", row ).html( html );

			layout.attr( "data-panel", "responsive" );

			$( "[data-row-responsive-breakpoint]", row ).trigger( "change.brix" );

			$( "[data-row-responsive-column]", row ).each( function() {
				self.change_responsive_column_ui.apply( this );
			} );

			$( ".brix-section-row-inner-wrapper", row ).sortable( "disable" );
		};

		/**
		 * Toggle editing panel.
		 */
		this.toggle_editing_panel = function( row ) {
			var box = brix_box( row ),
				panel = $( ".brix-section-row-layout-wrapper", row ),
				is_backend = $( "body" ).hasClass( "brix-is-backend-editing" ),
				duration = ! is_backend ? 0 : self.config.slide.duration;

			$( ".brix-section-row-show-all-layouts", row ).removeClass( "brix-section-row-show-all-layouts" );
			$( ".brix-section-row-layout" ).attr( "data-panel", "layout-change" );

			if ( ! row.hasClass( "brix-editing-row" ) ) {
				$( ".brix-editing-row", box ).removeClass( "brix-editing-row" );

				$( ".brix-section-row-layout-wrapper", box ).not( panel ).slideUp( {
					"duration": duration,
					"easing": self.config.slide.easing,
					"complete": function() {
						$( this ).parents( ".brix-section-row" ).first().removeClass( "brix-editing-row" );
						row.parents( ".brix-builder").first().removeClass( "brix-editing-row" );
					}
				} );

				panel.slideDown( {
					"duration": duration,
					"easing": self.config.slide.easing,
					"complete": function() {
						row.addClass( "brix-editing-row" );
						row.parents( ".brix-builder").first().addClass( "brix-editing-row" );

						var subsection = brix_parent( row, ".brix-subsection" ),
							limit = brix_section.column_limit( subsection );

						$( ".brix-section-row-layout-change-wrapper" ).attr( "data-size", subsection.attr( "data-size" ) );
						$( ".brix-section-row-layout-choices li", row ).removeClass( "brix-row-layout-hidden" );

						if ( limit < 12 ) {
							for ( var j = limit + 1; j <= 12; j++ ) {
								$( ".brix-section-row-layout-choices [data-layout*='/" + j + "']", row ).each( function() {
									$( this ).parent().addClass( "brix-row-layout-hidden" );
								} );
							}
						}
					}
				} );
			}
			else {
				panel.slideUp( {
					"duration": duration,
					"easing": self.config.slide.easing,
					"complete": function() {
						row.removeClass( "brix-editing-row" );
						row.parents( ".brix-builder").first().removeClass( "brix-editing-row" );
					}
				} );
			}
		};

		/**
		 * Reset editing layout classes.
		 */
		this.reset_editing_layout = function( panel, row ) {
			var builder = brix_parent( row, ".brix-builder" ),
				layout = $( ".brix-section-row-layout" );

			layout.removeClass( "brix-responsive-breakpoint-selected" );
			layout.attr( "data-panel", "layout-change" );

			row.removeClass( "brix-editing-row" );
			builder.removeClass( "brix-editing-row" );
		}

		/**
		 * Close the row layout editing panel.
		 */
		this.close_editing_layout = function( row ) {
			var panel = $( ".brix-section-row-layout-wrapper", row ),
				is_backend = $( "body" ).hasClass( "brix-is-backend-editing" ),
				duration = ! is_backend ? 0 : self.config.slide.duration;

			if ( is_backend ) {
				$( ".brix-section-row-inner-wrapper", row ).sortable( "enable" );

				panel.slideUp( {
					"duration": duration,
					"easing": this.config.slide.easing,
					"complete": function() {
						self.reset_editing_layout( panel, row );

						window.brix_controller.save_state();
					}
				} );
			}
			else {
				panel.hide();
				self.reset_editing_layout( panel, row );

				window.brix_controller.save_state();
			}
		};

		/**
		 * Back to layout editing.
		 */
		this.back_to_layout = function( row ) {
			var layout = $( ".brix-section-row-layout" );

			layout.attr( "data-panel", "layout-change" );

			$( ".brix-section-row-inner-wrapper", row ).sortable( "enable" );
		};

		/**
		 * Check a row's layout validity and adjusts it if needed.
		 */
		this.check_layout = function( row ) {
			row = $( row );

			var layout = [];

			$( ".brix-section-column[data-size]", row ).each( function() {
				layout.push( $( this ).attr( "data-size" ) );
			} );

			brix_row.change_layout( row, layout, true );
		};

		/**
		 * Change a row's layout.
		 */
		this.change_layout = function( row, layout, reset_responsive ) {
			row = $( ".brix-section-row-inner-wrapper", row ).first();

			var columns = $( ".brix-section-column", row ),
				section = brix_parent( row, ".brix-section" ),
				subsection = brix_parent( row, ".brix-subsection" ),
				limit = brix_section.column_limit( subsection ),
				box = brix_box( row );

			if ( layout.length >= limit ) {
				layout = [];

				for ( var n = 0; n < limit; n++ ) {
					layout.push( "1/" + limit );
				}
			}
			else {
				var realign = false;

				$.each( layout, function() {
					var den = parseInt( this.split( "/" )[1], 10 );

					if ( den > limit ) {
						realign = true;

						return false;
					}
				} );

				if ( realign ) {
					var new_limit = layout.length;
					layout = [];

					for ( var n = 0; n < new_limit; n++ ) {
						layout.push( "1/" + new_limit );
					}
				}
			}

			$.each( layout, function( i ) {
				var size = this,
					num = parseInt( size.split( "/" )[0], 10 ),
					den = parseInt( size.split( "/" )[1], 10 );

				var html = brix_column.create_column( num, den );

				if ( columns.length ) {
					$( ".brix-section-column-inner-wrapper", html )
						.append( columns.eq( i ).find( ".brix-block" ) );

					if ( columns.eq( i ).length ) {
						var column_data = $.parseJSON( columns.eq( i ).attr( "data-data" ) ),
							column_size = columns.eq( i ).attr( "data-size" );

						if ( reset_responsive && column_data && column_data["_responsive"] ) {
							if ( this != column_size ) {
								delete column_data["_responsive"];
							}
						}

						html.attr( "data-data", JSON.stringify( column_data ) );

						brix_column.setup( html, column_data );
					}

					if ( i === layout.length - 1 && layout.length < columns.length ) {
						for ( var j = 0; j < columns.length - layout.length; j++ ) {
							var index = j + layout.length;

							$( ".brix-section-column-inner-wrapper", html )
								.append( columns.eq( index ).find( ".brix-block" ) );
						}
					}
				}

				$( ".brix-section-row-layout-pre-selector", row ).remove();
				row.append( html );
			} );

			layout = layout.join( " " );

			var row_wrapper = row.parents( ".brix-section-row" ).first(),
				panel = $( ".brix-section-row-bar-panel", row_wrapper ).first();

			$( "[data-layout]", panel ).removeClass( "brix-current" );
			$( "[data-layout='" + layout  + "']", panel ).addClass( "brix-current" );

			columns.remove();

			brix_controller.refresh( section );

			var is_editing_row = row.parents( ".brix-editing-row" ).length;

			if ( ! is_editing_row ) {
				window.brix_controller.save_state();
			}
		};

		/**
		 * Visually select the vertical alignment for a column.
		 */
		this.change_vertical_alignment_ui = function() {
			$( this ).siblings().removeClass( "brix-current" );
			$( this ).addClass( "brix-current" );
		};

		/**
		 * Visually select the columns heights.
		 */
		this.change_vertical_alignment_equal_heights_ui = function() {
			var select = brix_parent( this, ".brix-vertical-alignment-equal-heights-select" ),
				checkbox = $( "input", select );

			if ( checkbox[0].checked ) {
				checkbox.prop( "checked", false );
			}
			else {
				checkbox.prop( "checked", true );
			}
		};

		/**
		 * Visually change the responsive width of a column.
		 */
		this.change_responsive_column_ui = function() {
			var size = $( this ).val(),
				wrapper = $( this ).parent(),
				placeholder = $( ".brix-placeholder", wrapper );

			$( this ).attr( "data-value", size );

			if ( size ) {
				wrapper.removeClass( "brix-inherit" );
			}
			else {
				wrapper.addClass( "brix-inherit" );
			}

			placeholder.html( size );
		};

		/**
		 * Change the responsive breakpoint.
		 */
		this.change_responsive_breakpoint_ui = function() {
			var layout = $( this ).parents( ".brix-section-row-layout" ),
				media = $( this ).parent().next(),
				breakpoint = $( this ).val();

			layout.removeClass( "brix-responsive-breakpoint-selected" );

			if ( $( this ).val() !== '' ) {
				layout.addClass( "brix-responsive-breakpoint-selected" );
			}

			if ( breakpoint && brix_breakpoints[breakpoint].media_query ) {
				media.html( brix_breakpoints[breakpoint].media_query );
			}
			else {
				media.html( "" );
			}
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			$( document ).on( "click.brix", ".brix-section-row .brix-vertical-alignment-equal-heights-label", function() {
				var select = brix_parent( this, ".brix-vertical-alignment-equal-heights-select" ),
					checkbox = $( "input", select );

				self.change_vertical_alignment_equal_heights_ui.apply( this );

				checkbox.trigger( "change.brix" );

				return false;
			} );

			/**
			 * Clone a row.
			 */
			$( document ).on( "click.brix", ".brix-clone-row", function() {
				brix_row.duplicate( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Show all the available layouts for a row.
			 */
			$( document ).on( "click.brix", ".brix-section-row-layout-more", function() {
				var choices = brix_parent( this, ".brix-section-row-layout-choices" );

				choices.toggleClass( "brix-section-row-show-all-layouts" );

				return false;
			} );

			/**
			 * Remove a row.
			 */
			$( document ).on( "click.brix", ".brix-remove.brix-row-remove", function() {
				brix_row.remove( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Toggle the row editing panel.
			 */
			$( document ).on( "click.brix", ".brix-edit-row", function() {
				brix_row.toggle_editing_panel( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Change the row layout.
			 */
			$( document ).on( "click.brix", ".brix-section-row [data-layout]", function() {
				var layout = $( this ).attr( "data-layout" ).split( " " );

				brix_row.change_layout( brix_parent( this, ".brix-section-row" ), layout, true );

				return false;
			} );

			/**
			 * Cancel row layout editing.
			 */
			$( document ).on( "click.brix", ".brix-row-layout-close", function() {
				brix_row.close_editing_layout( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Toggle responsive mode for the row layout.
			 */
			$( document ).on( "click.brix", ".brix-section-row .brix-section-row-edit-responsive", function() {
				brix_row.edit_responsive( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Change a column width on responsive.
			 */
			$( document ).on( "change.brix", ".brix-section-row [data-row-responsive-breakpoint]", function() {
				var breakpoint = $( this ).val(),
					row = $( this ).parents( ".brix-section-row" ).first(),
					wrapper = $( this ).parents( ".brix-section-row-layout-responsive-wrapper" ).first(),
					columns = $( ".brix-section-column", row );

				self.change_responsive_breakpoint_ui.apply( this );

				if ( ! breakpoint ) {

					return false;
				}

				$( "[data-row-responsive-column]", wrapper ).each( function( index ) {
					var column_data = $.parseJSON( columns.eq( index ).attr( "data-data" ) );

					if ( column_data && column_data["_responsive"] && column_data["_responsive"][breakpoint] ) {
						$( this ).val( column_data["_responsive"][breakpoint]["size"] );
					}
					else {
						$( this ).val( "" );
					}

					self.change_responsive_column_ui.apply( this );
				} );

				return false;
			} );

			/**
			 * Toggle vertical alignment mode for the row layout.
			 */
			$( document ).on( "click.brix", ".brix-section-row .brix-section-row-edit-vertical-alignment", function() {
				brix_row.edit_vertical_alignment( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Change the vertical alignment for a specific column.
			 */
			$( document ).on( "mousedown.brix", ".brix-section-row .brix-vertical-alignment-variant", function() {
				var variant = $( this ).attr( "data-variant" ),
					row = brix_parent( this, ".brix-section-row" ),
					columns = $( ".brix-section-column", row ),
					va_column = brix_parent( this, "[data-row-vertical-alignment-column]" ),
					index = parseInt( va_column.attr( "data-row-vertical-alignment-column" ), 10 );

				brix_row.change_vertical_alignment( columns.eq( index ), variant );
				self.change_vertical_alignment_ui.apply( this );

				return false;
			} );

			/**
			 * Switch back to editing the row layout.
			 */
			$( document ).on( "click.brix", ".brix-section-row .brix-section-row-back-to-layout", function() {
				brix_row.back_to_layout( brix_parent( this, ".brix-section-row" ) );

				return false;
			} );

			/**
			 * Change the equalization for the entire row.
			 */
			$( document ).on( "change.brix", ".brix-section-row .brix-vertical-alignment-equal-heights-select input", function() {
				var fluid = ( ! $( this ).prop( "checked" ) ) + false,
					row = brix_parent( this, ".brix-section-row" );

				brix_row.change_row_equalization( row, fluid );

				return false;
			} );

			/**
			 * Change the responsive layout for columns belonging to a specific row.
			 */
			$( document ).on( "change.brix", ".brix-section-row [data-row-responsive-column]", function( e, refresh ) {
				var size = $( this ).val(),
					index = parseInt( $( this ).attr( "data-row-responsive-column" ), 10 ),
					row = brix_parent( this, ".brix-section-row" ),
					section = brix_parent( row, ".brix-section" ),
					breakpoint = $( "[data-row-responsive-breakpoint]", row ).val();

				self.change_responsive_column_ui.apply( this );

				var column_data = $.parseJSON( $( ".brix-section-column", row ).eq( index ).attr( "data-data" ) );

				column_data = $.extend( true, {}, column_data );

				if ( ! column_data["_responsive"] ) {
					column_data["_responsive"] = {};
				}

				column_data["_responsive"][breakpoint] = {
					"size": size
				};

				$( ".brix-section-column", row ).eq( index ).attr( "data-data", JSON.stringify( column_data ) );

				if ( refresh ) {
					window.brix_controller.refresh( section );
				}

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