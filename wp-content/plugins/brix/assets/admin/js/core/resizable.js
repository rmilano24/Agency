( function( $ ) {
	"use strict";

	window.BrixBuilderResizableColumn = function() {
		var dragged_column = null,
			layout = [],
			new_delta = 0,
			step_px = 0,
			step = 0,
			original_size = '',
			original_next_size = '';

		function calc_col_size( size, base, delta ) {
			var num = parseInt( size.split( "/" )[0], 10 ),
				den = parseInt( size.split( "/" )[1], 10 );

			if ( den != base ) {
				num *= ( base / den );
				den = base;
			}

			num += delta;

			if ( num < 1 ) {
				num = 1;
			}

			return _brix_reduce_fraction( num, den );
		}

		function delta_cols( column, base, delta ) {
			var limit = 1 / base,
				next_column = column.next(),
				row = brix_parent( column, ".brix-section-row" );

			var old_sum =
				parseFloat( original_size.split( "/" )[0] / original_size.split( "/" )[1] ) +
				parseFloat( original_next_size.split( "/" )[0] / original_next_size.split( "/" )[1] );

			var column_frac = calc_col_size( original_size, base, delta ),
				next_column_frac = calc_col_size( original_next_size, base, -delta );

			var new_sum =
				parseFloat( column_frac.split( "/" )[0] / column_frac.split( "/" )[1] ) +
				parseFloat( next_column_frac.split( "/" )[0] / next_column_frac.split( "/" )[1] );

			old_sum = Number( Math.round( ( old_sum )+'e3')+'e-3');
			new_sum = Number( Math.round( ( new_sum )+'e3')+'e-3');

			if ( new_sum !== old_sum ) {
				return;
			}

			column.attr( "data-size", column_frac );
			brix_column.setup( column, {} );

			next_column.attr( "data-size", next_column_frac );
			brix_column.setup( next_column, {} );
		}

		function calc_base( columns ) {
			var base = 0;

			columns.each( function() {
				var size = $( this ).attr( "data-size" ),
					den = parseInt( size.split( "/" )[1], 10 );

				if ( den > base ) {
					base = den;
				}
			} );

			return "" + base;
		}

		function get_step( base ) {
			var steps = {
				"1": 12,
				"2": 12,
				"3": 12,
				"4": 12,
				"5": 10,
				"6": 12,
				"7": 7,
				"8": 8,
				"9": 9,
				"10": 10,
				"11": 11,
				"12": 12,
			};

			return steps[base];
		}

		var resizable_selector = ".brix-section-column:not( .brix-col-1-1 )",
			resizable_options = {
				handles: "e",
				start: function( event, ui ) {
					dragged_column = ui.element;
					layout = [];
					new_delta = 0;


					var size = ui.element.attr( "data-size" ),
						row_inner = brix_parent( ui.element, ".brix-section-row-inner-wrapper" ),
						row_width = row_inner.outerWidth(),
						columns = $( ".brix-section-column", row_inner );

					row_inner.addClass( "brix-resizing" );

					var base = calc_base( columns );
					step = get_step( base );

					step_px = parseInt( row_width / step, 10 );

					original_size = dragged_column.attr( "data-size" );
					original_next_size = dragged_column.next().attr( "data-size" );
				},
				resize: function( event, ui ) {
					var new_delta_temp = parseInt( ( ui.size.width - ui.originalSize.width ) / step_px, 10 );

					if ( new_delta_temp !== new_delta ) {
						new_delta = new_delta_temp;
						delta_cols( dragged_column, step, new_delta );
					}

					layout = [];

					$( ".brix-section-column", brix_parent( ui.element, ".brix-section-row-inner-wrapper" ) ).each( function() {
						layout.push( $( this ).attr( "data-size" ) );
					} );
				},
				stop: function( event, ui ) {
					brix_row.change_layout( brix_parent( ui.element, ".brix-section-row" ), layout, true );
					$( ".brix-resizing" ).removeClass( "brix-resizing" );

					dragged_column = null;
					layout = [];
					new_delta = 0;
					step_px = 0;
					step = 0;
					original_size = '';
					original_next_size = '';
				}
			};

		$( resizable_selector ).each( function() {
			$( this ).resizable( resizable_options );

			if ( $( this ).resizable( "instance" ) ) {
				$( this ).resizable( "destroy" );
			}

			$( this ).resizable( resizable_options );
		} );
	};

} )( jQuery );