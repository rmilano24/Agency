<?php
	$section_data = '';
	$section_classes = array(
		'brix-section',
		// 'brix-added',
		'brix-draggable'
	);

	if ( isset( $data->data->data ) ) {
		$section_data = $data->data->data;

		if ( brix_has_appearance( $section_data ) ) {
			$section_classes[] = 'brix-has-appearance';
		}

		if ( isset( $section_data->background_image ) && isset( $section_data->background_image->color ) && isset( $section_data->background_image->color->color ) && ! empty( $section_data->background_image->color->color ) ) {
			if ( ! brix_color_is_bright( $section_data->background_image->color->color ) ) {
				$section_classes[] = 'brix-section-bright-text';
			}
		}

		if ( isset( $section_data->_hidden ) && $section_data->_hidden == '1' ) {
			$section_classes[] = 'brix-hidden';
		}
	}

?>

<div class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" data-data="<?php echo htmlentities( json_encode( $section_data ) ); ?>">
	<div class="brix-section-extra-wrapper">
		<?php
			$section_layout_class = false;

			if ( isset( $data->data->layout ) ) {
				$section_layout_class = 'sect_';

				foreach ( $data->data->layout as $s ) {
					$section_layout_class .= $s->size;

					if ( $s->type === 'special' ) {
						$section_layout_class .= 's';
					}

					$section_layout_class .= '_';
				}

				$section_layout_class = trim( $section_layout_class, '_' );
			}
		?>

		<div class="brix-section-controls brix-controls">
			<a data-title="<?php echo esc_attr( __( 'Edit section', 'brix' ) ); ?>" class="brix-edit brix-section-edit brix-tooltip" href="#"><span><?php echo esc_html( __( 'Edit section', 'brix' ) ); ?></span></a>
			<?php
				printf( '<a href="#" data-title="%s" class="brix-section-duplicate brix-tooltip"><span>%s</span></a>',
					esc_html( __( 'Duplicate section', 'brix' ) ),
					esc_html( __( 'Duplicate section', 'brix' ) )
				);

				$nonce = wp_create_nonce( 'brix_nonce' );

				printf( '<a href="#" data-title="%s" class="brix-section-save-template brix-tooltip" data-nonce="%s"><span>%s</span></a>',
					esc_html( __( 'Save section template', 'brix' ) ),
					esc_attr( $nonce ),
					esc_html( __( 'Save section template', 'brix' ) )
				);

				printf( '<a href="#" data-title="%s" class="brix-section-replace-with-template brix-tooltip" data-nonce="%s"><span>%s</span></a>',
					esc_html( __( 'Replace section with template', 'brix' ) ),
					esc_attr( $nonce ),
					esc_html( __( 'Replace section with template', 'brix' ) )
				);

				$section_hidden_label = BrixBuilder::instance()->i18n_strings( 'section_hidden' );

				printf( '<span class="brix-section-hidden-label">%s</span>',
					esc_html( $section_hidden_label )
				);
			?>

			<?php
				$section_background_class = '';
				$section_background_color = '';
				$section_background_image = '';
				$section_background_title = '';

				if ( ! empty( $section_data ) ) {
					if ( isset( $section_data->background ) && ! empty( $section_data->background ) ) {
						if ( isset( $section_data->background ) && $section_data->background == 'image' && isset( $section_data->background_image ) && isset( $section_data->background_image->desktop ) && isset( $section_data->background_image->desktop->image ) && isset( $section_data->background_image->desktop->image->desktop["1"]->id ) && ! empty( $section_data->background_image->desktop->image->desktop["1"]->id ) ) {
							$section_background_class .= ' brix-background-image';
							$section_background_title = BrixBuilder::instance()->i18n_strings( 'background_image' );
						}
						else if ( isset( $section_data->background ) && $section_data->background == 'video' && isset( $section_data->background_video ) && isset( $section_data->background_video->url ) && ! empty( $section_data->background_video->url ) ) {
							$section_background_class .= ' brix-background-video';
							$section_background_title = BrixBuilder::instance()->i18n_strings( 'background_video' );
						}
						else if ( isset( $section_data->background_image ) && isset( $section_data->background_image->desktop ) && $section_data->background_image->desktop->color_type == "gradient" ) {
							$section_background_class .= ' brix-background-gradient';
							$section_background_title = BrixBuilder::instance()->i18n_strings( 'background_gradient' );
						}
						else if ( isset( $section_data->background_image ) && isset( $section_data->background_image->desktop ) && isset( $section_data->background_image->desktop->color ) && isset( $section_data->background_image->desktop->color->color ) && ! empty( $section_data->background_image->desktop->color->color ) ) {
							$section_background_color = $section_data->background_image->desktop->color->color;
							$section_background_class .= ' brix-background-color';
							$section_background_title = BrixBuilder::instance()->i18n_strings( 'background_color' );
						}
					}
				}

			?>

			<span class="brix-remove brix-section-remove"></span>

			<?php
				$section_width_label = BrixBuilder::instance()->i18n_strings( 'section_width_boxed' );

				if ( isset( $data->data->data->section_width ) ) {
					$section_width_label = BrixBuilder::instance()->i18n_strings( 'section_width_' . $data->data->data->section_width );
				}

				printf( '<span class="brix-section-width-label brix-tooltip" data-title="%s">%s</span>',
					esc_attr( __( 'Section width', 'brix' ) ),
					esc_html( $section_width_label )
				);
			?>
		</div>

		<div class="brix-section-inner-wrapper">
			<?php
				printf( '<span class="brix-background brix-section-background %s" data-title="%s"><span style="background-color: %s"></span></span>',
					esc_attr( $section_background_class ),
					esc_attr( $section_background_title ),
					esc_attr( $section_background_color )
				);
			?>
			<button type="button" class="brix-section-move-up brix-tooltip" data-title="<?php esc_attr_e( 'Move up', 'brix' ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Move up', 'brix' ); ?></span></button>
			<button type="button" class="brix-section-move-down brix-tooltip" data-title="<?php esc_attr_e( 'Move down', 'brix' ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Move down', 'brix' ); ?></span></button>
			<span class="brix-sortable-handle brix-section-sortable-handle"></span>

			<div class="brix-section-inner-content-wrapper">

				<?php
					if ( isset( $data->data ) && isset( $data->data->layout ) ) {
						foreach ( $data->data->layout as $layout ) {
							$subsection_class = '';

							if ( ! isset( $layout->rows ) || ! is_array( $layout->rows ) || empty( $layout->rows ) ) {
								$subsection_class = 'brix-rows-layout-empty';
							}
							else {
								$last_row = $layout->rows[count($layout->rows) - 1];

								if ( ! isset( $last_row->columns ) || empty( $last_row->columns ) ) {
									$subsection_class = 'brix-rows-layout-empty';
								}
							}

							printf( '<div data-type="%s" data-size="%s" class="brix-subsection brix-subsection-type-%s brix-subsection-%s %s">',
								esc_attr( $layout->type ),
								esc_attr( $layout->size ),
								esc_attr( $layout->type ),
								esc_attr( str_replace( '/', '-', $layout->size ) ),
								esc_attr( $subsection_class )
							);

							echo '<div class="brix-subsection-rows-wrapper">';
								if ( isset( $layout->rows ) && is_array( $layout->rows ) && ! empty( $layout->rows ) ) {
									foreach ( $layout->rows as $row ) {
										brix_template( BRIX_FOLDER . '/templates/admin/row', array(
											'data' => $row
										) );
									}
								}
								else {
									brix_template( BRIX_FOLDER . '/templates/admin/row', array(
										'data' => array()
									) );
								}
							echo '</div>';

							if ( $layout->type == 'standard' ) {
								echo '<div class="brix-section-row-empty">';
									printf( '<a href="#" data-title="%s" class="brix-add-new-row brix-tooltip"><span>%s</span></a>',
										esc_attr( __( 'Add row', 'brix' ) ),
										esc_html( __( 'Add row', 'brix' ) )
									);
								echo '</div>';
							}

							echo '</div>';
						}
					}
				?>

			</div>

		</div>

	</div>

	<?php
		printf( '<a href="#" data-title="%s" class="brix-add-new-section brix-add-new-section-inside" data-after><span class="brix-add-new-section-detail"></span><span class="brix-add-new-section-label">%s</span></a>',
			esc_attr( __( 'Add section here', 'brix' ) ),
			esc_html( __( 'Add section here', 'brix' ) )
		);
	?>
</div>