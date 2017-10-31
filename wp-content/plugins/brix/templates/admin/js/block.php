<div class="brix-block brix-block-{{ type }} brix-draggable" data-type="{{ type }}">

	<div class="brix-block-inner-wrapper">
		{{{ render_admin() }}}
	</div>

	<div class="brix-block-action-toolbar">
		<span class="brix-block-edit brix-tooltip" data-title="<?php esc_attr_e( 'Edit', 'brix' ); ?>"></span>
		<span class="brix-duplicate brix-block-duplicate brix-tooltip" data-title="<?php esc_attr_e( 'Clone', 'brix' ); ?>"></span>
		<span class="brix-remove brix-block-remove"></span>
	</div>
</div>