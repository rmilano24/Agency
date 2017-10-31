<?php

if ( ! class_exists( 'EvolveThemeUpdater' ) ) :

	/**
	 * The theme updater class.
	 *
	 * @since 1.0.0
	 */
	class EvolveThemeUpdater {

		/**
		 * The class instance.
		 *
		 * @static
		 * @var EvolveThemeUpdater
		 */
		private static $_instance = null;

		/**
		 * The API object.
		 *
		 * @var EvolveEnvatoAPI
		 */
		private $api = null;

		/**
		 * A string identifier for the product.
		 *
		 * @var string
		 */
		private $product_key = '';

		/**
		 * Authentication status.
		 *
		 * @var boolean
		 */
		private $authentication = false;

		/**
		 * Return the instance of the class.
		 *
		 * @static
		 * @since 1.0.0
		 * @param string $client_id The API application client ID.
		 * @param string $client_secret The API application client secret.
		 * @param string $product_key A string identifier for the product.
		 * @return EvolveThemeUpdater
		 */
		public static function instance( $client_id = '', $client_secret = '', $product_key = '' )
		{
			if ( self::$_instance === null ) {
				self::$_instance = new EvolveThemeUpdater( $client_id, $client_secret, $product_key );
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @param string $client_id The API application client ID.
		 * @param string $client_secret The API application client secret.
		 * @param string $product_key A string identifier for the product.
		 */
		public function __construct( $client_id, $client_secret, $product_key )
		{
			if ( ! class_exists( 'EvolveEnvatoAPI' ) ) {
				require_once get_template_directory() . '/inc/updater/envato-api.php';
			}

			$this->api = new EvolveEnvatoAPI();
			$this->api->set_client( $client_id, $client_secret );

			/* String identifier for the product. */
			$this->product_key = $product_key;
		}

		/**
		 * Check the authentication status.
		 *
		 * @since 1.0.0
		 * @return boolean
		 */
		public function is_authenticated()
		{
			return (bool) $this->authentication;
		}

		/**
		 * Get the current theme object.
		 *
		 * @since 1.0.0
		 * @return WP_Theme
		 */
		private function get_theme()
		{
			$current_theme = wp_get_theme();
			$current_stylesheet = get_stylesheet();

			if ( is_child_theme() ) {
				$current_stylesheet = $current_theme->get( 'Template' );
				$current_theme = wp_get_theme( $current_stylesheet );
			}

			return $current_theme;
		}

		/**
		 * Clean up saved data.
		 *
		 * @since 1.0.0
		 */
		public function cleanup()
		{
			/* Keys. */
			$access_token_key = $this->product_key . '_access_token';
			$refresh_token_key = $this->product_key . '_refresh_token';

			delete_option( $access_token_key );
			delete_option( $refresh_token_key );
		}

		/**
		 * Attempt to authenticate with the Envato API.
		 *
		 * @since 1.0.0
		 * @param string $code The first authentication code.
		 */
		public function maybe_authenticate( $code = false )
		{
			/* Reset authentication status. */
			$this->authentication = false;

			/* Keys. */
			$access_token_key = $this->product_key . '_access_token';
			$refresh_token_key = $this->product_key . '_refresh_token';

			/* Access token. */
			$access_token = get_option( $access_token_key );

			/* First authentication code. */
			$code = ! empty( $code ) ? sanitize_text_field( $code ) : false;

			if ( $access_token === false ) {
				if ( $code ) {
				    try {
				        /* Check the purchase validity. */
				        $auth = $this->check_purchase( $code );

				        if ( $auth !== false ) {
				        	$this->authentication = true;
				        }

				        if ( $this->authentication === true ) {
				        	/* Proceed storing authentication data. */
				        	update_option( $access_token_key, json_encode( $auth ) );
				        }
				    }
				    catch ( Exception $e ) {
				    	throw new Exception( $e->getMessage(), 1 );
				    }
				}
			}
			else {
			    try {
			        $access = @json_decode( $access_token, true );
			        $auth = $this->api->refresh_token( $access[ 'refresh_token' ] );

			        update_option( $refresh_token_key, json_encode( $auth ) );

			        $this->authentication = true;
			    }
			    catch( Exception $e ) {
			    	throw new Exception( $e->getMessage(), 1 );
			    }
			}
		}

		/**
		 * Check the purchase validity.
		 *
		 * @since 1.0.0
		 * @param string $code The first authentication code.
		 * @return boolean
		 */
		private function check_purchase( $code )
		{
			$auth = $this->api->authenticate( $code );
			$resp = $this->api->api( 'v3/market/buyer/purchases', 'GET' );

			foreach ( $resp[ 'purchases' ] as $purchase ) {
			   	if ( isset( $purchase[ 'item' ][ 'wordpress_theme_metadata' ] ) ) {
					if ( $purchase[ 'item' ][ 'wordpress_theme_metadata' ][ 'theme_name' ] === $this->product_key ) {
						return $auth;
					}
				}
			}

			return false;
		}

		/**
		 * Retrieve purchase information from the API.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_purchase()
		{
			try {
				$this->maybe_authenticate();

				if ( ! $this->authentication ) {
					return array();
				}

			    $resp = $this->api->api( 'v3/market/buyer/purchases', 'GET' );

			    $purchase_data = array();

			    foreach ( $resp[ 'purchases' ] as $purchase ) {
			    	if ( isset( $purchase[ 'item' ][ 'wordpress_theme_metadata' ] ) ) {
			    		if ( $purchase[ 'item' ][ 'wordpress_theme_metadata' ][ 'theme_name' ] === $this->product_key ) {
							$purchase_data[ 'theme' ]   = $this->get_theme()->get_stylesheet();
							$purchase_data[ 'item_id' ] = $purchase[ 'item' ][ 'id' ];
							$purchase_data[ 'version' ] = $purchase[ 'item' ][ 'wordpress_theme_metadata' ][ 'version' ];
							$purchase_data[ 'code' ]    = $purchase[ 'code' ];

			    			if ( version_compare( $this->get_theme()->get( 'Version' ), $purchase_data[ 'version' ], '<' ) ) {
			    				$resp = $this->api->api( 'v3/market/buyer/download', 'GET', array(
			    					'purchase_code' => $purchase_data[ 'code' ]
			    				) );

			    				$purchase_data[ 'download' ] = $resp[ 'wordpress_theme' ];
			    			}
			    		}

			    		return $purchase_data;
			    	}
			    }
			}
			catch ( Exception $e ) {
			}

			return array();
		}

		/**
		 * Perform additional actions to successfully install the theme.
		 *
		 * @param boolean $true
		 * @param mixed $hook_extra
		 * @param array $result
		 * @return array
		 */
		public function postInstall( $true, $hook_extra, $result )
		{
			update_option( 'agncy_updated', '1' );

			do_action( 'agncy_updated' );
		}

		/**
		 * Check for updates.
		 *
		 * @param stdClass $updates The updates object.
		 * @return stdClass
		 */
		public function check( $updates )
		{
			if ( ! isset( $updates->checked ) ) {
				return $updates;
			}

			/* Bump up the timeout for API requests. */
			add_filter( 'http_request_args', array( &$this, 'http_timeout' ), 10, 1 );

			// Attempt to get the purchase data.
			$purchase = $this->get_purchase();

			if ( isset( $purchase[ 'download' ] ) && ! empty( $purchase[ 'download' ] ) ) {
				$update = array(
					'url'         => "http://themeforest.net/item/theme/{$purchase[ 'item_id' ]}",
					'new_version' => $purchase[ 'version' ],
					'package'     => $purchase[ 'download' ],
					'theme'		  => $purchase[ 'theme' ]
				);

				$updates->response[ $purchase[ 'theme' ] ] = $update;
			}

			/* Restore the original timeout for API requests. */
			remove_filter( 'http_request_args', array( &$this, 'http_timeout' ), 10, 1 );

			return $updates;
		}

		/**
		 * Bump up the timeout for API requests.
		 *
		 * @since 1.0.0
		 * @param array $req The request arguments.
		 * @return array
		 */
		public function http_timeout( $req )
		{
			$req[ 'timeout' ] = 300;

			return $req;
		}

	}

endif;