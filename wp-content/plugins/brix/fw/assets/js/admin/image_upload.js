( function( $ ) {
	"use strict";

	/**
	 * Adding the multiple image upload sortable container to the UI building queue.
	 */
	$.brixf.ui.add( ".brix-image-upload[data-multiple][data-sortable] .brix-image-placeholder-container", function() {
		$( this ).sortable( {
			items: "> .brix-image-placeholder",
			update: function( event, ui ) {
				var upload = $( event.target ).parents( ".brix-image-upload" ).first(),
					input = $( "input[data-id]", upload ),
					values = [];

				$( ".brix-image-placeholder", upload ).each( function() {
					values.push( $( "img[data-id]", $( this ) ).attr( "data-id" ) );
				} );

				input.val( values.join( "," ) );
			}
		} );
	} );

	/**
	 * When clicking on an image upload remove button, remove its the previously selected image.
	 */
	$.brixf.delegate( ".brix-image-upload .brix-upload-remove", "click", "image_upload", function() {
		var upload = $( this ).parents( ".brix-image-upload" ).first(),
			container = $( ".brix-image-placeholder-container", upload ),
			multiple = upload.attr( "data-multiple" ) !== undefined,
			input = $( "input[data-id]", upload );

		if ( multiple ) {
			$( this ).parents( ".brix-image-placeholder" ).first().remove();

			var remaining_placeholders = $( ".brix-image-placeholder", upload );

			if ( ! remaining_placeholders.length ) {
				upload.removeClass( "brix-image-uploaded" );
				input.val( "" );

				var template = $( "script[type='text/template'][data-template='brix-image-placeholder']" );
				container.append( $.brixf.template( template, {
					"url": "",
					"id": ""
				} ) );
			}
			else {
				var values = [];

				remaining_placeholders.each( function() {
					values.push( $( "img[data-id]", $( this ) ).attr( "data-id" ) );
				} );

				input.val( values.join( "," ) );
			}
		}
		else {
			input.val( "" );
			$( "img", upload ).attr( "src", "" );
			upload.removeClass( "brix-image-uploaded" );
		}

		return false;
	} );

	/**
	 * Remove all uploaded attachments.
	 */
	$.brixf.delegate( ".brix-image-upload .brix-remove-all-action", "click", "image_upload", function() {
		var container = $( this ).parents( ".brix-image-upload" ).first(),
			images = $( ".brix-image-placeholder", container ),
			input = $( "input[data-id]", container );

		images.remove();
		container.removeClass( "brix-image-uploaded" );
		input.val( "" );

		return false;
	} );

	/**
	 * When clicking on an image upload Upload/Edit button, open a Media Library
	 * modal that allows the user to select an image to use.
	 */
	$.brixf.delegate( ".brix-image-upload .brix-edit-action, .brix-image-upload .brix-upload-action", "click", "image_upload", function() {
		var upload = $( this ).parents( ".brix-image-upload" ).first(),
			container = $( ".brix-image-placeholder-container", upload ),
			thumb_size = upload.attr( "data-thumb-size" ),
			multiple = upload.attr( "data-multiple" ) !== undefined,
			input = $( "input[data-id]", upload ).val();

		var media = new window.Brix_MediaSelector( {
			type: "image",
			multiple: multiple,
			select: function( selection ) {
				var template = $( "script[type='text/template'][data-template='brix-image-placeholder']" ),
					value = "",
					html = "";

				container.html( "" );

				if ( multiple ) {
					value = _.pluck( selection, "id" ).join( "," );

					$.each( selection, function() {
						var image_url = "";

						if ( this.sizes && this.sizes.full ) {
							image_url = this.sizes.full.url;
						}

						if ( this.sizes && this.sizes[thumb_size] ) {
							image_url = this.sizes[thumb_size].url;
						}

						container.append( $.brixf.template( template, {
							"url": image_url,
							"id": this.id
						} ) );
					} );
				}
				else {
					var image_url = "";

					value = selection.id;

					if ( selection.sizes && selection.sizes.full ) {
						image_url = selection.sizes.full.url;
					}
					else {
						image_url = selection.url;
					}

					if ( selection.sizes && selection.sizes[thumb_size] ) {
						image_url = selection.sizes[thumb_size].url;
					}

					container.append( $.brixf.template( template, {
						"url": image_url,
						"id": value
					} ) );
				}

				upload.addClass( "brix-image-uploaded" );
				$( "input[data-id]", upload ).val( value );
			}
		} );

		media.open( input.split( "," ) );

		return false;
	} );

} )( jQuery );