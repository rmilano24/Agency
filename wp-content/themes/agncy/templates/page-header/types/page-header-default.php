<?php
$disable_page_header = get_post_meta( get_the_ID(), 'agncy_disable_page_header', false );

if ( isset( $disable_page_header[0] ) && $disable_page_header[0] == '1' ) {
	return;
}
?>

<div class="agncy-ph">
	<div class="agncy-ph_wi">
		<?php agncy_page_before_title(); ?>

		<div class="agncy-ph-t_w">
			<?php agncy_page_title(); ?>
		</div>
	</div>
</div>

<?php do_action( 'agncy_after_page_header' ); ?>