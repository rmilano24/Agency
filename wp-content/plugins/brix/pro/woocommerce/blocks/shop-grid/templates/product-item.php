<?php
	/**
	 * woocommerce_shop_loop hook.
	 *
	 * @hooked WC_Structured_Data::generate_product_data() - 10
	 */
	do_action( 'woocommerce_shop_loop' );
?>

<?php wc_get_template_part( 'content', 'product' ); ?>