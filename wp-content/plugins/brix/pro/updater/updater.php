<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Plugin updater class. Picks up information from the plugin server and
 * integrates update notifications in the WordPress updates screen.
 *
 * @since 1.0.0
 */
class BrixBuilderUpdater {

	/**
	 * The updater class instance.
	 *
	 * @static
	 * @var BrixBuilderUpdater
	 */
	private static $_instance = null;

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Plugin data.
	 *
	 * @var array
	 */
	private $pluginData;

	/**
	 * __FILE__ of our plugin.
	 *
	 * @var string
	 */
	private $pluginFile;

	/**
	 * API call result.
	 *
	 * @var array
	 */
	private $apiResult;

	/**
	 * Base URL for the API.
	 *
	 * @var string
	 */
	private $apiBase = 'https://justevolve.it/wp-json/evolve-shop/v1';

	/**
	 * Product name for the API.
	 *
	 * @var string
	 */
	private $apiProduct = 'brix';

	/**
	 * Constructor for the updater class.
	 *
	 * @since 1.0.0
	 * @param string $pluginFile The plugin file name.
	 * @param string $gitHubUsername The Github username.
	 * @param string $gitHubProjectName The project name on Github.
	 * @param string $accessToken Private access token on Github.
	 */
	function __construct( $pluginFile ) {
		$this->pluginFile  = $pluginFile;

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'setTransient' ) );

		if ( ! $this->can_update() ) {
			return;
		}

		add_filter( 'plugins_api', array( $this, 'setPluginInfo' ), 10, 3 );
		add_action( 'brix_admin_page_subheading[group:brix]', array( $this, 'showCustomUpdatesNotice' ), 20 );
		add_filter( 'upgrader_post_install', array( $this, 'postInstall' ), 10, 3 );

		/* Builder meta information. */
		add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

		/* Edit admin menus. */
		add_action( 'admin_menu', array( $this, 'edit_admin_menus' ), 100 );
	}

	/**
	 * Check if the plugin has updates notifications turned on.
	 *
	 * @since 0.1.0
	 * @return boolean
	 */
	public function can_update()
	{
		/* Check that the plugin folder name is 'brix'. */
		$folder_name_check = basename( dirname( $this->pluginFile ) ) === 'brix';

		/* Check that the plugin folder is not a checkout from a version control repo. */
		$vcss = array( '.svn', '.git', '.hg', '.bzr' );
		$is_vcs = false;

		foreach ( $vcss as $v ) {
			if ( @is_dir( BRIX_FOLDER . $v ) ) {
				$is_vcs = true;
				break;
			}
		}

		/* Check that the plugin has valid purchase code and email data. */
		$maintenance_info = $this->getPurchaseData();

		return $folder_name_check && ! $is_vcs && $maintenance_info !== false && apply_filters( 'brix_can_update', true );
	}

	/**
	 * Get information regarding our plugin from WordPress.
	 *
	 * @since 1.0.0
	 */
	private function initPluginData()
	{
		$this->slug = basename( dirname( $this->pluginFile ) );
		$this->baseslug = plugin_basename( $this->pluginFile );

		$this->pluginData = get_plugin_data( $this->pluginFile );
	}

	/**
	 * Check if the remote response is an error.
	 *
	 * @since 1.0.0
	 * @param WP_Response $response The response object.
	 * @return boolean
	 */
	private function isAPIError( $response )
	{
		if ( is_wp_error( $response ) ) {
			return true;
		}

		$body = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $body ) ) {
			return true;
		}

		return isset( $response['response']['code'] ) && $response['response']['code'] !== 200 ? true : false;
	}

	/**
	 * Perform a request to the API.
	 *
	 * @since 1.0.0
	 * @param string $url The request URL.
	 * @param string $method The request method.
	 * @param array $params An array of parameters.
	 * @return string|WP_Error
	 */
	private function doRequest( $url, $method, $params = array() )
	{
		$method = strtolower( $method );
		$response = false;

		foreach ( $params as $k => $v ) {
			$params[$k] = urlencode( $v );
		}

		$response = $this->performRequest( $url, $method, $params );

		if ( is_wp_error( $response ) ) {
			/* Let's try again with standard HTTP. */
			$url = str_replace( 'https', 'http', $url );
			$response = $this->performRequest( $url, $method, $params );
		}

		return $response;
	}

	/**
	 * Actually perform a request to the API.
	 *
	 * @since 1.2.3
	 * @param string $url The request URL.
	 * @param string $method The request method.
	 * @param array $params An array of parameters.
	 * @return string|WP_Error
	 */
	private function performRequest( $url, $method, $params )
	{
		switch ( $method ) {
			case 'post':
				$response = wp_safe_remote_post( $url, array(
					'body' => $params
				) );

				break;
			case 'get':
				$url = add_query_arg( $params, $url );
				$response = wp_safe_remote_get( $url );

				break;
			default:
				$response = new WP_Error( 'brix_updater_request_wrong_method', 'API request error: wrong method.' , array( 'status' => 403 ) );

				break;
		}

		return $response;
	}

	/**
	 * Retrieve the purchase data.
	 *
	 * @since 1.0.0
	 * @return boolean|array
	 */
	private function getPurchaseData()
	{
		/* Maintenance info. */
		$info           = get_option( 'brix_registration' );
		$purchase_code  = isset( $info['purchase_code'] ) ? $info['purchase_code'] : '';
		$purchase_email = isset( $info['purchase_email'] ) ? $info['purchase_email'] : '';

		if ( empty( $purchase_code ) || empty( $purchase_email ) ) {
			return false;
		}

		return array(
			'purchase_code' => $purchase_code,
			'purchase_email' => $purchase_email,
		);
	}

	/**
	 * Attempt to link the current domain.
	 *
	 * @since 1.0.0
	 */
	public function linkDomain()
	{
		$maintenance_info = $this->getPurchaseData();

		if ( $maintenance_info === false ) {
			return;
		}

		/* Query the API. */
		$url = $this->apiBase . '/link-domain';

		$parse = parse_url( home_url() );
		$domain = $parse['host'];

		/* Get the results. */
		$response = $this->doRequest( $url, 'post', array(
			'code'    => $maintenance_info['purchase_code'],
			'email'   => $maintenance_info['purchase_email'],
			'product' => $this->apiProduct,
			'domain'  => $domain
		) );

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $this->isAPIError( $response ) ) {
			if ( isset( $response->errors ) && ! empty( $response->errors ) ) {
				$first_error = current( $response->errors );

				if ( isset( $first_error[0] ) ) {
					$error_message = sprintf( __( 'There was an error communicating with Brix server (%s).', 'brix' ),
						esc_html( $first_error[0] )
					);

					return new WP_Error( 'brix_shop_api_get_info_domain_link', $error_message, array( 'status' => 403 ) );
				}
			}

			return new WP_Error( 'brix_shop_api_get_info_domain_link', $body->message, array( 'status' => 403 ) );
		}

		return $body;
	}

	/**
	 * Retrieve the product changelog.
	 *
	 * @since 1.0.0
	 */
	private function getChangelog()
	{
		/* Query the API. */
		$url = $this->apiBase . '/get-changelog';

		/* Get the results. */
		$response = $this->doRequest( $url, 'get', array(
			'product' => $this->apiProduct
		) );

		if ( ! $this->isAPIError( $response ) ) {
			$result = wp_remote_retrieve_body( $response );

			if ( ! empty( $result ) ) {
				$result = @json_decode( $result );

				update_option( 'brix_changelog', $result );
			}
		}
	}

	/**
	 * Check if there are updates against the current version, no matter if the
	 * product was registered or not.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function hasUpdates()
	{
		$changelog = get_option( 'brix_changelog' );

		if ( ! $changelog ) {
			return false;
		}

		$is_framework_up_to_date = true;

		if ( isset( $changelog->requires_fw ) && ! empty( $changelog->requires_fw ) ) {
			$is_framework_up_to_date = version_compare( BRIX_FRAMEWORK_VERSION, $changelog->requires_fw ) >= 0;
		}

		return
			$is_framework_up_to_date &&
			version_compare( $changelog->new_version, BRIX_VERSION ) === 1;
	}

	/**
	 * Show a custom updates notice in admin pages.
	 *
	 * @since 1.0.0
	 */
	public function showCustomUpdatesNotice()
	{
		if ( $this->hasUpdates() ) {
			$changelog_url = BRIX_CHANGELOG_URI;
			$how_to_update_url = BRIX_DOCS_URI . 'getting-started/introduction#how-to-update';

			echo '<div class="brix-update-notice">';
				printf( '<span class="brix-update-available">%s</span>', esc_html( __( 'Update available!', 'brix' ) ) );

				if ( ! empty( $changelog_url ) ) {
					printf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
						esc_attr( $changelog_url ),
						esc_html( __( 'What\'s changed?', 'brix' ) )
					);
				}

				printf( '<a href="%s" target="_blank" rel="noopener noreferrer" id="brix-how-to-update">%s</a>',
					esc_attr( $how_to_update_url ),
					esc_html( __( 'How to update', 'brix' ) )
				);
			echo '</div>';
		}
	}

	/**
	 * Get information regarding our plugin from GitHub.
	 *
	 * @since 1.0.0
	 */
	private function getInfo()
	{
		/* Maintenance info. */
		$maintenance_info = $this->getPurchaseData();

		if ( $maintenance_info === false ) {
			return;
		}

		/* Only do this once. */
		if ( ! empty( $this->apiResult ) ) {
			return;
		}

		/* Clear the API cache. */
		$this->apiResult = null;

		/* Query the API. */
		$url = $this->apiBase . '/get-info';

		$parse = parse_url( home_url() );
		$domain = $parse['host'];

		/* Get the results. */
		$response = $this->doRequest( $url, 'get', array(
			'code'    => $maintenance_info['purchase_code'],
			'email'   => $maintenance_info['purchase_email'],
			'product' => $this->apiProduct,
			'domain'  => $domain
		) );

		if ( ! $this->isAPIError( $response ) ) {
			$this->apiResult = wp_remote_retrieve_body( $response );

			if ( ! empty( $this->apiResult ) ) {
				$this->apiResult = @json_decode( $this->apiResult );
			}
		}
		else {
			if ( is_array( $response ) && isset( $response['body'] ) ) {
				$error = @json_decode( wp_remote_retrieve_body( $response ) );

				if ( isset( $error->data ) && isset( $error->data->code ) && $error->data->code == 5 ) {
					// License expired, show a notice to the user
				}
			}
		}
	}

	/**
	 * Push in plugin version information to get the update notification.
	 *
	 * @param string $transient The transient data.
	 * @return stdClass
	 */
	public function setTransient( $transient )
	{
		/* Only retrieve the product changelog. */
		$this->getChangelog();

		/* Bail if we cannot update the plugin. */
		if ( ! $this->can_update() ) {
			return $transient;
		}

		/* If we have checked the plugin data before, don't re-check. */
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		/* Get plugin release information. */
		$this->initPluginData();
		$this->getInfo();

		if ( empty( $this->apiResult ) ) {
			/* If this fails, the user might not be entitled to receive updates. */
			return $transient;
		}

		/* Check the versions if we need to do an update. */
		if ( $this->hasUpdates() ) {
			$obj                                  = new stdClass();
			$obj->slug                            = $this->slug;
			$obj->plugin                          = $this->baseslug;
			$obj->new_version                     = $this->apiResult->new_version;
			$obj->url                             = $this->pluginData["PluginURI"];
			$obj->package                         = $this->apiResult->download_link;
			$transient->response[$this->baseslug] = $obj;
		}

		return $transient;
	}

	/**
	 * Push in plugin version information to display in the details lightbox.
	 *
	 * @param boolean $false
	 * @param string $action
	 * @param stdClass $response The response object.
	 * @return stdClass
	 */
	public function setPluginInfo( $false, $action, $response ) {
		/* Get plugin release information. */
		$this->initPluginData();
		$this->getInfo();

		/* If nothing is found, do nothing. */
		if ( empty( $response->slug ) || $response->slug != $this->slug ) {
			return false;
		}

		if ( empty( $this->apiResult ) ) {
			return false;
		}

		/* Add our plugin information. */
		$response->last_updated = $this->apiResult->last_updated;
		$response->slug         = $this->slug;
		$response->plugin_name  = $this->pluginData["Name"];
		$response->name         = $this->pluginData["Name"];
		$response->version      = $this->apiResult->new_version;
		$response->author       = $this->pluginData["AuthorName"];
		$response->homepage     = $this->pluginData["PluginURI"];

		/* This is our release download zip file. */
		$response->download_link = '';

		$changelog = isset( $this->apiResult->changelog ) ? $this->apiResult->changelog : '';

		/* Create tabs in the lightbox. */
		$response->sections = array(
			'description' => $this->pluginData["Description"],
			'changelog' => $changelog
		);

		/* Gets the required version of WP if available. */
		if ( isset( $this->apiResult->requires ) ) {
			$response->requires = $this->apiResult->requires;
		}

		/* Gets the tested version of WP if available. */
		if ( isset( $this->apiResult->tested ) ) {
			$response->tested = $this->apiResult->tested;
		}

		/* Gets the required version of the Evolve Framework if available. */
		if ( isset( $this->apiResult->requires_fw ) ) {
			$response->requires_fw = $this->apiResult->requires_fw;
		}

		return $response;
	}

	/**
	 * Perform additional actions to successfully install our plugin.
	 *
	 * @param boolean $true
	 * @param mixed $hook_extra
	 * @param array $result
	 * @return array
	 */
	public function postInstall( $true, $hook_extra, $result ) {
		// Get plugin information
		$this->initPluginData();

		// Remember if our plugin was previously activated
		$wasActivated = is_plugin_active( $this->baseslug );

		global $wp_filesystem;
		$pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->baseslug );
		$wp_filesystem->move( $result['destination'], $pluginFolder );
		$result['destination'] = $pluginFolder;

		// Re-activate plugin if needed
		if ( $wasActivated ) {
			$activate = activate_plugin( $this->baseslug );
		}

		return $result;
	}

	/**
	 * Add meta information to the plugin row in the Plugins screen in WordPress admin.
	 *
	 * @since 1.1.3
	 * @param array  $plugin_meta An array of the plugin's metadata,
	 *                            including the version, author,
	 *                            author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
	 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
	 *                            'Drop-ins', 'Search'.
	 * @return array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status )
	{
		$framework_data = get_plugin_data( BRIX_PLUGIN_FILE );
		$check = array( 'Name', 'PluginURI', 'AuthorName', 'AuthorURI', 'Version', 'TextDomain' );

		/* If this is not our plugin, exit. */
		foreach ( $check as $key ) {
			if ( $plugin_data[$key] !== $framework_data[$key] ) {
				return $plugin_meta;
			}
		}

		if ( ( ! isset( $plugin_data['update'] ) || ! $plugin_data['update'] ) && $this->can_update() && $this->hasUpdates() ) {
			$maintenance_page = admin_url( 'admin.php?page=brix_registration' );

			$update_notice = sprintf(
				__( 'An update is available, but you either didn\'t fill out the <a href="%s">account options</a>, or your license has expired.', 'brix' ),
				esc_attr( $maintenance_page )
			);

			$plugin_meta[] = sprintf( '<strong class="brix-update-available">%s</strong>',
				wp_kses_post( $update_notice )
			);
		}

		return $plugin_meta;
	}

	/**
	 * Edit admin menus.
	 *
	 * @since 1.0.0
	 */
	public function edit_admin_menus() {
		if ( ! BrixBuilder::has_instance() ) {
			return;
		}

		global $menu;

		$builder = BrixBuilder::instance();

		if ( $this->can_update() && $this->hasUpdates() ) {
			foreach ( $menu as &$menu_item ) {
				if ( $menu_item[2] === 'brix' ) {
					$menu_item[0] .= ' <span class="awaiting-mod count-1"><span class="pending-count">' . __( 'Update', 'brix' ) . '</span></span>';

					break;
				}
			}
		}
	}

	/**
	 * Return the instance of the builder class.
	 *
	 * @static
	 * @since 1.1.3
	 * @return BrixBuilderUpdater
	 */
	public static function instance()
	{
		if ( self::$_instance === null ) {
			self::$_instance = new BrixBuilderUpdater( BRIX_PLUGIN_FILE );
		}

		return self::$_instance;
	}

}

/* Attach the updater controller */
BrixBuilderUpdater::instance();