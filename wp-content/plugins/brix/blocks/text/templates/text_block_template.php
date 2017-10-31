<?php

$block_content  = '';

if ( isset( $data->data->content ) ) {
	$block_content = $data->data->content;
}

?>

<?php if ( ! empty( $block_content ) ) : ?>
	<div class="brix-block-content"><?php echo brix_format_text_content( $block_content, array(
		'shortcodes' => false
	) ); ?></div>
<?php endif; ?>