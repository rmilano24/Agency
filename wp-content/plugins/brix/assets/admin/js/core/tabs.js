( function( $ ) {
	"use strict";

	/**
	 * Toggle the tabs orientation.
	 */
	$.brixf.delegate( ".brix-block-tabs [data-handle='orientation'] select[name]", "change", "tabs_modal", function() {
		var bundle = $( this ).parents( ".brix-block-tabs" ).first(),
			orientation = $( this ).val();

		if ( ! orientation ) {
			orientation = "horizontal";
		}

		bundle.removeClass( "brix-vertical brix-horizontal" );
		bundle.addClass( "brix-" + orientation );

		$( '.brix-tabs-contents .brix-tabs-content', bundle ).css( {
			'min-height': $( ".brix-tab-block-nav > ul", bundle ).outerHeight()
		} );

		return false;
	} );

	/**
	 * Highlight the current tab.
	 */
	$.brixf.delegate( ".brix-tab-block-trigger", "mouseenter", "tabs_modal", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			content = $( ".brix-tabs-content", field ),
			input = $( "[data-tabs-value]", field ),
			index = $( this ).index(),
			tabs_value = $.parseJSON( input.val() ),
			current_class = 'brix-current';

		$( ".brix-tab-block-trigger" ).removeClass( current_class );
		$( this ).addClass( current_class );

		content.removeClass( current_class );
		content.eq( index ).addClass( current_class );
	} );

	/**
	 * Remove a tab.
	 */
	$.brixf.delegate( ".brix-tab-block-remove-tab", "click", "tabs_modal", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			tabs_external_container = $( this ).parents( '.brix-tabs-container' ).first(),
			trigger = $( this ).parents( ".brix-tab-block-trigger" ),
			index = trigger.index(),
			input = $( "[data-tabs-value]", field ),
			tabs_value = $.parseJSON( input.val() );

		trigger.remove();
		$( ".brix-tabs-content", field ).eq( index ).remove();

		tabs_value.splice( index, 1 );

		input.val( JSON.stringify( tabs_value ) );

		if ( tabs_value.length == 0 ) {
			tabs_external_container.addClass( 'brix-tabs-empty' );
		}

		$( '.brix-tabs-contents .brix-tabs-content', field ).css( {
			'min-height': $( ".brix-tab-block-nav > ul", field ).outerHeight()
		} );

		return false;
	} );

	/**
	 * Edit a tab.
	 */
	$.brixf.delegate( ".brix-tab-block-title", "click", "tabs_modal", function() {
		var trigger_title = $( this ),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-tabs-value]", field ),
			index = $( this ).parents( ".brix-tab-block-trigger" ).index(),
			content = $( ".brix-tabs-content", field ).eq( index ),
			tabs_value = $.parseJSON( input.val() );

		if ( window.brix_edit_tab_modal ) {
			delete window.brix_edit_tab_modal;
		}

		window.brix_edit_tab_modal = new BrixBuilderModal(
			"brix_edit_tab",
			"brix_tab_modal_load",
			tabs_value[index] ? tabs_value[index] : {},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_tabs_i18n_strings.title;

				if ( data.title && data.title != "" ) {
					title = data.title;
				}

				trigger_title.html( title );
				content.html( data.content );

				tabs_value[index] = data;

				input.val( JSON.stringify( tabs_value ) );

				$( '.brix-tabs-contents .brix-tabs-content', field ).css( {
					'min-height': $( ".brix-tab-block-nav > ul", field ).outerHeight()
				} );
			}
		);

		return false;
	} );

	/**
	 * Add a tab.
	 */
	$.brixf.delegate( ".brix-add-new-tab", "click", "tabs_modal", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			tabs_external_container = $( this ).parents( '.brix-tabs-container' ).first(),
			tabs_container = $( ".brix-tab-block-nav > ul", field ),
			add_new_tab_el = $( ".brix-tab-block-nav > ul .brix-add-new-tab-wrapper", field ),
			input = $( "[data-tabs-value]", field ),
			tabs_value = $.parseJSON( input.val() );

		if ( window.brix_add_new_tab_modal ) {
			delete window.brix_add_new_tab_modal;
		}

		window.brix_add_new_tab_modal = new BrixBuilderModal(
			"brix_add_new_tab",
			"brix_tab_modal_load",
			{},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_tabs_i18n_strings.title,
					current_class = "brix-current";

				if ( data.title && data.title != "" ) {
					title = data.title;
				}

				tabs_value.push( data );

				$( ".brix-tabs-content", field ).removeClass( current_class );
				$( ".brix-tab-block-trigger", field ).removeClass( current_class );

				$( ".brix-tabs-contents", field ).append( '<div class="brix-tabs-content ' + current_class + '">' + data.content + '</div>' );
				add_new_tab_el.before( '<li class="brix-tab-block-trigger ' + current_class + '"><div class="brix-tab-block-trigger-inner-wrapper"><span class="brix-tab-block-handle"></span><span class="brix-tab-block-title">' + title + '</span><span class="brix-tab-block-remove-tab"></span></div></li>' );

				input.val( JSON.stringify( tabs_value ) );

				$( '.brix-tabs-contents .brix-tabs-content', field ).css( {
					'min-height': tabs_container.outerHeight()
				} );

				tabs_external_container.removeClass( 'brix-tabs-empty' );
			}
		);

		return false;
	} );

	/**
	 * Boot the block UI.
	 */
	$.brixf.ui.add( ".brix-tabs-container", function() {
		$( this ).each( function() {
			var field = $( this ).parents( ".brix-field" ).first(),
			tabs_container = $( ".brix-tab-block-nav > ul", field ),
			input = $( "[data-tabs-value]", field ),
			tabs_value = {},
			position_from = 0,
			position_to = 0;

			$( '.brix-tabs-contents .brix-tabs-content', field ).css( {
				'min-height': tabs_container.outerHeight()
			} );

			tabs_container.sortable( {
				items: "li:not(.brix-add-new-tab-wrapper)",
				tolerance: "pointer",
				start: function( event, ui ) {
					tabs_value = $.parseJSON( input.val() );
					position_from = ui.item.index();
				},
				stop: function( event, ui ) {
					var old_tabs_value = $.extend( true, {}, tabs_value );

					position_to = ui.item.index();

					tabs_value[position_to] = old_tabs_value[position_from];
					tabs_value[position_from] = old_tabs_value[position_to];

					var copy_to = $( ".brix-tabs-content", field ).eq( position_to ).clone( true ),
						copy_from = $( ".brix-tabs-content", field ).eq( position_from ).clone( true );

					$( ".brix-tabs-content", field ).eq( position_to ).replaceWith( copy_from );
					$( ".brix-tabs-content", field ).eq( position_from ).replaceWith( copy_to );

					input.val( JSON.stringify( tabs_value ) );
				}
			} );
		} );
	} );

} )( jQuery );
