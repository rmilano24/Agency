<div class="agncy-ap-mc_w agncy-ap-mc-f">

	<div class="agncy-ap-b-h" v-if="demos.errors == false">
		<h2><?php esc_html_e( 'Demo content', 'agncy' ); ?></h2>
		<p>
			<?php
				echo wp_kses_post(
					__( 'Please note that some of the images used in the online demo may be replaced with creative commons images from <a href="https://unsplash.com" target="_blank">Unsplash</a>. Images are always intended for demo purposes only.', 'agncy' )
				);
			?>
		</p>
		<p>
			<?php
				echo wp_kses_post(
					__( 'Installing a demo will not erase any page, or post, or Media Library item, but keep in mind that will override any customization you might have made in the WordPress Customizer.', 'agncy' )
				);
			?>
		</p>
	</div>

	<div class="agncy-ap-d-c" v-if="demos.errors == false" v-bind:class="demosContainerClass">
		<div class="agncy-ap-d-c_w-i">
			<div class="agncy-ap-d--d" v-for="( demo, id ) in demos.list" v-bind:data-name="demo.name">
				<div class="agncy-ap-d--d-i">
					<img v-bind:src="demo.preview">
					<div class="agncy-ap-d--d-a-c">
						<a class="agncy-ap-btn agncy-ap-btn-fill agncy-ap-d--p-btn" v-bind:href="demo.url" target="_blank"><?php esc_html_e( 'Preview', 'agncy' ); ?></a>
						<button class="agncy-ap-btn agncy-ap-btn-fill agncy-ap-d--d-btn" type="button" v-on:click="installDemo( demo.name, '<?php echo esc_attr( wp_create_nonce( 'agncy_demo_install' ) ); ?>' )"><?php esc_html_e( 'Install', 'agncy' ); ?></button>
					</div>
				</div>
				<div class="agncy-ap-d--d-d">
					<p>{{ demo.label }}</p>
				</div>
			</div>
		</div>
	</div>
	<div class="agncy-ap-d-c agncy-ap-d-c--empty" v-else>
		<div class="agncy-ap-d-c--empty-m">
			<div>
				<h2><?php esc_html_e( 'Agncy Demo Installer plugin missing', 'agncy' ); ?></h2>
				<p><?php esc_html_e( 'In order to install the theme demos, please install and activate the Agncy Demo Installer plugin and then come back to this page.', 'agncy' ); ?></p>

				<?php
					printf( '<a href="%s" class="agncy-ap-btn agncy-ap-btn-fill">%s</a>',
						esc_attr( TGM_Plugin_Activation::get_instance()->get_tgmpa_url() ),
						esc_html( __( 'Install', 'agncy' ) )
					);
				?>
			</div>
		</div>
		<div class="agncy-ap-d-c_w-i">
			<div class="agncy-ap-d--d-empty">
				<div class="agncy-ap-d--d-i">
				</div>
			</div>
			<div class="agncy-ap-d--d-empty">
				<div class="agncy-ap-d--d-i">
				</div>
			</div>
			<div class="agncy-ap-d--d-empty">
				<div class="agncy-ap-d--d-i">
				</div>
			</div>
			<div class="agncy-ap-d--d-empty">
				<div class="agncy-ap-d--d-i">
				</div>
			</div>
		</div>
	</div>
</div>