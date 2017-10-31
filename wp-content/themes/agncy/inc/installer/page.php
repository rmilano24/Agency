<?php
	$subtitle = __( 'Theme setup wizard', 'agncy' );
?>

<div id="agncy-installer" v-bind:class="installerClassObject">
	<div class="agncy-i_w" v-if="step==0">
		<div class="agncy-i_w-i">
			<div class="agncy-i-pt_w">
				<p class="agncy-i-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<h1><?php esc_html_e( 'Welcome to Agncy!', 'agncy' ); ?></h1>
				<p><?php esc_html_e( 'Thank you for purchasing our theme!', 'agncy' ); ?></p>
				<p><?php esc_html_e( 'In a couple of steps you&#8217;ll be able to install all the required stuff and your theme will be ready to rock!', 'agncy' ); ?></p>
			</div>

			<div class="agncy-i-pb_w">
				<button class="agncy-i-btn agncy-i-btn-arr" type="button" v-on:click="installRequiredPlugins"><?php esc_html_e( 'Let&#8217;s start', 'agncy' ); ?><?php echo agncy_load_svg( 'img/arrow.svg' ); ?></button>
				<p><?php echo wp_kses_post( __( 'By clicking on the above button the required plugins will be installed; no current content will be deleted nor modified.', 'agncy' ) ); ?></p>
			</div>
		</div>
		<p><a class="agncy-skip" href="<?php echo esc_attr( TGM_Plugin_Activation::get_instance()->get_tgmpa_url() ); ?>"><?php esc_html_e( 'Skip the theme setup wizard', 'agncy' ); ?></a></p>
	</div>


	<div class="agncy-i_w" v-if="step==1">
		<div class="agncy-i_w-i">
			<div class="agncy-i-pt_w">
				<p class="agncy-i-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<h1><?php esc_html_e( 'Installing the required plugins', 'agncy' ); ?></h1>
			</div>

			<span class="agncy-i-spinner"></span>

			<div class="agncy-i-pb_w">
				<p v-html="status"></p>

				<div id="agncy-np_w"></div>
			</div>
		</div>
	</div>


	<div class="agncy-i_w" v-if="step==2">
		<div class="agncy-i_w-i">
			<div class="agncy-i-pt_w">
				<p class="agncy-i-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<h1><?php esc_html_e( 'You&#8217;re all set!', 'agncy' ); ?></h1>
			</div>

			<div class="agncy-i-pb_w">
				<?php echo agncy_load_svg( 'img/mark.svg' ); ?>
				<button type="button" class="agncy-i-btn" v-on:click="goToWelcome"><?php esc_html_e( 'Finish!', 'agncy' ); ?></button>
			</div>
		</div>
	</div>

</div>
