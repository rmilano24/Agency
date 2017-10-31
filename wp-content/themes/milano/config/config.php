<?php

/**
 * Main theme constant. Used by custom components to avoid running certain parts
 * of functionality if the current theme isn't Agncy.
 */
define( 'AGNCY_THEME_ACTIVE', true );

/* Configuration base folder path. */
$config_base_folder = trailingslashit( get_template_directory() . '/config' );

/* Includes folder path. */
$inc_folder = trailingslashit( get_template_directory() . '/inc' );

/* Templates folder path. */
$templates_folder = trailingslashit( get_template_directory() . '/templates' );

/* Global functionalities and theme setup. */
require_once $config_base_folder . 'global-functionalities.php';

/* About/what's new page. */
require_once $config_base_folder . 'about/about.php';

/* Plugins configuration. */
require_once $config_base_folder . 'plugins/plugins.php';

/* Admin utilities. */
require_once $inc_folder . 'admin.php';

/* Inc. */
require_once $inc_folder . 'editor.php';
require_once $inc_folder . 'helpers.php';
require_once $inc_folder . 'installer/installer.php';
require_once $inc_folder . 'updater/updater.php';
require_once $inc_folder . 'layout.php';
require_once $inc_folder . 'sidebars.php';
require_once $inc_folder . 'header.php';
require_once $inc_folder . 'page-header.php';
require_once $inc_folder . 'page-content.php';
require_once $inc_folder . 'footer.php';
require_once $inc_folder . 'blog.php';
require_once $inc_folder . 'single-post.php';
require_once $inc_folder . 'comments.php';
require_once $inc_folder . 'images.php';
require_once $inc_folder . 'projects.php';

/* Customizer. */
require_once $config_base_folder . 'customize/customize.php';
require_once $inc_folder . 'customizer/customizer.php';

/* Options */
require_once $config_base_folder . 'options.php';

/* Widgets */
require_once $config_base_folder . 'widgets.php';

/* Templates. */
require_once $templates_folder . 'header/header.php';
require_once $templates_folder . 'page-header/page-header.php';
require_once $templates_folder . 'page-content/page-content.php';

/* Brix. */
require_once $config_base_folder . 'brix/brix.php';