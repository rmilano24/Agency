<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder content loop block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
abstract class BrixBuilderLoopBlock extends BrixBuilderBlock {

	/**
	 * The content block post type.
	 *
	 * @var string
	 */
	protected $_post_type = '';

	/**
	 * Get a list of terms that belong to the taxonomies associated with the
	 * current post type.
	 *
	 * The function returns an array of items in the taxononmy:term_id format.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_taxonomy_terms()
	{
		$terms = array();
		$taxonomies = get_object_taxonomies( $this->_post_type, 'objects' );
		$taxonomies = wp_list_filter( $taxonomies, array(
			'public'  => true,
			'show_ui' => true
		) );

		foreach ( $taxonomies as $taxonomy ) {
			foreach ( get_terms( $taxonomy->name, array( 'hide_empty' => false ) ) as $term ) {
				$key = $taxonomy->name . ':' . $term->term_id;

				$terms[$key] = array(
					'label' => $term->name,
					'spec' => $taxonomy->labels->singular_name
				);
			}
		}

		return $terms;
	}

	/**
	 * Return an array containing the query parameters.
	 *
	 * @since 1.0.0
	 * @param stdClass $data The builder content block data.
	 * @return array
	 */
	abstract public function parse_query( $data, $query );

	/**
	 * Render the loop content block on frontend.
	 *
	 * @since 1.0.0
	 * @param array $data The content block data.
	 */
	public function render( $data )
	{
		$query_args = array();
		$query_args['post_type'] = $this->_post_type;

		$data->_query = new Brix_Query( $query_args );
		$data->_query = $this->parse_query( $data, $data->_query );

		parent::render( $data );

		wp_reset_postdata();
	}

}