<?php if ( get_theme_mod( 'educampus_show_programs', true ) ) : ?>
<?php 
$programs_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_programs', true ) ) {
    $programs_ornament = ' bg-islamic-ornament';
}
$programs_bg_color = get_theme_mod( 'educampus_bg_color_programs', '#eef3f8' );
$programs_bg_color_end = get_theme_mod( 'educampus_bg_color_end_programs', $programs_bg_color );
$programs_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_programs', '135deg' );
$prog_layout = get_theme_mod( 'educampus_programs_layout', 'grid' );
$prog_limit = get_theme_mod( 'educampus_programs_limit', 6 );
$col_class = ( $prog_layout === 'list' ) ? 'col-12' : 'col-lg-4 col-md-6';
?>
<section id="featured-programs" class="section-padding<?php echo esc_attr($programs_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($programs_bg_grad_dir); ?>, <?php echo esc_attr($programs_bg_color); ?>, <?php echo esc_attr($programs_bg_color_end); ?>);">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_programs_badge', 'PROGRAM STUDI UNGGULAN' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_programs_title', 'Program Studi Favorit & Unggul' ) ); ?></h2>
            <?php 
            $prog_desc = get_theme_mod( 'educampus_heading_programs_desc', 'Pilih program studi dengan tingkat keterserapan kerja tinggi di industri dan kurikulum adaptif.' );
            if ( ! empty( $prog_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $prog_desc ); ?></p>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php
            $prodi_query = new WP_Query( array(
                'post_type'      => 'program',
                'posts_per_page' => $prog_limit,
                'meta_query'     => array(
                    array(
                        'key'     => '_program_featured',
                        'value'   => '1',
                    ),
                ),
            ) );

            if ( ! $prodi_query->have_posts() ) {
                $prodi_query = new WP_Query( array(
                    'post_type'      => 'program',
                    'posts_per_page' => $prog_limit,
                ) );
            }

            if ( $prodi_query->have_posts() ) :
                while ( $prodi_query->have_posts() ) : $prodi_query->the_post();
                    $levels = get_the_terms( get_the_ID(), 'program_level' );
                    $level_name = ! empty( $levels ) && ! is_wp_error( $levels ) ? $levels[0]->name : 'Jenjang';
                    $accred = get_post_meta( get_the_ID(), '_program_accreditation', true );
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $prog_layout === 'list' ) : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden p-3 bg-white transform-hover d-flex flex-row align-items-center gap-3">
                            <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'campus-square', array( 'class' => 'w-100 h-100 object-fit-cover rounded-3' ) ); ?>
                                <?php else : ?>
                                    <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                        <i class="bi bi-mortarboard fs-3 text-campus-gold"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="mb-1">
                                    <span class="text-campus-muted small"><?php echo esc_html( $level_name ); ?></span>
                                    <?php if ( $accred ) : ?>
                                        <span class="badge-akreditasi ms-1"><?php echo esc_html( $accred ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_title(); ?></h3>
                                <p class="card-text text-campus-muted small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="<?php the_permalink(); ?>" class="btn btn-campus-outline btn-sm font-heading">
                                    <?php esc_html_e('Profil', 'educampus'); ?>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="ratio ratio-16x9">
                                    <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'object-fit-cover img-fluid' ) ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    <span class="text-campus-muted small font-heading fw-500"><?php echo esc_html( $level_name ); ?></span>
                                    <?php if ( $accred ) : ?>
                                        <span class="badge-akreditasi"><?php echo esc_html( $accred ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php the_title(); ?></h3>
                                <p class="card-text text-campus-muted small flex-grow-1"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-campus-outline btn-sm mt-4 align-self-start font-heading">
                                    <?php esc_html_e('Profil Prodi', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                $prodi_placeholder = array(
                    array( 'name' => 'S1 Teknik Informatika', 'desc' => 'Berfokus pada pengembangan kecerdasan buatan, keamanan siber, rekayasa perangkat lunak, dan sains data.', 'level' => 'S1' ),
                    array( 'name' => 'S1 Ilmu Komunikasi', 'desc' => 'Mempelajari komunikasi strategis, manajemen media baru, public relations, jurnalistik dan konten multimedia.', 'level' => 'S1' ),
                    array( 'name' => 'S1 Manajemen & Bisnis', 'desc' => 'Mempersiapkan entrepreneur dan pimpinan korporasi dengan fokus digital marketing, keuangan global, dan SDM.', 'level' => 'S1' ),
                );
                foreach ($prodi_placeholder as $p) :
            ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <?php if ( $prog_layout === 'list' ) : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden p-3 bg-white transform-hover d-flex flex-row align-items-center gap-3">
                            <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                    <i class="bi bi-mortarboard fs-3 text-campus-gold"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="mb-1">
                                    <span class="text-campus-muted small"><?php echo $p['level']; ?></span>
                                </div>
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo $p['name']; ?></h3>
                                <p class="card-text text-campus-muted small mb-0"><?php echo $p['desc']; ?></p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="#" class="btn btn-campus-outline btn-sm font-heading">
                                    <?php esc_html_e('Profil', 'educampus'); ?>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="mb-3">
                                    <span class="text-campus-muted small font-heading fw-500"><?php esc_html_e('Jenjang', 'educampus'); ?> <?php echo $p['level']; ?></span>
                                </div>
                                <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php echo $p['name']; ?></h3>
                                <p class="card-text text-campus-muted small flex-grow-1"><?php echo $p['desc']; ?></p>
                                <a href="#" class="btn btn-campus-outline btn-sm mt-4 align-self-start font-heading">
                                    <?php esc_html_e('Profil Prodi', 'educampus'); ?> <i class="bi bi-chevron-right small"></i>
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

        <div class="text-center mt-5">
            <a href="<?php echo esc_url( home_url( '/program' ) ); ?>" class="btn btn-campus-primary font-heading px-4 py-2">
                <?php esc_html_e('Lihat Semua Program Studi', 'educampus'); ?> <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
