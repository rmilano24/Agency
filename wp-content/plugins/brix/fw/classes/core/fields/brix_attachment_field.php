<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Attachment field class.
 *
 * @package   BrixFramework
 * @since 	  0.4.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_AttachmentField extends Brix_Field {

	/**
	 * Constructor for the attachment field class.
	 *
	 * @since 0.4.0
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

		$data['config'] = wp_parse_args( $data['config'], array(
			/* Attachment type. */
			'type'  => '',

			/* Allows for multiple attachments upload. */
			'multiple'    => false,

			/* Allows for multiple attachments to be manually sorted. */
			'sortable'    => false,

			/* Image size on backend UI. */
			'thumb_size'  => 'medium',
		) );

		if ( ! in_array( $data['config']['type'], array( '', 'image', 'audio', 'video', 'application' ) ) ) {
			$data['config']['type'] = '';
		}

		parent::__construct( $data );
	}
}

/**
 * Add the attachment field type to the valid registered field types.
 *
 * @since 0.4.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_attachment_field_type( $types ) {
	$types['attachment'] = 'Brix_AttachmentField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_attachment_field_type' );

/**
 * Generic upload placeholder template.
 *
 * @since 0.4.0
 * @return string
 */
function brix_attachment_upload_generic_placeholder_template() {
	$placeholder_html = '<div class="brix-attachment-placeholder brix-attachment-%s-placeholder">
		<div class="brix-field-panel-controls-wrapper">
			<div class="brix-field-panel-controls-inner-wrapper">
				<a href="#" class="brix-repeatable-remove brix-upload-remove"><span class="screen-reader-text">%s</span></a>
				<span class="brix-sortable-handle brix-attachment-sortable-handle"></span>
			</div>
		</div>
		<span class="brix-attachment-placeholder-icon" data-id="%s" alt=""></span>
		<div class="brix-attachment-details">
			<span class="brix-attachment-title">%s</span>
			<a href="%s" target="_blank" rel="noopener noreferrer" class="brix-attachment-extension">%s</a>
		</div>
	</div>';

	return $placeholder_html;
}

/**
 * Append the template for the attachment upload placeholder to the body of the page
 * for later use.
 *
 * @since 0.4.0
 */
function brix_attachment_upload_placeholder_templates() {
	/* Image upload template. */
	echo '<script type="text/template" data-template="brix-attachment-placeholder">';
		// echo '<span class="brix-sortable-handle"></span>';
		printf(
			brix_attachment_upload_generic_placeholder_template(),
			'{{ type }}',
			__( 'Remove', 'brix' ),
			'{{ id }}',
			'{{ title }}',
			'{{ url }}',
			'{{ extension }}'
		);
	echo '</script>';
}

add_action( 'admin_print_footer_scripts', 'brix_attachment_upload_placeholder_templates' );