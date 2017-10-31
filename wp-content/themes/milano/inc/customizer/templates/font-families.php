<script type="text/x-template" id="agncy-customizer-font-family">
	<div class="agncy-font-family agncy-fc-m">
		<div class="agncy-fc-h" v-on:click="toggleFamilyInstance">
			<span class="agncy-fc-l">{{ this.label }}
				<span class="agncy-fc-ir" v-on:click="remove( this._id )" v-if="value._custom">Remove</span>
			</span>
			<span class="agncy-fc-lh">{{ familyInfo }}</span>
			<span class="agncy-fc-e">
				<span class="agncy-fc-ie"><?php echo agncy_svg( 'img/edit.svg' ); ?></span>
				<span class="agncy-fc-ic"><?php echo agncy_svg( 'img/close.svg' ); ?></span>
			</span>
		</div>

		<div class="agncy-fc-c">
			<div class="agncy-f">
				<span class="agncy-f-l"><?php esc_html_e( 'Label', 'agncy' ); ?></span>
				<input type="text" v-model="value.label">
			</div>

			<div class="agncy-f">
				<agncy-graphic-radio v-model="value.source" v-bind:options="getFontSources()" class="agncy-f-gr-horz"></agncy-graphic-radio>
			</div>

			<div v-if="value.source == 'google_fonts'">
				<div class="agncy-f">
					<span class="agncy-f-l"><?php esc_html_e( 'Family', 'agncy' ); ?></span>
					<agncy-select v-model="value.google_fonts.font_family" v-on:input="refreshGoogleFont" v-bind:options="this.getFontFamiliesForSelect()" max="1"></agncy-select>
				</div>

				<div v-show="value.google_fonts.font_family != ''">
					<div class="agncy-f">
						<span class="agncy-f-l"><?php esc_html_e( 'Variants', 'agncy' ); ?></span>
						<agncy-select v-model="value.google_fonts.variants" v-bind:options="googleFontVariants" class="agncy-font-variants"></agncy-select>
					</div>

					<div class="agncy-f">
						<span class="agncy-f-l"><?php esc_html_e( 'Subsets', 'agncy' ); ?></span>
						<agncy-select v-model="value.google_fonts.subsets" v-bind:options="googleFontSubsets" class="agncy-font-subsets"></agncy-select>
					</div>
				</div>
			</div>
			<div v-if="value.source == 'typekit'">
				<div class="agncy-f">
					<span class="agncy-f-l"><?php esc_html_e( 'Kit ID', 'agncy' ); ?></span>
					<input type="text" v-model="value.typekit.kitId">
				</div>

				<div class="agncy-f" v-if="value.typekit.kitId">
					<span class="agncy-f-l"><?php esc_html_e( 'Family', 'agncy' ); ?></span>
					<input type="text" v-model="value.typekit.font_family">
				</div>
			</div>
			<div v-if="value.source == 'custom'">
				<div class="agncy-f">
					<span class="agncy-f-l"><?php esc_html_e( 'Family', 'agncy' ); ?></span>
					<input type="text" v-model="value.custom.font_family">
				</div>

				<div class="agncy-f">
					<span class="agncy-f-l"><?php esc_html_e( 'Stylesheet URL', 'agncy' ); ?></span>
					<input type="text" v-model="value.custom.url">
				</div>
			</div>
		</div>
	</div>
</script>