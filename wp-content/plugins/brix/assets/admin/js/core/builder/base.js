( function( $ ) {
	"use strict";

	/**
	 * Builder controller.
	 */
	$.brix = function() {

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
		 * History.
		 */
		self.history = [];
		self.history_index = 0;
		self.history_limit = 20 + 1;

		/**
		 * Builder update event.
		 */
		self.event = document.createEvent( "Event" );

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			this.bind();

			self.event.initEvent( "input", true, true);

			/**
			 * Boot the block UI.
			 */
			$.brixf.ui.add( ".brix-box", function() {
				self.refresh( this );
			} );

			/**
			 * Save initial history state.
			 */
			$( window ).on( "brix_ready", self.save_state );
		};

		/**
		 * Fix toolbar scrolling.
		 */
		this.fix_toolbar_scrolling = function() {
			if ( ! $( "body" ).hasClass( "brix-using-builder" ) ) {
				return false;
			}

			var toolbar = $( ".brix-template-actions" );

			if ( ! toolbar.length ) {
				return;
			}

			var toolbar_container = $( '.brix-templates' ),
				offset = toolbar_container.offset().top,
				scroll = $( window ).scrollTop(),
				adminbar = $( "#wpadminbar" ).length ? $( "#wpadminbar" ).outerHeight() : 0;

			toolbar.css( { 'width': '', 'left': '' } );

			if ( offset - scroll <= adminbar ) {
				toolbar.addClass( 'fixed' );
				toolbar.css({ 'width': toolbar_container.outerWidth(), 'left': toolbar_container.offset().left });
				toolbar_container.css({ 'padding-top': toolbar.outerHeight() });
			} else {
				toolbar.removeClass( 'fixed' );
				toolbar_container.css({ 'padding-top': '' });
			}
		}

		/**
		 * Adjust history body classes.
		 */
		this.adjust_history_buttons = function() {
			var undo_btn = $( ".brix-undo-btn" ),
				redo_btn = $( ".brix-redo-btn" );

			undo_btn.prop( "disabled", true );
			redo_btn.prop( "disabled", true );

			if ( self.history_index > 0 ) {
				undo_btn.prop( "disabled", false );
			}

			if ( self.history_index < self.history.length - 1 ) {
				redo_btn.prop( "disabled", false );
			}
		};

		/**
		 * Save the current builder state.
		 */
		this.save_state = function() {
			var box = $( ".brix-box" ),
				html = box.html(),
				val = $.extend( true, {}, box.data( "brix" ) );

			if ( self.history.length ) {
				var previous_val = self.history[self.history_index].val;

				if ( JSON.stringify( previous_val ) != JSON.stringify( val ) ) {
					if ( self.history_index < self.history_limit - 1 ) {
						self.history.splice( self.history_index + 1, self.history.length );
						self.history_index++;
					}
					else {
						self.history.splice( 0, 1 );
						self.history_index = self.history_limit - 1;
					}

					self.history[self.history_index] = {
						html: html,
						val: val
					};
				}
			}
			else {
				self.history[self.history_index] = {
					html: html,
					val: val
				};
			}

			self.adjust_history_buttons();
		};

		/**
		 * Load a particular builder state.
		 */
		this.load_state = function( box, index ) {
			var state = null;

			if ( self.history[index] ) {
				state = self.history[index];
			}

			if ( state ) {
				box.html( state.html );

				this.refresh( box );

				self.history_index = index;
			}

			this.adjust_history_buttons();
		};

		/**
		 * Undo.
		 */
		this.undo = function( box ) {
			if ( self.history_index >= 1 ) {
				self.load_state( box, self.history_index - 1 );
			}
		};

		/**
		 * Redo.
		 */
		this.redo = function( box ) {
			if ( self.history_index < self.history.length ) {
				self.load_state( box, self.history_index + 1 );
			}
		};

		/**
		 * Append a section.
		 */
		this.append_section = function( new_section, context, mode ) {
			var is_backend_editing = $( "body" ).hasClass( "brix-is-backend-editing" ),
				box = null;

			if ( is_backend_editing ) {
				new_section.addClass( "brix-adding" );
			}

			if ( context.is( ".brix-builder" ) ) {
				if ( mode === "prepend" ) {
					var control = $( "> .brix-add-new-section-inside", context ).first();

					control.after( new_section );
				}
				else {
					context.append( new_section );
				}
			}
			else {
				context.after( new_section );
			}

			box = brix_parent( new_section, ".brix-box" );
			brix_section.adjust( new_section );

			$( ".brix-save-builder-template", box ).removeAttr( "disabled" );

			if ( is_backend_editing ) {
				new_section.removeClass( "brix-adding" );
			}

			if ( is_backend_editing ) {
				window.brix_controller.refresh( box );
			}
			else {
				window.brix_controller.refresh( box, false );
			}
		};

		/**
		 * Add a section.
		 */
		this.add_section = function( ctrl ) {
			var html = $( $.brixf.template( "brix-section", {} ) ),
				context = null;

			if ( $( ctrl ).parents( ".brix-section" ).length ) {
				context = $( ctrl ).parents( ".brix-section" ).first();
			}
			else {
				var box = brix_box( ctrl ),
					mode = $( ctrl ).is( ".brix-add-new-section-inside" ) ? "prepend" : "append";

				context = $( ctrl ).parents( ".brix-builder" );
			}

			self.append_section( html, context, mode );
		};

		/**
		 * Delete a builder layout.
		 */
		this.deleteBuilderTemplate = function( id, callback, nonce ) {
			if ( id == "" ) {
				return;
			}

			$.post(
				ajaxurl,
				{
					"action": "brix_delete_template",
					"template_id": id,
					"nonce": nonce
				},
				function( response ) {
					if ( response.trim() == "1" ) {
						if ( callback ) {
							callback();
						}
					}
				}
			);
		};

		/**
		 * Change the template for a specific section.
		 */
		this.changeSectionTemplate = function( id, callback, nonce, section ) {
			var origin = ".brix-modal-container[data-key='brix-load-builder-template']";

			$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

			$.post(
				ajaxurl,
				{
					"action": "brix_load_template",
					"id": id,
					"nonce": nonce
				},
				function( response ) {
					var box = section.parents( ".brix-box" ).first();

					var new_section = $( response );

					$( section ).after( new_section );
					section.remove();

					$( window ).trigger( "brix_template_loaded" );
					self.refresh( box );

					if ( callback ) {
						$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
						callback();
					}
				}
			);
		};

		/**
		 * Change the current builder layout to a different template.
		 */
		this.changeBuilderTemplate = function( id, callback, nonce, box ) {
			var origin = ".brix-modal-container[data-key='brix-load-builder-template']";

			$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

			$.post(
				ajaxurl,
				{
					"action": "brix_load_template",
					"id": id,
					"nonce": nonce
				},
				function( response ) {
					$( ".brix-section" ).remove();
					$( response ).appendTo( $( ".brix-builder" ) );

					$( window ).trigger( "brix_template_loaded" );
					self.refresh( box );

					if ( callback ) {
						$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
						callback();
					}
				}
			);
		};

		/**
		 * Save the current builder layout in a template.
		 */
		this.saveBuilderTemplate = function( template_name, nonce, box, section_index ) {
			self.refresh( box );

			var data = $( "[data-brix-value]", box ).first().val(),
				json_data = $.parseJSON( data );

			if ( section_index > -1 ) {
				data = JSON.stringify( [ json_data[section_index] ] );
			}

			$( ".brix-box .brix-templates" ).addClass( "brix-loading" );

			var is_section = section_index > -1 ? 1 : 0;

			$.post(
				ajaxurl,
				{
					"action": "brix_save_template",
					"template_name": template_name,
					"data": data,
					"nonce": nonce,
					"section": is_section
				},
				function( response ) {
					// $( ".brix-load-builder-template", box ).removeAttr( "disabled" );
					$( ".brix-box .brix-load-builder-template" ).removeAttr( "disabled" );
					$( ".brix-box .brix-templates" ).removeClass( "brix-loading" );

					$( "body" ).addClass( "brix-has-templates" );
					$( "body" ).addClass( "brix-has-user-templates" );
				}
			);
		};

		/**
		 * Update the builder data.
		 */
		this.update = function( context, redraw ) {
			var brix_updated = [],
				is_box = context.is( ".brix-box" );

			if ( typeof redraw === "undefined" ) {
				redraw = true;
			}

			var sections = is_box ? $( ".brix-section", context ) : context;

			sections.each( function() {
				var section = {
					"data": {
						"data": $.parseJSON( $( this ).attr( "data-data" ) ),
						"layout": []
					}
				};

				$( ".brix-subsection", $( this ) ).each( function() {
					var subsection = {
						"size": $( this ).attr( "data-size" ),
						"type": $( this ).attr( "data-type" ),
						"rows": []
					};

					$( ".brix-section-row", this ).each( function() {
						var row = {
							"columns": [],
							"data": $.parseJSON( $( this ).attr( "data-data" ) )
						};

						$( ".brix-section-column", this ).each( function() {
							var column = {
								"data": $.parseJSON( $( this ).attr( "data-data" ) ),
								"size": $( this ).attr( "data-size" ),
								"blocks": []
							};

							$( ".brix-block", this ).each( function() {
								var block_data_string = $( this ).attr( "data-data" ),
									block_data = $.parseJSON( block_data_string );

								var block = {
									"data": block_data,
								};

								column.blocks.push( block );
							} );

							row.columns.push( column );
						} );

						if ( row.columns.length ) {
							subsection.rows.push( row );
						}
					} );

					section.data.layout.push( subsection );
				} );

				brix_updated.push( section );
			} );

			var box = is_box ? context : brix_box( context ),
				box_data = box.data( "brix" );

			if ( is_box ) {
				box_data = brix_updated;
			}
			else {
				var box_sections = $( ".brix-section", box ),
					section_index = context.index( box_sections );

				if ( ! box_data ) {
					box_data = {};
				}

				box_sections.each( function( i ) {
					if ( this === context.get( 0 ) ) {
						section_index = i;
						return false;
					}
				} );

				if ( section_index <= 0 ) {
					section_index = 0;
				}

				box_data[section_index] = brix_updated[0];
			}

			box.data( "brix", box_data );
			$( "[data-brix-value]", box ).attr( "value", JSON.stringify( box_data ) );

			brix_controller.updateContent( box );

			if ( redraw ) {
				$( "[data-brix-value]", box ).trigger( "brix_updated" );

				// self.save_state();
			}
		};

		/**
		 * Adjust builder components, such as rows.
		 */
		this.adjust = function( context ) {
			var empty_class = "brix-empty",
				is_box = context.is( ".brix-box" );

			if ( is_box ) {
				var frontend_save = $( ".brix-frontend-editing-save-template" ),
					frontend_reset = $( ".brix-frontend-editing-reset" );

				if ( ! $( ".brix-section", context ).length ) {
					context.addClass( empty_class );

					$( ".brix-save-builder-template" ).attr( "disabled", "disabled" );
					$( ".brix-reset-builder" ).attr( "disabled", "disabled" );
				}
				else {
					context.removeClass( empty_class );

					$( ".brix-save-builder-template" ).removeAttr( "disabled" );
					$( ".brix-reset-builder" ).removeAttr( "disabled" );
				}
			}

			/* Adjust sections */
			var sections = is_box ? $( ".brix-section", context ) : context;

			sections.each( function() {
				brix_section.adjust( $( this ) );

				if ( ! $( ".brix-section-row", this ).length ) {
					$( this ).addClass( empty_class );
				}
				else {
					$( this ).removeClass( empty_class );
				}
			} );

			/* Adjust columns */
			$( ".brix-section-column", context ).each( function() {
				if ( ! $( ".brix-block", this ).length ) {
					$( this ).addClass( empty_class );
				}
				else {
					$( this ).removeClass( empty_class );
				}
			} );

			$( ".brix-section-row", context ).each( function() {
				brix_row.setup( $( this ) );
			} );

			self.fix_toolbar_scrolling();
		};

		/**
		 * Sorting.
		 */
		this.sortables = function() {
			BrixBuilderResizableColumn();

			/**
			 * Sections sorting.
			 */
			// BrixBuilderSortable(
			// 	".brix-builder",
			// 	".brix-section",
			// 	false,
			// 	null,
			// 	{ top: 25, left: 10 }
			// );

			/**
			 * Rows sorting.
			 */
			BrixBuilderSortable(
				".brix-subsection-type-standard .brix-subsection-rows-wrapper",
				".brix-section-row",
				".brix-row-sortable-handle",
				".brix-subsection-type-standard .brix-subsection-rows-wrapper",
				{ top: 25, right: 10 }
			);

			/**
			 * Column sorting.
			 */
			BrixBuilderSortable(
				".brix-section-row-inner-wrapper",
				".brix-section-column:not( .brix-col-1-1 )",
				false,
				null,
				{ top: 25, left: 100 }
			);

			/**
			 * Blocks sorting.
			 */
			BrixBuilderSortable(
				".brix-section-column-inner-wrapper",
				".brix-block",
				false,
				".brix-section-column-inner-wrapper",
				{ top: 25, left: 100 }
			);
		};

		/**
		 * Update the content editor textarea.
		 */
		this.updateContent = function( box ) {
			if ( brix_env.use_builder == 0 ) {
				return;
			}

			var new_content = [];

			$( ".brix-block", box ).each( function() {
				if ( $( this ).attr( "data-stringified" ) ) {
					new_content.push( $( this ).attr( "data-stringified" ) );
				}
			} );

			new_content = new_content.join( "<br><br>" );
			new_content = new_content.replace( /\n/g, '<br>' );

			var mode = $( "#wp-content-wrap" ).hasClass( "html-active" ) ? "html" : "rich";

			if ( mode === "html" ) {
				$( "#content" ).val( new_content );
			}
			else {
				if ( typeof tinymce !== "undefined" && tinymce && tinymce.get( "content" ) ) {
					tinymce.get( "content" ).setContent( new_content );
				}
			}

			$( "#content" ).get( 0 ).dispatchEvent( self.event );
		};

		/**
		 * Bind sortables and adjustments to the builder interface, and updates
		 * the builder data.
		 */
		this.refresh = function( context, redraw ) {
			context = $( context );

			if ( typeof redraw === "undefined" ) {
				redraw = true;
			}

			this.adjust( context );
			this.sortables( context );
			this.update( context, redraw );
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			/**
			 * Undo.
			 */
			$( document ).on( "click.brix", ".brix-undo-btn", function() {
				var box = $( ".brix-box" );

				self.undo( box );

				return false;
			} );

			/**
			 * Redo.
			 */
			$( document ).on( "click.brix", ".brix-redo-btn", function() {
				var box = $( ".brix-box" );

				self.redo( box );

				return false;
			} );

			/**
			 * When saving the post, make sure to sync and save the builder data.
			 */
			$( "#post" ).on( "submit.brix", function() {
				$( ".brix-box" ).each( function() {
					var input = $( "[data-brix-value]", this );

					window.brix_controller.refresh( this );

					input.val( JSON.stringify( $( this ).data( "brix" ) ) );
				} );
			} );

			/**
			 * Empty builder contents.
			 */
			$( document ).on( "click.brix", ".brix-reset-builder", function() {
				if ( ! window.confirm( brix_i18n_strings.confirm_reset ) ) {
					return false;
				}

				var box = $( ".brix-box" ),
					sections = $( ".brix-section", box );

				$( ".brix-editing-row", box ).removeClass( "brix-editing-row" );

				sections.remove();
				self.refresh( box );

				return false;
			} );

			/**
			 * Builder full-screen.
			 */
			$( document ).on( "click.brix", ".brix-full-screen-builder", function() {
				$( "body" ).toggleClass( "brix-full-screen" );

				if ( ! $( "body" ).hasClass( "brix-full-screen" ) ) {
					self.fix_toolbar_scrolling();
				}

				return false;
			} );

			/**
			 * Back to Editor.
			 */
			$( document ).on( "click.brix", "#brix-back-to-editor", function() {
				var using_builder = $( "body" ).hasClass( "brix-using-builder" );

				if ( ! using_builder ) {
					return false;
				}

				brix_env.use_builder = 0;
				$( "body" ).removeClass( "brix-using-builder" );
				$( "#brix-used" ).val( brix_env.use_builder );

				return false;
			} );

			/**
			 * Use builder.
			 */
			$( document ).on( "click.brix", "#brix-use-builder", function() {
				var ctrl = $( this ),
					using_builder = $( "body" ).hasClass( "brix-using-builder" ),
					loading_builder = false;

				if ( using_builder ) {
					return false;
				}
				else {
					if ( ! loading_builder ) {
						var box = $( ".brix-box", $( "#brix-builder" )  ),
							builder_val = $.parseJSON( $( "[data-brix-value]", box ).val() );

						var mode = $( "#wp-content-wrap" ).hasClass( "html-active" ) ? "html" : "rich",
							content = "";

						if ( mode === "html" ) {
							content = $( "#content" ).val();
						}
						else {
							if ( typeof tinymce !== "undefined" && tinymce && tinymce.get( "content" ) ) {
								content = tinymce.get( "content" ).getContent();
							}
						}

						if ( ! builder_val.length && content != "" ) {
							var btn = $( this );

							loading_builder = true;

							var nonce = $( this ).attr( "data-nonce" );

							$.post(
								ajaxurl,
								{
									"action": "brix_load_blank_template",
									"content": content,
									"nonce": nonce
								},
								function( html ) {
									if ( html ) {
										html = $( html );

										self.append_section( html, $( ".brix-builder", box ) );
									}
									else {
										self.refresh( box );
									}

									brix_env.use_builder = 1;
									$( "#brix-used" ).val( brix_env.use_builder );
									$( "body" ).addClass( "brix-using-builder" );

									loading_builder = false;
								}
							);
						}
						else {
							brix_env.use_builder = 1;
							$( "#brix-used" ).val( brix_env.use_builder );
							$( "body" ).addClass( "brix-using-builder" );

							self.refresh( box );
						}
					}
				}

				return false;
			} );

			/**
			 * Append a new section.
			 */
			$( document ).on( "click.brix", ".brix-add-new-section", function() {
				self.add_section( this );

				return false;
			} );

			/**
			 * Builder structure.
			 */
			$( window ).on( "resize.brix", function() {
				$( ".brix-box" ).each( function() {
					self.fix_toolbar_scrolling();
					self.adjust( $( this ) );
				} );
			} );

			$( window ).on( 'scroll.brix', function() {
				$( ".brix-box" ).each( function() {
					self.fix_toolbar_scrolling();
				} );
			});
		}

		this.init();

	};

	window.brix_controller = new $.brix();

} )( jQuery );