( function( $ ) {
	"use strict";

	function Brix_FrontendEditing() {

		var self = this;

		// Reference to the element currently being edited.
		self.$ref = null;

		// Fake element that overlaps the page content.
		self.$el = null;

		// Reference to the element currently being hovered.
		self.$hover_ref = null;

		// Reference to the hover element.
		self.$hover_el = $( ".brix-frontend-editing-el-hover" );

		// Fake elements markups.
		self.select_element = '<div class="brix-frontend-editing-el-selected"></div>';

		/**
		 * Update.
		 */
		this.update = function( redraw ) {
			window.parent.brix_frontend_editing.update( redraw );
		};

		/**
		 * Enter an element's space.
		 */
		this.enter = function( e ) {
			self.clear_hover( false );

			if ( self.$hover_ref === null && e ) {
				var $ref = false;

				if ( ! $( e.target ).is( "[data-frontend-editing]" ) ) {
					if ( $( e.target ).parents( "[data-frontend-editing]" ).length ) {
						$ref = $( e.target ).parents( "[data-frontend-editing]" ).first();
					}
				}
				else {
					$ref = $( e.target );
				}

				if ( $ref === false ) {
					return;
				}

				if ( $ref.parents( ".brix-frontend-editing-no-hover" ).length ) {
					return;
				}

				self.$hover_ref = $ref;
			}

			if ( ! self.$hover_el || ! self.$hover_ref ) {
				return;
			}

			self.$hover_el.addClass( "brix-frontend-editing-el-hover-active" );

			var context = '';

			if ( self.$hover_ref.hasClass( "brix-section-column-block" ) ) {
				context = "block";
			}
			else if ( self.$hover_ref.hasClass( "brix-section-column" ) ) {
				context = "column";
			}
			else if ( self.$hover_ref.hasClass( "brix-section-row" ) ) {
				context = "row";
			}
			else if ( self.$hover_ref.hasClass( "brix-section" ) ) {
				context = "section";
			}

			self.$hover_el.attr( "data-context", context );

			var coords_ref = {
				top: self.$hover_ref.offset().top,
				left: self.$hover_ref.offset().left,
				width: self.$hover_ref.outerWidth(),
				height: self.$hover_ref.outerHeight()
			};

			var coords_el = {
				top: self.$hover_el.offset().top,
				left: self.$hover_el.offset().left,
				width: self.$hover_el.outerWidth(),
				height: self.$hover_el.outerHeight()
			};

			if ( JSON.stringify( coords_ref ) != JSON.stringify( coords_el ) ) {
				self.$hover_el.css( {
					top: self.$hover_ref.offset().top,
					left: self.$hover_ref.offset().left,
					width: self.$hover_ref.outerWidth(),
					height: self.$hover_ref.outerHeight()
				} );
			}
		};

		/**
		 * Leave an element's space.
		 */
		this.clear_hover = function( redraw ) {
			if ( typeof redraw === "undefined" ) {
				redraw = true;
			}

			self.$hover_el.removeClass( "brix-frontend-editing-el-hover-active" );
			self.$hover_el.attr( "data-context", "" );

			if ( redraw ) {
				self.$hover_el.css( {
					top: "",
					left: "",
					width: "",
					height: ""
				} );
			}

			self.$hover_ref = null;
		};

		/**
		 * Position the fake element.
		 */
		this.position = function() {
			self.enter();

			if ( self.$el == null || self.$ref == null ) {
				return;
			}

			$( ".brix-frontend-guide" ).remove();

			var left_guide = $( "<span class='brix-frontend-guide brix-frontend-vertical-guide'></span>" ),
				right_guide = $( "<span class='brix-frontend-guide brix-frontend-vertical-guide'></span>" ),
				top_guide = $( "<span class='brix-frontend-guide brix-frontend-horizontal-guide'></span>" ),
				bottom_guide = $( "<span class='brix-frontend-guide brix-frontend-horizontal-guide'></span>" );

			left_guide.insertAfter( self.$el );
			right_guide.insertAfter( self.$el );
			top_guide.insertAfter( self.$el );
			bottom_guide.insertAfter( self.$el );

			var top = self.$ref.offset().top,
				left = self.$ref.offset().left,
				width = self.$ref.outerWidth(),
				height = self.$ref.outerHeight(),
				right = left + width,
				bottom = top + height,
				v_scroll = -1 * $( window ).scrollTop(),
				h_scroll = -1 * $( window ).scrollLeft();

			setTimeout( function() {
				left_guide.css( "left", left + h_scroll );
				right_guide.css( "left", right + h_scroll - 1 );
				top_guide.css( "top", top + v_scroll );
				bottom_guide.css( "top", bottom + v_scroll - 1 );

				self.$el.css( {
					top: top,
					left: left,
					width: width,
					height: height
				} );
			}, 5 );
		};

		/**
		 * Activate selection on a particular element.
		 */
		this.detect = function() {
			if ( self.$ref ) {
				if ( self.$ref[0] === this ) {
					self.clear();
					return false;
				}
			}

			self.clear();

			var context = "",
				count = parseInt( $( this ).attr( "data-count" ) ),
				data = {
					special: false
				};

			if ( $( this ).hasClass( "brix-section-column-block" ) ) {
				context = "block";

				count = $( this ).index( ".brix-section-column-block" );
			}
			else if ( $( this ).hasClass( "brix-section-column" ) ) {
				context = "column";

				count = $( this ).index( ".brix-section-column" );
			}
			else if ( $( this ).hasClass( "brix-section-row" ) ) {
				context = "row";

				count = $( this ).index( ".brix-section-row" );
			}
			else if ( $( this ).hasClass( "brix-section" ) ) {
				context = "section";

				count = $( this ).index( ".brix-section" );
			}

			if ( $( this ).parents( ".brix-subsection-type-special" ).length ) {
				data.special = true;
			}

			window.parent.brix_frontend_editing.change_context( context, count, data );

			var $el = $( self.select_element );

			self.$ref = $( this );

			if ( self.$ref.parents( ".brix-frontend-editing-no-hover" ).length ) {
				return false;
			}

			self.$el = $el;

			$( "body" ).append( $el );

			self.position();

			return false;
		};

		/**
		 * Clear selection.
		 */
		this.clear = function() {
			self.clear_hover();

			window.parent.brix_frontend_editing.change_context( "", 0 );

			$( ".brix-frontend-editing-el-selected" ).remove();
			$( ".brix-frontend-editing-element-selected" ).removeClass( "brix-frontend-editing-element-selected" );
			$( ".brix-frontend-guide" ).remove();

			self.$ref = null;
			self.$el = null;
		};

		/**
		 * Route selection.
		 */
		this.select = function( context, count ) {
			var data = {
				special: false
			};

			switch ( context ) {
				case "section":
					self.detect.apply( $( ".brix-section" ).eq( count ).get( 0 ) );
					break;
				case "row":
					self.detect.apply( $( ".brix-section-row" ).eq( count ).get( 0 ) );
					break;
				case "column":
					self.detect.apply( $( ".brix-section-column" ).eq( count ).get( 0 ) );
					break;
				case "block":
					self.detect.apply( $( ".brix-section-column-block" ).eq( count ).get( 0 ) );
					break;
				default:
					break;
			}

			if ( self.$ref.parents( ".brix-subsection-type-special" ).length ) {
				data.special = true;
			}

			window.parent.brix_frontend_editing.change_context( context, count, data );
		};

		/**
		 * Adding new blocks.
		 */
		this.add_block = function() {
			var block = $( this ).parents( ".brix-section-column-block" ).first(),
				column = $( this ).parents( ".brix-section-column" ).first(),
				count = column.index( ".brix-section-column" ),
				index = null;

			if ( block ) {
				index = block.index();
			}

			window.parent.brix_frontend_editing.add_block( count, index );

			return false;
		};

		/**
		 * Add a new section a the bottom of the builder contents.
		 */
		this.add_section = function() {
			var sections = $( ".brix-section" );

			if ( sections.length ) {
				window.parent.brix_frontend_editing.add_section( sections.length - 1 );
			}
			else {
				window.parent.brix_frontend_editing.add_section();
			}
		};

		/**
		 * UI adjustments.
		 */
		this.adjustments = function() {
			// Make sure that the highlight and selection are correctly positioned.
			$( window ).on( "resize scroll", self.position );

			// Carousel start.
			$( document ).on( "dragStart.flickity", ".brix-section-column-carousel .brix-section-column-inner-wrapper", function() {
				self.clear();
				$( this ).addClass( "brix-frontend-editing-no-hover" );
			} );

			// Carousel end.
			$( document ).on( "settle.flickity", ".brix-section-column-carousel .brix-section-column-inner-wrapper", function() {
				self.clear();
				$( this ).removeClass( "brix-frontend-editing-no-hover" );
			} );

			// When switching tabs, resize the highlight.
			$( document ).on( "switched.brixf.tabs", ".brix-tabs.brix-component", self.position );

			// When switching accordion toggles, resize the highlight.
			$( document ).on( "switched.brixf.accordion", ".brix-accordion.brix-component", self.position );

			// When lazy loading images, resize their selection.
			$( document ).on( "brix-img-loaded", ".brix-block-image-img", self.position );

			// When lazy loading icons, resize their selection.
			$( document ).on( "brix-icon-loaded", ".brix-icon[data-src]", self.position );

			// Add section.
			$( document ).on( "click", ".brix-frontend-editing-add-section", self.add_section );
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			$( document ).on( "click", "[data-frontend-editing]", this.detect );

			$( document ).on( "mousemove", "[data-frontend-editing]", this.enter );
			$( document ).on( "mouseleave", "[data-frontend-editing]", this.clear_hover );

			$( document ).on( "click", "[data-frontend-editing] .brix-frontend-add-block", this.add_block );

			if ( $( "body.woocommerce-active" ).length ) {
				/* Trigger a cart refresh if WooCommerce is used. */

				$( window ).on( "brix_ready", function() {
					$( document.body ).trigger( 'wc_fragment_refresh' );
				} );
			}

			self.adjustments();
		};

		/**
		 * Component initialization.
		 */
		this.init = function() {
			this.bind();
		};

		this.init();

	}

	window.brix_frontend_editing = new Brix_FrontendEditing();

} )( jQuery );