<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder accordion content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderAccordionBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the accordion content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'accordion';
		$this->_title = __( 'Accordion', 'brix' );

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
		$fields = brix_accordion_content_block_fields();

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
		$accordion_empty = false;
		$accordion_data = isset( $data->data->accordion->toggle ) && ! empty( $data->data->accordion->toggle ) ? json_decode( $data->data->accordion->toggle ) : '';

		if ( empty( $accordion_data ) ) {
			$accordion_empty = true;
		}

		return $accordion_empty;
	}
}

/**
 * Add the accordion content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_accordion_content_block( $blocks ) {
	$blocks['accordion'] = array(
		'class'       => 'BrixBuilderAccordionBlock',
		'label'       => __( 'Accordion', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/accordion/i/accordion_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/accordion/i/accordion_icon.svg',
		'description' => __( 'Display accordion content.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_accordion_content_block' );

/**
 * Accordion block fields.
 *
 * @since 1.0.0
 * @return array
 */
function brix_accordion_content_block_fields() {
	return array(
		array(
			'type' => 'bundle',
			'label' => array(
				'type' => 'hidden',
				'text' => __( 'Accordion', 'brix' )
			),
			'handle' => 'accordion',
			'fields' => array(
				array(
					'handle' => 'toggle',
					'type' => 'accordion',
					'label' => array(
						'text' => __( 'Accordion', 'brix' ),
						'type' => 'block'
					),
				),
				array(
					'handle' => 'open',
					'type' => 'text',
					'label' => __( 'Expanded index', 'brix' ),
					'help' => __( 'Insert the zero-based index of the toggle to be expanded by default.', 'brix' ),
					'config' => array(
						'min' => 0
					)
				),
				array(
					'handle' => 'mode',
					'type' => 'select',
					'label' => __( 'Mode', 'brix' ),
					'config' => array(
						'data' => array(
							'' => __( 'Accordion (one toggle open at a time)', 'brix' ),
							'toggle' => __( 'Multiple toggles open at a time', 'brix' )
						)
					)
				),
				array(
					'handle' => 'icon_position',
					'type' => 'select',
					'label' => __( 'Icon position', 'brix' ),
					'config' => array(
						'data' => array(
							'left'  => __( 'Left', 'brix' ),
							'right' => __( 'Right', 'brix' )
						)
					)
				)
			),
			'config' => array(
				'class' => 'brix-block-accordion'
			)
		)
	);
}

/**
 * Print localization strings.
 *
 * @since 1.0.0
 */
function brix_toggle_i18n_strings() {
	wp_localize_script( 'jquery', 'brix_toggle_i18n_strings', array(
		'title' => __( 'Untitled toggle', 'brix' )
	) );
}

add_action( 'admin_enqueue_scripts', 'brix_toggle_i18n_strings' );

/**
 * Define the appearance of the accordion content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_accordion_content_block_admin_template( $html, $data ) {
	if ( isset( $data->accordion ) && isset( $data->accordion->toggle ) && ! empty( $data->accordion->toggle ) ) {
		if ( isset( $data->_style ) ) {
			$html .= '<span class="brix-block-style-label">' . __( 'Style', 'brix' ) . ': <strong>' . $data->_style . '</strong></span>';
		}

		$data->accordion->toggle = json_decode( $data->accordion->toggle );

		$html .= '<ul>';

		foreach ( $data->accordion->toggle as $toggle ) {
			$html .= '<li>' . esc_html( $toggle->title ) . '</li>';
		}

		$html .= '</ul>';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:accordion]', 'brix_accordion_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the accordion content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_accordion_content_block_stringified( $html, $data ) {
	$html = '';

	if ( isset( $data->accordion ) && isset( $data->accordion->toggle ) && ! empty( $data->accordion->toggle ) ) {
		$data->accordion->toggle = json_decode( $data->accordion->toggle );

		foreach ( $data->accordion->toggle as $toggle ) {
			$html .= $toggle->title;

			if ( ! empty( $toggle->content ) ) {
				$html .= "<br><br>" . $toggle->content;
			}
		}
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:accordion]', 'brix_accordion_content_block_stringified', 10, 2 );

/**
 * Load additional resources for the Accordion block.
 *
 * @since 1.1.3
 */
function brix_accordion_load_block_resources() {
	/* Load the accordion script. */
	brix_fw()->frontend()->add_script( 'brix-accordion' );
}

add_action( 'brix_load_block_resources[type:accordion]', 'brix_accordion_load_block_resources' );


/**
 * Add the required inline styles for the icon of the accordion builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_accordion_frontend_icon_block( $block_style, $block, $block_selector ) {
	$accordion = isset( $block->data ) && isset( $block->data->accordion->toggle ) ? $block->data->accordion->toggle : array();
	$accordion = json_decode( $accordion );

	if ( $accordion ) {
		foreach ( $accordion as $index => $toggle ) {
			$icon = isset( $toggle->decoration->icon ) ? $toggle->decoration->icon : '';
			$decoration = isset( $toggle->decoration ) ? $toggle->decoration : '';

			if ( $icon ) {
				$toggle_selector = $block_selector . ' [aria-controls="brix-accordion-panel-' . $index . '"]' ;

				if ( $decoration->type === 'icon' ) {
					$block_style .= brix_icon_style( $toggle_selector, $icon );
				}
			}

		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:accordion]', 'brix_process_accordion_frontend_icon_block', 10, 3 );