<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container my-5 section-padding">
    <div class="row">
        <!-- Main Content Area -->
        <main id="primary" class="col-lg-8 site-main">
            <?php if ( have_posts() ) : ?>
                <header class="page-header mb-4">
                    <h1 class="page-title border-bottom pb-2">
                        <?php
                        if ( is_home() && ! is_front_page() ) {
                            single_post_title();
                        } else {
                            the_archive_title();
                        }
                        ?>
                    </h1>
                </header>

                <div class="row g-4">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <div class="col-md-6">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 border-0 shadow-campus-soft rounded-campus overflow-hidden'); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="ratio ratio-16x9">
                                        <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="text-campus-gold font-heading small fw-bold mb-2">
                                        <?php the_date(); ?>
                                    </div>
                                    <h3 class="card-title h5 mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <div class="card-text text-campus-muted mb-4 flex-grow-1">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div>
                                        <a href="<?php the_permalink(); ?>" class="btn-campus-outline btn-sm py-2 px-3 text-uppercase font-heading font-size-sm">
                                            <?php esc_html_e( 'Selengkapnya', 'educampus' ); ?>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    <div class="educampus-pagination">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                        'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                    ) );
                    ?>
                    </div>

            <?php else : ?>
                <section class="no-results not-found text-center py-5">
                    <i class="bi bi-search text-campus-muted" style="font-size: 3rem;"></i>
                    <h2 class="h3 mt-3"><?php esc_html_e( 'Tidak Ada Konten Ditemukan', 'educampus' ); ?></h2>
                    <p class="text-campus-muted"><?php esc_html_e( 'Maaf, apa yang Anda cari tidak dapat kami temukan. Coba cari dengan kata kunci lain.', 'educampus' ); ?></p>
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-8 col-lg-6">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </main>

        <!-- Sidebar Area -->
        <aside id="secondary" class="col-lg-4 mt-5 mt-lg-0 widget-area">
            <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            <?php else : ?>
                <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                    <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Cari Informasi', 'educampus' ); ?></h3>
                    <?php get_search_form(); ?>
                </div>
                <div class="card border-0 shadow-campus-soft rounded-campus p-4 bg-white">
                    <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Kategori', 'educampus' ); ?></h3>
                    <ul class="list-unstyled mb-0">
                        <?php wp_list_categories( array(
                            'title_li'   => '',
                            'show_count' => true,
                            'style'      => 'list',
                        ) ); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</div>

<?php
get_footer();
