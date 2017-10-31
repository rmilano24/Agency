<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Style addons class.
 *
 * @package   BrixAddons
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class BrixAddons {

	/**
	 * The plugin class instance.
	 *
	 * @static
	 * @var BrixAddons
	 */
	private static $_instance = null;

	/**
	 * Contructor for the main plugin class. This function defines a list of
	 * constants used throughout the plugin and bootstraps the plugin
	 * and launch the inclusion of files and libraries.
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		/* BrixAddons. */
		define( 'BRIX_ADDONS', true );

		/* BrixAddons folder. */
		define( 'BRIX_ADDONS_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

		/* BrixAddons URI. */
		define( 'BRIX_ADDONS_URI', trailingslashit( BRIX_URI . 'styles' ) );

		/* BrixAddons blocks folder. */
		define( 'BRIX_ADDONS_BLOCKS_FOLDER', trailingslashit( BRIX_ADDONS_FOLDER . 'blocks' ) );

		/* BrixAddons assets uri. */
		define( 'BRIX_ADDONS_ASSETS_URI', trailingslashit( BRIX_ADDONS_FOLDER . 'assets/admin' ) );

		/* BrixAddons includes. */
		$this->_includes();

		/* BrixAddons bootstrap. */
		$this->_bootstrap();
	}

	/**
	 * Bootstrap the plugin. This method runs a series of operations that
	 * are needed by the plugin to operate correctly.
	 *
	 * @since 1.0.0
	 */
	private function _bootstrap()
	{
		/* Add scripts and styles on frontend. */
		add_action( 'wp_enqueue_scripts', array( $this, 'add_frontend_assets' ) );

		/* Automatically load icon fonts on frontend. */
		add_filter( 'brix_autoload_icon_fonts', '__return_true' );

		/* Add scripts and styles on admin. */
		// add_action( 'admin_init', array( $this, 'add_admin_assets' ) );
	}

	/**
	 * Load the plugin functions. These functions can either be utility
	 * helpers as well as functions enabling specific functionalities; as such
	 * their behavior can be altered via filters or overriding them all
	 * together.
	 *
	 * @since 1.0.0
	 */
	private function _includes()
	{
		/* Helpers. */
		require_once( BRIX_ADDONS_FOLDER . 'includes/colors.php' );

		/* Content blocks. */
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'divider/divider.php' );
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'button/button.php' );
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'feature_box/feature_box.php' );
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'list/list.php' );
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'tabs/tabs.php' );
		require_once( BRIX_ADDONS_BLOCKS_FOLDER . 'accordion/accordion.php' );
	}

	/**
	 * Add scripts and styles on frontend.
	 *
	 * @since 1.0.0
	 */
	public function add_frontend_assets()
	{
		/* Main BrixAddons stylesheet. */
		brix_fw()->frontend()->add_style( 'brix-addons-style', BRIX_ADDONS_URI . 'assets/frontend/css/style.css' );
	}

	/**
	 * Add scripts and styles on admin.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_assets()
	{
	}

	/**
	 * Return the instance of the class.
	 *
	 * @static
	 * @since 1.0.0
	 * @return BrixAddons
	 */
	public static function instance()
	{
		if ( self::$_instance === null ) {
			self::$_instance = new BrixAddons();
		}

		return self::$_instance;
	}

}

BrixAddons::instance();