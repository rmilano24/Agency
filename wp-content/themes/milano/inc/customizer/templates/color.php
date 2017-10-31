<script type="text/x-template" id="agncy-customizer-color">
	<div class="agncy-f-c-c">
		<span>{{ agncy.customizer.colors.structure[ _group ].fields[_key] }}</span>

		<agncy-color v-bind:value="value" v-on:input="updateColor"></agncy-color>
	</div>
</script>