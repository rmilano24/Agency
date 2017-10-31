<div class="agncy-sm_w">
	<form action="" method="post" id="agncy-register-widget-area">
		<h3><?php esc_html_e( 'Register a widget area', 'agncy-companion-plugin' ); ?></h3>

		<?php wp_nonce_field( 'agncy_register_sidebar' ); ?>
		<input type="text" name="name" placeholder="<?php esc_attr_e( 'Insert the name of the widget area', 'agncy-companion-plugin' ); ?>" size="50">
		<button class="agncy-btn"><?php esc_html_e( 'Register', 'agncy-companion-plugin' ); ?></button>
	</form>

	<?php
		$sidebars = agncy_get_sidebars();
	?>

	<?php if ( ! empty( $sidebars ) ) : ?>
		<form action="" method="post" id="agncy-remove-widget-area">
			<h3><?php esc_html_e( 'User-registered widget areas', 'agncy-companion-plugin' ); ?></h3>
			<?php wp_nonce_field( 'agncy_remove_sidebar' ); ?>

			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Name', 'agncy-companion-plugin' ) ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $sidebars as $id => $name ) : ?>
						<tr>
							<td class="agncy-sn"><?php echo esc_html( $name ); ?></td>
							<td>
								<button class="agncy-btn agncy-remove" name="sidebar_id" value="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Remove', 'agncy-companion-plugin' ) ?></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</form>
	<?php endif; ?>
</div>