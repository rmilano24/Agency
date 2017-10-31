( function( $ ) {
	"use strict";

	var BrixMedia = function() {

		var self = this;

		/**
		 * Drag starting position.
		 *
		 * @type {Number}
		 */
		this.start_index = 0;

		/**
		 * Drag ending position.
		 *
		 * @type {Number}
		 */
		this.end_index = 0;

		/**
		 * Get the field data object.
		 */
		this.get_data = function( field ) {
			return JSON.parse( $( "input[data-id]", field ).val() );
		};

		/**
		 * Set the field data object.
		 */
		this.set_data = function( field, data ) {
			$( "input[data-id]", field ).val( JSON.stringify( data ) );
		};

		/**
		 * Add an item to the field data object.
		 */
		this.add_item = function( field, item ) {
			var data = self.get_data( field );

			data.push( item );

			self.set_data( field, data );
		};

        /**
		 * Edit an item of the field data object.
		 */
		this.edit_item = function( field, index, item ) {
			var data = self.get_data( field );

            if ( typeof data[index] !== "undefined" ) {
                data[index] = item;
            }

			self.set_data( field, data );
		};

        /**
		 * Remove an item from the field data object.
		 */
        this.remove_item_data = function( field, index ) {
            var data = self.get_data( field );

            if ( typeof data[index] !== "undefined" ) {
                data = data.splice( index, 1 );
            }

            self.set_data( field, data );
        };

		/**
		 * Add a new item from the Media Library.
		 */
		this.add_media = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first(),
				container = $( ".brix-media-c", field ),
				template = $( "script[type='text/template'][data-template='brix_media-placeholder']" ),
				thumb_size = "thumbnail",
				data = self.get_data( field ),
				media = new window.Brix_MediaSelector( {
					multiple: true,
					select: function( selection ) {
						$.each( selection, function() {
							var image_url = "",
								selection_data = {};

							if ( this.sizes && this.sizes.full ) {
								image_url = this.sizes.full.url;
							}

							if ( this.sizes && this.sizes[thumb_size] ) {
								image_url = this.sizes[thumb_size].url;
							}

							selection_data = {
								"url": image_url,
								"source": "media"
							};

							container.append( $.brixf.template( template, selection_data ) );
							self.add_item( field, { "gallery_item_id": this.id, "source": "media" } );
						} );
					}
				} );

			media.open( [] );

			return false;
		};

		/**
		 * Add a new item from an external embed source.
		 */
		this.add_embed = function() {
			var ctrl = $( this ),
				field = $( this ).parents( ".brix-field-brix_media" ).first(),
				container = $( ".brix-media-c", field ),
				template = $( "script[type='text/template'][data-template='brix_media-embed-placeholder']" ),
				embed_data = {};

			if ( ! ctrl.is( "[data-add-embed]" ) ) {
				var data = self.get_data( field ),
					placeholder = $( this ),
					index = placeholder.index();

				embed_data = data[ index ];
			}

	        var modal = new $.brixf.modal( "brix-media-embed", embed_data, {
	        	simple: true,

				save: function( data, after_save, nonce ) {
					if ( typeof data.ev !== "undefined" ) {
						delete data.ev;
					}

					if ( data.url ) {
						data.source = "embed";

                        if ( typeof embed_data.url !== "undefined" ) {
                            placeholder.replaceWith( $.brixf.template( template, data ) );
                            self.edit_item( field, index, data );
                        }
                        else {
                            container.append( $.brixf.template( template, data ) );
    						self.add_item( field, data );
                        }
					}
				}
			} );

			modal.open( function( content, key, _data ) {
				var modal_data = {
					"action": "brix_media_embed_modal_load",
					"nonce": container.attr( "data-nonce" ),
					"data": _data
				};

				var origin = ".brix-modal-container[data-key='" + key + "']";
				$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

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
		};

		/**
		 * Remove an item from the media data.
		 */
		this.remove_item = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first(),
				placeholder = $( this ).parents( ".brix-image-placeholder" ).first(),
				index = placeholder.index();

			var data = self.get_data( field );

			data.splice( index, 1 );

			self.set_data( field, data );
			placeholder.remove();

			return false;
		};

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first();

			$( this ).sortable( {
				items: "> *",
				start: function( event, ui ) {
					self.start_index = $( ui.item ).index();
				},
				update: function( event, ui ) {
					var data = self.get_data( field );

					self.end_index = $( ui.item ).index();

					if ( self.start_index != self.end_index ) {
						data.splice( self.end_index, 0, data.splice( self.start_index, 1 )[0] );

						self.set_data( field, data );
					}
				}
			} );
		};

		/**
		 * Bind events.
		 */
		this.bind = function() {
			/* Initialize the sortable container. */
			$.brixf.ui.add( ".brix-field-brix_media .brix-media-c", this.init );

			/* Add items from the Media Library. */
			$.brixf.delegate( ".brix-field-brix_media [data-add-media]", "click", "brix_media", self.add_media );

			/* Add external embeds. */
			$.brixf.delegate( ".brix-field-brix_media [data-add-embed]", "click", "brix_media", self.add_embed );

			/* Edit an external embed. */
			$.brixf.delegate( ".brix-field-brix_media .brix-media-embed-placeholder", "click", "brix_media", self.add_embed );

			/* Remove a media item. */
			$.brixf.delegate( ".brix-field-brix_media .brix-upload-remove", "click", "brix_media", self.remove_item );
		};

		this.bind();

	};

	( new BrixMedia() );
} )( jQuery );