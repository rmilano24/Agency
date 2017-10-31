<h3><?php esc_html_e( 'Nothing Found', 'agncy' ); ?></h3>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	<p><?php printf( wp_kses_post( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'agncy' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
<?php endif; ?>