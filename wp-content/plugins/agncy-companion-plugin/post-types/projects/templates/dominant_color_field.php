<?php
	$value = $field->value();
	$handle = $field->handle();
?>

<input type="text" data-dominant name="<?php echo esc_attr( $handle ); ?>" value="<?php echo esc_attr( $value ); ?>">
<div data-dominant-preview></div>

<ul data-dominant-palette></ul>