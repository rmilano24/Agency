<?php

if ( ! defined( 'BRIX' ) ) {
	return;
}

/**
 * Builder Portfolio content block class.
 *
* @since 1.0.0
 */
class AgncyPortfolioBlock extends BrixBuilderLoopBlock {

	/**
	 * The content block post type.
	 *
	 * @var string
	 */
	protected $_post_type = 'agncy_project';

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'agncy_portfolio';
		$this->_title = __( 'Portfolio', 'agncy-companion-plugin' );

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
			'label' => __( 'How many items to load', 'agncy-companion-plugin' ),
			'default' => 10,
		);

		$fields[] = array(
			'handle' => 'include_taxonomy',
			'type'   => 'multiple_select',
			'label'  => __( 'Include from taxonomy', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => $this->get_taxonomy_terms(),
			),
		);

		$fields[] = array(
			'handle' => 'exclude_taxonomy',
			'type'   => 'multiple_select',
			'label'  => __( 'Exclude from taxonomy', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => $this->get_taxonomy_terms(),
			),
		);

		$fields[] = array(
			'handle' => 'agncy_portfolio_divider',
			'text'  => __( 'Style options', 'agncy-companion-plugin' ),
			'type'   => 'divider',
		);

			$fields[] = array(
				'handle' => 'agncy_portfolio_featured_image_sizes',
				'type' => 'select',
				'label' => __( 'Image size', 'agncy-companion-plugin' ),
				'config' => array(
					'visible' => array( 'agncy_featured_media' => '1' ),
					'data' => agncy_get_image_sizes_for_select()
				)
			);

		$fields[] = array(
			'handle' => 'columns',
			'type' => 'select',
			'label' => __( 'Columns', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => array(
					'2' => __( '2 columns', 'agncy-companion-plugin' ),
					'3' => __( '3 columns', 'agncy-companion-plugin' ),
					'4' => __( '4 columns', 'agncy-companion-plugin' ),
				),
			)
		);

		$fields[] = array(
			'handle' => 'paginate',
			'type' => 'select',
			'label' => __( 'Paginate', 'agncy-companion-plugin' ),
			'config' => array(
				'data' => array(
					''            => __( 'Do not paginate', 'agncy-companion-plugin' ),
					'static'      => __( 'Regular pagination', 'agncy-companion-plugin' ),
					'ajax_reload' => __( 'Reload block content', 'agncy-companion-plugin' ),
					'ajax_append' => __( 'Append new content', 'agncy-companion-plugin' ),
				),
				'controller' => true
			)
		);

		if ( function_exists( 'agncy_pagination' ) ) {
			$fields[] = array(
				'handle' => 'paginate_type',
				'type' => 'select',
				'label' => __( 'Pagination type', 'agncy-companion-plugin' ),
				'config' => array(
					'data' => array(
						'prevnext' => __( 'Previous and next links', 'agncy-companion-plugin' ),
						'numeric'  => __( 'Numeric pagination', 'agncy-companion-plugin' ),
					),
					'visible' => array( 'paginate' => 'static,ajax_reload' )
				)
			);
		}

		$fields[] = array(
			'handle' => 'agncy_portfolio_filter_divider',
			'text'  => __( 'Filter', 'agncy-companion-plugin' ),
			'type'   => 'divider',
		);

		$fields[] = array(
			'handle' => 'display_filter',
			'label' => __( 'Display filter', 'agncy-companion-plugin' ),
			'type' => 'radio',
			'config' => array(
				'data' => array(
					'none' => __( 'Do not display', 'agncy-companion-plugin' ),
					'custom' => __( 'Compose the filter', 'agncy-companion-plugin' )
				),
				'controller' => true
			)
		);

			$fields[] = array(
				'handle' => 'custom_filter',
				'type'   => 'multiple_select',
				'label'  => __( 'Filter contents', 'agncy-companion-plugin' ),
				'config' => array(
					'data' => array_merge(
						array(
							'categories' => __( 'All categories', 'agncy-companion-plugin' )
						),
						agncy_projects_metas()
					),
					'visible' => array( 'display_filter' => 'custom' )
				),
			);

			$fields[] = array(
				'handle' => 'filter_hierarchical',
				'type'   => 'checkbox',
				'label'  => __( 'Hierarchical', 'agncy-companion-plugin' ),
				'help' => __( 'Check this to enable category dropdowns.', '' ),
				'config' => array(
					'style' => array( 'switch', 'small' ),
					'visible' => array( 'display_filter' => '!=none' )
				),
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
		if ( ! empty( $data->data->paginate ) ) {
			if ( $data->data->paginate === 'static' || isset( $_POST['brix_agncy_portfolio_ajax_pagination'] ) ) {
				/* Perform pagination on AJAX requests too. */
				$query->paginate();
			}
		}

		if ( isset( $_GET[ 'project-category' ] ) && ! empty( $_GET[ 'project-category' ] ) ) {
			/* When pre-filtering items, look for query string variables first. */

			$term_slug = sanitize_text_field( $_GET[ 'project-category' ] );
			$term = get_term_by( 'slug', $term_slug, 'agncy_project_category' );

			if ( $term ) {
				$query->include_term( 'agncy_project_category', $term->term_id );
			}
		}
		else {
			/* Optionally include entries from specific taxonomies. */
			if ( isset( $data->data->include_taxonomy ) && ! empty( $data->data->include_taxonomy ) ) {
				$pairs = explode( ',', $data->data->include_taxonomy );

				foreach ( $pairs as $pair ) {
					list( $taxonomy, $term_id ) = explode( ':', $pair );

					$query->include_term( $taxonomy, $term_id );
				}
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

		/* Filter by project meta data. */
		$this->meta_filters( $query );

		return $query;
	}

	/**
	 * Filter by project meta data.
	 *
	 * @since 1.0.0
	 * @param WP_Query $query The projects query.
	 */
	private function meta_filters( &$query ) {
		$project_metas = agncy_projects_metas();
		$meta_query = array();

		foreach ( $project_metas as $meta => $label ) {
			if ( isset( $_GET[ $meta ] ) && ! empty( $_GET[ $meta ] ) ) {
				$meta_value = sanitize_text_field( $_GET[ $meta ] );

				$meta_query[] = array(
					'key' => $meta,
					'value' => $meta_value
				);
			}
		}

		if ( ! empty( $meta_query ) ) {
			$query->set_query_arg( 'meta_query', $meta_query );
		}
	}

}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function agncy_register_portfolio_content_block( $blocks ) {
	$blocks['agncy_portfolio'] = array(
		'class'       => 'AgncyPortfolioBlock',
		'label'       => __( 'Portfolio', 'agncy-companion-plugin' ),
		'icon'        => AGNCY_COMPANION_PLUGIN_URI . 'brix/blocks/portfolio/i/portfolio_icon.svg',
		'icon_path'   => AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/portfolio/i/portfolio_icon.svg',
		'description' => __( 'Display a list of projects.', 'agncy-companion-plugin' ),
		'group'       => __( 'Content', 'agncy-companion-plugin' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'agncy_register_portfolio_content_block' );

/**
 * Define the appearance of the testimonial content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_portfolio_content_block_admin_template( $html, $data ) {
	$posts_per_page = get_option( 'posts_per_page' );
	$pagination = __( 'Not paginated', 'agncy-companion-plugin' );
	$include_taxonomy = array();
	$exclude_taxonomy = array();

	if ( $data->posts_per_page != '' ) {
		if ( $data->posts_per_page == '-1' ) {
			$posts_per_page = __( 'All', 'agncy-companion-plugin' );
		}
		else {
			$posts_per_page = $data->posts_per_page;
		}
	}

	if ( ! empty( $data->paginate ) ) {
		switch ( $data->paginate ) {
			case 'static':
				$pagination = __( 'Standard pagination', 'agncy-companion-plugin' );
				break;
			case 'ajax_reload':
				$pagination = __( 'Reload block content', 'agncy-companion-plugin' );
				break;
			case 'ajax_append':
				$pagination = __( 'Append new content', 'agncy-companion-plugin' );
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
			$include_taxonomy = sprintf( __( 'From: %s', 'agncy-companion-plugin' ), implode( ', ', $include_taxonomy ) );
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
			$exclude_taxonomy = sprintf( __( 'Not from: %s', 'agncy-companion-plugin' ), implode( ', ', $exclude_taxonomy ) );
		}
	}

	$html = '<ul>';
		$html .= '<li>' . esc_html( sprintf( __( '%s projects', 'agncy-companion-plugin' ), $posts_per_page ) ) . '</li>';

		if ( ! empty( $include_taxonomy ) ) {
			$html .= '<li>' . esc_html( $include_taxonomy ) . '</li>';
		}

		if ( ! empty( $exclude_taxonomy ) ) {
			$html .= '<li>' . esc_html( $exclude_taxonomy ) . '</li>';
		}

		$html .= '<li>' . esc_html( $pagination ) . '</li>';
	$html .= '</ul>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:agncy_portfolio]', 'agncy_portfolio_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the portfolio content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function agncy_portfolio_content_block_stringified( $html, $data ) {
	return $html;
}

add_filter( 'brix_block_stringified[type:agncy_portfolio]', 'agncy_portfolio_content_block_stringified', 10, 2 );

/**
 * Custom template path for the portfolio block.
 *
 * @since 1.0.0
 * @return string
 */
function agncy_portfolio_block_template_path() {
	return AGNCY_COMPANION_PLUGIN_FOLDER . 'brix/blocks/portfolio/templates/portfolio_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:agncy_portfolio]', 'agncy_portfolio_block_template_path' );

/**
 * Portfolio block frontend class.
 *
 * @since 1.0.0
 * @param array $classes The block classes array.
 * @param array $data The content block data.
 * @return array
 */
function agncy_brix_portfolio_block_custom_class( $classes, $data ) {
	$classes[] = 'agncy-loop-portfolio';

	$ajax_pagination = array(
		'ajax_reload',
		'ajax_append'
	);

	if ( isset( $data->paginate ) && in_array( $data->paginate, $ajax_pagination ) ) {
		$classes[] = 'brix-agncy_portfolio-pagination-' . $data->paginate;
	}

	// if ( $data->agncy_style == 'grid' )  {
	// 	// $classes[] = 'agncy-p-lt-grid';
	// 	$classes[] = 'agncy-fixed-grid';
	// }
	// else if ( $data->agncy_style == 'masonry' ) {
	// 	$classes[] = 'agncy-p-lt-masonry';
	// }

	// if ( $data->agncy_style == 'grid' || $data->agncy_style == 'masonry' ) {
		if ( $data->columns ) {
			// $classes[] = 'agncy-p-gc-' .$data->columns;
			$classes[] = 'agncy-gc-' .$data->columns;
		}
	// }

	return $classes;
}

add_filter( 'brix_block_classes[type:agncy_portfolio]', 'agncy_brix_portfolio_block_custom_class', 10, 2 );