<?php

$list_type         = isset( $data->data->list_type ) && ! empty( $data->data->list_type ) ? $data->data->list_type : '';
$master_icon       = isset( $data->data->master_icon ) && ! empty( $data->data->master_icon ) ? $data->data->master_icon : '';
$list_elements     = array();
$classes           = array();
$icon_position     = isset( $data->data->icon_position ) && ! empty( $data->data->icon_position ) ? $data->data->icon_position : 'left';

if ( $list_type == 'simple' ) {
	$simple_list       = isset( $data->data->simple_list ) && ! empty( $data->data->simple_list ) ? $data->data->simple_list : '';
	$list_elements = explode( "\n", $simple_list );
	$classes[] = 'brix-block-list-simple';
}
else if ( $list_type == 'advanced' ) {
	$advanced_list     = isset( $data->data->advanced_list ) && ! empty( $data->data->advanced_list ) ? $data->data->advanced_list : '';
	$list_elements = $advanced_list;
	$classes[] = 'brix-block-list-advanced';
}
?>

<?php if ( ! empty( $list_elements ) ) : ?>
	<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php foreach ( $list_elements as $index => $element ) {
			$icon       = '';
			$icon_style = array();
			$el_classes = '';

			if ( isset( $master_icon->icon ) && ! empty( $master_icon->icon ) ) {
				$icon = brix_get_decoration_icon( $master_icon ) . ' ';
				$el_classes = 'brix-block-list-master-icon';
			}

			if ( $list_type == 'simple' ) {
				printf( '<li class="%s %s">', esc_attr( $el_classes ), esc_attr( 'brix-block-list-' . $index ) );

					if ( $icon_position == 'left' ) {
						echo $icon;
					}

					echo '<span>' . esc_html( $element ) . '</span>';

					if ( $icon_position == 'right' ) {
						echo $icon;
					}

				echo '</li>';
			}
			else if ( $list_type == 'advanced' ) {
				if ( isset( $element->icon->icon ) && ! empty( $element->icon->icon ) ) {
					$icon = brix_get_decoration_icon( $element->icon ) . ' ';
					$el_classes = 'brix-block-list-custom-icon';
				}

				$text = isset( $element->title->text ) ? $element->title->text : '';
				$text = brix_link( $element->title->link, $text, false );

				printf( '<li class="%s %s">', esc_attr( $el_classes ), esc_attr( 'brix-block-list-' . $index ) );

					if ( $icon_position == 'left' ) {
						echo $icon;
					}

					echo '<span>';
						echo wp_kses( $text, wp_kses_allowed_html( 'post' ) );
					echo '</span>';

					if ( $icon_position == 'right' ) {
						echo $icon;
					}

				echo '</li>';
			}
		} ?>
	</ul>
<?php endif; ?>