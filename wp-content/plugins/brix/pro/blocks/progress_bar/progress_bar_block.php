<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder progress bar content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderProgressBarBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the progress bar content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'progress_bar';
		$this->_title = __( 'Progress bar', 'brix' );

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
		$fields[] = array(
			'handle' => 'show_value',
			'label'  => __( 'Show value', 'brix' ),
			'help' => __( 'Display the numeric value next to the progress bar.', 'brix' ),
			'type'   => 'checkbox',
		);

		$fields[] = array(
			'handle' => 'animate',
			'label'  => __( 'Animate values', 'brix' ),
			'type'   => 'checkbox',
		);

		$fields[] = array(
			'handle' => 'data_divider',
			'text'  => __( 'Data', 'brix' ),
			'type'   => 'divider',
			'config' => array(
				'style' => 'in_page',
			)
		);

		$fields[] = array(
			'handle' => 'data',
			'label'  => array(
				'text' => __( 'Data', 'brix' ),
				'type' => 'hidden'
			),
			'type'   => 'bundle',
			'repeatable' => array(
				'sortable' => true
			),
			'fields' => array(
				array(
					'handle' => 'label',
					'label'  => array(
						'text' => __( 'Label', 'brix' ),
						'type' => 'block'
					),
					'type'   => 'text',
					'size' => 'medium'
				),
				array(
					'handle' => 'value',
					'label'  => array(
						'text' => __( 'Value', 'brix' ),
						'type' => 'block'
					),
					'type'   => 'number',
					'size' => 'medium',
					'config' => array(
						'min'   => '0',
						'max'   => '100'
					)
				),
				array(
					'handle' => 'color',
					'label'  => array(
						'text' => __( 'Color', 'brix' ),
						'type' => 'block'
					),
					'type'   => 'color',
					'size' => 'medium'
				)
			)
		);

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
		return empty( $data->data->data );
	}

}

/**
 * Add the progress bar content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_progress_bar_content_block( $blocks ) {
	$blocks['progress_bar'] = array(
		'class'       => 'BrixBuilderProgressBarBlock',
		'label'       => __( 'Progress bar', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'blocks/progress_bar/i/progress_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'blocks/progress_bar/i/progress_icon.svg',
		'description' => __( 'Display numeric values in multiple progress bars.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_progress_bar_content_block' );

/**
 * Define the appearance of the progress bar content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_progress_bar_content_block_admin_template( $html, $data ) {
	if ( isset( $data->data ) && ! empty( $data->data ) ) {

		if ( isset( $data->bar_size ) ) {
			$html .= '<span class="brix-block-style-label">Size: ';
				$html .= '<strong>' . $data->bar_size . '</strong>';

				if ( isset( $data->bar_style ) ) {
					if ( $data->bar_style == 'default' ) {
						$label = __( 'with squared borders', 'brix' );
					}
					else if ( $data->bar_style == 'rounded' ) {
						$label = __( 'with rounded borders', 'brix' );
					}

					$html .= ' <strong>' . $label . '</strong>';
				}

			$html .= '</span>';
		}


		$html .= '<ul>';

		foreach ( $data->data as $bar ) {
			$html .= sprintf( '<li>%s: <strong>%s</strong></li>',
				esc_html( $bar->label ),
				esc_html( $bar->value )
			);
		}

		$html .= '</ul>';
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:progress_bar]', 'brix_progress_bar_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the progress content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_progress_content_block_stringified( $html, $data ) {
	if ( isset( $data->data ) && ! empty( $data->data ) ) {
		$html = '<ul>';

		foreach ( $data->data as $bar ) {
			$html .= sprintf( '<li>%s: %s</li>',
				$bar->label,
				$bar->value
			);
		}

		$html .= '</ul>';
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:progress_bar]', 'brix_progress_content_block_stringified', 10, 2 );

/**
 * Custom template path for the progress bar block.
 *
 * @since 1.1.3
 * @return string
 */
function brix_progress_bar_block_template_path() {
	return BRIX_PRO_FOLDER . 'blocks/progress_bar/templates/progress_bar_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:progress_bar]', 'brix_progress_bar_block_template_path' );