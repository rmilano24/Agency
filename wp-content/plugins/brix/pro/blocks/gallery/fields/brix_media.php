<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Media field class.
 *
 * @package   Brix
 * @since 	  1.2.9
 * @version   1.0.0
 */

class Brix_MediaField extends Brix_Field {

	/**
	 * Constructor for the image field class.
	 *
	 * @since 1.0.0
	 * @param array $data The field data structure.
	 */
	public function __construct( $data )
	{
		if ( ! isset( $data['default'] ) ) {
			$data['default'] = '';
		}

		if ( ! isset( $data['config'] ) ) {
			$data['config'] = array();
		}

		parent::__construct( $data );
	}
}

/**
 * Add the image field type to the valid registered field types.
 *
 * @since 1.0.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_media_field_type( $types ) {
	$types['brix_media'] = 'Brix_MediaField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_media_field_type' );

/**
 * Return the path to the template of the media field.
 *
 * @since 1.2.9
 * @param string $template The template path string.
 * @return string
 */
function brix_media_field_template_path( $template ) {
	$template = BRIX_PRO_FOLDER . 'blocks/gallery/fields/templates/brix_media.php';

	return $template;
}

add_filter( "brix_field_template[type:brix_media]", 'brix_media_field_template_path' );

/**
 * Append the template for the image upload placeholder to the body of the page
 * for later use.
 *
 * @since 1.2.9
 */
function brix_media_upload_placeholder_template() {
	$placeholder_html = '<div data-source="%s" class="brix-image-placeholder brix-media-placeholder">
		<img src="%s" alt="">
		<a href="#" class="brix-upload-remove"><span class="screen-reader-text">%s</span></a>
	</div>';

	$embed_placeholder_html = '<div data-source="%s" class="brix-image-placeholder brix-media-embed-placeholder">
		<a href="#" class="brix-upload-remove"><span class="screen-reader-text">%s</span></a>
	</div>';

	echo '<script type="text/template" data-template="brix_media-placeholder">';
		printf(
			$placeholder_html,
			'{{ source }}',
			'{{ url }}',
			esc_html__( 'Remove', 'brix' )
		);
	echo '</script>';

	echo '<script type="text/template" data-template="brix_media-embed-placeholder">';
		printf(
			$embed_placeholder_html,
			'{{ source }}',
			esc_html__( 'Remove', 'brix' )
		);
	echo '</script>';
}

add_action( 'admin_print_footer_scripts', 'brix_media_upload_placeholder_template' );

/**
 * Populate the color presets editing modal.
 *
 * @since 1.2.9
 */
function brix_media_embed_modal_load() {
	if ( ! brix_is_post_nonce_valid( 'brix_media_embed_modal' ) ) {
		die();
	}

	$url = new Brix_TextField( array(
		'handle'  => 'url',
		'label'   => __( 'URL', 'brix' ),
		'help'    => __( 'The URL of the resource to embed', 'brix' ),
		'type'    => 'text',
		'default' => isset( $_POST[ 'data' ][ 'url' ] ) ? sanitize_text_field( $_POST[ 'data' ][ 'url' ] ) : '',
		'config' => array(
			'full' => true
		)
	) );

	ob_start();
	$url->render();
	$content = ob_get_contents();
	ob_end_clean();

	$m = new Brix_SimpleModal( 'brix-media-embed', array( 'title' => __( 'Embed', 'brix' ) ) );
	$m->render( $content );

	die();
}

add_action( 'wp_ajax_brix_media_embed_modal_load', 'brix_media_embed_modal_load' );