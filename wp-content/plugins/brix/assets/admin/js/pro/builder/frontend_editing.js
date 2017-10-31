( function( $ ) {
	"use strict";

	var BrixFrontendEditing = function() {

		var self = this;

		// Editing class applied to the body.
		this.editing_class = "brix-is-frontend-editing";
		this.backend_editing_class = "brix-is-backend-editing";

		// Returns a function, that, as long as it continues to be invoked, will not
		// be triggered. The function will be called after it stops being called for
		// N milliseconds. If `immediate` is passed, trigger the function on the
		// leading edge, instead of the trailing.
		this.debounce = function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		};

		/**
		 * Blanks the iframe.
		 */
		this.close = function() {
			self.$iframe.attr( "src", "" );

			var wrapper = $( ".brix-frontend-editing-iframe-wrapper" );
			wrapper.removeClass( "brix-device-preview" );
		};

		/**
		 * Populate the iframe.
		 */
		this.open = function( callback ) {
			self.close();

			$( ".brix-frontend-editing-responsive li" ).first().trigger( "click" );

			$( ".brix-frontend-editing-primary-toolbar-wrapper .brix-save-builder-template" ).prop( "disabled", $( ".brix-template-actions .brix-save-builder-template" ).prop( "disabled" )  );
			$( ".brix-frontend-editing-primary-toolbar-wrapper .brix-reset-builder" ).prop( "disabled", $( ".brix-template-actions .brix-reset-builder" ).prop( "disabled" )  );

			$( ".brix-section-row-layout-pre-selector .brix-row-remove" ).trigger( "click" );
			$( ".brix-section-row.brix-editing-row .brix-row-layout-close" ).trigger( "click.brix" );

			self.save_preview( function() {
				if ( callback ) {
					callback();
				}

				self.$iframe.attr( "src", self.$iframe.attr( "data-src" ) );
			} );
		};

		/**
		 * Toggle the frontend editing interface.
		 */
		this.toggle = function() {
			var $body = $( "body" );

			if ( $body.hasClass( self.editing_class ) ) {
				self.clear();
				self.close();

				$body.removeClass( self.editing_class );
				$body.addClass( self.backend_editing_class );
			}
			else {
				$body.removeClass( self.backend_editing_class );
				$body.addClass( self.editing_class );

				self.open( function() {
					setTimeout( function() {
						self.$wrapper.removeClass( "brix-frontend-editing-loading" );
					}, 1000 );
				} );
			}

			return false;
		};

		/**
		 * Get the window element of the iframe.
		 */
		this.getIframeWindow = function( iframe_object ) {
			var doc;

			if (iframe_object.contentWindow) {
				return iframe_object.contentWindow;
			}

			if (iframe_object.window) {
				return iframe_object.window;
			}

			if (!doc && iframe_object.contentDocument) {
				doc = iframe_object.contentDocument;
			}

			if (!doc && iframe_object.document) {
				doc = iframe_object.document;
			}

			if (doc && doc.defaultView) {
				return doc.defaultView;
			}

			if (doc && doc.parentWindow) {
				return doc.parentWindow;
			}

			return undefined;
		};

		/**
		 * Move a builder element.
		 */
		this.move_block = function( start_coords, end_coords ) {
			var block = $( ".brix-block" ).eq( start_coords.index ),
				end_ref_col = $( ".brix-section-column-inner-wrapper" ).eq( end_coords.column ),
				end_ref_block = $( ".brix-block", end_ref_col ).eq( end_coords.index );

			if ( end_ref_block.length ) {
				block.insertBefore( end_ref_block );
			}
			else {
				block.appendTo( end_ref_col );
			}
		}

		/**
		 * Update the builder data without forcing a refresh.
		 */
		this.update = function( redraw ) {
			if ( ! $( "body" ).hasClass( self.editing_class ) ) {
				return;
			}

			if ( typeof redraw === "undefined" ) {
				redraw = false;
			}

			window.brix_controller.refresh( $( ".brix-box" ), redraw );

			window.brix_controller.save_state();
		};

		/**
		 * Refresh the iframe.
		 */
		this.refresh = function() {
			if ( ! $( "body" ).hasClass( self.editing_class ) ) {
				return;
			}

			var force = false;

			if ( typeof window.brix_template_loaded !== "undefined" && window.brix_template_loaded ) {
				force = true;

				delete window.brix_template_loaded;
			}
			else if ( typeof window.brix_block_edit !== "undefined" ) {
				var type = $( window.brix_block_edit ).attr( "data-type" ),
					force_types = [
						"tabs",
						"accordion"
					];

				if ( $.inArray( type, force_types ) !== -1 && $( ".brix-block[data-type='" + type + "']" ).length == 1 ) {
					force = true;
				}

				delete window.brix_block_edit;
			}
			else if ( self.$el && self.$el.length && self.$el.hasClass( "brix-section-column" ) ) {
				if ( $( ".brix-is-carousel" ).length === 1 ) {
					force = true;
				}
			}

			self.clear();

			self.save_preview( function() {
				if ( force ) {
					self.$iframe.attr( "src", "" );
					self.$iframe.attr( "src", self.$iframe.attr( "data-src" ) );

					self.$wrapper.removeClass( "brix-frontend-editing-loading" );
				}
				else {
					$.get(
						self.$iframe.attr( "data-src" ),
						{},
						function( html ) {
							var pattern = /<style id="brix-fw-custom-css-css" type="text\/css">(.*)<\/style>/g,
								style = html.split( pattern );

							if ( style[1] ) {
								$( "#brix-fw-custom-css-css", self.$iframe.contents() ).html( style[1] );
							}

							$( ".brix-builder", self.$iframe.contents() ).replaceWith( $( ".brix-builder", html ) );

							self.getIframeWindow( self.$iframe[0] ).brix_ready();

							self.$wrapper.removeClass( "brix-frontend-editing-loading" );
						}
					);
				}
			} );
		};

		/**
		 * Save a temporary version of the page.
		 */
		this.save_preview = function( callback ) {
			self.$wrapper.addClass( "brix-frontend-editing-loading" );

			$.post(
				ajaxurl,
				{
					"data": $( "#post" ).serializeObject(),
					"post_id": self.id,
					"nonce": self.nonce,
					"action": "brix_frontend_editing_save_preview"
				},
				function( data ) {
			  		callback();
				}
			);
		};

		/**
		 * Save a definitive version of the page.
		 */
		this.save = function() {
			self.$wrapper.addClass( "brix-frontend-editing-loading" );

			$.post(
				ajaxurl,
				{
					"data": $( "#post" ).serializeObject(),
					"post_id": self.id,
					"nonce": self.nonce,
					"action": "brix_frontend_editing_save"
				},
				function( data ) {
					setTimeout( function() {
						self.$wrapper.removeClass( "brix-frontend-editing-loading" );
					}, 500 );
				}
			);
		};

		/**
		 * Clear the selection.
		 */
		this.clear = function() {
			self.change_context( "", 0 );

			var iframe_window = self.getIframeWindow( self.$iframe[0] );

			if ( typeof iframe_window !== "undefined" && typeof iframe_window.brix_frontend_editing !== "undefined" && iframe_window.brix_frontend_editing.clear !== "undefined" ) {
				iframe_window.brix_frontend_editing.clear();
			}
		};

		/**
		 * Open bottom panel.
		 */
		this.open_bottom_panel = function( ctn, action ) {
			ctn = $( ctn );

			$( ".brix-frontend-bottom-panel" ).attr( "data-action", action );
			$( ".brix-frontend-bottom-panel-inner-wrapper" ).html( ctn );
			$( ".brix-frontend-editing-iframe-wrapper" ).addClass( "brix-bottom-panel" );
			$( ".brix-frontend-editing-iframe-wrapper" ).attr( "data-action", action );
		};

		/**
		 * Close bottom panel.
		 */
		this.close_bottom_panel = function( clean_sections ) {
			if ( typeof clean_sections === "undefined" ) {
				clean_sections = true;
			}

			$( ".brix-frontend-editing-iframe-wrapper" ).removeClass( "brix-bottom-panel" );

			if ( clean_sections ) {
				// Remove empty temporary sections
				$( ".brix-section-empty .brix-section-remove" ).trigger( "click" );
			}

			// Remove empty temporary rows
			$( ".brix-row-layout-empty .brix-row-remove" ).trigger( "click" );

			$( ".brix-frontend-bottom-panel" ).attr( "data-action", "" );
			$( ".brix-frontend-bottom-panel-inner-wrapper" ).html( "" );
			$( ".brix-frontend-editing-iframe-wrapper" ).attr( "data-action", "" );
			$( ".brix-section-row.brix-editing-row .brix-row-layout-close" ).first().trigger( "click" );
		};

		/**
		 * Add row.
		 */
		this.add_row = function( count ) {
			self.clear();

			var row = $( ".brix-section-row" ).eq( count ),
				section = brix_parent( row, ".brix-section" );

			window.brix_add_row_index = count;

			$( ".brix-add-new-row", section ).trigger( "click" );

			row = row.next();

			var choices = $( ".brix-section-row-layout-pre-selector .brix-section-row-layout-choices", row )[0].outerHTML;

			self.open_bottom_panel( choices, "add-row" );
		};

		/**
		 * Edit row.
		 */
		this.edit_row = function( count ) {
			var row = $( ".brix-section-row" ).eq( count );

			$( ".brix-edit-row", row ).trigger( "click" );

			var ctn = $( ".brix-section-row-layout-wrapper", row )[0].outerHTML;

			self.open_bottom_panel( ctn, "edit-row" );
		};

		/**
		 * Add section.
		 */
		this.add_section = function( count ) {
			self.clear();

			var section = null;

			if ( typeof count !== "undefined" ) {
				section = $( ".brix-section" ).eq( count );

				$( ".brix-add-new-section-inside", section ).trigger( "click" );

				section = section.next();
			}
			else {
				$( ".brix-start .brix-add-new-section" ).first().trigger( "click" );

				section = $( ".brix-section" ).first();
			}

			var pre_selector = $( ".brix-section-row-layout-pre-selector", section )[0].outerHTML;

			self.open_bottom_panel( pre_selector, "add-section" );
		};

		/**
		 * Perform an action that's linked to a specific control.
		 */
		this.do_action = function() {
			var context = $( ".brix-editing-toolbar-wrapper" ).attr( "data-context" ),
				count = $( ".brix-editing-toolbar-wrapper" ).attr( "data-count" ),
				action = $( this ).attr( "data-action" );

			switch ( context ) {
				case 'section':
					if ( action == 'add' ) {
						self.add_section( count );
					}
					else {
						$( ".brix-section-" + action ).eq( count ).trigger( "click.brix" );
					}

					break;
				case 'row':
					if ( action == 'add' ) {
						self.add_row( count );
					}
					else if ( action == 'edit' ) {
						self.edit_row( count );
					}
					else {
						$( ".brix-" + action + "-row" ).eq( count ).trigger( "click" );
					}

					break;
				case 'column':
					$( ".brix-column-" + action ).eq( count ).trigger( "click" );
					break;
				case 'block':
					$( ".brix-block-" + action ).eq( count ).trigger( "click" );
					break;
				default:
					break;
			}

			if ( action == "remove" ) {
				self.clear();
			}
		};

		/**
		 * Close bottom panel.
		 */
		$( ".brix-close-bottom-panel" ).on( "click", function() {
			self.close_bottom_panel();

			return false;
		} );

		/**
		 * Change the selected element.
		 */
		this.change_element = function() {
			var contexts = [ "section", "row", "column", "block" ],
				toolbar_wrapper = $( ".brix-editing-toolbar-wrapper" ),
				context = toolbar_wrapper.attr( "data-context" ),
				count = toolbar_wrapper.attr( "data-count" ),
				selected_context = -1;

			if ( $( this ).parents( ".brix-editing-toolbar-section-wrapper" ).length ) {
				selected_context = 0;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-row-wrapper" ).length ) {
				selected_context = 1;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-column-wrapper" ).length ) {
				selected_context = 2;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-block-wrapper" ).length ) {
				selected_context = 3;
			}

			if ( selected_context === -1 || selected_context >= context ) {
				return false;
			}

			switch ( selected_context ) {
				case 0:
					self.$el = self.$el.parents( ".brix-section" ).first();
					count = $( self.$el ).index( ".brix-section" );
					break;
				case 1:
					self.$el = self.$el.parents( ".brix-section-row" ).first();
					count = $( self.$el ).index( ".brix-section-row" );
					break;
				case 2:
					self.$el = self.$el.parents( ".brix-section-column" ).first();
					count = $( self.$el ).index( ".brix-section-column" );
					break;
				case 3:
					self.$el = self.$el.parents( ".brix-block" ).first();
					count = $( self.$el ).index( ".brix-block" );
					break;
				default:
					self.$el = null;
					count = 0;
					break;
			}

			self.getIframeWindow( self.$iframe[0] ).brix_frontend_editing.select( contexts[selected_context], count );
		};

		/**
		 * Context change.
		 */
		this.change_context = function( context, count, data ) {
			var toolbar_wrapper = $( ".brix-editing-toolbar-wrapper" );

			toolbar_wrapper.attr( "data-context", context );
			toolbar_wrapper.attr( "data-count", count );
			toolbar_wrapper.removeClass( "brix-section-special" );

			if ( data && data.special ) {
				toolbar_wrapper.addClass( "brix-section-special" );
			}

			$( ".brix-editing-toolbar-block-wrapper i" ).html( "" );

			switch ( context ) {
				case "section":
					self.$el = $( ".brix-section" ).eq( count );
					break;
				case "row":
					self.$el = $( ".brix-section-row" ).eq( count );
					break;
				case "column":
					self.$el = $( ".brix-section-column" ).eq( count );
					break;
				case "block":
					self.$el = $( ".brix-block" ).eq( count );

					$( ".brix-editing-toolbar-block-wrapper i" ).html( "(" + $( ".brix-block-type-label", self.$el ).html() + ")" );
					break;
				default:
					self.$el = null;
					break;
			}
		};

		/**
		 * Add a new block.
		 */
		this.add_block = function( count, index ) {
			if ( index === -1 ) {
				index = null;
			}

			window.brix_add_block_index = index;

			$( ".brix-add-block" ).eq( count ).trigger( "click" );

			return false;
		}

		/**
		 * Activate the button to enable live editing.
		 */
		this.activate = function() {
			if ( ! $( "body" ).hasClass( "post-new-php" ) ) {
				$( "button.brix-frontend-editing" ).prop( "disabled", false );
			}
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			$( window ).on( "load", self.activate );

			// Toggle frontend editing.
			$( document ).off( "click.brix", ".brix-frontend-editing" );
			$( document ).on( "click", ".brix-frontend-editing", self.toggle );

			// Close frontend editing.
			self.$close.on( "click", self.toggle );

			// For every update to the Brix data, refresh the iframe.
			$( document ).on( "brix_updated", self.brix_data_selector, self.debounce( function() {
				self.refresh();
			}, 100 ) );

			// Clear the selection.
			self.$clear.on( "click", self.clear );

			// Editing toolbar buttons.
			$( ".brix-editing-btn" ).on( "click", self.do_action );

			// Change the selected element.
			$( ".brix-editing-toolbar-trigger-wrapper" ).on( "click", self.change_element );

			// Responsive breakpoint change.
			$( ".brix-frontend-editing-responsive li" ).on( "click", function() {
				var media = $( "[data-media]", this ).html(),
					pattern = /max-width:\s?(\d+px)/g,
					matches = pattern.exec( media ),
					icn = $( "[data-icn]", this ).html(),
					height = $( this ).attr( 'data-height' ),
					button = $( ".brix-frontend-editing-responsive > i" );

				$( ".brix-frontend-editing-responsive li" ).removeClass( "brix-active" );
				$( this ).addClass( "brix-active" );

				button.html( icn );

				if ( matches && matches[1] ) {
					self.$iframe_wrapper.css( "width", matches[1] );
				}
				else {
					self.$iframe_wrapper.css( "width", "" );
				}

				var wrapper = $( ".brix-frontend-editing-iframe-wrapper" );

				if ( height ) {
					wrapper.addClass( "brix-device-preview" );
					self.$iframe_wrapper.css( "height", height );
				} else {
					wrapper.removeClass( "brix-device-preview" );
					self.$iframe_wrapper.css( "height", "" );
				}

				return false;
			} );

			// Builder reset.
			$( ".brix-frontend-editing-reset" ).on( "click", function() {
				$( ".brix-reset-builder" ).trigger( "click" );

				return false;
			} );

			// Load a master template for the page.
			$( ".brix-frontend-editing-load-template" ).on( "click", function() {
				$( "#brix-templates-manager" ).trigger( "click.brix" );

				return false;
			} );

			// Save the current page as a template.
			$( ".brix-frontend-editing-save-template" ).on( "click", function() {
				$( ".brix-save-builder-template" ).trigger( "click" );

				return false;
			} );

			// Get help button.
			$( ".brix-frontend-editing-help" ).on( "click", function() {
				$( ".brix-frontend-editing-iframe-wrapper" ).addClass( "brix-panel-open" );

				return false;
			} );

			// Close a panel.
			$( ".brix-frontend-editing-panel-close" ).on( "click", function() {
				if ( $( ".brix-frontend-editing-iframe-wrapper" ).hasClass( "brix-panel-open" ) ) {
					$( ".brix-frontend-editing-iframe-wrapper" ).removeClass( "brix-panel-open" );
				}

				return false;
			} );

			// Preview the entire page.
			$( ".brix-frontend-editing-preview" ).on( "click", function() {
				self.clear();

				var wrapper = $( ".brix-frontend-editing-iframe-wrapper" ),
					iframe_body = $( "body", self.$iframe.contents() );

				wrapper.toggleClass( "preview-mode" );

				if ( wrapper.hasClass( "preview-mode" ) ) {
					iframe_body.addClass( "brix-frontend-preview-mode" );
				}
				else {
					iframe_body.removeClass( "brix-frontend-preview-mode" );
				}

				return false;
			} );

			// Add a new row.
			$( document ).on( "click", ".brix-frontend-bottom-panel [data-layout]", function() {
				var layout = $( this ).attr( "data-layout" ),
					panel_action = $( this ).parents( ".brix-frontend-bottom-panel" ).first().attr( "data-action" );

				if ( panel_action == 'edit-row' ) {
					$( ".brix-section-row.brix-editing-row .brix-section-row-layout-choices [data-layout='" + layout + "']" ).trigger( "click" );
				}
				else {
					$( ".brix-box .brix-section-row-layout-pre-selector [data-layout='" + layout + "']" ).first().trigger( "click" );
					self.close_bottom_panel();
				}

				return false;
			} );

			// Use a template.
			$( document ).on( "click", ".brix-frontend-bottom-panel .brix-load-builder-template", function() {
				var is_start = $( ".brix-box.brix-empty" ).length;

				if ( is_start ) {
					$( ".brix-start .brix-load-builder-template" ).trigger( "click" );
				}
				else {
					$( ".brix-box .brix-section-row:not(.brix-editing-row) .brix-section-row-layout-pre-selector .brix-load-builder-template" ).first().trigger( "click" );
				}

				self.close_bottom_panel( false );

				return false;
			} );

			/**
			 * Make sure to remove empty sections on template loading.
			 */
			$( window ).on( "brix_template_loaded", function() {
				// Template loaded marker
				window.brix_template_loaded = true;

				// Remove empty temporary sections
				$( ".brix-section-empty .brix-section-remove" ).trigger( "click" );
			} );

			// Edit row, "Back to layout" panel
			self.link_editing_row_panel_control( ".brix-section-row-back-to-layout", ".brix-section-row-layout-change-wrapper" );

			// Edit row, "Vertical alignment" panel
			self.link_editing_row_panel_control( ".brix-section-row-edit-vertical-alignment", ".brix-section-row-layout-vertical-alignment-wrapper" );

			// Edit row, "Responsive" panel
			self.link_editing_row_panel_control( ".brix-section-row-edit-responsive", ".brix-section-row-layout-responsive-wrapper" );

			/**
			 * Vertical alignment variant.
			 */
			$( document ).on( "mousedown.brix", ".brix-frontend-bottom-panel .brix-vertical-alignment-variant", function() {
				var count = $( this ).index( ".brix-frontend-bottom-panel .brix-vertical-alignment-variant" );

				$( ".brix-section-row.brix-editing-row .brix-vertical-alignment-variant" ).eq( count ).trigger( "mousedown.brix" );

				brix_row.change_vertical_alignment_ui.apply( this );

				return false;
			} );

			/**
			 * Vertical alignment, equal heights column.
			 */
			$( document ).on( "click.brix", ".brix-frontend-bottom-panel .brix-vertical-alignment-equal-heights-label", function() {
				brix_row.change_vertical_alignment_equal_heights_ui.apply( this );

				$( ".brix-section-row.brix-editing-row .brix-vertical-alignment-equal-heights-label" ).first().trigger( "click.brix" );

				return false;
			} );

			/**
			 * Vertical alignment, responsive breakpoint switch.
			 */
			$( document ).on( "change.brix", ".brix-frontend-bottom-panel [data-row-responsive-breakpoint]", function() {
				$( ".brix-section-row.brix-editing-row [data-row-responsive-breakpoint]" ).val( $( this ).val() ).trigger( "change.brix" );

				brix_row.change_responsive_breakpoint_ui.apply( this );

				$( ".brix-section-row.brix-editing-row [data-row-responsive-column]" ).each( function( index ) {
					$( ".brix-frontend-bottom-panel [data-row-responsive-column]" ).eq( index ).val( $( this ).val() ).trigger( "change.brix", false );
				} );

				return false;
			} );

			/**
			 * Vertical alignment, column width switch.
			 */
			$( document ).on( "change.brix", ".brix-frontend-bottom-panel [data-row-responsive-column]", function( e, refresh ) {
				if ( typeof refresh === "undefined" ) {
					refresh = true;
				}

				brix_row.change_responsive_column_ui.apply( this );

				var count = $( this ).index( ".brix-frontend-bottom-panel [data-row-responsive-column]" );

				$( ".brix-section-row.brix-editing-row [data-row-responsive-column]" ).eq( count ).val( $( this ).val() ).trigger( "change.brix", [ refresh ] );

				return false;
			} );

			self.$save_btn.on( "click", function() {
				self.save();

				return false;
			} );
		};

		/**
		 * Bottom panel sub-panels controls.
		 */
		this.link_editing_row_panel_control = function( cls, container ) {
			$( document ).on( "click", ".brix-frontend-bottom-panel " + cls, function() {
				$( ".brix-editing-row.brix-section-row " + cls ).trigger( "click.brix" );

				$( ".brix-frontend-bottom-panel " + container ).html( $( ".brix-editing-row.brix-section-row " + container )[0].innerHTML );

				return false;
			} );
		};

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			self.$el                = null;
			self.$clear             = $( ".brix-editing-clear-btn" );
			self.brix_data_selector = "[data-brix-value]";
			self.$wrapper           = $( ".brix-frontend-editing-iframe-wrapper" );
			self.$iframe_wrapper    = $( ".brix-frontend-editing-iframe", self.$wrapper );
			self.$iframe            = $( "iframe", self.$wrapper );
			self.$close             = $( ".brix-frontend-editing-close" );
			self.nonce              = self.$iframe.attr( "data-nonce" );
			self.id                 = self.$iframe.attr( "data-id" );
			self.$save_btn			= $( ".brix-frontend-editing-save" );

			this.bind();
		};

		this.init();

	};

	window.brix_frontend_editing = new BrixFrontendEditing();

} )( jQuery );