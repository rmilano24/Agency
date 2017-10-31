( function( $ ) {
	"use strict";

	/**
	 * Slide to include a particular element in the viewport.
	 */
	function brix_repeatable_maybe_scroll( element ) {
		element = $( element ).get( 0 );

		var rect = element.getBoundingClientRect(),
			in_viewport =
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= ( window.innerHeight || document.documentElement.clientHeight ) &&
				rect.right <= ( window.innerWidth || document.documentElement.clientWidth );

		if ( in_viewport ) {
			return;
		}

		$( element ).scrollintoview( {
			duration: 300,
			// easing: "easeInOutCubic",
			direction: "vertical",
			offset: 40
		} );
	}

	/**
	 * Replace a string at a given position.
	 */
	function brix_repeatable_replace_at( string, index, character, how_many ) {
		return string.substr( 0, index ) + character + string.substr( index + how_many );
	}

	/**
	 * Adding the sortable component to the UI building queue.
	 */
	$.brixf.ui.add( ".brix-sortable .brix-container-repeatable-inner-wrapper", function() {
		var brix_sortable_dragged_height = null;

		/**
		 * Add padding to the page wrap in order to avoid flickering when starting
		 * to drag.
		 */
		var brix_repeatable_sortable_mousedown = function( origin ) {
			if ( brix_sortable_dragged_height !== null ) {
				return false;
			}

			var sortable = 0;

			if ( $( origin ).parents( ".brix-bundle-fields-wrapper" ).length ) {
				sortable = $( origin ).parents( ".brix-bundle-fields-wrapper" ).first();
			}
			else {
				sortable = $( origin ).parents( ".brix-field-inner" ).first();
			}

			brix_sortable_dragged_height = sortable.outerHeight();

			$.brixSaveRichTextareas( sortable );

			$( "#wpbody" ).css( "padding-bottom", brix_sortable_dragged_height + 10 );

			return false;
		};

		/**
		 * Remove the padding to the page wrap.
		 */
		var brix_repeatable_sortable_mouseup = function() {
			brix_sortable_dragged_height = null;
			$( "#wpbody" ).css( "padding-bottom", "" );
		};

		$( document )
			.off( "mousedown.brix_sortable" )
			.off( "mouseup.brix_sortable" );

		$( document ).on( "mousedown.brix_sortable", ".brix-sortable-handle", function() {
			brix_repeatable_sortable_mousedown( $( this ) );
		} );

		$( document ).on( "mouseup.brix_sortable", ".brix-sortable-handle", function() {
			brix_repeatable_sortable_mouseup();
		} );

		$( this ).sortable( {
			handle: ".brix-sortable-handle",
			items: "> .brix-field-inner, .brix-bundle-fields-wrapper",
			tolerance: "pointer",
			distance: 10,
			start: function( e, ui ) {
				var css = {
					height: brix_sortable_dragged_height,
				};

				$( ".ui-sortable-placeholder" ).css( css );

				brix_repeatable_sortable_mouseup();
			},
			stop: function( e, ui ) {
				var sortable = $( ui.item ).parents( ".brix-sortable" ).first(),
					fields = $( "> .brix-field-inner, .brix-bundle-fields-wrapper", sortable ),
					depth = $( ui.item ).parents( ".brix-repeatable" ).length;

				fields.each( function( index, field ) {
					$( "[name]", field ).each( function() {
						var name_attr = $( this ).attr( "name" ),
							reg = new RegExp( /\[\d+\]/g ),
							matches = name_attr.match( reg ),
							i = 0;

						if ( matches && matches.length ) {
							for ( var j=0; j<matches.length; j++ ) {
								matches[j] = j === depth - 1 ? "[" + index + "]" : matches[j];
							}

							var match = null;

							while ( ( match = reg.exec( name_attr ) ) !== null ) {
								name_attr = brix_repeatable_replace_at( name_attr, match.index, matches[i], matches[i].length );
								i++;
							}

							$( this ).attr( "name", name_attr );
						}
					} );
				} );

				$( document ).trigger( "brix-repeatable-sortable-stop", [ $( ui.item ) ] );
			}
		} );
	} );

	/**
	 * When clicking on a repeatable remove button, remove its parent field.
	 */
	$.brixf.delegate( ".brix-repeatable-remove", "click", "repeatable", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			container = $( ".brix-container, .brix-bundle-fields-wrapper", field ).first(),
			current_field = $( this ).parents( ".brix-field-inner, .brix-bundle-fields-wrapper" ).first();

		current_field.remove();

		if ( ! $( ".brix-field-inner", container ).length ) {
			field.addClass( "brix-no-fields" );
		}

		return false;
	} );

	/**
	 * Remove all the added repeatable fields.
	 */
	$.brixf.delegate( ".brix-repeat-remove-all", "click", "repeatable", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			container = $( ".brix-container", field ).first(),
			fields = $( ".brix-field-inner, .brix-bundle-fields-wrapper", container );

		fields.remove();

		field.addClass( "brix-no-fields" );

		return false;
	} );

	/**
	 * When clicking on a repeatable control, load a field template and append
	 * it to the set of already created fields.
	 */
	$.brixf.delegate( ".brix-field.brix-repeatable .brix-repeat", "click", "repeatable", function() {
		var ctrl 		= $( this ),
			field 		= ctrl.parents( ".brix-field.brix-repeatable" ).first(),
			inner 		= ctrl.parents( ".brix-field-inner, .brix-bundle-fields-wrapper" ).first(),
			container 	= ctrl.parents( ".brix-container-repeatable-inner-wrapper" ).first(),
			empty_state = $( ".brix-empty-state", field );

		var update_count = function() {
			var current_count = parseInt( empty_state.attr( "data-count" ), 10 );

			current_count = current_count + 1;
			empty_state.attr( "data-count", current_count );

			return current_count;
		};

		var update_names = function( count, field ) {
			$( ".brix-field-inner", field ).each( function() {
				var control = $( ".brix-repeatable-controls", this ).first(),
					count = parseInt( empty_state.attr( "data-count" ), 10 );

				$( "[name]", html ).each( function() {
					$( this ).attr( "name", this.name.replaceLast( "[]", "[" + count + "]" ) );
				} );
			} );
		};

		var key = empty_state.attr( "data-key" ),
			tpl = $( "script[type='text/template'][data-template='" + key + "']" ),
			mode = ctrl.attr( "data-mode" );

		var insert = function( html, mode ) {
			var count = update_count();

			if ( mode ) {
				if ( mode === "append" ) {
					html.insertAfter( inner );
				}
				else if ( mode === "prepend" ) {
					html.insertBefore( inner );
				}
			}
			else {
				html.appendTo( container );
			}

			update_names( count, field );

			field.removeClass( "brix-no-fields" );

			setTimeout( function() {
				$.brixf.ui.build();

				brix_repeatable_maybe_scroll( html );
			}, 1 );
		};

		if ( ctrl.attr( "data-action" ) ) {
			window[ctrl.attr( "data-action" )]( tpl, insert );
		}
		else {
			var html = $( $.brixf.template( tpl, {} ) );

			insert( html, mode );
		}

		return false;
	} );

} )( jQuery );