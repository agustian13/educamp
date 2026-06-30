<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();

global $wp_query;
?>
<?php educampus_page_hero( array(
    'title' => sprintf( __( 'Hasil Pencarian: "%s"', 'educampus' ), get_search_query() ),
    'badge' => __( 'PENCARIAN', 'educampus' ),
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-4 mt-2">
        <main id="primary" class="col-lg-8 site-main">
            <?php if ( have_posts() ) : ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-campus-muted mb-0 font-body">
                        <?php
                        printf(
                            esc_html__( 'Ditemukan %d hasil untuk pencarian "%s"', 'educampus' ),
                            $wp_query->found_posts,
                            get_search_query()
                        );
                        ?>
                    </p>
                </div>
                <div class="row g-4">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="col-12">
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover' ); ?>>
                                <div class="row g-0 align-items-center">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="col-md-4 col-lg-3">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover w-100 h-100', 'style' => 'min-height: 180px; object-fit: cover;' ) ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="<?php echo has_post_thumbnail() ? 'col-md-8 col-lg-9' : 'col-12'; ?>">
                                        <div class="card-body p-4">
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                <span class="badge bg-campus-gold bg-opacity-10 text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small">
                                                    <?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ?? __( 'Post', 'educampus' ) ); ?>
                                                </span>
                                                <span class="text-campus-muted small">
                                                    <i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date(); ?>
                                                </span>
                                            </div>
                                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-2">
                                                <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            <p class="text-campus-muted small mb-0">
                                                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="educampus-pagination">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => sprintf( '<i class="bi bi-arrow-left"></i> %s', __( 'Sebelumnya', 'educampus' ) ),
                        'next_text' => sprintf( '%s <i class="bi bi-arrow-right"></i>', __( 'Berikutnya', 'educampus' ) ),
                    ) );
                    ?>
                </div>
                <div class="mt-3 text-center small text-campus-muted"><?php printf( __( 'Menampilkan halaman %d dari %d', 'educampus' ), max( 1, get_query_var( 'paged' ) ), $wp_query->max_num_pages ?: 1 ); ?></div>
            <?php else : ?>
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-5 text-center">
                    <i class="bi bi-search text-campus-muted" style="font-size: 4rem;"></i>
                    <h2 class="h3 font-heading fw-bold text-campus-navy mt-4 mb-3">
                        <?php esc_html_e( 'Tidak Ada Hasil Ditemukan', 'educampus' ); ?>
                    </h2>
                    <p class="text-campus-muted mb-4">
                        <?php esc_html_e( 'Maaf, tidak ada konten yang cocok dengan kata kunci pencarian Anda. Coba dengan kata kunci lain.', 'educampus' ); ?>
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
        <aside id="secondary" class="col-lg-4 mt-5 mt-lg-0 widget-area">
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Pencarian Lanjutan', 'educampus' ); ?></h3>
                <?php get_search_form(); ?>
            </div>
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Kategori', 'educampus' ); ?></h3>
                <ul class="list-unstyled mb-0">
                    <?php
                    wp_list_categories( array(
                        'title_li'   => '',
                        'show_count' => true,
                        'style'      => 'list',
                    ) );
                    ?>
                </ul>
            </div>
        </aside>
    </div>
</div>
<?php
get_footer();
