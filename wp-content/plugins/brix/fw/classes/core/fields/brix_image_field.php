<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Image field class.
 *
 * @package   BrixFramework
 * @since 	  0.1.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_ImageField extends Brix_Field {

	/**
	 * Constructor for the image field class.
	 *
	 * @since 0.1.0
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
			/* Allows for multiple images upload; doesn't manage densities, nor breakpoints, nor image sizes. */
			'multiple'    => false,

			/* Allows for multiple images to be manually sorted. */
			'sortable'    => false,

			/* When false, revert to the default 'desktop' breakpoint. */
			'breakpoints' => false,

			/* When false, revert to the default '1' density. */
			'density'     => false,

			/* Not taken into account when multiple or more than one density is being used. */
			'image_size'  => false,

			/* Image size on backend UI. */
			'thumb_size'  => 'medium',
		) );

		parent::__construct( $data );
	}
}

/**
 * Add the image field type to the valid registered field types.
 *
 * @since 0.1.0
 * @param array $types An array containing the valid registered field types.
 * @return array
 */
function brix_register_image_field_type( $types ) {
	$types['image'] = 'Brix_ImageField';

	return $types;
}

add_filter( 'brix_field_types', 'brix_register_image_field_type' );

/**
 * Append the template for the image upload placeholder to the body of the page
 * for later use.
 *
 * @since 0.1.0
 */
function brix_image_upload_placeholder_template() {
	$placeholder_html = '<div class="brix-image-placeholder">
		<img data-id="%s" src="%s" alt="">
		<a href="#" class="brix-upload-remove"><span class="screen-reader-text">%s</span></a>
	</div>';

	echo '<script type="text/template" data-template="brix-image-placeholder">';
		printf(
			$placeholder_html,
			'{{ id }}',
			'{{ url }}',
			__( 'Remove', 'brix' )
		);
	echo '</script>';
}

add_action( 'admin_print_footer_scripts', 'brix_image_upload_placeholder_template' );