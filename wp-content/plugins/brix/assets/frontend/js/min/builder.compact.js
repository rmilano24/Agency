( function( $ ) {
	"use strict";

	$.brix_transitions = {};

	/**
	 * Check for transition support.
	 *
	 * @return {Boolean}
	 */
	$.brix_transitions.checkSupport = function() {
		var s = $( "body" ).get( 0 ).style,
			transitionSupport = "transition" in s || "WebkitTransition" in s || "MozTransition" in s || "msTransition" in s || "OTransition" in s;

		return transitionSupport;
	};

	/**
	 * Binds a callback upon finishing a CSS transition.
	 *
	 * @param {String|Object} element The DOM element or its CSS selector.
	 * @param {Function} callback Function to be executed at the end of the transition.
	 */
	$.brix_transitions.callback = function( element, callback ) {
		var mode = typeof element;

		/**
		 * Transition end callback.
		 *
		 * @param  {Object} obj The transitioning object.
		 * @param  {Object} e The event object.
		 */
		function evTransition( obj, e ) {
			obj = $( obj );

			if ( obj.get( 0 ) !== e.target ) {
				return false;
			}

			if ( typeof callback === "function" ) {
				callback( $( obj ), e );
			}

			return false;
		};

		var event_string = "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev";

		if ( ! $.brix_transitions.checkSupport() ) {
			if ( typeof callback === "function" ) {
				$( element ).each( function() {
					callback( $( this ) );
				} );
			}
		}
		else {
			switch ( mode ) {
				case "object":
					$( element ).each( function() {
						$( this ).one( event_string, function( e ) {
							return evTransition( this, e );
						} );
					} );

					break;
				case "string":
					$( document ).on( event_string, element, function( e ) {
						return evTransition( this, e );
					} );

					break;
				default:
					break;
			}
		}
	};
} )( jQuery );;
/**
 * Evolve Framework Javascript bootstrap
 * Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * Licensed under GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 */

"use strict";

if ( typeof jQuery === "undefined" ) {
	throw new Error( "jQuery is required." );
}

( function( $ ) {
	/**
	 * Evolve Framework master object. This object stores configuration used by
	 * the components.
	 *
	 * @type {Object}
	 */
	$.brixf = {
		/* The CSS class that identifies framework components. */
		componentID: ".brix-component",

		/* Default namespace used by events bound by components. */
		namespace: ".brixf",

		/* Complex UI components management. */
		ui: {
			/* Event name that identifies the creation of complex UI components. */
			event: "ui-build",

			signature: "brix-ui",

			components: [],

			components_count: 0,

			components_built: 0,

			/**
			 * Add a complex UI component to the building queue.
			 *
			 * @param  {String|Object} selector The UI component selector or object.
			 * @param  {Function} The UI component creation callback.
			 */
			add: function( selector, callback ) {
				$.brixf.ui.components.push( {
					"selector": selector,
					"callback": callback
				} );

				$.brixf.ui.components_count++;
			},

			/**
			 * Actually perform the building of the UI.
			 */
			do_build: function() {
				$.each( $.brixf.ui.components, function() {
					$.brixf.ui.components_built++;

					var selector = this.selector,
						callback = this.callback;

					var elements = $( selector );

					elements = elements.filter( function() {
						return $( this ).attr( "data-" + $.brixf.ui.signature ) !== "1";
					} );

					if ( ! elements.length ) {
						$.brixf.ui.end_build();
						return;
					}

					elements.attr( "data-" + $.brixf.ui.signature, "1" );
					callback.call( elements );
					$.brixf.ui.end_build();
				} );
			},

			/**
			 * Declare finished the building process.
			 */
			end_build: function() {
				if ( $.brixf.ui.components_count === $.brixf.ui.components_built ) {
					setTimeout( function() {
						$( ".brix-tab-container" ).addClass( "brix-tab-container-loaded" );
					}, 5 );
				}
			},

			/**
			 * This function is entitled to instantiate complex UI components that alter
			 * the DOM structure.
			 *
			 * Triggers a "ui-build.brixf" event on the $( window ) object.
			 */
			build: function() {
				$.brixf.ui.components_built = 0;

				$.brixf.ui.do_build();
				$( window ).trigger( $.brixf.resolveEventName( $.brixf.ui.event, $.brixf.namespace ) );
			},
		},

		/**
		 * Resolve a custom event namespace.
		 *
		 * @param  {String} ns The custom event namespace.
		 * @return {String}
		 */
		resolveNamespace: function( ns ) {
			if ( ns !== "" && ns.indexOf( "." ) === -1 ) {
				ns = "." + ns;
			}

			return $.brixf.namespace + ns;
		},

		/**
		 * Resolve a custom event name with proper namespace.
		 *
		 * @param  {String} ns The event name.
		 * @return {String}
		 */
		resolveEventName: function( event, ns ) {
			var name = "";

			$.each( event.split( " " ), function() {
				name += " " + this + $.brixf.resolveNamespace( ns );
			} );

			name = name.trim();

			return name;
		},

		/**
		 * Bind an event to an element with proper namespacing.
		 *
		 * @param  {jQuery}   element The DOM object.
		 * @param  {String}   event The event name.
		 * @param  {String}   ns The event namespace.
		 * @param  {Function} callback Function to be executed upon firing of the event.
		 */
		on: function( element, event, ns, callback ) {
			$( element ).on( $.brixf.resolveEventName( event, ns ), callback );
		},

		/**
		 * Bind a one-time event to an element with proper namespacing.
		 *
		 * @param  {jQuery}   element The DOM object.
		 * @param  {String}   event The event name.
		 * @param  {String}   ns The event namespace.
		 * @param  {Function} callback Function to be executed upon firing of the event.
		 */
		one: function( element, event, ns, callback ) {
			$( element ).one( $.brixf.resolveEventName( event, ns ), callback );
		},

		/**
		 * Bind an event to the document to be executed when firing on a specific set of elements with proper namespacing.
		 *
		 * @param  {String}   element The CSS selector string.
		 * @param  {String}   event The event name.
		 * @param  {String}   ns The event namespace.
		 * @param  {Function} callback Function to be executed upon firing of the event.
		 */
		delegate: function( element, event, ns, callback ) {
			$( document ).on( $.brixf.resolveEventName( event, ns ), element, callback );
		},

		/**
		 * Undelegate an event from the document element.
		 *
		 * @param  {String}   event The event name.
		 * @param  {String}   ns The event namespace.
		 */
		undelegate: function( event, ns ) {
			$( document ).off( $.brixf.resolveEventName( event, ns ) );
		},

		/**
		 * Templating function.
		 *
		 * @param  {String|Object} name The template key or the template object.
		 * @param  {Object} data The template data.
		 * @return {String} The template contents.
		 */
		template: function( name, data ) {
			if ( typeof _ === "undefined" ) {
				throw new Error( "Underscore is required." );
				return '';
			}

			var raw = name;

			if ( typeof name === "string" ) {
				raw = $( "script[type='text/template'][data-template='" + name + "']" );
			}

			if ( ! raw.length ) {
				throw new Error( "Template '" + name + "' not found." );
				return '';
			}

			var template_settings = {
					evaluate:    /<#([\s\S]+?)#>/g,
					interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
					escape:      /\{\{([^\}]+?)\}\}(?!\})/g
				},
				version = parseFloat( brix_framework.wp_version );

			if ( version < 4.5 ) {
				return _.template(
					raw.html(),
					data,
					template_settings
				);
			}
			else {
				var template = _.template( raw.html(), template_settings );

				return template( data );
			}
		}
	};

	/* Boot the components. */
	window.brix_ready = function() {
		$.brixf.ui.build();

		if ( typeof $.brixf.tabs !== "undefined" ) {
			$.brixf.tabs( ".brix-tabs" + $.brixf.componentID );
		}

		if ( typeof $.brixf.accordion !== "undefined" ) {
			$.brixf.accordion( ".brix-accordion" + $.brixf.componentID );
		}

		$( this ).trigger( "brix_ready" );
	};

	$( document ).on( "ready", function() {
		window.brix_ready();
	} );
} )( jQuery );;
/**
 * author Christopher Blum, forked by Evolve
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function (factory) {
  if (typeof define == 'function' && define.amd) {
    // AMD
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node, CommonJS
    module.exports = factory(require('jquery'));
  } else {
      // Browser globals
    factory(jQuery);
  }
}(function ($) {
  var inviewObjects = {}, viewportSize, viewportOffset,
      d = document, w = window, documentElement = d.documentElement, expando = $.expando, timer;

  $.event.special.inview = {
    add: function(data) {
      inviewObjects[data.guid + "-" + this[expando]] = { data: data, $element: $(this) };

      // Use setInterval in order to also make sure this captures elements within
      // "overflow:scroll" elements or elements that appeared in the dom tree due to
      // dom manipulation and reflow
      // old: $(window).scroll(checkInView);
      //
      // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
      // intervals while the user scrolls. Therefore the inview event might fire a bit late there
      //
      // Don't waste cycles with an interval until we get at least one element that
      // has bound to the inview event.
      if (!timer && !$.isEmptyObject(inviewObjects)) {
         timer = setInterval(checkInView, 250);
      }
    },

    remove: function(data) {
      try { delete inviewObjects[data.guid + "-" + this[expando]]; } catch(e) {}

      // Clear interval when we no longer have any elements listening
      if ($.isEmptyObject(inviewObjects)) {
         clearInterval(timer);
         timer = null;
      }
    }
  };

  function getViewportSize() {
    var mode, domObject, size = { height: w.innerHeight, width: w.innerWidth };

    // if this is correct then return it. iPad has compat Mode, so will
    // go into check clientHeight/clientWidth (which has the wrong value).
    if (!size.height) {
      mode = d.compatMode;
      if (mode || !$.support.boxModel) { // IE, Gecko
        domObject = mode === 'CSS1Compat' ?
          documentElement : // Standards
          d.body; // Quirks
        size = {
          height: domObject.clientHeight,
          width:  domObject.clientWidth
        };
      }
    }

    return size;
  }

  function getViewportOffset() {
    return {
      top:  w.pageYOffset || documentElement.scrollTop   || d.body.scrollTop,
      left: w.pageXOffset || documentElement.scrollLeft  || d.body.scrollLeft
    };
  }

  function checkInView() {
    var $elements = [], elementsLength, i = 0;

    $.each(inviewObjects, function(i, inviewObject) {
      var selector  = inviewObject.data.selector,
          $element  = inviewObject.$element;
      $elements.push(selector ? $element.find(selector) : $element);
    });

    elementsLength = $elements.length;
    if (elementsLength) {
      viewportSize   = viewportSize   || getViewportSize();
      viewportOffset = viewportOffset || getViewportOffset();

      for (; i<elementsLength; i++) {
        // Ignore elements that are not in the DOM tree
        if (!$.contains(documentElement, $elements[i][0])) {
          continue;
        }

        var $element      = $($elements[i]),
            elementSize   = { height: $element.outerHeight(), width: $element.outerWidth() },
            elementOffset = $element.offset(),
            inView        = $element.data('inview'),
            visiblePartX,
            visiblePartY,
            visiblePartsMerged;

        // Don't ask me why because I haven't figured out yet:
        // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
        // Even though it sounds weird:
        // It seems that the execution of this function is interferred by the onresize/onscroll event
        // where viewportOffset and viewportSize are unset
        if (!viewportOffset || !viewportSize) {
          return;
        }

        if (elementOffset.top + elementSize.height > viewportOffset.top &&
            elementOffset.top < viewportOffset.top + viewportSize.height &&
            elementOffset.left + elementSize.width > viewportOffset.left &&
            elementOffset.left < viewportOffset.left + viewportSize.width) {
          visiblePartX = (viewportOffset.left > elementOffset.left ?
            'right' : (viewportOffset.left + viewportSize.width) < (elementOffset.left + elementSize.width) ?
            'left' : 'both');
          visiblePartY = (viewportOffset.top > elementOffset.top ?
            'bottom' : (viewportOffset.top + viewportSize.height) < (elementOffset.top + elementSize.height) ?
            'top' : 'both');
          visiblePartsMerged = visiblePartX + "-" + visiblePartY;
          if (!inView || inView !== visiblePartsMerged) {
            $element.data('inview', visiblePartsMerged).trigger('inview', [true, visiblePartX, visiblePartY]);
          }
        } else if (inView) {
          $element.data('inview', false).trigger('inview', [false]);
        }
      }
    }
  }

  $(w).bind("scroll resize scrollstop", function() {
    viewportSize = viewportOffset = null;
  });

  // IE < 9 scrolls to focused elements without firing the "scroll" event
  if (!documentElement.addEventListener && documentElement.attachEvent) {
    documentElement.attachEvent("onfocusin", function() {
      viewportOffset = null;
    });
  }
}));;
( function( $ ) {
	"use strict";

	/**
	 * Inview controller.
	 */
	$.brixf.inview = new function() {

		var self = this;

		/* The class associated to elements entering the viewport. */
		this.inview_class = "brix-inview";

		/**
		 * Register a selector to perform an action when entering the viewport.
		 */
		this.register = function( selector, callback, toggle ) {
			$( selector ).on( "inview", function( event, isInView, visiblePartX, visiblePartY ) {
				if ( isInView ) {
					if ( ! $( this ).hasClass( self.inview_class ) ) {
						$( this ).addClass( self.inview_class );

						if ( callback ) {
							callback( $( this ) );
						}
					}
				}
				else if ( toggle ) {
					$( this ).removeClass( self.inview_class );
				}
			} );
		};

	};
} )( jQuery );;
( function( $ ) {
	"use strict";

	/**
	 * Images preloader. Fires a callback when a given image, all images in a
	 * container or a background images applied to a page element have been
	 * loaded.
	 *
	 * @param  {String|Object} element A selector string or a DOM element.
	 * @param  {Object} config The configuration object.
	 */
	$.brix_load_images = function( element, config ) {
		element = $( element );

		config = $.extend( {}, {
			/* Set to true to wait for the image/all the images to be loaded before firing the callbacks. */
			preload: true,

			/* The HTML attribute that stores the image URL. Used with "data-" prepended as well for lazy loading. */
			attr: "src",

			/* The HTML data attribute ("data-" prepended) that stores the image URL for lazy loading. */
			background_attr: "bg",

			/* The HTML attribute that stores the high definition image URL. Used with "data-" prepended as well for lazy loading. */
			retina_attr: "srcset",

			/* The HTML attribute that stores the high definition background image URL. Used with "data-" prepended as well for lazy loading. */
			retina_background_attr: "srcset-bg",

			/* Callback fired when all the images have been loaded. */
			all: function() {},

			/* Callback fired whenever an image is loaded. */
			single: function() {}
		}, config );

		if ( ! element.length ) {
			config.all( [] );

			return;
		}

		var instance = {
			images: [],
			loaded: 0,

			parse_densities: function( srcset ) {
				var src = "";

				if ( typeof srcset === "object" ) {
					if ( srcset[window.devicePixelRatio] ) {
						src = srcset[window.devicePixelRatio];
					}
					else {
						for ( var density in srcset ) {
							if ( density < window.devicePixelRatio ) {
								src = srcset[density];
							}
						}
					}
				}
				else {
					src = srcset;
				}

				return src;
			},

			get_src: function( obj ) {
				obj = $( obj );

				var src = "",
					lazy = false;

				if ( obj.is( "img" ) ) {
					src = obj.attr( "src" );
					lazy = ( ! src || src === "" || src === "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" ) && obj.data( config.attr ) && obj.data( config.attr ) !== "";

					if ( lazy ) {
						src = obj.data( config.attr );
					}

					/* Check if alternative sizes have been provided for different screen densities. */
					if ( obj.data( config.retina_attr ) && obj.data( config.retina_attr ) !== "" ) {
						var srcset = instance.parse_densities( obj.data( config.retina_attr ) );

						if ( srcset !== "" ) {
							src = srcset;
							lazy = true;
						}
					}
				}
				else {
					var background_image = obj.css( "background-image" ).replace( "url(", "" ).replace( ")", "" ),
						background_image_lazy = obj.data( config.background_attr );

					if ( background_image != "none" ) {
						src = background_image;
					}

					if ( background_image_lazy != "" ) {
						src = background_image_lazy;
						lazy = true;
					}

					/* Check if alternative sizes have been provided for different screen densities. */
					if ( obj.data( config.retina_background_attr ) && obj.data( config.retina_background_attr ) !== "" ) {
						var srcset = instance.parse_densities( obj.data( config.retina_background_attr ) );

						if ( srcset !== "" ) {
							src = srcset;
							lazy = true;
						}
					}
				}

				return {
					"element": obj,
					"src": src,
					"lazy": lazy
				};
			},

			load_callback: function( data ) {
				var object = data.element;

				this.loaded++;

				if ( data.src !== undefined ) {
					if ( object.is( "img" ) && data.lazy ) {
						$( object ).attr( "src", data.src );
					}
					else if ( ! object.is( "img" ) ) {
						$( object ).css( "background-image", "url(" + data.src + ")" );
					}

					$( object ).removeClass( "brix-loading" );
				}

				config.single( object );

				if ( this.loaded === this.images.length ) {
					config.all( this.images );
				}
			}
		};

		element.each( function() {
			instance.images.push( $( this ) );
		} );

		$.each( instance.images, function() {
			var self = this,
				data = instance.get_src( self );

			if ( data.src !== undefined ) {
				data.element.addClass( "brix-loading" );
			}

			if ( config.preload ) {
				if ( data.src !== undefined ) {
					$( "<img />" )
						.one( "load error", function() {
							instance.load_callback( data );
						} )
						.attr( "src", data.src );
				}
				else {
					instance.load_callback( data );
				}
			}
			else {
				instance.load_callback( data );
			}
		} );
	};
} )( jQuery );;
/*jshint browser:true */
/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/

;(function( $ ){

  'use strict';

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement("div");
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        'iframe[src*="player.vimeo.com"]',
        'iframe[src*="youtube.com"]',
        'iframe[src*="youtube-nocookie.com"]',
        'iframe[src*="kickstarter.com"][src*="video.html"]',
        'object',
        'embed'
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(count){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('id')){
          var videoID = 'fitvid' + count;
          $this.attr('id', videoID);
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );;
/*!
 * SVGInjector v2.0.36 - Fast, caching, dynamic inline SVG DOM injection library
 * https://github.com/flobacher/SVGInjector2
 * forked from:
 * https://github.com/iconic/SVGInjector
 *
 * Copyright (c) 2015 flobacher <flo@digital-fuse.net>
 * @license MIT
 *
 * original Copyright (c) 2014 Waybury <hello@waybury.com>
 * @license MIT
 */
!function(e,t){"use strict";var n=function(){function n(e){n.instanceCounter++,this.init(e)}var r,s,i,l,a,o,c,u,f,p,d,m,g,v,h,y,b,S,A,C,x,N,k,w,j,E,I,T,G,V="http://www.w3.org/2000/svg",F="http://www.w3.org/1999/xlink",O="sprite",L=O+"--",q=[O],M="icon";return i=[],n.instanceCounter=0,n.prototype.init=function(n){n=n||{},r={},o={},o.isLocal="file:"===e.location.protocol,o.hasSvgSupport=t.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure","1.1"),s={count:0,elements:[]},l={},a={},a.evalScripts=n.evalScripts||"always",a.pngFallback=n.pngFallback||!1,a.onlyInjectVisiblePart=n.onlyInjectVisiblePart||!0,a.keepStylesClass="undefined"==typeof n.keepStylesClass?"":n.keepStylesClass,a.spriteClassName="undefined"==typeof n.spriteClassName?O:n.spriteClassName,a.spriteClassIdName="undefined"==typeof n.spriteClassIdName?L:n.spriteClassIdName,a.removeStylesClass="undefined"==typeof n.removeStylesClass?M:n.removeStylesClass,a.removeAllStyles="undefined"!=typeof n.removeAllStyles&&n.removeAllStyles,a.fallbackClassName="undefined"==typeof n.fallbackClassName?q:n.fallbackClassName,a.prefixStyleTags="undefined"==typeof n.prefixStyleTags||n.prefixStyleTags,a.spritesheetURL="undefined"!=typeof n.spritesheetURL&&""!==n.spritesheetURL&&n.spritesheetURL,a.prefixFragIdClass=a.spriteClassIdName,a.forceFallbacks="undefined"!=typeof n.forceFallbacks&&n.forceFallbacks,a.forceFallbacks&&(o.hasSvgSupport=!1),x(t.querySelector("html"),"no-svg",o.hasSvgSupport),o.hasSvgSupport&&"undefined"==typeof n.removeStylesClass&&C(a.removeStylesClass)},n.prototype.inject=function(e,t,n){if(void 0!==e.length){var r=0,s=this;I.call(e,function(i){s.injectElement(i,function(s){n&&"function"==typeof n&&n(s),t&&e.length===++r&&t(r)})})}else e?this.injectElement(e,function(r){n&&"function"==typeof n&&n(r),t&&t(1),e=null}):t&&t(0)},n.prototype.injectElement=function(e,t){var n,r=e.getAttribute("data-src")||e.getAttribute("src");if(!r){if(!a.spritesheetURL)return;if(n=h(e),""===n)return;r=a.spritesheetURL+"#"+n}e.setAttribute("data-src",r);var i=r.split("#");1===i.length&&i.push("");var l;if(!/\.svg/i.test(r))return void t("Attempted to inject a file with a non-svg extension: "+r);if(!o.hasSvgSupport){var f=e.getAttribute("data-fallback")||e.getAttribute("data-png");return void(f?(e.setAttribute("src",f),t(null)):a.pngFallback?(i.length>1&&i[1]?(l=i[1]+".png",j(a.fallbackClassName)?c(e,i[1],a.fallbackClassName):w(a.fallbackClassName)?a.fallbackClassName(e,i[1]):"string"==typeof a.fallbackClassName?E(e,a.fallbackClassName):e.setAttribute("src",a.pngFallback+"/"+l)):(l=r.split("/").pop().replace(".svg",".png"),e.setAttribute("src",a.pngFallback+"/"+l)),t(null)):t("This browser does not support SVG and no PNG fallback was defined."))}j(a.fallbackClassName)&&u(e,i[1],a.fallbackClassName),s.elements.indexOf(e)===-1&&(s.elements.push(e),A(t,r,e))},n.prototype.getEnv=function(){return o},n.prototype.getConfig=function(){return a},c=function(e,t,n){var r="undefined"==typeof n?q:n.slice(0);I.call(r,function(e,n){r[n]=e.replace("%s",t)}),E(e,r)},u=function(e,t,n){n="undefined"==typeof n?q.slice(0):n.slice(0);var r,s,i=e.getAttribute("class");"undefined"!=typeof i&&null!==i&&(s=i.split(" "),s&&(I.call(n,function(e){e=e.replace("%s",t),r=s.indexOf(e),r>=0&&(s[r]="")}),e.setAttribute("class",k(s.join(" ")))))},p=function(e,t,n,r){var s=0;return e.textContent=e.textContent.replace(/url\(('|")*#.+('|")*(?=\))/g,function(e){return s++,e+"-"+t}),s},f=function(e,t){var n,r,s,i,l,a,o,c,u,f,p,d,m,g,v,h,y=[{def:"linearGradient",attrs:["fill","stroke"]},{def:"radialGradient",attrs:["fill","stroke"]},{def:"clipPath",attrs:["clip-path"]},{def:"mask",attrs:["mask"]},{def:"filter",attrs:["filter"]},{def:"color-profile",attrs:["color-profile"]},{def:"cursor",attrs:["cursor"]},{def:"marker",attrs:["marker","marker-start","marker-mid","marker-end"]}];I.call(y,function(y){for(r=e.querySelectorAll(y.def+"[id]"),i=0,s=r.length;i<s;i++){for(n=r[i].id+"-"+t,c=y.attrs,f=0,u=c.length;f<u;f++)for(l=e.querySelectorAll("["+c[f]+'="url(#'+r[i].id+')"]'),o=0,a=l.length;o<a;o++)l[o].setAttribute(c[f],"url(#"+n+")");for(p=e.querySelectorAll("[*|href]"),g=[],m=0,d=p.length;m<d;m++)p[m].getAttributeNS(F,"href").toString()==="#"+r[i].id&&g.push(p[m]);for(h=0,v=g.length;h<v;h++)g[h].setAttributeNS(F,"href","#"+n);r[i].id=n}})},d=function(e,t,n){var r;"undefined"==typeof n&&(n=["id","viewBox"]);for(var s=0;s<e.attributes.length;s++)r=e.attributes.item(s),n.indexOf(r.name)<0&&t.setAttribute(r.name,r.value)},m=function(e){var n=t.createElementNS(V,"svg");return I.call(e.childNodes,function(e){n.appendChild(e.cloneNode(!0))}),d(e,n),n},g=function(e,t,n){var r,s,i,l,a,o,c=n.getAttribute("data-src").split("#"),u=e.textContent,f="",p=function(e,t,n){n[t]="."+i+" "+e};if(c.length>1)s=c[1],i=s+"-"+t,r=new RegExp("\\."+s+" ","g"),e.textContent=u.replace(r,"."+i+" ");else{for(l=c[0].split("/"),i=l[l.length-1].replace(".svg","")+"-"+t,r=new RegExp("([\\s\\S]*?){([\\s\\S]*?)}","g");null!==(a=r.exec(u));){o=a[1].trim().split(", "),o.forEach(p);var d=o.join(", ")+"{"+a[2]+"}\n";f+=d}e.textContent=f}n.setAttribute("class",n.getAttribute("class")+" "+i)},v=function(e){var t=e.getAttribute("class");return t?t.trim().split(" "):[]},h=function(e){var t=v(e),n="";return I.call(t,function(e){e.indexOf(a.spriteClassIdName)>=0&&(n=e.replace(a.spriteClassIdName,""))}),n},y=function(e,t,n){var r,s,i,l,a,o,c,u=!1,f=null;if(void 0===n)return t.cloneNode(!0);if(r=t.getElementById(n)){if(l=r.getAttribute("viewBox"),i=l.split(" "),r instanceof SVGSymbolElement)s=m(r),u=!0;else if(r instanceof SVGViewElement){if(f=null,e.onlyInjectVisiblePart){var p='*[width="'+i[2]+'"][height="'+i[3]+'"]';a={},0===Math.abs(parseInt(i[0]))?p+=":not([x])":(a.x=i[0],p+='[x="'+i[0]+'"]'),0===Math.abs(parseInt(i[1]))?p+=":not([y])":(a.y=i[1],p+='[y="'+i[1]+'"]'),c=t.querySelectorAll(p),c.length>1,f=c[0]}if(f&&f instanceof SVGSVGElement){s=f.cloneNode(!0);for(var d in a)"width"!==d&&"height"!==d&&s.removeAttribute(d)}else if(f&&f instanceof SVGUseElement){var g=t.getElementById(f.getAttributeNS(F,"href").substr(1));s=m(g),l=g.getAttribute("viewBox"),i=l.split(" "),u=!0}else u=!0,s=t.cloneNode(!0)}u&&(s.setAttribute("viewBox",i.join(" ")),s.setAttribute("width",i[2]+"px"),s.setAttribute("height",i[3]+"px")),s.setAttribute("xmlns",V),s.setAttribute("xmlns:xlink",F),o=v(s);var h=e.prefixFragIdClass+n;return o.indexOf(h)<0&&(o.push(h),s.setAttribute("class",o.join(" "))),s}},b=function(e,t,n,r){i[e]=i[e]||[],i[e].push({callback:n,fragmentId:t,element:r,name:name})},S=function(e){for(var t,n=0,r=i[e].length;n<r;n++)!function(n){setTimeout(function(){t=i[e][n],N(e,t.fragmentId,t.callback,t.element,t.name)},0)}(n)},A=function(t,n,s){var i,l,a,c,u;if(i=n.split("#"),l=i[0],a=2===i.length?i[1]:void 0,"undefined"!=typeof a&&(u=l.split("/"),c=u[u.length-1].replace(".svg","")),void 0!==r[l])r[l]instanceof SVGSVGElement?N(l,a,t,s,c):b(l,a,t,s,c);else{if(!e.XMLHttpRequest)return t("Browser does not support XMLHttpRequest"),!1;r[l]={},b(l,a,t,s,c);var f=new XMLHttpRequest;f.onreadystatechange=function(){if(4===f.readyState){if(404===f.status||null===f.responseXML)return t("Unable to load SVG file: "+l),!1;if(!(200===f.status||o.isLocal&&0===f.status))return t("There was a problem injecting the SVG: "+f.status+" "+f.statusText),!1;if(f.responseXML instanceof Document)r[l]=f.responseXML.documentElement;else if(DOMParser&&DOMParser instanceof Function){var e;try{var s=new DOMParser;e=s.parseFromString(f.responseText,"text/xml")}catch(t){e=void 0}if(!e||e.getElementsByTagName("parsererror").length)return t("Unable to parse SVG file: "+n),!1;r[l]=e.documentElement}S(l)}},f.open("GET",l),f.overrideMimeType&&f.overrideMimeType("text/xml"),f.send()}},C=function(e){var n="svg."+e+" {fill: currentColor;}",r=t.head||t.getElementsByTagName("head")[0],s=t.createElement("style");s.type="text/css",s.styleSheet?s.styleSheet.cssText=n:s.appendChild(t.createTextNode(n)),r.appendChild(s)},x=function(e,t,n){n?e.className.replace(t,""):e.className+=" "+t},N=function(t,n,i,o,c){var u,m,h,b;if(u=y(a,r[t],n),"undefined"==typeof u||"string"==typeof u)return i(u),!1;u.setAttribute("role","img"),I.call(u.children||[],function(e){e instanceof SVGDefsElement||e instanceof SVGTitleElement||e instanceof SVGDescElement||e.setAttribute("role","presentation")}),b=o.getAttribute("aria-hidden")||u.getAttribute("aria-hidden"),h=T("desc",u,o,n,c,!b),m=T("title",u,o,n,c,!b),b?u.setAttribute("aria-hidden","true"):u.setAttribute("aria-labelledby",m+" "+h),d(o,u,["class"]);var S=[].concat(u.getAttribute("class")||[],"injected-svg",o.getAttribute("class")||[]).join(" ");u.setAttribute("class",k(S)),f(u,s.count,c),u.removeAttribute("xmlns:a");for(var A,C,x=u.querySelectorAll("script"),N=[],w=0,j=x.length;w<j;w++)C=x[w].getAttribute("type"),C&&"application/ecmascript"!==C&&"application/javascript"!==C||(A=x[w].innerText||x[w].textContent,N.push(A),u.removeChild(x[w]));if(N.length>0&&("always"===a.evalScripts||"once"===a.evalScripts&&!l[t])){for(var E=0,G=N.length;E<G;E++)new Function(N[E])(e);l[t]=!0}var V=u.querySelectorAll("style");I.call(V,function(e){var t=v(u),n=!0;(t.indexOf(a.removeStylesClass)>=0||a.removeAllStyles)&&t.indexOf(a.keepStylesClass)<0?e.parentNode.removeChild(e):(p(e,s.count,u,c)>0&&(n=!1),a.prefixStyleTags&&(g(e,s.count,u,c),n=!1),n&&(e.textContent+=""))}),o.parentNode.replaceChild(u,o),delete s.elements[s.elements.indexOf(o)],s.count++,i(u)},k=function(e){e=e.split(" ");for(var t={},n=e.length,r=[];n--;)t.hasOwnProperty(e[n])||(t[e[n]]=1,r.unshift(e[n]));return r.join(" ")},w=function(e){return!!(e&&e.constructor&&e.call&&e.apply)},j=function(e){return"[object Array]"===Object.prototype.toString.call(e)},E=function(e,t){var n=e.getAttribute("class");n=n?n:"",j(t)&&(t=t.join(" ")),t=n+" "+t,e.setAttribute("class",k(t))},I=Array.prototype.forEach||function(e,t){if(void 0===this||null===this||"function"!=typeof e)throw new TypeError;var n,r=this.length>>>0;for(n=0;n<r;++n)n in this&&e.call(t,this[n],n,this)},T=function(e,t,n,r,i){var l,a=r?r+"-":"";return a+=e+"-"+s.count,l=n.querySelector(e),l?G(e,t,l.textContent,a,t.firstChild):(l=t.querySelector(e),l?l.setAttribute("id",a):i?G(e,t,r,a,t.firstChild):a=""),a},G=function(e,n,r,s,i){var l,a=n.querySelector(e);return a&&a.parentNode.removeChild(a),l=t.createElementNS(V,e),l.appendChild(t.createTextNode(r)),l.setAttribute("id",s),n.insertBefore(l,i),l},n}();"object"==typeof angular?angular.module("svginjector",[]).provider("svgInjectorOptions",function(){var e={};return{set:function(t){e=t},$get:function(){return e}}}).factory("svgInjectorFactory",["svgInjectorOptions",function(e){return new n(e)}]).directive("svg",["svgInjectorFactory",function(e){var t=e.getConfig();return{restrict:"E",link:function(n,r,s){s.class&&s.class.indexOf(t.spriteClassIdName)>=0?s.$observe("class",function(){e.inject(r[0])}):(s.dataSrc||s.src)&&e.inject(r[0])}}}]):"object"==typeof module&&"object"==typeof module.exports?module.exports=n:"function"==typeof define&&define.amd?define(function(){return n}):"object"==typeof e&&(e.SVGInjector=n)}(window,document);
//# sourceMappingURL=./dist/svg-injector.map.js;
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

} )( jQuery );;
( function( $ ) {
	"use strict";

	var Brix_BlogBuilderBlock = function() {

		( new Brix_LoopBuilderBlock( {
			"handle": "blog",
			"block_selector": ".brix-section-column-block-blog",
			"loop_selector": ".brix-blog-block-loop-wrapper",
			"pagination_selector": ".brix-blog-block-pagination-wrapper"
		} ) );

	};

	Brix_BlogBuilderBlock();
} )( jQuery );;
( function( $ ) {
	"use strict";

	function Brix_WooGridBuilderBlock() {

		( new Brix_LoopBuilderBlock( {
			"handle": "woo-shop-grid",
			"block_selector": ".brix-section-column-block-woo_shop_grid",
			"loop_selector": ".brix-woo-shop-grid-block-loop-wrapper .products",
			"pagination_selector": ".brix-woo-shop-grid-block-pagination-wrapper",
			"replacements": ".woocommerce-result-count"
		} ) );

	};

	Brix_WooGridBuilderBlock();
} )( jQuery );;
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