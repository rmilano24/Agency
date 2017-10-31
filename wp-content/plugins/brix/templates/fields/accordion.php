<?php
	$accordion = $field->value();

	if ( empty( $accordion ) ) {
		$accordion = '[]';
	}

	$accordion = json_decode( $accordion );
?>


<div class="brix-accordion-container">
	<ul>
		<?php
			if ( ! empty( $accordion ) ) {
				foreach ( $accordion as $index => $toggle ) {
					$toggle_title = isset( $toggle->title ) && ! empty( $toggle->title ) ? $toggle->title : __( 'Untitled toggle', 'brix' );
					$toggle_content = isset( $toggle->content ) && ! empty( $toggle->content ) ? $toggle->content : '';

					printf( '<li class="brix-accordion-block-toggle-trigger">
						<span class="brix-accordion-block-toggle-preview"></span>
						<div class="brix-accordion-block-content-wrapper">
							<span class="brix-accordion-block-toggle-index">%s</span>
							<span class="brix-accordion-block-toggle-title">%s</span>
							<span class="brix-accordion-block-toggle-content">%s</span>
						</div>
						<span class="brix-accordion-block-toggle-remove"></span>
					</li>',
						esc_html( $index ),
						esc_html( $toggle_title ),
						esc_html( $toggle_content )
					);
				}
			}
		?>
		<li class="brix-add-new-toggle-wrapper">
			<span href="#" class="brix-add-new-toggle"><?php echo esc_html( __( 'Add toggle', 'brix' ) ); ?></span>
		</li>
	</ul>
</div>

<?php
	$value = htmlspecialchars( json_encode( $accordion ) );

	printf( '<input type="hidden" name="%s" data-accordion-value value="%s">', esc_attr( $field->handle() ), $value );
?>
