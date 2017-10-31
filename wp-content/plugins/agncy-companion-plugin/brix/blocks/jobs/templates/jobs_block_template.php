<?php

$office_id = isset( $data->data->office_id ) && $data->data->office_id ? $data->data->office_id : 0;

$query_args = array(
    'post_type'      => 'agncy_job',
    'posts_per_page' => -1,
    'post_status'    => 'publish'
);

if ( $office_id ) {
    $term = wp_get_object_terms( $office_id, 'agncy_office_taxonomy' );

    if ( ! empty( $term ) ) {
        $query_args[ 'tax_query' ] = array(
            array(
                'taxonomy' => 'agncy_office_taxonomy',
                'field'    => 'id',
                'terms'    => absint( $term[ 0 ]->term_id )
            )
        );
    }
}

$jobs = new WP_Query( $query_args );

?>

<div class="agncy-jobs">
    <?php if ( $jobs->have_posts() ) : ?>
        <ul>
            <?php while ( $jobs->have_posts() ) : ?>
                <?php

                    $jobs->the_post();
                    $term = wp_get_object_terms( get_the_ID(), 'agncy_office_taxonomy' );
                    $office = wp_list_pluck( $term, 'name' );

                ?>

                <li>
                    <?php if ( $office ) : ?>
                        <span class="agncy-job-office"><?php echo esc_html( implode( ', ', $office ) ); ?></span>
                    <?php endif; ?>

                    <div class="agncy-jobs-c">
                        <span class="agncy-job-title"><?php the_title(); ?></span>

                        <p>
                            <a class="agncy-job-apply-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Apply now', 'agncy-companion-plugin' ); ?></a>
                        </p>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p><?php esc_html_e( 'Sorry, there are no positions available at the moment.', 'agncy-companion-plugin' ); ?></p>
    <?php endif; ?>
</div>