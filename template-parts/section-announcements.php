<!-- Section 6b: Pengumuman -->
<?php if ( get_theme_mod( 'educampus_show_announcements', true ) ) : ?>
<?php 
$ann_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_announcements', true ) ) {
    $ann_ornament = ' bg-islamic-ornament';
}
$ann_bg_color = get_theme_mod( 'educampus_bg_color_announcements', '#f8f9fa' );
$ann_bg_color_end = get_theme_mod( 'educampus_bg_color_end_announcements', $ann_bg_color );
$ann_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_announcements', '135deg' );
?>
<section id="pengumuman" class="section-padding<?php echo esc_attr($ann_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($ann_bg_grad_dir); ?>, <?php echo esc_attr($ann_bg_color); ?>, <?php echo esc_attr($ann_bg_color_end); ?>);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div>
                <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_announcements_badge', 'PENGUMUMAN' ) ); ?></span>
                <h2 class="h1 font-heading fw-bold text-campus-navy mb-0"><?php echo esc_html( get_theme_mod( 'educampus_heading_announcements_title', 'Pengumuman Resmi Kampus' ) ); ?></h2>
                <?php 
                $ann_desc = get_theme_mod( 'educampus_heading_announcements_desc', '' );
                if ( ! empty( $ann_desc ) ) : ?>
                    <p class="text-campus-muted mt-2 mb-0"><?php echo esc_html( $ann_desc ); ?></p>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url( home_url( '/pengumuman' ) ); ?>" class="btn btn-campus-primary font-heading px-4 py-2 mt-3 mt-md-0">
                <?php esc_html_e('Semua Pengumuman', 'educampus'); ?> <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <?php
        $ann_layout = get_theme_mod( 'educampus_announcements_layout', 'grid' );
        $col_class = ( $ann_layout === 'list' ) ? 'col-12' : 'col-md-6';
        
        $announcement_query = new WP_Query( array(
            'post_type'      => 'announcement',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $announcement_query->have_posts() ) :
        ?>
            <div class="row g-4">
                <?php while ( $announcement_query->have_posts() ) : $announcement_query->the_post(); ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 transform-hover">
                            <div class="card-body p-4 d-flex align-items-start gap-3">
                                <!-- TOA Megaphone Icon Block -->
                                <div class="flex-shrink-0 text-center">
                                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; background-color: rgba(197, 160, 89, 0.15);">
                                        <i class="bi bi-megaphone-fill fs-3" style="color: var(--color-secondary-dark);"></i>
                                    </div>
                                </div>
                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-danger bg-opacity-10 text-danger small rounded-pill px-2 py-1">
                                            <i class="bi bi-megaphone-fill me-1"></i> <?php esc_html_e('Pengumuman', 'educampus'); ?>
                                        </span>
                                        <?php if ( (time() - get_the_time('U')) < 259200 ) : // 3 days ?>
                                            <span class="badge bg-success bg-opacity-10 text-success small rounded-pill px-2 py-1"><?php esc_html_e('Baru', 'educampus'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="h6 font-heading fw-bold text-campus-navy mb-2 lh-base">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="text-campus-muted small mb-2"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading">
                                        <?php esc_html_e('Baca Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <!-- Static Fallback Pengumuman -->
            <div class="row g-4">
                <?php
                $fallback_announcements = array(
                    array(
                        'title' => 'Jadwal Ujian Akhir Semester (UAS) Genap 2025/2026',
                        'desc'  => 'Pelaksanaan UAS Genap dijadwalkan pada tanggal 23 Juni – 5 Juli 2026. Mahasiswa wajib memastikan status KRS aktif.',
                        'new'   => true,
                    ),
                    array(
                        'title' => 'Pengumuman Hasil Seleksi Beasiswa Unggulan Batch II',
                        'desc'  => 'Daftar nama penerima beasiswa unggulan telah dirilis. Silakan cek portal SIAKAD masing-masing.',
                        'new'   => true,
                    ),
                    array(
                        'title' => 'Perpanjangan Batas Pembayaran UKT Semester Ganjil',
                        'desc'  => 'Batas akhir pembayaran UKT diperpanjang hingga 20 Juli 2026. Hubungi bagian keuangan untuk cicilan.',
                        'new'   => false,
                    ),
                    array(
                        'title' => 'Pendaftaran Wisuda Periode September 2026 Dibuka',
                        'desc'  => 'Calon wisudawan dapat mendaftar melalui portal SIAKAD mulai 1 – 30 Juni 2026.',
                        'new'   => false,
                    ),
                );
                foreach ( $fallback_announcements as $ann ) :
                ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 transform-hover">
                            <div class="card-body p-4 d-flex align-items-start gap-3">
                                <!-- TOA Megaphone Icon Block -->
                                <div class="flex-shrink-0 text-center">
                                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; background-color: rgba(197, 160, 89, 0.15);">
                                        <i class="bi bi-megaphone-fill fs-3" style="color: var(--color-secondary-dark);"></i>
                                    </div>
                                </div>
                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-danger bg-opacity-10 text-danger small rounded-pill px-2 py-1">
                                            <i class="bi bi-megaphone-fill me-1"></i> <?php esc_html_e('Pengumuman', 'educampus'); ?>
                                        </span>
                                        <?php if ( $ann['new'] ) : ?>
                                            <span class="badge bg-success bg-opacity-10 text-success small rounded-pill px-2 py-1"><?php esc_html_e('Baru', 'educampus'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="h6 font-heading fw-bold text-campus-navy mb-2 lh-base">
                                        <a href="#" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php echo $ann['title']; ?>
                                        </a>
                                    </h3>
                                    <p class="text-campus-muted small mb-2"><?php echo $ann['desc']; ?></p>
                                    <a href="#" class="text-campus-gold text-decoration-none small fw-bold font-heading">
                                        <?php esc_html_e('Baca Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
