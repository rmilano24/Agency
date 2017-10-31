<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Plugin Name: Brix Builder
 * Description: A new way of managing content in WordPress.
 * Version: 1.2.15
 * Plugin URI: http://brixbuilder.com
 * Author: Evolve
 * Author URI: http://justevolve.it
 * License: GPL2
 * Text Domain: brix
 * Domain Path: /languages/
 *
 * Brix Builder is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Brix Builder is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @package   BrixBuilder
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( ! class_exists( 'BrixBuilder' ) ) :

class BrixBuilder {

	/**
	 * The builder class instance.
	 *
	 * @static
	 * @var BrixBuilder
	 */
	private static $_instance = null;

	/**
	 * Columns counter.
	 *
	 * @var integer
	 */
	private $_columns = 0;

	/**
	 * Blocks counter.
	 *
	 * @var integer
	 */
	private $_blocks = 0;

	/**
	 * The current page ID.
	 *
	 * @var array
	 */
	private $_page_id = 0;

	/**
	 * Post Types the page builder is enabled for.
	 *
	 * @var array
	 */
	private $_screens = array();

	/**
	 * Post Types the page builder is enabled for.
	 *
	 * @var array
	 */
	private $_depth = 0;

	/**
	 * Processed data.
	 *
	 * @var array
	 */
	private $processed_data = array();

	/**
	 * Supports array.
	 *
	 * @var array
	 */
	private $supports = array();

	/**
	 * Contructor for the main builder class. This function defines a list of
	 * constants used throughout the builder and bootstraps the builder
	 * and launch the inclusion of files and libraries.
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		/* Builder version number. */
		define( 'BRIX_VERSION', '1.2.15' );

		/* Builder URI. */
		define( 'BRIX_URI', plugin_dir_url( BRIX_PLUGIN_FILE ) );

		/* Builder classes folder. */
		define( 'BRIX_CLASSES_FOLDER', trailingslashit( BRIX_FOLDER . 'classes' ) );

		/* Builder fields folder. */
		define( 'BRIX_FIELDS_FOLDER', trailingslashit( BRIX_FOLDER . 'fields' ) );

		/* Builder templates folder. */
		define( 'BRIX_TEMPLATES_FOLDER', trailingslashit( BRIX_FOLDER . 'templates' ) );

		/* Builder content blocks folder. */
		define( 'BRIX_BLOCKS_FOLDER', trailingslashit( BRIX_FOLDER . 'blocks' ) );

		/* Builder assets URI. */
		define( 'BRIX_ADMIN_ASSETS_URI', trailingslashit( BRIX_URI . 'assets/admin' ) );

		/* Builder assets folder. */
		define( 'BRIX_ADMIN_ASSETS_FOLDER', trailingslashit( BRIX_FOLDER . 'assets/admin' ) );

		/* Documentation public URI. */
		define( 'BRIX_DOCS_URI', 'https://justevolve.it/docs/brix' );

		/* Support public URI. */
		define( 'BRIX_SUPPORT_URI', 'https://justevolve.it/wp-login.php' );

		/* Changelog public URI. */
		define( 'BRIX_CHANGELOG_URI', 'https://justevolve.it/changelog/brix' );

		/* Builder includes. */
		$this->_includes();

		/* Builder bootstrap. */
		$this->_bootstrap();
	}

	/**
	 * Load internationalization functions and the builder text domain.
	 *
	 * @since 1.0.0
	 */
	private function _i18n()
	{
		/* Load the text domain for builder files. */
		load_plugin_textdomain( 'brix', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Return an array of post types for which the builder has been enabled.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function _get_screens()
	{
		$post_types = array();
		$post_types_option = brix_get_option( 'post_types' );

		if ( $post_types_option === false ) {
			$post_types[] = 'page';
		}
		elseif ( ! empty( $post_types_option ) ) {
			$post_types = explode( ',', $post_types_option );
		}

		return apply_filters( 'brix_post_types', $post_types );
	}

	/**
	 * Bootstrap the builder. This method runs a series of operations that
	 * are needed by the builder to operate correctly.
	 *
	 * @since 1.0.0
	 */
	private function _bootstrap()
	{
		/* Load internationalization functions and the builder text domain. */
		$this->_i18n();

		if ( ! brix_install_check() ) {
			return;
		}

		/* Adding builder-specific responsive breakpoints. */
		brix_add_breakpoint( 0, 'mobile', _x( 'Mobile', 'responsive breakpoint', 'brix' ), 'mobile', '@media screen and (max-width: 767px)' );

		/* Add the builder meta box to the enabled post types. */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 10, 2 );

		/* Register the saving action. */
		add_action( 'save_post', array( $this, 'save' ) );

		/* Add templates on admin. */
		add_action( 'admin_footer', array( $this, 'templates' ) );

		/* Enqueue the builder data. */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_data' ) );

		/* Add scripts and styles on frontend. */
		add_action( 'wp_head', array( $this, 'add_frontend_assets' ), 0 );

		/* Localize frontend assets. */
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_frontend_data' ), 30 );

		/* Select where to attach the builder. */
		add_action( 'wp_head', array( $this, 'pre_render_frontend' ) );

		/* Process the builder data for frontend display. */
		add_action( 'wp_enqueue_scripts', array( $this, 'process_frontend' ), 0 );

		/* Prepare the post content for rendering. */
		add_filter( 'content_pagination', array( $this, 'prepare_post_content' ), 10, 2 );

		/**
		 * Builder global options.
		 * You can deregister the creation of the options page in the theme by
		 * unattaching this hook:
		 *
		 * remove_action( 'init', array( BrixBuilder::instance(), 'options' ), 50 );
		 */
		add_action( 'init', array( $this, 'options' ), 50 );
		add_filter( 'brix_admin_page_icon[page:brix]', array( $this, 'menu_icon' ) );
		add_action( 'brix_admin_page_subheading[group:brix]', array( $this, 'page_subheading' ) );

		/**
		 * Kind of hackish way of storing a temporary version of the builder
		 * data, when openin a post for a preview.
		 */
		add_filter( 'preview_post_link', array( $this, 'save_preview' ), 10, 2 );

		/* AJAX load a builder template. */
		add_action( 'wp_ajax_brix_load_template', array( $this, 'load_template' ) );

		/* Builder interface toggle button. */
		add_action( 'edit_form_after_title', array( $this, 'interface_toggle_button' ) );

		/* Add a set of body classes when the builder is active in the page/post editing screen. */
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		/* Add a set of body classes when the builder is active in the page/post frontend. */
		add_filter( 'body_class', array( $this, 'body_class' ) );


		add_action( 'init', array( $this, 'load_styles' ) );
	}

	/**
	 * Load the builder functions. These functions can either be utility
	 * helpers as well as functions enabling specific functionalities; as such
	 * their behavior can be altered via filters or overriding them all
	 * together.
	 *
	 * @since 1.0.0
	 */
	private function _includes()
	{
		/* General utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'helpers.php' );

		/* Templates utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'templates.php' );

		/* Section utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'section.php' );

		/* Row utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'row.php' );

		/* Column utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'column.php' );

		/* Block utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'block.php' );

		/* Options. */
		require_once( BRIX_INCLUDES_FOLDER . 'options.php' );

		/* Post utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'post.php' );

		/* Frontend utilities. */
		require_once( BRIX_INCLUDES_FOLDER . 'frontend.php' );

		/* Frontend spacing. */
		require_once( BRIX_INCLUDES_FOLDER . 'spacing.php' );

		/* Background helpers. */
		require_once( BRIX_INCLUDES_FOLDER . 'background.php' );

		/* Icon helpers. */
		require_once( BRIX_BLOCKS_FOLDER . 'icon/helpers.php' );

		/* Fields. */
		require_once( BRIX_FIELDS_FOLDER . 'brix_spacing.php' );
		require_once( BRIX_FIELDS_FOLDER . 'brix_breakpoints.php' );
		require_once( BRIX_FIELDS_FOLDER . 'brix_background.php' );
		require_once( BRIX_FIELDS_FOLDER . 'brix_video_background.php' );
		require_once( BRIX_FIELDS_FOLDER . 'tabs.php' );
		require_once( BRIX_FIELDS_FOLDER . 'builder.php' );
		require_once( BRIX_FIELDS_FOLDER . 'accordion.php' );
		require_once( BRIX_FIELDS_FOLDER . 'widget_transport.php' );

		/* Content blocks. */
		require_once( BRIX_BLOCKS_FOLDER . 'block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'loop_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'text/text_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'divider/divider_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'blog/blog_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'single_post/single_post_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'widget_area/widget_area_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'tabs/tabs_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'accordion/accordion_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'feature_box/feature_box_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'list/list_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'image/image_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'icon/icon_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'button/button_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'html/html_block.php' );
		require_once( BRIX_BLOCKS_FOLDER . 'widget/widget_block.php' );

		/* Pro features. */
		require_once( BRIX_FOLDER . 'pro/pro.php' );

	}

	/**
	 * Load styles and effects
	 *
	 * @since 1.2.7
	 */
	public function load_styles() {
		/* Support for default block styles. */
		define( 'BRIX_STYLES', apply_filters( 'brix_enable_styles', true ) );

		/* Support for default block entrance effects. */
		define( 'BRIX_EFFECTS', apply_filters( 'brix_enable_effects', true ) );

		/* Styles. */
		if ( BRIX_STYLES ) {
			require_once( BRIX_FOLDER . 'styles/styles.php' );
		}

		/* Effects. */
		if ( BRIX_EFFECTS ) {
			require_once( BRIX_FOLDER . 'effects/effects.php' );
		}
	}

	/**
	 * Localize frontend assets.
	 *
	 * @since 1.1.1
	 */
	public function localize_frontend_data()
	{
		$frontend_data = array(
			'breakpoints' => brix_breakpoints(),
			'responsive' => brix_get_option( 'responsive_breakpoints' )
		);

		$frontend_data = apply_filters( 'brix_localize_frontend_data', $frontend_data );

		/* Builder environment variables. */
		wp_localize_script( 'brix-script', 'brix_env', $frontend_data );
	}

	/**
	 * Add scripts and styles on frontend.
	 *
	 * @since 1.0.0
	 */
	public function add_frontend_assets()
	{
		/* Admin bar. */
		if ( is_admin_bar_showing() ) {
			brix_fw()->frontend()->add_inline_style( '#wpadminbar .brix-buy a{color:#D9EB3D !important}#wpadminbar .brix-buy svg{position:relative !important;bottom:-4px}#wpadminbar .brix-buy svg path{fill:#D9EB3D}' );
		}

		if ( ! $this->is_frontend_using_builder() ) {
			return;
		}

		/* Register global libraries. */
		brix_fw()->frontend()->register_script( 'brix-flickity', BRIX_URI . 'assets/frontend/js/core/lib/flickity.pkgd.min.js', array( 'jquery' ), '2.0.5' );

		$suffix = brix_get_scripts_suffix();

		/* Main builder stylesheet. */
		brix_fw()->frontend()->add_style( 'brix-style', BRIX_URI . 'assets/frontend/css/style.css' );

		/* Main builder script. */
		brix_fw()->frontend()->add_script( 'brix-script', BRIX_URI . 'assets/frontend/js/min/builder.' . $suffix . '.js' );
	}

	/**
	 * Check if the current user is entitled to use Brix.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	private function _current_user_can_use_builder()
	{
		$current_user = wp_get_current_user();
		$user_roles = $current_user->roles;
		$user_role = array_shift( $user_roles );

		$roles = array();

		if ( defined( 'BRIX_USER_ROLES_MANAGEMENT' ) && BRIX_USER_ROLES_MANAGEMENT ) {
			$roles_option = brix_get_option( 'user_roles' );

			if ( ! empty( $roles_option ) ) {
				$roles = explode( ',', $roles_option );
			}
		}

		$roles[] = 'administrator';

		$user_can = in_array( $user_role, $roles ) || ( is_multisite() && is_super_admin() );

		return $user_can;
	}

	/**
	 * Check if the builder is enabled for the current post type. If the current
	 * screen is displaying a page, optionally check for page template too.
	 *
	 * @since 1.0.0
	 * @param integer $post_id The post ID.
	 * @return boolean
	 */
	private function check_builder_enabled( $post_id = false )
	{
		$add = true;

		$post_type = false;

		if ( $post_id ) {
			$post_type = get_post_type( $post_id );
		}
		elseif ( isset( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( $post_type ) {
			if ( ! in_array( $post_type, $this->_get_screens() ) ) {
				return false;
			}
		}
		else {
			if ( $post_id ) {
				$post = get_post( $post_id );
			}
			else {
				global $post;
			}

			if ( $post ) {
				$post_type = get_post_type( $post->ID );

				if ( ! in_array( $post_type, $this->_get_screens() ) ) {
					return false;
				}

				$add = apply_filters( "brix[post_type:$post_type]", $add );

				if ( $post_type === 'page' ) {
					$template = get_post_meta( $post->ID, '_wp_page_template', true );

					if( ! $template ) {
						$template = 'default';
					}

					$add = apply_filters( "brix[post_type:page][template:$template]", $add );
				}
			}
		}

		$add = apply_filters( 'brix_check_builder_enabled', $add );

		return $add;
	}

	/**
	 * Check if the current page is using the builder in lieu of the content.
	 *
	 * @since 1.0.0
	 * @param integer $post_id The post ID.
	 * @return boolean
	 */
	private function is_using_builder( $post_id = false )
	{
		if ( ! $post_id ) {
			global $post;

			if ( $post ) {
				$post_id = $post->ID;
			}
		}

		if ( $post_id ) {
			$brix_enabled = $this->check_builder_enabled( $post_id );
			$brix_used = (int) get_post_meta( $post_id, 'brix_used', true ) || (int) get_post_meta( $post_id, '_brix_used', true );

			$using_builder = apply_filters( 'brix_is_using_builder', ( $brix_used && $brix_enabled ) );

			return $using_builder;
		}

		return false;
	}

	/**
	 * Add a set of body classes when the builder is active in the page/post editing screen.
	 *
	 * @since 1.0.0
	 * @param string $classes Admin body classes.
	 * @return string
	 */
	public function admin_body_class( $classes )
	{
		if ( $this->check_builder_enabled() ) {
			$classes .= ' brix-enabled';
			$classes .= ' brix-is-backend-editing';

			if ( brix_has_templates() ) {
				$classes .= ' brix-has-templates';

				if ( brix_has_user_templates() ) {
					$classes .= ' brix-has-user-templates';
				}

				if ( brix_has_sticky_templates() ) {
					$classes .= ' brix-has-sticky-templates';
				}
			}

			if ( $this->is_using_builder() ) {
				$classes .= ' brix-using-builder';
			}
		}

		return $classes;
	}

	/**
	 * Check if we're using Brix on frontend.
	 *
	 * @since 1.2.7
	 * @return boolean
	 */
	public function is_frontend_using_builder()
	{
		if ( ! is_singular() ) {
			return false;
		}

		$post_id = get_queried_object_id();

		$brix_enabled = $this->check_builder_enabled( $post_id );
		$brix_used = (int) get_post_meta( $post_id, 'brix_used', true ) || (int) get_post_meta( $post_id, '_brix_used', true );
		$using_builder = apply_filters( 'brix_is_using_builder', ( $brix_used && $brix_enabled ) );

		return $using_builder;
	}

	/**
	 * Add a set of body classes when the builder is active in the page/post frontend.
	 *
	 * @since 1.0.0
	 * @param array $classes An array of body classes.
	 * @return array
	 */
	public function body_class( $classes )
	{
		if ( $this->is_frontend_using_builder() ) {
			$classes[] = ' brix-active';

			if ( wp_is_mobile() ) {
				$classes[] = 'brix-mobile';
			}

			$classes[] = 'brix-ua-' . brix_get_browser_name();
		}

		return $classes;
	}

	/**
	 * Display a toggle button above the editor and below the title to toggle the
	 * builder appearance.
	 *
	 * @since 1.0.0
	 */
	public function interface_toggle_button()
	{
		if ( ! $this->_current_user_can_use_builder() ) {
			return;
		}

		if ( ! $this->check_builder_enabled() ) {
			return;
		}

		global $post;

		printf( '<input type="hidden" name="brix_used" id="brix-used" value="%s">', esc_attr( $this->is_using_builder() ) );

		$logo_name_svg = $this->i18n_strings( 'logo_name_svg' );
		$edit_label = '<span class="brix-logo-label-edit-with">' . __( 'Edit with', 'brix' ) . '</span>' . $logo_name_svg;

		$label = $edit_label;
		$label = apply_filters( 'brix_logo_label', $label );

		echo '<div class="brix-bar">';

			printf( '<button type="button" id="brix-use-builder" data-nonce="%s">
				<span class="brix-logo">%s</span>
				<span class="brix-logo-label">%s</span>
			</button>',
				esc_attr( wp_create_nonce( 'brix_load_blank_template' ) ),
				BrixBuilder::instance()->i18n_strings( 'logo_svg' ),
				$label
			);

			printf( '<button type="button" id="brix-back-to-editor">%s</button>', esc_html( $this->i18n_strings( 'back_to_editor' ) ) );
		echo '</div>';

		do_meta_boxes( null, 'brix_after_title_builder', $post );
	}

	/**
	 * Add the builder meta box to the enabled post types.
	 *
	 * @since 1.0.0
	 * @param string $post_type The screen post type.
	 * @param stdClass $post The post object.
	 */
	public function add_meta_box( $post_type, $post = false )
	{
		if ( ! $this->_current_user_can_use_builder() ) {
			return;
		}

		if ( ! $this->check_builder_enabled() ) {
			return;
		}

		add_meta_box(
			'brix-builder',
			__( 'Layout builder', 'brix' ),
			array( $this, 'render_metabox' ),
			$post_type,
			'brix_after_title_builder',
			'high'
		);
	}

	/**
	 * Manage the translation strings for builder elements.
	 *
	 * @since 1.0.0
	 * @param string $string An optional key to return the value of the corresponding translation.
	 * @return array|string
	 */
	public function i18n_strings( $string = false )
	{
		$strings = array(
			'enter_template_name'             => __( 'Enter a name for the template:', 'brix' ),
			'confirm_template_change'         => __( "You're about to apply the selected template. Are you sure?", 'brix' ),
			'confirm_template_delete'         => __( 'Deleted templates cannot be recovered. Are you sure?', 'brix' ),
			'confirm_reset'                   => _x( 'Are you sure?', 'builder reset confirm', 'brix' ),
			'section_width_boxed'             => __( 'As large as the page content', 'brix' ),
			'section_width_extended-boxed'    => __( 'Stretched container with centered content', 'brix' ),
			'section_width_extended-extended' => __( 'Stretched container and content', 'brix' ),
			'section_hidden'                  => __( 'Hidden Section', 'brix' ),
			'background_color'                => __( 'Background color', 'brix' ),
			'background_gradient'             => __( 'Background gradient', 'brix' ),
			'background_image'                => __( 'Background image', 'brix' ),
			'background_video'                => __( 'Background video', 'brix' ),
			'column'                          => __( 'Column', 'brix' ),
			'row'                             => __( 'Row', 'brix' ),
			'section'                         => __( 'Section', 'brix' ),
			'responsive_inherit'              => _x( 'Default', 'responsive behavior', 'brix' ),
			'responsive_hide'                 => _x( 'Hide', 'responsive behavior', 'brix' ),
			'responsive_select_breakpoint'    => __( 'Select a breakpoint&hellip;', 'brix' ),
			'plugin_name'                     => '%s',
			'edit_with_brix'                  => __( 'Edit with %s', 'brix' ),
			'logo_name_svg'                   => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="336" height="87" viewBox="0 0 336 87" version="1.1"><title>bRix</title><desc>Created with Sketch.</desc><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><g id="Artboard-2" sketch:type="MSArtboardGroup" transform="translate(-32.000000, -35.000000)" fill="#607D8B"><path d="M44.89 72.36L88.11 72.36C91.46 72.36 94.29 71.2 96.62 68.87 98.99 66.5 100.17 63.64 100.17 60.3 100.17 57 98.99 54.16 96.62 51.79 94.29 49.43 91.46 48.24 88.11 48.24L44.89 48.24 44.89 72.36ZM44.89 109.24L88.11 109.24C91.46 109.24 94.29 108.06 96.62 105.69 98.99 103.36 100.17 100.52 100.17 97.18 100.17 93.88 98.99 91.04 96.62 88.67 94.29 86.31 91.46 85.12 88.11 85.12L44.89 85.12 44.89 109.24ZM38.54 122C36.76 122 35.24 121.39 33.97 120.16 32.74 118.89 32.13 117.37 32.13 115.59L32.13 41.89C32.13 40.12 32.74 38.61 33.97 37.39 35.24 36.12 36.76 35.48 38.54 35.48L88.11 35.48C94.97 35.48 100.83 37.91 105.7 42.78 110.52 47.65 112.93 53.49 112.93 60.3 112.93 67.66 110.2 73.82 104.74 78.77 110.2 83.68 112.93 89.82 112.93 97.18 112.93 104.04 110.52 109.88 105.7 114.7 100.83 119.57 94.97 122 88.11 122L38.54 122ZM220.6 60.3C220.6 53.49 218.18 47.65 213.36 42.78 208.49 37.91 202.63 35.48 195.78 35.48L146.2 35.48C144.42 35.48 142.9 36.12 141.63 37.39 140.4 38.61 139.79 40.12 139.79 41.89 139.79 43.67 140.4 45.17 141.63 46.4 142.9 47.63 144.42 48.24 146.2 48.24L195.78 48.24C199.12 48.24 201.95 49.43 204.28 51.79 206.65 54.16 207.84 57 207.84 60.3 207.84 63.64 206.65 66.5 204.28 68.87 201.95 71.2 199.12 72.36 195.78 72.36L146.2 72.36C144.42 72.36 142.9 73 141.63 74.27 140.4 75.49 139.79 77 139.79 78.77 139.79 80.51 140.4 82.01 141.63 83.28 142.9 84.51 144.42 85.12 146.2 85.12L191.52 85.12C196.05 85.12 199.9 86.73 203.08 89.94 206.25 93.12 207.84 96.95 207.84 101.43L207.84 115.59C207.84 117.37 208.47 118.89 209.74 120.16 210.97 121.39 212.47 122 214.25 122 215.98 122 217.48 121.39 218.75 120.16 219.98 118.89 220.6 117.37 220.6 115.59L220.6 101.43C220.6 92.84 217.4 85.67 211.01 79.92 217.4 74.92 220.6 68.38 220.6 60.3L220.6 60.3ZM249.29 120.16C248.07 118.89 247.45 117.37 247.45 115.59L247.45 41.89C247.45 40.12 248.07 38.61 249.29 37.39 250.56 36.12 252.09 35.48 253.86 35.48 255.6 35.48 257.1 36.12 258.37 37.39 259.6 38.61 260.21 40.12 260.21 41.89L260.21 115.59C260.21 117.37 259.6 118.89 258.37 120.16 257.1 121.39 255.6 122 253.86 122 252.09 122 250.56 121.39 249.29 120.16L249.29 120.16ZM367.87 41.89C367.87 40.12 367.26 38.61 366.03 37.39 364.76 36.12 363.26 35.48 361.52 35.48 359.75 35.48 358.24 36.12 357.02 37.39 355.75 38.61 355.11 40.12 355.11 41.89L355.11 56.05C355.11 60.58 353.53 64.43 350.35 67.6 347.18 70.77 343.33 72.36 338.8 72.36L316.14 72.36C311.65 72.36 307.8 70.77 304.59 67.6 301.41 64.43 299.83 60.58 299.83 56.05L299.83 41.89C299.83 40.12 299.21 38.61 297.98 37.39 296.72 36.12 295.21 35.48 293.48 35.48 291.7 35.48 290.18 36.12 288.91 37.39 287.68 38.61 287.07 40.12 287.07 41.89L287.07 56.05C287.07 65.36 290.71 72.93 297.98 78.77 290.71 84.61 287.07 92.17 287.07 101.43L287.07 115.59C287.07 117.37 287.68 118.89 288.91 120.16 290.18 121.39 291.7 122 293.48 122 295.21 122 296.72 121.39 297.98 120.16 299.21 118.89 299.83 117.37 299.83 115.59L299.83 101.43C299.83 96.95 301.41 93.12 304.59 89.94 307.8 86.73 311.65 85.12 316.14 85.12L338.8 85.12C343.33 85.12 347.18 86.73 350.35 89.94 353.53 93.12 355.11 96.95 355.11 101.43L355.11 115.59C355.11 117.37 355.75 118.89 357.02 120.16 358.24 121.39 359.75 122 361.52 122 363.26 122 364.76 121.39 366.03 120.16 367.26 118.89 367.87 117.37 367.87 115.59L367.87 101.43C367.87 92.17 364.23 84.61 356.95 78.77 364.23 72.93 367.87 65.36 367.87 56.05L367.87 41.89Z" sketch:type="MSShapeGroup"/></g></g></svg>',
			'logo_svg'                        => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="348" height="390" viewBox="0 0 348 390" version="1.1"><title>Polygon 1 + Polygon 1 + Polygon 1</title><desc>Created with Sketch.</desc><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><g id="Artboard-1" sketch:type="MSArtboardGroup" transform="translate(-26.000000, -5.000000)"><g id="Polygon-1-+-Polygon-1-+-Polygon-1" sketch:type="MSLayerGroup" transform="translate(26.000000, 5.000000)"><path d="M169.17 26.65L169.17 26.65 30.61 106.65C30.48 106.72 30.1 107.04 29.6 107.57 28.88 108.34 28.15 109.33 27.51 110.43 26.88 111.53 26.39 112.65 26.08 113.66 25.87 114.37 25.79 114.86 25.79 115L25.79 275C25.79 275.15 25.87 275.63 26.08 276.34 26.39 277.35 26.88 278.47 27.51 279.57 28.15 280.67 28.88 281.66 29.6 282.43 30.1 282.96 30.49 283.28 30.61 283.35L169.17 363.35C169.3 363.42 169.76 363.6 170.48 363.77 171.51 364.01 172.73 364.14 174 364.14 175.26 364.14 176.48 364.01 177.51 363.77 178.23 363.6 178.69 363.42 178.82 363.35L317.38 283.35C317.51 283.28 317.89 282.96 318.39 282.43 319.12 281.66 319.84 280.67 320.48 279.57 321.11 278.47 321.6 277.35 321.91 276.34 322.12 275.63 322.2 275.14 322.2 275L322.2 115C322.2 114.85 322.12 114.37 321.91 113.66 321.6 112.65 321.11 111.53 320.48 110.43 319.84 109.33 319.11 108.34 318.39 107.57 317.89 107.04 317.5 106.72 317.38 106.65L178.82 26.65C178.69 26.58 178.23 26.4 177.51 26.23 176.48 25.99 175.26 25.86 173.99 25.86 172.73 25.86 171.51 25.99 170.48 26.23 169.76 26.4 169.3 26.58 169.17 26.65L169.17 26.65ZM191.32 5L329.88 85C339.45 90.52 347.2 103.95 347.2 115L347.2 275C347.2 286.05 339.45 299.47 329.88 305L191.32 385C181.75 390.52 166.24 390.53 156.67 385L18.11 305C8.54 299.48 0.79 286.05 0.79 275L0.79 115C0.79 103.95 8.54 90.53 18.11 85L156.67 5C166.24-0.52 181.75-0.53 191.32 5Z" id="Shape" fill="#607D8B" sketch:type="MSShapeGroup"/><path d="M174.02 313.53C175.55 313.52 177.08 313.18 178.26 312.5L273.51 257.5C275.91 256.12 277.85 252.76 277.85 250L277.85 140C277.85 137.24 275.91 133.88 273.51 132.5L178.26 77.5C175.86 76.12 171.98 76.12 169.59 77.5L87.29 125.02 122.02 145.07 122.02 230.9 174.02 260.93 174.02 313.53Z" id="Polygon-1" fill="#CDDC39" sketch:type="MSShapeGroup"/><path d="M277.85 140.27L277.85 140C277.85 137.24 275.91 133.88 273.51 132.5L178.26 77.5C175.86 76.12 171.98 76.12 169.59 77.5L74.33 132.5C71.94 133.88 70 137.24 70 140L70 140.27 70 140.53C70 143.3 71.94 146.66 74.33 148.04L169.59 203.03C171.99 204.42 175.86 204.41 178.26 203.03L273.51 148.04C275.91 146.65 277.85 143.29 277.85 140.53L277.85 140.27Z" id="Polygon-1" fill="#D9EB3D" sketch:type="MSShapeGroup"/></g></g></g></svg>',
			'back_to_editor'                  => __( 'Back to Editor', 'brix' ),
			'equal_height_columns'            => __( 'All columns are equally tall', 'brix' ),
			'choose_vertical_alignment'       => __( 'Choose the vertical alignment for the content inside each colum.', 'brix' )
		);

		if ( $string !== false ) {
			if ( isset( $strings[$string] ) ) {
				return $strings[$string];
			}
			else {
				return $string;
			}
		}

		return $strings;
	}

	/**
	 * Enqueue the builder data.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_data()
	{
		if ( ! $this->_current_user_can_use_builder() ) {
			return;
		}

		/* Enqueue the editor scripts (WordPress 4.8+). */
		// wp_enqueue_editor();

		/* Builder environment variables. */
		wp_localize_script( 'jquery', 'brix_env', array(
			'use_builder' => $this->is_using_builder()
		) );

		/* Builder localization strings. */
		wp_localize_script( 'jquery', 'brix_i18n_strings', $this->i18n_strings() );

		/* Builder content blocks. */
		wp_localize_script( 'jquery', 'brix_blocks', brix_get_blocks() );

		/* Builder breakpoints. */
		wp_localize_script( 'jquery', 'brix_breakpoints', brix_breakpoints() );

		/* Enqueue custom data. */
		$custom_enqueued_data = apply_filters( 'brix_custom_enqueued_data', array() );

		foreach ( $custom_enqueued_data as $key => $data ) {
			wp_localize_script( 'jquery', $key, $data );
		}
	}

	/**
	 * Retrieve the saved builder data.
	 *
	 * @since 1.0.0
	 * @param integer $page_id The page ID.
	 * @param boolean $raw Set to true to retrieve the raw serialized data.
	 * @return array
	 */
	public function get_data( $page_id = false, $raw = false )
	{
		$is_preview = ! is_admin() && isset( $_GET['preview'] ) && is_user_logged_in();

		if ( ! $page_id ) {
			global $post;

			if ( $post ) {
				$page_id = $post->ID;
			}
		}

		if ( ! $is_preview ) {
			if ( $page_id ) {
				delete_post_meta( $page_id, 'brix_preview' );
				delete_post_meta( $page_id, '_brix_preview' );
			}
		}

		$data = array();

		if ( $page_id ) {
			$brix_data = get_post_meta( $page_id, 'brix', true );

			if ( $brix_data ) {
				update_post_meta( $page_id, '_brix', $brix_data );
				delete_post_meta( $page_id, 'brix' );
			}

			$post_custom = get_post_custom( $page_id );
			$builder_key = $is_preview ? '_brix_preview' : '_brix';
			$builder_key = apply_filters( 'brix_builder_key', $builder_key );

			if ( $is_preview && ! array_key_exists( $builder_key, $post_custom ) ) {
				$builder_key = '_brix';
			}

			if ( isset( $post_custom[$builder_key][0] ) ) {
				$data = $post_custom[$builder_key][0];

				if ( ! $raw ) {
					$data = brix_unserialize_template( $data );
				}
			}
		}

		return $data;
	}

	/**
	 * Process styles and data for a specific set of builder data.
	 *
	 * @since 1.0.0
	 * @param stdClass $data The builder data.
	 * @param string $selector_prefix The string that will prefix generated selectors.
	 */
	public function process_data( $data, $selector_prefix )
	{
		$current_id = get_the_ID();
		$this->processed_data = array();

		foreach ( $data as $section_index => &$section ) {
			$section = apply_filters( 'brix_process_frontend_section', $section, $section_index, $selector_prefix );

			foreach ( $section->data->layout as &$subsection ) {
				foreach ( $subsection->rows as &$row ) {
					foreach ( $row->columns as &$column ) {
						$column = apply_filters( 'brix_process_frontend_column', $column, $this->_columns, $selector_prefix );

						foreach ( $column->blocks as &$block ) {
							$block = apply_filters( 'brix_process_frontend_block', $block, $this->_blocks, $selector_prefix );

							$this->_blocks++;
						}

						$this->_columns++;
					}
				}
			}
		}

		$this->processed_data = $data;
	}

	/**
	 * Process the builder data for frontend display.
	 *
	 * @since 1.0.0
	 * @param integer $page_id The page ID. If left empty, is the currently queried object ID.
	 */
	public function process_frontend( $page_id = false )
	{
		$is_main_query = false;
		$this->_page_id = get_queried_object_id();

		if ( ! $page_id ) {
			$is_main_query = true;
			$page_id = $this->_page_id;
		}
		else {
			if ( $page_id == $this->_page_id ) {
				return;
			}
		}

		/* Check that the entry belongs to a post type for which the builder is actually enabled. */
		if ( ! in_array( get_post_type( $page_id ), $this->_get_screens() ) ) {
			return;
		}

		if ( ! $this->is_using_builder( $page_id ) ) {
			return;
		}

		$data = (array) $this->get_data( $page_id );
		$selector_prefix = $is_main_query ? false : '.post-' . $page_id;

		$this->process_data( $data, $selector_prefix );
	}

	/**
	 * AJAX load a builder template.
	 *
	 * @since 1.0.0
	 */
	public function load_template()
	{
		/* Verify the validity of the supplied nonce. */
		$is_valid_nonce = brix_is_post_nonce_valid( 'brix_nonce' );

		if ( ! $is_valid_nonce ) {
			die();
		}

		if ( ! isset( $_POST['id'] ) ) {
			die();
		}

		$id = sanitize_text_field( $_POST['id'] );
		$template = brix_get_template( $id );

		if ( ! $template ) {
			die();
		}

		$data = $template['data'];

		if ( ! empty( $data ) ) {
			foreach ( $data as $section ) {
				brix_template( BRIX_TEMPLATES_FOLDER . 'admin/section', array(
					'data'  => $section,
				) );
			}
		}

		die();
	}

	/**
	 * Render the builder meta box on admin.
	 *
	 * @since 1.0.0
	 */
	public function render_metabox()
	{
		$this->render_admin();
	}

	/**
	 * Render the builder structure on admin.
	 *
	 * @since 1.0.0
	 * @param stdClass $builder_data The data supplied to the builder interface.
	 * @param string $builder_handle The builder input handle.
	 */
	public function render_admin( $builder_data = false, $builder_handle = 'brix' )
	{
		if ( $builder_data === false ) {
			$builder_data = $this->get_data();
		}

		$metabox_classes = array(
			'brix',
			'brix-metabox',
			'brix-box'
		);

		if ( empty( $builder_data ) ) {
			$metabox_classes[] = 'brix-empty';
		}

		printf( '<div class="%s">', esc_attr( implode( ' ', $metabox_classes ) ) );

			/**
			 * Builder UI.
			 */
			$this->render_admin_ui( $builder_data, $builder_handle );

		echo '</div>';
	}

	/**
	 * Render the builder UI in admin.
	 *
	 * @since 1.0.0
	 * @param stdClass $data The builder data.
	 * @param string $builder_handle The builder input handle.
	 */
	private function render_admin_ui( $data, $builder_handle )
	{
		global $post;

		/**
		 * Layout templates.
		 */
		echo '<div class="brix-templates">';
			$nonce = wp_create_nonce( 'brix_nonce' );

			echo '<div class="brix-template-actions">';
				$logo = BrixBuilder::instance()->i18n_strings( 'logo_svg' );

				printf( '<span class="brix-logo">%s</span>', $logo );

				printf( '<button type="button" href="#" class="brix-load-builder-template brix-tooltip" id="brix-templates-manager" data-title="%s" data-nonce="%s" %s><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Templates manager', 'brix' ) ),
					esc_attr( $nonce ),
					'',
					esc_html( __( 'Templates manager', 'brix' ) )
				);

				printf( '<button type="button" href="#" class="brix-save-builder-template brix-tooltip" data-title="%s" data-nonce="%s" %s><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Save template', 'brix' ) ),
					esc_attr( $nonce ),
					empty( $data ) ? esc_attr( 'disabled' ) : '',
					esc_html( __( 'Save template', 'brix' ) )
				);

				printf( '<button type="button" href="#" class="brix-reset-builder brix-tooltip" data-title="%s" data-nonce="%s" %s><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Empty page contents', 'brix' ) ),
					esc_attr( $nonce ),
					empty( $data ) ? esc_attr( 'disabled' ) : '',
					esc_html( __( 'Empty page contents', 'brix' ) )
				);

				$frontend_editing_status = 'disabled';

				if ( $post->post_status !== 'auto-draft' ) {
					$frontend_editing_status = '';
				}

				printf( '<button type="button" href="#" class="brix-frontend-editing" data-nonce="%s" %s>%s</button>',
					esc_attr( $nonce ),
					esc_attr( $frontend_editing_status ),
					esc_html( __( 'Frontend editing', 'brix' ) )
				);

				do_action( 'brix_template_actions', $nonce );

				printf( '<button type="button" href="#" class="brix-full-screen-builder brix-tooltip" data-title="%s"><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Toggle full screen', 'brix' ) ),
					esc_html( __( 'Toggle full screen', 'brix' ) )
				);

				printf( '<button type="button" href="#" id="brix-redo" class="brix-tooltip brix-redo-btn" data-title="%s" disabled><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Redo', 'brix' ) ),
					esc_html( __( 'Redo', 'brix' ) )
				);

				printf( '<button type="button" href="#" id="brix-undo" class="brix-tooltip brix-undo-btn" data-title="%s" disabled><span class="screen-reader-text">%s</span></button>',
					esc_attr( __( 'Undo', 'brix' ) ),
					esc_html( __( 'Undo', 'brix' ) )
				);

			echo '</div>';
		echo '</div>';

		echo '<div class="brix-builder">';
			wp_nonce_field( 'brix', 'brix_nonce' );

			echo '<div class="brix-start">';
				echo '<div class="brix-start-inner-wrapper">';

					echo '<div class="brix-empty-message">';
						$brix_name = BrixBuilder::instance()->i18n_strings( 'logo_name_svg' );
						printf( '<p>%s %s</p>', esc_html( __( 'Welcome to', 'brix' ) ), $brix_name );
						echo '<p>' . esc_html( __( 'your page actually doesn’t have content yet.', 'brix' ) ) . '</p>';
					echo '</div>';


					echo '<div class="brix-section-add">';
						echo '<p>';
							echo esc_html( __( 'Start by adding a', 'brix' ) );
							echo '<a href="#" class="brix-add-new-section"><span>' . esc_html( __( 'New Section', 'brix' ) ) . '</span></a>';

							if ( !brix_has_sticky_templates() ) {
								echo '<span class="brix-template-load">';
									echo '<span>' . esc_html( __( 'or', 'brix' ) ) . '</span>';
									printf( '<a href="#" class="brix-load-builder-template" data-nonce="%s"><span>%s</span></a>',
										esc_attr( $nonce ),
										esc_html( __( 'Use a template', 'brix' ) )
									);
								echo '</span>';
							}
						echo '</p>';
					echo '</div>';

					$nonce = wp_create_nonce( 'brix_nonce' );

					if ( brix_has_sticky_templates( true ) ) {
						echo '<div class="brix-sticky-template-load">';
							echo '<p>' . esc_html( __( 'or', 'brix' ) ) . '</p>';
							echo '<p class="brix-template-load-heading">' . esc_html( __( 'Use a template', 'brix' ) ) . '</p>';
							echo '<p>' . esc_html( __( 'Some of the available page layouts', 'brix' ) ) . '</p>';

							echo '<div class="brix-sticky-templates">';
								foreach ( brix_get_sticky_templates( true ) as $template ) {
									printf( '<button type="button" data-id="%s" data-nonce="%s"><img src="%s"></button>',
										esc_attr( $template['id'] ),
										esc_attr( $nonce ),
										esc_attr( $template['thumb'] )
									);
								}
							echo '</div>';

							printf( '<a href="#" class="brix-load-builder-template" data-nonce="%s"><span>%s</span></a>',
								esc_attr( $nonce ),
								esc_html( __( 'View all templates', 'brix' ) )
							);
						echo '</div>';
					}

				echo '</div>';
			echo '</div>';

			printf( '<a href="#" data-title="%s" class="brix-add-new-section brix-add-new-section-inside" data-before><span class="brix-add-new-section-detail"></span><span class="brix-add-new-section-label">%s</span></a>',
				esc_attr( __( 'Add section here', 'brix' ) ),
				esc_html( __( 'Add section here', 'brix' ) )
			);

			if ( ! empty( $data ) ) {
				foreach ( $data as $section ) {
					brix_template( BRIX_TEMPLATES_FOLDER . 'admin/section', array(
						'data'  => $section,
					) );
				}
			}

			$value = htmlspecialchars( json_encode( $data ) );

			$builder_field_type = apply_filters( 'brix_field_type', 'hidden' );
			printf( '<input type="%s" name="%s" data-brix-value data-value value="%s">',
				esc_attr( $builder_field_type ),
				esc_attr( $builder_handle ),
				$value
			);
		echo '</div>';
	}

	/**
	 * Select where to attach the builder.
	 *
	 * @since 1.0.0
	 */
	public function pre_render_frontend()
	{
		if ( $this->is_using_builder( get_the_ID() ) ) {
			/* Attach the builder to the main content. */
			add_filter( 'the_content', array( $this, 'render_frontend' ), 10 );
		}
	}

	/**
	 * Render the builder structure on frontend.
	 *
	 * @since 1.0.0
	 * @param string $text The optional entry content.
	 * @return string
	 */
	public function render_frontend( $text = '', $page_id = false )
	{
		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $text;
		}

		$post = get_post();

		if ( post_password_required( $post ) ) {
			return $text;
		}

		if ( $page_id === false ) {
			$current_id = get_the_ID();

			if ( ! is_singular() || $this->_page_id != $current_id || $this->_depth > 0 || ! $this->check_builder_enabled() ) {
				return $text;
			}
		}
		else {
			if ( $this->_page_id == $page_id ) {
				return '';
			}

			$current_id = $page_id;
		}

		$this->_depth++;
		$data = $this->processed_data;

		if ( empty( $data ) ) {
			return apply_filters( 'brix_return_no_data', $text );
		}

		/* Prevent the original post content from being rendered, while preserving hooked custom content. */
		// $pattern = '/<!--brix-->(.|\s)*?<!--\/brix-->/';
		// $text = preg_replace( $pattern, '', $text );

		$brix_start = strpos( $text, '<!--brix-->' );
		$brix_end = strpos( $text, '<!--/brix-->' );

		$text_before = substr( $text, 0, $brix_start );
		$text_after = substr( $text, $brix_end, strlen( $text ) );

		$text = $text_before . $text_after;

		$text = str_replace( '<!--brix-->', '', $text );
		$text = str_replace( '<!--/brix-->', '', $text );
		$text = str_replace( '<p></p>', '', $text );

		/* Render Brix contents. */
		$text .= $this->render_builder( $data );

		return $text;
	}

	/**
	 * Render the builder structure in admin.
	 *
	 * @since 1.0.0
	 * @param stdClass $data The builder data.
	 * @return string
	 */
	public function render_builder( $data )
	{
		$classes = array(
			'brix-builder',
		);

		$builder = sprintf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) );

			$builder = apply_filters( 'brix_before_render', $builder, $data );

			foreach ( $data as $index => $section ) {
				$builder .= brix_template( BRIX_TEMPLATES_FOLDER . 'frontend/section', array(
					'data' => $section,
					'index' => $index
				), false );
			}

			$builder = apply_filters( 'brix_after_render', $builder, $data );

		$builder .= '</div>';

		/* Parse Brix contents for shortcodes. */
		$builder = shortcode_unautop( $builder );
		$builder = do_shortcode( $builder );

		return $builder;
	}

	/**
	 * Prepare the post content to be rendered. Wraps the original post content in
	 * comments so that it can be excluded from rendering when Brix is being used.
	 *
	 * @since 1.0.0
	 * @param array $pages The pages array.
	 * @param WP_Post $post The current post object.
	 * @return array
	 */
	public function prepare_post_content( $pages, $post ) {
		if ( is_admin() ) {
			return $pages;
		}

		$post_id = $post->ID;

		if ( ! $this->is_using_builder( $post_id ) ) {
			return $pages;
		}

		$comment_start = '<!--brix-->';
		$comment_end = '<!--/brix-->';

		$first_page = $pages[0];
		$last_page = $pages[count( $pages ) - 1];

		if ( brix_string_starts_with( $first_page, $comment_start ) && brix_string_ends_with( $last_page, $comment_end ) ) {
			return $pages;
		}

		$first_page = str_replace( $comment_start, '', $first_page );
		$last_page = str_replace( $comment_end, '', $last_page );

		$pages[0] = $comment_start . $pages[0];
		$pages[count( $pages ) - 1] = $pages[count( $pages ) - 1] . $comment_end;

		return $pages;
	}

	/**
	 * Print builder templates.
	 *
	 * @since 1.0.0
	 */
	public function templates()
	{
		if ( ! $this->_current_user_can_use_builder() ) {
			return;
		}

		$empty_row_obj = array(
			'data' => new stdClass(),
			'columns' => array()
		);

		$empty_section_obj = array(
			'data' => array(
				'layout' => array(
					array(
						'size' => '1/1',
						'type' => 'standard',
						'rows' => array(
							$empty_row_obj
						)
					)
				)
			),
		);

		echo '<script type="text/template" data-template="brix-section">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/section', array(
				'data' => json_decode( json_encode( $empty_section_obj ) )
			) );
		echo '</script>';

		echo '<script type="text/template" data-template="brix-row">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/row', array(
				'data' => json_decode( json_encode( $empty_row_obj ) )
			) );
		echo '</script>';

		echo '<script type="text/template" data-template="brix-js-block">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/js/block', array(
				'data' => array()
			) );
		echo '</script>';

		echo '<script type="text/template" data-template="brix-js-column">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/js/column', array(
				'data' => array()
			) );
		echo '</script>';

		echo '<script type="text/template" data-template="brix-js-row">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/row', array(
				'data' => array()
			) );
		echo '</script>';

		echo '<script type="text/template" data-template="brix-js-section">';
			brix_template( BRIX_TEMPLATES_FOLDER . 'admin/js/section', array(
				'data' => array()
			) );
		echo '</script>';
	}

	/**
	 * When the post is saved, save the custom data contained in the builder meta box.
	 *
	 * @since 1.0.0
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id )
	{
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! $this->_current_user_can_use_builder() ) {
			return;
		}

		if ( ! brix_user_can_save( $post_id, 'brix', 'brix_nonce' ) ) {
			return;
		}

		$is_preview = isset( $_POST['wp-preview'] ) && ! empty( $_POST['wp-preview'] );

		delete_post_meta( $post_id, 'brix_used' );
		delete_post_meta( $post_id, '_brix_frontend_editing' );

		if ( ! isset( $_POST['brix_used'] ) ) {
			delete_post_meta( $post_id, '_brix_used' );
		}
		else {
			update_post_meta( $post_id, '_brix_used', absint( $_POST['brix_used'] ) );
		}

		if ( isset( $_POST['brix'] ) ) {
			$data = $_POST['brix'];
			$builder_key = $is_preview ? '_brix_preview' : '_brix';

			$data = brix_serialize_template( $data, true );

			update_post_meta( $post_id, $builder_key, $data );

			if ( ! $is_preview ) {
				delete_post_meta( $post_id, '_brix_preview' );
			}
		}
		else {
			delete_post_meta( $post_id, '_brix' );
			delete_post_meta( $post_id, '_brix_preview' );
		}
	}

	/**
	 * Kind of hackish way of storing a temporary version of the builder
	 * data, when opening a post for a preview.
	 *
	 * @since 1.0.0
	 * @param string $url The preview URL.
	 * @param WP_Post $post The post object.
	 * @return string
	 */
	public function save_preview( $url, $post )
	{
		if ( $post && ! empty( $_POST ) ) {
			$this->save( $post->ID );
		}

		return $url;
	}

	/**
	 * Declare a set of global options for the builder.
	 *
	 * @since 1.0.0
	 */
	public function options()
	{
		if ( ! is_admin() ) {
			return;
		}

		if ( ! class_exists( 'Brix_Framework' ) ) {
			return;
		}

		if ( ! brix_install_check() ) {
			return;
		}

		$menu_page = new Brix_BrixMenuPage( 'brix', 'Brix', brix_global_options(), array(
			'group' => 'brix'
		) );

		$submenu_page = new Brix_BrixSubmenuPage( 'brix', 'brix', __( 'Configuration', 'brix' ), array(), array(
			'group' => 'brix'
		) );

		if ( brix_is_documented() ) {
			$docs_submenu_page = new Brix_BrixSubmenuPage( 'brix', 'brix_docs', __( 'Documentation', 'brix' ), array(), array(
				'group' => 'brix'
			) );
		}
	}

	/**
	 * Display a subheading in option pages.
	 *
	 * @since 1.0.0
	 */
	public function page_subheading()
	{
		echo '<span class="brix-version">' . esc_html( sprintf( __( 'v. %s', 'brix' ), BRIX_VERSION ) ) . '</span>';
	}

	/**
	 * Define a custom menu icon for the option page.
	 *
	 * @since 1.0.0
	 * @param string $icon The icon URL.
	 * @return string
	 */
	public function menu_icon( $icon = null )
	{
		$icon = BRIX_ADMIN_ASSETS_URI . 'css/i/brix_menu_icon.svg';

		return $icon;
	}

	/**
	 * Stringify the contents of a builder instance.
	 *
	 * @since 1.0.0
	 * @param integer $post_id The post ID.
	 * @return string
	 */
	public static function stringify( $post_id )
	{
		$data = self::instance()->get_data( $post_id );

		if ( empty( $data ) ) {
			return false;
		}

		$stringified = '';

		foreach ( $data as $index => $section ) {
			if ( isset( $section->data->layout ) ) {
				foreach ( $section->data->layout as $subsection ) {
					if ( isset( $subsection->rows ) ) {
						foreach ( $subsection->rows as $row ) {
							if ( isset( $row->columns ) ) {
								foreach ( $row->columns as $column ) {
									if ( isset( $column->blocks ) ) {
										foreach ( $column->blocks as $block ) {
											$stringified .= brix_get_block_stringified( $block->data->_type, $block->data );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $stringified;
	}

	/**
	 * Mark the current theme to support a particular Brix feature.
	 *
	 * @since 1.2.15
	 * @param string $feature The feature string.
	 */
	public function add_support( $feature ) {
		if ( ! $this->has_support( $feature ) ) {
			$this->supports[] = $feature;
		}
	}

	/**
	 * Check if the current theme supports a particular Brix feature.
	 *
	 * @since 1.2.15
	 * @param string $feature The feature string.
	 * @return boolean
	 */
	public function has_support( $feature ) {
		return in_array( $feature, $this->supports );
	}

	/**
	 * Return the instance of the builder class.
	 *
	 * @static
	 * @since 1.0.0
	 * @return BrixBuilder
	 */
	public static function instance()
	{
		if ( self::$_instance === null ) {
			self::$_instance = new BrixBuilder();
		}

		return self::$_instance;
	}

	/**
	 * Check if the instance of the builder class is not null.
	 *
	 * @static
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function has_instance()
	{
		return self::$_instance !== null;
	}

}

if ( ! function_exists( 'brix_add_admin_assets' ) ) {
	/**
	 * Add scripts and styles on admin.
	 *
	 * @since 1.0.0
	 */
	function brix_add_admin_assets() {
		$suffix = brix_get_scripts_suffix();

		/* Main builder stylesheet. */
		brix_fw()->admin()->add_style( 'brix-admin-style', BRIX_URI . 'assets/admin/css/style.css', array( 'brix-admin' ) );

		/* Main builder JavaScript controller. */
		brix_fw()->admin()->add_script( 'brix-admin-script', BRIX_URI . 'assets/admin/js/min/builder.' . $suffix . '.js', array( 'underscore', 'jquery-ui-sortable', 'jquery-ui-datepicker', 'media-upload', 'jquery-masonry', 'jquery-ui-resizable' ) );
	}

	/* Add scripts and styles on admin. */
	add_action( 'admin_init', 'brix_add_admin_assets' );
}

if ( ! function_exists( 'brix_load' ) ) {
	/**
	 * Load the builder instance.
	 *
	 * @since 1.0.0
	 */
	function brix_load() {
		/* Common admin utilities. */
		require_once( dirname( __FILE__ ) . '/fw/fw.php' );

		/* Builder. */
		define( 'BRIX', true );

		/* Builder main plugin file. */
		define( 'BRIX_PLUGIN_FILE', __FILE__ );

		/* Builder folder. */
		define( 'BRIX_INSTALL_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

		/* Builder URI. */
		define( 'BRIX_INSTALL_URI', plugin_dir_url( __FILE__ ) );

		/* Builder folder. */
		define( 'BRIX_FOLDER', trailingslashit( dirname( __FILE__ ) ) );

		/* Builder includes folder. */
		define( 'BRIX_INCLUDES_FOLDER', trailingslashit( BRIX_FOLDER . 'includes' ) );

		/* Common admin utilities. */
		require_once( dirname( __FILE__ ) . '/includes/admin.php' );

		/* Custom admin pages. */
		// require_once( BRIX_INCLUDES_FOLDER . 'pages/welcome.php' );
		require_once( BRIX_INCLUDES_FOLDER . 'pages/docs.php' );

		/* Load the Brix instance. */
		BrixBuilder::instance();
	}

	add_action( 'plugins_loaded', 'brix_load' );
}

endif;

if ( ! function_exists( 'brix_activate' ) ) {
	/**
	 * Activate the plugin.
	 *
	 * @since 1.0.0
	 */
	function brix_activate( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			update_option( 'brix_installed', '1' );
		}
	}

	add_action( 'activated_plugin', 'brix_activate' );
}

if ( ! function_exists( 'brix_uninstall' ) ) {
	/**
	 * Uninstall the plugin.
	 *
	 * @since 1.0.0
	 */
	function brix_uninstall() {
		delete_option( 'brix_installed' );
	}

	register_uninstall_hook( __FILE__, 'brix_uninstall' );
}