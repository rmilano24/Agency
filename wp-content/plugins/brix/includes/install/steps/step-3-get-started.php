<div class="brix-install-step-header">
	<h1><?php esc_html_e( 'Get started', 'brix' ); ?></h1>
	<p><?php esc_html_e( 'Now you are ready to create your first page with Brix!', 'brix' ); ?></p>
</div>

<div class="brix-install-stripes-wrapper">
	<div class="brix-install-stripe brix-start-stripe">
		<a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>" class="brix-start-btn"><?php esc_html_e( 'Start', 'brix' ); ?></a>

		<div class="brix-start-image-wrapper">
			<img src="<?php echo BRIX_INSTALL_URI . 'assets/admin/css/i/brix-start.gif'; ?>" alt="">
		</div>
	</div>

	<div class="brix-install-stripe brix-integration-stripe">
		<div class="brix-integration-col">
			<h2><?php esc_html_e( 'A better theme integration', 'brix' ); ?></h2>
			<p><?php esc_html_e( 'Do you need to integrate Brix with your theme grid?', 'brix' ); ?></p>
			<p><?php esc_html_e( 'You can quickly change the grid width, gutter and the vertical spacing to fit your theme design.', 'brix' ); ?></p>
		</div>

		<div class="brix-integration-col">
			<h2><?php esc_html_e( 'Brix is responsive!', 'brix' ); ?></h2>
			<p><?php esc_html_e( 'From the main options you can change in a matter of seconds how Brix will adapt to different devices by using the built-in media queries or create your custom ones.', 'brix' ); ?></p>
		</div>

		<div class="brix-integration-btn-wrapper">
			<a href="<?php echo admin_url( 'admin.php?page=brix' ); ?>" class="brix-options-btn"><?php esc_html_e( 'Integration options', 'brix' ); ?></a>
		</div>
	</div>
</div>

<div class="brix-install-navigation">
	<?php brix_install_prev_button(); ?>
	<a href="<?php echo admin_url( 'admin.php?page=brix' ); ?>" class="brix-install-end"><?php esc_html_e( 'Close', 'brix' ); ?></a>
</div>