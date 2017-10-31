<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/* Set to true to manage user roles. */
define( 'BRIX_USER_ROLES_MANAGEMENT', true );

/**
 * Return a list of options to be displayed in the User Roles options screen.
 *
 * @since 1.0.0
 * @return array
 */
function brix_user_roles_options( $options ) {
	if ( ! class_exists( 'Brix_Framework' ) ) {
		return array();
	}

	global $wp_roles;

	$roles_names = $wp_roles->get_names();

	if ( isset( $roles_names['administrator'] ) ) {
		unset( $roles_names['administrator'] );
	}

	$options[] = array(
		'type' => 'group',
		'handle' => 'brix-user-roles-options',
		'label' => _x( 'User roles', 'user roles options', 'brix' ),
		'fields' => array(
			array(
				'handle' => 'global_user_roles',
				'text'  => __( 'User roles', 'brix' ),
				'type'   => 'divider',
				'config' => array(
					'style' => 'in_page'
				)
			),
			array(
				'handle' => 'user_roles',
				'label'  => __( 'Additional roles that can create content with Brix', 'brix' ),
				'type'   => 'multiple_select',
				'help' => __( 'Administrators have access by default.', 'brix' ),
				'config' => array(
					'vertical' => true,
					'data' => $roles_names
				),
				'default' => 'administrator'
			),
		),
	);

	return $options;
}

add_filter( 'brix_global_options', 'brix_user_roles_options' );