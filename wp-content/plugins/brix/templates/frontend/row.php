<?php if ( isset( $data->columns ) && is_array( $data->columns ) && ! empty( $data->columns ) ) : ?>
	<?php
		$row_data = isset( $data->data ) ? $data->data : array();

		$classes = array(
			'brix-section-row'
		);

		$data_attr = array();

		$classes = apply_filters( 'brix_section_row_classes', $classes, $row_data );
		$data_attr = apply_filters( 'brix_section_row_data_attrs', $data_attr, $row_data );
	?>

	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo esc_attr( implode( ' ', $data_attr ) ); ?>>
		<div class="brix-section-row-inner-wrapper">
			<?php
				foreach ( $data->columns as $index => $column ) {
					brix_template( BRIX_FOLDER . '/templates/frontend/column', array(
						'data' => $column,
					) );
				}
			?>

		</div>
	</div>
<?php endif; ?>