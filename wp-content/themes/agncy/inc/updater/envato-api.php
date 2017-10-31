<?php

if ( ! class_exists( 'EvolveEnvatoAPI' ) ) :

	/**
	 * The Evolve Envato API class.
	 *
	 * @since 1.0.0
	 */
	class EvolveEnvatoAPI {

		/**
		 * API base URL.
		 *
		 * @var string
		 */
		private $_base = 'https://api.envato.com/';

        /**
         * API access mode. Can either be 'oauth' or 'personal'.
         *
         * @var string
         */
        private $_mode = 'oauth';

		/**
         * API access data.
         *
         * @var arra
         */
        private $_access = array(
			'access_token' => '', /* The access token. */
			'refresh_token' => '' /* The refresh token. */
		);

		/**
         * Application client ID.
         *
         * @var string
         */
        private $_client_id = '';

		/**
         * Application client secret.
         *
         * @var string
         */
        private $_client_secret = '';

        /**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{
		}

		/**
		 * Set the personal token for authentication and sets the class to
		 * operate in 'personal' mode.
		 *
		 * @since 1.0.0
		 * @param string $personal_token The access personal token.
		 */
		public function set_personal_token( $personal_token )
		{
			$this->_access['access_token'] = $personal_token;
			$this->_mode = 'personal';
		}

		/**
		 * Set the client information for authentication and sets the class to
		 * operate in 'oauth' mode.
		 *
		 * @since 1.0.0
		 * @param string $id The client ID.
		 * @param string $secrete The client secret.
		 */
		public function set_client( $id, $secret )
		{
			$this->_client_id = $id;
			$this->_client_secret = $secret;
			$this->_mode = 'oauth';
		}

		/**
		 * Check if the access mode is set to oAuth, or Personal.
		 *
		 * @since 1.0.0
		 * @return boolean
		 */
		private function is_oauth()
		{
			return $this->_mode === 'oauth';
		}

        /**
         * Check if we can connect to the API.
         *
         * @since 1.0.0
         * @return boolean
         */
        public function can_connect()
        {
            if ( in_array( 'curl', get_loaded_extensions() ) ) {
                return true;
            }

            return false;
        }

		/**
         * Check if we can connect to the API.
         *
         * @since 1.0.0
         */
		private function check_connection_ability()
		{
			if ( ! $this->can_connect() ) {
				throw new Exception( 'Unable to connect to the API.' );
			}
		}

		/**
		 * Enqueue parameters on GET requests.
		 *
		 * @since 1.0.0
		 * @param string $url The request URL.
		 * @param array $params The parameters array.
		 *
		 * @return string
		 */
		private function build_url( $url, $params = array() )
		{
			if ( empty( $params ) ) {
				return $url;
			}

			if ( strpos( $url, '?' ) === false ) {
				$url .= '?';
			}
			else {
				$url .= '&';
			}

			return $url . http_build_query( $params );
		}

		/**
		 * Perform a request to the API.
		 *
		 * @since 1.0.0
		 * @param string $url The API URL to be invoked.
		 * @param string $method The request method. Can either be 'GET' or 'POST'.
		 * @param array $headers Optional request headers.
		 * @param array $params Optional parameters to be sent in the request.
		 *
		 * @return array
		 */
        private function request( $url, $method = 'GET', $headers = array(), $params = array() )
        {
			$this->check_connection_ability();

            $curl = call_user_func( 'curl_' . 'init' );

            curl_setopt_array( $curl, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT        => 20,
				CURLOPT_HEADER         => false,
				CURLOPT_USERAGENT      => 'Evolve Envato API client',
            ) );

			if ( ! empty( $headers ) ) {
				curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
			}

			if ( $method === 'POST' ) {
				curl_setopt( $curl, CURLOPT_POST, true );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			}
			else {
				$url = $this->build_url( $url, $params );
				curl_setopt( $curl, CURLOPT_POST, 0 );
			}

			curl_setopt( $curl, CURLOPT_URL, $url );

			$resp = call_user_func( 'curl_' . 'exec', $curl );

			curl_close( $curl );

			$body = @json_decode( $resp, true );

			if ( ! $body ) {
				throw new Exception( 'API error.' );
			}

            return $body;
        }

		/**
		 * Authenticate via oAuth.
		 *
		 * @since 1.0.0
		 * @param string $code The application first authentication code.
		 *
		 * @return array
		 */
		public function authenticate( $code = '' )
		{
			if ( ! $this->is_oauth() ) {
				/* We're using a personal token, so authentication is not needed. */
				return;
			}

			$url = $this->_base . 'token';
			$params = array(
				'grant_type'     => 'authorization_code',
				'code'           => $code,
				'client_id'      => $this->_client_id,
				'client_secret'  => $this->_client_secret,
			);

			$resp = $this->request( $url, 'POST', array(), $params );

			if ( ! isset( $resp[ 'access_token' ] ) || ! isset( $resp[ 'refresh_token' ] ) ) {
				throw new Exception( 'Error retrieving the access token.' );
			}

			$this->_access[ 'access_token' ] = $resp[ 'access_token' ];
			$this->_access[ 'refresh_token' ] = $resp[ 'refresh_token' ];

			return $resp;
		}

		/**
		 * Refresh the authentication token.
		 *
		 * @since 1.0.0
		 * @param string $refresh_token The refresh token.
		 *
		 * @return array
		 */
		public function refresh_token( $refresh_token )
		{
			if ( ! $this->is_oauth() ) {
				/* We're using a personal token, so authentication is not needed. */
				return;
			}

			$url = $this->_base . 'token';
			$params = array(
				'grant_type'    => 'refresh_token',
				'refresh_token' => $refresh_token,
				'client_id'     => $this->_client_id,
				'client_secret' => $this->_client_secret,
			);

			$resp = $this->request( $url, 'POST', array(), $params );

			if ( ! isset( $resp[ 'access_token' ] ) ) {
				throw new Exception( 'Error retrieving the refresh token.' );
			}

			$this->_access[ 'access_token' ] = $resp[ 'access_token' ];

			return $resp;
		}

		/**
		 * Call an API endpoint and return the response in array format.
		 *
		 * @since 1.0.0
		 * @param string $endpoint The API endpoint.
		 * @param string $method The request method. Can either be 'GET' or 'POST'.
		 * @param array $params Optional parameters to be sent in the request.
		 *
		 * @return array
		 */
		public function api( $endpoint, $method = 'GET', $params = array() )
		{
			if ( empty( $this->_access['access_token'] ) ) {
			    throw new Exception( 'Empty token: authentication needed.' );
			}

			$headers = array(
				'Authorization: Bearer ' . $this->_access['access_token']
			);

			return $this->request( $this->_base . $endpoint, $method, $headers, $params );
		}

    }

endif;