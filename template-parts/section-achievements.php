<!-- Section 8: Achievements -->
<?php if ( get_theme_mod( 'educampus_show_achievements', true ) ) : ?>
<?php 
$achieve_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_achievements', true ) ) {
    $achieve_ornament = ' bg-islamic-ornament';
}
$achieve_bg_color = get_theme_mod( 'educampus_bg_color_achievements', '#f5f0e4' );
$achieve_bg_color_end = get_theme_mod( 'educampus_bg_color_end_achievements', $achieve_bg_color );
$achieve_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_achievements', '135deg' );
?>
<section id="achievements" class="section-padding<?php echo esc_attr($achieve_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($achieve_bg_grad_dir); ?>, <?php echo esc_attr($achieve_bg_color); ?>, <?php echo esc_attr($achieve_bg_color_end); ?>);">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_achievements_badge', 'PRESTASI' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_achievements_title', 'Prestasi Internasional & Nasional' ) ); ?></h2>
            <?php 
            $achieve_desc = get_theme_mod( 'educampus_heading_achievements_desc', 'Berbagai pencapaian bergengsi civitas akademika EduCampus di tingkat global.' );
            if ( ! empty( $achieve_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $achieve_desc ); ?></p>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php
            $achieve_layout = get_theme_mod( 'educampus_achievements_layout', 'grid' );
            $col_class = ( $achieve_layout === 'list' ) ? 'col-12' : 'col-md-4';

            $achieve_query = new WP_Query( array(
                'post_type'      => 'achievement',
                'posts_per_page' => 3,
            ) );

            if ( $achieve_query->have_posts() ) :
                while ( $achieve_query->have_posts() ) : $achieve_query->the_post();
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $achieve_layout === 'list' ) : ?>
                        <!-- List Layout -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white p-3 transform-hover d-flex flex-row align-items-center gap-3">
                            <div class="flex-shrink-0 text-campus-gold" style="font-size: 1.8rem;">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_title(); ?></h3>
                                <p class="text-campus-muted small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Standard Grid Layout -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white text-center p-4 transform-hover d-flex flex-column">
                            <div class="display-5 text-campus-gold mb-3"><i class="bi bi-trophy-fill"></i></div>
                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php the_title(); ?></h3>
                            <p class="text-campus-muted small mb-0 flex-grow-1"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Static premium placeholder fallback
                $achieve_placeholder = array(
                    array( 'title' => 'Medali Emas WICO Korea 2025', 'desc' => 'Inovasi alat filtrasi air tenaga surya karya tim riset rekayasa lingkungan Fakultas Teknik.' ),
                    array( 'title' => 'Akreditasi Internasional FIBAA 2026', 'desc' => 'Program studi Manajemen dan Hukum resmi mendapatkan akreditasi kualifikasi standar Eropa.' ),
                    array( 'title' => 'Juara Umum Pekan Ilmiah Mahasiswa (PIMNAS) 2025', 'desc' => 'EduCampus mendominasi perolehan medali emas pada program kreativitas mahasiswa kategori inovasi IPTEK.' ),
                );
                foreach ($achieve_placeholder as $a) :
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $achieve_layout === 'list' ) : ?>
                        <!-- List Fallback -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white p-3 transform-hover d-flex flex-row align-items-center gap-3">
                            <div class="flex-shrink-0 text-campus-gold" style="font-size: 1.8rem;">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo $a['title']; ?></h3>
                                <p class="text-campus-muted small mb-0"><?php echo $a['desc']; ?></p>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Grid Fallback -->
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white text-center p-4 transform-hover d-flex flex-column">
                            <div class="display-5 text-campus-gold mb-3"><i class="bi bi-trophy-fill"></i></div>
                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php echo $a['title']; ?></h3>
                            <p class="text-campus-muted small mb-0 flex-grow-1"><?php echo $a['desc']; ?></p>
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
