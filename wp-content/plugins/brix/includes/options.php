<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Return a list of options to be displayed in the Post Type options screen.
 *
 * @since 1.0.0
 * @return array
 */
function brix_post_type_options( $options ) {
	if ( ! class_exists( 'Brix_Framework' ) ) {
		return array();
	}

	$post_types = get_post_types( array(
		'public' => true
	) );

	if ( isset( $post_types['attachment'] ) ) {
		unset( $post_types['attachment'] );
	}

	$select_post_types = array();

	foreach ( $post_types as $post_type ) {
		$obj = get_post_type_object( $post_type );

		$select_post_types[$post_type] = $obj->labels->name;
	}

	$options[] = array(
		'type' => 'group',
		'handle' => 'brix-post-type-options',
		'label' => _x( 'Post types', 'post type options', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'global_post_types',
				'text'  => __( 'Post types', 'brix' ),
				'type'   => 'divider',
				'config' => array(
					'style' => 'in_page'
				)
			),
			array(
				'handle' => 'global_post_types_desc',
				'text'  => __( 'Brix can be activated on any Post Type declared by any plugin you might have installed, but keep in mind that Brix will only be displayed in Post Types that use the <code>the_content</code> WordPress function on frontend.', 'brix' ),
				'type'   => 'description',
				'config' => array(
					'style' => 'important'
				)
			),
			array(
				'handle' => 'post_types',
				'label'  => __( 'Post types', 'brix' ),
				'type'   => 'multiple_select',
				'help' => __( 'Select the content types for which Brix should be enabled.', 'brix' ),
				'config' => array(
					'vertical' => true,
					'data' => $select_post_types
				),
				'default' => 'page'
			),
		),
	);

	return $options;
}

add_filter( 'brix_global_options', 'brix_post_type_options' );

/**
 * Return a list of global options for the Builder.
 *
 * @since 1.0.0
 * @return array
 */
function brix_global_options() {
	if ( ! class_exists( 'Brix_Framework' ) ) {
		return array();
	}

	$options = array();

	$options[] = array(
		'type' => 'group',
		'handle' => 'brix-global-options',
		'label' => __( 'Global options', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'global_spacing',
				'text'  => __( 'Spacing & dimensions', 'brix' ),
				'type'   => 'divider',
				'config' => array(
					'style' => 'in_page'
				)
			),
			array(
				'handle' => 'container',
				'label'  => __( 'Container width', 'brix' ),
				'help' => __( "This is the width that's going to be used by the content generated with Brix.", 'brix' ),
				'type'   => 'text',
				'config' => array()
			),
			array(
				'handle' => 'gutter',
				'label'  => __( 'Gutter', 'brix' ),
				'help'	 => __( 'The gutter is the <strong>horizontal</strong> space between columns. Please note that it is not recommended to express the gutter value in percentage.', 'brix' ),
				'type'   => 'text',
				'config' => array()
			),
			array(
				'handle' => 'baseline',
				'label'  => __( 'Baseline', 'brix' ),
				'help'	 => __( 'The baseline is the <strong>vertical</strong> space between rows.', 'brix' ),
				'type'   => 'text',
				'config' => array()
			),
			array(
				'handle' => 'responsive_breakpoints',
				'label'  => __( 'Breakpoints', 'brix' ),
				'help'	 => __( 'Use these options to define additional responsive breakpoints and quickly select under which one the layout becomes fluid.', 'brix' ),
				'type'   => 'builder_breakpoints'
			),
		)
	);

	$options = apply_filters( 'brix_global_options', $options );

	return $options;
}

/**
 * Get the maximum width of the builder container.
 *
 * @since 1.0.0
 * @return string
 */
function brix_get_container() {
	$container = brix_get_option( 'container' );
	$container_value = '';

	if ( $container !== false ) {
		$container_value = $container;
	}

	return $container_value;
}

/**
 * Get the base gutter value of the builder container.
 *
 * @since 1.0.0
 * @return string
 */
function brix_get_gutter() {
	$gutter = brix_get_option( 'gutter' );
	$gutter_value = '';

	if ( $gutter !== false ) {
		$gutter_value = $gutter;
	}

	return $gutter_value;
}

/**
 * Get the base baseline value of the builder container.
 *
 * @since 1.0.0
 * @return string
 */
function brix_get_baseline() {
	$baseline = brix_get_option( 'baseline' );
	$baseline_value = '';

	if ( $baseline !== false ) {
		$baseline_value = $baseline;
	}

	return $baseline_value;
}

/**
 * Get the key of the breakpoint under which elements go full width.
 *
 * @since 1.0.0
 * @return string
 */
function brix_get_media_mobile() {
	$custom_breakpoints = brix_get_option( 'responsive_breakpoints' );
	$media_value = '';

	if ( isset( $custom_breakpoints['full_width_media_query'] ) && ! empty( $custom_breakpoints['full_width_media_query'] ) ) {
		$media_value = $custom_breakpoints['full_width_media_query'];
	}

	return $media_value;
}

/**
 * Update the Brix options key.
 *
 * @since 1.1.2
 */
function brix_update_option_key() {
	$key = 'ev_brix_';

	if ( is_child_theme() ) {
		$theme = wp_get_theme();
		$key .= $theme->Template;
	}
	else {
		$key .= get_option( 'stylesheet' );
	}

	$old_options = get_option( $key );

	if ( $old_options ) {
		update_option( brix_get_options_key(), get_option( $key ) );
		delete_option( $key );
	}
}

add_action( 'init', 'brix_update_option_key' );

/**
 * Menu page fields container class.
 *
 * A menu page is a field container that is displayed in a page in the WordPress
 * administration.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Brix_BrixMenuPage extends Brix_MenuPage {
    /**
	 * Set the value for a specific field inside the container.
	 *
	 * @since 1.0.0
	 * @param string $key The field key.
	 * @return mixed The value of the field. Returns boolean false if the field has no value.
	 */
	public function set_field_value( $key = '' )
	{
		return brix_get_option( $key );
	}

	/**
	 * When the page or tab is saved, save a single custom option contained in the page or tab.
	 *
	 * @since 1.0.0
	 * @param array $element The element structure.
	 * @param string|array $element_value The element value.
	 */
	protected function _save_single_field( $element, $value )
	{
		$value = Brix_Field::sanitize( $element, $value );

		brix_update_option( $element['handle'], $value );
	}

	/**
	 * Delete a single custom option contained in the page or tab.
	 *
	 * @since 1.0.0
	 * @param string $handle The element handle.
	 */
	protected function _delete_single_field( $handle )
	{
		brix_delete_option( $handle );
	}
}

/**
 * Submenu page fields container class.
 *
 * A submenu page is a field container that is displayed in a page in the WordPress
 * administration as a submenu belonging to a parent page.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Brix_BrixSubmenuPage extends Brix_SubmenuPage {
    /**
	 * Set the value for a specific field inside the container.
	 *
	 * @since 1.0.0
	 * @param string $key The field key.
	 * @return mixed The value of the field. Returns boolean false if the field has no value.
	 */
	public function set_field_value( $key = '' )
	{
		return brix_get_option( $key );
	}

	/**
	 * When the page or tab is saved, save a single custom option contained in the page or tab.
	 *
	 * @since 1.0.0
	 * @param array $element The element structure.
	 * @param string|array $element_value The element value.
	 */
	protected function _save_single_field( $element, $value )
	{
		$value = Brix_Field::sanitize( $element, $value );

		brix_update_option( $element['handle'], $value );
	}

	/**
	 * Delete a single custom option contained in the page or tab.
	 *
	 * @since 1.0.0
	 * @param string $handle The element handle.
	 */
	protected function _delete_single_field( $handle )
	{
		brix_delete_option( $handle );
	}
}

/**
 * Display the plugin utility links in option pages.
 *
 * @since 1.0.0
 */
function brix_utility_links() {
	$documentation_url = BRIX_DOCS_URI;
	$support_url = BRIX_SUPPORT_URI;

	echo '<div class="brix-utility-links">';
		printf( '<a id="brix-utility-documentation" href="%s" target="_blank" rel="noopener noreferrer"><span>%s</span></a>',
			esc_attr( $documentation_url ),
			esc_html( __( 'Docs', 'brix' ) )
		);

		printf( '<a id="brix-utility-support" href="%s" target="_blank" rel="noopener noreferrer"><span>%s</span></a>',
			esc_attr( $support_url ),
			esc_html( __( 'Support', 'brix' ) )
		);
	echo '</div>';
}

// add_action( 'brix_admin_page_subheading[group:brix]', 'brix_utility_links' );