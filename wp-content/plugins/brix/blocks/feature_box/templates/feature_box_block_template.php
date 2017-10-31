<?php

$layout      = '';
$icon_type   = '';
$icon        = '';
$icon_color  = '';
$image       = '';
$content     = '';
$icon_style  = array();
$image_style = '';
$decoration_html = '';

if ( isset( $data->data->decoration ) ) {
	$decoration_html = brix_get_decoration( $data->data->decoration );
}

if ( isset( $data->data->content ) && ! empty( $data->data->content ) ) {
	$content = $data->data->content;
}

if ( isset( $data->data->layout ) && ! empty( $data->data->layout ) ) {
	$layout = $data->data->layout;
}

?>

<?php echo $decoration_html; ?>

<?php if ( ! empty( $content ) ) : ?>
	<div class="brix-block-feature-box-content-wrapper">
		<div class="brix-block-content">
			<?php echo brix_format_text_content( $content ); ?>
		</div>
	</div>
<?php endif; ?>