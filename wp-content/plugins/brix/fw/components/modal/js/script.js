( function( $ ) {
	"use strict";

	/* Custom component namespace. */
	var namespace = "modal";

	/**
	 * Remove the modal when clicking on its background.
	 *
	 * @return {boolean}
	 */
	// $.brixf.delegate( ".brix-modal-container", "click", namespace, function( e ) {
	// 	if ( $( e.target ).is( ".brix-modal-container" ) ) {
	// 		$( this ).remove();
	// 		$( "body" ).removeClass( "brix-modal-open" );

	// 		return false;
	// 	}
	// } );

	/**
	 * Remove the modal when clicking on its close button.
	 *
	 * @return {boolean}
	 */
	$.brixf.delegate( ".brix-modal-close", "click", namespace, function( e ) {
		$( this ).parents( ".brix-modal-container" ).first().data( "brix-modal" ).close();

		return false;
	} );

	/**
	 * Remove the foremost modal when pressing the ESC key.
	 *
	 * @return {boolean}
	 */
	$.brixf.key( "esc", function() {
		var modals = $( ".brix-modal-container" );

		if ( modals.length ) {
			modals.last().data( "brix-modal" ).close();

			return false;
		}
	} );

	/**
	 * Modal window.
	 *
	 * @param {String} key The modal key.
	 * @param {Object} data The data supplied to the modal window when opening it.
	 * @param {Object} config The configuration object.
	 */
	$.brixf.modal = function( key, data, config ) {
		config = $.extend( {
			/* Callback function fired after the modal is saved. */
			save: function() {},

			/* Callback function fired after the modal is closed. */
			close: function() {},

			/* Additional CSS class to be passed to the modal container. */
			class: "",

			/* Wait for the save function to be completed before closing the modal. */
			wait: false,

			/* Set to true if the modal is reduced in size. */
			simple: false,
		}, config );

		var self = this;

		self.config = config;

		/**
		 * Remove unnecessary Ui components.
		 */
		this.adjust = function() {
			$( ".mce-inline-toolbar-grp, .mce-tooltip" ).each( function() {
				// Clear TinyMCE panels
				if ( ! this.style.display ) {
					$( this ).remove();
				}
			} );
		};

		/**
		 * Close the modal.
		 */
		this.close = function() {
			config.close();

			$( ".brix-modal-container[data-key='" + key + "']" ).nextAll( ".brix-modal-container" ).remove();
			$( ".brix-modal-container[data-key='" + key + "']" ).remove();

			self.adjust();

			var modals = $( ".brix-modal-container" );

			if ( ! modals.length ) {
				$( "body" ).removeClass( "brix-modal-open" );
			}

			$( window ).trigger( "brix-modal-close" );
		};

		/**
		 * Close the modal and serialize its contents.
		 *
		 * @param {Object} data The modal serialized data.
		 */
		this.save = function( data ) {
			var origin = ".brix-modal-container[data-key='" + key + "']",
				save_btn = origin + " .brix-modal-footer .brix-save",
				nonce = $( save_btn ).attr( "data-nonce" );

			if ( config.wait ) {
				config.save( data, this.close, nonce );
			}
			else {
				config.save( data, null, nonce );
				this.close();
			}
		};

		/**
		 * Open the modal.
		 *
		 * @param {Function} content The function that populates the modal content.
		 */
		this.open = function( content ) {
			if ( typeof content !== "function" ) {
				throw new Error( "Content is not a function." );
			}

			self.adjust();

			// self.scroll = $( window ).scrollTop();
			var origin = ".brix-modal-container[data-key='" + key + "']";

			$( origin ).remove();

			var modal_class = config.class;

			if ( config.simple ) {
				modal_class += " brix-modal-container-simple";
			}

			var html = '<div class="brix-modal-container ' + modal_class + '" data-key="' + key + '">';
				html += '<div class="brix-modal-wrapper">';
					html += '<a class="brix-modal-close" href="#"><i data-icon="brix-modal-close" class="brix-icon brix-component" aria-hidden="true"></i></a>';

					html += '<div class="brix-modal-wrapper-inner">';
					html += '</div>';
				html += '</div>';
			html += '</div>';

			html = $( html );

			if ( ! $( "body" ).hasClass( "brix-modal-open" ) ) {
				html.appendTo( $( "#brix-modals-container" ) );
				$( "body" ).addClass( "brix-modal-open" );
			}
			else {
				$( ".brix-modal-container" ).last().after( html );
			}

			$( ".brix-modal-container" ).last().data( "brix-modal", self );

			content(
				$( origin + " .brix-modal-wrapper-inner" ),
				key,
				data
			);
		};

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			var origin = ".brix-modal-container[data-key='" + key + "']",
				save_btn = origin + " .brix-modal-footer .brix-save",
				form = origin + " form",
				modal_namespace = namespace + "-form-" + key;

			$.brixf.undelegate( "submit", modal_namespace );
			$.brixf.undelegate( "click", modal_namespace );

			$.brixf.delegate( save_btn, "click", modal_namespace, function() {
				$( form ).trigger( "submit." + modal_namespace );

				return false;
			} );

			$.brixf.delegate( form, "submit", modal_namespace, function() {
				$.brixSaveRichTextareas( this );

				brix_idle_button( $( save_btn ) );

				self.save( $( this ).serializeObject() );

				$.brixf.undelegate( "submit", modal_namespace );
				$.brixf.undelegate( "click", modal_namespace );

				return false;
			} );
		};

		this.init();
	};
} )( jQuery );
