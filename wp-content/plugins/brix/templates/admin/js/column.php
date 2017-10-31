<div class="brix-section-column brix-draggable brix-empty brix-col-{{ size.replace( '/', '-' ) }}" data-size="{{ size }}" data-data="">
	<a href="#" class="brix-column-merge brix-tooltip" data-title="<?php echo esc_attr( __( 'Merge columns', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Merge columns', 'brix' ) ); ?></span></a>

	<div class="brix-section-column-controls brix-controls">

		<div class="brix-section-column-bar-panel">
			<div class="brix-section-column-bar-panel-inner-wrapper">
				<a href="#" class="brix-column-split brix-tooltip" data-title="<?php echo esc_attr( __( 'Split column', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Split column', 'brix' ) ); ?></span></a>
				<a data-title="<?php echo esc_attr( __( 'Edit column', 'brix' ) ); ?>" class="brix-edit brix-column-edit  brix-tooltip" href="#"><span><?php echo esc_html( __( 'Edit column', 'brix' ) ); ?></span></a>
			</div>
		</div>

		<span class="brix-section-column-carousel-mode brix-tooltip" data-title="<?php echo esc_attr( __( 'Carousel mode enabled', 'brix' ) ); ?>"></span>
		<span class="brix-background brix-column-background"><span></span></span>

		<span class="brix-section-column-label brix-label">{{ size_label }}</span>
	</div>

	<div class="brix-section-column-inner-wrapper">
	</div>

	<div class="brix-add-block-wrapper">
		<a class="brix-add-block" href="#"><span class="brix-add-block-inner-wrapper"><span class="brix-add-block-label screen-reader-text"><?php echo esc_html( __( 'Add content', 'brix' ) ); ?></span></span></a>
	</div>

</div>