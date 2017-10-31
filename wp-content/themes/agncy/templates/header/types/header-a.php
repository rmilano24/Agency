<header class="agncy-h-hl">
	<div class="agncy-h-hl-w_i">
		<?php agncy_logo(); ?>

		<div class="agncy-h-c">
			<div class="agncy-h-n">
				<?php agncy_nav( 'primary' ); ?>
			</div>

			<?php
				if ( function_exists( 'ev_get_option' ) ) {
					$widget_area1 = ev_get_option( 'header_layout_a_widget_area_1' );
					$widget_area2 = ev_get_option( 'header_layout_a_widget_area_2' );

					if ( $widget_area1 ) {
						echo '<div class="agncy-h-wa">';
							dynamic_sidebar( $widget_area1 );
						echo '</div>';
					}

					if ( $widget_area2 ) {
						echo '<div class="agncy-h-wa">';
							dynamic_sidebar( $widget_area2 );
						echo '</div>';
					}
				}
			?>
		</div>
	</div>
</header>
