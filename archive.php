<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<?php
$post_type = get_post_type();
$badge = $post_type ? ( get_post_type_object( $post_type )->labels->name ?? '' ) : '';
$archive_desc = get_the_archive_description();
$hero_content = '';
if ( $archive_desc ) {
    $hero_content = '<p class="lead text-white-50 font-body mb-0 mt-3">' . wp_kses_post( $archive_desc ) . '</p>';
}
educampus_page_hero( array(
    'title'           => get_the_archive_title(),
    'badge'           => $badge,
    'container_class' => 'text-center',
    'content'         => $hero_content,
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-4 mt-2">
        <main id="primary" class="col-lg-8 site-main">
            <?php if ( have_posts() ) : ?>
                <div class="row g-4">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="col-md-6">
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover h-100 d-flex flex-column' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="ratio ratio-16x9">
                                        <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                    <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="card-text text-campus-muted small flex-grow-1">
                                        <?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
                                    </p>
                                    <a href="<?php the_permalink(); ?>" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-3">
                                        <?php esc_html_e( 'Selengkapnya', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
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
            <?php else : ?>
                <div class="text-center py-5">
                    <i class="bi bi-archive text-campus-muted" style="font-size: 4rem;"></i>
                    <h2 class="h3 font-heading fw-bold text-campus-navy mt-4">
                        <?php esc_html_e( 'Tidak Ada Konten', 'educampus' ); ?>
                    </h2>
                    <p class="text-campus-muted">
                        <?php esc_html_e( 'Belum ada konten yang tersedia untuk arsip ini.', 'educampus' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-campus-primary mt-3">
                        <?php esc_html_e( 'Kembali ke Beranda', 'educampus' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </main>
        <aside id="secondary" class="col-lg-4 mt-5 mt-lg-0 widget-area">
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Cari Informasi', 'educampus' ); ?></h3>
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
