<?php
/**
 * The template for displaying single study programs
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();
        $levels = get_the_terms( $post_id, 'program_level' );
        $level_name = (!empty($levels) && !is_wp_error($levels)) ? $levels[0]->name : 'Sarjana (S1)';
?>
        <?php
        $prog_accred = get_post_meta( $post_id, '_program_accreditation', true );
        $badges = '<div class="d-flex flex-wrap align-items-center gap-2 mb-2">';
        $badges .= '<span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small">' . esc_html( $level_name ) . '</span>';
        if ( ! empty( $prog_accred ) ) {
            $badges .= '<span class="badge bg-success text-white font-heading fw-bold px-2 py-1 rounded-pill small">' . sprintf( esc_html__( 'Akreditasi %s', 'educampus' ), esc_html( $prog_accred ) ) . '</span>';
        }
        $badges .= '</div>';
        educampus_page_hero( array(
            'title'   => get_the_title(),
            'class'   => 'bg-islamic-ornament-half',
            'content' => $badges,
        ) ); ?>

        <!-- Main Layout -->
        <div class="container my-5 pb-5 position-relative">
            <i class="bi bi-book-half deco-icon" style="opacity:0.04;font-size:7rem;top:40px;right:-30px;color:var(--color-primary);animation:float-spin 18s linear infinite;pointer-events:none;"></i>
            <?php educampus_breadcrumbs(); ?>
            
            <div class="row g-5 mt-2">
                <!-- Program Main Content (8 Columns) -->
                <div class="col-lg-8">
                    
                    <!-- Tabs Navigation (Overview, Curriculum, Careers) -->
                    <ul class="nav nav-pills mb-4 border-bottom pb-3 gap-2 section-reveal" id="programTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill font-heading fw-bold py-2 px-4" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                                <?php esc_html_e( 'Ringkasan', 'educampus' ); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill font-heading fw-bold py-2 px-4" id="curriculum-tab" data-bs-toggle="pill" data-bs-target="#curriculum" type="button" role="tab" aria-controls="curriculum" aria-selected="false">
                                <?php esc_html_e( 'Kurikulum', 'educampus' ); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill font-heading fw-bold py-2 px-4" id="prospects-tab" data-bs-toggle="pill" data-bs-target="#prospects" type="button" role="tab" aria-controls="prospects" aria-selected="false">
                                <?php esc_html_e( 'Prospek Karir', 'educampus' ); ?>
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tabs Content -->
                    <div class="tab-content bg-white p-4 shadow-campus-soft rounded-campus mb-5 section-accent-program section-reveal" id="programTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            <!-- Kaprodi & Sekprodi dari Unit (struktur organisasi) -->
                            <?php
                            $unit_kaprodi = null;
                            $unit_sekprodi = null;
                            $unit_children = get_posts( array(
                                'post_type'      => 'unit',
                                'posts_per_page' => -1,
                                'post_parent'    => $post_id,
                                'post_status'    => 'publish',
                            ) );
                            foreach ( $unit_children as $u ) {
                                $pos = strtolower( get_post_meta( $u->ID, '_unit_position_title', true ) );
                                if ( strpos( $pos, 'kaprodi' ) !== false || ( strpos( $pos, 'ketua' ) !== false && strpos( $pos, 'program studi' ) !== false ) ) {
                                    $unit_kaprodi = $u;
                                } elseif ( strpos( $pos, 'sekprodi' ) !== false || ( strpos( $pos, 'sekretaris' ) !== false && strpos( $pos, 'program studi' ) !== false ) ) {
                                    $unit_sekprodi = $u;
                                }
                            }

                            if ( $unit_kaprodi || $unit_sekprodi ) :
                            ?>
                            <div class="section-reveal">
                                <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><?php esc_html_e( 'Pengelola Program Studi', 'educampus' ); ?></h3>
                                <div class="row g-3 mb-4">
                                    <?php if ( $unit_kaprodi ) :
                                        $k_nidn = get_post_meta( $unit_kaprodi->ID, '_unit_nidn', true );
                                        $k_url  = $k_nidn ? get_permalink( reset( get_posts( array( 'post_type' => 'lecturer', 'meta_key' => '_lecturer_nidn', 'meta_value' => $k_nidn, 'fields' => 'ids', 'posts_per_page' => 1 ) ) ?: array() ) ) : '';
                                        $k_photo = get_post_meta( $unit_kaprodi->ID, '_unit_photo_url', true ) ?: ( has_post_thumbnail( $unit_kaprodi->ID ) ? get_the_post_thumbnail_url( $unit_kaprodi->ID, 'medium' ) : '' );
                                    ?>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 h-100 card-gradient-light transform-hover">
                                            <i class="bi bi-person-vcard deco-icon" style="opacity:0.04;font-size:4rem;bottom:-10px;right:-5px;animation:float-rotate 10s ease-in-out infinite;pointer-events:none;"></i>
                                            <div class="row align-items-center g-3 position-relative z-1">
                                                <div class="col-md-4 text-center">
                                                    <?php if ( $k_photo ) : ?>
                                                        <img loading="lazy" src="<?php echo esc_url( $k_photo ); ?>" class="rounded-circle border border-3 border-campus-gold object-fit-cover" alt="Kaprodi" style="width: 90px; height: 90px; object-fit: cover;">
                                                    <?php else : ?>
                                                        <div class="bg-campus-navy text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px;">
                                                            <i class="bi bi-person-fill fs-1 text-campus-gold"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-8">
                                                    <?php if ( $k_url ) : ?>
                                                    <a href="<?php echo esc_url( $k_url ); ?>" class="text-decoration-none">
                                                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( get_the_title( $unit_kaprodi ) ); ?> <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;opacity:0.4;"></i></h4>
                                                    </a>
                                                    <?php else : ?>
                                                    <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( get_the_title( $unit_kaprodi ) ); ?></h4>
                                                    <?php endif; ?>
                                                    <span class="text-campus-gold small d-block mb-2"><?php esc_html_e( 'Ketua Program Studi', 'educampus' ); ?></span>
                                                    <?php if ( $k_nidn ) : ?>
                                                        <p class="text-campus-muted small mb-0">NIDN: <?php echo esc_html( $k_nidn ); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ( $unit_sekprodi ) :
                                        $s_nidn = get_post_meta( $unit_sekprodi->ID, '_unit_nidn', true );
                                        $s_url  = $s_nidn ? get_permalink( reset( get_posts( array( 'post_type' => 'lecturer', 'meta_key' => '_lecturer_nidn', 'meta_value' => $s_nidn, 'fields' => 'ids', 'posts_per_page' => 1 ) ) ?: array() ) ) : '';
                                        $s_photo = get_post_meta( $unit_sekprodi->ID, '_unit_photo_url', true ) ?: ( has_post_thumbnail( $unit_sekprodi->ID ) ? get_the_post_thumbnail_url( $unit_sekprodi->ID, 'medium' ) : '' );
                                    ?>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 h-100 card-gradient-warm transform-hover">
                                            <i class="bi bi-person-badge deco-icon" style="opacity:0.04;font-size:4rem;top:-10px;right:-5px;animation:float-bounce 9s ease-in-out infinite;pointer-events:none;"></i>
                                            <div class="row align-items-center g-3 position-relative z-1">
                                                <div class="col-md-4 text-center">
                                                    <?php if ( $s_photo ) : ?>
                                                        <img loading="lazy" src="<?php echo esc_url( $s_photo ); ?>" class="rounded-circle border border-3 border-campus-gold object-fit-cover" alt="Sekprodi" style="width: 90px; height: 90px; object-fit: cover;">
                                                    <?php else : ?>
                                                        <div class="bg-campus-navy text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px;">
                                                            <i class="bi bi-person-fill fs-1 text-campus-gold"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-8">
                                                    <?php if ( $s_url ) : ?>
                                                    <a href="<?php echo esc_url( $s_url ); ?>" class="text-decoration-none">
                                                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( get_the_title( $unit_sekprodi ) ); ?> <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;opacity:0.4;"></i></h4>
                                                    </a>
                                                    <?php else : ?>
                                                    <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( get_the_title( $unit_sekprodi ) ); ?></h4>
                                                    <?php endif; ?>
                                                    <span class="text-campus-gold small d-block mb-2"><?php esc_html_e( 'Sekretaris Program Studi', 'educampus' ); ?></span>
                                                    <?php if ( $s_nidn ) : ?>
                                                        <p class="text-campus-muted small mb-0">NIDN: <?php echo esc_html( $s_nidn ); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="section-reveal">
                                <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php esc_html_e( 'Deskripsi Program Studi', 'educampus' ); ?></h3>
                                <div class="font-body text-campus-navy mb-4" style="line-height: 1.8;">
                                    <?php the_content(); ?>
                                </div>
                            </div>

                            <?php
                            $prog_visi = get_post_meta( $post_id, '_program_visi', true );
                            $prog_misi = get_post_meta( $post_id, '_program_misi', true );
                            if ( ! empty( $prog_visi ) ) :
                            ?>
                            <div class="card border-0 shadow-campus-soft rounded-campus mb-4 section-reveal-left position-relative overflow-hidden card-gradient-cool section-accent-program">
                                <i class="bi bi-compass deco-icon" style="opacity:0.05;font-size:5rem;top:-15px;right:-15px;animation:float-rotate 10s ease-in-out infinite;pointer-events:none;"></i>
                                <div class="card-body p-4">
                                    <h4 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><?php esc_html_e( 'Visi Program Studi', 'educampus' ); ?></h4>
                                    <div class="border-start border-4 border-campus-gold ps-3">
                                        <p class="fw-bold text-campus-navy mb-0" style="font-size:1rem; line-height:1.6;">"<?php echo esc_html( $prog_visi ); ?>"</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php
                            $prog_misi_lines = ! empty( $prog_misi ) ? array_filter( array_map( 'trim', explode( "\n", $prog_misi ) ) ) : array();
                            if ( ! empty( $prog_misi_lines ) ) :
                            ?>
                            <div class="card border-0 shadow-campus-soft rounded-campus mb-4 section-reveal-right position-relative overflow-hidden card-gradient-light section-accent-program">
                                <i class="bi bi-list-check deco-icon" style="opacity:0.05;font-size:5rem;bottom:-15px;left:-15px;animation:float-bounce 9s ease-in-out infinite;pointer-events:none;"></i>
                                <div class="card-body p-4">
                                    <h4 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><?php esc_html_e( 'Misi Program Studi', 'educampus' ); ?></h4>
                                    <ol class="list-group list-group-numbered mb-0">
                                        <?php foreach ( $prog_misi_lines as $item ) : ?>
                                        <li class="list-group-item border-0 ps-0" style="background:transparent; padding-left:0 !important;">
                                            <span class="text-campus-navy" style="font-size:0.95rem;"><?php echo esc_html( $item ); ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Dosen Pengajar (di dalam tab ringkasan) -->
                            <div class="section-reveal">
                                <h4 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><?php esc_html_e( 'Dosen Pengajar Utama', 'educampus' ); ?></h4>
                                <div class="row g-3">
                                    <?php
                                    $lecturer_query = new WP_Query( array(
                                        'post_type'      => 'lecturer',
                                        'posts_per_page' => -1,
                                        'meta_key'       => '_lecturer_program_id',
                                        'meta_value'     => (string) $post_id,
                                    ) );

                                    if ( $lecturer_query->have_posts() ) :
                                        while ( $lecturer_query->have_posts() ) : $lecturer_query->the_post();
                                    ?>
                                            <div class="col-md-4">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                    <div class="card h-100 border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white text-center p-3 transform-hover card-gradient-gold">
                                                        <?php if ( has_post_thumbnail() ) : ?>
                                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded-circle mx-auto mb-3 object-fit-cover', 'style' => 'width: 80px; height: 80px;' ) ); ?>
                                                        <?php else : ?>
                                                            <div class="bg-campus-navy text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                                                <i class="bi bi-person-fill fs-2 text-campus-gold"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_title(); ?></h4>
                                                        <?php $jf = get_post_meta( get_the_ID(), '_lecturer_jabatan_fungsional', true ); ?>
                                                        <small class="text-campus-gold fw-semibold d-block"><?php echo esc_html( $jf ); ?></small>
                                                        <small class="text-campus-muted d-block"><?php echo get_post_meta( get_the_ID(), '_lecturer_position', true ) ? get_post_meta( get_the_ID(), '_lecturer_position', true ) : esc_html__( 'Dosen Utama', 'educampus' ); ?></small>
                                                    </div>
                                                </a>
                                            </div>
                                    <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    else :
                                    ?>
                                        <div class="col-12">
                                            <p class="text-campus-muted small mb-0"><?php esc_html_e( 'Belum ada data dosen.', 'educampus' ); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Curriculum Tab -->
                        <div class="tab-pane fade section-reveal" id="curriculum" role="tabpanel" aria-labelledby="curriculum-tab">
                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php esc_html_e( 'Struktur Kurikulum', 'educampus' ); ?></h3>
                            <?php
                            $curriculum_json = get_post_meta( $post_id, '_program_curriculum_json', true );
                            $curriculum_data = ! empty( $curriculum_json ) ? json_decode( $curriculum_json, true ) : array();
                            if ( ! empty( $curriculum_data ) ) :
                                ksort( $curriculum_data );
                                $sem_keys = array_keys( $curriculum_data );
                            ?>
                            <div class="curriculum-semester-toggle">
                                <ul class="nav nav-pills mb-3 flex-wrap gap-1" id="semesterTab" role="tablist">
                                    <?php foreach ( $sem_keys as $ii => $sem ) : ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill font-heading fw-semibold px-3 py-1 small <?php echo $ii === 0 ? 'active' : ''; ?>"
                                            id="sem-<?php echo $sem; ?>-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#sem-<?php echo $sem; ?>"
                                            type="button" role="tab"
                                            aria-controls="sem-<?php echo $sem; ?>"
                                            aria-selected="<?php echo $ii === 0 ? 'true' : 'false'; ?>">
                                            Semester <?php echo $sem; ?>
                                        </button>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="tab-content" id="semesterTabContent">
                                    <?php foreach ( $sem_keys as $ii => $sem ) : ?>
                                    <div class="tab-pane fade <?php echo $ii === 0 ? 'show active' : ''; ?>" id="sem-<?php echo $sem; ?>" role="tabpanel" aria-labelledby="sem-<?php echo $sem; ?>-tab">
                                        <div class="curriculum-content">
                                            <table class="table curriculum-table">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e( 'Kode MK', 'educampus' ); ?></th>
                                                        <th><?php esc_html_e( 'Nama Mata Kuliah', 'educampus' ); ?></th>
                                                        <th style="text-align:center;"><?php esc_html_e( 'SKS', 'educampus' ); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ( $curriculum_data[ $sem ] as $course ) : ?>
                                                    <tr>
                                                        <td><?php echo esc_html( $course['code'] ?? '-' ); ?></td>
                                                        <td><?php echo esc_html( $course['name'] ?? '' ); ?></td>
                                                        <td style="text-align:center;"><?php echo esc_html( $course['sks'] ?? '-' ); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php else : ?>
                                <p class="text-campus-muted small mb-0"><?php esc_html_e( 'Belum ada data kurikulum.', 'educampus' ); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Prospects Tab -->
                        <div class="tab-pane fade" id="prospects" role="tabpanel" aria-labelledby="prospects-tab">
                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php esc_html_e( 'Prospek Pekerjaan Lulusan', 'educampus' ); ?></h3>
                            <?php
                            $careers_content = get_post_meta( $post_id, '_program_careers', true );
                            if ( ! empty( $careers_content ) ) :
                                echo wp_kses_post( $careers_content );
                            else :
                            ?>
                                <p class="text-campus-muted small mb-0"><?php esc_html_e( 'Belum ada data prospek karir.', 'educampus' ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Details Info (4 Columns) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 mb-4">
                        <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Informasi Admisi', 'educampus' ); ?></h3>
                        <ul class="list-unstyled small text-campus-muted mb-0 d-flex flex-column gap-3">
                            <?php
                            $gelar = get_post_meta( $post_id, '_program_gelar', true );
                            ?>
                            <?php if ( ! empty( $gelar ) ) : ?>
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="fw-bold"><?php esc_html_e( 'Gelar', 'educampus' ); ?></span>
                                <span><?php echo esc_html( $gelar ); ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if ( ! empty( $prog_accred ) ) : ?>
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="fw-bold"><?php esc_html_e( 'Akreditasi', 'educampus' ); ?></span>
                                <span class="text-success fw-bold"><?php echo esc_html( $prog_accred ); ?><?php echo ! empty( $prog_accred_inst ) ? ' (' . esc_html( $prog_accred_inst ) . ')' : ''; ?></span>
                            </li>
                            <?php endif; ?>
                            <?php
                            $accred_cert = get_post_meta( $post_id, '_program_accred_cert', true );
                            $accred_sk   = get_post_meta( $post_id, '_program_accred_sk', true );
                            if ( ! empty( $accred_cert ) ) :
                            ?>
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="fw-bold"><?php esc_html_e( 'Sertifikat', 'educampus' ); ?></span>
                                <span><a href="<?php echo esc_url( $accred_cert ); ?>" target="_blank" class="text-campus-gold text-decoration-none fw-bold"><i class="bi bi-file-earmark-pdf-fill me-1"></i> <?php esc_html_e( 'Unduh PDF', 'educampus' ); ?></a></span>
                            </li>
                            <?php endif; ?>
                            <?php
                            if ( ! empty( $accred_sk ) ) :
                            ?>
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="fw-bold"><?php esc_html_e( 'SK Akreditasi', 'educampus' ); ?></span>
                                <span><a href="<?php echo esc_url( $accred_sk ); ?>" target="_blank" class="text-campus-gold text-decoration-none fw-bold"><i class="bi bi-file-earmark-text-fill me-1"></i> <?php esc_html_e( 'Unduh PDF', 'educampus' ); ?></a></span>
                            </li>
                            <?php endif; ?>
                            <?php
                            $prog_duration = get_post_meta( $post_id, '_program_duration', true );
                            ?>
                            <?php if ( ! empty( $prog_duration ) ) : ?>
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="fw-bold"><?php esc_html_e( 'Masa Studi', 'educampus' ); ?></span>
                                <span><?php echo esc_html( $prog_duration ); ?></span>
                            </li>
                            <?php endif; ?>
                            <?php
                            $prog_method = get_post_meta( $post_id, '_program_method', true );
                            ?>
                            <?php if ( ! empty( $prog_method ) ) : ?>
                            <li class="d-flex justify-content-between pb-1">
                                <span class="fw-bold"><?php esc_html_e( 'Metode Kuliah', 'educampus' ); ?></span>
                                <span><?php echo esc_html( $prog_method ); ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Social Media -->
                    <?php
                    $prog_youtube   = get_post_meta( $post_id, '_program_youtube', true );
                    $prog_instagram = get_post_meta( $post_id, '_program_instagram', true );
                    $prog_tiktok    = get_post_meta( $post_id, '_program_tiktok', true );
                    $prog_facebook  = get_post_meta( $post_id, '_program_facebook', true );
                    $socmeds = array(
                        'youtube'  => array( 'url' => $prog_youtube, 'icon' => 'bi-youtube', 'color' => '#ff0000' ),
                        'instagram' => array( 'url' => $prog_instagram, 'icon' => 'bi-instagram', 'color' => '#e1306c' ),
                        'tiktok'    => array( 'url' => $prog_tiktok, 'icon' => 'bi-tiktok', 'color' => '#000' ),
                        'facebook'  => array( 'url' => $prog_facebook, 'icon' => 'bi-facebook', 'color' => '#1877f2' ),
                    );
                    if ( array_filter( array_column( $socmeds, 'url' ) ) ) :
                    ?>
                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 mb-4">
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
                    <?php endif; ?>

                    <!-- CTA PMB Banner -->
                    <div class="card border-0 bg-campus-navy text-white shadow-campus-soft rounded-campus p-4 text-center">
                        <h4 class="h5 font-heading text-campus-gold fw-bold mb-3"><?php esc_html_e( 'Daftar Sekarang', 'educampus' ); ?></h4>
                        <p class="small text-white-50 mb-4"><?php esc_html_e( 'Ingin bergabung dengan program studi ini? Pendaftaran dibuka sekarang.', 'educampus' ); ?></p>
                        <div class="d-grid gap-2">
                            <a href="<?php echo esc_url( home_url( '/pmb' ) ); ?>" class="btn btn-campus-secondary btn-sm py-2">
                                <i class="bi bi-mortarboard-fill me-1"></i> <?php esc_html_e( 'Daftar Online', 'educampus' ); ?>
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm py-2">
                                <i class="bi-whatsapp me-1 text-success"></i> <?php esc_html_e( 'Tanya Admisi (WA)', 'educampus' ); ?>
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
        <h1 class="h2"><?php esc_html_e( 'Program Studi Tidak Ditemukan', 'educampus' ); ?></h1>
        <p class="text-campus-muted"><?php esc_html_e( 'Konten program studi sedang dipersiapkan.', 'educampus' ); ?></p>
    </div>
<?php
endif;
get_footer();
