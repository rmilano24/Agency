<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Output the controls for the row editing toolbar
 *
 * @since 1.1.3
 */
function brix_section_row_layout_vertical_alignment_controls() {
?>
	<button type="button" class="brix-section-row-edit-responsive brix-tooltip" title="<?php echo esc_html( __( 'Responsive', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Responsive', 'brix' ) ); ?></span></button>
	<button type="button" class="brix-section-row-back-to-layout brix-tooltip" title="<?php echo esc_html( __( 'Back to row layout', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Back to row layout', 'brix' ) ); ?></span></button>
<?php
}

add_action( 'brix_section_row_layout_vertical_alignment-controls', 'brix_section_row_layout_vertical_alignment_controls' );

/**
 * Output the controls for the row editing toolbar
 *
 * @since 1.1.3
 */
function brix_section_row_layout_change_controls() {
?>
	<button type="button" class="brix-section-row-edit-vertical-alignment brix-tooltip" title="<?php echo esc_attr( __( 'Vertical alignment', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Vertical alignment', 'brix' ) ); ?></span></button>
	<button type="button" class="brix-section-row-edit-responsive brix-tooltip" title="<?php echo esc_attr( __( 'Responsive', 'brix' ) ); ?>"><span class="screen-reader-text"><?php echo esc_html( __( 'Responsive', 'brix' ) ); ?></span></button>
<?php
}

add_action( 'brix_section_row_layout_change_controls', 'brix_section_row_layout_change_controls' );