<?php if ( ! defined( 'AGNCY_COMPANION' ) ) die( 'Forbidden' );

/**
 * Register and load the widget.
 *
 * @since 1.0.0
 */
function agncy_load_social_widget() {
	register_widget( 'Agncy_SocialWidget' );
}

add_action( 'widgets_init', 'agncy_load_social_widget' );

/**
 * Social widget definition.
 *
 * @since 1.0.0
 */
class Agncy_SocialWidget extends WP_Widget {

	/**
	 * Constructor.
	 */
	function __construct()
	{
		parent::__construct(
			'agncy_social_widget',
			__( 'Agncy Social Widget', 'agncy-companion-plugin' ),
			array( 'description' => __( 'Display your social networks links.', 'agncy-companion-plugin' ) )
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
		$networks = $instance[ 'networks' ];

		echo $args[ 'before_widget' ];

		if ( ! empty( $title ) ) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}

		if ( function_exists( 'agncy_social_networks' ) ) {
			$socials = ev_get_option( 'social_fields' );
			$social_networks_names = agncy_social_networks();

			if ( ! empty( $networks ) ) {
				$networks = explode( ',', $networks );

				echo '<div class="agncy-social-widget"><ul>';

				foreach ( $networks as $network ) {
					if ( isset( $socials[ $network ] ) && ! empty( $socials[ $network ] ) ) {
						printf( '<li class="%s"><a href="%s">%s</a></li>',
							'agncy-social-' . esc_html( $network ),
							esc_attr( $socials[ $network ] ),
							esc_html( $social_networks_names[ $network ] )
						);
					}
				}

				echo '</ul></div>';
			}
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
		$networks = '';

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}

		if ( isset( $instance[ 'networks' ] ) ) {
			$networks = $instance[ 'networks' ];
		}

		$social_networks = array_keys( agncy_social_networks() );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'agncy-companion-plugin' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'networks' ); ?>"><?php
				printf( __( 'A comma-separated list of social network names. Pick one from: %s.', 'agncy-companion-plugin' ),
					'<code>' . implode( '</code>, <code>', $social_networks ) . '</code>'
				);
			?></label>

			<input class="widefat" id="<?php echo $this->get_field_id( 'networks' ); ?>" name="<?php echo $this->get_field_name( 'networks' ); ?>" type="text" value="<?php echo esc_attr( $networks ); ?>" />
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
		$instance[ 'networks' ] = ( ! empty( $new_instance[ 'networks' ] ) ) ? esc_html( $new_instance[ 'networks' ] ) : '';

		$instance[ 'networks' ] = str_replace( ' ', '', $instance[ 'networks' ] );

		return $instance;
	}

}