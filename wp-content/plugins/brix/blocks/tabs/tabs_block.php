<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder tabs content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderTabsBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the tabs content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'tabs';
		$this->_title = __( 'Tabs', 'brix' );

		add_filter( "brix_block_fields[type:{$this->_type}]", array( $this, 'fields' ) );
		add_filter( "brix_block_style_fields[type:{$this->_type}]", array( $this, 'style_fields' ) );
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
		$fields = brix_tabs_content_block_fields();

		return $fields;
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
		$tabs_empty = false;
		$tab_data = isset( $data->data->tabs->tabs ) && ! empty( $data->data->tabs->tabs ) ? json_decode( $data->data->tabs->tabs ) : '';

		if ( empty( $tab_data ) ) {
			$tabs_empty = true;
		} else {
			$tabs_empty = false;
		}

		return $tabs_empty;
	}

}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_tabs_content_block( $blocks ) {
	$blocks['tabs'] = array(
		'class'       => 'BrixBuilderTabsBlock',
		'label'       => __( 'Tabs', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/tabs/i/tabs_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/tabs/i/tabs_icon.svg',
		'description' => __( 'Display tabbed content.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_tabs_content_block' );

/**
 * Tabs block fields.
 *
 * @since 1.0.0
 * @return array
 */
function brix_tabs_content_block_fields() {
	return array(
		array(
			'type' => 'bundle',
			'label' => array(
				'type' => 'hidden',
				'text' => __( 'Tabs', 'brix' )
			),
			'handle' => 'tabs',
			'fields' => array(
				array(
					'handle' => 'tabs',
					'type' => 'tabs',
					'label' => array(
						'text' => __( 'Tabs', 'brix' ),
						'type' => 'hidden'
					),
				),
				array(
					'handle' => 'orientation',
					'type' => 'select',
					'label' => __( 'Orientation', 'brix' ),
					'config' => array(
						'data' => array(
							'horizontal' => __( 'Horizontal', 'brix' ),
							'vertical' => __( 'Vertical', 'brix' ),
						)
					)
				),
				array(
					'handle' => 'tabs_nav_alignment',
					'type' => 'select',
					'label' => __( 'Navigation alignment', 'brix' ),
					'config' => array(
						'data' => array(
							'left'  => __( 'Left', 'brix' ),
							'center'  => __( 'Center', 'brix' ),
							'right' => __( 'Right', 'brix' )
						)
					)
				)
			),
			'config' => array(
				'class' => 'brix-block-tabs'
			)
		)
	);
}

/**
 * Print localization strings.
 *
 * @since 1.0.0
 */
function brix_tabs_i18n_strings() {
	wp_localize_script( 'jquery', 'brix_tabs_i18n_strings', array(
		'title' => __( 'Untitled tab', 'brix' )
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_tabs_i18n_strings' );

/**
 * Define the appearance of the tabs content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_tabs_content_block_admin_template( $html, $data ) {
	if ( isset( $data->tabs ) && isset( $data->tabs->tabs ) && ! empty( $data->tabs->tabs ) ) {
		$data->tabs->tabs = json_decode( $data->tabs->tabs );

		if ( isset( $data->_style ) ) {
			$html .= '<span class="brix-block-style-label">' . __( 'Style', 'brix' ) . ': ';
				$html .= '<strong>' . $data->_style . '</strong>';

				if ( isset( $data->tabs_nav_alignment ) ) {
					$html .= ', <strong>' . $data->tabs_nav_alignment . '</strong>';
				}

			$html .= '</span>';
		}

		$html .= '<ul>';

		foreach ( $data->tabs->tabs as $tab ) {
			$html .= '<li>' . esc_html( $tab->title ) . '</li>';
		}

		$html .= '</ul>';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:tabs]', 'brix_tabs_content_block_admin_template', 10, 2 );

/**
 * Add a specific CSS class to the tabs block bundle field.
 *
 * @since 1.0.0
 * @param array $classes An array of CSS classes.
 * @param Brix_Field $field The field object.
 * @return array
 */
function brix_tabs_builder_block_class( $classes, $field ) {
	$value = $field->value();
	$orientation = 'horizontal';

	if ( ! empty( $value ) && isset( $value['orientation'] ) && ! empty( $value['orientation'] ) ) {
		$orientation = $value['orientation'];
	}

	$classes[] = 'brix-' . $orientation;

	return $classes;
}

add_filter( 'brix_field_classes[type:bundle][class:brix-block-tabs]', 'brix_tabs_builder_block_class', 10, 2 );

/**
 * Define the appearance of the tabs content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_tabs_content_block_stringified( $html, $data ) {
	$html = '';

	if ( isset( $data->tabs ) && isset( $data->tabs->tabs ) && ! empty( $data->tabs->tabs ) ) {
		$data->tabs->tabs = json_decode( $data->tabs->tabs );

		foreach ( $data->tabs->tabs as $tab ) {
			$html .= $tab->title;

			if ( ! empty( $tab->content ) ) {
				$html .= "<br><br>" . $tab->content;
			}
		}
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:tabs]', 'brix_tabs_content_block_stringified', 10, 2 );

/**
 * Load additional resources for the Tabs block.
 *
 * @since 1.1.3
 */
function brix_tabs_load_block_resources() {
	/* Load the tabs script. */
	brix_fw()->frontend()->add_script( 'brix-tabs' );
}

add_action( 'brix_load_block_resources[type:tabs]', 'brix_tabs_load_block_resources' );

/**
 * Add the required inline styles for the icon of the tabs builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_tabs_icon_frontend_block( $block_style, $block, $block_selector ) {
	$tabs = isset( $block->data ) && isset( $block->data->tabs->tabs ) ? $block->data->tabs->tabs : array();

	$tabs = json_decode( $tabs );

	if ( $tabs ) {
		foreach ( $tabs as $index => $tab ) {
			$icon = isset( $tab->decoration->icon ) ? $tab->decoration->icon : '';
			$decoration = isset( $tab->decoration ) ? $tab->decoration : '';

			if ( $icon ) {
				$tab_selector = $block_selector . ' [aria-controls="brix-tab-panel-' . $index . '"]' ;

				if ( $decoration->type === 'icon' ) {
					$block_style .= brix_icon_style( $tab_selector, $icon );
				}
			}

		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:tabs]', 'brix_process_tabs_icon_frontend_block', 10, 3 );