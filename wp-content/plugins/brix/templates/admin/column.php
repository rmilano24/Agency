<?php

$carousel_label = '';
$size = str_replace( '/', '-', $data->size );

$classes = array(
	'brix-col-' . $size,
	'brix-draggable'
);

if ( ! isset( $data->blocks ) || empty( $data->blocks ) ) {
	$classes[] = 'brix-empty';
}

list( $num, $den ) = explode( '-', $size );
$size = round( ( $num / $den ) * 100, 1 ) . '%';

$appearance_classes = array();
$column_data = '';

if ( isset( $data->data ) ) {
	$column_data = $data->data;

	if ( brix_has_appearance( $column_data ) ) {
		$classes[] = 'brix-has-appearance';
	}

	if ( isset( $column_data->background_image ) && isset( $column_data->background_image->color ) && isset( $column_data->background_image->color->color ) && ! empty( $column_data->background_image->color->color ) ) {
		if ( ! brix_color_is_bright( $column_data->background_image->color->color ) ) {
			$classes[] = 'brix-column-bright-text';
		}
	}

	if ( isset( $data->data->enable_column_carousel ) && ! empty( $data->data->enable_column_carousel ) ) {
		$classes[] = 'brix-is-carousel';
	}
}

?>

<div class="brix-section-column <?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-size="<?php echo esc_attr( $data->size ); ?>" data-data="<?php echo htmlentities( json_encode( $column_data ) ); ?>">

	<a href="#" class="brix-column-merge brix-tooltip" data-title="<?php echo esc_attr( __( 'Merge columns', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Merge columns', 'brix' ) ); ?></span></a>

	<div class="brix-section-column-controls brix-controls">

		<div class="brix-section-column-bar-panel">
			<div class="brix-section-column-bar-panel-inner-wrapper">
				<a href="#" class="brix-column-split brix-tooltip" data-title="<?php echo esc_attr( __( 'Split column', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Split column', 'brix' ) ); ?></span></a>
				<a data-title="<?php echo esc_attr( __( 'Edit column', 'brix' ) ); ?>" class="brix-edit brix-column-edit  brix-tooltip <?php echo esc_attr( implode( ' ', $appearance_classes ) ); ?>" href="#"><span><?php echo esc_html( __( 'Edit column', 'brix' ) ); ?></span></a>
			</div>
		</div>

		<span class="brix-section-column-carousel-mode brix-tooltip" data-title="<?php echo esc_attr( __( 'Carousel mode enabled', 'brix' ) ); ?>"></span>

		<?php
			$column_background_class = '';
			$column_background_color = '';
			$column_background_image = '';
			$column_background_title = '';

			if ( ! empty( $column_data ) ) {
				if ( isset( $column_data->background ) && ! empty( $column_data->background ) ) {
					if ( isset( $column_data->background ) && $column_data->background == 'image' && isset( $column_data->background_image ) && isset( $column_data->background_image->desktop ) && isset( $column_data->background_image->desktop->image ) && isset( $column_data->background_image->desktop->image->desktop["1"]->id ) && ! empty( $column_data->background_image->desktop->image->desktop["1"]->id ) ) {
						$column_background_class .= ' brix-background-image';
						$column_background_title = BrixBuilder::instance()->i18n_strings( 'background_image' );
					}
					else if ( isset( $column_data->background ) && $column_data->background == 'video' && isset( $column_data->background_video ) && isset( $column_data->background_video->url ) && ! empty( $column_data->background_video->url ) ) {
						$column_background_class .= ' brix-background-video';
						$column_background_title = BrixBuilder::instance()->i18n_strings( 'background_video' );
					}
					else if ( isset( $column_data->background_image ) && isset( $column_data->background_image->desktop ) && $column_data->background_image->desktop->color_type == "gradient" ) {
						$column_background_class .= ' brix-background-gradient';
						$column_background_title = BrixBuilder::instance()->i18n_strings( 'background_gradient' );
					}
					else if ( isset( $column_data->background_image ) && isset( $column_data->background_image->desktop ) && isset( $column_data->background_image->desktop->color ) && isset( $column_data->background_image->desktop->color->color ) && ! empty( $column_data->background_image->desktop->color->color ) ) {
						$column_background_color = $column_data->background_image->desktop->color->color;
						$column_background_class .= ' brix-background-color';
						$column_background_title = BrixBuilder::instance()->i18n_strings( 'background_color' );
					}
				}
			}

			printf( '<span class="brix-background brix-column-background %s" data-title="%s"><span style="background-color: %s"></span></span>',
				esc_attr( $column_background_class ),
				esc_attr( $column_background_title ),
				esc_attr( $column_background_color )
			);
		?>

		<span class="brix-section-column-label brix-label"><?php echo esc_html( $size ); ?></span>
	</div>

	<div class="brix-section-column-inner-wrapper">
		<?php
			if ( isset( $data->blocks ) && ! empty( $data->blocks ) ) {
				foreach ( $data->blocks as $block ) {
					brix_template( BRIX_FOLDER . '/templates/admin/block', array(
						'data' => $block
					) );
				}
			}
		?>
	</div>

	<div class="brix-add-block-wrapper">
		<a class="brix-add-block" href="#"><span class="brix-add-block-inner-wrapper"><span class="brix-add-block-label screen-reader-text"><?php echo esc_html( __( 'Add content', 'brix' ) ); ?></span></span></a>
	</div>

</div>