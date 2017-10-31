<?php

$status = agncy_system_status();

$agncy_plugins = array();
$agncy_active_plugins = get_option( 'active_plugins' );
$agncy_all_plugins = get_plugins();

foreach ( $agncy_active_plugins as $active_plugin ) {
	$agncy_plugins[ $agncy_all_plugins[$active_plugin]['Name'] ] = $agncy_all_plugins[$active_plugin]['Version'];
}

if ( is_multisite() ) {
	$agncy_network_active_plugins = get_site_option( 'active_sitewide_plugins' );

	foreach ( $agncy_network_active_plugins as $active_plugin => $last_updated ) {
		$agncy_plugins[ $agncy_all_plugins[$active_plugin]['Name'] ] = $agncy_all_plugins[$active_plugin]['Version'];
	}
}

/**
 * Print a status row.
 *
 * @since 1.0.0
 * @param array $status The system status array.
 * @param string $label The row label.
 * @param string $key The system status setting key.
 * @return
 */
function agncy_print_status_row( $status, $label, $key ) {
	$value = $status[$key]['value'];
	$warning = isset( $status[$key]['warning'] ) ? $status[$key]['warning'] : false;
	$row_class = $warning ? 'agncy-ap-ss-warning' : '';

	printf( '<tr class="%s">', esc_attr( $row_class ) );
		echo '<td>';
			printf( '<div class="agncy-ap-ss-r-l">%s</div>', esc_html( $label ) );
			printf( '<div class="agncy-ap-ss-r-v">%s</div>', esc_html( $value ) );

			if ( $warning ) {
				printf( '<div class="agncy-ap-ss-r-m">%s</div>', wp_kses_post( $warning ) );
			}
		echo '</td>';
	echo '</tr>';
}

?>

<div class="agncy-ap-mc_w">
	<table class="agncy-ap-ss-t_w">
		<thead>
			<tr>
				<th colspan="2"><?php esc_html_e( 'WordPress environment', 'agncy' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php agncy_print_status_row( $status, __( 'WP version:', 'agncy' ), 'wp_version' ); ?>
			<?php agncy_print_status_row( $status, __( 'WP Multisite:', 'agncy' ), 'wp_multisite' ); ?>
			<?php agncy_print_status_row( $status, __( 'WP memory limit:', 'agncy' ), 'wp_memory_limit' ); ?>
			<?php agncy_print_status_row( $status, __( 'WP debug mode:', 'agncy' ), 'wp_debug' ); ?>
		</tbody>
	</table>

	<table class="agncy-ap-ss-t_w">
		<thead>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Server environment', 'agncy' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php agncy_print_status_row( $status, __( 'Server info:', 'agncy' ), 'server' ); ?>
			<?php agncy_print_status_row( $status, __( 'PHP version:', 'agncy' ), 'php_version' ); ?>
			<?php agncy_print_status_row( $status, __( 'PHP Post Max Size:', 'agncy' ), 'php_post_max_size' ); ?>
			<?php agncy_print_status_row( $status, __( 'PHP Time Limit:', 'agncy' ), 'php_time_limit' ); ?>
			<?php agncy_print_status_row( $status, __( 'PHP Max Input Vars:', 'agncy' ), 'php_max_input_vars' ); ?>
			<?php agncy_print_status_row( $status, __( 'Max Upload Size:', 'agncy' ), 'php_max_file_upload' ); ?>
		</tbody>
	</table>

	<table class="agncy-ap-ss-t_w">
		<thead>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Active plugins', 'agncy' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $agncy_plugins as $name => $version ) : ?>
				<tr>
					<td>
						<div class="agncy-ap-ss-r-l"><?php echo esc_html( $name ); ?></div>
						<div class="agncy-ap-ss-r-v"><?php echo esc_html( $version ); ?></div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<table class="agncy-ap-ss-t_w">
		<thead>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Theme', 'agncy' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php agncy_print_status_row( $status, __( 'Version:', 'agncy' ), 'theme_version' ); ?>
			<?php agncy_print_status_row( $status, __( 'Child theme:', 'agncy' ), 'theme_child' ); ?>
		</tbody>
	</table>
</div>
<div class="agncy-ap-sc_w">
	<p><?php echo wp_kses_post( __( 'The System Status report helps with troubleshooting issues with your site and allows to have a clear view over software versions, server settings and WordPress conifiguration.', 'agncy' ) ); ?></p>
	<p><?php echo wp_kses_post( __( 'Keep in mind that some of the issues that may arise can be solved on your own (following the indications provided), while for others it is best to talk to your host provider directly.', 'agncy' ) ); ?></p>
</div>