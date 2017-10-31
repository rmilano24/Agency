<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Return a bytes value for a size string.
 *
 * @since 1.0.0
 * @param string $val The size string.
 * @return integer
 */
function brix_return_bytes( $val ) {
	$val = trim( $val );
	$last = strtolower( $val[strlen( $val ) - 1] );
	$val = intval( $val );

	switch( $last ) {
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}

	return absint( $val );
}

/**
 * Get the computed value of Max File Upload.
 *
 * @since 1.0.0
 * @return string
 */
function brix_max_file_upload_in_bytes() {
	$max_upload = brix_return_bytes( ini_get( 'upload_max_filesize' ) );
	$max_post = brix_return_bytes( ini_get( 'post_max_size' ) );
	$memory_limit = brix_return_bytes( ini_get( 'memory_limit' ) );

	$max_file_upload = min( $max_upload, $max_post, $memory_limit );
	$max_file_upload = ( $max_file_upload / (1024*1024) ) . 'M';

	return $max_file_upload;
}

/**
 * Output a status report for the plugin installation.
 *
 * @since 1.0.0
 */
function brix_registration_page_status_report() {
	global $wp_version;

	$theme = wp_get_theme();
	$active_plugins = get_option( 'active_plugins' );
	$all_plugins = get_plugins();

	echo '<pre id="brix-status-report">';
		echo 'STATUS REPORT:' . "\n\n";
		echo '* WordPress version: ' . esc_html( $wp_version ) . "\n";
		echo '* Theme: ' . esc_html( $theme->Name ) . ' (' . esc_html( $theme->Version ) . ")\n";

		if ( is_multisite() ) {
			echo '* Multisite: ON' . "\n";
		}

		echo '* On-site active plugins:' . "\n";

		foreach ( $active_plugins as $active_plugin ) {
			printf( "\t - %s (%s)\n",
				esc_html( $all_plugins[$active_plugin]['Name'] ),
				esc_html( $all_plugins[$active_plugin]['Version'] )
			);
		}

		if ( is_multisite() ) {
			$network_active_plugins = get_site_option( 'active_sitewide_plugins' );

			echo '* Network active plugins:' . "\n";

			foreach ( $network_active_plugins as $active_plugin => $last_updated ) {
				printf( "\t - %s (%s)\n",
					esc_html( $all_plugins[$active_plugin]['Name'] ),
					esc_html( $all_plugins[$active_plugin]['Version'] )
				);
			}
		}

		$memory = WP_MEMORY_LIMIT;

		if ( function_exists( 'memory_get_usage' ) ) {
			$system_memory = @ini_get( 'memory_limit' );
			$memory        = max( $memory, $system_memory );
		}

		echo '* Server: ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
		echo '* PHP version: ' . phpversion() . "\n";
		echo '* Memory limit: ' . $memory . "\n";
		echo '* Max file upload: ' . brix_max_file_upload_in_bytes() . "\n";
		// echo '* Settings:' . "\n";

		// 	$options = array(
		// 		'container',
		// 		'gutter',
		// 		'baseline',
		// 		'responsive_breakpoints',
		// 	);

		// 	foreach ( $options as $option ) {
		// 		$value = brix_get_option( $option );

		// 		if ( is_array( $value ) ) {
		// 			$value = json_encode( $value );
		// 		}

		// 		printf( "\t - %s: %s\n",
		// 			esc_html( $option ),
		// 			esc_html( $value )
		// 		);
		// 	}

	echo '</pre>';
}

/**
 * Render the Support page content.
 *
 * @since 1.1.2
 */
function brix_support_page() {
	echo '<div class="brix-admin-page-content">';
		brix_support_page_content();
	echo '</div>';
}

add_action( 'brix_admin_page_content[page:brix_support]', 'brix_support_page' );

/**
 * Render the Support page.
 *
 * @since 1.1.2
 */
function brix_support_page_content() {
	?>
		<div class="brix-admin-page-col brix-admin-page-col-left">
			<div class="brix-admin-page-content-block">
				<h2><?php esc_html_e( 'Get support', 'brix' ); ?></h2>
				<p>
					<?php
						echo wp_kses_post(
							sprintf( __( 'We regularly check the compatibility of our products with other components such as themes and plugins, and it is our care to fix bugs and anomalies directly referrable to Brix. You can read our support policy <a href="%s" target="_blank" rel="noreferrer noopener">here</a>.', 'brix' ),
								esc_attr( 'https://justevolve.it/support-and-license-policy/' )
							)
						);
					?>
				</p>
				<p>
					<?php
						echo wp_kses_post(
							sprintf( __( 'Support is provided on the <a href="%s" target="_blank" rel="noreferrer noopener">Evolve Shop</a> through our ticketing system: on your dashboard, look for the "Support" section, from where you\'ll be able to browse tickets or create one.', 'brix' ),
								esc_attr( 'https://justevolve.it' )
							)
						);
					?>
				</p>
				<a href="https://justevolve.it/" target="_blank" rel="noreferrer noopener" class="brix-btn brix-action-btn brix-btn-size-medium brix-btn-style-button"><?php esc_html_e( 'Go to your dashboard', 'brix' ); ?></a>
			</div>
		</div>

		<div class="brix-admin-page-col brix-admin-page-col-right">
			<div class="brix-admin-page-content-block">
				<!-- <h3><?php esc_html_e( 'System status report', 'brix' ); ?></h3> -->

				<div class="brix-system-status-wrapper">
					<div class="brix-system-status">
						<?php brix_registration_page_status_report(); ?>
					</div>
					<div class="brix-system-status-desc-wrapper">
						<h4><?php esc_html_e( 'Why does this matter?', 'brix' ); ?></h4>
						<p><?php esc_html_e( 'This report is a partial snapshot of your installation. Pasting the contents of the Status Report in support tickets may help to understand what\'s going on with your copy of Brix.', 'brix' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	<?php
}