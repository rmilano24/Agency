<script type="text/x-template" id="agncy-customizer-font-family-instance">
	<div class="agncy-font-family-instance agncy-fc-m">
		<div class="agncy-fc-h" v-on:click="toggleFamilyInstance">
			<span class="agncy-fc-l">{{ this.label }}</span>
			<span class="agncy-fc-lh">{{ fontInfo }}</span>
			<span class="agncy-fc-e">
				<span class="agncy-fc-ie"><?php echo agncy_svg( 'img/edit.svg' ); ?></span>
				<span class="agncy-fc-ic"><?php echo agncy_svg( 'img/close.svg' ); ?></span>
			</span>
		</div>

		<div class="agncy-fc-c">
			<div class="agncy-f">
				<label class="agncy-f-l"><?php esc_html_e( 'Family', 'agncy' ); ?></label>
				<select v-model="value.font_family" v-on:change="changeFamily">
					<option v-for="( family, key ) in families" v-bind:value="key">{{ family.label }}</option>
				</select>
			</div>

			<div v-if="value.font_family != ''">
				<div class="agncy-f" v-if="families[value.font_family].source == 'google_fonts' || families[value.font_family].source == 'typekit'">
					<label class="agncy-f-l"><?php esc_html_e( 'Variant', 'agncy' ); ?></label>

					<select class="agncy-fi-v" v-model="value.variant">
						<option v-for="variant in getFamilyVariants( families, value.font_family )" v-bind:value="variant">{{ variant }}</option>
					</select>
				</div>

				<div class="agncy-fc-fpr">
					<div class="agncy-f">
						<label class="agncy-f-l"><?php esc_html_e( 'Size', 'agncy' ); ?></label>
						<input type="text" v-model="value.font_size" v-bind:placeholder="this._defaults.font_size">
					</div>
					<div class="agncy-f">
						<label class="agncy-f-l"><?php esc_html_e( 'Height', 'agncy' ); ?></label>
						<input type="text" v-model="value.line_height" v-bind:placeholder="this._defaults.line_height">
					</div>
					<div class="agncy-f">
						<label class="agncy-f-l"><?php esc_html_e( 'Spacing', 'agncy' ); ?></label>
						<input type="text" v-model="value.letter_spacing" v-bind:placeholder="this._defaults.letter_spacing">
					</div>
				</div>

				<div class="agncy-f">
					<label class="agncy-f-l"><?php esc_html_e( 'Transform', 'agncy' ); ?></label>
					<select v-model="value.text_transform">
						<option value="none"><?php esc_html_e( 'None', 'agncy' ); ?></option>
						<option value="uppercase"><?php esc_html_e( 'Uppercase', 'agncy' ); ?></option>
						<option value="lowercase"><?php esc_html_e( 'Lowercase', 'agncy' ); ?></option>
						<option value="capitalize"><?php esc_html_e( 'Capitalize', 'agncy' ); ?></option>
					</select>
				</div>
			</div>
		</div>

	</div>
</script>