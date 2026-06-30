<?php
/**
 * The template for displaying single faculty pages
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();
?>
        <?php educampus_page_hero( array(
            'title'   => get_the_title(),
            'badge'   => get_theme_mod( 'educampus_archive_faculty_badge', 'FAKULTAS' ),
            'class'   => 'bg-islamic-ornament-half',
        ) ); ?>

        <!-- Main Layout Grid -->
        <div class="container my-5 pb-5 position-relative">
            <i class="bi bi-gear-wide-connected deco-icon deco-icon-3" style="opacity:0.06;font-size:8rem;top:100px;right:-60px;animation:float-spin 20s linear infinite;"></i>
            <?php educampus_breadcrumbs(); ?>
            
            <div class="row g-5 mt-2">
                <!-- Faculty Main Info (8 Columns) -->
                <div class="col-lg-8">
                    <!-- Dean Profile -->
                    <?php
                    $dean_name   = get_post_meta( $post_id, '_faculty_dean_name', true );
                    $dean_period = get_post_meta( $post_id, '_faculty_dean_period', true );
                    $dean_photo  = get_post_meta( $post_id, '_faculty_dean_photo', true );
                    $dean_quote  = get_post_meta( $post_id, '_faculty_dean_quote', true );

                    if ( ! empty( $dean_name ) ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus mb-5 section-reveal position-relative overflow-hidden card-gradient-cool section-accent-faculty">
                        <i class="bi bi-person-vcard deco-icon" style="opacity:0.05;font-size:6rem;bottom:-20px;right:-10px;animation:float-rotate 12s ease-in-out infinite;"></i>
                        <div class="card-body p-4">
                            <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-4"><?php esc_html_e( 'Profil Dekan', 'educampus' ); ?></h3>
                            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">
                                <div class="flex-shrink-0 text-center text-md-start">
                                    <?php if ( ! empty( $dean_photo ) ) : ?>
                                        <div class="position-relative d-inline-block">
                                            <div class="bg-campus-gold rounded-circle position-absolute" style="top:4px;left:4px;right:-4px;bottom:-4px;z-index:0;"></div>
                                            <img loading="lazy" src="<?php echo esc_url( $dean_photo ); ?>" class="rounded-circle position-relative z-1 border border-3 border-white shadow-campus-soft object-fit-cover" alt="Dekan" style="width:110px;height:110px;object-fit:cover;">
                                        </div>
                                    <?php else : ?>
                                        <div class="bg-campus-navy text-white rounded-circle d-flex align-items-center justify-content-center" style="width:110px;height:110px;">
                                            <i class="bi bi-person-fill fs-1 text-campus-gold"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 text-center text-md-start">
                                    <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( $dean_name ); ?></h4>
                                    <?php if ( ! empty( $dean_period ) ) : ?>
                                    <span class="badge bg-campus-gold bg-opacity-10 text-campus-gold fw-semibold small mb-2"><?php echo esc_html( $dean_period ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $dean_quote ) ) : ?>
                                    <p class="text-campus-muted small mb-0 mt-2 bg-light p-3 rounded-campus fst-italic border-start border-4 border-campus-gold">"<?php echo esc_html( $dean_quote ); ?>"</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Faculty Description -->
                    <div class="entry-content mb-5 section-reveal">
                        <?php the_content(); ?>
                    </div>

                    <!-- Visi -->
                    <?php
                    $faculty_visi = get_post_meta( $post_id, '_faculty_visi', true );
                    if ( ! empty( $faculty_visi ) ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus mb-5 section-reveal-left position-relative overflow-hidden card-gradient-warm section-accent-faculty">
                        <i class="bi bi-compass deco-icon" style="opacity:0.05;font-size:5rem;top:-15px;right:-15px;animation:float-rotate 10s ease-in-out infinite;"></i>
                        <div class="card-body p-4">
                            <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-4"><?php esc_html_e( 'Visi Fakultas', 'educampus' ); ?></h3>
                            <div class="border-start border-4 border-campus-gold ps-3">
                                <p class="fw-bold text-campus-navy mb-0" style="font-size:1.15rem; line-height:1.6;">"<?php echo esc_html( $faculty_visi ); ?>"</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Misi -->
                    <?php
                    $faculty_misi = get_post_meta( $post_id, '_faculty_misi', true );
                    $faculty_misi_lines = ! empty( $faculty_misi ) ? array_filter( array_map( 'trim', explode( "\n", $faculty_misi ) ) ) : array();
                    if ( ! empty( $faculty_misi_lines ) ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus mb-5 section-reveal-right position-relative overflow-hidden card-gradient-light section-accent-faculty">
                        <i class="bi bi-list-check deco-icon" style="opacity:0.05;font-size:5rem;bottom:-15px;left:-15px;animation:float-bounce 9s ease-in-out infinite;"></i>
                        <div class="card-body p-4">
                            <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-4"><?php esc_html_e( 'Misi Fakultas', 'educampus' ); ?></h3>
                            <ol class="list-group list-group-numbered mb-0">
                                <?php foreach ( $faculty_misi_lines as $item ) : ?>
                                <li class="list-group-item border-0 ps-0" style="background:transparent; padding-left:0 !important;">
                                    <span class="text-campus-navy" style="font-size:0.95rem;"><?php echo esc_html( $item ); ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Study Programs (Prodi) -->
                    <div class="mb-5 section-reveal">
                        <div class="position-relative overflow-hidden bg-campus-navy rounded-campus p-4 mb-4 d-flex align-items-center">
                            <i class="bi bi-mortarboard-fill position-absolute" style="font-size:5rem;color:rgba(255,255,255,0.06);top:50%;right:-10px;transform:translateY(-50%);"></i>
                            <i class="bi bi-journal-text position-absolute" style="font-size:3.5rem;color:rgba(255,255,255,0.04);bottom:-15px;left:20px;"></i>
                            <h3 class="h5 font-heading fw-bold text-white mb-0 position-relative z-1">
                                <i class="bi bi-mortarboard-fill text-campus-gold me-2"></i> <?php esc_html_e( 'Program Studi', 'educampus' ); ?>
                            </h3>
                        </div>
                        <?php
                        $program_query = new WP_Query( array(
                            'post_type'      => 'program',
                            'posts_per_page' => -1,
                            'meta_query'     => array(
                                array(
                                    'key'     => '_program_faculty_id',
                                    'value'   => $post_id,
                                    'compare' => '='
                                )
                            )
                        ) );

                        if ( $program_query->have_posts() ) :
                        ?>
                        <div class="row g-3">
                            <?php
                            while ( $program_query->have_posts() ) : $program_query->the_post();
                                $levels = get_the_terms( get_the_ID(), 'program_level' );
                                $level_name = ( ! empty( $levels ) && ! is_wp_error( $levels ) ) ? $levels[0]->name : '-';
                                $prog_accred = get_post_meta( get_the_ID(), '_program_accreditation', true );
                                $accred_institution = get_post_meta( get_the_ID(), '_program_accred_institution', true );
                            ?>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-campus-soft rounded-campus h-100 bg-white transform-hover position-relative overflow-hidden section-accent-program">
                                    <div class="card-body p-4 d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <?php if ( ! empty( $prog_accred ) ) : ?>
                                            <span class="badge bg-success bg-opacity-10 text-success fw-semibold small"><?php printf( esc_html__( 'Akreditasi %s', 'educampus' ), esc_html( $prog_accred ) ); ?></span>
                                            <?php endif; ?>
                                            <span class="badge bg-campus-navy bg-opacity-10 text-campus-navy fw-semibold small"><?php echo esc_html( $level_name ); ?></span>
                                            <?php if ( ! empty( $accred_institution ) ) : ?>
                                            <span class="badge bg-campus-gold bg-opacity-10 text-campus-gold fw-semibold small"><?php echo esc_html( $accred_institution ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-2"><?php the_title(); ?></h4>
                                        <p class="text-campus-muted small flex-grow-1 mb-3"><?php echo wp_trim_words( get_the_excerpt(), 12 ); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-auto d-inline-flex align-items-center">
                                            <?php esc_html_e( 'Detail Prodi', 'educampus' ); ?> <i class="bi-chevron-right small ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                        <?php else : ?>
                        <div class="text-center py-5">
                            <i class="bi bi-mortarboard text-campus-muted" style="font-size: 2.5rem;"></i>
                            <p class="text-campus-muted small mt-3 mb-0"><?php esc_html_e( 'Belum ada program studi terdaftar untuk fakultas ini.', 'educampus' ); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar Info (4 Columns) -->
                <div class="col-lg-4">
                    <?php
                    $established     = get_post_meta( $post_id, '_faculty_established', true );
                    $students_count  = get_post_meta( $post_id, '_faculty_students_count', true );
                    $lecturers_count = get_post_meta( $post_id, '_faculty_lecturers_count', true );
                    $location        = get_post_meta( $post_id, '_faculty_location', true );

                    $has_info = ! empty( $established ) || ! empty( $students_count ) || ! empty( $lecturers_count ) || ! empty( $location );

                    if ( $has_info ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus mb-4 section-reveal-right card-gradient-gold">
                        <div class="card-body p-4">
                            <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-1"></i> <?php esc_html_e( 'Informasi Fakultas', 'educampus' ); ?></h3>
                            <ul class="list-unstyled small text-campus-muted mb-0 d-flex flex-column gap-3">
                                <?php if ( ! empty( $established ) ) : ?>
                                <li class="d-flex align-items-center">
                                    <span class="d-flex align-items-center justify-content-center bg-campus-gold bg-opacity-10 text-campus-gold rounded-circle me-2 flex-shrink-0" style="width:32px;height:32px;"><i class="bi bi-calendar-check"></i></span>
                                    <div><strong class="d-block text-campus-navy" style="font-size:.7rem;"><?php esc_html_e( 'DIDIRIKAN', 'educampus' ); ?></strong><?php echo esc_html( $established ); ?></div>
                                </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $students_count ) ) : ?>
                                <li class="d-flex align-items-center">
                                    <span class="d-flex align-items-center justify-content-center bg-campus-gold bg-opacity-10 text-campus-gold rounded-circle me-2 flex-shrink-0" style="width:32px;height:32px;"><i class="bi bi-people"></i></span>
                                    <div><strong class="d-block text-campus-navy" style="font-size:.7rem;"><?php esc_html_e( 'MAHASISWA', 'educampus' ); ?></strong><?php echo esc_html( $students_count ); ?></div>
                                </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $lecturers_count ) ) : ?>
                                <li class="d-flex align-items-center">
                                    <span class="d-flex align-items-center justify-content-center bg-campus-gold bg-opacity-10 text-campus-gold rounded-circle me-2 flex-shrink-0" style="width:32px;height:32px;"><i class="bi bi-person-workspace"></i></span>
                                    <div><strong class="d-block text-campus-navy" style="font-size:.7rem;"><?php esc_html_e( 'DOSEN', 'educampus' ); ?></strong><?php echo esc_html( $lecturers_count ); ?></div>
                                </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $location ) ) : ?>
                                <li class="d-flex align-items-center">
                                    <span class="d-flex align-items-center justify-content-center bg-campus-gold bg-opacity-10 text-campus-gold rounded-circle me-2 flex-shrink-0" style="width:32px;height:32px;"><i class="bi bi-building"></i></span>
                                    <div><strong class="d-block text-campus-navy" style="font-size:.7rem;"><?php esc_html_e( 'LOKASI', 'educampus' ); ?></strong><?php echo esc_html( $location ); ?></div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Social Media -->
                    <?php
                    $faculty_youtube   = get_post_meta( $post_id, '_faculty_youtube', true );
                    $faculty_instagram = get_post_meta( $post_id, '_faculty_instagram', true );
                    $faculty_tiktok    = get_post_meta( $post_id, '_faculty_tiktok', true );
                    $faculty_facebook  = get_post_meta( $post_id, '_faculty_facebook', true );
                    $socmeds = array(
                        'youtube'  => array( 'url' => $faculty_youtube, 'icon' => 'bi-youtube', 'color' => '#ff0000' ),
                        'instagram' => array( 'url' => $faculty_instagram, 'icon' => 'bi-instagram', 'color' => '#e1306c' ),
                        'tiktok'    => array( 'url' => $faculty_tiktok, 'icon' => 'bi-tiktok', 'color' => '#000' ),
                        'facebook'  => array( 'url' => $faculty_facebook, 'icon' => 'bi-facebook', 'color' => '#1877f2' ),
                    );
                    if ( array_filter( array_column( $socmeds, 'url' ) ) ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white mb-4 section-reveal-right">
                        <div class="card-body p-4">
                            <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><i class="bi bi-share me-1"></i> <?php esc_html_e( 'Media Sosial', 'educampus' ); ?></h3>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php foreach ( $socmeds as $platform => $data ) : ?>
                                    <?php if ( ! empty( $data['url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" rel="noopener noreferrer"
                                        class="d-flex align-items-center justify-content-center rounded-circle text-white text-decoration-none"
                                        style="width:38px;height:38px;background:<?php echo esc_attr( $data['color'] ); ?>;transition:transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.15)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                        title="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                                        <i class="bi <?php echo esc_attr( $data['icon'] ); ?>" style="font-size:1.1rem;"></i>
                                    </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Admission Banner Callout -->
                    <div class="card border-0 bg-campus-navy text-white shadow-campus-soft rounded-campus text-center section-reveal-right position-relative overflow-hidden">
                        <i class="bi bi-mortarboard-fill deco-icon" style="opacity:0.08;font-size:6rem;top:-10px;right:-15px;color:#fff;animation:float-rotate 8s ease-in-out infinite;"></i>
                        <div class="card-body p-4 position-relative z-1">
                            <i class="bi bi-mortarboard-fill text-campus-gold fs-3 mb-2 d-block"></i>
                            <h4 class="h5 font-heading text-campus-gold fw-bold mb-2"><?php esc_html_e( 'Ingin Mendaftar?', 'educampus' ); ?></h4>
                            <p class="small text-white-50 mb-4"><?php esc_html_e( 'Daftarkan diri Anda pada program studi favorit di bawah naungan fakultas ini secara online.', 'educampus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/pmb' ) ); ?>" class="btn btn-campus-secondary btn-sm py-2 d-block font-heading">
                                <?php esc_html_e( 'Pendaftaran PMB Online', 'educampus' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    endwhile;
else :
?>
    <div class="container my-5 py-5 text-center">
        <h1 class="h2"><?php esc_html_e( 'Fakultas Tidak Ditemukan', 'educampus' ); ?></h1>
        <p class="text-campus-muted"><?php esc_html_e( 'Konten fakultas sedang dipersiapkan administrator.', 'educampus' ); ?></p>
    </div>
<?php
endif;
get_footer();
