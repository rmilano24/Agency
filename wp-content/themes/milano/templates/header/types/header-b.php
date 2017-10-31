<header class="agncy-h-hl">
	<div class="agncy-h-hl-w_i">
		<?php agncy_logo(); ?>

		<div class="agncy-h-n-t_w">
			<a href="#" class="agncy-drawer-trigger agncy-h-n-t"><span></span></a>
		</div>
	</div>
</header>

<?php

	$skin = ev_get_option( 'header_layout_b_skin' );
	$skin = $skin != '' ? $skin : 'dark';

?>

<div class="agncy-drawer agncy-h-drawer agncy-drawer-s-<?php echo esc_attr( $skin ); ?>">
	<div class="agncy-h-drawer-w">
		<div class="agncy-h-drawer-w_i">
			<div class="agncy-h-drawer-h">
				<?php agncy_logo( true ); ?>
				<button type="button" class="agncy-drawer-close agncy-h-drawer-close"></button>
			</div>

			<div class="agncy-h-drawer-c-w_i">
				<div class="agncy-h-n">
					<?php agncy_nav( 'primary' ); ?>
				</div>

				<?php

				$widget_area = ev_get_option( 'header_layout_b_widget_area_1' );

				?>

				<div class="agncy-h-wa">
					<?php if ( $widget_area && is_active_sidebar( $widget_area ) ) : ?>
						<aside class="agncy-h-wa-1">
							<?php dynamic_sidebar( $widget_area ); ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php
		$background_overlay = ev_get_option( 'header_layout_b_background_overlay' );
		$background_drawer_class = '';

		if ( ! isset( $background_overlay[ 'color' ] ) || empty( $background_overlay[ 'color' ] ) ) {
			$background_drawer_class .= ' agncy-h-drawer-empty-overlay';
		}
	?>

	<span class="agncy-h-drawer-bg <?php echo esc_attr( $background_drawer_class ); ?>"></span>
</div>
