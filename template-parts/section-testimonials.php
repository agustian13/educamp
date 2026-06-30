<!-- Section 10: Alumni Testimonials -->
<?php if ( get_theme_mod( 'educampus_show_testimonials', true ) ) : ?>
<?php 
$testimonials_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_testimonials', true ) ) {
    $testimonials_ornament = ' bg-islamic-ornament';
}
$testimonials_bg_color = get_theme_mod( 'educampus_bg_color_testimonials', '#eef3f8' );
$testimonials_bg_color_end = get_theme_mod( 'educampus_bg_color_end_testimonials', $testimonials_bg_color );
$testimonials_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_testimonials', '135deg' );
?>
<section id="testimonials" class="section-padding<?php echo esc_attr($testimonials_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($testimonials_bg_grad_dir); ?>, <?php echo esc_attr($testimonials_bg_color); ?>, <?php echo esc_attr($testimonials_bg_color_end); ?>);">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_testimonials_badge', 'TESTIMONI ALUMNI' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_testimonials_title', 'Apa Kata Mereka Tentang Kami?' ) ); ?></h2>
            <?php 
            $testimonials_desc = get_theme_mod( 'educampus_heading_testimonials_desc', '' );
            if ( ! empty( $testimonials_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $testimonials_desc ); ?></p>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Bootstrap Carousel for Testimonials -->
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="card border-0 shadow-campus-soft rounded-campus p-5 bg-white text-center position-relative">
                                <i class="bi bi-quote position-absolute text-campus-gold opacity-10" style="font-size: 6rem; top: 10px; left: 30px;"></i>
                                <p class="lead font-body text-campus-navy mb-4 position-relative z-1">
                                    <?php esc_html_e('"Belajar di EduCampus memberikan saya landasan teknis yang sangat kuat serta wawasan enterpreneurship. Fasilitas lab komputasi awan di kampus membuat saya siap langsung terjun bekerja di unicorn teknologi Singapura setelah lulus."', 'educampus'); ?>
                                </p>
                                <h4 class="h5 font-heading fw-bold text-campus-navy mb-1"><?php esc_html_e('Rian Hidayat, S.Kom.', 'educampus'); ?></h4>
                                <small class="text-campus-gold"><?php esc_html_e('Alumni Informatika 2022 - Cloud Architect at ByteDance', 'educampus'); ?></small>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card border-0 shadow-campus-soft rounded-campus p-5 bg-white text-center position-relative">
                                <i class="bi bi-quote position-absolute text-campus-gold opacity-10" style="font-size: 6rem; top: 10px; left: 30px;"></i>
                                <p class="lead font-body text-campus-navy mb-4 position-relative z-1">
                                    <?php esc_html_e('"Dosen-dosen di Fakultas Ekonomi sangat suportif. Mereka memfasilitasi magang bersertifikat selama 1 tahun penuh di Kantor Akuntan Publik Big Four, yang mempercepat karir saya sebagai analis keuangan senior."', 'educampus'); ?>
                                </p>
                                <h4 class="h5 font-heading fw-bold text-campus-navy mb-1"><?php esc_html_e('Siti Nurhaliza, S.E.', 'educampus'); ?></h4>
                                <small class="text-campus-gold"><?php esc_html_e('Alumni Manajemen 2021 - Financial Analyst at Ernst & Young', 'educampus'); ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button class="btn btn-campus-outline rounded-circle p-2 d-flex align-items-center justify-content-center" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" style="width: 40px; height: 40px;">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="btn btn-campus-outline rounded-circle p-2 d-flex align-items-center justify-content-center" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" style="width: 40px; height: 40px;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
