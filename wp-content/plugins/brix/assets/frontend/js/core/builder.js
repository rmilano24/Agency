( function( $ ) {
	"use strict";

	/**
	 * Builder frontend component.
	 */
	$.brix = function() {

		var self = this,
			mobile = $( "body" ).hasClass( "brix-mobile" );

		self.$brix = $( ".brix-builder" );

		self.injector = new SVGInjector( {
			evalScripts: false
		} );

		/**
		 * Extend an options object directly from the global namespace.
		 */
		this.extend_options = function( options, key ) {
			if ( typeof window[key] !== "undefined" ) {
				options = $.extend( {}, options, window[key] );
			}

			return options;
		};

		/**
		 * Extend an option parameter directly from the global namespace.
		 */
		this.extend_option = function( option, key ) {
			if ( typeof window[key] !== "undefined" ) {
				option = window[key];
			}

			return option;
		};

		/**
		 * Register elements for visibility control.
		 */
		this.inview = function() {
			$.brixf.inview.register( ".brix-section", function( section ) {
				var video_container = $( ".brix-background-video-container", section );

				$.brix_load_images( $( ".brix-section-background-wrapper .brix-background-image", section ), {
					single: function( wrapper ) {
						setTimeout( function() {
							$( wrapper ).addClass( "brix-background-loaded" );
						}, 200 );
					}
				} );

				if ( $( "video", video_container ).length ) {
					if ( mobile && video_container.attr( "data-mobile-disabled" ) == "1" ) {
						return;
					}

					$( "video", video_container )[0].play();
				}

				section.trigger( "brix_section_inview" );
			} );

			$.brixf.inview.register( ".brix-section-column", function( column ) {
				var video_container = $( ".brix-background-video-container", column );

				$.brix_load_images( $( ".brix-column-background-wrapper .brix-background-image", column ), {
					single: function( wrapper ) {
						setTimeout( function() {
							$( wrapper ).addClass( "brix-background-loaded" );
						}, 200 );
					}
				} );

				if ( $( "video", video_container ).length ) {
					if ( mobile && video_container.attr( "data-mobile-disabled" ) == "1" ) {
						return;
					}

					$( "video", video_container )[0].play();
				}

				column.trigger( "brix_column_inview" );
			} );

			$.brixf.inview.register( ".brix-section-column-block", function( block ) {
				self.adjust_blocks( block );
				self.load_images( block );

				block.trigger( "brix_block_inview" );
			} );

			$.brixf.inview.register( ".brix-section-column-block-gallery .brix-block-preloaded-img", function( img ) {
				$.brix_load_images( img, {
					single: function( obj ) {
						obj.trigger( "brix-img-loaded" );
						obj.addClass( "brix-img-loaded" );
						self.adjust();

						obj.next().remove();
					}
				} );
			} );
		};

		/**
		 * Attempt to load all images in a block.
		 */
		this.load_images = function( block ) {
			if ( block.is( ".brix-section-column-block-gallery" ) ) {
				return;
			}

			var block_preloaded_images = $( ".brix-block-preloaded-img", block );

			if ( block_preloaded_images.length ) {
				$.brix_load_images( block_preloaded_images, {
					single: function( obj ) {
						obj.trigger( "brix-img-loaded" );
						obj.addClass( "brix-img-loaded" );
						self.adjust();

						obj.next().remove();
					},
					all: function() {
						self.adjust();
					}
				} );
			}

			if ( $( ".brix-icon[data-src]:not(.brix-block-preloaded-img)", block ).length ) {
				var icons = block[ 0 ].querySelectorAll( ".brix-icon[data-src]:not(.brix-block-preloaded-img)" );

				self.injector.inject(
					icons,
					function() {},
					function( svg ) {
						var w = parseInt( $( svg ).attr( "width" ), 10 ),
							h = parseInt( $( svg ).attr( "height" ), 10 );

						svg.setAttribute( "viewBox", "0 0 " + w + " " + h );

						setTimeout( function() {
							$( svg ).addClass( "brix-icon-loaded" );
						}, 1 );

						$( svg ).trigger( "brix-icon-loaded" );
					}
				);
			}
		};

		/**
		 * Adjust blocks.
		 */
		this.adjust_blocks = function( block ) {
			if ( $( block ).is( ".brix-fit-vids" ) ) {
				$( block ).fitVids();
			}
			else {
				if ( $( ".brix-fit-vids" ).length ) {
					$( ".brix-fit-vids" ).fitVids();
				}
			}
		};

		/**
		 * Stretch non-boxed sections.
		 */
		this.stretch_columns = function() {
			$( ".brix-section-column-stretch" ).each( function() {
				var section = $( this ).parents( ".brix-extended-boxed" ).first(),
					column = $( this ),
					column_content = $( ".brix-section-column-spacing-wrapper", column ).first(),
					background = $( ".brix-background-wrapper", column ).first();

				if ( ! section.length ) {
					return;
				}


				if ( $( column ).hasClass( "brix-section-column-column-stretch" ) ) {
					column_content.css( {
						"margin-left": "",
						"margin-right": "",
					} );
				}

				if ( background.length ) {
					background.css( {
						"left": "",
						"right": "",
					} );
				}

				if ( column.is( ":first-child" ) ) {
					var shift = section.offset().left - column.offset().left;

					if ( background.length ) {
						background.css( {
							"left": shift,
						} );
					}

					if ( $( column ).hasClass( "brix-section-column-column-stretch" ) ) {
						column_content.css( {
							"margin-left": shift,
						} );
					}

				}

				if ( column.is( ":last-child" ) ) {
					var shift = ( $( window ).width() - ( column.offset().left + column.outerWidth() ) ) * -1;

					if ( background.length ) {
						background.css( {
							"right": shift,
						} );
					}

					if ( $( column ).hasClass( "brix-section-column-column-stretch" ) ) {
						column_content.css( {
							"margin-right": shift,
						} );
					}
				}
			} );
		};

		/**
		 * Stretch non-boxed sections.
		 */
		this.stretch_sections = function() {
			$( ".brix-extended-extended, .brix-extended-boxed" ).each( function() {
				var section = $( this ),
					window_width = $( window ).width();

				section.css( {
					"width": "",
					"padding-left": "",
					"padding-right": "",
					"margin-left": "",
					"margin-right": ""
				} );

				var width = section.outerWidth(),
					left_offset = section.offset().left,
					right_offset = window_width - width - left_offset;

				var off = self.extend_option( 0, "brix_extended_section_offset" ),
					off_position = self.extend_option( "left", "brix_extended_section_offset_position" ),
					section_css = {
						"width": window_width - off
					};

				switch ( off_position ) {
					case "left":
						section_css["margin-left"] = -(left_offset - off);
						section_css["margin-right"] = -right_offset;
						break;
					case "right":
						section_css["margin-right"] = -(right_offset - off);
						section_css["margin-left"] = -left_offset;
						break;
				}

				if ( section.is( ".brix-extended-boxed" ) ) {
					switch ( off_position ) {
						case "left":
							section_css["padding-left"] = left_offset - off;
							section_css["padding-right"] = right_offset;
							break;
						case "right":
							section_css["padding-right"] = right_offset - off;
							section_css["padding-left"] = left_offset;
							break;
					}
				}

				section.css( section_css );
			} );
		};

		/**
		 * Equalize column heights.
		 *
		 * Safari height 100% fix
		 */
		this.adjust_columns = function() {
			var isSafariOrFirefox = $( "body" ).hasClass( 'brix-ua-safari' );

			if ( ! mobile && ! isSafariOrFirefox ) {
				return;
			}

			$( ".brix-section-row" ).each( function( i ) {
				var columns = $( ".brix-section-column", this );

				if ( columns.length <= 1 ) {
					return;
				}

				columns.css( "height", "" );

				var row = $( this ),
					row_width = row.outerWidth(),
					column_groups = [],
					group_index = 0,
					count = 0,
					max = 0;

				columns.each( function() {
					var size = parseInt( $( this ).outerWidth(), 10 ),
						new_count = count + size;

					if ( $( this ).outerHeight() > max ) {
						max = $( this ).outerHeight();
					}

					if ( ! column_groups[group_index] ) {
						column_groups[group_index] = {
							max: 0,
							columns: []
						};
					}

					column_groups[group_index].max = max;
					column_groups[group_index].columns.push( $( this ) );

					if ( new_count >= row_width ) {
						group_index++;
						max = 0;
						count = 0;
					}
				} );

				$.each( column_groups, function( i, group ) {
					if ( ! group ) {
						return;
					}

					$.each( group.columns, function() {
						if ( group.columns.length === 1 ) {
							return;
						}

						if ( ! row.is( ".brix-section-column-height-fluid" ) ) {
							$( this ).css( "height", group.max );
						}
					} );
				} );
			} );
		};

		/**
		 * Adjust column carousels.
		 */
		this.adjust_column_carousels = function() {
			$( '.brix-section-column-carousel .brix-section-column-inner-wrapper' ).each( function() {
				if ( $( this ).data( 'flickity' ) ) {
					var max = 0;

					$( ".is-selected", this ).each( function() {
						if ( $( this ).outerHeight() > max ) {
							max = $( this ).outerHeight();
						}
					} );

					$( ".flickity-viewport", this ).css( "height", max );
				}
			} );
		}

		/**
		 * Adjust elements when resizing or scrolling.
		 */
		this.adjust = function() {
			self.stretch_sections();
			self.stretch_columns();
			self.adjust_blocks();
			self.adjust_column_carousels();
			self.adjust_columns();

			self.$brix.trigger( "adjust.brix" );
		};

		/**
		 * Equalize section heights.
		 */
		this.equalize_sections = function() {
			$( ".brix-section:has(.brix-background-video-container)" ).each( function() {
				var max = 0;

				$( this ).css( "height", "" );

				if ( $( this ).outerHeight() > max ) {
					max = $( this ).outerHeight();
				}

				$( this ).css( "height", max );
			} );

			$( ".brix-section.brix-fluid-section" ).each( function() {
				var subsections = $( ".brix-subsection", this );

				if ( subsections.length <= 1 ) {
					return;
				}

				var section_width = $( this ).outerWidth(),
					max = 0,
					current_width = 0;

				subsections.each( function() {
					$( this ).css( "height", "" );

					current_width += $( this ).outerWidth();

					if ( $( this ).outerHeight() > max ) {
						max = $( this ).outerHeight();
					}
				} );

				if ( current_width > section_width ) {
					return;
				}

				subsections.each( function() {
					if ( $( this ).is( ".brix-subsection-type-standard" ) ) {
						$( this ).css( "height", max );
					}
					else {
						$( ".brix-section-column", this ).css( "height", max );
					}
				} );
			} );
		};

		/**
		 * Events binding.
		 */
		this.bind = function() {
			$( window ).off( "resize.brix" );
			$( window ).off( "load.brix" );

			$( window ).on( "resize.brix", this.adjust );
			$( window ).on( "load.brix", this.load );

			/**
			 * Preloaded images entrance effect.
			 */
			$( document ).on( "brix-img-loaded", ".brix-block-image-img", function() {
				if ( $( this ).prev().is( ".brix-block-image-img-placeholder" ) ) {
					$( this ).prev().remove();

					// Safari height 100% fix
					setTimeout( function() {
						self.adjust_columns();
					}, 1 );
				}
			} );

			// $( document ).on( "switched.brixf.accordion", ".brix-accordion", self.adjust_columns );
		};

		/**
		 * Window load.
		 */
		this.load = function() {
			self.adjust();
		};

		/**
		 * Builder init.
		 */
		this.init = function() {
			$.brixf.ui.add( ".brix-builder", function() {
				self.bind();
				self.load();
				self.inview();
			} );
		};

		this.init();

	};

	window.brix = new $.brix();

} )( jQuery );