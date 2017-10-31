<?php

$columns = isset( $data->data->columns ) && $data->data->columns ? $data->data->columns : 3;

$query_args = array(
    'post_type'      => 'agncy_team_member',
    'posts_per_page' => -1,
    'post_status'    => 'publish'
);

$team = new WP_Query( $query_args );

$class = 'agncy-team';

if ( $columns ) {
    $class .= ' agncy-team-col-' . $columns;
}

$image_size = isset( $data->data->image_size ) ? $data->data->image_size : 'full';

?>

<div class="<?php echo esc_attr( $class ); ?>">
    <?php if ( $team->have_posts() ) : ?>
        <ul>
            <?php while ( $team->have_posts() ) : ?>
                <?php
                    $team->the_post();

                    $role = get_post_meta( get_the_ID(), 'role', true );
                    $has_bio = (bool) get_post_meta( get_the_ID(), 'has_bio', true );
                ?>

                <li class="agncy-team-member">
                    <?php
                        $image = get_post_meta( get_the_ID(), 'image', true );

                        if ( isset( $image[ 'desktop' ] ) && isset( $image[ 'desktop' ][ 1 ][ 'id' ] ) && ! empty( $image[ 'desktop' ][ 1 ][ 'id' ] ) ) {
                            $featured_image = ev_get_image( $image[ 'desktop' ][ 1 ][ 'id' ], $image_size );

                            if ( $featured_image ) {
                                printf( '<img class="agncy-team-member-image" src="%s">',
                                    esc_attr( $featured_image )
                                );
                            }
                        }
                    ?>
                    <span class="agncy-team-member-title"><?php the_title(); ?></span>

                    <?php if ( $role ) : ?>
                        <span class="agncy-team-member-role"><?php echo esc_html( $role ); ?></span>
                    <?php endif; ?>

                    <?php if ( $has_bio ) : ?>
                        <p>
                            <a class="agncy-team-member-bio-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View bio', 'agncy-companion-plugin' ); ?></a>
                        </p>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>