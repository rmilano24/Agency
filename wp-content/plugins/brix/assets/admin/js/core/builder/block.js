( function( $ ) {
	"use strict";

	window.brix_block = new function() {

		var self = this;

		/**
		 * Duplicate a block.
		 */
		this.duplicate = function( block ) {
			var section = brix_parent( block, ".brix-section" ),
				new_block = block.clone( false );

			new_block
				.insertAfter( block );

			window.brix_controller.refresh( section );

			window.brix_controller.save_state();
		};

		/**
		 * Remove a block.
		 */
		this.remove = function( block ) {
			var section = brix_parent( block, ".brix-section" );

			block.remove();
			window.brix_controller.refresh( section );

			window.brix_controller.save_state();
		};

		/**
		 * Edit a block.
		 */
		this.edit = function( block ) {
			if ( block.hasClass( "brix-block-invalid" ) ) {
				return false;
			}

			window.brix_block_wrapper = brix_parent( block, ".brix-section-column-inner-wrapper" );

			var type = block.attr( "data-type" ),
				data = $.parseJSON( block.attr( "data-data" ) );

			_brix_block_edit( type, data, block );
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			/**
			 * Remove a block.
			 */
			$( document ).on( "click.brix", ".brix-remove.brix-block-remove", function() {
				self.remove( brix_parent( this, ".brix-block" ) );

				return false;
			} );

			/**
			 * After having selected the block type, open its creation modal window.
			 */
			$( document ).on( "click.brix", ".brix-select-block", function() {
				_brix_block_add_modal_open( $( this ).attr( "data-type" ) );

				return false;
			} );

			/**
			 * Revert the block type selection.
			 */
			$( document ).on( "click.brix", ".brix-block-back", function() {
				_brix_blocks_add_modal();

				return false;
			} );

			/**
			 * Edit a block.
			 */
			$( document ).on( "click.brix", ".brix-block-edit", function() {
				self.edit( brix_parent( this, ".brix-block" ) );

				return false;
			} );

			/**
			 * Filter blocks by clicking on their group.
			 */
			$.brixf.delegate( ".brix-modal-blocks-groups li", "click", "brix", function() {
				$( ".brix-modal-blocks-groups li" ).removeClass( "brix-active" );
				$( this ).addClass( "brix-active" );

				var group = $( this ).attr( "data-group" ),
					modal = $( this ).parents( ".brix-modal" ).first();

				$( ".brix-blocks-selection-wrapper", modal ).removeClass( "brix-active" );
				$( ".brix-blocks-selection-wrapper[data-group='" + group + "']", modal ).addClass( "brix-active" );

				if ( $( ".brix-blocks-selection-wrapper[data-group='" + group + "'] .brix-blocks-selection", modal ).data( "masonry" ) ) {
					$( ".brix-blocks-selection-wrapper[data-group='" + group + "'] .brix-blocks-selection", modal ).masonry();
				}

				if ( $( this ).attr( "data-all" ) == "1" ) {
					$( ".brix-modal-blocks-search > input" ).focus();
				}

				return false;
			} );

			/**
			 * Filter the available builder blocks.
			 */
			$.brixf.delegate( ".brix-modal-blocks-search > input", "keyup", "brix", function() {
				var wrapper = $( this ).parents( ".brix-blocks-selection-wrapper" ).first(),
					blocks = $( ".brix-blocks-selection li", wrapper ),
					search = $( this ).val().toLowerCase(),
					found_class = "brix-found";

				if ( search !== "" ) {
					blocks.removeClass( found_class );
					blocks = blocks.filter( function() {
						return $( this ).text().toLowerCase().indexOf( search ) !== -1;
					} ).addClass( found_class );
				}
				else {
					blocks.addClass( found_class );
				}

				$( ".brix-blocks-selection", wrapper ).masonry( 'destroy' );
				$( ".brix-blocks-selection", wrapper ).masonry( {
					itemSelector: "." + found_class
				} );

				return false;
			} );

			/**
			 * Duplicate a content block.
			 */
			$( document ).on( "click.brix", ".brix-block-duplicate", function() {
				self.duplicate( brix_parent( this, ".brix-block" ) );

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

	/**
	 * Open the blocks selection modal.
	 */
	window._brix_blocks_add_modal = function() {
		_brix_block_selection_modal_open();
	};

	/**
	 * Close the block selection modal.
	 */
	var _brix_block_selection_modal_close = function() {
		if ( window.brix_blocks_add_modal ) {
			window.brix_blocks_add_modal.close();
			delete window.brix_blocks_add_modal;
		}
	};

	/**
	 * Open the block selection modal.
	 */
	var _brix_block_selection_modal_open = function() {
		_brix_block_add_modal_close();

		window.brix_blocks_add_modal = new BrixBuilderModal(
			"brix_blocks",
			"brix_blocks_modal_load",
			{},
			function( data ) {},
			function() {
				$( ".brix-blocks-selection" ).masonry( {
					itemSelector: ".brix-found",
					transitionDuration: 0
				} );
			}
		);
	};

	/**
	 * Close the block modal to add a block to a column.
	 */
	var _brix_block_add_modal_close = function() {
		if ( window.brix_block_add_modal ) {
			window.brix_block_add_modal.close();
			delete window.brix_block_add_modal;
		}
	};

	/**
	 * Open the block modal in order to add it to a column.
	 */
	var _brix_block_add_modal_open = function( type ) {
		if ( window.brix_blocks_add_modal ) {
			_brix_block_selection_modal_close();

			_brix_block_edit( type );
		}
	};

	/**
	 * Block filter data.
	 */
	function brix_block_filter_data( data, type ) {
		switch ( type ) {
			case "widget-rss":
				if ( typeof data.instance['show_summary'] === "undefined" ) {
					data.instance['show_summary'] = '0';
				}

				if ( typeof data.instance['show_author'] === "undefined" ) {
					data.instance['show_author'] = '0';
				}

				if ( typeof data.instance['show_date'] === "undefined" ) {
					data.instance['show_date'] = '0';
				}
				break;
			case "widget-archives":
				data.instance.count = data.instance.count == 'on' ? '1': '0';
				data.instance.dropdown = data.instance.dropdown == 'on' ? '1': '0';
				break;
			default:
				break;
		}

		return data;
	}

	/**
	 * Block editing modal.
	 */
	function _brix_block_edit( type, data, original_block ) {
		_brix_block_add_modal_close();

		var block_data = {
			_type: type,
			_state: original_block ? "edit" : "add"
		};

		if ( data !== undefined ) {
			block_data = $.extend( block_data, data );
		}

		if ( typeof window.brix_block_edit ) {
			delete window.brix_block_edit;
		}

		window.brix_block_add_modal = new BrixBuilderModal(
			"brix_block",
			"brix_block_modal_load",
			block_data,
			function( data, after_save, nonce ) {
				if ( window.brix_block_wrapper ) {
					if ( data.ev ) {
						delete data.ev;
					}

					if ( data._wp_http_referer ) {
						delete data._wp_http_referer;
					}

					data._type = type;
					data = brix_block_filter_data( data, type );

					$.ajax( {
						type: "POST",
						url: ajaxurl,
						data: {
							data: data,
							type: type,
							dataType: "json",
							nonce: nonce,
							action: 'brix_ajax_get_block_admin_template'
						},
						success: function( resp ) {
							resp = $.parseJSON( resp );

							var html = $( $.brixf.template( "brix-js-block", {
								type: type,
								render_admin: function() {
									var type_label = window.brix_blocks[type].label,
										type_icon = window.brix_blocks[type].icon ? window.brix_blocks[type].icon : '',
										html = '<i class="brix-block-type-icon"><img src="' + type_icon + '"></i>';

									html += '<div class="brix-block-label-wrapper">';
										html += '<p class="brix-block-type-label">' + type_label + '</p>';

										if ( resp && resp["admin_template"] ) {
											html += '<div class="brix-block-type-sublabel">' + resp["admin_template"] + '</div>';
										}
									html += '</div>';

									return html;
								}
							} ) );

							html = $( html );
							html.attr( "data-data", JSON.stringify( data ) );
							html.attr( "data-stringified", resp["stringified"] );

							if ( data._hidden && data._hidden == "1" ) {
								html.addClass( "brix-hidden" );
							}
							else {
								html.removeClass( "brix-hidden" );
							}

							if ( original_block !== undefined ) {
								original_block.replaceWith( html );
							}
							else {
								if ( typeof window.brix_add_block_index == "undefined" || window.brix_add_block_index === null ) {
									$( window.brix_block_wrapper ).append( html );
								}
								else {
									$( window.brix_block_wrapper ).children().eq( window.brix_add_block_index ).after( html );
								}

								delete window.brix_add_block_index;
							}

							window.brix_block_edit = html;
							window.brix_controller.refresh( brix_box( window.brix_block_wrapper ) );

							window.brix_controller.save_state();

							delete window.brix_block_wrapper;

							if ( after_save ) {
								after_save();
							}
						}
					} );
				}
			},
			null,
			true
		);
	};

} )( jQuery );