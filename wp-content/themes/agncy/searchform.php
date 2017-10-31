<?php
	global $searchform_index;

	$searchform_index = (int) $searchform_index;
	$searchform_index++;
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="search-<?php echo esc_attr( $searchform_index ); ?>"><?php esc_html_e( 'Search for:', 'agncy' ) ?></label>
		<input type="text" value="" id="search-<?php echo esc_attr( $searchform_index ); ?>" name="s" class="s" placeholder="<?php echo esc_attr( esc_html__( 'Search&#8230;', 'agncy' ) ); ?>">
		<button type="submit" class="searchsubmit" value="Search"><?php echo agncy_load_svg( 'img/search.svg' ); ?></button>
	</div>
</form>