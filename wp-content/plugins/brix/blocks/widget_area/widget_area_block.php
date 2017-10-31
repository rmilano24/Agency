<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder widget area content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderWidgetAreaBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the widget area content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'widget_area';
		$this->_title = __( 'Widget area', 'brix' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
	}

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.0.0
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	public function fields( $fields )
	{
		$fields[] = array(
			'handle' => 'widget_area',
			'type' => 'select',
			'label' => __( 'Widget area', 'brix' ),
			'config' => array(
				'data' => $this->get_widget_areas()
			)
		);

		return $fields;
	}

	/**
	 * Get the currently registered widget areas.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function get_widget_areas()
	{
		global $wp_registered_sidebars;

		$sidebars = array();

		foreach( $wp_registered_sidebars as $sidebar ) {
			$sidebars[$sidebar['id']] = $sidebar['name'];
		}

		return $sidebars;
	}

	/**
	 * Check if the block is "empty".
	 *
	 * @since 1.2
	 * @param array $data The content block data.
	 * @return boolean
	 */
	protected function is_empty( $data )
	{
		$widget_area = isset( $data->data->widget_area ) ? $data->data->widget_area : false;

		return ! is_active_sidebar( $widget_area );
	}

}

/**
 * Add the widget area content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_widget_area_content_block( $blocks ) {
	$blocks['widget_area'] = array(
		'class'       => 'BrixBuilderWidgetAreaBlock',
		'label'       => __( 'Widget area', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/widget_area/i/widgets_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/widget_area/i/widgets_icon.svg',
		'description' => __( 'Display contents from a registered widget area.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_widget_area_content_block' );

/**
 * Define the appearance of the widget area content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_widget_area_content_block_admin_template( $html, $data ) {
	if ( isset( $data->widget_area ) && ! empty( $data->widget_area ) ) {
		$total_widgets = wp_get_sidebars_widgets();

		if ( empty( $total_widgets[$data->widget_area] ) ) {
			$html = esc_html( __( 'Empty widget area selected.', 'brix' ) );
		}
		else {
			global $wp_registered_sidebars;

        	$sidebar_widgets = count( $total_widgets[$data->widget_area] );

        	if ( isset( $wp_registered_sidebars[$data->widget_area]['name'] ) ) {
        		$html .= '&ldquo;' . $wp_registered_sidebars[$data->widget_area]['name'] . '&rdquo;, ';
        	}

        	$html .= sprintf( _n( 'containing %s widget', 'containing %s widgets', $sidebar_widgets, 'brix' ), $sidebar_widgets );
		}

		$html = esc_html( $html );
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:widget_area]', 'brix_widget_area_content_block_admin_template', 10, 2 );
