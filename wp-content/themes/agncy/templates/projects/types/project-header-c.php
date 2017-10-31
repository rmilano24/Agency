<?php
$id              = get_the_ID();
$prj_sub_heading = agncy_get_project_subtitle();
$prj_img_size	 = get_post_meta( $id, 'header_featured_image_size', true );
$prj_img         = ev_get_featured_image( $prj_img_size, $id );
$prj_color       = '';
$prj_video 		 = get_post_meta( $id, 'video', true );
$prj_use_video   = (bool) get_post_meta( $id, 'use_video', true ) && $prj_video;

?>
<div class="agncy-ph-sp">

	<div class="agncy-ph-sp-fi_cloned" style="background-image:url(<?php echo esc_attr( $prj_img ); ?>)"></div>

	<div class="agncy-ph-sp-fi_w <?php echo esc_attr( $prj_use_video ? 'agncy-ph-sp-fi-video' : '' ); ?>">
		<?php if ( $prj_use_video ) : ?>
			<a href="<?php echo esc_attr( $prj_video ); ?>">
		<?php endif; ?>

		<div class="agncy-ph-sp-fi" style="background-image:url(<?php echo esc_attr( $prj_img ); ?>)"></div>

		<?php if ( $prj_use_video ) : ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="agncy-ph-sp-t_w">
		<div class="agncy-ph-sp-t_w-i">
			<?php if ( $prj_sub_heading ) : ?>
				<p><?php print $prj_sub_heading; ?></p>
			<?php endif; ?>

			<h1><?php the_title(); ?></h1>

			<span class="agncy-ph-sp-scroll"><?php echo agncy_load_svg( 'img/arrow.svg' ); ?></span>
		</div>
	</div>

</div>

<?php if ( $prj_use_video ) : ?>
	<script type="text/x-template" id="agncy-fi-video">
		<?php
			if ( agncy_projects_slideshow_is_self_hosted_video( $prj_video ) ) {
				$video = sprintf( '<video src="%s" loop></video>',
					esc_attr( $prj_video )
				);
			}
			else {
				$video = wp_oembed_get( $prj_video );
			}

			print $video;
		?>
	</script>
<?php endif; ?>