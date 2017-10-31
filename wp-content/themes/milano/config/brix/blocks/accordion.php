<?php

/**
 * Return the toggle icon for the accordion builder block
 *
 * @since 1.0.0
 * @param  stdClass $data The block data.
 * @return string
 */
function agncy_brix_accordion_toggle_icon() {
	echo '<span class="agncy-ba-t">' . agncy_load_svg( 'img/chevron.svg' ) . '</span>';
}

add_action( 'brix_accordion_before_toggle_trigger_label', 'agncy_brix_accordion_toggle_icon' );