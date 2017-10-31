<?php

if ( class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * Font families customizer control.
	 *
	 * This control enables to pick font families and their variants and subsets from a
	 * list. Possible sources are Google Fonts, Typekit, or custom stylesheet.
	 */
	class Agncy_Customize_FontFamilies_Control extends WP_Customize_Control {

		/**
		 * The type of the control.
		 *
		 * @var string
		 */
		public $type = 'agncy_typography';

		/**
		 * Render the control interface.
		 *
		 * @since 1.0.0
		 */
		public function render_content() {
			?>
				<input type="hidden" <?php $this->link(); ?>>

				<div class="agncy-font_families">
					<span class="agncy-fc-sh"><?php esc_html_e( 'Font Families', 'agncy' ); ?></span>
					<div class="agncy-ff-c">
						<agncy-customizer-font-family
							v-bind:key="id"
							v-for="( data, id ) in global"
							v-bind:_id="id"
							v-bind:id="id + '-font-family'"
							v-bind:value="global[id]"
							v-on:input="updateControl"
							v-bind:label="getFamilyLabel( id )"
							v-on:refresh_google_fonts_variants="refreshGoogleFontVariants"
						></agncy-customizer-font-family>

						<div class="agncy-ff-add" v-on:click="addFamily">
							<?php echo agncy_svg( 'img/add.svg' ); ?>
						</div>
					</div>

					<div class="agncy-ffi" v-for="( instance, key ) in instances">
						<span class="agncy-fc-sh">{{ getInstanceLabel( key ) }}</span>
						<span class="agncy-fc-sh-h">{{ getInstanceDescription( key ) }}</span>
						<div class="agncy-ffi-c">
							<agncy-customizer-font-family-instance
								v-for="( field, kf ) in instance"
								v-bind:key="kf"
								v-bind:id="kf"
								v-bind:value="instance[kf]"
								v-bind:families="global"
								v-on:input="updateControl"
								v-bind:label="getInstanceSubLabel( key, kf )"
								v-bind:_defaults="instance[kf]._defaults"
							></agncy-customizer-font-family-instance>
						</div>
					</div>
				</div>
			<?php
		}
	}

	/**
	 * Font families selector template.
	 *
	 * @since 1.0.0
	 */
	function agncy_customizer_load_font_families_template() {
		require_once get_template_directory() . '/inc/customizer/templates/font-families.php';
		require_once get_template_directory() . '/inc/customizer/templates/font-family-instance.php';
	}

	add_action( 'customize_controls_print_footer_scripts', 'agncy_customizer_load_font_families_template' );

endif;

/**
 * Define the instance data for the typography field.
 *
 * @since 1.0.0
 * @param array $data The instance data.
 * @param array $rule The instance ruleset.
 * @return object
 */
function agncy_customizer_typography_instance_data( $data, $rule ) {
	/* Default instance data. */
	$instance_data = array(
		'font_family'    => '',
		'variant'        => '',
		'font_size'      => '',
		'line_height'    => '',
		'letter_spacing' => '',
		'text_transform' => ''
	);

	$data = (object) wp_parse_args( $data, $instance_data );

	/* Handling default data. */
	$data->_defaults = array();

	/* Unsetting unnecessary default rules. */
	$rules = current( $rule[ 'selectors' ] );

	$rules[ 'original-font-family' ] = '';

	if ( isset( $rules[ 'font-family' ] ) ) {
		$rules[ 'original-font-family' ] = $rules[ 'font-family' ];
		unset( $rules[ 'font-family' ] );
	}

	foreach ( $rules as $prop => $value ) {
		$prop = str_replace( '-', '_', $prop );
		$prop = trim( $prop, '_' );

		$data->_defaults[ $prop ] = $value;
	}

	return $data;
}

add_filter( 'agncy_customizer_instance_data[mod:agncy_typography]', 'agncy_customizer_typography_instance_data', 10, 2 );

/**
 * Define the global data for the typography field.
 *
 * @since 1.0.0
 * @param array $data The global data.
 * @return object
 */
function agncy_customizer_typography_global_data( $data ) {
	/* Basic font family data. */
	$family_data = array(
		'label' => '',
		'source' => 'google_fonts',
		'google_fonts' => array(
			'font_family'   => '',
			'variants' => '',
			'subsets'  => ''
		),
		'typekit' => array(
			'kitId' => '',
			'font_family' => ''
		),
		'custom' => array(
			'url' => '',
			'font_family' => ''
		),
	);

	$primary_family_data = (object) wp_parse_args( array(
		'label' => __( 'Primary', 'agncy' ),
		'google_fonts' => array(
			'font_family' => 'Overpass',
			'variants' => '300,600,700',
			'subsets' => 'latin'
		)
	), $family_data );

	$secondary_family_data = (object) wp_parse_args( array(
		'label' => __( 'Secondary', 'agncy' ),
		'google_fonts' => array(
			'font_family' => 'Overpass Mono',
			'variants' => 'regular',
			'subsets' => 'latin'
		)
	), $family_data );

	$headings_bold_family_data = (object) wp_parse_args( array(
		'label' => __( 'Headings bold', 'agncy' ),
		'source' => 'custom',
		'custom' => array(
			'font_family' => 'antoniobold',
			'url' => '%themepath%/fonts/stylesheet.css'
		)
	), $family_data );

	$headings_regular_family_data = (object) wp_parse_args( array(
		'label' => __( 'Headings regular', 'agncy' ),
		'source' => 'custom',
		'custom' => array(
			'font_family' => 'antonioregular',
			'url' => '%themepath%/fonts/stylesheet.css'
		)
	), $family_data );

	$headings_light_family_data = (object) wp_parse_args( array(
		'label' => __( 'Headings light', 'agncy' ),
		'source' => 'custom',
		'custom' => array(
			'font_family' => 'antoniolight',
			'url' => '%themepath%/fonts/stylesheet.css'
		)
	), $family_data );

	$data = (object) $data;

	/* Default primary font family. */
	if ( isset( $data->primary ) ) {
		$data->primary = (object) wp_parse_args( $data->primary, $primary_family_data );
	}
	else {
		$data->primary = (object) $primary_family_data;
	}

	/* Default secondary font family. */
	if ( isset( $data->secondary ) ) {
		$data->secondary = (object) wp_parse_args( $data->secondary, $secondary_family_data );
	}
	else {
		$data->secondary = (object) $secondary_family_data;
	}

	/* Default headings bold font family. */
	if ( isset( $data->headings_bold ) ) {
		$data->headings_bold = (object) wp_parse_args( $data->headings_bold, $headings_bold_family_data );
	}
	else {
		$data->headings_bold = (object) $headings_bold_family_data;
	}

	/* Default headings regular font family. */
	if ( isset( $data->headings_regular ) ) {
		$data->headings_regular = (object) wp_parse_args( $data->headings_regular, $headings_regular_family_data );
	}
	else {
		$data->headings_regular = (object) $headings_regular_family_data;
	}

	/* Default headings light font family. */
	if ( isset( $data->headings_light ) ) {
		$data->headings_light = (object) wp_parse_args( $data->headings_light, $headings_light_family_data );
	}
	else {
		$data->headings_light = (object) $headings_light_family_data;
	}

	return $data;
}

add_filter( 'agncy_customizer_global_data[mod:agncy_typography]', 'agncy_customizer_typography_global_data' );