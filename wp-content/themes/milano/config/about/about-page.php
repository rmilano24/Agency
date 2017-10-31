<?php

$agncy = agncy_get_theme();
$has_update = agncy_has_updates();
$theme_status = agncy_theme_status();

$auth_url = add_query_arg( array(
	'response_type' => 'code',
	'client_id'     => agncy_get_envato_api_client_id(),
	'redirect_uri'  => 'https://justevolve.it/updaters/agncy_updater.php'
), 'https://api.envato.com/authorization' );

?>

<div class="agncy-ap_w" v-bind:class="this.aboutAppClass" id="agncy-about">

	<?php agncy_show_plugin_admin_notices( true ); ?>

	<div class="agncy-ap-h_w">
		<div class="agncy-ap-h_w-i">
			<?php if ( $theme_status == 'first_install' ) : ?>
				<h1><?php esc_html_e( 'Welcome to Agncy', 'agncy' ); ?></h1>
			<?php else : ?>
				<h1><?php esc_html_e( 'Agncy', 'agncy' ); ?></h1>
			<?php endif; ?>

			<p><?php esc_html_e( 'Agncy is now installed and ready to use! Discover what&#8217;s changed in the latest theme update and make sure to register your license in order to enable auto updates.', 'agncy' ); ?></p>

			<?php if ( $has_update ) : ?>
				<a href="<?php echo esc_attr( admin_url( 'update-core.php' ) ); ?>" class="agncy-ap-v_w agncy-ap-ua">
					<span class="agncy-ap-v-l"><?php esc_html_e( 'Update available', 'agncy' ); ?></span>
					<span class="agncy-ap-v-n"><?php echo esc_html( $agncy->get( 'Version' ) ); ?> &rarr; <?php echo esc_html( $has_update ); ?></span>
				</a>
			<?php else : ?>
				<div class="agncy-ap-v_w">
					<?php if ( is_child_theme() ) : ?>
						<span class="agncy-ap-v-l"><?php esc_html_e( 'Parent theme version', 'agncy' ); ?></span>
					<?php else : ?>
						<span class="agncy-ap-v-l"><?php esc_html_e( 'Current version', 'agncy' ); ?></span>
					<?php endif; ?>
					<span class="agncy-ap-v-n"><?php echo esc_html( $agncy->get( 'Version' ) ); ?></span>
				</div>
			<?php endif; ?>

		</div>

		<div class="agncy-ap-n agncy-ap-n-warning" v-if="auto_updates.linked == false">
			<h4><?php esc_html_e( 'Activate your theme in order to receive automatic updates.', 'agncy' ); ?></h4>
			<p><?php esc_html_e( 'In order to enable automatic updates, you have to connect the theme to your Envato account.', 'agncy' ); ?></p>

			<button class="agncy-ap-n-btn agncy-ap-btn agncy-ap-btn-fill" v-on:click="autoUpdatesAuth( '<?php echo esc_attr( $auth_url ); ?>' )"><?php esc_html_e( 'Activate now', 'agncy' ); ?></button>
		</div>
	</div>

	<div class="agncy-ap-t-n_w">
		<button v-if="theme_status=='first_install'" class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'welcome' }" type="button" v-on:click="setPanel('welcome')">
			<?php esc_html_e( 'Welcome', 'agncy' ); ?>
		</button>
		<button v-if="theme_status==''" class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'intro' }" type="button" v-on:click="setPanel('intro')">
			<?php esc_html_e( 'Getting started', 'agncy' ); ?>
		</button>
		<button v-if="theme_status=='updated'" class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'new_update' }" type="button" v-on:click="setPanel('new_update')">
			<?php esc_html_e( "What's new", 'agncy' ); ?>
		</button>
		<button class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'update' }" type="button" v-on:click="setPanel('update')">
			<?php esc_html_e( 'Auto updates', 'agncy' ); ?>
			<span v-if="auto_updates.linked == false" class="agncy-ap-badge agncy-ap-b-warning"></span>
			<span v-if="auto_updates.linked == true" class="agncy-ap-badge agncy-ap-b-success"></span>
		</button>
		<button class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'demos' }" type="button" v-on:click="setPanel('demos')">
			<?php esc_html_e( 'Demo content', 'agncy' ); ?>
		</button>
		<button class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'support' }" type="button" v-on:click="setPanel('support')">
			<?php esc_html_e( 'Support', 'agncy' ); ?>
		</button>
		<button class="agncy-ap-t-n-i" v-bind:class="{ 'active': panel == 'system' }" type="button" v-on:click="setPanel('system')">
			<?php esc_html_e( 'System status', 'agncy' ); ?>
			<span v-if="system_status.errors == true" class="agncy-ap-badge agncy-ap-b-warning"></span>
			<span v-if="system_status.errors == false" class="agncy-ap-badge agncy-ap-b-success"></span>
		</button>
	</div>

	<div class="agncy-ap-t_w">
		<div class="agncy-ap-p_w agncy-ap-panel-welcome" v-if="panel=='welcome'">
			<?php get_template_part( 'config/about/panels/welcome' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-intro" v-if="panel=='intro'">
			<?php get_template_part( 'config/about/panels/getting_started' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-new_update" v-if="panel=='new_update'">
			<?php get_template_part( 'config/about/panels/new_update' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-update" v-if="panel=='update'">
			<?php get_template_part( 'config/about/panels/auto_updates' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-support" v-if="panel=='support'">
			<?php get_template_part( 'config/about/panels/support' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-system" v-if="panel=='system'">
			<?php get_template_part( 'config/about/panels/system' ); ?>
		</div>

		<div class="agncy-ap-p_w agncy-ap-panel-demos" v-if="panel=='demos'">
			<?php get_template_part( 'config/about/panels/demos' ); ?>
		</div>
	</div>

</div>