<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Builder team content block class.
 *
 * @package   Brix
 * @since 	  1.0.0
 * @version   1.0.0
 * @author 	  Evolve <info@justevolve.it>
 * @copyright Copyright (c) 2016, Andrea Gandino, Simone Maranzana
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class BrixBuilderTeamBlock extends BrixBuilderBlock {

	/**
	 * Constructor for the content block.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->_type = 'team';
		$this->_title = __( 'Team member', 'brix' );

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
			'handle' => 'picture_source',
			'type' => 'select',
			'label' => __( 'Picture source', 'brix' ),
			'config' => array(
				'controller' => true,
				'data' => array(
					'image'    => __( 'Media library', 'brix' ),
					'external' => __( 'External link', 'brix' )
				)
			)
		);

		$fields[] = array(
			'handle' => 'picture_url',
			'type' => 'text',
			'label' => __( 'External link', 'brix' ),
			'help' => __( 'Specify the full URL to the image.', 'brix' ),
			'config' => array(
				'full' => true,
				'visible' => array( 'picture_source' => 'external' )
			)
		);

		$fields[] = array(
			'handle' => 'picture',
			'type' => 'image',
			'label'  => __( 'Picture', 'brix' ),
			'config' => array(
				'visible' => array( 'picture_source' => 'image' ),
				'image_size' => true
			)
		);

		$fields[] = array(
			'handle' => 'name',
			'type' => 'text',
			'label'  => __( 'Name', 'brix' ),
			'config' => array(
				'full' => true,
			)
		);

		$fields[] = array(
			'handle' => 'role',
			'type' => 'text',
			'label'  => __( 'Role', 'brix' ),
			'config' => array(
				'full' => true,
			)
		);

		$fields[] = array(
			'handle' => 'bio',
			'type' => 'textarea',
			'label'  => __( 'Bio', 'brix' ),
			'config' => array(
				'rich' => true,
				'rows' => 16
			)
		);

		$fields[] = array(
			'handle' => 'alignment',
			'type' => 'select',
			'label' => __( 'Alignment', 'brix' ),
			'config' => array(
				'data' => array(
					'left' => __( 'Left', 'brix' ),
					'center' => __( 'Center', 'brix' ),
					'right' => __( 'Right', 'brix' )
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
		$team_empty = false;
		$team_data_empty = false;

		if ( empty( $data->data->name ) && empty( $data->data->role ) && empty( $data->data->bio ) ) {
			$team_data_empty = true;
		}

		if ( $team_data_empty ) {
			if ( isset( $data->data->picture_source ) && $data->data->picture_source == 'image' ) {
				if ( isset( $data->data->picture ) && empty( $data->data->picture->desktop[1]->id ) ) {
					$team_empty = true;
				}
			}

			if ( isset( $data->data->picture_source ) && $data->data->picture_source == 'external' ) {
				if ( isset( $data->data->picture_url ) && empty( $data->data->picture_url ) ) {
					$team_empty = true;
				}
			}
		}

		return $team_empty;
	}

}

/**
 * Add the text content block to the registered content blocks.
 *
 * @since 1.0.0
 * @param array $types An array containing the registered content blocks.
 * @return array
 */
function brix_register_team_content_block( $blocks ) {
	$blocks['team'] = array(
		'class'       => 'BrixBuilderTeamBlock',
		'label'       => __( 'Team member', 'brix' ),
		'icon'        => BRIX_PRO_URI . 'blocks/team/i/team_icon.svg',
		'icon_path'   => BRIX_PRO_FOLDER . 'blocks/team/i/team_icon.svg',
		'description' => __( 'Display information about a person/team member.', 'brix' ),
		'group'       => __( 'Content', 'brix' )
	);

	return $blocks;
}

add_filter( 'brix_get_blocks', 'brix_register_team_content_block' );

/**
 * Return the full URL to the picture of a team member.
 *
 * @since 1.0.0
 * @param stdClass $data The block data.
 * @param string $image_size The image size to retrieve.
 * @return string
 */
function brix_team_content_block_get_picture_url( $data, $image_size = 'full' ) {
	$team_picture = '';
	$team_picture_url = '';
	$team_picture_source = isset( $data->picture_source ) ? $data->picture_source : 'image';

	if ( $team_picture_source === 'image' ) {
		if ( isset( $data->picture ) ) {
			$team_picture = $data->picture;

			if ( isset( $team_picture->desktop[1]->id ) && ! empty( $team_picture->desktop[1]->id ) ) {
				if ( isset( $team_picture->desktop[1]->image_size ) ) {
					$image_size = $team_picture->desktop[1]->image_size;
				}

				$team_picture_url = brix_get_image( $team_picture->desktop[1]->id, $image_size );
			}
		}
	}
	elseif ( $team_picture_source === 'external' ) {
		if ( isset( $data->picture_url ) ) {
			if ( ! empty( $data->picture_url ) ) {
				$team_picture_url = $data->picture_url;
			}
		}
	}

	return $team_picture_url;
}

/**
 * Define the appearance of the team content block in the admin.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_team_content_block_admin_template( $html, $data ) {
	$html                = '';
	$image_style         = '';
	$default_classes     = array(
		'brix-team-block-wrapper'
	);

	$block_admin_classes = apply_filters( "brix_team_content_block_admin_template_classes", $default_classes, $data );
	$image_style         = apply_filters( "brix_team_content_block_admin_template_image_style", $image_style, $data );

	$html = sprintf( '<div class="%s">', esc_attr( implode( ' ', $block_admin_classes ) ) );

		$picture_url = brix_team_content_block_get_picture_url( $data, 'thumbnail' );

		if ( $picture_url ) {
			$html .= '<div class="brix-team-block-picture" style="' . esc_attr( $image_style ) . '">';
				$html .= sprintf( '<img src="%s" />', esc_url( $picture_url ) );
			$html .= '</div>';
		}

		if ( isset( $data->name ) && ! empty( $data->name ) ) {
			$html .= '<div class="brix-team-block-name">' . esc_html( $data->name ) . '</div>';
		}

		if ( isset( $data->role ) && ! empty( $data->role ) ) {
			$html .= '<div class="brix-team-block-role">' . esc_html( $data->role ) . '</div>';
		}

		if ( isset( $data->bio ) && ! empty( $data->bio ) ) {
			$html .= '<div class="brix-team-block-bio">' . wp_kses_post( $data->bio ) . '</div>';
		}

	$html .= '</div>';

	return $html;
}

add_filter( 'brix_block_admin_template[type:team]', 'brix_team_content_block_admin_template', 10, 2 );

/**
 * Define the appearance of the team content block when stringified.
 *
 * @since 1.0.0
 * @param string $html The html code of the content block.
 * @param stdClass $data The content block data.
 * @return string
 */
function brix_team_content_block_stringified( $html, $data ) {
	$html = '';

	$picture_url = brix_team_content_block_get_picture_url( $data );

	if ( $picture_url ) {
		$html .= sprintf( '<img src="%s" />', esc_url( $picture_url ) );
	}

	if ( isset( $data->name ) && ! empty( $data->name ) ) {
		$html .= "<br><br>" . $data->name;
	}

	if ( isset( $data->role ) && ! empty( $data->role ) ) {
		$html .= "<br><br>" . $data->role;
	}

	if ( isset( $data->bio ) && ! empty( $data->bio ) ) {
		$html .= "<br><br>" . $data->bio;
	}

	return $html;
}

add_filter( 'brix_block_stringified[type:team]', 'brix_team_content_block_stringified', 10, 2 );

/**
 * Custom template path for the team block.
 *
 * @since 1.1.3
 * @return string
 */
function brix_team_block_template_path() {
	return BRIX_PRO_FOLDER . 'blocks/team/templates/team_block_template.php';
}

add_filter( 'brix_block_master_template_path[type:team]', 'brix_team_block_template_path' );

/**
 * Get the builder addons team alignment value.
 *
 * @since 1.0.0
 * @param  array $data The block data object
 * @return string
 */
function brix_styles_team_get_alignment( $data ) {
	$alignment = isset( $data ) && isset( $data->alignment ) ? $data->alignment : 'left';

	return $alignment;
}

/**
 * Admin template classes.
 *
 * @since 1.0.0
 * @param array $default_classes An array of default classes.
 * @param array $data The block data object.
 * @return array
 */
function brix_team_content_block_admin_template_classes( $default_classes, $data ) {
	$default_classes[] = 'brix-team-alignment-' . brix_styles_team_get_alignment( $data );

	return $default_classes;
}

add_filter( 'brix_team_content_block_admin_template_classes', 'brix_team_content_block_admin_template_classes', 10, 2 );

/**
 * Add the required inline styles for the team builder block.
 *
 * @since 1.0.0
 * @param string $block_style The block style.
 * @param stdClass $block The block object.
 * @param string $block_selector The block CSS selector.
 * @return string
 */
function brix_process_team_frontend_block( $block_style, $block, $block_selector ) {
	$layout = brix_styles_team_get_alignment( $block->data );

	if ( $layout ) {
		$block_style .= $block_selector . ' {';
			$block_style .= 'text-align:' . $layout . ';';
		$block_style .= '}';

		if ( $layout == 'left' || $layout == 'right' ) {
			$block_style .= $block_selector . ' .brix-team-block-picture img {';
				$block_style .= 'float:' . $layout . ';';
			$block_style .= '}';
		} else if ( $layout == 'center' ) {
			$block_style .= $block_selector . ' .brix-team-block-picture img {';
				$block_style .= 'margin: 0 auto;';
			$block_style .= '}';
		}
	}

	return $block_style;
}

add_filter( 'brix_block_style[type:team]', 'brix_process_team_frontend_block', 10, 3 );

/**
 * Get the team member picture
 *
 * @since 1.2.7
 * @param  array $data The block data object
 */
function brix_team_block_get_picture( $data ) {
	$team_picture_url = '';
	$image_size = 'full';

	if ( $data->data->picture_source == 'image' ) {
		$image_size = 'full';
		$image_id = 0;

		if ( isset( $data->data->picture ) ) {
			$team_picture = $data->data->picture;

			if ( isset( $team_picture->desktop[1]->id ) && ! empty( $team_picture->desktop[1]->id ) ) {
				if ( isset( $team_picture->desktop[1]->image_size ) ) {
					$image_size = $team_picture->desktop[1]->image_size;
				}

				$image_id = $team_picture->desktop[1]->id;
			}
		}
	}
	else {
		$image_id = brix_team_content_block_get_picture_url( $data->data );
	}

?>
	<?php if ( ! empty( $image_id ) ) : ?>
		<div class="brix-team-block-picture">
			<?php
				echo brix_get_lazy_image_markup( $image_id, array(
					'size' => $image_size
				) );
			?>
		</div>
	<?php endif; ?>
<?php
}

/**
 * Get the team member name
 *
 * @since 1.2.7
 * @param  array $data The block data object
 */
function brix_team_block_get_team_name( $data ) {
	$team_name    = '';

	if ( isset( $data->data->name ) ) {
		$team_name = $data->data->name;
	}
?>
	<?php if ( ! empty( $team_name ) ) : ?>
		<div class="brix-team-block-name">
			<p><?php echo esc_html( $team_name ); ?></p>
		</div>
	<?php endif; ?>
<?php
}

/**
 * Get the team member role
 *
 * @since 1.2.7
 * @param  array $data The block data object
 */
function brix_team_block_get_team_role( $data ) {
	$team_role    = '';

	if ( isset( $data->data->role ) ) {
		$team_role = $data->data->role;
	}
?>
	<?php if ( ! empty( $team_role ) ) : ?>
		<div class="brix-team-block-role">
			<p><?php echo esc_html( $team_role ); ?></p>
		</div>
	<?php endif; ?>
<?php
}

/**
 * Get the team member bio
 *
 * @since 1.2.7
 * @param  array $data The block data object
 */
function brix_team_block_get_team_bio( $data ) {
	$team_bio     = '';

	if ( isset( $data->data->bio ) ) {
		$team_bio = $data->data->bio;
	}
?>
	<?php if ( ! empty( $team_bio ) ) : ?>
		<div class="brix-team-block-bio">
			<?php echo brix_format_text_content( $team_bio ); ?>
		</div>
	<?php endif; ?>
<?php
}