( function( $ ) {
	"use strict";

	var blocks_context = ".brix-section-column-inner-wrapper:not([data-carousel])";

	/**
	 * Add padding to the page wrap in order to avoid flickering when starting
	 * to drag.
	 */
	var brix_sortable_mousedown = function( origin ) {
		if ( $( origin ).is( ".brix-section-column-block" ) ) {
			var parent = ".brix-section-column-inner-wrapper";

			$( parent ).css( "min-height", "" );

			var row = $( origin ).parents( ".brix-section-row" ).first(),
				height = $( origin ).parents( parent ).first().outerHeight();

			$( parent, row ).css( "min-height", height );

			$( ".brix-frontend-sortable" ).sortable( "refreshPositions" );
		}

		return false;
	};

	/**
	 * Remove the padding to the page wrap.
	 */
	var brix_sortable_mouseup = function() {
	};

	/**
	 * Create and size the sortable element when dragging.
	 */
	var brix_sortable_helper = function( e, ui ) {
		var helper_html = '<span>' + $( ".brix-block-frontend-editing-placeholder svg", ui )[0].outerHTML + '</span>',
			helper_class = '';

		if ( ui.hasClass( "brix-section-column-block" ) ) {
			helper_class = 'brix-sortable-block';
		}

		var helper = '<div class="brix-sortable-helper ' + helper_class + '">' + helper_html + '</div">';

		return $( helper );
	};

	/**
	 * Sortable component for builder elements on backend.
	 *
	 * @param {String} parent      The main sortables container.
	 * @param {String} items       Items to sort.
	 * @param {String} handle      Optional handle element.
	 * @param {String} connectWith Connect with other sortables.
	 * @param {String} cursorAt Cursor position when sorting.
	 */
	window.BrixFrontendEditingSortable = function( parent, items, handle, connectWith, cursorAt ) {
		var mouseobj = handle ? handle : items,
			self = this;

		this.start_coords = null;
		this.end_coords = null;

		this.get_start_coords = function( item ) {
			var index = 0;

			if ( item.hasClass( "brix-section-column-block" ) ) {
				index = item.index( ".brix-section-column-block" );
			}

			this.start_coords = {
				index: index
			};
		};

		this.get_end_coords = function( item ) {
			var index = 0,
				column_index = 0;

			if ( item.hasClass( "brix-section-column-block" ) ) {
				var arrival_column = item.parents( ".brix-section-column" ),
					column_index = arrival_column.index( ".brix-section-column" );

				index = item.index();
			}

			this.end_coords = {
				index: index,
				column: column_index
			};
		};

		// $( mouseobj )
		// 	.off( "mousedown.brix" )
		// 	.off( "mouseup.brix" );

		// $( mouseobj ).on( "mousedown.brix", function() {
		// 	brix_sortable_mousedown( $( this ) );
		// } );

		// $( mouseobj ).on( "mouseup.brix", function() {
		// 	brix_sortable_mouseup();
		// } );

		var sortable_options = {
			handle: handle,
			items: items,
			helper: brix_sortable_helper,
			forcePlaceholderSize: true,
			tolerance: "pointer",
			distance: 10,
			cursorAt: cursorAt,
			appendTo: document.body,
			start: function( e, ui ) {
				brix_sortable_mousedown( ui.item );

				$( parent ).addClass( "brix-drop-area" );
				$( "body" ).addClass( "brix-dragging" );

				$( "brix-frontend-sortable" ).sortable( "refreshPositions" );

				self.get_start_coords( ui.item );

				brix_sortable_mouseup();
			},
			stop: function( e, ui ) {
				$( parent ).removeClass( "brix-drop-area" );
				$( "body" ).removeClass( "brix-dragging" );
				$( ".brix-section-column-inner-wrapper" ).css( "min-height", "" );

				window.brix_frontend_editing.clear();

				$( ui.item ).attr( "style", "" );

				self.get_end_coords( ui.item );

				window.parent.brix_frontend_editing.move_block( self.start_coords, self.end_coords );

				var empty_columns = $( ".brix-section-column-inner-wrapper" ).filter( function() {
					return $( this ).children().length == 0;
				} );

				empty_columns.html( "" );

				// Toggle comment to force a page redraw
				window.parent.brix_frontend_editing.update( true );
			}
		};

		if ( connectWith ) {
			sortable_options.connectWith = connectWith;
		}

		$( parent ).addClass( "brix-frontend-sortable" );

		if ( $( parent ).data( "sortable" ) ) {
			$( parent ).sortable( "refresh" );
		}
		else {
			$( parent ).sortable( sortable_options );
		}
	};

	$( window ).on( "brix_ready", function() {
		var block_sortable = new BrixFrontendEditingSortable(
			blocks_context,
			".brix-section-column-block",
			false,
			blocks_context,
			{ top: 24, left: 24 }
		);
	} );

	$( window ).on( "resize", function() {
		var is_preview = $( "body" ).hasClass( "brix-frontend-preview-mode" );

		$( ".brix-frontend-sortable" ).each( function() {
			if ( is_preview ) {
				$( this ).sortable( "disable" );
			}
			else {
				$( this ).sortable( "enable" );
			}
		} );
	} );

} )( jQuery );