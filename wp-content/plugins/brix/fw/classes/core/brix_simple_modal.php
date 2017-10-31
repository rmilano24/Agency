<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Simple modal class.
 *
 * A modal is a container that is displayed in a popup.
 *
 * @package   BrixFramework
 * @since 	  0.1.0
 * @version   0.1.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @link 	  https://github.com/Justevolve/evolve-framework
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Brix_SimpleModal {

	/**
	 * The modal configuration array.
	 *
	 * @var array
	 */
	private $_config = array();

	/**
	 * Constructor for the modal class.
	 *
	 * @param string $handle A slug-like definition of the modal.
	 * @param array $config Optional configuration array.
	 * @since 0.1.0
	 */
	function __construct( $handle, $config = array() )
	{
		$this->_config = wp_parse_args( $config, array(
			/* Title of the modal. */
			'title' => '',

			/* Text of the close button for the modal. */
			'button' => __( 'OK', 'brix' ),

			/* Nonce of the close button for the modal. */
			'button_nonce' => wp_create_nonce( "brix_modal_$handle" ),
		) );
	}

   /**
	* Render the modal content.
	*
	* @since 0.1.0
	* @param string $content The modal content.
	*/
	public function render( $content )
	{
		$title = $this->_config['title'];

		if ( $title ) {
			echo '<div class="brix-modal-header">';
				echo '<h1>' . esc_html( $title ) . '</h1>';
			echo '</div>';
		}

		echo '<form class="brix brix-modal">';
			wp_nonce_field( 'brix_modal', 'ev', false );

			echo $content;

			echo '<div class="brix-modal-footer">';
				brix_btn(
					$this->_config['button'],
					'save',
					array(
						'attrs' => array(
							'data-nonce' => $this->_config['button_nonce'],
							'class' => 'brix-save',
							'type' => 'submit'
						),
						'size' => 'medium'
					)
				);
			echo '</div>';
		echo '</form>';
	}

}