<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Register and load the widget.
 *
 * @since 1.0.0
 */
function agncy_load_office_widget() {
	register_widget( 'Agncy_OfficeWidget' );
}

add_action( 'widgets_init', 'agncy_load_office_widget' );

/**
 * Social widget definition.
 *
 * @since 1.0.0
 */
class Agncy_OfficeWidget extends WP_Widget {

	/**
	 * Constructor.
	 */
	function __construct()
	{
		parent::__construct(
			'agncy_office_widget',
			__( 'Agncy Office Widget', 'agncy-companion-plugin' ),
			array( 'description' => __( 'Display office information.', 'agncy-companion-plugin' ) )
		);
	}

	/**
	 * Widget output.
	 *
	 * @since 1.0.0
	 * @param array $args The widget arguments.
	 * @param array $instance The widget instance.
	 */
	public function widget( $args, $instance )
	{
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$office_id = $instance[ 'office_id' ];

		if ( empty( $office_id ) ) {
			return;
		}

		$office = get_post( $office_id );

		echo $args[ 'before_widget' ];

		if ( empty( $title ) ) {
			$title = $office->post_title;
		}

		if ( ! empty( $title ) ) {
			echo $args[ 'before_title' ] . esc_html( $title ) . $args[ 'after_title' ];
		}

		$address  = true;
		$contacts = true;
		$page_id  = false;

		if ( $address ) {
			$address = get_post_meta( $office_id, 'address', true );

			if ( $address ) {
				printf( '<div class="agncy-office-address">%s</div>',
					wp_kses_post( wpautop( $address ) )
				);
			}
		}

		if ( $contacts ) {
			$meta = get_post_meta( $office_id, 'meta', true );

			if ( ! empty( $meta ) ) {
				echo '<ul class="agncy-office-meta">';

				foreach ( $meta as $m ) {
					$icon = '';
					$value = esc_html( $m[ 'value' ] );

					if ( $m['meta'] == 'email' ) {
						$icon = agncy_companion_load_svg( 'assets/img/email.svg' );
						$value = sprintf( '<a href="mailto:%s" target="_blank">%s</a>', esc_html( $value ), esc_html( $value ) );
					}
					if ( $m['meta'] == 'phone' ) {
						$icon = agncy_companion_load_svg( 'assets/img/phone.svg' );
					}
					if ( $m['meta'] == 'fax' ) {
						$icon = agncy_companion_load_svg( 'assets/img/fax.svg' );
					}
					if ( $m['meta'] == 'skype' ) {
						$icon = agncy_companion_load_svg( 'assets/img/skype.svg' );
						$value = sprintf( '<a href="skype:%s?chat">%s</a>', esc_html( $value ), esc_html( $value ) );
					}

					printf( '<li class="agncy-office-meta-%s">%s %s</li>',
						esc_attr( $m[ 'meta' ] ),
						$icon,
						$value
					);
				}

				echo '</ul>';
			}
		}

		if ( $page_id ) {
			printf( '<p><a class="agncy-office-view-link" href="%s">%s</a></p>',
				wp_kses_post( get_permalink( $page_id ) ),
				esc_html( __( 'View office', 'agncy-companion-plugin' ) )
			);
		}

		echo $args[ 'after_widget' ];
	}

	/**
	 * Widget backend.
	 *
	 * @since 1.0.0
	 * @param array $instance The widget instance.
	 */
	public function form( $instance )
	{
		$title = '';
		$office_id = '';

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}

		if ( isset( $instance[ 'office_id' ] ) ) {
			$office_id = $instance[ 'office_id' ];
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'agncy-companion-plugin' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'office_id' ); ?>"><?php
				esc_html_e( 'Office', 'agncy-companion-plugin' );
			?></label>

			<select class="widefat" name="<?php echo $this->get_field_name( 'office_id' ); ?>" id="<?php echo $this->get_field_id( 'office_id' ); ?>">
				<?php foreach ( agncy_get_offices_for_select() as $id => $name ) : ?>
					<option value="<?php echo esc_attr( $id ); ?>" <?php echo $id == $office_id ? esc_attr( 'selected' ) : ''; ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new.
	 *
	 * @since 1.0.0
	 * @param array $new_instance The new widget instance.
	 * @param array $old_instance The old widget instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance[ 'title' ] = ( ! empty( $new_instance[ 'title' ] ) ) ? esc_html( $new_instance[ 'title' ] ) : '';
		$instance[ 'office_id' ] = ( ! empty( $new_instance[ 'office_id' ] ) ) ? esc_html( $new_instance[ 'office_id' ] ) : '';

		return $instance;
	}

}