<?php
/**
 * The template for displaying program CPT archive pages
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

// Get GET filters
$keyword_filter = isset( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
$faculty_filter = isset( $_GET['prog_faculty'] ) ? intval( $_GET['prog_faculty'] ) : 0;
$level_filter   = isset( $_GET['prog_level'] ) ? sanitize_text_field( $_GET['prog_level'] ) : '';

// Custom Query setup
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'program',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'meta_query'     => array(),
    'tax_query'      => array(),
);

if ( ! empty( $keyword_filter ) ) {
    $args['s'] = $keyword_filter;
}

if ( ! empty( $level_filter ) ) {
    $args['tax_query'][] = array(
        'taxonomy' => 'program_level',
        'field'    => 'slug',
        'terms'    => $level_filter,
    );
}

if ( ! empty( $faculty_filter ) ) {
    $args['meta_query'][] = array(
        'key'     => '_program_faculty_id',
        'value'   => $faculty_filter,
        'compare' => '=',
    );
}

$program_query = new WP_Query( $args );
?>

<?php educampus_page_hero( array(
    'title'           => get_theme_mod( 'educampus_archive_program_title', esc_html__( 'Pilihan Program Studi', 'educampus' ) ),
    'badge'           => get_theme_mod( 'educampus_archive_program_badge', esc_html__( 'ADMISI', 'educampus' ) ),
    'class'           => 'bg-islamic-ornament-half',
    'container_class' => 'text-center',
    'content'         => '<p class="lead text-white-50 font-body mb-0" style="max-width: 600px; margin: 0 auto;">' . esc_html( get_theme_mod( 'educampus_archive_program_desc', esc_html__( 'Pilih program studi impian Anda dengan kurikulum berstandar internasional dan peluang karir yang cemerlang.', 'educampus' ) ) ) . '</p>',
) ); ?>

<!-- Main Grid Section -->
<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>

    <div class="row g-4 mt-2">
        <!-- Sidebar Filter (Col 3) -->
        <aside class="col-lg-3">
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 bg-white program-filter-sidebar sticky-lg-top">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-4 font-heading fw-bold"><i class="bi bi-funnel-fill text-campus-gold me-2"></i> <?php esc_html_e( 'Filter Prodi', 'educampus' ); ?></h3>
                
                <form method="get" action="">
                    <!-- Keyword Search -->
                    <div class="mb-4">
                        <label for="keyword" class="form-label font-heading fw-bold text-campus-navy small"><?php esc_html_e( 'Cari Nama Program', 'educampus' ); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm border-end-0" id="keyword" name="keyword" placeholder="<?php esc_attr_e( 'Contoh: Informatika...', 'educampus' ); ?>" value="<?php echo esc_attr( $keyword_filter ); ?>">
                            <span class="input-group-text bg-white border-start-0 text-muted"><i class="bi bi-search small"></i></span>
                        </div>
                    </div>

                    <!-- Faculty Select -->
                    <div class="mb-4">
                        <label for="faculty" class="form-label font-heading fw-bold text-campus-navy small"><?php esc_html_e( 'Fakultas', 'educampus' ); ?></label>
                        <select class="form-select form-select-sm" id="faculty" name="prog_faculty">
                            <option value="0"><?php esc_html_e( 'Semua Fakultas', 'educampus' ); ?></option>
                            <?php
                            $faculties = get_posts( array(
                                    'post_type'      => 'faculty',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                            ) );
                            foreach ( $faculties as $f ) {
                                    $selected = ( $faculty_filter === $f->ID ) ? 'selected' : '';
                                    echo '<option value="' . esc_attr( $f->ID ) . '" ' . $selected . '>' . esc_html( $f->post_title ) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Level Select -->
                    <div class="mb-4">
                        <label for="level" class="form-label font-heading fw-bold text-campus-navy small"><?php esc_html_e( 'Jenjang', 'educampus' ); ?></label>
                        <select class="form-select form-select-sm" id="level" name="prog_level">
                            <option value=""><?php esc_html_e( 'Semua Jenjang', 'educampus' ); ?></option>
                            <?php
                            $levels = get_terms( array(
                                'taxonomy'   => 'program_level',
                                'hide_empty' => false,
                            ) );
                            if ( ! is_wp_error( $levels ) && ! empty( $levels ) ) {
                                foreach ( $levels as $l ) {
                                    $selected = ( $level_filter === $l->slug ) ? 'selected' : '';
                                    echo '<option value="' . esc_attr( $l->slug ) . '" ' . $selected . '>' . esc_html( $l->name ) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Filter Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-campus-primary btn-sm py-2">
                            <?php esc_html_e( 'Terapkan Filter', 'educampus' ); ?>
                        </button>
                        <?php if ( ! empty( $keyword_filter ) || ! empty( $faculty_filter ) || ! empty( $level_filter ) ) : ?>
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>" class="btn btn-campus-outline btn-sm py-2 text-center text-decoration-none">
                                <?php esc_html_e( 'Reset Filter', 'educampus' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Right Grid Content (Col 9) -->
        <main class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h2 class="h6 text-campus-navy mb-0 fw-bold">
                    <?php printf( esc_html__( 'Menampilkan %s Program Studi', 'educampus' ), $program_query->found_posts ); ?>
                </h2>
            </div>

            <div class="row g-4">
                <?php if ( $program_query->have_posts() ) : ?>
                    <?php while ( $program_query->have_posts() ) : $program_query->the_post(); ?>
                        <?php
                        $prog_accred = get_post_meta( get_the_ID(), '_program_accreditation', true );
                        $levels = get_the_terms( get_the_ID(), 'program_level' );
                        $level_name = (!empty($levels) && !is_wp_error($levels)) ? $levels[0]->name : 'Sarjana (S1)';
                        
                        $faculty_id = get_post_meta( get_the_ID(), '_program_faculty_id', true );
                        $faculty_name = $faculty_id ? get_the_title( $faculty_id ) : 'Umum';
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                                <!-- Thumbnail -->
                                <div class="position-relative overflow-hidden">
                                    <div class="ratio ratio-16x9">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'object-fit-cover img-fluid' ) ); ?>
                                        <?php else : ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-islamic-ornament" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%) !important;">
                                                <i class="bi bi-mortarboard text-campus-gold" style="font-size: 2.5rem; opacity: 0.85; z-index: 1;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="position-absolute top-0 end-0 bg-campus-navy text-white text-uppercase font-heading fw-bold px-2 py-1 m-2 rounded shadow-sm" style="font-size: 0.65rem; z-index: 2;">
                                        <?php echo esc_html( $faculty_name ); ?>
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                    <div class="mb-3">
                                        <span class="text-campus-muted small"><?php echo esc_html( $level_name ); ?></span>
                                    </div>
                                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-3"><?php the_title(); ?></h3>
                                    <p class="card-text text-campus-muted small flex-grow-1">
                                        <?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
                                    </p>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-campus-outline btn-sm mt-4 align-self-start font-heading">
                                        <?php esc_html_e( 'Profil Prodi', 'educampus' ); ?> <i class="bi bi-chevron-right small ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>

                    <!-- Pagination -->
                    <div class="mt-5 col-12">
                        <div class="educampus-pagination">
                        <?php
                        echo paginate_links( array(
                            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'format'    => '?paged=%#%',
                            'current'   => max( 1, $paged ),
                            'total'     => $program_query->max_num_pages,
                            'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                            'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                            'type'      => 'list',
                        ) );
                        ?>
                        </div>
                        ?>
                    </div>

                <?php else : ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search text-campus-muted" style="font-size: 3rem;"></i>
                        <h3 class="h5 mt-3 text-campus-navy"><?php esc_html_e( 'Tidak Ada Program Studi Ditemukan', 'educampus' ); ?></h3>
                        <p class="text-campus-muted small"><?php esc_html_e( 'Coba sesuaikan pilihan filter atau kata kunci pencarian Anda.', 'educampus' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php
get_footer();
