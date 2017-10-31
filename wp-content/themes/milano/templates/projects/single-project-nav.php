<?php
$prev_post = get_previous_post();
$next_post = get_next_post();
$arr_icon = agncy_load_svg( 'img/arrow.svg' );
$img_size = 'large';

$nav_classes = 'agncy-sp-p';

$show_share = get_post_meta( get_queried_object_id(), 'agncy_project_share', true );

if ( $show_share ) {
	$nav_classes .= ' agncy-sp-share-enabled';
}

$show_nav = agncy_show_project_nav() && ( ! empty( $prev_post ) || ! empty( $next_post ) );

if ( $show_nav ) {
	$nav_classes .= ' agncy-sp-nav-enabled';
}

if ( $show_nav || $show_share ) : ?>
	<div class="<?php echo esc_attr( $nav_classes ); ?>">

	<?php
		if ( $show_share ) {
			do_action( 'agncy_project_navigation' );
		}
	?>

	<?php if ( $show_nav ) : ?>
		<div class="agncy-sp-p-w_i">

		<?php
			if ( $prev_post ) {
				$prev_post_title     = get_the_title( $prev_post->ID );
				$prev_post_permalink = get_permalink( $prev_post->ID );
				$prev_post_subtitle  = agncy_get_project_subtitle( $prev_post->ID );
				$prev_post_color	 = agncy_get_project_color( $prev_post->ID );
				$prev_post_fi        = ev_get_featured_image( $img_size, $prev_post->ID );
				$prev_classes        = 'agncy-previous-post agncy-sp-ni_w';

				$prev_post_style = '';

				if ( $prev_post_fi ) {
					$prev_post_style = 'style="background-image:url(' . $prev_post_fi . ')"';
					$prev_classes    .= ' agncy-w-fi';
				}

				printf( '<div class="%s">', esc_attr( $prev_classes ) );
					printf( '<a href="%s" data-color="%s" class="agncy-sp-ni">', esc_url( $prev_post_permalink ), esc_attr( $prev_post_color ) );

						echo agncy_load_svg( 'img/arrow.svg' );

						echo '<span class="agncy-sp-ni_w-i">';

							printf( '<span class="agncy-previous-post-label agncy-sp-label">%s</span>', esc_html__( 'Previous project', 'agncy' ) );

							printf( '<span class="agncy-sp-subtitle">%s</span>', esc_html( $prev_post_subtitle ) );
							printf( '<span class="agncy-sp-title">%s</span>', wp_kses_post( $prev_post_title ) );

						echo '</span>';

						printf( '<span class="agncy-sp-bg" %s></span>', $prev_post_style );
					echo '</a>';
				echo '</div>';
			}

			if ( $next_post ) {
				$next_post_title     = get_the_title( $next_post->ID );
				$next_post_permalink = get_permalink( $next_post->ID );
				$next_post_subtitle  = agncy_get_project_subtitle( $next_post->ID );
				$next_post_color	 = agncy_get_project_color( $next_post->ID );
				$next_post_fi        = ev_get_featured_image( $img_size, $next_post->ID );
				$next_classes        = 'agncy-next-post agncy-sp-ni_w';

				$next_post_style = '';

				if ( $next_post_fi ) {
					$next_post_style = 'style="background-image:url(' . $next_post_fi . ')"';
					$next_classes .= ' agncy-w-fi';
				}

				printf( '<div class="%s">', esc_attr( $next_classes ) );
					printf( '<a href="%s" data-color="%s" class="agncy-sp-ni">', esc_url( $next_post_permalink ), esc_attr( $next_post_color ) );

						echo agncy_load_svg( 'img/arrow.svg' );

						echo '<span class="agncy-sp-ni_w-i">';

							printf( '<span class="agncy-next-post-label agncy-sp-label">%s</span>', esc_html__( 'Next project', 'agncy' ) );

							printf( '<span class="agncy-sp-subtitle">%s</span>', esc_html( $next_post_subtitle ) );
							printf( '<span class="agncy-sp-title">%s</span>', esc_html( $next_post_title ) );

						echo '</span>';

						printf( '<span class="agncy-sp-bg" %s></span>', $next_post_style );
					echo '</a>';
				echo '</div>';
			}
		?>
		</div>
	<?php endif; ?>

	</div>
<?php endif; ?>