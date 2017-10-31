<header class="agncy-h-hlm">
	<div class="agncy-h-hlm-w_i">
		<?php agncy_logo( 'mobile' ); ?>

		<div class="agncy-h-n-t_w">
			<a href="#" class="agncy-drawer-trigger agncy-h-mn-t"><span></span></a>
		</div>
	</div>
</header>

<?php
	$skin = '';

	if ( function_exists( 'ev_get_option' ) ) {
		$skin = ev_get_option( 'mobile_drawer_skin' );
	}

	$skin = $skin != '' ? $skin : 'light';

?>

<div class="agncy-drawer agncy-h-m-drawer agncy-drawer-s-<?php echo esc_attr( $skin ); ?>">
	<div class="agncy-h-m-drawer-h">
		<button type="button" class="agncy-drawer-close agncy-mh-drawer-close"></button>
	</div>

	<div class="agncy-h-m-drawer-c-w_i">
		<?php agncy_nav( 'primary' ); ?>
	</div>

	<span class="agncy-h-m-drawer-bg"></span>
</div>