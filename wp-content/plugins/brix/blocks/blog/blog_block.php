<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder blog content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderBlogBlock extends BrixBuilderLoopBlock {

	/**
	 * The content block post type.
	 *
	 * @var string
	 */
	protected $_post_type = 'post';

	/**
	 * Constructor for the blog content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'blog';
		$this->_title = __( 'Blog', 'brix' );

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
			'handle' => 'posts_per_page',
			'type' => 'number',
			'label' => __( 'How many items to load', 'brix' ),
			'default' => 10
		);

		$fields[] = array(
			'handle' => 'include_taxonomy',
			'type'   => 'multiple_select',
			'label'  => __( 'Include from taxonomy', 'brix' ),
			'config' => array(
				'data' => $this->get_taxonomy_terms()
			),
		);

		$fields[] = array(
			'handle' => 'exclude_taxonomy',
			'type'   => 'multiple_select',
			'label'  => __( 'Exclude from taxonomy', 'brix' ),
			'config' => array(
				'data' => $this->get_taxonomy_terms()
			),
		);

		$fields[] = array(
			'handle' => 'paginate',
			'type' => 'select',
			'label' => __( 'Paginate', 'brix' ),
			'config' => array(
				'visible' => apply_filters( 'brix_blog_block_paginate_field_visible', array() ),
				'controller' => true,
				'data' => array(
					''            => __( 'Do not paginate', 'brix' ),
					'static'      => __( 'Regular pagination', 'brix' ),
					'ajax_reload' => __( 'Reload block content', 'brix' ),
					'ajax_append' => __( 'Append new content', 'brix' ),
				)
			)
		);

		return $fields;
	}

	/**
	 * Parse the query object and modify it according to the content block data.
	 *
	 * @since 1.0.0
	 * @param stdClass $data The builder content block data.
	 * @param WP_Query $query The builder query object.
	 * @return WP_Query
	 */
	public function parse_query( $data, $query )
	{
		/* How many items to load. Defaults to the Reading > Settings setting value. */
		$query->set_query_arg( 'posts_per_page', $data->data->posts_per_page );

		/* Setting up pagination, if needed. */
		$pagination_on = ! empty( $data->data->paginate ) && ( $data->data->paginate === 'static' || isset( $_POST['brix_blog_ajax_pagination'] ) );
		$pagination_on = apply_filters( 'brix_blog_paginate', $pagination_on, $data->data );

		if ( $pagination_on ) {
			/* Perform pagination on AJAX requests too. */
			$query->paginate();
		}

		/* Optionally include entries from specific taxonomies. */
		if ( isset( $data->data->include_taxonomy ) && ! empty( $data->data->include_taxonomy ) ) {
			$pairs = explode( ',', $data->data->include_taxonomy );

			foreach ( $pairs as $pair ) {
				list( $taxonomy, $term_id ) = explode( ':', $pair );

				$query->include_term( $taxonomy, $term_id );
			}
		}

		/* Optionally exclude entries from specific taxonomies. */
		if ( isset( $data->data->exclude_taxonomy ) && ! empty( $data->data->exclude_taxonomy ) ) {
			$pairs = explode( ',', $data->data->exclude_taxonomy );

			foreach ( $pairs as $pair ) {
				list( $taxonomy, $term_id ) = explode( ':', $pair );

				$query->exclude_term( $taxonomy, $term_id );
			}
		}

		return $query;
	}
}

/**
 * Add the blog content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_blog_content_block( $blocks ) {
	$blocks['blog'] = array(
		'class'       => 'BrixBuilderBlogBlock',
		'label'       => __( 'Blog', 'brix' ),
		'icon'        => BRIX_URI . 'blocks/blog/i/blog_icon.svg',
		'icon_path'   => BRIX_FOLDER . 'blocks/blog/i/blog_icon.svg',
		'description' => __( 'Display a list of blog posts.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_blog_content_block' );

/**
 * Blog block frontend class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function brix_blog_block_pagination_class( $classes, $data ) {
	$ajax_pagination = array(
		'ajax_reload',
		'ajax_append'
	);

	if ( isset( $data->paginate ) && in_array( $data->paginate, $ajax_pagination ) ) {
		$classes[] = ' brix-blog-pagination-' . $data->paginate;
	}

	return $classes;
}

add_filter( 'brix_block_classes[type:blog]', 'brix_blog_block_pagination_class', 10, 2 );

/**
 * Define the appearance of the blog in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_blog_content_block_admin_template( $html, $data ) {
	$posts_per_page = get_option( 'posts_per_page' );
	$pagination = __( 'Not paginated', 'brix' );
	$include_taxonomy = array();
	$exclude_taxonomy = array();

	if ( $data->posts_per_page != '' ) {
		if ( $data->posts_per_page == '-1' ) {
			$posts_per_page = __( 'All', 'brix' );
		}
		else {
			$posts_per_page = $data->posts_per_page;
		}
	}

	if ( ! empty( $data->paginate ) ) {
		switch ( $data->paginate ) {
			case 'static':
				$pagination = __( 'Standard pagination', 'brix' );
				break;
			case 'ajax_reload':
				$pagination = __( 'Reload block content', 'brix' );
				break;
			case 'ajax_append':
				$pagination = __( 'Append new content', 'brix' );
				break;
		}
	}

	if ( ! empty( $data->include_taxonomy ) ) {
		$terms = explode( ',', $data->include_taxonomy );

		foreach ( $terms as $term ) {
			list( $taxonomy, $term_id ) = explode( ':', $term );

			$term_obj = get_term( $term_id, $taxonomy );

			if ( $term_obj ) {
				$include_taxonomy[] = $term_obj->name;
			}
		}

		if ( ! empty( $include_taxonomy ) ) {
			$include_taxonomy = sprintf( __( 'From: %s', 'brix' ), implode( ', ', $include_taxonomy ) );
		}
	}

	if ( ! empty( $data->exclude_taxonomy ) ) {
		$terms = explode( ',', $data->exclude_taxonomy );

		foreach ( $terms as $term ) {
			list( $taxonomy, $term_id ) = explode( ':', $term );

			$term_obj = get_term( $term_id, $taxonomy );

			if ( $term_obj ) {
				$exclude_taxonomy[] = $term_obj->name;
			}
		}

		if ( ! empty( $exclude_taxonomy ) ) {
			$exclude_taxonomy = sprintf( __( 'Not from: %s', 'brix' ), implode( ', ', $exclude_taxonomy ) );
		}
	}

	$html = '<ul>';
		$html .= apply_filters( 'brix_blog_content_block_admin_template_data_before', '', $data );

		$html .= '<li>' . esc_html( sprintf( __( '%s posts', 'brix' ), $posts_per_page ) ) . '</li>';

		if ( ! empty( $include_taxonomy ) ) {
			$html .= '<li>' . esc_html( $include_taxonomy ) . '</li>';
		}

		if ( ! empty( $exclude_taxonomy ) ) {
			$html .= '<li>' . esc_html( $exclude_taxonomy ) . '</li>';
		}

		$html .= '<li>' . esc_html( $pagination ) . '</li>';

		$html .= apply_filters( 'brix_blog_content_block_admin_template_data_after', '', $data );
	$html .= '</ul>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:blog]', 'brix_blog_content_block_admin_template', 10, 2 );