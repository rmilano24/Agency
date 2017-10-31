( function( $ ) {
	"use strict";

	window.agncy_installer = new Vue( {
		el: '#agncy-installer',
		data: {
			plugins: [],
			current_plugin: 0,
			step: 0,
			status: ""
		},
		created: function() {
			this.plugins = Object.keys( agncy_installer_localized.plugins );
		},
		methods: {
			goToWelcome: function() {
				window.location.href = agncy_installer_localized.welcome_url;
			},
			installPlugin: function() {
				var slug = this.plugins[this.current_plugin],
					url = agncy_installer_localized.plugins[slug].action,
					self = this;

				self.status = agncy_installer_localized.plugins[slug].action_label;

				$.get(
					url,
					{},
					function() {
						self.checkPluginStatus( slug, function( status ) {
							if ( status == 1 ) {
								self.current_plugin++;

								if ( self.current_plugin === self.plugins.length ) {
									NProgress.done( true );

									setTimeout( function() {
										self.step++;

										$( "body" ).removeClass( "ev-installer-installing" );
									}, NProgress.settings.trickleSpeed );
								}
								else {
									NProgress.set( self.current_plugin / self.plugins.length );
									self.installPlugin();
								}
							}
							else {
								NProgress.done( true );
								self.status = agncy_installer_localized.plugins_error;
								$( "body" ).removeClass( "ev-installer-installing" );
							}
						} );
					}
				)
			},
			checkPluginStatus: function( slug, callback ) {
				$.post(
					agncy.ajax_url,
					{
						"action": "agncy_install_check_plugin_active",
						"path": agncy_installer_localized.plugins[slug].path
					},
					callback
				)
			},
			installRequiredPlugins: function() {
				var self = this;

				this.step++;

				$( "body" ).addClass( "ev-installer-installing" );

				if ( ! this.plugins.length ) {
					this.step++;

					return;
				}

				setTimeout( function() {
					NProgress.configure( {
						parent: "#agncy-np_w",
						showSpinner: false,
						trickle: false,
						trickleRate: 0.005,
						trickleSpeed: 800
					} );

					// NProgress.start();
					// NProgress.set( 0.1 );

					self.installPlugin();
				}, 10 );
			}
		},
		computed: {
			installerClassObject: function() {
				var classes = {};

				return classes;
			}
		}
	} );
} )( jQuery );
