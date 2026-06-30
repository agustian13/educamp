<!-- Section 4: Faculties -->
<?php if ( get_theme_mod( 'educampus_show_faculties', true ) ) : ?>
<?php 
$faculties_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_faculties', true ) ) {
    $faculties_ornament = ' bg-islamic-ornament';
}
$faculties_bg_color = get_theme_mod( 'educampus_bg_color_faculties', '#f0ebe0' );
$faculties_bg_color_end = get_theme_mod( 'educampus_bg_color_end_faculties', $faculties_bg_color );
$faculties_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_faculties', '135deg' );
?>
<section id="faculties" class="section-padding<?php echo esc_attr($faculties_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($faculties_bg_grad_dir); ?>, <?php echo esc_attr($faculties_bg_color); ?>, <?php echo esc_attr($faculties_bg_color_end); ?>);">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_faculties_badge', 'FAKULTAS' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_faculties_title', 'Fakultas & Sekolah Pascasarjana' ) ); ?></h2>
            <?php 
            $fac_desc = get_theme_mod( 'educampus_heading_faculties_desc', 'EduCampus memiliki berbagai rumpun ilmu sains, humaniora, dan teknik yang terintegrasi secara profesional.' );
            if ( ! empty( $fac_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $fac_desc ); ?></p>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php
            $fac_layout = get_theme_mod( 'educampus_faculties_layout', 'grid' );
            $col_class = ( $fac_layout === 'list' ) ? 'col-12 col-md-6' : 'col-sm-6 col-md-6 col-lg-3';
            
            // Fetch CPT Faculty
            $faculty_query = new WP_Query( array(
                'post_type'      => 'faculty',
                'posts_per_page' => 4,
            ) );

            if ( $faculty_query->have_posts() ) :
                while ( $faculty_query->have_posts() ) : $faculty_query->the_post();
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $fac_layout === 'list' ) : ?>
                        <!-- Horizontal List Layout -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-row align-items-center p-3 gap-3">
                            <div class="flex-shrink-0" style="width: 80px; height: 80px;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'campus-square', array( 'class' => 'w-100 h-100 object-fit-cover rounded-3' ) ); ?>
                                <?php else : ?>
                                    <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                        <i class="bi bi-building fs-3"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_title(); ?></h3>
                                <p class="card-text text-campus-muted small mb-2"><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading">
                                    <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Standard Grid Layout -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="ratio ratio-4x3">
                                    <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'object-fit-cover img-fluid' ) ); ?>
                                </div>
                            <?php else : ?>
                                <div class="ratio ratio-4x3 bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                    <i class="bi bi-building fs-1"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-3"><?php the_title(); ?></h3>
                                <p class="card-text text-campus-muted small flex-grow-1"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-3">
                                    <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php 
                endwhile;
                wp_reset_postdata();
            else :
                // Static premium placeholder fallback
                $faculties_placeholder = array(
                    array( 'name' => 'Fakultas Teknik & Ilmu Komputer', 'desc' => 'Menyelenggarakan program studi Informatika, Teknik Sipil, Elektro, dan Sistem Informasi.' ),
                    array( 'name' => 'Fakultas Ekonomi & Bisnis', 'desc' => 'Program studi Manajemen, Akuntansi, dan Ekonomi Pembangunan berstandar internasional.' ),
                    array( 'name' => 'Fakultas Hukum & Ilmu Sosial', 'desc' => 'Mengkaji hukum pidana/perdata, hubungan internasional, serta ilmu komunikasi.' ),
                    array( 'name' => 'Fakultas Kedokteran & Kesehatan', 'desc' => 'Menyiapkan tenaga medis profesional melalui laboratorium dan rumah sakit jejaring.' ),
                );
                foreach ($faculties_placeholder as $f) :
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $fac_layout === 'list' ) : ?>
                        <!-- Horizontal List Fallback -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-row align-items-center p-3 gap-3">
                            <div class="flex-shrink-0" style="width: 80px; height: 80px;">
                                <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                    <i class="bi bi-mortarboard fs-3 text-campus-gold"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo $f['name']; ?></h3>
                                <p class="card-text text-campus-muted small mb-2"><?php echo $f['desc']; ?></p>
                                <a href="#" class="text-campus-gold text-decoration-none small fw-bold font-heading">
                                    <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Grid Fallback -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                            <div class="ratio ratio-4x3 bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                <i class="bi bi-mortarboard fs-1 text-campus-gold"></i>
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-3"><?php echo $f['name']; ?></h3>
                                <p class="card-text text-campus-muted small flex-grow-1"><?php echo $f['desc']; ?></p>
                                <a href="#" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-3">
                                    <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>
<?php endif; ?>
