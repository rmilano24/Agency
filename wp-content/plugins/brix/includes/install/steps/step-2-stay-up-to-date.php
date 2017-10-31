<div class="brix-install-step-header">
	<h1><?php esc_html_e( 'Stay up to date', 'brix' ); ?></h1>
	<p><?php esc_html_e( 'Brix is updated regularly, bringing in new features, providing security enhancements, fixing bugs, and generally be compatible with the latest and greatest WordPress version available.', 'brix' ); ?></p>
</div>

<div class="brix-install-stripes-wrapper">
	<div class="brix-install-stripe">
		<div class="brix-install-update-notice-wrapper">
			<p><?php echo wp_kses_post( __( 'Enable the automatic update system by inserting your <strong>Purchase Code</strong> and <strong>email</strong>.', 'brix' ) ); ?></p>
			<p><?php echo wp_kses_post( __( 'You are entitled to Brix updates if you have a valid support account over at <a href="https://justevolve.it" target="_blank" rel="noopener noreferrer">Evolve Shop</a>. If your support account expires, you can renew your plan with additional months of support, re-gaining the ability to download updates.', 'brix' ) ); ?></p>
		</div>
		<?php brix_registration_page_details(); ?>

	</div>
 </div>

<div class="brix-install-navigation">
	<?php brix_install_prev_button(); ?>
	<?php brix_install_next_button(); ?>
</div>