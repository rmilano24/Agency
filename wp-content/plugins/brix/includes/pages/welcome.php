<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Render the plugin system status page.
 *
 * @since 1.0.0
 */
function brix_welcome_page() {
	ob_start();

	$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'brix-tab-panel-docs';

	echo '<div class="brix-admin-page-content">';
		echo '<div class="brix-welcome-section">';
			echo '<span class="brix-welcome-logo"></span>';
			printf( '<h2>%s</h2>', esc_html__( 'Welcome!', 'brix' ) );

			echo '<p>' . __( 'Here\'s some information to get you up and running.', 'brix' ) . '</p>';

			echo '<p>';
				printf( __( 'Don\' forget to register your copy in order to receive automatic updates and <a href="%s">start creating</a> with Brix right now!', 'brix' ),
					admin_url( 'post-new.php?post_type=page' )
				);
			echo '</p>';
		echo '</div>';

		echo '<div class="brix-tabs brix-component brix-docs-support-tabs" data-push="querystring">';
			echo '<div class="brix-tab-container brix-tab-container-loaded">';
				$active_class = $active_tab == 'brix-tab-panel-docs' ? 'brix-active' : '';
				printf( '<div aria-labelledby="brix-tab-docs" id="brix-tab-panel-docs" class="brix-tab %s" role="tabpanel">', esc_attr( $active_class ) );
					?>
					<div class="brix-admin-page-col brix-admin-page-col-left">
						<div class="brix-admin-page-content-block">
							<h2><?php esc_html_e( 'Documentation', 'brix' ); ?></h2>
							<p>
								<?php
									echo wp_kses_post(
										sprintf( __( 'The documentation is updated regularly and it is only available on the <a href="%s" target="_blank" rel="noreferrer noopener">Evolve Shop</a>.', 'brix' ),
											esc_attr( 'https://justevolve.it/docs/brix' )
										)
									);
								?>
							</p>
							<p>
								<?php
									echo wp_kses_post(
										sprintf( __( 'You can find more Brix tutorials and useful articles on the <a href="%s" target="_blank" rel="noreferrer noopener">official blog</a>, so make sure to follow its feed.', 'brix' ),
											esc_attr( 'https://www.brixbuilder.com/blog' )
										)
									);
								?>
							</p>
							<p>
								<?php
									echo wp_kses_post(
										sprintf( __( 'Subscribe to the <a href="%s" target="_blank" rel="noreferrer noopener">YouTube playlist</a> to be notified when a new tutorial is available.', 'brix' ),
											esc_attr( 'https://youtu.be/Ob67R_GShYU?list=PLP2JkJpnBbLg97GHGi_1rVLot1yu-yHh8' )
										)
									);
								?>
							</p>

							<a href="https://justevolve.it/docs/brix/" target="_blank" rel="noreferrer noopener" class="brix-btn brix-action-btn brix-btn-size-medium brix-btn-style-button"><?php esc_html_e( 'Read the online docs', 'brix' ); ?></a>
						</div>
					</div>

					<div class="brix-admin-page-col brix-admin-page-col-right">
						<div class="brix-admin-page-content-block">
							<h3><?php esc_html_e( 'Video tutorials', 'brix' ); ?></h3>
							<iframe width="640" height="360" src="https://www.youtube.com/embed/videoseries?list=PLP2JkJpnBbLg97GHGi_1rVLot1yu-yHh8" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
					<?php
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

	$content = ob_get_contents();
	ob_end_clean();

	$content = apply_filters( 'brix_welcome_page', $content );

	echo $content;
}

add_action( 'brix_admin_page_content[page:brix_welcome]', 'brix_welcome_page' );