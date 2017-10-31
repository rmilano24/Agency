( function( $ ) {
	"use strict";

	window.brix_column = new function() {

		var self = this;

		/**
		 * Check if the column's appearance has been tweaked.
		 */
		this.has_appearance = function( data ) {
			if ( data["background"] && data["background"] !== "" ) {
				return true;
			}

			if ( data["enable_column_carousel"] && data["enable_column_carousel"] == "1" ) {
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
		 * Column setup.
		 */
		this.setup = function( column, data ) {
			if ( ! data ) {
				return;
			}

			if ( self.has_appearance( data ) ) {
				column.addClass( "brix-has-appearance" );
			}
			else {
				column.removeClass( "brix-has-appearance" );
			}

			if ( data.enable_column_carousel && data.enable_column_carousel == "1" ) {
				column.addClass( "brix-is-carousel" );
			}
			else {
				column.removeClass( "brix-is-carousel" );
			}

			column.removeClass( "brix-column-bright-text" );

			$( ".brix-column-background", column )
				.removeClass( "brix-background-image" )
				.removeClass( "brix-background-video" )
				.removeClass( "brix-background-color" )
				.removeClass( "brix-background-gradient" )
				.css( "background-color", "" );

			var background_title = '';
			$( ".brix-column-background", column ).removeAttr( "data-title" );

			if ( data.background && data.background != "" ) {
				var bg_image = data.background_image.desktop;

				if ( data.background == "image" && typeof bg_image !== "undefined" && bg_image.image && bg_image.image["desktop"]["1"].id != "" ) {
					$( ".brix-column-background", column )
						.addClass( "brix-background-image" );

					background_title = brix_i18n_strings.background_image;
				}
				else if ( data.background == "video" && data.background_video && data.background_video.url != "" ) {
					$( ".brix-column-background", column )
						.addClass( "brix-background-video" );

					background_title = brix_i18n_strings.background_video;
				}
				else if ( typeof bg_image !== "undefined" && bg_image.color_type && bg_image.color_type == "gradient" ) {
					$( ".brix-column-background", column )
						.addClass( "brix-background-gradient" );

					background_title = brix_i18n_strings.background_gradient;
				}
				else if ( typeof bg_image !== "undefined" && bg_image.color_type && bg_image.color_type == "solid" && bg_image.color && bg_image.color.color != "" ) {
					$( ".brix-column-background", column )
						.addClass( "brix-background-color");

					$( ".brix-column-background span", column )
						.css( "background-color", bg_image.color.color );

					if ( ! brix_is_color_bright( bg_image.color.color ) ) {
						column.addClass( "brix-column-bright-text" );
					}

					background_title = brix_i18n_strings.background_color;
				}

			}

			if ( background_title != '' ) {
				$( ".brix-column-background", column ).attr( "data-title", background_title );
			}

			var size = column.attr( "data-size" ),
				num = parseInt( size.split( "/" )[0], 10 ),
				den = parseInt( size.split( "/" )[1], 10 );

			$( ".brix-section-column-label", column ).html( Number( Math.round( ( ( num / den ) * 100 )+'e1')+'e-1') + '%' );

			column[0].className = column[0].className.replace(/\bbrix-col-\d{1,2}-\d{1,2}?\b/g, '');
			column.addClass( "brix-col-" + size.replace( "/", "-" ) );
		};

		/**
		 * Check if a column can be merged with the one that precedes it.
		 */
		this.can_merge = function( column ) {
			var prbrix_column = column.prev();

			if ( ! prbrix_column.length ) {
				return false;
			}

			var size = column.attr( "data-size" ),
				prbrix_size = prbrix_column.attr( "data-size" ),
				den = parseInt( size.split( "/" )[1], 10 ),
				num = parseInt( size.split( "/" )[0], 10 ),
				prbrix_den = parseInt( prbrix_size.split( "/" )[1], 10 ),
				prbrix_num = parseInt( prbrix_size.split( "/" )[0], 10 ),
				subsection = brix_parent( column, ".brix-subsection" ),
				max_c_den = parseInt( den, 10 ) * parseInt( prbrix_den, 10 );

			var new_frac_num = ( max_c_den * num / den ) + ( max_c_den * prbrix_num / prbrix_den ),
				new_frac_den = max_c_den;

			var new_frac = _brix_reduce_fraction( new_frac_num, new_frac_den );

			new_frac_den = parseInt( new_frac.split( "/" )[1], 10 );

			if ( new_frac_den > brix_section.column_limit( subsection ) ) {
				return false;
			}

			return true;
		};

		/**
		 * Check if a column can be split.
		 */
		this.can_split = function( column ) {
			var size = column.attr( "data-size" ),
				den = parseInt( size.split( "/" )[1], 10 ),
				num = parseInt( size.split( "/" )[0], 10 ),
				new_frac = _brix_reduce_fraction( num, den * 2 ),
				subsection = brix_parent( column, ".brix-subsection" );

			if ( den * 2 > brix_section.column_limit( subsection ) ) {
				return false;
			}

			return true;
		};

		/**
		 * Create an empty column and return its HTML.
		 */
		this.create_column = function( num, den ) {
			num = parseInt( num, 10 );
			den = parseInt( den, 10 );

			var new_frac = _brix_reduce_fraction( num, den ),
				html = $( $.brixf.template( "brix-js-column", {
					size: new_frac,
					size_label: Number( Math.round( ( ( num / den ) * 100 )+'e1')+'e-1') + '%'
				} ) );

			return html;
		}

		/**
		 * Split a column.
		 */
		this.split = function( column ) {
			if ( ! self.can_split( column ) ) {
				return false;
			}

			var row = brix_parent( column, ".brix-section-row" ),
				size = column.attr( "data-size" ),
				den = parseInt( size.split( "/" )[1], 10 ),
				num = parseInt( size.split( "/" )[0], 10 ),
				new_frac = _brix_reduce_fraction( num, den * 2 );

			var layout = [];

			$( ".brix-section-column", row ).each( function() {
				if ( ! $( this ).is( column ) ) {
					layout.push( $( this ).attr( "data-size" ) );
				}
				else {
					layout.push( new_frac );
					layout.push( new_frac );
				}
			} );

			column.after( self.create_column( num, den * 2 ) );

			brix_row.change_layout( row, layout, true );
		};

		/**
		 * Merge the current column with the one that precedes it.
		 */
		this.merge = function( column ) {
			var section = brix_parent( column, ".brix-section" ),
				row = brix_parent( column, ".brix-section-row" );

			if ( ! brix_column.can_merge( column ) ) {
				return false;
			}

			var prbrix_column = column.prev(),
				size = column.attr( "data-size" ),
				prbrix_size = prbrix_column.attr( "data-size" ),
				den = parseInt( size.split( "/" )[1], 10 ),
				num = parseInt( size.split( "/" )[0], 10 ),
				prbrix_den = parseInt( prbrix_size.split( "/" )[1], 10 ),
				prbrix_num = parseInt( prbrix_size.split( "/" )[0], 10 ),
				max_c_den = parseInt( den, 10 ) * parseInt( prbrix_den, 10 );

			var new_frac_num = ( max_c_den * num / den ) + ( max_c_den * prbrix_num / prbrix_den ),
				new_frac_den = max_c_den;

			var new_frac = _brix_reduce_fraction( new_frac_num, new_frac_den );

			var layout = [];

			$( ".brix-section-column", row ).each( function( i ) {
				if ( ! $( this ).is( column ) ) {
					layout.push( $( this ).attr( "data-size" ) );
				}
				else {
					layout.pop();
					layout.push( new_frac );
				}
			} );

			prbrix_column.attr( "data-size", new_frac );
			$( ".brix-block", column ).appendTo( $( ".brix-section-column-inner-wrapper", prbrix_column ) );
			column.remove();

			var data = $.extend( true, {}, $.parseJSON( prbrix_column.attr( "data-data" ) ) );

			if ( data && data["_responsive"] ) {
				delete data["_responsive"];
			}

			prbrix_column.attr( "data-data", JSON.stringify( data ) );

			self.setup( prbrix_column, data );

			window.brix_controller.refresh( section );

			brix_row.change_layout( row, layout, true );
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			/**
			 * Merge the current column with the one that precedes it.
			 */
			$( document ).on( "click.brix", ".brix-column-merge", function() {
				self.merge( brix_parent( this, ".brix-section-column" ) );

				return false;
			} );

			/**
			 * Split a column.
			 */
			$( document ).on( "click.brix", ".brix-column-split", function() {
				self.split( brix_parent( this, ".brix-section-column" ) );

				return false;
			} );

			/**
			 * Column data edit.
			 */
			$( document ).on( "click.brix", ".brix-column-edit", function() {
				var button = $( this ),
					column = brix_parent( button, ".brix-section-column" ),
					data = $.parseJSON( column.attr( "data-data" ) ),
					box = brix_box( column );

				var modal = new BrixBuilderModal(
					"brix_column",
					"brix_column_modal_load",
					data,
					function( data ) {
						if ( data.ev ) {
							delete data.ev;
						}

						if ( data._wp_http_referer ) {
							delete data._wp_http_referer;
						}

						var column_data = $.parseJSON( column.attr( "data-data" ) );
						column_data = $.evExtendObject( data, column_data );

						brix_column.setup( column, column_data );

						column.attr( "data-data", JSON.stringify( column_data ) );

						window.brix_controller.refresh( box );

						window.brix_controller.save_state();
					}
				);

				return false;
			} );

			/**
			 * Click to add a new block.
			 */
			$( document ).on( "click.brix", ".brix-add-block", function() {
				window.brix_block_wrapper = $( ".brix-section-column-inner-wrapper", brix_parent( this, ".brix-section-column" ) );

				_brix_blocks_add_modal();

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