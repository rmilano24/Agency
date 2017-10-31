<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Convert line endings in an array.
 *
 * @since 1.0.0
 * @param array $data The data array.
 * @return array
 */
function brix_replace_endlines_deep( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	foreach ( $data as $k => $v ) {
		if ( is_array( $v ) ) {
			$data[$k] = brix_replace_endlines_deep( $v );
		}
		else {
			$data[$k] = str_replace( "\r\n", "\n", $data[$k] );
		}
	}

	return $data;
}

/**
 * Serialize a template's data.
 *
 * @since 1.0.0
 * @param array $data The template's data array.
 * @param boolean $escape_slashes Set to true to escape slashes.
 * @return string
 */
function brix_serialize_template( $data, $escape_slashes = false ) {
	$data = stripslashes_deep( $data );
	$data = json_decode( $data, true );

	if ( $escape_slashes === true ) {
		$data = brix_escape_slashes_deep( $data );
	}

	// $data = brix_replace_endlines_deep( $data );
	$data = base64_encode( json_encode( $data ) );

	return $data;
}

/**
 * Unserialize a template's data.
 *
 * @since 1.0.0
 * @param string $data The serialized template's data.
 * @return array
 */
function brix_unserialize_template( $data ) {
	$data = base64_decode( $data );
	$data = json_decode( $data );

	if ( $data === null || ! is_array( $data ) ) {
		return array();
	}

	$data = stripslashes_deep( $data );

	return $data;
}

/**
 * Return an array of defined builder templates.
 *
 * Each builder template has the following structure:
 * array(
 *		'id'          => 'default1',
 *		'type'        => 'default',
 *		'label'       => __( '', 'brix' ),
 *		'description' => '',
 *		'data'		  => array(),
 *		'group'		  => '',
 *		'post_type'   => '',
 *		'section'	  => false,
 *		'thumb'		  => '',
 *		'sticky'	  => false
 *	)
 *
 * @since 1.0.0
 * @return array
 */
function brix_get_templates() {
	$templates = array();
	$brix_templates = get_option( 'brix_templates' );

	if ( ! empty( $brix_templates ) ) {
		foreach ( $brix_templates as $template ) {
			$templates[] = $template;
		}
	}

	return apply_filters( 'brix_get_templates', $templates );
}

/**
 * Return an array of defined sticky builder templates.
 *
 * @since 1.0.0
 * @param boolean $with_thumb Set to true to retrieve only templates that have a preview image.
 * @return array
 */
function brix_get_sticky_templates( $with_thumb = false ) {
	$templates = brix_get_templates();
	$sticky_templates = array();

	foreach ( $templates as $template ) {
		if ( isset( $template['sticky'] ) && $template['sticky'] ) {
			if ( $with_thumb && empty( $template['thumb'] ) ) {
				continue;
			}

			$sticky_templates[] = $template;
		}
	}

	return $sticky_templates;
}

/**
 * Check if there are sticky templates defined.
 *
 * @since 1.0.0
 * @param boolean $with_thumb Set to true to retrieve only templates that have a preview image.
 * @return array
 */
function brix_has_sticky_templates( $with_thumb = false ) {
	$sticky_templates = brix_get_sticky_templates( $with_thumb );

	return ! empty( $sticky_templates );
}

/**
 * Return a builder template by its id.
 *
 * @since 1.0.0
 * @param string $id The builder template id.
 * @return string
 */
function brix_get_template( $id ) {
	$templates = brix_get_templates();
	$find = brix_array_find( $templates, 'id:' . $id );

	if ( $find !== null ) {
		$find['data'] = brix_unserialize_template( $find['data'] );

		return $find;
	}

	return false;
}

/**
 * Check if the builder has a set of defined templates.
 *
 * @since 1.0.0
 * @return boolean
 */
function brix_has_templates() {
	return count( brix_get_templates() ) > 0;
}

/**
 * Check if the builder has a set of user-defined templates.
 *
 * @since 1.1.1
 * @return boolean
 */
function brix_has_user_templates() {
	$templates = brix_get_templates();

	foreach ( $templates as $template ) {
		if ( $template['type'] == 'user' ) {
			return true;
		}
	}

	return false;
}

/**
 * Display a list of templates saved by the user.
 *
 * @since 1.0.2
 * @param array $templates A list of templates.
 * @param string $title The title of the list.
 * @param string $type The type of the templates.
 */
function brix_display_user_templates_list( $templates, $title, $type ) {
	$nonce = wp_create_nonce( 'brix_nonce' );
	$subtitle = '';

	if ( $type == 'pages' ) {
		$subtitle = __( 'Page templates are complete layouts composed by multiple sections.', 'brix' );
	}
	elseif ( $type == 'sections' ) {
		$subtitle = __( 'Section templates are composed by rows and columns.', 'brix' );
	}

	$wrapper_class = 'brix-active';

	printf( '<div class="brix-templates-inner-wrapper %s" data-type="%s">',
		esc_attr( $wrapper_class ),
		esc_attr( $type )
	);
		printf( '<h3 class="brix-templates-title">%s</h3>', esc_html( $title ) );

		if ( ! empty( $subtitle ) ) {
			printf( '<p class="brix-templates-subtitle">%s</p>', esc_html( $subtitle ) );
		}

		echo '<ul class="brix-user-template">';
			foreach ( $templates as $template ) {
				$label = $template['label'];

				if ( isset( $template['index'] ) && $template['index'] ) {
					$label .= sprintf( " (%s)", $template['index'] );
				}

				printf( '<li class="brix-template"><div class="brix-template-wrapper">'	);

					echo '<span class="brix-user-template-label">' . esc_html( $label ) . '</span class="brix-user-template-label">';
					printf( '<span class="brix-use-builder-template" data-sticky="%s" data-id="%s" data-nonce="%s">%s</span>',
						esc_attr( isset( $template['sticky'] ) ? (int) $template['sticky'] : 0 ),
						esc_attr( $template['id'] ),
						esc_attr( $nonce ),
						esc_html__( 'Use this template', 'brix' )
					);

					echo '<div class="brix-user-template-toolbar">';
						do_action( 'brix_user_template_toolbar', $template );

						echo '<span class="brix-remove-builder-template"></span>';
					echo '</div>';

				echo '</div></li>';
			}
		echo '</ul>';
	echo '</div>';
}

/**
 * Display a list of default templates.
 *
 * @since 1.0.0
 * @param array $templates A list of templates.
 * @param string $title The title of the list.
 * @param string $type The type of the templates.
 */
function brix_display_templates_list( $templates, $title, $type ) {
	$nonce = wp_create_nonce( 'brix_nonce' );
	$subtitle = '';

	$wrapper_class = '';

	$wrapper_class = 'brix-active';

	if ( $type == 'pages' ) {
		$subtitle = __( 'Page templates are complete layouts composed by multiple sections.', 'brix' );
	}
	elseif ( $type == 'sections' ) {
		$subtitle = __( 'Section templates are composed by rows and columns.', 'brix' );
	}

	printf( '<div class="brix-templates-inner-wrapper %s">', esc_attr( $wrapper_class ) );
		printf( '<h3 class="brix-templates-title">%s</h3>', esc_html( $title ) );

		if ( ! empty( $subtitle ) ) {
			printf( '<p class="brix-templates-subtitle">%s</p>', esc_html( $subtitle ) );
		}

		echo '<ul class="brix-default-template">';

		foreach ( $templates as $template ) {
			$label = $template['label'];

			if ( isset( $template['index'] ) && $template['index'] ) {
				$label .= sprintf( " (%s)", $template['index'] );
			}

			printf( '<li class="%s">',
				esc_attr( 'brix-template brix-template-type-' . $type )
			);

				echo '<div class="brix-template-wrapper">';

					echo '<div class="brix-template-image-wrapper" style="background-image: url(' . $template['thumb'] . ')">';
						printf( '<span class="brix-template-image-overlay"><span class="brix-template-load-btn brix-use-builder-template" data-sticky="%s" data-id="%s" data-nonce="%s">%s</span></span>',
							esc_attr( isset( $template['sticky'] ) ? (int) $template['sticky'] : 0 ),
							esc_attr( $template['id'] ),
							esc_attr( $nonce ),
							esc_html( __( 'Use this template', 'brix' ) )
						);
					echo '</div>';

					echo '<div class="brix-template-label-wrapper">';
						echo '<p>' . esc_html( $label ) . '</p>';

						// if ( isset( $template['description'] ) && ! empty( $template['description'] ) ) {
						// 	echo '<p class="brix-template-label-description">' . esc_html( $template['description'] ) . '</p>';
						// }
					echo '</div>';

				echo '</div>';

			echo '</li>';
		}

		echo '</ul>';
	echo '</div>';
}

/**
 * Load the contents of the builder template selection modal.
 *
 * @since 1.0.0
 */
function brix_load_template_modal_load() {
	$templates = brix_get_templates();
	$nonce = wp_create_nonce( 'brix_nonce' );
	$templates_wrapper_classes = 'brix-templates-wrapper';

	$modal_templates = array(
		'default' => array(),
		'sections' => array(),
		'user' => array()
	);

	foreach ( $templates as $template ) {
		$modal_templates[$template['type']][] = $template;
	}

	$has_default_templates = ! empty( $modal_templates['default'] ) || ! empty( $modal_templates['sections'] );

	echo '<div class="brix-modal-header">';
		echo '<h1>' . esc_html( __( 'Templates manager', 'brix' ) ) . '</h1>';
		echo '<iframe id="brix-template-export-frame" style="display:none;"></iframe>';
	echo '</div>';

	echo '<div class="brix-templates-toolbar">';

		echo '<div class="brix-templates-nav">';
			$user_templates_class = '';

			if ( $has_default_templates ) {
				echo '<a class="brix-template-nav-item brix-active" href="#" data-nav="default">' . esc_html( __( 'Built-in templates', 'brix' ) ) . '</a>';
			}
			else {
				$user_templates_class = 'brix-active';
			}

			printf( '<a class="brix-template-nav-item %s" href="#" data-nav="user">%s</a>',
				esc_attr( $user_templates_class ),
				esc_html( __( 'My templates', 'brix' ) )
			);

		echo '</div>';

		echo '<div class="brix-templates-actions-wrapper">';
			do_action( 'brix_templates_actions' );
		echo '</div>';

	echo '</div>';

	if ( $has_default_templates ) {
		echo '<div data-nav="default" class="' . esc_attr( $templates_wrapper_classes ) . ' brix-active">';

			if ( ! empty( $modal_templates['default'] ) && ! empty( $modal_templates['sections'] ) ) {
				echo '<div class="brix-templates-type-switch">';
					echo '<a href="#" class="brix-active">' . esc_html__( 'All', 'brix' ) . '</a>';
					echo '<a href="#">' . esc_html__( 'Page templates', 'brix' ) . '</a>';
					echo '<a href="#">' . esc_html__( 'Sections', 'brix' ) . '</a>';
				echo '</div>';
			}

			if ( ! empty( $modal_templates['default'] ) ) {
				brix_display_templates_list(
					$modal_templates['default'],
					__( 'Page templates', 'brix' ),
					'pages'
				);
			}

			if ( ! empty( $modal_templates['sections'] ) ) {
				brix_display_templates_list(
					$modal_templates['sections'],
					__( 'Sections', 'brix' ),
					'sections'
				);
			}
		echo '</div>';
	}

	$brix_user_template_state = '';

	if ( empty( $modal_templates['user'] ) ) {
		$brix_user_template_state = 'brix-user-template-empty';
	}

	$templates_wrapper_classes .= ' ' . $brix_user_template_state;

	if ( ! $has_default_templates ) {
		$templates_wrapper_classes .= ' brix-active';
	}

	echo '<div data-nav="user" class="' . esc_attr( $templates_wrapper_classes ) . '">';
		printf( '<div id="brix-drop-wrapper-notice" data-type=""></div>' );

		echo '<div class="brix-user-template-drop-wrapper">';
			brix_create_templates_drop_area();
		echo '</div>';

		echo '<div class="brix-user-template-content">';
			brix_display_user_templates_list_content();
		echo '</div>';
	echo '</div>';

	die();
}

add_action( 'wp_ajax_brix_load_template_modal_load', 'brix_load_template_modal_load' );

/**
 * Display the content of the user templates tab.
 *
 * @since 1.0.2
 */
function brix_display_user_templates_list_content() {
	$templates = brix_get_templates();
	$modal_templates = array(
		'default' => array(),
		'sections' => array(),
		'user' => array()
	);

	foreach ( $templates as $template ) {
		$modal_templates[$template['type']][] = $template;
	}

	$user_page_templates = array();
	$user_section_templates = array();

	foreach ( $modal_templates['user'] as $user_template ) {
		if ( $user_template['section'] ) {
			$user_section_templates[] = $user_template;
		}
		else {
			$user_page_templates[] = $user_template;
		}
	}

	$content_inner_class = '';

	if ( empty( $user_section_templates ) ) {
		$content_inner_class .= ' brix-user-template-sections-empty';
	}

	if ( empty( $user_page_templates ) ) {
		$content_inner_class .= ' brix-user-template-pages-empty';
	}

	printf( '<div class="brix-user-template-content-inner %s">',
		esc_attr( $content_inner_class )
	);

		echo '<span class="brix-user-template-empty-message">';
			echo wp_kses_post( __( "<strong>You haven't saved any template yet!</strong><br>Start saving your first template by clicking on the 'Save template' link in the builder 'Layout templates' section.", 'brix' ) );
		echo '</span>';

		echo '<div class="brix-templates-type-switch">';
			echo '<a href="#" class="brix-active">' . esc_html__( 'All', 'brix' ) . '</a>';
			echo '<a href="#" data-type="pages">' . esc_html__( 'Page templates', 'brix' ) . '</a>';
			echo '<a href="#" data-type="sections">' . esc_html__( 'Sections', 'brix' ) . '</a>';
		echo '</div>';

		brix_display_user_templates_list(
			$user_page_templates,
			__( 'Page templates', 'brix' ),
			'pages'
		);

		brix_display_user_templates_list(
			$user_section_templates,
			__( 'Sections', 'brix' ),
			'sections'
		);

	echo '</div>';
}

/**
 * AJAX call to display the content of the user templates tab.
 *
 * @since 1.0.2
 */
function brix_display_user_templates_list_content_ajax() {
	brix_display_user_templates_list_content();

	die();
}

add_action( 'wp_ajax_brix_display_user_templates_list_content_ajax', 'brix_display_user_templates_list_content_ajax' );

/**
 * Delete a user-defined builder template.
 *
 * @since 1.0.0
 */
function brix_delete_template() {
	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_nonce' );

	if ( ! $is_valid_nonce ) {
		die();
	}

	if ( ! isset( $_POST['template_id'] ) || empty( $_POST['template_id'] ) ) {
		die();
	}

	$brix_templates = brix_get_templates();
	$template_id = sanitize_text_field( $_POST['template_id'] );

	foreach ( $brix_templates as $index => $template ) {
		if ( $template['id'] == $template_id ) {
			unset( $brix_templates[$index] );
		}
	}

	brix_save_templates( $brix_templates );

	die( '1' );
}

add_action( 'wp_ajax_brix_delete_template', 'brix_delete_template' );

/**
 * Save a user-defined builder template.
 *
 * @since 1.0.0
 */
function brix_save_template() {
	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_nonce' );

	if ( ! $is_valid_nonce ) {
		die();
	}

	$is_section = isset( $_POST['section'] ) && $_POST['section'] ? true : false;
	$brix_templates = brix_get_templates();

	if ( empty( $brix_templates ) ) {
		$brix_templates = array();
	}

	$id = 'user_template_' . count( $brix_templates ) . '_' . time();
	$template_name = sanitize_text_field( $_POST['template_name'] );
	$data = $_POST['data'];

	$templates_count = 0;

	foreach ( $brix_templates as $tpl ) {
		if ( $tpl['label'] === $template_name ) {
			$templates_count++;
		}
	}

	$data = brix_serialize_template( $data, true );

	$template = array(
		'id'        => $id,
		'type'      => 'user',
		'label'     => $template_name,
		'index'		=> $templates_count,
		'post_type' => '',
		'data'      => $data,
		'section'	=> intval( $is_section )
	);

	$brix_templates[] = $template;

	brix_save_templates( $brix_templates );

	die( $id );
}

add_action( 'wp_ajax_brix_save_template', 'brix_save_template' );

/**
 * Save a list of templates.
 *
 * @since 1.0.0
 * @param array $templates An array of builder templates.
 */
function brix_save_templates( $templates ) {
	foreach ( $templates as $i => $template ) {
		if ( $template['type'] !== 'user' ) {
			unset( $templates[$i] );
		}
	}

	update_option( 'brix_templates', $templates );
}

/**
 * Load a blank template pre-filling it with a text block with the provided
 * content.
 *
 * @since 1.0.0
 */
function brix_load_blank_template() {
	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_load_blank_template' );

	/* Temporary page content. */
	$content = isset( $_POST['content'] ) ? $_POST['content'] : '';
	$content = stripslashes( $content );

	if ( ! $is_valid_nonce || $content === '' ) {
		die();
	}

	$blank_blocks = array(
		array(
			'data' => array(
				'content' => $content,
				'_type' => 'text'
			)
		)
	);

	$blank_section_obj = array(
		'data' => array(
			'layout' => array(
				array(
					'size' => '1/1',
					'type' => 'standard',
					'rows' => array(
						array(
							'data' => new stdClass(),
							'columns' => array(
								array(
									'size' => '1/1',
									'blocks' => $blank_blocks
								)
							)
						)
					)
				)
			)
		),
	);

	brix_template( BRIX_TEMPLATES_FOLDER . 'admin/section', array(
		'data' => json_decode( json_encode( $blank_section_obj ) )
	) );

	die();
}

add_action( 'wp_ajax_brix_load_blank_template', 'brix_load_blank_template' );

/**
 * Create the drop area to upload the templates.
 *
 * @since 1.0.0
 */
function brix_create_templates_drop_area() {
	printf( '<form action="%s" method="post" enctype="multipart/form-data" id="brix-templates-dropzone">', admin_url( '/' ) );
		echo '<span class="brix-user-template-drop-wrapper-close"></span>';
		echo '<span class="brix-drop-message">' . __( 'Drop files here or click to upload', 'brix' ) . '</span>';
		wp_nonce_field( 'brix_import_templates' );
	echo '</form>';
}

/**
 * Export the templates.
 *
 * @since 1.0.0
 * @param string $name The name of the template to export.
 */
function brix_export_templates() {
	if ( ! isset( $_GET['brix_export_templates'] ) ) {
		return;
	}

	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'brix_export_templates' );

	if ( ! $is_valid_nonce ) {
		return;
	}

	$name = '';

	if ( isset( $_GET['name'] ) && ! empty( $_GET['name'] ) ) {
		$name = sanitize_text_field( $_GET['name'] );
	}

	$brix_templates = brix_get_templates();
	$user_templates = array();
	$is_single = $name && ! empty( $name );

	foreach ( $brix_templates as $template ) {
		if ( $template['type'] !== 'user' ) {
			continue;
		}

		if ( $is_single ) {
			if ( $name == $template['id'] ) {
				$user_templates[] = $template;
			}
		}
		else {
			$user_templates[] = $template;
		}
	}

	if ( empty( $user_templates ) ) {
		return;
	}

	$exported_data = base64_encode( serialize( $user_templates ) );
	$filename = __( 'All templates', 'brix' );

	if ( $is_single ) {
		$filename = $user_templates[0]['label'];
	}

	$filename .= ' - Brix.' . date( 'Y-m-d' ) . '.txt';

	header( 'Content-disposition: attachment; filename=' . $filename );
	header( 'Content-type: text/plain' );

	ob_start();
	echo $exported_data;
	ob_end_flush();

	die();
}

add_action( 'admin_init', 'brix_export_templates' );

/**
 * Import a set of uploaded templates.
 *
 * @since 1.0.0
 */
function brix_import_templates() {
	$key = 'file';
	$return = array();

	if ( ! empty( $_POST ) ) {
		$is_valid_nonce = isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'brix_import_templates' );

		if ( ! $is_valid_nonce ) {
			return;
		}

		$can_upload = ( ! empty( $_FILES ) ) && isset( $_FILES[ $key ] );

		if ( ! $can_upload ) {
			$return['type'] = 'error';
			$return['message'] = __( 'Please specify a file to upload.', 'brix' );

			die( json_encode( $return ) );
		}
	}
	else {
		return;
	}

	$file_data = $_FILES[ $key ];
	$num = count( $file_data['name'] );

	$imported_templates_count = 0;
	$skipped_templates_count = 0;
	$error_templates_count = 0;
	$imported_data = null;

	for ( $i=0; $i<$num; $i++ ) {
		if ( ! isset( $file_data['tmp_name'] ) || ! isset( $file_data['tmp_name'][$i] ) ) {
			continue;
		}

		$brix_templates = brix_get_templates();
		$tmp_name = $file_data['tmp_name'][$i];

		$imported_data = implode( '', file( $tmp_name ) );
		$imported_data = base64_decode( $imported_data );
		$imported_data = @unserialize( $imported_data );

		if ( $imported_data == null || ! is_array( $imported_data ) ) {
			$error_templates_count++;

			continue;
		}

		foreach ( $imported_data as $k => $template ) {
			$template_data = $template['data'];

			foreach ( $brix_templates as $user_template ) {
				if ( $user_template['data'] == $template_data ) {
					$skipped_templates_count++;
					unset( $imported_data[$k] );
				}
			}
		}

		if ( ! empty( $imported_data ) ) {
			foreach ( $imported_data as $single_template ) {
				$brix_templates[] = $single_template;
			}

			$imported_templates_count += count( $imported_data );

			brix_save_templates( $brix_templates );
		}
	}

	if ( $imported_templates_count > 0 ) {
		$return['type'] = 'success';

		if ( $skipped_templates_count > 0 ) {
			if ( $error_templates_count > 0 ) {
				$return['message'] = sprintf( __( '%s templates imported, %s templates skipped and %s errors.', 'brix' ),
					esc_html( $imported_templates_count ),
					esc_html( $skipped_templates_count ),
					esc_html( $error_templates_count )
				);
			}
			else {
				$return['message'] = sprintf( __( '%s templates imported and %s templates skipped.', 'brix' ),
					esc_html( $imported_templates_count ),
					esc_html( $skipped_templates_count )
				);
			}
		}
		else {
			$return['message'] = sprintf( __( '%s templates imported.', 'brix' ),
				esc_html( $imported_templates_count )
			);
		}
	}
	else {
		if ( $skipped_templates_count > 0 ) {
			$return['type'] = 'warning';

			if ( $error_templates_count > 0 ) {
				$return['message'] = sprintf( __( '%s templates skipped and %s errors.', 'brix' ),
					esc_html( $skipped_templates_count ),
					esc_html( $error_templates_count )
				);
			}
			else {
				$return['message'] = sprintf( __( '%s templates skipped.', 'brix' ),
					esc_html( $skipped_templates_count )
				);
			}
		}
		else {
			$return['type'] = 'error';
			$return['message'] = __( 'No templates imported: verify the uploaded file.', 'brix' );
		}
	}

	die( json_encode( $return ) );
}

add_action( 'admin_init', 'brix_import_templates' );

/**
 * Add a list of default dummy templates.
 *
 * @since 1.1.2
 * @param array $templates An array of templates data.
 * @return array
 */
function brix_add_default_templates( $templates ) {
	$default_templates = brix_get_default_templates();

	$templates = array_merge( $templates, $default_templates );

	return $templates;
}

add_filter( 'brix_get_templates', 'brix_add_default_templates' );

/**
 * Get a list of default dummy templates.
 *
 * @since 1.1.3
 * @param array $templates An array of templates data.
 * @return array
 */
function brix_get_default_templates() {
	$default_templates = apply_filters( 'brix_get_default_templates', array() );

	return $default_templates;
}