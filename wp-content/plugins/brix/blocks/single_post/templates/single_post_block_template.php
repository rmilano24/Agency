<?php

$id = 0;
$post = false;

if ( isset( $data->data->id ) ) {
	$id = $data->data->id;
}

$id = apply_filters( 'brix_single_post_builder_block_id', $id, $data->data );

if ( ! $id ) {
	return;
}

$post_query = new Brix_Query( array(
	'posts_per_page' => 1,
	'post_type'      => 'post',
	'p'              => (int) $id
) );

?>

<?php if ( $post_query->have_posts() ) : while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
	<article id="post-<?php echo esc_attr( $id ); ?>" <?php post_class(); ?>>
		<?php
			$default_path = BRIX_BLOCKS_FOLDER . 'single_post/templates/partials/single_post_default';
			$path = apply_filters( 'brix_single_post_builder_block_path', $default_path, $data->data );

			$data_array = json_decode( json_encode( $data->data ), true );

			brix_template( $path, $data_array );
		?>
	</article>
<?php endwhile; endif; wp_reset_postdata(); ?>