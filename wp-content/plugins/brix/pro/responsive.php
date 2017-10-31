<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/* Add a breakpoint for tablets. */
brix_add_breakpoint( 1, 'tablet', _x( 'Tablet', 'responsive breakpoint', 'brix' ), 'tablet', '@media screen and (max-width: 1024px)' );

/**
 * Add breakpoints controls to the main configuration page.
 *
 * @since 1.1.3
 */
function brix_breakpoints_controls() {
	?>
	<div class="brix-breakpoints-add">
		<?php
			brix_btn( __( 'Define a new breakpoint', 'brix' ), 'action', array(
				'attrs' => array(
					'class' => 'brix-breakpoint-add',
				),
				'size' => 'small',
			) );
		?>
	</div>
	<?php
}

add_action( 'brix_breakpoints_controls', 'brix_breakpoints_controls' );