( function( html ) {
	"use strict";

	/**
	 * Preloader.
	 */
	var Agncy_Preloader = function() {

		/* Instance. */
		var self = this;

		/* Clickers. */
		this.clickers = [
			".menu-item a",
			".page_item a",
			"a.agncy-p-i-w_i",
			".brix-blog-block-pagination-wrapper a",
			"[data-agncy-paginate='static'] .brix-agncy_portfolio-block-pagination-wrapper a",
			".agncy-ps-s a",
			".agncy-mc .entry-title a",
			".agncy-site-title a",
			".agncy-sp-tl a",
			".agncy-entry-cl a",
			".agncy-e-c-pm a",
			".agncy-rm a",
			".agncy-sp-p a",
			".agncy-e-m a",
			".agncy-ph-bt a",
			"a.page-numbers",
			"a.agncy-ac-apl",
			".recentcomments a",
			".widget_categories a",
			".gallery-icon > a",
			".tiled-gallery-item > a",
			".brix-block-button",
			".agncy-job-apply-link"
		];

		/* Unclickers */
		this.unclickers = [
			".menu-item > a > span",
			".format-video.has-post-thumbnail .agncy-image > a",
			// "a.brix-block-load-more",
			".gallery-icon > a[href$='.jpeg']",
			".gallery-icon > a[href$='.jpg']",
			".gallery-icon > a[href$='.gif']",
			".gallery-icon > a[href$='.png']",
			".tiled-gallery-item > a[href$='.jpeg']",
			".tiled-gallery-item > a[href$='.jpg']",
			".tiled-gallery-item > a[href$='.gif']",
			".tiled-gallery-item > a[href$='.png']",
			"a.agncy-pfgallery-p-arr",
			"a.agncy-pfgallery-n-arr",
			"[data-agncy-paginate='ajax_reload'] a.page-numbers",
			".brix-blog-pagination-ajax_append .brix-blog-block-pagination-wrapper a",
			".brix-blog-pagination-ajax_reload .brix-blog-block-pagination-wrapper a",
			"a.agncy-ps-s-media-video-poster-image",
			".agncy-ph-sp-fi-video a",
			".agncy-ph-sp-open-video",
			".agncy-mn-n .menu-item-has-children a[href='#'], .agncy-mn-n .page_item_has_children a[href='#'],.agncy-mn-n .menu-item-has-children a:not([href]), .agncy-mn-n .page_item_has_children a:not([href])",
			".agncy-mn-n .menu-item-has-children a span, .agncy-mn-n .page_item_has_children a span,.agncy-mn-n .menu-item-has-children a:not([href]) span, .agncy-mn-n .page_item_has_children a:not([href]) span"
		];

		/* The stack of events to wait for the page to be officially loaded. */
		this.stack = [];

		/* Lock state. */
		this.lock = false;

		/* State. */
		this.state = "";

		/* Loader transition speed. */
		this.speed = 1000;

		/* Loader waiting speed. */
		this.waiting_speed = 250;

		/* Start transition microseconds. */
		this.start = 0;

		/* Waiting timeout. */
		this.timeout = 0;

		/* Loaded page status. */
		this.loaded = false;

		/**
		 * Check if a given URL points to the same page (anchor).
		 */
		this.is_current_page = function( url ) {
			var current_url = window.location.href.split( "#" );

			if ( typeof current_url[ 0 ] !== "undefined" ) {
				if ( ! url.endsWith( "/" ) ) {
					url += "/";
				}

				if ( url.indexOf( "#" ) === 0 || current_url.indexOf( url[ 0 ] ) > -1 ) {
					return true;
				}
			}

			return false;
		};

		/**
		 * Check if an URL points to an image file.
		 */
		this.is_img = function( url ) {
			return( url.match(/\.(jpeg|jpg|gif|png)$/) != null );
		};

		/**
		 * Set a state for the preloader transition.
		 */
		this.setState = function( state ) {
			switch ( state ) {
				case "loading":
					self.state = state;
					html.classList.add( "agncy-p-loading" );

					self.timeout = setTimeout( function() {
						self.setState( "waiting" );
					}, self.speed );

					break;
				case "waiting":
					self.state = state;
					html.classList.add( "agncy-p-waiting" );

					break;
				case "load":
					self.state = state;
					html.classList.add( "agncy-p-load" );

					break;
				default:
					html.classList.remove( "agncy-p-waiting" );
					self.state = "";

					break;
			}
		};

		/**
		 * Preload the resources from the given URL.
		 */
		this.load = function( target ) {
			var r = new XMLHttpRequest(),
				url = target.href;

			r.onreadystatechange = function() {
				if ( r.readyState != 4 || r.status != 200 ) {
					if ( r.status == 404 ) {
						self.jump( url );
					}
					else {
						return;
					}
				}

				var parser = new DOMParser(),
					doc = parser.parseFromString( r.responseText, "text/html" ),
					items = doc.querySelectorAll( ".agncy-ph-fi,.agncy-ph-sp-fi, .agncy-ps-s-active img" ),
					loaded = 0;

				if ( items.length ) {
					for ( var i = 0; i < items.length; i++ ) {
						self.preloadResource( items[ i ], function() {
							loaded++;

							if ( loaded === items.length ) {
								self.jump( url );
							}
						} );
					}
				}
				else {
					self.jump( url );
				}
			};

			r.open( "GET", url, true );
			r.send();

			var color = target.getAttribute( "data-color" );

			if ( ! color ) {
				color = agncy.preloader.color;
			}

			document.documentElement.setAttribute( "style", "background-color:" + color + ";color:" + color + ";" );

			self.start = ( new Date() ).getTime();
			self.setState( "loading" );
		};

		/**
		 * Preload a specific resource in page.
		 */
		this.preloadResource = function( item, callback ) {
			switch ( item.nodeName ) {
				case "IMG":
					if ( ! self.is_img( item.src ) ) {
						callback();

						return;
					}

					var image = new Image();

					image.onload = function() {
						callback();
					};

					image.src = item.src;
					break;
				default:
					if ( item.style[ "background-image" ] ) {
						var src = item.style[ "background-image" ];

						src = src.replace( 'url("', '' );
						src = src.replace( '")', '' );

						if ( src ) {
							if ( ! self.is_img( src ) ) {
								callback();

								return;
							}

							var image = new Image();

							image.onload = function() {
								callback();
							};

							image.src = src;
						}
					}

					break;
			}
		};

		/**
		 * Actually perform the page switch.
		 */
		this.jump = function( url ) {
			localStorage.setItem( "agncy_jump", true );

			if ( self.state == "waiting" ) {
				self.setState( "" );

				setTimeout( function() {
					document.location.href = url;
				}, self.waiting_speed );
			}
			else {
				var time = ( new Date() ).getTime(),
					sleep = self.start + self.speed - time;

				clearTimeout( self.timeout );

				setTimeout( function() {
					self.setState( "" );
					document.location.href = url;
				}, sleep );
			}
		};

		/**
		 * Check if an element matches a particular CSS selector.
		 */
		this.selectorMatches = function( el, selector ) {
			var p = Element.prototype,
				f = p.matches || p.webkitMatchesSelector || p.mozMatchesSelector || p.msMatchesSelector || p.oMatchesSelector || function(s) {
					return [].indexOf.call( document.querySelectorAll( s ), this ) !== -1;
				};

			return f.call( el, selector );
		};

		/**
		 * Click handler.
		 */
		this.handleClick = function( e ) {
			var target = e.target;

			while ( target && target.parentNode !== document ) {
				var wanted = self.selectorMatches( target, self.clickers.join( "," ) ),
					unwanted = self.selectorMatches( target, self.unclickers.join( "," ) );

				if ( unwanted ) {
					// e.preventDefault();

					return;
				}

				// console.log( wanted, target.href, self.is_current_page( target.href ) );

				if ( wanted && target.href != '' && target.href != '#' && ! self.is_current_page( target.href ) ) {
					if ( location.hostname != target.hostname ) {
						return;
					}

					e.preventDefault();

					if ( self.lock === false ) {
						self.lock = true;
						self.load( target );

						return;
					}
				}
				// else {
				// 	e.preventDefault();
				//
				// 	return;
				// }

				target = target.parentNode;

				if ( ! target ) {
					return;
				}
			}
        };

		/**
		 * Add a new element to the loading stack.
		 */
		this.add = function( key ) {
			this.stack[ key ] = false;
		};

		/**
		 * Complete an element of the stack.
		 */
		this.complete = function( key ) {
			this.stack[ key ] = true;

			if ( ! self.loaded && this.check() ) {
				this.show();

				self.loaded = true;
			}
		};

		/**
		 * Check if all elements in the stack have been loaded.
		 */
		this.check = function() {
			var loaded = true;

			for ( var j in this.stack ) {
				if ( this.stack[ j ] !== true ) {
					loaded = false;
					break;
				}
			}

			return loaded;
		};

		this.show = function() {
			setTimeout( function() {
				self.setState( "" );
				html.classList.remove( "agncy-p-load" );

				// setTimeout( function() {
				// 	html.removeAttribute( "style" );
				// }, self.speed );

				html.classList.add( 'agncy-p-loaded' );

				var event = new Event( 'agncy-p-loaded' );
    			html.dispatchEvent( event );
			}, 10 );
		};

		this.init = function() {
			if ( localStorage.getItem( "agncy_jump" ) ) {
				localStorage.removeItem( "agncy_jump" );

				self.setState( "load" );
			}
			else {
				self.setState( "load" );
			}
		};

		/* Init. */
		self.init();

		/* Event binding. */
		document.addEventListener( "click", self.handleClick );

		/* Scroll back to top before unloading the window. */
		window.onbeforeunload = function() {
			if ( localStorage.getItem( "agncy_jump" ) || typeof document.body.classList[ "single-agncy_project" ] == "undefined" ) {
				window.scrollTo( 0, 0 );
			}
		}
	};

	window.agncy_preloader = new Agncy_Preloader();

} )( document.documentElement );