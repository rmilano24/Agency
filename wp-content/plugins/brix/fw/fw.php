<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Evolve Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Evolve Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @package   BrixFramework
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_Framework {

	/**
	 * The framework class instance.
	 *
	 * @static
	 * @var Brix_Framework
	 */
	private static $_instance = null;

	/**
	 * The admin controller.
	 *
	 * @var Brix_AdminController
	 */
	private $_admin = null;

	/**
	 * The login controller.
	 *
	 * @var Brix_LoginController
	 */
	private $_login = null;

	/**
	 * The media manager.
	 *
	 * @var Brix_MediaManager
	 */
	private $_media = null;

	/**
	 * The frontend interface.
	 *
	 * @var Brix_FrontendInterface
	 */
	private $_frontend = null;

	/**
	 * The theme configuration array.
	 *
	 * @var array
	 */
	private $_config = array();

	/**
	 * Contructor for the main framework class. This function defines a list of
	 * constants used throughout the framework and bootstraps the framework
	 * and launch the inclusion of files and libraries.
	 *
	 * @since 0.1.0
	 */
	function __construct()
	{
		/* Framework. */
		define( 'BRIX_FW', true );

		/* Framework version number. */
		define( 'BRIX_FRAMEWORK_VERSION', '1.0.6' );

		/* Theme folder. */
		define( 'BRIX_THEME_FOLDER', trailingslashit( get_template_directory() ) );

		/* Theme URI. */
		define( 'BRIX_THEME_URI', trailingslashit( get_template_directory_uri() ) );

		/* Child theme folder. */
		define( 'BRIX_CHILD_THEME_FOLDER', trailingslashit( get_stylesheet_directory() ) );

		/* Child theme URI. */
		define( 'BRIX_CHILD_THEME_URI', trailingslashit( get_stylesheet_directory_uri() ) );

		/* Framework folder. */
		define( 'BRIX_FRAMEWORK_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

		/* Framework main file path. */
		define( 'BRIX_FRAMEWORK_MAIN_FILE_PATH', basename( BRIX_FRAMEWORK_FOLDER ) . '/evolve-framework.php' );

		/* Framework URI. */
		define( 'BRIX_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) );

		/* Framework includes folder. */
		define( 'BRIX_FRAMEWORK_INCLUDES_FOLDER', trailingslashit( BRIX_FRAMEWORK_FOLDER . 'includes' ) );

		/* Framework classes folder. */
		define( 'BRIX_FRAMEWORK_CLASSES_FOLDER', trailingslashit( BRIX_FRAMEWORK_FOLDER . 'classes' ) );

		/* Framework templates folder. */
		define( 'BRIX_FRAMEWORK_TEMPLATES_FOLDER', trailingslashit( BRIX_FRAMEWORK_FOLDER . 'templates' ) );

		/* Framework includes. */
		$this->_includes();

		/* Framework bootstrap. */
		$this->_bootstrap();
	}

	/**
	 * Load internationalization functions and the framework text domain.
	 *
	 * @since 0.1.0
	 */
	private function _i18n()
	{
		/* Localize framework strings. */
		add_action( 'admin_enqueue_scripts', array( $this, 'i18n_strings' ) );
	}

	/**
	 * Localize framework strings.
	 *
	 * @since 0.4.0
	 */
	public function i18n_strings()
	{
		global $wp_version;

		wp_localize_script( 'jquery', 'brix_framework', array(
			'wp_version' => $wp_version,
			'editor' => array(
				'text' => __( 'Text', 'brix' ),
				'visual' => __( 'Visual', 'brix' ),
				'add_media' => __( 'Add Media', 'brix' ),
			),
			'color' => array(
				'presets' => brix_get_color_presets(),
				'new_preset_name' => __( 'Insert a name for the preset', 'brix' )
			),
			'link' => array(
				'create' => __( 'Insert this URL', 'brix' )
			)
		) );
	}

	/**
	 * Bootstrap the framework. This method runs a series of operations that
	 * are needed by the framework to operate correctly, such as loading the
	 * controllers for the admin part of the website; the text domain
	 * for the framework is also loaded by this method.
	 *
	 * @since 0.1.0
	 */
	private function _bootstrap()
	{
		/* Load internationalization functions and the framework text domain. */
		$this->_i18n();

		/* Instantiate the controller of the admin area. */
		$this->_admin = new Brix_AdminController();

		/* Instantiate the class of the frontend controller. */
		$this->_frontend = new Brix_FrontendController();

		/* Instantiate the controller of the theme login and registration screens. */
		$this->_login = new Brix_LoginController();

		/* Instantiate the class of the theme media manager. */
		$this->_media = new Brix_MediaManager();
	}

	/**
	 * Load the framework functions. These functions can either be utility
	 * helpers as well as functions enabling specific functionalities; as such
	 * their behavior can be altered via filters or overriding them all
	 * together.
	 *
	 * @since 0.1.0
	 */
	private function _includes()
	{
		/* String utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'string.php' );

		/* General system utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'system.php' );

		/* Button utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'button.php' );

		/* Templating utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'templates.php' );

		/* Images utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'images.php' );

		/* Icons utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'icons.php' );

		/* Media utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'media.php' );

		/* Array utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'array.php' );

		/* Link utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'link.php' );

		/* Color utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'color.php' );

		/* Notices utilities. */
		require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'admin/notices.php' );

		/* Admin utilities */
		if ( is_admin() ) {
			/* Fields utilities. */
			require_once( BRIX_FRAMEWORK_INCLUDES_FOLDER . 'admin/fields.php' );
		}

		/* Core classes. */
		$this->_includes_core();
	}

	/**
	 * Load the framework core classes.
	 *
	 * @since 0.1.0
	 */
	private function _includes_core()
	{
		/* List array wrapper. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/libs/brix_list.php' );

		/* Rich text editor adapter. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/libs/js_wp_editor.php' );

		/* Query class. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_query.php' );

		/* Pages controller. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_controller.php' );

		/* Admin pages controller. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/controllers/brix_admincontroller.php' );

		/* Frontend interface & controller. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_frontend_interface.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/controllers/brix_frontendcontroller.php' );

		/* Login and registration pages controller. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/controllers/brix_logincontroller.php' );

		/* Media manager class. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_media_manager.php' );

		/* Frontend interface class. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_frontend_interface.php' );

		/* Fields. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_text_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_textarea_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_number_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_checkbox_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_radio_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_multiple_select_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_select_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_image_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_attachment_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_divider_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_description_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_color_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_icon_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_date_field.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields/brix_bundle_field.php' );

		/* Fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_fields_container.php' );

		/* Meta box fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_meta_box.php' );

		/* Modal fields containers. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_modal.php' );
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/brix_simple_modal.php' );

		/* Admin page fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_admin_page.php' );

		/* Admin menu page fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_menu_page.php' );

		/* Admin theme menu page fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_theme_page.php' );

		/* Admin submenu page fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_submenu_page.php' );

		/* User meta box fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_user_meta_box.php' );

		/* Taxonomy meta box fields container. */
		require_once( BRIX_FRAMEWORK_CLASSES_FOLDER . 'core/fields_containers/brix_taxonomy_meta_box.php' );
	}

	/**
	 * Return the instance of the admin controller.
	 *
	 * @since 0.1.0
	 * @return Brix_AdminController The instance of the admin controller.
	 */
	public function admin()
	{
		return $this->_admin;
	}

	/**
	 * Return the instance of the login controller.
	 *
	 * @since 0.1.0
	 * @return Brix_LoginController The instance of the login controller.
	 */
	public function login()
	{
		return $this->_login;
	}

	/**
	 * Return the instance of the media manager class.
	 *
	 * @since 0.1.0
	 * @return Brix_MediaManager The instance of the media manager class.
	 */
	public function media()
	{
		return $this->_media;
	}

	/**
	 * Return the instance of the frontend interface.
	 *
	 * @since 0.1.0
	 * @return Brix_FrontendInterface The instance of the frontend interface.
	 */
	public function frontend()
	{
		return $this->_frontend;
	}

	/**
	 * Return the theme configuration array.
	 *
	 * @since 0.1.0
	 * @return Brix_LoginController The theme configuration array.
	 */
	public function config()
	{
		return $this->_config;
	}

	/**
	 * Add a configuration setting to the theme configuration array.
	 *
	 * @since 0.1.0
	 * @param string $key The configuration key.
	 * @param mixed $value The configuration value.
	 */
	public function set_config( $key, $value )
	{
		if ( is_array( $value ) ) {
			if ( ! isset( $this->_config[$key] ) ) {
				$this->_config[$key] = array();
			}

			$this->_config[$key] = wp_parse_args( $value, $this->_config[$key] );
		}
		else {
			$this->_config[$key] = $value;
		}
	}

	/**
	 * Return the instance of the framework class.
	 *
	 * @static
	 * @since 0.1.0
	 * @return Brix_Framework
	 */
	public static function instance()
	{
		if ( self::$_instance === null ) {
			self::$_instance = new Brix_Framework();
		}

		return self::$_instance;
	}

}

/* Let the fun begin! */
Brix_Framework::instance();