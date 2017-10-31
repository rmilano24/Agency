( function( $ ) {
	"use strict";

	/**
	 * Adding the multiple attachment upload sortable container to the UI building queue.
	 */
	$.brixf.ui.add( ".brix-attachment-upload-container[data-multiple][data-sortable]", function() {
		$( this ).sortable( {
			items: "> .brix-attachment-placeholder",
			update: function( event, ui ) {
				var container = $( event.target ),
					input = $( "input[data-id]", container ),
					values = [];

				$( ".brix-attachment-placeholder", container ).each( function() {
					values.push( $( "[data-id]", $( this ) ).attr( "data-id" ) );
				} );

				input.val( values.join( "," ) );
			}
		} );
	} );

	/**
	 * When clicking on an attachment upload remove button, remove its the previously selected image.
	 */
	$.brixf.delegate( ".brix-attachment-placeholder .brix-upload-remove", "click", "attachment_upload", function() {
		var upload = $( this ).parents( ".brix-attachment-placeholder" ).first(),
			container = $( this ).parents( ".brix-attachment-upload-container" ).first(),
			multiple = container.attr( "data-multiple" ) !== undefined,
			input = $( "input[data-id]", container );

		upload.remove();

		if ( multiple ) {
			var remaining_placeholders = $( ".brix-attachment-placeholder", container );

			if ( ! remaining_placeholders.length ) {
				container.removeClass( "brix-attachment-uploaded" );
				input.val( "" );
			}
			else {
				var values = [];

				remaining_placeholders.each( function() {
					values.push( $( "[data-id]", $( this ) ).attr( "data-id" ) );
				} );

				input.val( values.join( "," ) );
			}
		}
		else {
			input.val( "" );
			$( "img", container ).attr( "src", "" );
			container.removeClass( "brix-attachment-uploaded" );
		}

		return false;
	} );

	/**
	 * Remove all uploaded attachments.
	 */
	$.brixf.delegate( ".brix-attachment-upload-container .brix-remove-all-action", "click", "attachment_upload", function() {
		var container = $( this ).parents( ".brix-attachment-upload-container" ).first(),
			attachments = $( ".brix-attachment-placeholder", container ),
			input = $( "input[data-id]", container );

		attachments.remove();
		container.removeClass( "brix-attachment-uploaded" );
		input.val( "" );

		return false;
	} );

	/**
	 * When clicking on an attachment upload Upload/Edit button, open a Media Library
	 * modal that allows the user to select an attachment to use.
	 */
	$.brixf.delegate( ".brix-attachment-upload-container .brix-edit-action, .brix-attachment-upload-container .brix-upload-action", "click", "attachment_upload", function() {
		var container = $( this ).parents( ".brix-attachment-upload-container" ).first(),
			type = container.attr( "data-type" ),
			thumb_size = container.attr( "data-thumb-size" ),
			multiple = container.attr( "data-multiple" ) !== undefined,
			input = $( "input[data-id]", container ).val();

		var media = new window.Brix_MediaSelector( {
			type: type,
			multiple: multiple,
			select: function( selection ) {
				var value = "",
					html = "",
					controls = $( ".brix-attachment-upload-action", container ),
					template = $( "script[type='text/template'][data-template='brix-attachment-placeholder']" );

				$( ".brix-attachment-placeholder", container ).remove();

				if ( multiple ) {
					value = _.pluck( selection, "id" ).join( "," );

					$.each( selection, function() {
						var extension = this.url.split(/[\\/]/).pop() + " (" + this.filesizeHumanReadable + ")",
							type = this.type;

						if ( this.subtype ) {
							type += "-" + this.subtype;
						}

						controls.before( $.brixf.template( template, {
							"type": type,
							"id": this.id,
							"title": this.title,
							"extension": extension,
							"url": this.url
						} ) );
					} );
				}
				else {
					value = selection.id;
					var extension = selection.url.split(/[\\/]/).pop() + " (" + selection.filesizeHumanReadable + ")",
						type = selection.type;

					if ( selection.subtype ) {
						type += "-" + selection.subtype;
					}

					controls.before( $.brixf.template( template, {
						"type": type,
						"id": value,
						"title": selection.title,
						"extension": extension,
						"url": selection.url
					} ) );
				}

				container.addClass( "brix-attachment-uploaded" );
				$( "input[data-id]", container ).val( value );
			}
		} );

		media.open( input.split( "," ) );

		return false;
	} );

} )( jQuery );