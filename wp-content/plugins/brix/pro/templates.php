<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Templates actions.
 *
 * @since 1.1.3
 */
function brix_pro_templates_actions() {
	printf( '<a class="brix-templates-action brix-templates-export" href="%s">%s</a>',
		admin_url( '/?brix_export_templates&_wpnonce=' . wp_create_nonce( 'brix_export_templates' ) ),
		esc_html__( 'Export all', 'brix' )
	);
	echo '<a class="brix-templates-action brix-templates-import" href="#">' . esc_html( __( 'Import', 'brix' ) ) . '</a>';
}

add_action( 'brix_templates_actions', 'brix_pro_templates_actions' );

/**
 * Print controls for the single user template.
 *
 * @since 1.1.3
 * @param array $template Template data.
 */
function brix_pro_user_template_toolbar( $template ) {
	printf( '<a class="brix-export-builder-template brix-templates-action brix-templates-export" href="%s"><span class="screen-reader-text">%s</span></a>',
		admin_url( '/?brix_export_templates&name=' . $template['id'] . '&_wpnonce=' . wp_create_nonce( 'brix_export_templates' ) ),
		esc_html__( 'Export', 'brix' )
	);
}

add_action( 'brix_user_template_toolbar', 'brix_pro_user_template_toolbar' );