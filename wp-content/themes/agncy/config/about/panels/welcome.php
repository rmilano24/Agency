<?php
$arrow_icon = agncy_load_svg( 'img/arrow.svg' );
?>
<div class="agncy-ap-mc_w">
	<div class="agncy-ap-if_w">
		<iframe width="640" height="360" src="https://www.youtube.com/embed/videoseries?list=PLRcW-as7a7gAmu-JBSMGcAxJtPt-USCrk" frameborder="0" allowfullscreen></iframe>
	</div>
</div>

<div class="agncy-ap-sc_w">
	<div class="agncy-ap-sc-b">
		<div class="agncy-ap-s-h agncy-ap-b-h">
			<h2><?php esc_html_e( "Where to start?", 'agncy' ); ?></h2>
			<p><?php esc_html_e( "Here you can find some useful links to start using your theme right away!", 'agncy' ); ?></p>
		</div>
		<a class="agncy-ap-btn agncy-ap-btn-link" href="<?php echo esc_url( admin_url( 'admin.php?page=agncy-global' ) ); ?>"><?php esc_html_e( 'Theme options', 'agncy' ); ?><?php print $arrow_icon; ?></a>
		<a class="agncy-ap-btn agncy-ap-btn-link" target="_blank" href="https://shop.justevolve.it/docs/brix/"><?php esc_html_e( 'Brix documentation', 'agncy' ); ?><?php print $arrow_icon; ?></a>
	</div>
</div>