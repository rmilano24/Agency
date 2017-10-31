( function( $ ) {
	"use strict";

	function agncy_about_get_hash() {
		var hash = window.location.hash.replace( "#", "" );

		if ( hash == "" ) {
			return "intro";
		}

		return hash;
	}

	window.agncy_about = new Vue( {
		el: '#agncy-about',
		data: {
			panel: agncy_about_get_hash(),
			auto_updates: {
				working: false,
				linked : agncy.auto_updates,
				status : "",
				code   : "",
				linking: false
			},
			system_status: {
				errors: agncy.system_status._errors > 0
			},
			demos: {
				working: false,
				list: agncy.demos,
				errors: agncy.demos.length === 0
			},
			theme_status: agncy.theme_status,
			display: false
		},
		created: function() {
			if ( this.auto_updates.code ) {
				this.auto_updates.linked = false;
			}

			if ( this.theme_status == 'first_install' ) {
				this.setPanel( "welcome" );
			}
			else if ( this.theme_status == 'updated' ) {
				this.setPanel( "new_update" );
			}
		},
		mounted: function() {
			this.display = true;
		},
		computed: {
			aboutAppClass: function() {
				var klass = {};

				klass[ "agncy-ap-d" ] = this.display;

				return klass;
			},
			demosContainerClass: function() {
				var klass = {};

				klass[ "agncy-ap-d-installing" ] = this.demos.working;

				return klass;
			}
		},
		methods: {
			setPanel: function( panel ) {
				window.location.hash = "#" + panel;
				this.panel = panel;

				return false;
			},
			installDemo: function( demo, nonce ) {
				var self = this;

				self.demos.working = true;
				$( ".agncy-ap-d--d[data-name='" + demo + "']" ).addClass( "agncy-d-installing" );

				$.post(
					agncy.ajax_url,
					{
						"action": "agncy_demo_install",
						"demo"  : demo,
						"nonce" : nonce
					},
					function( res ) {
						self.demos.working = false;
						$( ".agncy-ap-d--d[data-name='" + demo + "']" )
							.removeClass( "agncy-d-installing" )
							.addClass( "agncy-d-installed" );
					}
				);
			},
			linkAutoUpdates: function( nonce ) {
				var self = this;

				self.auto_updates.working = true;

				$.post(
					agncy.ajax_url,
					{
						"action": "agncy_link_auto_updates",
						"code"  : self.auto_updates.code,
						"nonce" : nonce
					},
					function( res ) {
						self.auto_updates.working = false;

						if ( res == '1' ) {
							self.auto_updates.linked = true;
							self.auto_updates.status = "";
						}
						else {
							self.auto_updates.linked = false;
							self.auto_updates.status = res;
						}

						self.auto_updates.linking = false;
						self.auto_updates.working = false;
					}
				);

				return false;
			},
			autoUpdatesAuth: function( url ) {
				this.setPanel( 'update' );

				var self = this;

				self.auto_updates.working = true;
				self.auto_updates.linking = true;

				// Fixes dual-screen position                         Most browsers      Firefox
				var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
				var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

				var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
				var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

				var w = 500,
					h = 900;

				var left = ((width / 2) - (w / 2)) + dualScreenLeft;
				var top = ((height / 2) - (h / 2)) + dualScreenTop;
				var newWindow = window.open(
					url, "", 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

				// Puts focus on the newWindow
				if (window.focus) {
					newWindow.focus();
				}

				self.auto_updates.working = false;

				return false;
			},
			unlinkAutoUpdates: function( nonce ) {
				var self = this;

				self.auto_updates.working = true;

				$.post(
					agncy.ajax_url,
					{
						"action": "agncy_unlink_auto_updates",
						"nonce": nonce
					},
					function( res ) {
						self.auto_updates.working = false;

						if ( res == '1' ) {
							self.auto_updates.linked = false;
						}
						else {
							self.auto_updates.linked = true;
							self.auto_updates.status = "";
						}
					}
				)
			}
		}
	} );
} )( jQuery );