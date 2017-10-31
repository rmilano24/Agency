<?php
$skin = '';
if ( function_exists( 'ev_get_option' ) ) {
	$skin = ev_get_option( 'footer_layout_skin' );
}
$classes = 'agncy-f';

if ( empty( $skin ) ) {
	$skin = 'light';
}

if ( $skin ) {
	$classes .= ' agncy-f-skin-' . $skin;
}
?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo agncy_footer_layout(); ?>
</div>