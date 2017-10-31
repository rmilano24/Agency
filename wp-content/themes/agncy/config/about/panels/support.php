<div class="agncy-ap-mc_w">
	<div class="agncy-ap-s-h agncy-ap-b-h">
		<h2><?php esc_html_e( "Need help?", 'agncy' ); ?></h2>
		<p><?php
			echo wp_kses_post(
					sprintf( __( 'We regularly check the compatibility of our products with other components such as themes and plugins, and it is our care to fix bugs and anomalies directly referrable to Brix. You can read our support policy <a href="%s" target="_blank" rel="noreferrer noopener">here</a>.', 'agncy' ),
						esc_attr( 'https://justevolve.it/support-and-license-policy/' )
					)
				);
		?></p>
		<p><?php
			echo wp_kses_post(
				sprintf( __( 'Support is provided on the <a href="%s" target="_blank" rel="noreferrer noopener">Justevolve.it</a> website through our ticketing system: on your dashboard, look for the "Support" section, from where you\'ll be able to browse tickets or create one.', 'agncy' ),
					esc_attr( 'https://justevolve.it' )
				)
			);
		?></p>
		<a class="agncy-ap-btn agncy-ap-btn-link" href="https://justevolve.it/wp-login.php"><?php esc_html_e( 'Go to your dashboard', 'agncy' ); ?><?php echo agncy_load_svg( 'img/arrow.svg' ); ?></a>
	</div>
</div>