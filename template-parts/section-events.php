<!-- Section 7: Events -->
<?php if ( get_theme_mod( 'educampus_show_events', true ) ) : ?>
<?php 
$events_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_events', true ) ) {
    $events_ornament = ' bg-islamic-ornament';
}
$events_bg_color = get_theme_mod( 'educampus_bg_color_events', '#eef2e8' );
$events_bg_color_end = get_theme_mod( 'educampus_bg_color_end_events', $events_bg_color );
$events_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_events', '135deg' );

$event_query = new WP_Query( array(
    'post_type'      => 'event',
    'posts_per_page' => 2,
) );
?>
<section id="events" class="section-padding<?php echo esc_attr($events_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($events_bg_grad_dir); ?>, <?php echo esc_attr($events_bg_color); ?>, <?php echo esc_attr($events_bg_color_end); ?>);">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_events_badge', 'KEGIATAN KAMPUS' ) ); ?></span>
                <h2 class="h1 font-heading fw-bold text-campus-navy m-0"><?php echo esc_html( get_theme_mod( 'educampus_heading_events_title', 'Agenda Kegiatan Mendatang' ) ); ?></h2>
                <?php 
                $events_desc = get_theme_mod( 'educampus_heading_events_desc', '' );
                if ( ! empty( $events_desc ) ) : ?>
                    <p class="text-campus-muted mt-2 mb-0"><?php echo esc_html( $events_desc ); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?php echo esc_url( home_url( '/agenda' ) ); ?>" class="btn btn-campus-outline font-heading px-4">
                    <?php esc_html_e('Lihat Semua Agenda', 'educampus'); ?>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if ( $event_query->have_posts() ) : ?>
            <?php
            $events_layout = get_theme_mod( 'educampus_events_layout', 'grid' );
            $col_class = ( $events_layout === 'list' ) ? 'col-12' : 'col-lg-6';

            while ( $event_query->have_posts() ) : $event_query->the_post();
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $events_layout === 'list' ) : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white p-3 transform-hover">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="rounded-3 overflow-hidden shadow-sm border border-light text-center" style="min-width: 70px;">
                                        <div class="bg-campus-navy text-white py-1 px-2 font-heading fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                            <?php echo get_the_time('M'); ?>
                                        </div>
                                        <div class="bg-white py-1 px-2">
                                            <span class="fs-4 font-heading fw-bold text-campus-navy d-block mb-0 lh-1"><?php echo get_the_time('d'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <span class="badge bg-secondary bg-opacity-10 text-campus-navy px-2 py-1" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> <?php echo get_the_time('H:i'); ?> <?php esc_html_e('WIB', 'educampus'); ?></span>
                                    </div>
                                    <h3 class="h6 font-heading fw-bold text-campus-navy mb-0">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="col-auto ms-auto">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-campus-outline btn-sm font-heading">
                                        <?php esc_html_e('Detail', 'educampus'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white p-4 h-100 transform-hover">
                            <div class="row align-items-center">
                                <div class="col-auto text-center mb-3 mb-sm-0">
                                    <div class="rounded-3 overflow-hidden shadow-sm border border-light text-center" style="min-width: 80px;">
                                        <div class="bg-campus-navy text-white py-1 px-2 font-heading fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <?php echo get_the_time('M'); ?>
                                        </div>
                                        <div class="bg-white py-2 px-3">
                                            <span class="display-6 font-heading fw-bold text-campus-navy d-block mb-0 lh-1"><?php echo get_the_time('d'); ?></span>
                                            <span class="text-campus-muted small font-heading" style="font-size: 0.7rem;"><?php echo get_the_time('Y'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <span class="badge bg-secondary bg-opacity-10 text-campus-navy small rounded-pill mb-2"><i class="bi bi-clock me-1"></i> <?php echo get_the_time('H:i'); ?> <?php esc_html_e('WIB', 'educampus'); ?></span>
                                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="text-campus-muted small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <div class="col-12 text-center py-5">
                    <div class="py-4">
                        <i class="bi bi-calendar-event text-campus-gold display-1 mb-3 opacity-50"></i>
                        <h4 class="font-heading fw-bold text-campus-navy mb-2">Belum Ada Agenda</h4>
                        <p class="text-campus-muted mb-0">Agenda kegiatan akan segera diumumkan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
