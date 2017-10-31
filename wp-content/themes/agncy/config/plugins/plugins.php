<?php

/**
 * Array of plugin arrays. Required keys are name and slug.
 * If the source is NOT from the .org repo, then source is also required.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_plugins_configuration() {
	$plugins = array(
		array(
			'name'     => 'Evolve Framework',
			'slug'     => 'evolve-framework',
			'source'   => 'https://github.com/Justevolve/evolve-framework/archive/1.0.8.zip',
			'required' => true,
			'version'  => '1.0.8',
			'path'     => 'evolve-framework/evolve-framework.php'
		),
		array(
			'name'     => 'Agncy Companion Plugin',
			'slug'     => 'agncy-companion-plugin',
			'source'   => get_template_directory() . '/config/plugins/agncy-companion-plugin.zip',
			'required' => true,
			'version'  => '1.0.0',
			'path'     => 'agncy-companion-plugin/agncy-companion-plugin.php'
		),
		array(
			'name'     => 'Agncy Demo Importer',
			'slug'     => 'agncy-demo-importer',
			'source'   => get_template_directory() . '/config/plugins/agncy-demo-importer.zip',
			'required' => false,
			'version'  => '1.0.0',
			'path'     => 'agncy-demo-importer/agncy-demo-importer.php'
		),
		array(
			'name'     => 'Brix Page Builder',
			'slug'     => 'brix',
			'source'   => get_template_directory() . '/config/plugins/brix.zip',
			'required' => true,
			'version'  => '1.2.15',
			'path'     => 'brix/brix.php'
		),
	);

	return $plugins;
}

/* Inclusing the TGMPA class. */
require_once trailingslashit( get_template_directory() . '/inc/installer' ) . 'class-tgm-plugin-activation.php';

/**
 * Register the required plugins for this theme.
 *
 * @since 1.0.0
 */
function agncy_register_required_plugins() {
	/* Retrieve plugins configuration. */
	$plugins = agncy_plugins_configuration();

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'agncy',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'agncy',
		'has_notices'  => false,                   // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                    // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'agncy_register_required_plugins' );