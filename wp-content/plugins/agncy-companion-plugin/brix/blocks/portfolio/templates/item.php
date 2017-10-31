<?php
$id = get_the_ID();
$image_size = agncy_portfolio_featured_media_size( $data );

/* Project classes. */
$classes = apply_filters( 'brix_agncy_project_item_classes', array(), $data, $id, $index );

/* Project subtitle. */
$subtitle = agncy_get_project_subtitle( $id );

$media_style = '';

$project_color = agncy_get_project_color();

?>
<article id="project-<?php echo esc_attr( $id ); ?>" <?php post_class( $classes ); ?>>
	<a class="agncy-p-i-w_i" href="<?php the_permalink(); ?>" data-color="<?php echo esc_attr( $project_color ); ?>">
		<div class="agncy-p-i-m" <?php echo esc_attr( $media_style ); ?> style="color:<?php echo esc_attr( $project_color ); ?>">
			<?php
				echo brix_get_lazy_image_markup( ev_get_featured_image_id(), array(
					'classes'       => array( 'brix-block-image-img' ),
					'size'          => $image_size,
					'link'          => '',
					'caption'       => '',
					'caption_class' => ''
				) );
			 ?>
		</div>

		<div class="agncy-p-i-d">
			<header>
				<h2 class="agncy-p-i-h"><?php the_title(); ?></h2>

				<?php if ( $subtitle ) : ?>
					<p class="agncy-p-i-t"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</header>
		</div>
	</a>
</article>