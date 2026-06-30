<?php
/**
 * The template for displaying faculty CPT archive pages
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<?php educampus_page_hero( array(
    'title'           => get_theme_mod( 'educampus_archive_faculty_title', 'Fakultas & Sekolah Pascasarjana' ),
    'badge'           => get_theme_mod( 'educampus_archive_faculty_badge', 'AKADEMIK' ),
    'container_class' => 'text-center',
    'content'         => '<p class="lead text-white-50 font-body mb-0" style="max-width: 600px; margin: 0 auto;">' . esc_html( get_theme_mod( 'educampus_archive_faculty_desc', 'EduCampus menyelenggarakan program pendidikan unggul di bawah rumpun ilmu teknik, sains, ekonomi, dan hukum.' ) ) . '</p>',
) ); ?>

<!-- Main Grid Section -->
<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>

    <div class="row g-4 mt-3">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                        <!-- Thumbnail -->
                        <div class="ratio ratio-4x3">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'object-fit-cover img-fluid' ) ); ?>
                            <?php else : ?>
                                <div class="bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                    <i class="bi bi-building fs-1 text-campus-gold"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h3 class="h6 font-heading fw-bold text-campus-navy mb-3"><?php the_title(); ?></h3>
                            <p class="card-text text-campus-muted small flex-grow-1">
                                <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
                            </p>
                            <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-3 d-inline-flex align-items-center">
                                <?php esc_html_e( 'Selengkapnya', 'educampus' ); ?> <i class="bi-chevron-right small ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Pagination -->
            <div class="mt-5 col-12">
                <div class="educampus-pagination">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                    'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                ) );
                ?>
                </div>
            </div>

        <?php else : ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-building text-campus-muted" style="font-size: 3rem;"></i>
                <h3 class="h5 mt-3 text-campus-navy"><?php esc_html_e( 'Belum Ada Fakultas', 'educampus' ); ?></h3>
                <p class="text-campus-muted small"><?php esc_html_e( 'Data fakultas sedang dipersiapkan oleh administrator.', 'educampus' ); ?></p>
            </div>
        <?php endif; ?>
        ?>
    </div>
</div>

<?php
get_footer();
