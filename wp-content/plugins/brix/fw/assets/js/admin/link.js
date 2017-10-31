( function( $ ) {
	"use strict";

	/**
	 * Check empty status on link inputs.
	 */
	$( document ).on( "input.brix_link", ".brix-link-input-wrapper input", function() {
		if ( $( this ).val() !== "" ) {
			$( this ).parent().addClass( "brix-not-empty" );
		}
		else {
			$( this ).parent().removeClass( "brix-not-empty" );
		}
	} );

	/**
	 * Click on a link control.
	 */
	$.brixf.delegate( ".brix-link-ctrl-btn", "click", "link", function() {
        var key = "brix-link",
            ctrl = $( this ),
            ctrl_wrapper = $( this ).parents( '.brix-link-ctrl' ).first(),
            data = {
                "url": $( "[data-link-url]", ctrl_wrapper ).val(),
                "target": $( "[data-link-target]", ctrl_wrapper ).val(),
                "rel": $( "[data-link-rel]", ctrl_wrapper ).val(),
                "title": $( "[data-link-title]", ctrl_wrapper ).val(),
                "class": $( "[data-link-class]", ctrl_wrapper ).val(),
            };

        var modal = new $.brixf.modal( key, data, {
        	simple: true,

        	close: function() {
        		$( window ).off( "keydown.brix_link" );
        	},
			save: function( data, after_save, nonce ) {
				$( "[data-link-url]", ctrl_wrapper ).val( data["url"] );
				$( "[data-link-target]", ctrl_wrapper ).val( data["target"] );
				$( "[data-link-rel]", ctrl_wrapper ).val( data["rel"] );
				$( "[data-link-title]", ctrl_wrapper ).val( data["title"] );
				$( "[data-link-class]", ctrl_wrapper ).val( data["class"] );

				ctrl.removeClass( "brix-link-on" );

				if ( data["url"] != "" ) {
					ctrl.addClass( "brix-link-on" );
				}
			}
		} );

        modal.open( function( content, key, _data ) {
			var modal_data = {
				"action": "brix_link_modal_load",
				"nonce": ctrl_wrapper.attr( "data-nonce" ),
				"data": _data
			};

			var origin = ".brix-modal-container[data-key='" + key + "']";

			if ( _data["rel"] || data["title"] || data["class"] ) {
				$( origin ).addClass( "brix-link-modal-expanded" );
			}

			$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

			$( window ).off( "keydown.brix_link" );
			$( window ).on( "keydown.brix_link", function( e ) {
				if ( e.which == 9 ) {
					$( '.brix-modal-container[data-key="brix-link"]' ).addClass( 'brix-link-modal-expanded' );
					$( window ).off( "keydown.brix_link" );

					return false;
				}
			} );

			$.post(
				ajaxurl,
				modal_data,
				function( response ) {
					response = $( response );

					$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
					content.html( response );

					setTimeout( function() {
						$.brixf.ui.build();
					}, 1 );
				}
			);
		} );

		return false;
	} );

	// $.brixf.delegate( ".brix-link-trigger", "click", "link", function() {
	// 	$( '.brix-modal-container[data-key="brix-link"]').addClass( 'brix-link-modal-expanded' );

	// 	return false;
	// } );

	/**
	 * Check if a string represents a URL.
	 */
	function brix_is_url( s ) {
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

		return regexp.test( s );
	}

	$.brixf.ui.add( ".brix-link-input-wrapper input", function() {
		$( this ).trigger( "input.brix_link" );
	} );

	$.brixf.ui.add( ".brix-link-url-wrapper [name='url']", function() {
		var nonce = $( this ).attr( "data-nonce" );

		$( this ).selectize( {
			plugins: [],
			valueField: "id",
			labelField: "text",
			searchField: [ "text" ],
			dropdownParent: "body",
			create: true,
			createOnBlur: true,
			maxItems: 1,
			load: function( query, callback ) {
				if ( ! query.length || brix_is_url( query ) ) {
					return callback();
				}

				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					data: {
						action: "brix_link_search_entries",
						search: query,
						nonce: nonce
					},
					error: function() {
						callback();
					},
					success: function( res ) {
						callback( $.parseJSON( res ) );
					}
				} );
			},
			render: {
				item: function( item, escape ) {
					var html = '<div>';

					if ( item.spec && item.spec !== "" ) {
						html += '<span>' + escape( item.spec ) + '</span>';
					}

					html += escape( item.text );
					html += '</div>';

					return html;
				},
				option: function( item, escape ) {
					var html = '<div>';

					if ( item.spec && item.spec !== "" ) {
						html += '<span>' + escape( item.spec ) + '</span>';
					}

					html += escape( item.text );
					html += '</div>';

					return html;
				},
				option_create: function(data, escape) {
					return '<div class="brix-link-create create">' + brix_framework.link.create + '</div>';
				}
			}
		} );

		$( this ).get( 0 ).selectize.focus();
	} );

} )( jQuery );
