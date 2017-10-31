<?php if ( ! defined( 'BRIX_FW' ) ) die( 'Forbidden' );

/**
 * Parse a folder full of icon files.
 *
 * @since 1.1.2
 * @param string $folder The folder path
 * @return array
 */
function brix_parse_icon_set_folder( $set ) {
	$folder = $set['path'];
	$icons = array();
	$files = scandir( $folder );

	foreach ( $files as $file ) {
		if ( ! brix_string_ends_with( $file, '.svg' ) ) {
			continue;
		}

		$name = str_replace( '.svg', '', $file );
		// $name = str_replace( '.svg', '', $file );

		$icons[] = array(
			'name'  => $name,
			'url'   => trailingslashit( $set['url'] ) . $file
		);
	}

	return $icons;
}

/**
 * Return a list of the imported icon fonts to be used with the icon picker
 * field.
 *
 * To add a new icon font, hook into the `brix_get_icon_fonts` filter and append
 * the following array:
 *
 * $fonts[] = array(
 * 	 'name'    => Library name,
 *   'label'   => Library label,
 *   'url'     => URL TO THE ICON FONT CSS FILE,
 *   'prefix'  => '',  // Library CSS prefix, optional
 *   'mapping' => array(
 *   	'fa-envelope-o',
 *    	'fa-heart',
 *     	// other mappings
 * );
 *
 * @since 0.1.0
 * @return array
 */
function brix_get_icon_fonts() {
	$icon_fonts = apply_filters( 'brix_get_icon_fonts', array() );

	/* Remove duplicate icon families. */
	foreach ( $icon_fonts as $index => $icon_font ) {
		foreach ( $icon_fonts as $_i => $_if ) {
			if ( $icon_font['name'] == $_if['name'] && $_i !== $index ) {
				unset( $icon_fonts[$index] );
			}
		}
	}

	foreach ( $icon_fonts as $index => $icon_font ) {
		if ( isset( $icon_font['path'] ) && file_exists( $icon_font['path'] ) ) {
			$icon_fonts[$index]['mapping'] = brix_parse_icon_set_folder( $icon_font );
		}
		else {
			unset( $icon_fonts[$index] );
		}
	}

	return $icon_fonts;
}

/**
 * Return the markup to display an icon.
 *
 * @since 0.4.0
 * @param string $icon The icon name.
 * @param array $attrs The icon attributes.
 * @return string
 */
function brix_get_icon( $icon, $attrs = array() ) {
	if ( empty( $icon ) ) {
		return;
	}

	$icon_fonts = brix_get_icon_fonts();

	$url = '';

	foreach ( $icon_fonts as $index => $icon_font ) {
		foreach ( $icon_font['mapping'] as $file ) {
			if ( $file['name'] === $icon ) {
				$url = $file['url'];
				break;
			}
		}
	}

	if ( ! $url ) {
		return '';
	}

	$icon_classes = array(
		'brix-icon'
	);

	$icon_classes = array_map( 'esc_attr', $icon_classes );

	$attrs = wp_parse_args( $attrs, array(
		'class' => implode( ' ', $icon_classes )
	) );

	if ( is_admin() ) {
		$attrs['src'] = $url;
	}
	else {
		$attrs['data-src'] = $url;
	}

	$attrs_html = '';

	foreach ( $attrs as $attr_key => $attr_value ) {
		$attrs_html .= ' ' . $attr_key . '="' . esc_attr( $attr_value ) . '"';
	}

	if ( is_admin() ) {
		return sprintf( '<img %s>', $attrs_html );
	}
	else {
		return sprintf( '<i %s></i>', $attrs_html );
	}
}

/**
 * Display an icon.
 *
 * @since 0.4.0
 * @param string $icon The icon name.
 * @param array $attrs The icon attributes.
 */
function brix_icon( $icon, $attrs = array() ) {
	echo brix_get_icon( $icon, $attrs );
}

/**
 * Contents for the icon selection modal.
 *
 * @since 0.4.0
 */
function brix_icon_modal_load() {
	if ( ! brix_is_post_nonce_valid( 'brix_icon' ) ) {
		die();
	}

	if ( ! isset( $_POST['data'] ) ) {
		die();
	}

	$data = $_POST['data'];

	$prefix = isset( $data['prefix'] ) ? $data['prefix'] : '';
	$url    = isset( $data['url'] ) ? $data['url'] : '';
	$set    = isset( $data['set'] ) ? $data['set'] : '';
	$icon   = isset( $data['icon'] ) ? $data['icon'] : '';
	$color  = isset( $data['color'] ) ? $data['color'] : '';
	$size   = isset( $data['size'] ) ? $data['size'] : '';
	$config = isset( $data['config'] ) ? $data['config'] : array();
	$is_modal = (bool) ( isset( $config['modal'] ) && $config['modal'] );

	$icon_selected_class = '';

	$icon_fonts = brix_get_icon_fonts();

	$external_wrapper_class = '';

	if ( ! empty( $color ) || ! empty( $size ) ) {
		$external_wrapper_class = 'brix-icon-controls-active';
	}

	if ( $is_modal ) {
		$external_wrapper_class .= ' brix-icon-loaded-in-modal';
	}

	$content = sprintf( '<div class="brix-icon-sets-external-wrapper brix-active %s">', esc_attr( $external_wrapper_class ) );

		if ( $icon ) {
			$icon_selected_class = 'brix-icon-selected';
		}

		$content .= sprintf( '<div class="brix-icon-preview-wrapper %s">', esc_attr( $icon_selected_class ) );

			if ( $icon ) {
				$content .= brix_get_decoration_icon( array(
					'icon' => $icon
				) );
			}
			else {
				$content .= '<div class="brix-empty-icon-message-wrapper">';
					$content .= sprintf( '<p>%s</p>', esc_html__( 'Start to search an icon or choose one from the sets below', 'brix' ) );
				$content .= '</div>';

				$content .= '<div class="brix-icon-wrapper brix-empty-icon">';
					$content .= '<img src="" class="brix-icon">';
				$content .= '</div>';
			}

			$set_name = isset( $icon_fonts[$set] ) ? $icon_fonts[$set]['label'] : $set;

			$preview_data_class = '';

			if ( ! $icon ) {
				$preview_data_class = 'brix-empty-icon';
			}

			$content .= sprintf( '<div class="brix-icon-preview-data %s">', esc_attr( $preview_data_class ) );
				$content .= sprintf( '<p class="brix-icon-font-set">%s</p>', esc_html( $set_name ) );
				$content .= sprintf( '<p class="brix-icon-name">%s</p>', esc_html( $icon ) );
			$content .= '</div>';

			$content .= '<span class="brix-icon-preview-toggle">' . esc_html__( 'Edit', 'brix' ) . '</span>';


			$content .= '<div class="brix-icon-sets-controls-external-wrapper">';

				$content .= '<div class="brix-icon-sets-controls-wrapper">';
					$content .= '<div class="brix-icon-sets-controls-field-wrapper">';
						$content .= sprintf( '<label>%s</label>', esc_html( __( 'Color', 'brix' ) ) );
						$content .= brix_color( 'color', $color, false, false, false );
					$content .= '</div>';

					$content .= '<div class="brix-icon-sets-controls-field-wrapper">';
						$content .= sprintf( '<label>%s</label>', esc_html( __( 'Size', 'brix' ) ) );
						$content .= sprintf( '<input type="text" name="size" value="%s" data-icon-size>', esc_attr( $size ) );
					$content .= '</div>';

					$content .= sprintf( '<input type="hidden" name="url" value="%s" data-icon-url>', esc_attr( $url ) );
					$content .= sprintf( '<input type="hidden" name="prefix" value="%s" data-icon-prefix>', esc_attr( $prefix ) );
					$content .= sprintf( '<input type="hidden" name="set" value="%s" data-icon-set>', esc_attr( $set ) );
					$content .= sprintf( '<input type="hidden" name="icon" value="%s" data-icon-name>', esc_attr( $icon ) );
				$content .= '</div>';

			$content .= '</div>';

		$content .= '</div>';

		$content .= '<div class="brix-icon-search-wrapper">';
			$content .= sprintf( '<input type="text" placeholder="%s" data-icon-search>', esc_attr( _x( 'Search&hellip;', 'icon search', 'brix' ) ) );
		$content .= '</div>';

		$content .= '<div class="brix-icon-sets-inner-wrapper">';
			$content .= '<div class="brix-icon-sets">';

				$aliases = brix_styles_get_icon_aliases();

				foreach ( $icon_fonts as $index => $font ) {
					$set_class = 'brix-icon-set brix-icon-set-' . $font['name'];

					$content .= sprintf( '<div class="%s">', esc_attr( $set_class ) );
						$content .= sprintf( '<h2>%s <span class="brix-icon-set-count">%s</span></h2>',
							esc_html( $font['label'] ),
							esc_html( count( $font['mapping'] ) )
						);

						$content .= '<div class="brix-icon-set-icons">';

							foreach ( $font['mapping'] as $set_icon ) {
								$icon_class = 'brix-icon brix-component';
								// $icon_class = $font['prefix'] . ' ' . $set_icon . ' brix-icon brix-component';

								if ( $font['name'] == $set && $font['prefix'] == $prefix && $set_icon['name'] == $icon ) {
									$icon_class .= ' brix-selected';
								}

								$set_icon_stripped = strstr( $set_icon['name'], '-' );
								$set_icon_stripped = trim( $set_icon_stripped, '-' );
								$icon_aliased = '';

								if ( isset( $aliases[$set_icon_stripped] ) ) {
									$icon_aliased = $aliases[$set_icon_stripped][0];
								}

								$content .= sprintf( '<span class="brix-icon-set-icon"><img data-src="%s" data-prefix="%s" data-set="%s" data-set-name="%s" data-icon-name="%s" data-icon-stripped="%s" data-icon-aliases="%s" class="%s" aria-hidden="true"></span>',
									esc_attr( $set_icon['url'] ),
									esc_attr( $font['prefix'] ),
									esc_attr( $font['name'] ),
									esc_attr( $font['label'] ),
									esc_attr( $set_icon['name'] ),
									esc_attr( $set_icon_stripped ),
									esc_attr( $icon_aliased ),
									esc_attr( $icon_class )
								);
							}

						$content .= '</div>';
					$content .= '</div>';
				}

			$content .= '</div>';
		$content .= '</div>';
	$content .= '</div>';

	$m = new Brix_SimpleModal( 'brix-icon', array( 'title' => __( 'Icon', 'brix' ) ) );
	$m->render( $content );

	die();
}

add_action( 'wp_ajax_brix_icon_modal_load', 'brix_icon_modal_load' );