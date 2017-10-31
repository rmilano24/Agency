<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Agncy
 * @since Agncy 1.0.0
 */

$sidebar = agncy_get_page_sidebar();
?>

<?php if ( $sidebar && is_active_sidebar( $sidebar[ 'sidebar' ] ) ) : ?>
	<aside class="agncy-ms">
		<?php dynamic_sidebar( $sidebar[ 'sidebar' ] ); ?>
	</aside>
<?php endif; ?>