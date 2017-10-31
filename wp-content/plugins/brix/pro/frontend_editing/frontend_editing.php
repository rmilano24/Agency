<?php if ( ! defined( 'BRIX_PRO' ) ) die( 'Forbidden' );

/**
 * Set to be using the builder in case of frontend editing mode.
 *
 * @since 1.2.1
 * @param boolean $using_builder The using builder previous setting.
 * @return boolean
 */
function brix_frontend_editing_using_builder( $using_builder ) {
	if ( brix_is_frontend_editing() ) {
		$using_builder = true;
	}

	return $using_builder;
}

add_filter( 'brix_is_using_builder', 'brix_frontend_editing_using_builder' );

/**
 * Add scripts and styles on frontend.
 *
 * @since 1.2
 */
function brix_frontend_editing_add_frontend_assets() {
	if ( ! brix_is_frontend_editing() ) {
		return;
	}

	$suffix = brix_get_scripts_suffix();

	/* Main builder frontend style. */
	brix_fw()->frontend()->add_style( 'brix-frontend-editing-frontend-style', BRIX_PRO_URI . 'assets/frontend/css/frontend-editing.css', array( 'brix-style' ) );

	/* Main builder JavaScript controller. */
	$deps = array( 'brix-script' );

	brix_fw()->frontend()->add_script( 'brix-frontend-editing-frontend-script', BRIX_PRO_URI . 'assets/frontend/js/min/brix_frontend_editing.' . $suffix . '.js', $deps );
}

/* Add scripts and styles on frontend. */
add_action( 'init', 'brix_frontend_editing_add_frontend_assets' );

/**
 * Output the iframe markup for live editing.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_iframe_markup() {
	global $post, $pagenow;

	if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) || ! $post ) {
		return;
	}

	$nonce = wp_create_nonce( 'brix_frontend_editing' );
	$preview_url = get_preview_post_link( $post->ID );
	$preview_url = add_query_arg( 'test_preview', $nonce, $preview_url );

	$icn_logo          = '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="40" viewBox="0 0 36 40"><g fill="none" fill-rule="evenodd"><path fill="#D9EB3D" d="M16.308578,0.398720931 C17.0710982,-0.0415203233 18.3076107,-0.04139165 19.069908,0.398720931 L33.9741162,9.00366948 C34.7366363,9.44391076 35.3547811,10.5148263 35.3547811,11.3950514 L35.3547811,28.6049486 C35.3547811,29.4854311 34.7364135,30.5562179 33.9741162,30.9963305 L19.069908,39.601279 C18.3073878,40.0415203 17.0708754,40.0413916 16.308578,39.601279 L1.4043699,30.9963305 C0.641849684,30.5560892 0.0237049108,29.4851737 0.0237049108,28.6049486 L0.0237049108,11.3950514 C0.0237049108,10.5145689 0.642072553,9.44378207 1.4043699,9.00366948 L16.308578,0.398720931 L16.308578,0.398720931 Z M16.9989889,3.18736302 C17.3802057,2.96726739 17.9988484,2.9675954 18.3794971,3.18736302 L31.9042867,10.9959039 C32.2855035,11.2159995 32.5945408,11.7519239 32.5945408,12.1914591 L32.5945408,27.8085409 C32.5945408,28.2487322 32.2849354,28.7843284 31.9042867,29.0040961 L18.3794971,36.812637 C17.9982803,37.0327326 17.3796376,37.0324046 16.9989889,36.812637 L3.47419935,29.0040961 C3.09298253,28.7840005 2.78394524,28.2480761 2.78394524,27.8085409 L2.78394524,12.1914591 C2.78394524,11.7512678 3.09355067,11.2156716 3.47419935,10.9959039 L16.9989889,3.18736302 L16.9989889,3.18736302 Z"/><path fill="#CDDC39" d="M17.6479385,25.9190048 L12.5426662,22.9664631 L12.5426662,17.0290324 L22.7611332,11.0880258 L27.2497247,13.6843714 C27.6310265,13.9049287 27.9401327,14.4411565 27.9401327,14.8809595 L27.9401327,25.1217709 C27.9401327,25.562071 27.6307073,26.0976431 27.249855,26.3175177 L17.6479385,31.8609207 L17.6479385,25.9190048 L17.6479385,25.9190048 Z"/><path fill="#D9EB3D" d="M8.09014343,14.5008686 C7.70893813,14.2807359 7.70931569,13.9237214 8.09014343,13.703941 L17.0012063,8.56125426 C17.3824116,8.34125594 18.0007355,8.34147378 18.3814285,8.56125426 L27.2893394,13.7039407 C27.6704099,13.923939 27.6700324,14.2809538 27.2893394,14.5008684 L17.6914395,20.0452798 L8.09014343,14.5008686 L8.09014343,14.5008686 Z"/></g></svg>';
	$icn_preview       = '<svg xmlns="http://www.w3.org/2000/svg" class="brix-preview-chevron" width="11" height="11" viewBox="0 0 11 11"><path fill="#3D6173" fill-rule="evenodd" d="M63.8954477,146.504914 C63.8954477,146.228779 63.9927512,145.99604 64.1873608,145.806689 L70.4989967,139.495052 L76.810633,145.806689 C76.9999841,145.99604 77.0946566,146.227464 77.0946566,146.500969 C77.0946566,146.779735 76.9999841,147.016419 76.810633,147.211029 C76.6160245,147.400379 76.3819689,147.495052 76.1084639,147.495052 C75.834959,147.495052 75.6009034,147.400379 75.4062949,147.211029 L70.4989967,142.287952 L65.5838104,147.211029 C65.3892008,147.400379 65.1551465,147.495052 64.8816408,147.495052 C64.6081353,147.495052 64.3767109,147.400379 64.1873608,147.211029 C63.9927512,147.016419 63.8954477,146.78105 63.8954477,146.504914 Z" transform="rotate(-45 -132 154.371)"/></svg>';
	$icn_preview_shape = '<svg xmlns="http://www.w3.org/2000/svg" class="brix-preview-shape" width="48" height="48" viewBox="0 0 48 48"><polygon fill="#D9EB3D" fill-rule="evenodd" points="60 133 108 133 60 181" transform="translate(-60 -133)"/></svg>';

	echo '<div class="brix-frontend-editing-iframe-wrapper">';

		echo '<span class="brix-frontend-editing-logo">' . $icn_logo . '</span>';

		brix_frontend_editing_primary_toolbar();

		brix_frontend_editing_secondary_toolbar();

		brix_frontend_editing_panel();

		echo '<div class="brix-frontend-editing-iframe-inner-wrapper">';
			echo '<div class="brix-frontend-editing-iframe">';

				printf( '<span class="brix-frontend-editing-preview"><span class="screen-reader-text">%s</span>%s%s</span>',
					esc_html( __( 'Preview', 'brix' ) ),
						$icn_preview,
						$icn_preview_shape
					);

				printf( '<iframe data-src="%s" data-nonce="%s" data-id="%s"></iframe>',
					esc_attr( $preview_url ),
					esc_attr( $nonce ),
					esc_attr( $post->ID )
				);

			echo '</div>';
		echo '</div>';

		brix_row_add_panel();

		echo '<div class="brix-frontend-editing-bottom-toolbar-wrapper">';
			brix_editing_toolbar();
		echo '</div>';

	echo '</div>';
}

add_action( 'admin_footer', 'brix_frontend_editing_iframe_markup' );

/**
 * Frontend add a new row panel.
 *
 * @since 1.1.3
 */
function brix_row_add_panel() {
	$icn_close = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><polygon fill="#000000" fill-rule="evenodd" points="20 2.013 12.013 10 20 17.987 17.987 20 10 12.013 2.013 20 0 17.987 7.987 10 0 2.013 2.013 0 10 7.987 17.987 0"/></svg>';
	$icn_save = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><polygon fill="#000000" fill-rule="evenodd" points="6.23 13.696 17.925 3 20 5.075 6.23 17.845 0 11.615 2.075 9.541"/></svg>';

	echo '<div class="brix-frontend-bottom-panel" data-action="">';

		echo '<div class="brix-frontend-bottom-panel-inner-wrapper">';
		echo '</div>';

		printf( '<span class="brix-close-bottom-panel"><i class="brix-close">%s</i><i class="brix-save">%s</i></span>', $icn_close, $icn_save );
	echo '</div>';
}

/**
 * Frontend editing panel.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_panel() {
	$icn_back = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><polygon fill="#FFFFFF" fill-rule="evenodd" points="20 8.772 20 11.228 4.795 11.228 11.754 18.246 10 20 0 10 10 0 11.754 1.754 4.795 8.772"/></svg>';

	echo '<div class="brix-frontend-editing-panel-wrapper">';

		echo '<div class="brix-frontend-editing-panel-actions">';
			printf( '<span class="brix-frontend-editing-panel-close"><span>%s</span>%s</span>',
				$icn_back,
				esc_html( __( 'Close', 'brix' ) )
			);
		echo '</div>';

		echo '<div class="brix-frontend-editing-panel-content">';
			printf( '<h2 class="brix-frontend-editing-panel-heading">%s</h2>', __( 'Frontend editing', 'brix' ) );

			printf( '<p>%s</p>',
				__( 'Welcome to Frontend editing! This mode will allow you to get a high fidelity preview of your page and see your modifications live as you do them.', 'brix' )
			);
			printf( '<p>%s</p>',
				__( 'To permanently save your modifications, you can either hit the <strong>"Update"</strong> button in the top/right corner, or you can go back to backend editing and save your page.', 'brix' )
			);
			printf( '<p>%s</p>',
				__( 'Start by going over elements in your page, and select one by clicking on its space: after that, look for the expanded item in the bottom bar area, where you\'ll find the respective controls. You can also access ancestor elements by clicking on their respective symbols in the bottom bar.', 'brix' )
			);
			printf( '<p>%s</p>',
				__( 'Here\'s a list of symbols used throughout the Frontend editing interface:', 'brix' )
			);

			$icn_section          = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#ffffff" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z"/></svg>';
			$icn_row              = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#ffffff" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M2,8 L16,8 L16,10 L2,10 L2,8 Z"/></svg>';
			$icn_col              = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#ffffff" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M8.25,2 L10,2 L10,16 L8.25,16 L8.25,2 Z"/></svg>';
			$icn_block            = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#ffffff" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M5,6 L13,6 L13,12 L5,12 L5,6 Z"/></svg>';

			echo '<ul class="brix-frontend-symbols">';
				printf( '<li>%s <span>%s</span></li>',
					$icn_section,
					__( 'Section', 'brix' )
				);
				printf( '<li>%s <span>%s</span></li>',
					$icn_row,
					__( 'Row', 'brix' )
				);
				printf( '<li>%s <span>%s</span></li>',
					$icn_col,
					__( 'Column', 'brix' )
				);
				printf( '<li>%s <span>%s</span></li>',
					$icn_block,
					__( 'Block', 'brix' )
				);
			echo '</ul>';

			echo '<p>';
				printf( __( 'Learn more about Frontend editing on the <a href="%s" target="_blank" rel="noopener noreferrer">official docs</a>.', 'brix' ),
					esc_attr( 'https://justevolve.it/docs/brix/getting-started/frontend-editing/' )
				);
			echo '</p>';
		echo '</div>';

	echo '</div>';
}

/**
 * Media queries helpers for frontend editing.
 *
 * @since 1.2
 * @return array
 */
function brix_frontend_editing_media_queries() {
	$media_queries = array(
		'desktop' => array(
			'label' => __( 'Desktop', 'brix' ),
			'query' => '@media all',
			'height' => '',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M18.1702128,13.9906103 L18.1702128,1.97183099 L1.82978723,1.97183099 L1.82978723,13.9906103 L18.1702128,13.9906103 Z M18.1702128,0 C18.6524841,0 19.0780133,0.195617653 19.4468085,0.586852958 C19.8156037,0.978091268 20,1.43974761 20,1.97183099 L20,13.9906103 C20,14.5226937 19.8156037,14.9921743 19.4468085,15.399061 C19.0780133,15.8059478 18.6524841,16.0093897 18.1702128,16.0093897 L11.8297872,16.0093897 L11.8297872,18.028169 L13.6170213,18.028169 L13.6170213,20 L6.38297872,20 L6.38297872,18.028169 L8.17021277,18.028169 L8.17021277,16.0093897 L1.82978723,16.0093897 C1.34751537,16.0093897 0.92198754,15.8059478 0.553191489,15.399061 C0.184395329,14.9921743 0,14.5226937 0,13.9906103 L0,1.97183099 C0,1.43974761 0.184395329,0.978091268 0.553191489,0.586852958 C0.92198754,0.195617653 1.34751537,0 1.82978723,0 L18.1702128,0 Z"/></svg>'
		),
		'tablet_l' => array(
			'label' => __( 'Tablet landscape', 'brix' ),
			'query' => '@media screen and (max-width: 1024px)',
			'height' => '768',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M16.3012346,15.8203125 L16.3012346,2.5 L3.65925926,2.5 L3.65925926,15.8203125 L16.3012346,15.8203125 Z M9.98024691,19.1796875 C10.3226346,19.1796875 10.6189296,19.0559907 10.8691345,18.8085938 C11.119342,18.5611968 11.2444444,18.2682308 11.2444444,17.9296875 C11.2444444,17.5911442 11.119342,17.2981775 10.8691345,17.05078 C10.6189296,16.803385 10.3226346,16.6796875 9.98024691,16.6796875 C9.63785924,16.6796875 9.34156421,16.803385 9.09135929,17.05078 C8.84115184,17.2981775 8.71604938,17.5911442 8.71604938,17.9296875 C8.71604938,18.2682308 8.84115184,18.5611968 9.09135929,18.8085938 C9.34156421,19.0559907 9.63785924,19.1796875 9.98024691,19.1796875 L9.98024691,19.1796875 Z M15.9061728,0 C16.4856001,0 16.9794209,0.20182 17.3876556,0.60547 C17.7958877,1.0091175 18,1.4973925 18,2.0703125 L18,17.9296875 C18,18.502607 17.7958877,18.9908835 17.3876556,19.3945312 C16.9794209,19.7981791 16.4856001,20 15.9061728,20 L4.09382716,20 C3.51440043,20 3.02057807,19.7981791 2.61234568,19.3945312 C2.20411319,18.9908835 2,18.502607 2,17.9296875 L2,2.0703125 C2,1.4973925 2.20411319,1.0091175 2.61234568,0.60547 C3.02057807,0.20182 3.51440043,0 4.09382716,0 L15.9061728,0 Z" transform="rotate(90 10 10)"/></svg>'
		),
		'tablet_p' => array(
			'label' => __( 'Tablet portrait', 'brix' ),
			'query' => '@media screen and (max-width: 768px)',
			'height' => '1024',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M16.3012346,15.8203125 L16.3012346,2.5 L3.65925926,2.5 L3.65925926,15.8203125 L16.3012346,15.8203125 Z M9.98024691,19.1796875 C10.3226346,19.1796875 10.6189296,19.0559907 10.8691345,18.8085938 C11.119342,18.5611968 11.2444444,18.2682308 11.2444444,17.9296875 C11.2444444,17.5911442 11.119342,17.2981775 10.8691345,17.05078 C10.6189296,16.803385 10.3226346,16.6796875 9.98024691,16.6796875 C9.63785924,16.6796875 9.34156421,16.803385 9.09135929,17.05078 C8.84115184,17.2981775 8.71604938,17.5911442 8.71604938,17.9296875 C8.71604938,18.2682308 8.84115184,18.5611968 9.09135929,18.8085938 C9.34156421,19.0559907 9.63785924,19.1796875 9.98024691,19.1796875 L9.98024691,19.1796875 Z M15.9061728,0 C16.4856001,0 16.9794209,0.20182 17.3876556,0.60547 C17.7958877,1.0091175 18,1.4973925 18,2.0703125 L18,17.9296875 C18,18.502607 17.7958877,18.9908835 17.3876556,19.3945312 C16.9794209,19.7981791 16.4856001,20 15.9061728,20 L4.09382716,20 C3.51440043,20 3.02057807,19.7981791 2.61234568,19.3945312 C2.20411319,18.9908835 2,18.502607 2,17.9296875 L2,2.0703125 C2,1.4973925 2.20411319,1.0091175 2.61234568,0.60547 C3.02057807,0.20182 3.51440043,0 4.09382716,0 L15.9061728,0 Z"/></svg>'
		),
		'mobile_l' => array(
			'label' => __( 'Mobile landscape', 'brix' ),
			'query' => '@media screen and (max-width: 667px)',
			'height' => '375',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M14.1371841,15.4468085 L14.1371841,2.72340426 L5.81949458,2.72340426 L5.81949458,15.4468085 L14.1371841,15.4468085 Z M9.97833935,19.0638298 C10.3537915,19.0638298 10.6786999,18.9290793 10.9530672,18.6595745 C11.2274373,18.3900697 11.3646209,18.0709237 11.3646209,17.7021277 C11.3646209,17.3333316 11.2274373,17.0141849 10.9530672,16.7446822 C10.6786999,16.4751769 10.3537915,16.3404255 9.97833935,16.3404255 C9.60288716,16.3404255 9.2779788,16.4751769 9.00361149,16.7446822 C8.72924142,17.0141849 8.59205776,17.3333316 8.59205776,17.7021277 C8.59205776,18.0709237 8.72924142,18.3900697 9.00361149,18.6595745 C9.2779788,18.9290793 9.60288716,19.0638298 9.97833935,19.0638298 L9.97833935,19.0638298 Z M13.7039711,0 C14.3393539,0 14.8808632,0.226949447 15.3285212,0.680851064 C15.7761765,1.13475268 16,1.67375523 16,2.29787234 L16,17.7021277 C16,18.3262442 15.7761765,18.865246 15.3285212,19.3191489 C14.8808632,19.7730519 14.3393539,20 13.7039711,20 L6.29602888,20 C5.66064668,20 5.1191357,19.7730519 4.67148014,19.3191489 C4.22382448,18.865246 4,18.3262442 4,17.7021277 L4,2.29787234 C4,1.67375523 4.22382448,1.13475268 4.67148014,0.680851064 C5.1191357,0.226949447 5.66064668,0 6.29602888,0 L13.7039711,0 Z" transform="rotate(90 10 10)"/></svg>'
		),
		'mobile_p' => array(
			'label' => __( 'Mobile portrait', 'brix' ),
			'query' => '@media screen and (max-width: 375px)',
			'height' => '667',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M14.1371841,15.4468085 L14.1371841,2.72340426 L5.81949458,2.72340426 L5.81949458,15.4468085 L14.1371841,15.4468085 Z M9.97833935,19.0638298 C10.3537915,19.0638298 10.6786999,18.9290793 10.9530672,18.6595745 C11.2274373,18.3900697 11.3646209,18.0709237 11.3646209,17.7021277 C11.3646209,17.3333316 11.2274373,17.0141849 10.9530672,16.7446822 C10.6786999,16.4751769 10.3537915,16.3404255 9.97833935,16.3404255 C9.60288716,16.3404255 9.2779788,16.4751769 9.00361149,16.7446822 C8.72924142,17.0141849 8.59205776,17.3333316 8.59205776,17.7021277 C8.59205776,18.0709237 8.72924142,18.3900697 9.00361149,18.6595745 C9.2779788,18.9290793 9.60288716,19.0638298 9.97833935,19.0638298 L9.97833935,19.0638298 Z M13.7039711,0 C14.3393539,0 14.8808632,0.226949447 15.3285212,0.680851064 C15.7761765,1.13475268 16,1.67375523 16,2.29787234 L16,17.7021277 C16,18.3262442 15.7761765,18.865246 15.3285212,19.3191489 C14.8808632,19.7730519 14.3393539,20 13.7039711,20 L6.29602888,20 C5.66064668,20 5.1191357,19.7730519 4.67148014,19.3191489 C4.22382448,18.865246 4,18.3262442 4,17.7021277 L4,2.29787234 C4,1.67375523 4.22382448,1.13475268 4.67148014,0.680851064 C5.1191357,0.226949447 5.66064668,0 6.29602888,0 L13.7039711,0 Z"/></svg>'
		),
	);

	return $media_queries;
}

/**
 * Frontend editing secondary toolbar.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_secondary_toolbar() {
	$icn_help    = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M13.0516432,9.22535211 C13.6776233,8.59937202 13.9906103,7.84820432 13.9906103,6.97183099 C13.9906103,5.87636432 13.599378,4.9374062 12.8169014,4.15492958 C12.0344248,3.37245296 11.0954667,2.98122066 10,2.98122066 C8.90453333,2.98122066 7.96557521,3.37245296 7.18309859,4.15492958 C6.40062197,4.9374062 6.00938967,5.87636432 6.00938967,6.97183099 L7.98122066,6.97183099 C7.98122066,6.43974761 8.18466254,5.97026704 8.5915493,5.56338028 C8.99843606,5.15649352 9.46791662,4.95305164 10,4.95305164 C10.5320834,4.95305164 11.0015639,5.15649352 11.4084507,5.56338028 C11.8153375,5.97026704 12.0187793,6.43974761 12.0187793,6.97183099 C12.0187793,7.50391437 11.8153375,7.97339493 11.4084507,8.38028169 L10.1877934,9.64788732 C9.40531681,10.4929608 9.01408451,11.4319189 9.01408451,12.4647887 L9.01408451,12.9812207 L10.9859155,12.9812207 C10.9859155,11.9483508 11.3771478,11.0093927 12.1596244,10.1643192 L13.0516432,9.22535211 Z M10.9859155,16.971831 L10.9859155,15 L9.01408451,15 L9.01408451,16.971831 L10.9859155,16.971831 Z M10,-0.0234741784 C12.7543166,-0.0234741784 15.1095377,0.954608075 17.0657292,2.91079662 C19.0219177,4.86698817 20,7.2222092 20,9.97652582 C20,12.7308424 19.0219177,15.0860635 17.0657292,17.0422535 C15.1095377,18.9984448 12.7543166,19.9765258 10,19.9765258 C7.24568338,19.9765258 4.89046235,18.9984448 2.9342723,17.0422535 C0.978081052,15.0860635 0,12.7308424 0,9.97652582 C0,7.2222092 0.978081052,4.86698817 2.9342723,2.91079662 C4.89046235,0.954608075 7.24568338,-0.0234741784 10,-0.0234741784 L10,-0.0234741784 Z"/></svg>';
	$icn_desktop = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M18.1702128,13.9906103 L18.1702128,1.97183099 L1.82978723,1.97183099 L1.82978723,13.9906103 L18.1702128,13.9906103 Z M18.1702128,0 C18.6524841,0 19.0780133,0.195617653 19.4468085,0.586852958 C19.8156037,0.978091268 20,1.43974761 20,1.97183099 L20,13.9906103 C20,14.5226937 19.8156037,14.9921743 19.4468085,15.399061 C19.0780133,15.8059478 18.6524841,16.0093897 18.1702128,16.0093897 L11.8297872,16.0093897 L11.8297872,18.028169 L13.6170213,18.028169 L13.6170213,20 L6.38297872,20 L6.38297872,18.028169 L8.17021277,18.028169 L8.17021277,16.0093897 L1.82978723,16.0093897 C1.34751537,16.0093897 0.92198754,15.8059478 0.553191489,15.399061 C0.184395329,14.9921743 0,14.5226937 0,13.9906103 L0,1.97183099 C0,1.43974761 0.184395329,0.978091268 0.553191489,0.586852958 C0.92198754,0.195617653 1.34751537,0 1.82978723,0 L18.1702128,0 Z"/></svg>';
	$icn_sort    = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M6.42708333,0 L10.8541667,4.42708333 L7.52083333,4.42708333 L7.52083333,12.2395833 L5.33333333,12.2395833 L5.33333333,4.42708333 L2,4.42708333 L6.42708333,0 Z M14.1875,15.5729167 L17.5208333,15.5729167 L13.09375,20 L8.66666667,15.5729167 L12,15.5729167 L12,7.76041667 L14.1875,7.76041667 L14.1875,15.5729167 Z"/></svg>';

	echo '<div class="brix-frontend-editing-secondary-toolbar-wrapper">';

		// printf( '<span class="brix-secondary-toolbar-icn-btn brix-frontend-editing-sorting"><span>%s</span>%s</span>',
		// 	esc_html( __( 'Sorting', 'brix' ) ),
		// 	$icn_sort
		// );

		echo '<span class="brix-secondary-toolbar-icn-btn brix-frontend-editing-responsive">';
			printf( '<span>%s</span><i>%s</i>',
				esc_html( __( 'Responsive', 'brix' ) ),
				$icn_desktop
			);

			echo '<div class="brix-frontend-editing-responsive-submenu">';
				echo '<div class="brix-frontend-editing-responsive-submenu-inner-wrapper">';
					$media_queries = brix_frontend_editing_media_queries();

					echo '<ul>';
						foreach ( $media_queries as $device => $query ) {
							$li_class = '';

							if ( $device == 'desktop' ) {
								$li_class = 'brix-active';
							}

							printf( '<li class="%s" data-breakpoint="%s" data-height="%s">', esc_attr( $li_class ), esc_attr( $device ), esc_attr( $query['height'] ) );
								echo '<i data-icn>' . $query['icon'] . '</i>';
								echo '<p class="brix-frontend-editing-breakpoint-label">' . esc_html( $query['label'] ) . '</p>';
								echo '<p class="brix-frontend-editing-breakpoint-media" data-media>' . esc_html( $query['query'] ) . '</p>';
							echo '</li>';
						}
					echo '</ul>';
				echo '</div>';
			echo '</div>';
		echo '</span>';

		printf( '<span class="brix-secondary-toolbar-icn-btn brix-frontend-editing-help brix-tooltip" data-title="%s"><span>%s</span>%s</span>',
			esc_attr( __( 'Help', 'brix' ) ),
			esc_html( __( 'Help', 'brix' ) ),
			$icn_help
		);

	echo '</div>';
}

/**
 * Frontend editing primary toolbar.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_primary_toolbar() {
	$icn_undo              = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M10.2752294,7.00917431 C12.507655,7.00917431 14.5106936,7.66665982 16.2844037,8.98165138 C18.0581138,10.2966429 19.2966312,11.9938733 20,14.0733945 L17.706422,14.8073394 C17.1865424,13.2171174 16.2461828,11.9250815 14.8853196,10.9311941 C13.5244594,9.93730349 11.9877754,9.44036697 10.2752294,9.44036697 C8.37919413,9.44036697 6.69725651,10.0519809 5.2293578,11.2752294 L8.80733945,14.8073394 L0,14.8073394 L0,6 L3.48623853,9.53211009 C5.41285284,7.85014312 7.67582826,7.00917431 10.2752294,7.00917431 L10.2752294,7.00917431 Z"/></svg>';
	$icn_redo              = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M10.2752294,7.00917431 C12.507655,7.00917431 14.5106936,7.66665982 16.2844037,8.98165138 C18.0581138,10.2966429 19.2966312,11.9938733 20,14.0733945 L17.706422,14.8073394 C17.1865424,13.2171174 16.2461828,11.9250815 14.8853196,10.9311941 C13.5244594,9.93730349 11.9877754,9.44036697 10.2752294,9.44036697 C8.37919413,9.44036697 6.69725651,10.0519809 5.2293578,11.2752294 L8.80733945,14.8073394 L0,14.8073394 L0,6 L3.48623853,9.53211009 C5.41285284,7.85014312 7.67582826,7.00917431 10.2752294,7.00917431 L10.2752294,7.00917431 Z" transform="matrix(-1 0 0 1 20 0)"/></svg>';
	$icn_templates_manager = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M0,0 L20,0 L20,7 L0,7 L0,0 Z M2,2 L5,2 L5,5 L2,5 L2,2 Z M7,2 L10,2 L10,5 L7,5 L7,2 Z M8,20 L8,9 L20,9 L20,20 L8,20 Z M0,20 L0,9 L6,9 L6,20 L0,20 Z"/></svg>';
	$icn_save_template     = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M0,1.99079514 C0,0.891309342 0.894919036,0 2.00786078,0 L15.6028748,0 L20,4.41375732 L20,17.9942795 C20,19.1020084 19.1017876,20 18.0092049,20 L1.99079514,20 C0.891309342,20 0,19.1017876 0,18.0092049 L0,1.99079514 Z M14,6 L14,2 L2,2 L2,6 L14,6 Z M10.5,18 C11.4479225,18 12.2682245,17.6536494 12.9609375,16.9609375 C13.6536505,16.2682245 14,15.4479225 14,14.5 C14,13.5520775 13.6536505,12.7317755 12.9609375,12.0390625 C12.2682245,11.3463495 11.4479225,11 10.5,11 C9.5520775,11 8.7317755,11.3463495 8.0390625,12.0390625 C7.3463495,12.7317755 7,13.5520775 7,14.5 C7,15.4479225 7.3463495,16.2682245 8.0390625,16.9609375 C8.7317755,17.6536494 9.5520775,18 10.5,18 L10.5,18 Z"/></svg>';
	$icn_reset_builder     = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M10,11.767767 L14.065864,15.8336309 L15.8336309,14.065864 L11.767767,10 L15.8336309,5.93413601 L14.065864,4.16636906 L10,8.23223305 L5.93413601,4.16636906 L4.16636906,5.93413601 L8.23223305,10 L4.16636906,14.065864 L5.93413601,15.8336309 L10,11.767767 Z M0,10 C0,4.4771525 4.47593818,0 10,0 C15.5228475,0 20,4.47593818 20,10 C20,15.5228475 15.5240618,20 10,20 C4.4771525,20 0,15.5240618 0,10 Z"/></svg>';

	echo '<div class="brix-frontend-editing-primary-toolbar-wrapper">';

		echo '<div class="brix-frontend-editing-primary-toolbar-messages-wrapper">';
			// echo '<p><em>Editing</em>Page title</p>';
		echo '</div>';

		echo '<div class="brix-frontend-editing-primary-toolbar-actions-wrapper">';

			printf( '<span class="brix-primary-toolbar-icn-btn brix-frontend-editing-load-template brix-tooltip" data-title="%s"><span>%s</span>%s</span>',
				esc_html( __( 'Templates manager', 'brix' ) ),
				esc_html( __( 'Templates manager', 'brix' ) ),
				$icn_templates_manager
			);

			$nonce = wp_create_nonce( 'brix_nonce' );

			printf( '<button type="button" href="#" class="brix-primary-toolbar-icn-btn brix-frontend-editing-save-template brix-tooltip" data-title="%s" data-nonce="%s" %s><span class="screen-reader-text">%s</span>%s</button>',
				esc_attr( __( 'Save template', 'brix' ) ),
				esc_attr( $nonce ),
				'',
				esc_html( __( 'Save template', 'brix' ) ),
				$icn_save_template
			);

			printf( '<button type="button" href="#" class="brix-primary-toolbar-icn-btn brix-reset-builder brix-tooltip" data-title="%s" data-nonce="%s" %s><span class="screen-reader-text">%s</span>%s</button>',
				esc_attr( __( 'Empty page contents', 'brix' ) ),
				esc_attr( $nonce ),
				'',
				esc_html( __( 'Empty page contents', 'brix' ) ),
				$icn_reset_builder
			);

			echo '<span class="brix-frontend-editing-primary-toolbar-divider"></span>';

			printf( '<button type="button" href="#" class="brix-primary-toolbar-icn-btn brix-tooltip brix-undo-btn" data-title="%s" disabled><span class="screen-reader-text">%s</span>%s</button>',
				esc_attr( __( 'Undo', 'brix' ) ),
				esc_html( __( 'Undo', 'brix' ) ),
				$icn_undo
			);

			printf( '<button type="button" href="#" class="brix-primary-toolbar-icn-btn brix-tooltip brix-redo-btn" data-title="%s" disabled><span class="screen-reader-text">%s</span>%s</button>',
				esc_attr( __( 'Redo', 'brix' ) ),
				esc_html( __( 'Redo', 'brix' ) ),
				$icn_redo
			);

			echo '<span class="brix-frontend-editing-primary-toolbar-divider"></span>';

			printf( '<span class="brix-btn-text brix-frontend-editing-close">%s</span>',
				esc_html( __( 'Backend editing', 'brix' ) )
			);

			printf( '<span class="brix-btn-text brix-frontend-editing-save">%s</span>',
				esc_html( __( 'Update', 'brix' ) )
			);
		echo '</div>';

	echo '</div>';
}

/**
 * Template tag to check if we're in live editing mode.
 *
 * @since 1.1.3
 * @return boolean
 */
function brix_is_frontend_editing() {
	return isset( $_GET['test_preview'] ) && wp_verify_nonce( $_GET['test_preview'], 'brix_frontend_editing' );
}

/**
 * Hide the admin bar when live editing.
 *
 * @since 1.1.3
 * @param boolean $show True to show the admin bar.
 * @return boolean
 */
function brix_frontend_editing_hide_admin_bar( $show ) {
	if ( brix_is_frontend_editing() ) {
		return false;
	}

	return $show;
}

add_filter( 'show_admin_bar', 'brix_frontend_editing_hide_admin_bar' );

/**
 * Save a preview of builder changes for live editing.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_save_preview() {
	if ( empty( $_POST ) ) {
		die();
	}

	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_frontend_editing' );

	if ( ! $is_valid_nonce ) {
		die();
	}

	$data = $_POST['data']['brix'];
	$data = brix_serialize_template( $data, true );

	update_post_meta( $_POST['post_id'], '_brix_frontend_editing', $data );

	die();
}

add_action( 'wp_ajax_brix_frontend_editing_save_preview', 'brix_frontend_editing_save_preview' );

/**
 * Save a definitive version of builder changes for live editing.
 *
 * @since 1.1.3
 */
function brix_frontend_editing_save() {
	if ( empty( $_POST ) ) {
		die();
	}

	/* Verify the validity of the supplied nonce. */
	$is_valid_nonce = brix_is_post_nonce_valid( 'brix_frontend_editing' );

	if ( ! $is_valid_nonce ) {
		die();
	}

	$data = $_POST['data']['brix'];
	$data = brix_serialize_template( $data, true );

	update_post_meta( $_POST['post_id'], '_brix_frontend_editing', $data );
	update_post_meta( $_POST['post_id'], '_brix', $data );

	die();
}

add_action( 'wp_ajax_brix_frontend_editing_save', 'brix_frontend_editing_save' );

/**
 * Define the temporary meta key for builder preview.
 *
 * @since 1.1.3
 * @param string $builder_key The original builder key.
 * @return string
 */
function brix_frontend_editing_builder_key( $builder_key ) {
	if ( brix_is_frontend_editing() ) {
		$builder_key = '_brix_frontend_editing';
	}

	return $builder_key;
}

add_filter( 'brix_builder_key', 'brix_frontend_editing_builder_key' );

/**
 * Editing toolbar.
 *
 * @since 1.2
 * @param string $context The context string.
 */
function brix_editing_toolbar( $context = '' ) {
	$icn_edit             = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="#3D6072" fill-rule="evenodd" d="M15.75,3.58333333 L14.125,5.20833333 L10.7916667,1.875 L12.4166667,0.25 C12.5833333,0.0833333333 12.7916667,0 13.0416667,0 C13.2916667,0 13.5,0.0833333333 13.6666667,0.25 L15.75,2.33333333 C15.9166667,2.5 16,2.70833333 16,2.95833333 C16,3.20833333 15.9166667,3.41666667 15.75,3.58333333 L15.75,3.58333333 Z M0,12.6666667 L9.83333333,2.83333333 L13.1666667,6.16666667 L3.33333333,16 L0,16 L0,12.6666667 Z"/></svg>';
	$icn_duplicate        = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="#3D6072" fill-rule="evenodd" d="M12,4 L12,2.0085302 C12,0.901950359 11.1007504,0 9.9914698,0 L2.0085302,0 C0.901950359,0 0,0.899249601 0,2.0085302 L0,9.9914698 C0,11.0980496 0.899249601,12 2.0085302,12 L4,12 L4,10 L2,10 L2,2 L10,2 L10,4 L12,4 Z M16,6.99201702 C16,5.8918564 15.1017394,5 14.0020869,5 L10,5 L10,6.99201702 L13.9829712,6.99201703 L13.9829712,13.9897461 L10,13.9897461 L10,16 L14.0020869,16 C15.1055038,16 16,15.0998238 16,14.007983 L16,6.99201702 Z M5,6.99201702 C5,5.8918564 5.8938998,5 7.0048815,5 L10,5 L10,6.99201702 L7.01702881,6.99201703 L7.01702881,13.9897461 L10,13.9897461 L10,16 L7.0048815,16 C5.89761602,16 5,15.0998238 5,14.007983 L5,6.99201702 Z"/></svg>';
	$icn_remove           = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="#3D6072" fill-rule="evenodd" d="M8,6.74292128 L4.85730319,3.60022447 L3.60022447,4.85730319 L6.74292128,8 L3.60022447,11.1426968 L4.85730319,12.3997755 L8,9.25707872 L11.1426968,12.3997755 L12.3997755,11.1426968 L9.25707872,8 L12.3997755,4.85730319 L11.1426968,3.60022447 L8,6.74292128 Z M0,8 C0,3.581722 3.59071231,0 8,0 C12.418278,0 16,3.59071231 16,8 C16,12.418278 12.4092877,16 8,16 C3.581722,16 0,12.4092877 0,8 Z"/></svg>';
	$icn_sort             = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="#3D6072" fill-rule="evenodd" d="M8,0 L13,5 L3,5 L8,0 Z M8,16 L13,11 L3,11 L8,16 Z"/></svg>';
	$icn_save_template    = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#FFFFFF" fill-rule="evenodd" d="M0,1.99079514 C0,0.891309342 0.894919036,0 2.00786078,0 L15.6028748,0 L20,4.41375732 L20,17.9942795 C20,19.1020084 19.1017876,20 18.0092049,20 L1.99079514,20 C0.891309342,20 0,19.1017876 0,18.0092049 L0,1.99079514 Z M14,6 L14,2 L2,2 L2,6 L14,6 Z M10.5,18 C11.4479225,18 12.2682245,17.6536494 12.9609375,16.9609375 C13.6536505,16.2682245 14,15.4479225 14,14.5 C14,13.5520775 13.6536505,12.7317755 12.9609375,12.0390625 C12.2682245,11.3463495 11.4479225,11 10.5,11 C9.5520775,11 8.7317755,11.3463495 8.0390625,12.0390625 C7.3463495,12.7317755 7,13.5520775 7,14.5 C7,15.4479225 7.3463495,16.2682245 8.0390625,16.9609375 C8.7317755,17.6536494 9.5520775,18 10.5,18 L10.5,18 Z"/></svg>';
	$icn_replace_template = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#000000" fill-rule="evenodd" d="M10.03367,2.8956229 C11.7845204,2.8956229 13.3108795,3.43433912 14.6127946,4.51178451 C15.9147089,5.5892299 16.7452285,6.95846357 17.1043771,8.61952862 L20,8.61952862 C19.6408514,6.19527327 18.518528,4.15264754 16.6329966,2.49158249 C14.7474661,0.830517441 12.5477107,0 10.03367,0 C7.2951596,0 4.9382831,0.987642828 2.96296296,2.96296296 L0,0 L0,8.61952862 L8.61952862,8.61952862 L4.98316498,4.98316498 C6.37486545,3.59146451 8.0583499,2.8956229 10.03367,2.8956229 Z M5.38720539,15.4855216 C4.0852911,14.4080762 3.2547715,13.0388426 2.8956229,11.3777775 L0,11.3777775 C0.359148606,13.8020329 1.481472,15.8446586 3.36700337,17.5057237 C5.25253387,19.1667887 7.45228929,19.9973061 9.96632997,19.9973061 C12.7048404,19.9973061 15.0617169,19.0096633 17.037037,17.0343432 L20,19.9973061 L20,11.3777775 L11.3804714,11.3777775 L15.016835,15.0141412 C13.6251345,16.4058416 11.9416501,17.1016832 9.96632997,17.1016832 C8.2154796,17.1016832 6.68912054,16.562967 5.38720539,15.4855216 Z" transform="matrix(-1 0 0 1 20 0)"/></svg>';
	$icn_section          = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z"/></svg>';
	$icn_row              = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M2,8 L16,8 L16,10 L2,10 L2,8 Z"/></svg>';
	$icn_col              = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M8.25,2 L10,2 L10,16 L8.25,16 L8.25,2 Z"/></svg>';
	$icn_block            = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M5,6 L13,6 L13,12 L5,12 L5,6 Z"/></svg>';

	$icn_add              = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="#FFFFFF" fill-rule="evenodd" d="M9.33333333,6.66666667 L9.33333333,1.00087166 C9.33333333,0.444630861 8.88876631,0 8.34036509,0 L7.65963491,0 C7.10701243,0 6.66666667,0.448105505 6.66666667,1.00087166 L6.66666667,6.66666667 L1.00087166,6.66666667 C0.444630861,6.66666667 3.03893084e-13,7.11123369 3.03859504e-13,7.65963491 L3.03817821e-13,8.34036509 C3.03783983e-13,8.89298757 0.448105505,9.33333333 1.00087166,9.33333333 L6.66666667,9.33333333 L6.66666667,14.9991283 C6.66666667,15.5553691 7.11123369,16 7.65963491,16 L8.34036509,16 C8.89298757,16 9.33333333,15.5518945 9.33333333,14.9991283 L9.33333333,9.33333333 L14.9991283,9.33333333 C15.5553691,9.33333333 16,8.88876631 16,8.34036509 L16,7.65963491 C16,7.10701243 15.5518945,6.66666667 14.9991283,6.66666667 L9.33333333,6.66666667 Z"/></svg>';

	printf( '<div class="brix-editing-toolbar-wrapper" data-context="%s" data-count="0">', esc_attr( $context ) );

		echo '<div class="brix-editing-empty-state">';
			echo esc_html( __( 'Start editing by selecting an element…', 'brix' ) );
		echo '</div>';

		// SECTION

		echo '<div class="brix-editing-toolbar-context-wrapper brix-editing-toolbar-section-wrapper">';
			echo '<div class="brix-editing-toolbar-trigger-wrapper">';
				echo '<span class="brix-editing-toolbar-trigger">';
					echo $icn_section;
				echo '</span>';
				printf( '<span class="brix-editing-toolbar-label"><span><em>%s</em>%s</span></span>',
					esc_html__( 'Edit', 'brix' ),
					esc_html__( 'Section', 'brix' )
				);
			echo '</div>';

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="edit"><em>%s</em>%s</span>',
				esc_html( __( 'Edit', 'brix' ) ),
				esc_html( __( 'Edit', 'brix' ) ),
				$icn_edit );
			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="duplicate"><em>%s</em>%s</span>',
				esc_html( __( 'Duplicate', 'brix' ) ),
				esc_html( __( 'Duplicate', 'brix' ) ),
				$icn_duplicate );
			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="save-template"><em>%s</em>%s</span>',
				esc_html( __( 'Save section template', 'brix' ) ),
				esc_html( __( 'Save section template', 'brix' ) ),
				$icn_save_template );
			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="replace-with-template"><em>%s</em>%s</span>',
				esc_html( __( 'Replace section with template', 'brix' ) ),
				esc_html( __( 'Replace section with template', 'brix' ) ),
				$icn_replace_template );

			// printf( '<span class="brix-editing-btn" data-action="sort"><em>%s</em>%s</span>',
			// 	esc_html( __( 'Sorting', 'brix' ) ),
			// 	$icn_sort );

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="remove"><em>%s</em>%s</span>',
				esc_html( __( 'Remove', 'brix' ) ),
				esc_html( __( 'Remove', 'brix' ) ),
				$icn_remove );

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="add"><em>%s</em>%s</span>',
				esc_html( __( 'Add a new section', 'brix' ) ),
				esc_html( __( 'Add a new section', 'brix' ) ),
				$icn_add );

		echo '</div>';

		// ROW

		echo '<div class="brix-editing-toolbar-context-wrapper brix-editing-toolbar-row-wrapper">';
			echo '<div class="brix-editing-toolbar-trigger-wrapper">';
				echo '<span class="brix-editing-toolbar-trigger">';
					echo $icn_row;
				echo '</span>';
				printf( '<span class="brix-editing-toolbar-label"><span><em>%s</em>%s</span></span>',
					esc_html__( 'Edit', 'brix' ),
					esc_html__( 'Row', 'brix' )
				);
			echo '</div>';

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="edit"><em>%s</em>%s</span>',
				esc_html( __( 'Edit', 'brix' ) ),
				esc_html( __( 'Edit', 'brix' ) ),
				$icn_edit );
			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="clone"><em>%s</em>%s</span>',
				esc_html( __( 'Duplicate', 'brix' ) ),
				esc_html( __( 'Duplicate', 'brix' ) ),
				$icn_duplicate );
			// printf( '<span class="brix-editing-btn" data-action="sort"><em>%s</em>%s</span>',
			// 	esc_html( __( 'Sorting', 'brix' ) ),
			// 	$icn_sort );
			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="remove"><em>%s</em>%s</span>',
				esc_html( __( 'Remove', 'brix' ) ),
				esc_html( __( 'Remove', 'brix' ) ),
				$icn_remove );

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="add"><em>%s</em>%s</span>',
				esc_html( __( 'Add a new row', 'brix' ) ),
				esc_html( __( 'Add a new row', 'brix' ) ),
				$icn_add );

		echo '</div>';

		// COLUMN

		echo '<div class="brix-editing-toolbar-context-wrapper brix-editing-toolbar-column-wrapper">';
			echo '<div class="brix-editing-toolbar-trigger-wrapper">';
				echo '<span class="brix-editing-toolbar-trigger">';
					echo $icn_col;
				echo '</span>';
				printf( '<span class="brix-editing-toolbar-label"><span><em>%s</em>%s</span></span>',
					esc_html__( 'Edit', 'brix' ),
					esc_html__( 'Column', 'brix' )
				);
			echo '</div>';

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="edit"><em>%s</em>%s</span>',
				esc_html( __( 'Edit', 'brix' ) ),
				esc_html( __( 'Edit', 'brix' ) ),
				$icn_edit );

		echo '</div>';

		// BLOCK

		echo '<div class="brix-editing-toolbar-context-wrapper brix-editing-toolbar-block-wrapper">';
			echo '<div class="brix-editing-toolbar-trigger-wrapper">';
				echo '<span class="brix-editing-toolbar-trigger">';
					echo $icn_block;
				echo '</span>';
				printf( '<span class="brix-editing-toolbar-label"><span><em>%s</em>%s <i></i></span></span>',
					esc_html__( 'Edit', 'brix' ),
					esc_html__( 'Block', 'brix' )
				);
			echo '</div>';

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="edit"><em>%s</em>%s</span>',
				esc_html( __( 'Edit', 'brix' ) ),
				esc_html( __( 'Edit', 'brix' ) ),
				$icn_edit );

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="duplicate"><em>%s</em>%s</span>',
				esc_html( __( 'Duplicate', 'brix' ) ),
				esc_html( __( 'Duplicate', 'brix' ) ),
				$icn_duplicate );

			printf( '<span class="brix-editing-btn brix-tooltip" data-title="%s" data-action="remove"><em>%s</em>%s</span>',
				esc_html( __( 'Remove', 'brix' ) ),
				esc_html( __( 'Remove', 'brix' ) ),
				$icn_remove );

		echo '</div>';


		printf( '<span class="brix-editing-clear-btn">%s</span>',
			esc_html( __( 'Clear selection', 'brix' ) )
		);

	echo '</div>';
}

/**
 * Adding data attributes to editable element on frontend preview.
 *
 * @since 1.2
 * @param array $attrs An array of data attributes.
 * @param stdClass $data The object data.
 * @return array
 */
function brix_frontend_editing_data_attrs( $attrs, $data ) {
	if ( ! brix_is_frontend_editing() ) {
		return $attrs;
	}

	$attrs[] = 'data-frontend-editing=1';

	return $attrs;
}

add_filter( 'brix_block_attrs', 'brix_frontend_editing_data_attrs', 10, 2 );
add_filter( 'brix_section_column_data_attrs', 'brix_frontend_editing_data_attrs', 10, 2 );
add_filter( 'brix_section_row_data_attrs', 'brix_frontend_editing_data_attrs', 10, 2 );
add_filter( 'brix_section_attrs', 'brix_frontend_editing_data_attrs', 10, 2 );

/**
 * Return empty text when no builder data is present and we're in preview mode.
 *
 * @since 1.2
 * @param string $text The pristine page content.
 * @return string
 */
function brix_frontend_editing_return_no_data( $text ) {
	if ( brix_is_frontend_editing() ) {
		$text = BrixBuilder::instance()->render_builder( array() );
	}

	return $text;
}

add_filter( 'brix_return_no_data', 'brix_frontend_editing_return_no_data' );

/**
 * Add block control added to the column.
 *
 * @since 1.2
 * @param stdClass $data The column data.
 */
function brix_frontend_editing_add_block_control( $data ) {
	if ( ! brix_is_frontend_editing() ) {
		return;
	}

	$icn_add = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"><path fill="#2F4B59" fill-rule="evenodd" d="M7,5 L7,1.00247329 C7,0.455760956 6.55228475,0 6,0 C5.44386482,0 5,0.448822582 5,1.00247329 L5,5 L1.00247329,5 C0.455760956,5 9.50500275e-17,5.44771525 6.123234e-17,6 C2.71788817e-17,6.55613518 0.448822582,7 1.00247329,7 L5,7 L5,10.9975267 C5,11.544239 5.44771525,12 6,12 C6.55613518,12 7,11.5511774 7,10.9975267 L7,7 L10.9975267,7 C11.544239,7 12,6.55228475 12,6 C12,5.44386482 11.5511774,5 10.9975267,5 L7,5 Z"/></svg>';

	printf( '<span class="brix-frontend-add-block">%s</span>',
		$icn_add
	);
}

add_action( 'brix_block_after_render', 'brix_frontend_editing_add_block_control' );
add_action( 'brix_column_after_render', 'brix_frontend_editing_add_block_control' );

/**
 * Hover element for builder element selection.
 *
 * @since 1.2
 */
function brix_frontend_editing_add_hover_element() {
	if ( ! brix_is_frontend_editing() ) {
		return;
	}

	$icn_section = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z"/></svg>';
	$icn_row     = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M2,8 L16,8 L16,10 L2,10 L2,8 Z"/></svg>';
	$icn_col     = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#3D6072" fill-rule="evenodd" d="M0,1.99508929 C0,0.893231902 0.892622799,0 1.99508929,0 L16.0049107,0 C17.1067681,0 18,0.892622799 18,1.99508929 L18,16.0049107 C18,17.1067681 17.1073772,18 16.0049107,18 L1.99508929,18 C0.893231902,18 0,17.1073772 0,16.0049107 L0,1.99508929 Z M2,2 L16,2 L16,16 L2,16 L2,2 Z M8.25,2 L10,2 L10,16 L8.25,16 L8.25,2 Z"/></svg>';

	echo '<div class="brix-frontend-editing-el-hover" data-context="">';
		printf( '<span class="brix-frontend-editing-el-hover-section">%s</span>', $icn_section );
		printf( '<span class="brix-frontend-editing-el-hover-row">%s</span>', $icn_row );
		printf( '<span class="brix-frontend-editing-el-hover-column">%s</span>', $icn_col );
	echo '</div>';
}

add_action( 'wp_footer', 'brix_frontend_editing_add_hover_element' );

/**
 * Add custom dependencies for frontend scripts when in preview mode.
 *
 * @since 1.2
 * @param array $deps An array of script dependencies.
 * @return array
 */
function brix_pro_frontend_script_dependencies( $deps ) {
	if ( brix_is_frontend_editing() ) {
		$deps[] = 'jquery-ui-sortable';
	}

	return $deps;
}

add_filter( 'brix_pro_frontend_script_dependencies', 'brix_pro_frontend_script_dependencies' );

/**
 * Add the "add section" block at the brix builder container bottom.
 *
 * @since 1.2
 * @param  string $builder The builder markup.
 * @param  stdClass $data The builder data.
 * @return string
 */
function brix_add_section_after_render( $builder, $data ) {
	if ( brix_is_frontend_editing() ) {
		$icn_add = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"><path fill="#2F4B59" fill-rule="evenodd" d="M7,5 L7,1.00247329 C7,0.455760956 6.55228475,0 6,0 C5.44386482,0 5,0.448822582 5,1.00247329 L5,5 L1.00247329,5 C0.455760956,5 9.50500275e-17,5.44771525 6.123234e-17,6 C2.71788817e-17,6.55613518 0.448822582,7 1.00247329,7 L5,7 L5,10.9975267 C5,11.544239 5.44771525,12 6,12 C6.55613518,12 7,11.5511774 7,10.9975267 L7,7 L10.9975267,7 C11.544239,7 12,6.55228475 12,6 C12,5.44386482 11.5511774,5 10.9975267,5 L7,5 Z"/></svg>';
		$builder .= sprintf( '<span class="brix-frontend-editing-add-section">%s</span>', $icn_add );
	}

	return $builder;
}

add_filter( 'brix_after_render', 'brix_add_section_after_render', 10, 2 );

/**
 * The Frontend editing block markup.
 *
 * @since 1.2
 * @param stdClass $data The block data.
 */
function brix_content_block_frontend_editing_placeholder( $data ) {
	if ( ! brix_is_frontend_editing() ) {
		return;
	}

	$blocks = brix_get_blocks();
	$block_icn = '';

	if ( isset( $blocks[$data->_type] ) ) {
		$type = $blocks[$data->_type];

		if ( isset( $type['icon_path'] ) && ! empty( $type['icon_path'] ) && file_exists( $type['icon_path'] ) ) {
			$block_icn = implode( '', file( $type['icon_path'] ) );
		}
	}

	printf( '<span class="brix-block-frontend-editing-placeholder">%s</span>',
		$block_icn
	);
}

add_action( 'brix_block_before_render', 'brix_content_block_frontend_editing_placeholder' );