<?php

if ( class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * Colors customizer control.
	 */
	class Agncy_Customize_Colors_Control extends WP_Customize_Control {

		/**
		 * The type of the control.
		 *
		 * @var string
		 */
		public $type = 'agncy_color';

		/**
		 * Render the control interface.
		 *
		 * @since 1.0.0
		 */
		public function render_content() {
			?>
				<input type="hidden" <?php $this->link(); ?>>

				<div class="agncy-colors">
					<div class="agncy-c-g-c" v-bind:key="id" v-for="( group, id ) in this.$data">
						<div v-if="typeof agncy.customizer.colors.structure[ id ] !== 'undefined'">
							<span class="agncy-fc-sh">{{ agncy.customizer.colors.structure[ id ].label }}</span>

							<div class="agncy-c-c">
								<agncy-customizer-color
									v-for="( control, kc ) in group"
									v-model="control"
									v-bind:_group="id"
									v-bind:key="kc"
									v-bind:_key="kc"
									v-on:input="updateControl"
								></agncy-customizer-color>
							</div>
						</div>
					</div>
				</div>
			<?php
		}
	}

	/**
	 * Color template.
	 *
	 * @since 1.0.0
	 */
	function agncy_customizer_load_color_template() {
		require_once get_template_directory() . '/inc/customizer/templates/color.php';
	}

	add_action( 'customize_controls_print_footer_scripts', 'agncy_customizer_load_color_template' );

	/**
	 * Define the instance data for the color field.
	 *
	 * @since 1.0.0
	 * @param array $data The instance data.
	 * @param array $rule The instance ruleset.
	 * @return object
	 */
	function agncy_customizer_colors_instance_data( $data, $rule ) {
		/* Default instance data. */
		$instance_data = array(
			'color' => '',
		);

		$data = (object) wp_parse_args( $data, $instance_data );

		/* Handling default data. */
		$data->_defaults = array();
		$rules = current( $rule[ 'selectors' ] );

		foreach ( $rules as $prop => $value ) {
			$prop = str_replace( '-', '_', $prop );
			$prop = trim( $prop, '_' );

			$data->_defaults[ $prop ] = $value;
		}

		return $data;
	}

	add_filter( 'agncy_customizer_instance_data[mod:agncy_colors]', 'agncy_customizer_colors_instance_data', 10, 2 );

endif;