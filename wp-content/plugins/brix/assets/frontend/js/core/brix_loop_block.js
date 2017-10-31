( function( $ ) {
	"use strict";

	window.Brix_LoopBuilderBlock = function( config ) {

		var self = this;

		/**
		 * Configuration object.
		 *
		 * @type {Object}
		 */
		self.config = $.extend( {}, {
			"handle": "",
			"block_selector": "",
			"loop_selector": "",
			"pagination_selector": "",

			"reload_selector": "",
			"replacements": ""
		}, config );

		/**
		 * Loading state.
		 */
		self.loading = false;

		self.config.replacements = self.config.replacements.split(",");

		/**
		 * Perform the loading request.
		 */
		this.load = function( url, type, callback, block ) {
			if ( self.loading ) {
				return;
			}

			self.loading = true;

			var loading_class = "brix-" + config.handle + "-block-loading",
				loading_event = "brix-" + config.handle + "-block-loading",
				loaded_event = "brix-" + config.handle + "-block-loaded",
				pagination_flag = "brix_" + config.handle + "_ajax_pagination";

			block.addClass( loading_class );
			block.trigger( loading_event, [ type ] );

			var data = {};

			data[ pagination_flag ] = "1";

			$.ajax( {
				type: "POST",
				url: url,
				data: data,
				success: function( resp ) {
					if ( callback ) {
						callback( resp, function() {
							self.loading = false;
						} );
					}

					block.removeClass( loading_class );
					block.trigger( loaded_event );
				}
			} );
		};

		/**
		 * Reload the current block with new content.
		 */
		this.reload = function( href ) {
			var block = $( this ).parents( config.block_selector ).first(),
				block_count = block.attr( "data-count" ),
				block_style = block.attr( "data-style" );

			if ( ! href || typeof href !== "string" ) {
				href = $( this ).attr( "href" );
			}

			self.load(
				href,
				"reload",
				function( resp ) {
					var resp_block = $( config.block_selector + "[data-count='" + block_count + "']", resp ),
						custom_callback = "brix_" + config.handle + "_block_reload_" + block_style,
						reload_event = "brix_" + config.handle + "_block_reload";

					if ( typeof window[ custom_callback ] === "function" ) {
						window[ custom_callback ]( resp_block );

						$( window ).trigger( "resize" );
						$( window ).trigger( reload_event, [ block ] );
					}
					else {
						$.brix_load_images( $( "img", resp_block ), {
							all: function() {
								var html_loop = $( config.loop_selector, resp_block ).html(),
									pagination = $( config.pagination_selector, resp_block );

								$( config.loop_selector, block ).html( html_loop );

								if ( pagination.html() != "" ) {
									if ( $( config.pagination_selector, block ).length ) {
										$( config.pagination_selector, block ).replaceWith( pagination );
									}
									else {
										$( config.loop_selector, block ).after( pagination );
									}
								}
								else {
									$( config.pagination_selector, block ).remove();
								}

								if ( self.config.replacements.length ) {
									$.each( self.config.replacements, function( i, replacement ) {
										var ctn = $( replacement, resp_block );

										if ( ctn.html() != "" ) {
											if ( $( replacement, block ).length ) {
												$( replacement, block ).replaceWith( ctn );
											}
										}
										else {
											$( replacement, block ).remove();
										}
									} );
								}

								$( window ).trigger( "resize" );
								$( window ).trigger( reload_event, [ block ] );
							}
						} );
					}
				},
				block
			);

			return false;
		};

		/**
		 * Load new content and append it to the current block.
		 */
		this.append = function() {
			var block = $( this ).parents( config.block_selector ).first(),
				block_count = block.attr( "data-count" ),
				block_style = block.attr( "data-style" );

			self.load(
				$( this ).attr( "href" ),
				"append",
				function( resp ) {
					var resp_block = $( config.block_selector + "[data-count='" + block_count + "']", resp ),
						custom_callback = "brix_" + config.handle + "_block_append_" + block_style,
						append_event = "brix_" + config.handle + "_block_append";

					if ( typeof window[ custom_callback ] === "function" ) {
						window[ custom_callback ]( resp_block );

						$( window ).trigger( "resize" );
						$( window ).trigger( append_event, [ block ] );
					}
					else {
						var html_loop = $( config.loop_selector, resp_block ).html(),
							pagination = $( config.pagination_selector, resp_block );

						$.brix_load_images( $( "img", resp_block ), {
							all: function() {
								$( config.loop_selector, block ).append( html_loop );

								if ( pagination.html() != "" ) {
									$( config.pagination_selector, block ).replaceWith( pagination );
								}
								else {
									$( config.pagination_selector, block ).remove();
								}

								if ( self.config.replacements.length ) {
									$.each( self.config.replacements, function( i, replacement ) {
										var ctn = $( replacement, resp_block );

										if ( ctn.html() != "" ) {
											if ( $( replacement, block ).length ) {
												$( replacement, block ).replaceWith( ctn );
											}
										}
										else {
											$( replacement, block ).remove();
										}
									} );
								}

								$( window ).trigger( "resize" );
								$( window ).trigger( append_event, [ block ] );
							}
						} );
					}
				},
				block
			);

			return false;
		};

		/**
		 * Event bindings.
		 */
		this.bind = function() {
			var reload_selector = ".brix-" + config.handle + "-pagination-ajax_reload .brix-" + config.handle + "-block-pagination-wrapper a",
				append_selector = ".brix-" + config.handle + "-pagination-ajax_append .brix-" + config.handle + "-block-pagination-wrapper [data-load-more]",
				reload_event = "brix_" + config.handle + "_block_reload",
				append_event = "brix_" + config.handle + "_block_append";

			if ( config.reload_selector ) {
				reload_selector += "," + config.reload_selector;
			}

			$( document ).off( "click", reload_selector );
			$( document ).off( "click", append_selector );

			$( document ).on( "click", reload_selector, this.reload );
			$( document ).on( "click", append_selector, this.append );

			$( window ).off( reload_event + ".brix_loop" );
			$( window ).off( append_event + ".brix_loop" );

			$( window ).on( reload_event + ".brix_loop", function() { self.loading = false; } );
			$( window ).on( append_event + ".brix_loop", function() { self.loading = false; } );
		};

		/**
		 * Component initialization.
		 */
		this.init = function() {
			$.brixf.ui.add( config.block_selector, function() {
				self.bind();
			} );
		};

		this.init();

	};

} )( jQuery );