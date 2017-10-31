<?php

/**
 * Creation of the social options page.
 *
 * @since 1.0.0
 */
function agncy_social_option_page() {
    if ( ! function_exists( 'ev_fw' ) ) {
        return;
    }

	$args = array(
		'group' => 'agncy',
		'vertical' => true
	);

	$fields = array();

	$social_fields = array(
		'type'   => 'group',
		'label'  => __( 'Global', 'agncy' ),
		'handle' => '_global_social',
		'fields' => array(
			array(
				'type' => 'divider',
				'text' => __( 'Social', 'agncy' )
			),
			array(
				'type' => 'bundle',
				'label' => array(
					'type' => 'hidden',
					'text' => __( 'Social', 'agncy' ),
				),
				'handle' => 'social_fields',
				'fields' => agncy_social_networks_fields(),
			),

		)
	);

	$social_fields['fields'] = apply_filters( 'agncy_social_fields', $social_fields['fields'] );
	$fields[] = $social_fields;

	ev_fw()->admin()->add_submenu_page( 'agncy', 'agncy-social', __( 'Social', 'agncy' ), $fields, $args );
}

add_action( 'init', 'agncy_social_option_page' );

/**
 * Return a list of social networks.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_social_networks() {
    $socials = array(
		'dribbble'   => 'Dribbble',
		'behance'    => 'Behance',
		'youtube'    => 'YouTube',
		'twitter'    => 'Twitter',
		'vimeo'      => 'Vimeo',
		'facebook'   => 'Facebook',
		'googleplus' => 'GooglePlus',
		'linkedin'   => 'LinkedIn',
		'pinterest'  => 'Pinterest',
		'instagram'  => 'Instagram',
		'flickr'     => 'Flickr',
		'skype'      => 'Skype',
		'deviantart' => 'Deviantart',
		'picasa'     => 'Picasa',
	);

	$socials = apply_filters( 'agncy_social_networks', $socials );

    return $socials;
}

/**
 * The list of the social networks
 *
 * @since 1.0.0
 * @return array
 */
function agncy_social_networks_fields() {
	$socials = agncy_social_networks();

	foreach ( $socials as $social => $label ) {
		$fields[] = array(
			'type' => 'text',
			'handle' => $social,
			'help' => __( 'Full URL for the social network profile.', 'agncy' ),
			'label' => $label,
			'config' => array(
				'full' => true
			)
		);
	};

	return $fields;
}