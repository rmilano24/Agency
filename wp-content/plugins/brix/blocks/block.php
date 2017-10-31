<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
abstract class BrixBuilderBlock {

	/**
	 * The content block type.
	 *
	 * @var string
	 */
	protected $_type = '';

	/**
	 * The content block title.
	 *
	 * @var string
	 */
	protected $_title = '';

	/**
	 * Return the list of fields that compose the content block.
	 *
	 * @since 1.0.0
	 * @param array $fields A list of fields that compose the content block.
	 * @return array
	 */
	abstract public function fields( $fields );

	/**
	 * Render the content block on frontend.
	 *
	 * @since 1.0.0
	 * @param array $data The content block data.
	 */
	public function render( $data )
	{
		$markup = '';

		if ( $this->is_empty( $data ) ) {
			/* If the block is empty, load an alternative template on frontend. */
			$markup = apply_filters( "brix_block_empty_render[type:{$data->data->_type}]", $markup );

			if ( ! empty( $markup ) ) {
				$markup = '<div class="brix-empty-block">' . $markup . '</div>';
			}
		}

		if ( empty( $markup ) ) {
			$path = BRIX_BLOCKS_FOLDER . $data->data->_type .'/templates/' . $data->data->_type . '_block_template';
			$path = apply_filters( "brix_block_master_template_path[type:{$data->data->_type}]", $path, $data->data );

			brix_template( $path, array(
				'data' => $data,
				'block' => $this
			) );
		}
		else {
			echo wp_kses_post( $markup );
		}
	}

	/**
	 * Return the content block title.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_title()
	{
		return $this->_title;
	}

	/**
	 * Return the block templates array
	 *
	 * @return array The block templates array
	 */
	protected function get_block_templates( $templates )
	{
		return apply_filters( "brix_get_block_templates[type:{$this->_type}]", $templates );
	}

	/**
	 * Return the list of fields that compose the style of the content block.
	 *
	 * @since 1.0.0
	 * @param array $fields A list of fields that compose the style of the content block.
	 * @return array
	 */
	public function style_fields( $fields )
	{
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
		return false;
	}

}

/**
 * Return the template path according to the template style selected in a builder content block.
 *
 * @since 1.0.0
 * @param string $block_type The content block type.
 * @param string $selected_template The content block selected style option.
 * @return string
 */
function brix_get_block_template_path( $block_type, $selected_template ) {
	$template_paths = apply_filters( "brix_get_block_template_path[type:$block_type]", array() );

	if ( isset( $template_paths[$selected_template] ) ) {
		return $template_paths[$selected_template];
	}

	return false;
}

/**
 * Display the block admin template when inserted in the builder layout.
 *
 * @since 1.0.0
 * @param string $block_type The content block type.
 * @param array $data The content block data.
 * @return string
 */
function brix_get_block_admin_template( $block_type, $data = array() ) {
	$allowed_html = wp_kses_allowed_html( 'post' );

	$data = json_decode( json_encode( $data ) );
	$html = apply_filters( "brix_block_admin_template[type:$block_type]", '', $data );
	$html = wp_kses( $html, $allowed_html );

	return $html;
}

/**
 * Display a stringified version of the block.
 *
 * @since 1.0.0
 * @param string $block_type The content block type.
 * @param array $data The content block data.
 * @return string
 */
function brix_get_block_stringified( $block_type, $data = array() ) {
	$data = json_decode( json_encode( $data ) );
	$html = apply_filters( "brix_block_stringified[type:$block_type]", '', $data );

	return $html;
}

/**
 * Retrieve an admin block template via AJAX.
 *
 * @since 1.0.0
 */
function brix_ajax_get_block_admin_template() {
	/* Verify that we're submitting any data. */
	if ( empty( $_POST ) ) {
		die();
	}

	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_modal_brix_block' );

	if ( ! $is_valid_nonce ) {
		die();
	}

	$block_type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : false;
	$block_data = isset( $_POST['data'] ) ? $_POST['data'] : false;

	if ( ! $block_type || ! $block_data ) {
		die();
	}

	$block_data = stripslashes_deep( $block_data );

	$ret = array(
		'admin_template' => brix_get_block_admin_template( $block_type, $block_data ),
		'stringified' => brix_get_block_stringified( $block_type, $block_data ),
	);

	echo json_encode( $ret );

	die();
}

add_action( 'wp_ajax_brix_ajax_get_block_admin_template', 'brix_ajax_get_block_admin_template' );
