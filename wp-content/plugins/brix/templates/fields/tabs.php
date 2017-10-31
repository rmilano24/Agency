<?php
	$tabs = $field->value();
	$tabs_class = '';

	if ( empty( $tabs ) ) {
		$tabs = '[]';
	}

	$tabs = json_decode( $tabs );

	if ( empty( $tabs ) ) {
		$tabs_class = 'brix-tabs-empty';
	}
?>

<div class="brix-tabs-container <?php echo esc_attr( $tabs_class ); ?>">
	<div class="brix-tab-block-nav">
		<ul>
			<?php
				if ( ! empty( $tabs ) ) {
					foreach ( $tabs as $index => $tab ) {
						$tab_title = isset( $tab->title ) && ! empty( $tab->title ) ? $tab->title : __( 'Untitled tab', 'brix' );

						printf( '<li class="brix-tab-block-trigger %s"><div class="brix-tab-block-trigger-inner-wrapper"><span class="brix-tab-block-handle"></span><span class="brix-tab-block-title">%s</span><span class="brix-tab-block-remove-tab"></span></div></li>',
							$index === 0 ? esc_attr( 'brix-current' ) : '',
							esc_html( $tab_title )
						);
					}
				}
			?>
			<li class="brix-add-new-tab-wrapper">
				<span href="#" class="brix-add-new-tab"><?php echo esc_html( __( 'Add tab', 'brix' ) ); ?></span>
			</li>
		</ul>
	</div>

	<div class="brix-tabs-contents">
		<?php
			if ( ! empty( $tabs ) ) {
				foreach ( $tabs as $index => $tab ) {
					$tab_content = isset( $tab->content ) && ! empty( $tab->content ) ? $tab->content : '';

					printf( '<div class="brix-tabs-content %s">%s</div>',
						$index === 0 ? esc_attr( 'brix-current' ) : '',
						esc_html( $tab_content )
					);
				}
			}
		?>
	</div>
</div>

<?php
	$value = htmlspecialchars( json_encode( $tabs ) );

	printf( '<input type="hidden" name="%s" data-tabs-value value="%s">', esc_attr( $field->handle() ), $value );
?>
