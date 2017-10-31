( function( $ ) {
	"use strict";

	/**
	 * Templates export call.
	 */
	function brix_templates_call_export( url ) {
		document.getElementById( "brix-template-export-frame" ).src = url;

		return false;
	}

	/**
	 * Templates export.
	 */
	$( document ).on( "click", ".brix-templates-export", function() {
		return brix_templates_call_export( $( this ).attr( "href" ) );
	} );

	/**
	 * Making sure to empty the drop area.
	 */
	function emptyTemplatesDropArea() {
		if ( $( ".brix-user-template-drop-wrapper" ).hasClass( "brix-import-active" ) ) {
			if ( window.brix_templates_dropzone ) {
				window.brix_templates_dropzone.removeAllFiles();
			}
		}
	}

	/**
	 * Templates import toggle.
	 */
	$( document ).on( "click", ".brix-templates-import", function() {
		$( ".brix-template-nav-item[data-nav='user']" ).trigger( "click" );
		$( ".brix-user-template-drop-wrapper" ).toggleClass( "brix-import-active" );

		$( window ).trigger( "brix-templates-import-toggle" );

		emptyTemplatesDropArea();

		return false;
	} );

	/**
	 * Close the template import drop area.
	 */
	$( document ).on( "click", ".brix-user-template-drop-wrapper-close", function() {
		$( ".brix-template-nav-item[data-nav='user']" ).trigger( "click" );
		$( ".brix-user-template-drop-wrapper" ).removeClass( "brix-import-active" );

		return false;
	} );

	/**
	 * Loading of the templates modal.
	 */
	$( window ).on( "brix-templates-modal-loaded", function() {
		window.brix_templates_dropzone = new Dropzone( "#brix-templates-dropzone" );

		window.brix_templates_dropzone.on( "dragenter", function() {
			window.brix_templates_dropzone.removeAllFiles();
		} );
	} );

	/**
	 * Templates upload.
	 */
	var BrixTemplateUpload = function() {

		var self = this;

		// this.sending = function() {
		// };

		// this.error = function() {
		// };

		this.success = function( response ) {
			response = $.parseJSON( response );

			$.get(
				ajaxurl,
				{
					"action": "brix_display_user_templates_list_content_ajax"
				},
				function( markup ) {
					var notice = $( "#brix-drop-wrapper-notice" ),
						container = $( ".brix-templates-wrapper[data-nav='user']" );

					container.addClass( "brix-uploading" );

					$( ".brix-user-template-content" ).html( markup );

					notice.attr( "data-type", response.type );
					notice.html( response.message );

					notice.addClass( "brix-active" );

					setTimeout( function() {
						notice.removeClass( "brix-active" );
					}, 4000 );

					$( "body" ).removeClass( "brix-has-templates" );
					container.addClass( "brix-user-template-empty" );

					if ( $( ".brix-user-template-content .brix-template" ).length ) {
						$( "body" ).addClass( "brix-has-templates" );
						container.removeClass( "brix-user-template-empty" );
					}

					container.removeClass( "brix-uploading" );
				}
			);
		};

		Dropzone.options.brixTemplatesDropzone = {
			uploadMultiple: true,
			acceptedFiles: "text/plain",
			// sending: function() {
			// 	self.sending();
			// },
			// error: function() {
			// 	self.error();
			// },
			success: function( file, response ) {
				self.success( response.trim() );
			},
			init: function() {
				this.on( "drop", function( file ) {
					this.removeAllFiles();
				} );
			}
		};
	};

	var brix_template_upload = new BrixTemplateUpload();

} )( jQuery );