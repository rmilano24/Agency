<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Render the Docs page content.
 *
 * @since 1.1.2
 */
function brix_docs_page() {
	echo '<div class="brix-admin-page-content">';
		brix_docs_page_content();
	echo '</div>';
}

add_action( 'brix_admin_page_content[page:brix_docs]', 'brix_docs_page' );

/**
 * Render the Docs page.
 *
 * @since 1.1.2
 */
function brix_docs_page_content() {
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
}