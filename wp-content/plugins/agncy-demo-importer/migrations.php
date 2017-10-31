<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Delete attachment duplicates.
 *
 * @since 1.0.0
 */
function agncy_demo_install_delete_duplicates() {
	/* Cleaning up. */
	$posts_to_delete = get_posts( array(
		'post_type'      => 'attachment',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_key'       => '_ev_dummy_to_delete'
	) );

	if ( empty( $posts_to_delete ) ) {
		return;
	}

	foreach ( $posts_to_delete as $post ) {
		wp_delete_attachment( $post->ID, true );
	}

	global $wpdb;
	$wpdb->query( "delete from {$wpdb->postmeta} where meta_key = '_ev_original_post_id'" );
	$wpdb->query( "delete from {$wpdb->termmeta} where meta_key = '_ev_original_post_id'" );
}

/**
 * Migrate media in posts and pages.
 *
 * @since 1.0.0
 * @param boolean $options_imported True if global options have been imported.
 * @param boolean $mods_imported True if style mods have been imported.
 * @param boolean $dummy_imported True if XML data has been imported.
 */
function agncy_demo_install_migrate_media( $options_imported, $mods_imported, $dummy_imported ) {
	if ( ! $dummy_imported ) {
		return;
	}

	/* Get the media conversion array. */
	$attachments_migration = agncy_demo_map_rewritten_attachments();

	if ( ! empty( $attachments_migration ) ) {
		/* Migrate pages built with Brix. */
		agncy_demo_install_migrate_brix_data( $attachments_migration );
	}

	/* Project data. */
	agncy_demo_install_migrate_project_slides_data();

	/* Cleaning up. */
	global $wpdb;
	$wpdb->query( "delete from {$wpdb->postmeta} where meta_key = '_ev_dummy_to_process'" );

	/* Menu migration. */
	$migration = agncy_demo_map_rewritten_menus();

	if ( ! empty( $migration ) ) {
		/* Migrate menu associations. */
		agncy_demo_install_migrate_menus( $migration, false );
	}

	/* Cleaning up. */
	global $wpdb;
	$wpdb->query( "delete from {$wpdb->termmeta} where meta_key = '_ev_dummy_to_process'" );

	agncy_demo_install_delete_duplicates();
}

add_action( 'agncy_demo_installed', 'agncy_demo_install_migrate_media', 10, 3 );

/**
 * Return a map of attachment IDs that have been changed during the installation
 * process.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_demo_map_rewritten_attachments() {
	$migration = array();

	global $wpdb;

	$duplicates = $wpdb->get_results( "select post_content, group_concat(`ID` separator ',') as ids from {$wpdb->posts} where post_type = 'attachment' group by post_content having count( post_content ) > 1" );

	foreach ( $duplicates as $duplicate ) {
		$ids = explode( ',', $duplicate->ids );
		sort( $ids );

		$original_post_id = array_shift( $ids );

		if ( ! isset( $migration[ $original_post_id ] ) ) {
			$migration[ $original_post_id ] = array();
		}

		foreach( $ids as $id ) {
			$_id = get_post_meta( $id, '_ev_original_post_id', true );

			// $migration[ $original_post_id ][] = $id;

			if ( $_id ) {
				update_post_meta( $_id, '_ev_dummy_to_delete', '1' );

				$migration[ $original_post_id ][] = $_id;
			}
		}
	}

	$other_mappings = $wpdb->get_results( "select ID, meta_value from {$wpdb->posts},{$wpdb->postmeta} where post_type = 'attachment' and meta_key = '_ev_original_post_id' and post_id = ID and ID != meta_value" );

	foreach( $other_mappings as $mapping ) {
		$original_post_id = $mapping->ID;

		if ( ! isset( $migration[ $original_post_id ] ) ) {
			$migration[ $original_post_id ] = array();
		}

		$migration[ $original_post_id ][] = $mapping->meta_value;
	}

	asort( $migration );
	$migration = array_reverse( $migration, true );

	return $migration;
}

/**
 * Return a map of menu IDs that have been changed during the installation
 * process.
 *
 * @since 1.0.0
 * @return array
 */
function agncy_demo_map_rewritten_menus() {
	$rewritten_menus = get_terms( array(
		'taxonomy'   => 'nav_menu',
		'number'     => 0,
		'meta_query'	 => array(
			'relation' => 'AND',
			array(
				'key' => '_ev_original_post_id',
				'value' => '',
				'compare' => '!='
			),
			array(
				'key' => '_ev_dummy_to_process',
				'value' => '',
				'compare' => '!='
			)
		),
		'hide_empty' => false
	) );

	if ( empty( $rewritten_menus ) ) {
		return;
	}

	$migration = array();

	foreach ( $rewritten_menus as $menu ) {
		$migration[ get_term_meta( $menu->term_id, '_ev_original_post_id', true ) ] = $menu->term_id;
	}

	return $migration;
}

/**
 * Migrate Brix data in posts and pages.
 *
 * @since 1.0.0
 * @param array $migration The migration data.
 */
function agncy_demo_install_migrate_brix_data( $migration ) {
	if ( ! defined( 'BRIX' ) ) {
		return;
	}

	if ( empty( $migration ) ) {
		return;
	}

	$posts_to_process = get_posts( array(
		'post_type'      => BrixBuilder::instance()->_get_screens(),
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_query'	 => array(
			'relation' => 'AND',
			array(
				'key' => '_ev_dummy_to_process',
				'value' => '1',
				'compare' => '='
			),
			array(
				'key' => '_brix_used',
				'value' => '1',
				'compare' => '='
			)
		),
	) );

	if ( empty( $posts_to_process ) ) {
		return;
	}

	$post_mapping = array();

	$posts_to_remap = get_posts( array(
		'post_type'      => 'any',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_query'	 => array(
			'relation' => 'AND',
			array(
				'key' => '_ev_original_post_id',
			),
		),
	) );

	foreach ( $posts_to_remap as $post ) {
		$post_mapping[ get_post_meta( $post->ID, '_ev_original_post_id', true ) ] = $post->ID;
	}

	$post_mapping = array_unique( $post_mapping );

	foreach ( $posts_to_process as $post ) {
		$data = BrixBuilder::instance()->get_data( $post->ID, true );
		$data = base64_decode( $data );

		/* Media. */
		foreach ( $migration as $id => $old_id ) {
			$old_id = (array) $old_id;

			foreach ( $old_id as $_oid ) {
				/* Generic media attachments. */
				$data = str_replace( '"desktop":["",{"id":"' . $_oid . '","image_size"', '"desktop":["",{"id":"' . $id . '","image_size"', $data );

				/* Gallery media attachments. */
				$data = str_replace( '{\"gallery_item_id\":' . $_oid . ',\"source\":\"media\"', '{\"gallery_item_id\":' . $id . ',\"source\":\"media\"', $data );
			}
		}

		/* Posts mapping. */
		foreach ( $post_mapping as $old_id => $id ) {
			/* Links */
			$data = str_replace( '"link":{"url":"' . $old_id . '","target":""', '"link":{"url":"' . $id . '","target":""', $data );
		}

		$data = base64_encode( $data );
		update_post_meta( $post->ID, '_brix', $data );

		$brix = BrixBuilder::instance();

		wp_update_post( array(
			'ID' => $post->ID,
			'post_content' => call_user_func_array( array( $brix, 'stringify' ), array( $post->ID ) )
		) );
	}
}

/**
 * Migrate project data in posts and pages.
 *
 * @since 1.0.0
 * @param array $migration The migration data.
 */
function agncy_demo_install_migrate_project_slides_data() {
	if ( empty( $migration ) ) {
		return;
	}

	$posts_to_process = get_posts( array(
		'post_type'      => 'any',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_query'	 => array(
			'relation' => 'AND',
			array(
				'key' => 'agncy_slide',
				'value' => '1',
				'compare' => '='
			),
		),
	) );

	if ( empty( $posts_to_process ) ) {
		return;
	}

	$post_mapping = array();

	$posts_to_remap = get_posts( array(
		'post_type'      => 'any',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_query'	 => array(
			'relation' => 'AND',
			array(
				'key' => '_ev_original_post_id',
			),
		),
	) );

	foreach ( $posts_to_remap as $post ) {
		$post_mapping[ get_post_meta( $post->ID, '_ev_original_post_id', true ) ] = $post->ID;
	}

	$post_mapping = array_unique( $post_mapping );

	foreach ( $posts_to_process as $post ) {
		$slides = (array) get_post_meta( $post->ID, 'agncy_slide', true );

		foreach ( $slides as $i => $slide ) {
			foreach ( $post_mapping as $old_id => $id ) {
				if ( isset( $slide[ 'ref_id' ] ) && $slide[ 'ref_id' ] == $old_id ) {
					$slides[ $i ][ 'ref_id' ] = $id;
				}
			}

			update_post_meta( $post->ID, 'agncy_slide', $slides );
		}
	}
}

/**
 * Re-save the customizer file upon importing the demo content.
 *
 * @since 1.0.0
 * @param boolean $options_imported True if global options have been imported.
 * @param boolean $mods_imported True if style mods have been imported.
 * @param boolean $dummy_imported True if XML data has been imported.
 */
function agncy_demo_install_migrate_mods( $options_imported, $mods_imported, $dummy_imported ) {
	if ( ! $mods_imported ) {
		return;
	}

	if ( function_exists( 'agncy_customizer_saved' ) ) {
		agncy_customizer_saved();
	}
}

add_action( 'agncy_demo_installed', 'agncy_demo_install_migrate_mods', 10, 3 );

/**
 * Migrate menu associations.
 *
 * @since 1.0.0
 * @param array $migration The migration data.
 * @param array $attachments_migration The migration data of attachments.
 */
function agncy_demo_install_migrate_menus( $migration, $attachments_migration ) {
	$menu_widgets = get_option( 'widget_nav_menu' );
	$original_menus = array_keys( $migration );

	foreach ( $menu_widgets as $i => $menu ) {
		if ( isset( $menu[ 'nav_menu' ] ) && in_array( $menu[ 'nav_menu' ], $original_menus ) ) {
			$menu_widgets[ $i ][ 'nav_menu' ] = $migration[ $menu[ 'nav_menu' ] ];
		}
	}

	update_option( 'widget_nav_menu', $menu_widgets );

	/* Location association. */
	$theme_mods = get_theme_mods();

	if ( isset( $theme_mods[ 'nav_menu_locations' ] ) && ! empty( $theme_mods[ 'nav_menu_locations' ] ) ) {
		foreach ( $theme_mods[ 'nav_menu_locations' ] as $location => $menu_id ) {
			if ( isset( $migration[ $menu_id ] ) ) {
				$theme_mods[ 'nav_menu_locations' ][ $location ] = $migration[ $menu_id ];
			}
		}

		set_theme_mod( 'nav_menu_locations', $theme_mods[ 'nav_menu_locations' ] );
	}

	/* Attachments. */
	if ( ! empty( $attachments_migration ) ) {
		$items_to_process = get_posts( array(
			'post_type'      => 'any',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'meta_key'       => 'menu-item-agncy_background_image'
		) );

		if ( empty( $items_to_process ) ) {
			return;
		}

		foreach ( $items_to_process as $item ) {
			$data = get_post_meta( $item->ID, 'menu-item-agncy_background_image', true );

			if ( isset( $data[ 'desktop' ] ) ) {
				foreach ( $attachments_migration as $id => $old_id ) {
					$old_id = (array) $old_id;

					foreach ( $old_id as $_oid ) {
						if ( $data[ 'desktop' ][ 1 ][ 'id' ] == $_oid ) {
							$data[ 'desktop' ][ 1 ][ 'id' ] = $id;

							update_post_meta( $item->ID, 'menu-item-agncy_background_image', $data );
							break;
						}
					}
				}
			}

		}
	}
}