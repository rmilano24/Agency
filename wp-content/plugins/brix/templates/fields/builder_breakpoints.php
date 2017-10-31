<?php

$breakpoints = brix_breakpoints();

if ( isset( $breakpoints['desktop'] ) ) {
	unset( $breakpoints['desktop'] );
}

$value = $field->value();

?>

<div class="brix-breakpoints-container">
	<?php foreach ( $breakpoints as $id => $breakpoint ) : ?>
		<?php
			$original = '';

			if ( isset( $breakpoint['_original'] ) ) {
				$original = json_encode( $breakpoint['_original'] );
			}
		?>
		<div class="brix-breakpoint" data-id="<?php echo esc_attr( $id ); ?>" data-original="<?php echo htmlentities( $original ); ?>">
			<span class="brix-custom-breakpoint-label">
				<?php echo esc_html( $breakpoint['label'] ); ?>
			</span>

			<span class="brix-custom-breakpoint-media-query">
				<?php echo esc_html( $breakpoint['media_query'] ); ?>
			</span>

			<?php
				if ( isset( $breakpoint['custom'] ) && $breakpoint['custom'] ) {
					brix_btn( _x( 'Remove', 'remove breakpoint', 'brix' ), 'action', array(
						'attrs' => array(
							'class' => 'brix-breakpoint-remove',
						),
						'size' => 'small',
						'style' => 'text'
					) );
				}
				else {
					$changed = false;

					if ( isset( $breakpoint['_original'] ) ) {
						foreach ( $breakpoint['_original'] as $k => $v ) {
							if ( isset( $breakpoint[$k] ) && $breakpoint[$k] !== $v ) {
								$changed = true;
								break;
							}
						}
					}

					if ( $changed ) {
						brix_btn( _x( 'Reset', 'reset breakpoint', 'brix' ), 'action', array(
							'attrs' => array(
								'class' => 'brix-breakpoint-reset',
							),
							'size' => 'small',
							'style' => 'text'
						) );
					}
				}
			?>
		</div>
	<?php endforeach; ?>
</div>

<?php
	/**
	 * Add breakpoints controls hook.
	 *
	 * @since 1.1.3
	 */
	do_action( 'brix_breakpoints_controls' );
?>

<?php

$handle = $field->handle();

$full_width_media_queries = array( '' => '-' );

foreach ( $breakpoints as $id => $breakpoint ) {
	$full_width_media_queries[$id] = $breakpoint['label'];
}

$full_width_media_query = isset( $value['full_width_media_query'] ) ? $value['full_width_media_query'] : '';

echo '<div class="brix-full-width-media-query">';
	brix_select(
		$handle . '[full_width_media_query]',
		$full_width_media_queries,
		$full_width_media_query,
		'brix-full-width-media-query'
	);

	printf( '<span>%s</span>', esc_html( __( 'Sections and columns go full width under this breakpoint.', 'brix' ) ) );
echo '</div>';

if ( isset( $value['breakpoints'] ) ) {
	if ( empty( $value['breakpoints'] ) ) {
		$value['breakpoints'] = '{}';
	}

	$value = json_decode( $value['breakpoints'] );
}
else {
	$value = new stdClass();
}

$value = htmlspecialchars( json_encode( $value ) );

printf( '<input type="hidden" name="%s[breakpoints]" data-breakpoints-value value="%s">', esc_attr( $handle ), $value );