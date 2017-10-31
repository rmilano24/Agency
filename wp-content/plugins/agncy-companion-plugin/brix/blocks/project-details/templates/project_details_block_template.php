<?php

if ( get_post_type( get_the_ID() ) !== 'agncy_project' ) {
	return;
}

$project_metas = agncy_projects_metas();

$metas = array();
$prj_data_meta = get_post_meta( get_the_ID(), 'meta', true );

$block_metas = explode( ',', $data->data->metas );
$alignment = $data->data->display;

if ( $prj_data_meta ) {
	foreach ( $prj_data_meta as $meta ) {
		if ( ! isset( $metas[ $meta[ 'meta' ] ] ) ) {
			$metas[ $meta[ 'meta' ] ] = array();
		}

		$metas[ $meta[ 'meta' ] ][] = $meta[ 'value' ];
	}
}

$categories = wp_get_object_terms( get_the_ID(), 'agncy_project_category' );

$metas[ '_category' ] = array( __( 'Category', 'agncy-companion-plugin' ) );

?>
<?php if ( $block_metas ) : ?>
	<div class="agncy-p-d <?php echo esc_attr( 'agncy-p-d-a-' . $alignment ); ?>">
		<?php do_action( 'agncy_project_meta_before' ); ?>

		<?php foreach ( $block_metas as $meta ) : ?>
			<?php
				if ( ! isset( $metas[ $meta ] ) ) continue;

				$value = $metas[ $meta ];
			?>
			<div class="agncy-p-d-b">
				<?php
					switch ( $meta ) {
						case '_category':
							echo wp_kses_post( sprintf( '<span class="agncy-p-d-b-l">%s</span>', $value[ 0 ] ) );

							echo '<span class="agncy-p-d-b-d">';
								foreach ( $categories as $j => $category ) {
									if ( $j > 0 ) {
										echo ', ';
									}
									// printf( '<a href="%s">', esc_attr( get_term_link( $category->term_id ) ) );
										echo esc_html( $category->name );
									// echo '</a>';
								}
							echo '</span>';
							break;
						default:
							echo wp_kses_post( sprintf( '<span class="agncy-p-d-b-l">%s</span>', $project_metas[ $meta ] ) );
							echo wp_kses_post( sprintf( '<span class="agncy-p-d-b-d">%s</span>', implode( ', ', $value ) ) );
							break;
					}


				?>
			</div>
		<?php endforeach; ?>

		<?php do_action( 'agncy_project_meta_after' ); ?>
	</div>
<?php endif; ?>