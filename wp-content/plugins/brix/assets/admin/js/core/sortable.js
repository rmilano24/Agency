( function( $ ) {
	"use strict";

	var brix_dragged_height = null;

	/**
	 * Add padding to the page wrap in order to avoid flickering when starting
	 * to drag.
	 */
	var brix_sortable_mousedown = function( origin ) {
		if ( brix_dragged_height !== null ) {
			return false;
		}

		var has_scroll = ( document.documentElement.scrollHeight !== document.documentElement.clientHeight ),
			draggable = $( origin ).is( ".brix-draggable" ) ? $( origin ) : $( origin ).parents( ".brix-draggable" ).first();

		brix_dragged_height = draggable.outerHeight();

		if ( has_scroll ) {
			$( "#wpwrap" ).css( "padding-bottom", draggable.outerHeight() );
		}

		if ( $( origin ).is( ".brix-block" ) ) {
			var parent = ".brix-section-column-inner-wrapper";

			$( parent ).css( "min-height", "" );

			var row = $( origin ).parents( ".brix-section-row" ).first(),
				height = $( origin ).parents( parent ).first().outerHeight();

			$( parent, row ).css( "min-height", height );

			$( parent ).sortable( "refreshPositions" );
		}

		return false;
	};

	/**
	 * Remove the padding to the page wrap.
	 */
	var brix_sortable_mouseup = function() {
		brix_dragged_height = null;
		$( "#wpwrap" ).css( "padding-bottom", 0 );
	};

	/**
	 * Create and size the sortable element when dragging.
	 */
	var brix_sortable_helper = function( e, ui ) {
		var helper_html = '',
			helper_class = '';

		if ( ui.hasClass( "brix-block" ) ) {
			helper_class = 'brix-sortable-block';
			helper_html += '<img src="' + $( ".brix-block-type-icon img", ui ).attr( "src" ) + '">';
			helper_html += '<p>' + $( ".brix-block-type-label", ui ).html() + '</p>';
		}
		else if ( ui.hasClass( "brix-section-column" ) ) {
			helper_class = 'brix-sortable-column';
			helper_html += '<p>' + brix_i18n_strings.column + '</p>';
		}
		else if ( ui.hasClass( "brix-section-row" ) ) {
			helper_class = 'brix-sortable-row';
			helper_html += '<p>' + brix_i18n_strings.row + '</p>';
		}
		else if ( ui.hasClass( "brix-section" ) ) {
			helper_class = 'brix-sortable-section';
			helper_html += '<p>' + brix_i18n_strings.section + '</p>';
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
	window.BrixBuilderSortable = function( parent, items, handle, connectWith, cursorAt ) {
		var mouseobj = handle ? handle : items;

		$( mouseobj )
			.off( "mousedown.brix" )
			.off( "mouseup.brix" );

		$( mouseobj ).on( "mousedown.brix", function() {
			brix_sortable_mousedown( $( this ) );
		} );

		$( mouseobj ).on( "mouseup.brix", function() {
			brix_sortable_mouseup();
			$( ".brix-section-column-inner-wrapper" ).css( "min-height", "" );
		} );

		var sortable_options = {
			handle: handle,
			items: items,
			helper: brix_sortable_helper,
			forcePlaceholderSize: true,
			tolerance: "pointer",
			distance: 10,
			cursorAt: cursorAt,
			start: function( e, ui ) {
				$( ".brix-builder" ).addClass( "brix-dragging" );

				var css = {
					height: brix_dragged_height,
				};

				$( ".ui-sortable-placeholder" ).css( css );

				brix_sortable_mouseup();
			},
			stop: function( e, ui ) {
				$( ".brix-builder" ).removeClass( "brix-dragging" );
				$( ".brix-section-column-inner-wrapper" ).css( "min-height", "" );

				var section = null;

				if ( $( ui.item ).is( ".brix-section" ) ) {
					section = $( ui.item );
				}
				else {
					section = $( ui.item ).parents( ".brix-section" ).first();
					section = brix_box( section );
				}

				window.brix_controller.refresh( section );

				var is_editing_row = $( ui.item ).parents( ".brix-editing-row" ).length;

				if ( ! is_editing_row ) {
					window.brix_controller.save_state();
				}
			}
		};

		if ( connectWith ) {
			sortable_options.connectWith = connectWith;
		}

		if ( $( parent ).data( "sortable" ) ) {
			$( parent ).sortable( "refresh" );
		}
		else {
			$( parent ).sortable( sortable_options );
		}
	};

} )( jQuery );