<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Add the inline style for the gutter and the baseline values of specific
 * breakpoints.
 *
 * @since 1.0.0
 * @param string $media_key The breakpoint key.
 */
function brix_apply_media_query_spacing( $media_key = '' ) {
	$gutter = $baseline = $wrap = '';

	if ( ! $media_key ) {
		$gutter   = brix_get_gutter();
		$baseline = brix_get_baseline();
	}
	else {
		$breakpoints = brix_breakpoints();

		if ( isset( $breakpoints[$media_key]['gutter'] ) ) {
			$gutter = $breakpoints[$media_key]['gutter'];
		}

		if ( isset( $breakpoints[$media_key]['baseline'] ) ) {
			$baseline = $breakpoints[$media_key]['baseline'];
		}

		$wrap = $breakpoints[$media_key]['media_query'];
	}

	$gutter   = str_replace( ',', '.', $gutter );
	$baseline = str_replace( ',', '.', $baseline );

	$gutter_val    = floatval( $gutter );
	$gutter_unit   = str_replace( $gutter_val, '', $gutter );
	$baseline_val  = floatval( $baseline );
	$baseline_unit = str_replace( $baseline_val, '', $baseline );
	$media         = brix_get_media_mobile();

	if ( ! $gutter_unit ) {
		$gutter_unit = 'px';
	}

	if ( ! $baseline_unit ) {
		$baseline_unit = 'px';
	}

	$gutter_style  = '';

	if ( $gutter !== '' || $baseline !== '' || ( $media !== '' && $media === $media_key ) ) {
		if ( ! empty( $wrap ) ) {
			$gutter_style .= $wrap . '{';
		}

			if ( $media !== '' && $media === $media_key ) {
				$gutter_style .= '.brix-section-column,.brix-subsection{';
					$gutter_style .= 'width: 100%;min-height:0;';
				$gutter_style .= '}';
			}

			if ( $gutter !== '' || $baseline !== '' ) {
				$gutter_style .= '.brix-section-column-block{';

					if ( $gutter !== '' ) {
						$gutter_style .= 'padding-left:' . $gutter_val/2 . $gutter_unit . ';';
						$gutter_style .= 'padding-right:' . $gutter_val/2 . $gutter_unit . ';';
					}

					if ( $baseline !== '' ) {
						$gutter_style .= 'padding-top:' . $baseline_val/2 . $baseline_unit . ';';
						$gutter_style .= 'padding-bottom:' . $baseline_val/2 . $baseline_unit . ';';
					}

				$gutter_style .= '}';
			}

		if ( ! empty( $wrap ) ) {
			$gutter_style .= '}';
		}
	}

	// Disable gutter when loaded inside the content
	if ( $gutter !== '' ) {
		if ( ! empty( $wrap ) ) {
			$gutter_style .= $wrap . '{';
		}

			$gutter_style .= '.entry-content .brix-builder{';
				$gutter_style .= 'margin-left: -' . $gutter_val/2 . $gutter_unit . ';';
				$gutter_style .= 'margin-right: -' . $gutter_val/2 . $gutter_unit . ';';
			$gutter_style .= '}';

		if ( ! empty( $wrap ) ) {
			$gutter_style .= '}';
		}
	}

	brix_fw()->frontend()->add_inline_style( $gutter_style );
}

/**
 * Add the inline style for the gutter and the baseline values of all the breakpoints.
 *
 * @since 1.0.0
 */
function brix_apply_spacing() {
	if ( ! BrixBuilder::instance()->is_frontend_using_builder() ) {
		return;
	}

	brix_apply_media_query_spacing();

	$breakpoints = brix_breakpoints();

	if ( isset( $breakpoints['desktop'] ) ) {
		unset( $breakpoints['desktop'] );
	}

	foreach ( $breakpoints as $id => $breakpoint ) {
		brix_apply_media_query_spacing( $id );
	}
}

add_action( 'get_header', 'brix_apply_spacing' );