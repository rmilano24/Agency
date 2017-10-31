( function( $ ) {
	"use strict";

	/**
	 * Templates toggle.
	 */
	$( document ).on( "click", ".brix-template-nav-item", function() {
		var nav = $( this ).attr( 'data-nav' ),
			toolbar = $( this ).parents( ".brix-templates-toolbar" ).first();

		$( '.brix-template-nav-item' ).removeClass( 'brix-active' );
		$( '.brix-templates-wrapper' ).removeClass( 'brix-active' );

		$( this ).addClass( 'brix-active' );
		$( '.brix-templates-wrapper[data-nav="' + nav + '"]' ).addClass( 'brix-active' );
		toolbar.attr( "data-nav", nav );

		return false;
	} );

	/**
	 * Switch template type.
	 */
	$( document ).on( "click", ".brix-templates-type-switch a", function() {
		var index = $( this ).index(),
			wrapper = $( this ).parents( ".brix-templates-wrapper" ).first();

		$( ".brix-templates-inner-wrapper", wrapper ).removeClass( "brix-active" );

		if ( index ) {
			$( ".brix-templates-inner-wrapper", wrapper ).eq( index - 1 ).addClass( "brix-active" );
		}
		else {
			$( ".brix-templates-inner-wrapper", wrapper ).addClass( "brix-active" );
		}

		$( this ).siblings().removeClass( "brix-active" );
		$( this ).addClass( "brix-active" );

		return false;
	} );

	/**
	 * Save a builder template.
	 */
	function _brix_save_template( box, section_index, nonce ) {
		var template_name = prompt( brix_i18n_strings.enter_template_name );

		if ( template_name ) {
			if ( template_name.trim() !== "" ) {
				window.brix_controller.saveBuilderTemplate( template_name, nonce, box, section_index );
			}
		}
	}

	/**
	 * Save the current layout as a new builder template.
	 */
	$( ".brix-save-builder-template" ).on( "click.brix", function() {
		var box = brix_box( this ),
			nonce = $( this ).attr( "data-nonce" ),
			section_index = -1;

		_brix_save_template( box, section_index, nonce );

		return false;
	} );

	/**
	 * Save the current section layout as a new builder template.
	 */
	$( document ).on( "click.brix", ".brix-section-save-template", function() {
		var box = brix_box( this ),
			nonce = $( this ).attr( "data-nonce" ),
			sections = $( ".brix-section", box ),
			section = brix_parent( this, ".brix-section" ),
			section_index = 0;

		sections.each( function( i ) {
			if ( this === section.get( 0 ) ) {
				section_index = i;
				return false;
			}
		} );

		_brix_save_template( box, section_index, nonce );

		return false;
	} );

	$( document ).on( "click.brix", ".brix-section-replace-with-template", function() {
		var box = brix_box( this ),
			// nonce = $( this ).attr( "data-nonce" ),
			sections = $( ".brix-section", box ),
			section = brix_parent( this, ".brix-section" ),
			section_index = 0;

		sections.each( function( i ) {
			if ( this === section.get( 0 ) ) {
				section_index = i;
				return false;
			}
		} );

		_brix_load_builder_templates( section_index, box );

		return false;
	} );

	/**
	 * Load a list the available builder templates.
	 */
	function _brix_load_builder_templates( section_index, box ) {
		window.brix_section_template = false;

		if ( typeof section_index !== "undefined" && section_index !== false ) {
			window.brix_section_template = $( ".brix-section", box ).eq( section_index );
		}

		window.brix_load_template_modal = new BrixBuilderModal(
			"brix-load-builder-template",
			"brix_load_template_modal_load",
			{
				// "nonce": $( this ).attr( "data-nonce" ),
				"section": window.brix_section_template ? true : false
			},
			function( data ) {},
			function() {
				$( window ).trigger( "brix-templates-modal-loaded" );
			}
		);
	}

	/**
	 * Load a list the available builder templates.
	 */
	$( document ).on( "click.brix", ".brix-box .brix-load-builder-template", function() {
		var box = brix_box( this ),
			sections = $( ".brix-section", box ),
			section_index = false;

		if ( $( this ).parents( ".brix-section" ).length ) {
			var section_index = 0,
				section = brix_parent( this, ".brix-section" );

			sections.each( function( i ) {
				if ( this === section.get( 0 ) ) {
					section_index = i;
					return false;
				}
			} );
		}

		_brix_load_builder_templates( section_index, box );

		return false;
	} );

	/**
	 * Change the current builder layout to the selected template.
	 */
	$( document ).on( "click.brix", ".brix-use-builder-template", function() {
		if ( confirm( brix_i18n_strings.confirm_template_change ) ) {
			var box = $( ".brix-box" ).first(),
				ul = $( this ).parents( "ul" ).first(),
				nonce = $( this ).attr( "data-nonce" ),
				id = $( this ).attr( "data-id" );

			if ( window.brix_section_template && window.brix_section_template !== false ) {
				window.brix_controller.changeSectionTemplate( id, function() {
					if ( window.brix_load_template_modal ) {
						window.brix_load_template_modal.close();
					}

					window.brix_controller.save_state();
				}, nonce, window.brix_section_template );
			}
			else {
				window.brix_controller.changeBuilderTemplate( id, function() {
					if ( window.brix_load_template_modal ) {
						window.brix_load_template_modal.close();
					}

					window.brix_controller.save_state();
				}, nonce, box );
			}

			delete window.brix_section_template;
		}

		return false;
	} );

	/**
	 * Delete a builder layout.
	 */
	$( document ).on( "click.brix", ".brix-user-template .brix-remove-builder-template", function() {
		if ( confirm( brix_i18n_strings.confirm_template_delete ) ) {
			var template_row = $( this ).parents( ".brix-template" ).first(),
				wrapper = $( this ).parents( ".brix-templates-inner-wrapper" ).first(),
				user_wrapper = $( ".brix-templates-wrapper[data-nav='user']" ),
				wrapper_type = wrapper.attr( "data-type" ),
				master_wrapper = $( this ).parents( ".brix-templates-wrapper" ).first(),
				content = $( ".brix-user-template-content-inner", master_wrapper ),
				ref = $( ".brix-use-builder-template", template_row ),
				id = ref.attr( "data-id" ),
				nonce = ref.attr( "data-nonce" );

			window.brix_controller.deleteBuilderTemplate( id, function() {
				template_row.remove();

				user_wrapper.removeClass( "brix-user-template-empty" );
				wrapper.removeClass( "brix-user-template-type-empty" );

				content.removeClass( "brix-user-template-" + wrapper_type + "-empty" );

				if ( $( ".brix-template", wrapper ).length === 0 ) {
					content.addClass( "brix-user-template-" + wrapper_type + "-empty" );
				}

				if ( $( ".brix-template", user_wrapper ).length === 0 ) {
					$( ".brix-templates-type-switch [data-type='" + wrapper_type + "']", master_wrapper ).remove();
					user_wrapper.addClass( "brix-user-template-empty" );
				}

				if ( $( ".brix-template" ).length === 0 ) {
					$( "body" ).removeClass( "brix-has-templates" );
				}

				if ( $( "[data-nav='user'] .brix-template" ).length === 0 ) {
					$( "body" ).removeClass( "brix-has-user-templates" );
				}

				if ( $( ".brix-template[data-sticky='1']", master_wrapper ).length === 0 ) {
					$( "body" ).removeClass( "brix-has-sticky-templates" );
				}
			}, nonce );
		}

		return false;
	} );

	$( document ).on( "click.brix", ".brix-sticky-templates button", function() {
		var id = $( this ).attr( "data-id" ),
			nonce = $( this ).attr( "data-nonce" ),
			box = brix_box( this ),
			builder_start = brix_parent( this, ".brix-start" );

		builder_start.addClass( "brix-loading" );

		window.brix_controller.changeBuilderTemplate( id, function() {
			if ( window.brix_load_template_modal ) {
				window.brix_load_template_modal.close();
			}

			builder_start.removeClass( "brix-loading" );
		}, nonce, box );

		return false;
	} );

} )( jQuery );