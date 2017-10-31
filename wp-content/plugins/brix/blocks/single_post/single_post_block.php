<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder single post content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderSinglePostBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the single post content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'single_post';
		$this->_title = __( 'Post', 'brix' );

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
			'handle' => 'id',
			'label'  => __( 'Post', 'brix' ),
			'help' => __( 'Select the post that you want to display.', 'brix' ),
			'type'   => 'multiple_select',
			'config' => array(
				'data' => 'brix_single_post_block_search_posts',
				'data_callback' => 'brix_single_post_block_find_post',
				'max' => 1
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
		return empty( $data->data->id );
	}

}

/**
 * Add the single post content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_single_post_content_block( $blocks ) {
	$blocks['single_post'] = array(
		'class'       => 'BrixBuilderSinglePostBlock',
		'label'       => __( 'Post', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/single_post/i/post_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/single_post/i/post_icon.svg',
		'description' => __( 'Display a single blog post.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_single_post_content_block' );

/**
 * Define the appearance of the single post content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_single_post_content_block_admin_template( $html, $data ) {
	if ( isset( $data->id ) && ! empty( $data->id ) ) {
		$post = get_post( $data->id );

		if ( $post ) {
			$html = '&ldquo;' . esc_html( $post->post_title ) . '&rdquo;';
		}
	}

	return $html;
}

add_filter( 'brix_block_admin_template[type:single_post]', 'brix_single_post_content_block_admin_template', 10, 2 );

/**
 * Search posts.
 *
 * @since 1.0.0
 */
function brix_single_post_block_search_posts() {
	if ( empty( $_POST ) || ! isset( $_POST['search'] ) ) {
		die( json_encode( array() ) );
	}

	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_multiple_select_ajax' );

	if ( ! $is_valid_nonce ) {
		die( json_encode( array() ) );
	}

	$search = sanitize_text_field( $_POST['search'] );
	$results = brix_search_items( $search );

	die( json_encode( $results ) );
}

add_action( 'wp_ajax_brix_single_post_block_search_posts', 'brix_single_post_block_search_posts' );

/**
 * Find a single blog post by its ID.
 *
 * @since 1.0.0
 * @param integer $id The post ID.
 * @return array
 */
function brix_single_post_block_find_post( $id ) {
	$post = get_post( $id );

	return array(
		'id' => $id,
		'text' => html_entity_decode( $post->post_title )
	);
}