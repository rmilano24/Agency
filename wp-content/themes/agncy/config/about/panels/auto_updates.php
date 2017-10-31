<?php

$auth_url = add_query_arg( array(
	'response_type' => 'code',
	'client_id'     => agncy_get_envato_api_client_id(),
	'redirect_uri'  => 'https://justevolve.it/updaters/agncy_updater.php'
), 'https://api.envato.com/authorization' );

?>

<div class="agncy-ap-mc_w">
	<div class="agncy-ap-u_w">
		<div class="agncy-ap-u-h agncy-ap-b-h">
			<h2><?php esc_html_e( 'Enable auto updates', 'agncy' ); ?></h2>
			<p><?php esc_html_e( 'In order to enable the auto update system you have to register your license. Click on the button below to begin the procedure.', 'agncy' ); ?></p>
		</div>

		<div class="agncy-ap-u-n_w" v-if="auto_updates.linked == true">
			<p>
				<?php
					esc_html_e( 'You have enabled auto updates.', 'agncy' );
				?>
			</p>
			<p>
				<button v-bind:disabled="auto_updates.working == true" v-on:click="unlinkAutoUpdates( '<?php echo wp_create_nonce( 'agncy_unlink_auto_updates' ); ?>' )" type="button" class="agncy-ap-btn agncy-ap-btn-link"><?php esc_html_e( 'Turn off auto updates.', 'agncy' ); ?></button>
			</p>
		</div>
		<div class="agncy-ap-u-f_w" v-if="auto_updates.linked == false">
			<div v-if="auto_updates.linking == true">
				<input type="text" v-model="auto_updates.code">
				<button v-bind:disabled="auto_updates.working == true" v-on:click="linkAutoUpdates( '<?php echo wp_create_nonce( 'agncy_link_auto_updates' ); ?>' )" type="button" class="agncy-ap-btn agncy-ap-btn-fill agncy-ap-btn-save"><?php esc_html_e( 'Save', 'agncy' ); ?></button>

				<p v-if="auto_updates.status != ''">{{ auto_updates.status }}</p>
			</div>
			<div v-else>
				<button v-if="auto_updates.linked == false" v-bind:disabled="auto_updates.working == true" v-on:click="autoUpdatesAuth( '<?php echo esc_attr( $auth_url ); ?>' )" type="button" class="agncy-ap-btn agncy-ap-btn-fill agncy-ap-btn-save"><?php esc_html_e( 'Activate your theme', 'agncy' ); ?></button>
			</div>
		</div>
	</div>
</div>