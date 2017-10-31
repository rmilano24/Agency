<?php
	$classes = array(
		'brix-section',
	);

	$attrs = array();

	if ( isset( $data->data->data->_hidden ) && $data->data->data->_hidden == '1' ) {
		return;
	}

	if ( isset( $data->data->data->class ) ) {
		$classes = array_merge( $classes, (array) $data->data->data->class );
	}

	$classes = apply_filters( 'brix_section_classes', $classes, $data->data );

	if ( isset( $data->data->data->attrs ) ) {
		$attrs = array_merge( $attrs, (array) $data->data->data->attrs );
	}

	$attrs = apply_filters( 'brix_section_attrs', $attrs, $data->data );

	if ( ! empty( $data->data->data->id ) ) {
		$attrs[] = 'id=' . esc_attr( $data->data->data->id );
	}

	$section_tag = 'div';

	if ( isset( $data->data->data->section ) && ! empty( $data->data->data->section ) ) {
		$section_tag = 'section';
	}
?>

<<?php echo esc_html( $section_tag ); ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"  <?php echo esc_attr( implode( ' ', $attrs ) ); ?>>
	<?php
		if ( isset( $data->data->data->background ) ) {
			echo brix_background( $data->data->data, 'section' );
		}

		do_action( 'brix_section_start', $data );
	?>
	<div class="brix-section-inner-wrapper">

		<?php foreach ( $data->data->layout as $subsection ) : ?>

			<?php
				printf( '<div class="brix-subsection brix-subsection-type-%s brix-subsection-%s">',
					esc_attr( $subsection->type ),
					esc_attr( str_replace( '/', '-', $subsection->size ) )
				);

					echo '<div class="brix-subsection-spacing-wrapper">';

						if ( isset( $subsection->rows ) && is_array( $subsection->rows ) && ! empty( $subsection->rows ) ) {
							foreach ( $subsection->rows as $row ) {
								brix_template( BRIX_FOLDER . '/templates/frontend/row', array(
									'data' => $row
								) );
							}

						}
					echo '</div>';


				echo '</div>';
			?>

		<?php endforeach; ?>

	</div>
</<?php echo esc_html( $section_tag ); ?>>