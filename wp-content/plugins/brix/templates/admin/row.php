<?php
	$row_layout_class = false;

	$row_classes = array(
		'brix-section-row',
		'brix-draggable'
	);

	if ( isset( $data->columns ) ) {
		$row_layout_class = 'col_';

		foreach ( $data->columns as $c ) {
			$row_layout_class .= $c->size;

			$row_layout_class .= '_';
		}

		$row_layout_class = trim( $row_layout_class, '_' );
	}
	else {
		$row_classes[] = 'brix-row-layout-empty';
	}

	$row_data = isset( $data->data ) ? $data->data : array(
		'_fluid' => 0
	);
?>

<div class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>" data-data="<?php echo htmlentities( json_encode( $row_data ) ); ?>">
	<div class="brix-section-row-layout brix-section-row-layout-wrapper" data-panel="layout-change">

		<div class="brix-section-row-layout-change-wrapper brix-section-row-layout-choices">
			<div class="brix-section-row-layout-header">
				<div class="brix-section-row-layout-header-controls">
					<?php do_action( 'brix_section_row_layout_change_controls' ); ?>
				</div>

				<span class="brix-section-row-layout-label"><span class="brix-section-row-editing-label"><?php echo esc_html( __( 'Change the row layout', 'brix' ) ); ?></span> <span class="brix-section-row-layout-more">(<?php echo esc_html( __( 'Show all', 'brix' ) ); ?>)</span></span>

				<button type="button" class="brix-row-layout-close"><span class="screen-reader-text"><?php echo esc_html( __( 'OK', 'brix' ) ); ?></span></button>
			</div>

			<?php
				brix_section_row_layout_choices();
			?>
		</div>


		<div class="brix-section-row-layout-responsive-wrapper">
			<div class="brix-section-row-layout-header">
				<div class="brix-section-row-layout-header-controls">
					<button type="button" class="brix-section-row-edit-vertical-alignment brix-tooltip" title="<?php echo esc_html( __( 'Vertical alignment', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Vertical alignment', 'brix' ) ); ?></span></button>
					<button type="button" class="brix-section-row-back-to-layout brix-tooltip" title="<?php echo esc_html( __( 'Back to row layout', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Back to row layout', 'brix' ) ); ?></span></button>
				</div>

				<span class="brix-section-row-layout-label"><span class="brix-section-row-editing-label"><?php echo esc_html( __( 'Responsive', 'brix' ) ); ?></span></span>

				<button type="button" class="brix-row-layout-close"><span class="screen-reader-text"><?php echo esc_html( __( 'OK', 'brix' ) ); ?></span></button>
			</div>

			<div class="brix-section-row-layout-responsive-columns-wrapper"></div>
		</div>


		<div class="brix-section-row-layout-vertical-alignment-wrapper">
			<div class="brix-section-row-layout-header">
				<div class="brix-section-row-layout-header-controls">
					<?php do_action( 'brix_section_row_layout_vertical_alignment-controls' ); ?>
				</div>

				<span class="brix-section-row-layout-label"><span class="brix-section-row-editing-label"><?php echo esc_html( __( 'Vertical alignment', 'brix' ) ); ?></span></span>

				<button type="button" class="brix-row-layout-close"><span class="screen-reader-text"><?php echo esc_html( __( 'Ok', 'brix' ) ); ?></span></button>
			</div>

			<div class="brix-section-row-layout-vertical-alignment-columns-wrapper"></div>
		</div>
	</div>



	<div class="brix-section-row-bar">
		<div class="brix-section-row-bar-inner-wrapper">

			<div class="brix-controls">
				<span class="brix-edit-row brix-tooltip" data-title="<?php echo esc_attr( __( 'Edit row', 'brix' ) ); ?>"></span>
				<span class="brix-sortable-handle brix-row-sortable-handle"></span>
				<span class="brix-clone-row brix-tooltip" data-title="<?php echo esc_attr( __( 'Duplicate row', 'brix' ) ); ?>"></span>
				<span class="brix-remove brix-row-remove brix-remove-row"></span>
			</div>
		</div>
	</div>

	<div class="brix-section-row-inner-wrapper">

		<?php
			if ( isset( $data->columns ) && is_array( $data->columns ) && ! empty( $data->columns ) ) {
				foreach ( $data->columns as $column ) {
					brix_template( BRIX_FOLDER . '/templates/admin/column', array(
						'data' => $column
					) );
				}
			}
			else {
				?>
				<div class="brix-section-row-layout brix-section-row-layout-pre-selector">
					<span class="brix-remove brix-row-remove brix-remove-row"></span>

					<?php brix_section_row_layout_choices(); ?>

					<div class="brix-section-row-layout-pre-selector-inner">
						<span><?php echo esc_html( __( 'Or', 'brix' ) ); ?></span>
						<?php
							$nonce = wp_create_nonce( 'brix_nonce' );

							printf( '<a href="#" class="brix-load-builder-template" data-nonce="%s">%s</a>',
								esc_attr( $nonce ),
								esc_html( __( 'Use a template', 'brix' ) )
							);
						?>
					</div>
				</div>
				<?php
			}
		?>

	</div>
</div>
