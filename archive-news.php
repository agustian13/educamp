<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

global $wp_query;

$featured_id = null;
if ( have_posts() ) {
    the_post();
    $featured_id = get_the_ID();
}
?>
<?php
$news_search = '<form role="search" method="get" class="d-flex gap-2 mt-3" action="' . esc_url( home_url( '/' ) ) . '">
    <input type="hidden" name="post_type" value="post" />
    <div class="input-group">
        <input type="search" class="form-control form-control-lg bg-white bg-opacity-10 border-0 text-white placeholder-white-50 rounded-start-3" placeholder="' . esc_attr__( 'Cari berita...', 'educampus' ) . '" value="' . get_search_query() . '" name="s" required style="backdrop-filter:blur(4px);">
        <button class="btn btn-lg px-4 rounded-end-3" type="submit" style="background:#c5a059;color:#0a2540;"><i class="bi bi-search"></i></button>
    </div>
</form>';
educampus_page_hero( array(
    'title'           => get_theme_mod( 'educampus_archive_news_title', 'Berita & Pengumuman' ),
    'badge'           => get_theme_mod( 'educampus_archive_news_badge', 'BERITA & PENGUMUMAN' ),
    'content'         => '<p class="text-white-50 mb-0" style="max-width:550px;">' . esc_html( get_theme_mod( 'educampus_archive_news_desc', 'Informasi terhangat mengenai riset, akademik, dan kehidupan kampus.' ) ) . '</p>' . $news_search,
) ); ?>

<!-- Filter -->
<section class="bg-white border-bottom sticky-top" style="top:0;z-index:1020;">
    <div class="container py-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex flex-wrap gap-1 align-items-center">
                <a href="<?php echo esc_url( educampus_get_news_archive_url() ); ?>" class="btn btn-sm fw-semibold font-heading rounded-pill px-3 <?php echo ! is_category() ? 'btn-campus-primary' : 'btn-campus-outline'; ?>"><?php esc_html_e( 'Semua', 'educampus' ); ?></a>
                <?php
                $categories = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                    foreach ( $categories as $category ) {
                        $active = is_category( $category->slug ) ? 'btn-campus-primary' : 'btn-campus-outline';
                        echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="btn btn-sm rounded-pill px-3 font-heading fw-semibold ' . $active . '">' . esc_html( $category->name ) . '</a>';
                    }
                }
                ?>
            </div>
            <span class="small text-campus-muted font-heading fw-semibold"><i class="bi bi-file-text me-1"></i> <?php printf( _n( '%s Berita', '%s Berita', $wp_query->found_posts, 'educampus' ), number_format_i18n( $wp_query->found_posts ) ); ?></span>
        </div>
    </div>
</section>

<div class="bg-campus-light section-padding" style="padding-top:60px;padding-bottom:60px;">
    <div class="container">
        <?php educampus_breadcrumbs(); ?>

        <?php if ( $featured_id ) : 
            $categories = get_the_category( $featured_id );
            $cat_name = ! empty( $categories ) ? $categories[0]->name : '';
        ?>
        <div class="card border-0 shadow-campus-soft rounded-4 overflow-hidden bg-white transform-hover mb-5">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-7">
                    <?php if ( has_post_thumbnail( $featured_id ) ) : ?>
                        <?php echo get_the_post_thumbnail( $featured_id, 'campus-large', array( 'class' => 'w-100 h-100 object-fit-cover', 'style' => 'min-height:300px;' ) ); ?>
                    <?php else : ?>
                        <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center" style="min-height:300px;">
                            <i class="bi bi-newspaper fs-1 text-campus-gold opacity-50"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-5 d-flex">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <?php if ( $cat_name ) : ?>
                        <span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small align-self-start mb-2"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <span class="text-campus-muted small mb-2"><i class="bi bi-calendar-event me-1"></i><?php echo get_the_date( '', $featured_id ); ?></span>
                        <h2 class="h4 font-heading fw-bold text-campus-navy mb-2">
                            <a href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>" class="text-decoration-none text-campus-navy hover-secondary"><?php echo get_the_title( $featured_id ); ?></a>
                        </h2>
                        <p class="text-campus-muted small flex-grow-1"><?php echo wp_trim_words( get_the_excerpt( $featured_id ), 20 ); ?></p>
                        <a href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>" class="btn btn-campus-outline btn-sm align-self-start font-heading"><?php esc_html_e( 'Baca Selengkapnya', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-md-6 col-lg-4">
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card h-100 border-0 shadow-campus-soft rounded-4 bg-white transform-hover d-flex flex-column' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                        <div class="ratio ratio-16x9 overflow-hidden position-relative">
                            <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-start justify-content-end p-2" style="pointer-events:none;">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) : ?>
                                <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small" style="background:rgba(197,160,89,0.9);color:#0a2540;"><?php echo esc_html( $categories[0]->name ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="ratio ratio-16x9 bg-campus-navy d-flex align-items-center justify-content-center position-relative">
                            <i class="bi bi-newspaper fs-1 text-campus-gold opacity-40"></i>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-start justify-content-end p-2" style="pointer-events:none;">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) : ?>
                                <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small" style="background:rgba(197,160,89,0.9);color:#0a2540;"><?php echo esc_html( $categories[0]->name ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <span class="text-campus-muted small mb-2"><i class="bi bi-calendar-event me-1"></i><?php echo get_the_date(); ?></span>
                            <h3 class="h6 font-heading fw-bold text-campus-navy mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none stretched-link"><?php the_title(); ?></a>
                            </h3>
                            <p class="card-text text-campus-muted small flex-grow-1"><?php echo wp_trim_words( get_the_excerpt(), 12 ); ?></p>
                            <span class="text-campus-gold text-decoration-none small fw-bold font-heading d-inline-flex align-items-center position-relative z-1" style="pointer-events:none;">
                                <?php esc_html_e( 'Baca', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </article>
                </div>
                <?php endwhile; ?>

                <div class="col-12 mt-3">
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
                    <i class="bi bi-newspaper text-campus-muted" style="font-size:3rem;"></i>
                    <h3 class="h5 mt-3 text-campus-navy"><?php esc_html_e( 'Belum Ada Berita', 'educampus' ); ?></h3>
                    <p class="text-campus-muted small"><?php esc_html_e( 'Belum ada berita yang dipublikasikan. Silakan kembali lagi nanti.', 'educampus' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
