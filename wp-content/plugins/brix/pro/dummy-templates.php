<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Complete the information regarding dummy default templates.
 *
 * @since 1.1.2
 * @param array $data The array data.
 * @return array
 */
function brix_default_templates_data( $data ) {
	switch ( $data['id'] ) {
		// Page templates
		case 'landing_page':
			$data['label']       = __( 'Landing page', 'brix' );
			$data['description'] = __( 'Stripe based landing page with different column configurations.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/landing.jpg';
			break;
		case 'about':
			$data['label']       = __( 'About', 'brix' );
			$data['description'] = __( 'Stripe based page with services, team and clients sections.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/about.jpg';
			break;

		// Section templates
		case 'feature_1':
			$data['label']       = __( 'Feature 1', 'brix' );
			$data['description'] = __( 'Intro text plus three columns section with feature boxes.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/features_1.jpg';
			break;
		case 'feature_2':
			$data['label']       = __( 'Feature 2', 'brix' );
			$data['description'] = __( 'Feature page with two equal height blocks, with an image and a call to action button.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/features_2.jpg';
			break;
		case 'feature_3':
			$data['label']       = __( 'Feature 3', 'brix' );
			$data['description'] = __( 'Heading with a modular four columns for two rows feature boxes grid.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/features_3.jpg';
			break;
		case 'feature_4':
			$data['label']       = __( 'Feature 4', 'brix' );
			$data['description'] = __( 'Heading with an image on top and two rows with feature boxes.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/features_4.jpg';
			break;


		case 'promo_1':
			$data['label']       = __( 'Promo 1', 'brix' );
			$data['description'] = __( 'Simple two columns text and call to action plus image section.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/promo_1.jpg';
			break;
		case 'promo_2':
			$data['label']       = __( 'Promo 2', 'brix' );
			$data['description'] = __( 'Flat colored section with centered heding and a big image underneath.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/promo_2.jpg';
			break;
		case 'promo_3':
			$data['label']       = __( 'Promo 3', 'brix' );
			$data['description'] = __( 'Flat colored section with centered image, text and a call to action button.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/promo_3.jpg';
			break;


		case 'content_1':
			$data['label']       = __( 'Content 1', 'brix' );
			$data['description'] = __( 'Two columns row with text and an image, a diver section and a three columns layout with text blocks.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/content_1.jpg';
			break;
		case 'content_2':
			$data['label']       = __( 'Content 2', 'brix' );
			$data['description'] = __( 'Heading with two columns text and a section with a big image and three text block rows on a side.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/content_2.jpg';
			break;
		case 'content_3':
			$data['label']       = __( 'Content 3', 'brix' );
			$data['description'] = __( 'Simple example of a frequently asked questions section made with the accordion block.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/content_3.jpg';
			break;
		case 'content_4':
			$data['label']       = __( 'Content 4', 'brix' );
			$data['description'] = __( 'Simple descriptive section with a side flat colored section.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/content_4.jpg';
			break;


		case 'testimonial_1':
			$data['label']       = __( 'Testimonial 1', 'brix' );
			$data['description'] = __( 'Three rows testimonial section made with the feature box block.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/testimonial_1.jpg';
			break;
		case 'testimonial_2':
			$data['label']       = __( 'Testimonial 2', 'brix' );
			$data['description'] = __( 'Example of a checkered testimonial section made with the image and the text blocks.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/testimonial_2.jpg';
			break;
		case 'testimonial_3':
			$data['label']       = __( 'Testimonial 3', 'brix' );
			$data['description'] = __( 'Three columns testimonial section made with the team member block.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/testimonial_3.jpg';
			break;


		case 'team_1':
			$data['label']       = __( 'Team 1', 'brix' );
			$data['description'] = __( 'Big heading and a three columns carousel team section.', 'brix' );
			$data['thumb']       = BRIX_URI . 'includes/i/team_1.jpg';
			break;


		default:
			break;
	}

	return $data;
}

add_filter( 'brix_import_default_template_data', 'brix_default_templates_data' );

/**
 * Parse a folder full of dummy templates.
 *
 * @since 1.1.2
 * @param string $folder The folder path
 * @return array
 */
function brix_parse_dummy_templates_folder() {
	$folder    = BRIX_PRO_FOLDER . 'dummy_templates/';
	$templates = array();
	$files     = scandir( $folder );

	foreach ( $files as $file ) {
		if ( ! brix_string_ends_with( $file, '.txt' ) ) {
			continue;
		}

		$imported_data = implode( '', file( $folder . $file ) );
		$imported_data = base64_decode( $imported_data );
		$imported_data = @unserialize( $imported_data );

		if ( $imported_data == null || ! is_array( $imported_data ) ) {
			continue;
		}

		$id = str_replace( '.txt', '', $file );

		$imported_data[0]['id'] = $id;
		$imported_data[0]['type'] = 'default';

		if ( $imported_data[0]['section'] == '1' ) {
			$imported_data[0]['type'] = 'sections';
		}

		$imported_data[0] = apply_filters( 'brix_import_default_template_data', $imported_data[0] );

		$templates[] = $imported_data[0];
	}

	return $templates;
}

/**
 * Add default templates by reading them from the dummy templates folder.
 *
 * @since 1.1.3
 * @param array $templates An array of templates.
 * @return array
 */
function brix_pro_add_default_templates( $templates ) {
	$templates = array_merge( $templates, brix_parse_dummy_templates_folder() );

	return $templates;
}

add_filter( 'brix_get_default_templates', 'brix_pro_add_default_templates' );