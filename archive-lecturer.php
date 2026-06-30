<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$current_letter = sanitize_text_field( get_query_var( 'letter', '' ) );
$current_program = absint( get_query_var( 'program_id', '' ) );
$alphabet = range( 'A', 'Z' );
$all_programs = get_posts( array(
    'post_type'      => 'program',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );
$base_url = get_post_type_archive_link( 'lecturer' );
?>

<?php educampus_page_hero( array(
    'title'           => get_theme_mod( 'educampus_archive_lecturer_title', 'Direktori Dosen & Akademisi' ),
    'badge'           => get_theme_mod( 'educampus_archive_lecturer_badge', 'CIVITAS' ),
    'container_class' => 'text-center',
    'content'         => '<p class="lead text-white-50 font-body mb-0 mx-auto" style="max-width:580px;">' . esc_html( get_theme_mod( 'educampus_archive_lecturer_desc', 'Mengenal jajaran staf pendidik profesional dan peneliti ahli di lingkungan kampus EduCampus.' ) ) . '</p>',
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>

    <!-- Filter Section -->
    <div class="rounded-campus shadow-campus-soft p-3 p-md-4 mb-4 filter-gradient">

        <!-- A-Z Index -->
        <div class="mb-3 pb-3 border-bottom">
            <span class="text-campus-muted font-heading small fw-bold d-block mb-2"><i class="bi bi-fonts me-1"></i>INDEX NAMA</span>
            <div class="d-flex flex-wrap gap-1">
                <a href="<?php echo esc_url( $base_url ); ?>" class="letter-link d-inline-flex align-items-center justify-content-center fw-bold font-heading text-decoration-none rounded-2 px-2 py-1 <?php echo empty( $current_letter ) ? 'letter-active' : 'letter-inactive'; ?>">All</a>
                <?php foreach ( $alphabet as $letter ) :
                    $active = strtoupper( $current_letter ) === $letter;
                    $url = add_query_arg( 'letter', $letter, $base_url );
                ?>
                <a href="<?php echo esc_url( $url ); ?>" class="letter-link d-inline-flex align-items-center justify-content-center fw-bold font-heading text-decoration-none rounded-2 px-2 py-1 <?php echo $active ? 'letter-active' : 'letter-inactive'; ?>"><?php echo $letter; ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Prodi Filter + Search -->
        <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
            <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
                <span class="text-campus-muted font-heading small text-nowrap flex-shrink-0"><i class="bi bi-mortarboard me-1"></i>PRODI</span>
                <form method="get" class="d-flex">
                    <input type="hidden" name="post_type" value="lecturer" />
                    <input type="hidden" name="letter" value="<?php echo esc_attr( $current_letter ); ?>" />
                    <select name="program_id" class="form-select form-select-sm bg-campus-light border-0 filter-field" style="padding:0.4rem 1.8rem 0.4rem 0.6rem;max-width:200px;" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e( 'Semua Prodi', 'educampus' ); ?></option>
                        <?php foreach ( $all_programs as $prog ) : ?>
                        <option value="<?php echo esc_attr( $prog->ID ); ?>" <?php selected( $current_program, $prog->ID ); ?>><?php echo esc_html( $prog->post_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <noscript><button type="submit" class="btn btn-campus-primary btn-sm ms-2 px-3"><?php esc_html_e( 'Filter', 'educampus' ); ?></button></noscript>
                </form>
                <?php if ( ! empty( $current_program ) ) : ?>
                <a href="<?php echo esc_url( $base_url ); ?>" class="btn btn-sm btn-outline-secondary px-2 flex-shrink-0" style="padding:0.3rem 0.45rem;"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </div>
            <div class="ms-md-auto w-100 w-md-auto">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="hidden" name="post_type" value="lecturer" />
                    <input type="hidden" name="letter" value="<?php echo esc_attr( $current_letter ); ?>" />
                    <?php if ( ! empty( $current_program ) ) : ?>
                    <input type="hidden" name="program_id" value="<?php echo esc_attr( $current_program ); ?>" />
                    <?php endif; ?>
                    <div class="d-flex gap-1">
                        <input type="search" class="form-control form-control-sm bg-campus-light border-0 filter-field flex-grow-1" placeholder="<?php esc_attr_e( 'Cari dosen...', 'educampus' ); ?>" value="<?php echo get_search_query(); ?>" name="s" style="padding:0.5rem 0.75rem;">
                        <button type="submit" class="btn btn-campus-primary btn-sm px-3 d-flex align-items-center gap-1" style="padding:0.5rem 0.75rem;"><i class="bi bi-search"></i><span class="d-none d-sm-inline">Cari</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .letter-link { font-size:0.78rem; width:32px; height:28px; transition:all 0.15s ease; }
    .letter-active { background:var(--color-primary); color:#fff; box-shadow:0 0 0 3px rgba(10,37,64,0.3); }
    .letter-inactive { background:#f1f5f9; color:#374151; }
    .letter-inactive:hover { background:#e2e8f0; color:var(--color-primary); }
    .filter-field { border-radius:6px !important; transition:all 0.2s ease; }
    .filter-field:focus { box-shadow:0 0 0 3px rgba(10,37,64,0.2) !important; background:#fff !important; }
    .filter-gradient { background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 50%, #e8edf5 100%); }
    </style>

    <!-- Stats bar + View Toggle -->
    <?php
    global $wp_query;
    $total = $wp_query->found_posts;
    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
        <div>
            <span class="font-heading fw-bold text-campus-navy"><?php echo number_format( $total ); ?></span>
            <span class="text-campus-muted small"> <?php esc_html_e( 'Dosen ditemukan', 'educampus' ); ?></span>
            <?php if ( ! empty( $current_letter ) ) : ?>
            <span class="text-campus-muted small ms-2">| Index: <strong class="text-campus-gold"><?php echo strtoupper( $current_letter ); ?></strong></span>
            <?php endif; ?>
            <?php if ( ! empty( $current_program ) ) : 
                $prog_title = get_the_title( $current_program );
            ?>
            <span class="text-campus-muted small ms-2">| Prodi: <strong class="text-campus-gold"><?php echo esc_html( $prog_title ); ?></strong></span>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ( $wp_query->max_num_pages > 1 ) : ?>
            <small class="text-campus-muted d-none d-sm-inline"><?php printf( esc_html__( 'Halaman %1$s dari %2$s', 'educampus' ), max( 1, get_query_var( 'paged', 1 ) ), $wp_query->max_num_pages ); ?></small>
            <?php endif; ?>
            <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                <button type="button" class="btn btn-outline-campus-primary lecturer-view-btn active" data-view="grid" title="Grid View">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="btn btn-outline-campus-primary lecturer-view-btn" data-view="list" title="List View">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div class="lecturer-view-grid">
        <div class="row g-3">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post();
                    $position           = get_post_meta( get_the_ID(), '_lecturer_position', true ) ?: 'Dosen Pengajar';
                    $jabatan_fungsional = get_post_meta( get_the_ID(), '_lecturer_jabatan_fungsional', true );
                    $first_letter = strtoupper( substr( get_the_title(), 0, 1 ) );
                    $prog_id = get_post_meta( get_the_ID(), '_lecturer_program_id', true );
                    $prog_name = $prog_id ? get_the_title( $prog_id ) : '';
                ?>
                <div class="col-sm-6 col-lg-4 col-xl-3 section-reveal">
                    <div class="card border-0 shadow-campus-soft rounded-3 overflow-hidden h-100 bg-white">
                        <div class="d-flex align-items-center gap-3 p-3">
                            <div class="flex-shrink-0">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded-2 object-fit-cover', 'style' => 'width:56px;height:56px;' ) ); ?>
                                <?php else : ?>
                                <div class="bg-campus-navy text-white rounded-2 d-flex align-items-center justify-content-center fw-bold font-heading fs-5" style="width:56px;height:56px;">
                                    <?php echo esc_html( $first_letter ); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-width-0">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-0 lh-sm"><?php the_title(); ?></h3>
                                <?php if ( $jabatan_fungsional ) : ?>
                                <span class="small text-campus-gold fw-semibold"><?php echo esc_html( $jabatan_fungsional ); ?></span>
                                <?php endif; ?>
                                <span class="small text-campus-muted d-block lh-sm"><?php echo esc_html( $position ?: ( $jabatan_fungsional ? '' : 'Dosen Pengajar' ) ); ?></span>
                            </div>
                        </div>
                        <?php if ( $prog_name ) : ?>
                        <div class="px-3 pb-2">
                            <span class="badge bg-campus-light text-campus-muted fw-normal small rounded-1 px-2 py-1"><?php echo esc_html( $prog_name ); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="px-3 pb-3 mt-auto">
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-secondary w-100 rounded-2 font-heading fw-semibold">
                                <?php esc_html_e( 'Lihat Profil', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1 small"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                <div class="col-12 mt-4">
                    <?php
                    $pagination_add = array();
                    if ( ! empty( $current_letter ) ) $pagination_add['letter'] = $current_letter;
                    if ( ! empty( $current_program ) ) $pagination_add['program_id'] = $current_program;
                    ?>
                    <div class="educampus-pagination">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                        'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                        'add_args'  => $pagination_add,
                    ) );
                    ?>
                    </div>
                </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-people text-campus-muted d-block" style="font-size:3.5rem;line-height:1;"></i>
                    <h3 class="h5 mt-3 text-campus-navy">
                        <?php if ( ! empty( $current_letter ) && ! empty( $current_program ) ) : ?>
                            <?php printf( esc_html__( 'Tidak Ada Dosen dengan Awal Huruf "%s" di Program Studi Terpilih', 'educampus' ), esc_html( strtoupper( $current_letter ) ) ); ?>
                        <?php elseif ( ! empty( $current_letter ) ) : ?>
                            <?php printf( esc_html__( 'Tidak Ada Dosen dengan Awal Huruf "%s"', 'educampus' ), esc_html( strtoupper( $current_letter ) ) ); ?>
                        <?php elseif ( ! empty( $current_program ) ) : ?>
                            <?php esc_html_e( 'Tidak Ada Dosen di Program Studi Terpilih', 'educampus' ); ?>
                        <?php else : ?>
                            <?php esc_html_e( 'Belum Ada Data Dosen', 'educampus' ); ?>
                        <?php endif; ?>
                    </h3>
                    <p class="text-campus-muted small"><?php esc_html_e( 'Data dosen sedang dipersiapkan oleh administrator.', 'educampus' ); ?></p>
                    <?php if ( ! empty( $current_letter ) || ! empty( $current_program ) ) : ?>
                    <a href="<?php echo esc_url( $base_url ); ?>" class="btn btn-campus-primary btn-sm rounded-pill px-4 mt-3"><?php esc_html_e( 'Tampilkan Semua', 'educampus' ); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- List View -->
    <div class="lecturer-view-list" style="display:none;">
        <?php
        // Use $wp_query->posts directly to avoid loop conflicts
        $list_posts = $wp_query->posts;
        if ( ! empty( $list_posts ) ) :
            foreach ( $list_posts as $list_post ) :
                setup_postdata( $list_post );
                $position           = get_post_meta( $list_post->ID, '_lecturer_position', true ) ?: 'Dosen Pengajar';
                $jabatan_fungsional = get_post_meta( $list_post->ID, '_lecturer_jabatan_fungsional', true );
                $prog_id = get_post_meta( $list_post->ID, '_lecturer_program_id', true );
                $prog_name = $prog_id ? get_the_title( $prog_id ) : '';
                $nidn = get_post_meta( $list_post->ID, '_lecturer_nidn', true );
                $email = get_post_meta( $list_post->ID, '_lecturer_email', true );
            ?>
            <div class="d-flex align-items-center gap-3 py-3 px-3 border-bottom border-light-subtle lecturer-list-item">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="<?php echo get_permalink( $list_post ); ?>" class="text-decoration-none fw-bold font-heading" style="color:var(--color-primary);font-size:0.9rem;">
                            <?php echo get_the_title( $list_post ); ?>
                            <i class="bi bi-box-arrow-up-right" style="font-size:0.6rem;opacity:0.4;"></i>
                        </a>
                        <?php if ( $jabatan_fungsional ) : ?>
                        <span class="badge bg-campus-gold text-campus-navy rounded-pill font-heading fw-bold" style="font-size:0.62rem;"><?php echo esc_html( $jabatan_fungsional ); ?></span>
                        <?php endif; ?>
                        <?php if ( $prog_name ) : ?>
                        <span class="badge bg-campus-light text-campus-muted fw-normal rounded-pill" style="font-size:0.62rem;"><?php echo esc_html( $prog_name ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap gap-3 align-items-center mt-1 small text-campus-muted">
                        <?php if ( $nidn ) : ?>
                        <span>NIDN: <?php echo esc_html( $nidn ); ?></span>
                        <?php endif; ?>
                        <?php if ( $email ) : ?>
                        <span><i class="bi bi-envelope me-1" style="color:var(--color-primary);"></i><?php echo esc_html( $email ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?php echo get_permalink( $list_post ); ?>" class="btn btn-sm btn-outline-campus-primary flex-shrink-0 d-none d-sm-inline-block" style="font-size:0.75rem;">Profil</a>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>

            <?php if ( $wp_query->max_num_pages > 1 ) : ?>
            <div class="mt-4">
                <div class="educampus-pagination">
                <?php
                $pagination_add = array();
                if ( ! empty( $current_letter ) ) $pagination_add['letter'] = $current_letter;
                if ( ! empty( $current_program ) ) $pagination_add['program_id'] = $current_program;
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                    'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                    'add_args'  => $pagination_add,
                ) );
                ?>
                </div>
            </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="text-center py-5">
                <i class="bi bi-people text-campus-muted d-block" style="font-size:3.5rem;line-height:1;"></i>
                <h3 class="h5 mt-3 text-campus-navy">
                    <?php esc_html_e( 'Belum Ada Data Dosen', 'educampus' ); ?>
                </h3>
            </div>
        <?php endif; ?>
    </div>

    <script>
    (function() {
        var btns = document.querySelectorAll('.lecturer-view-btn');
        var grid = document.querySelector('.lecturer-view-grid');
        var list = document.querySelector('.lecturer-view-list');
        var stored = localStorage.getItem('lecturer_view') || 'grid';

        function setView(v) {
            btns.forEach(function(b) {
                b.classList.toggle('active', b.dataset.view === v);
            });
            if (grid) grid.style.display = v === 'grid' ? '' : 'none';
            if (list) list.style.display = v === 'list' ? '' : 'none';
            localStorage.setItem('lecturer_view', v);
        }
        btns.forEach(function(b) {
            b.addEventListener('click', function() { setView(this.dataset.view); });
        });
        setView(stored);
    })();
    </script>
</div>

<?php get_footer(); ?>
