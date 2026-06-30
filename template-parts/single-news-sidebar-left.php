<?php
/**
 * Single News — Sidebar Left Layout
 * Sidebar on left, content on right.
 *
 * @package EduCampus
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_id      = get_the_ID();
$reading_time = ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ?: 1;

$terms = get_the_terms( $post_id, 'news_category' );
if ( empty( $terms ) || is_wp_error( $terms ) ) {
    $terms = get_the_terms( $post_id, 'category' );
}
$cat_name  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : 'Berita';
$cat_color = ! empty( $terms ) && ! is_wp_error( $terms ) ? get_term_meta( $terms[0]->term_id, 'category_color', true ) : '';
?>
<!-- Hero Bar -->
<div class="bg-campus-navy bg-islamic-ornament-half text-white py-4 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <?php educampus_breadcrumbs( 'text-white-50', 'text-white-50 opacity-75' ); ?>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
            <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small" style="background:<?php echo $cat_color ?: '#c5a059'; ?>;color:#0a2540;">
                <?php echo esc_html( $cat_name ); ?>
            </span>
            <span class="text-white-50 small"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date(); ?></span>
            <span class="text-white-50 small"><i class="bi bi-clock me-1"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
        </div>
    </div>
</div>

<div class="container my-5 pb-5">
    <div class="row g-4">

        <!-- SIDEBAR LEFT -->
        <aside id="secondary" class="col-lg-4 order-lg-1 widget-area">
            <div style="position:sticky;top:100px;">

                <!-- Search -->
                <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                    <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><i class="bi bi-search me-1"></i> <?php echo esc_html__( 'Cari Berita', 'educampus' ); ?></h3>
                    <form role="search" method="get" class="d-flex gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="hidden" name="post_type" value="post" />
                        <input type="search" class="form-control rounded-3 border-light shadow-none bg-campus-light px-3" placeholder="<?php echo esc_attr__( 'Kata kunci...', 'educampus' ); ?>" name="s" required />
                        <button type="submit" class="btn btn-campus-primary px-3 rounded-3"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <!-- Categories -->
                <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                    <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><i class="bi bi-tags me-1"></i> <?php echo esc_html__( 'Kategori', 'educampus' ); ?></h3>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <?php
                        $categories = get_terms( array( 'taxonomy' => 'news_category', 'hide_empty' => false ) );
                        if ( empty( $categories ) || is_wp_error( $categories ) ) {
                            $categories = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
                        }
                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $category ) {
                                echo '<li class="d-flex justify-content-between align-items-center">';
                                echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="text-campus-navy text-decoration-none hover-gold"><i class="bi bi-chevron-right small text-campus-gold me-1"></i> ' . esc_html( $category->name ) . '</a>';
                                echo '<span class="badge bg-light text-campus-navy rounded-pill">' . esc_html( $category->count ) . '</span>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- CTA -->
                <div class="card border-0 bg-campus-navy text-white shadow-campus-soft rounded-campus p-4 text-center position-relative overflow-hidden">
                    <i class="bi bi-mortarboard-fill deco-icon" style="opacity:0.08;font-size:6rem;top:-10px;right:-15px;color:#fff;animation:float-rotate 8s ease-in-out infinite;"></i>
                    <div class="position-relative z-1">
                        <i class="bi bi-mortarboard-fill text-campus-gold fs-3 mb-2 d-block"></i>
                        <h4 class="h5 font-heading text-campus-gold fw-bold mb-2"><?php echo esc_html__( 'Admisi Pendaftaran', 'educampus' ); ?></h4>
                        <p class="small text-white-50 mb-4"><?php echo esc_html__( 'Ada pertanyaan mengenai tata cara pendaftaran, syarat jalur prestasi, atau biaya kuliah?', 'educampus' ); ?></p>
                        <div class="d-grid gap-2">
                            <a href="<?php echo esc_url( home_url( '/pmb' ) ); ?>" class="btn btn-campus-secondary btn-sm py-2"><?php echo esc_html__( 'Brosur Digital PMB', 'educampus' ); ?></a>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm py-2"><i class="bi bi-whatsapp text-success me-1"></i> <?php echo esc_html__( 'Kontak via WhatsApp', 'educampus' ); ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- CONTENT RIGHT -->
        <main id="primary" class="col-lg-8 order-lg-2 site-main">
            <!-- Featured Image -->
            <?php if ( has_post_thumbnail() ) : ?>
            <div class="rounded-4 overflow-hidden shadow-campus-soft mb-4">
                <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid w-100', 'style' => 'max-height:480px;object-fit:cover;' ) ); ?>
            </div>
            <?php endif; ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-campus-soft rounded-campus bg-white overflow-hidden' ); ?>>
                <div class="card-body p-4 p-md-5">
                    <h1 class="h2 fw-bold text-campus-navy mb-3 lh-sm"><?php the_title(); ?></h1>

                    <div class="d-flex flex-wrap align-items-center gap-3 text-campus-muted small mb-4 pb-3 border-bottom">
                        <span class="d-flex align-items-center gap-1">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', get_the_author(), array( 'class' => 'rounded-circle' ) ); ?>
                            <span><?php the_author(); ?></span>
                        </span>
                        <span><i class="bi bi-calendar-event me-1 text-campus-gold"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
                        <span><i class="bi bi-clock me-1 text-campus-gold"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
                    </div>

                    <div class="entry-content mb-4" style="font-size:1rem;line-height:1.8;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags( $post_id );
                    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-4 pt-3 border-top">
                        <span class="small fw-bold text-campus-muted font-heading me-1"><i class="bi bi-tags me-1"></i> TAGS:</span>
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge bg-light text-campus-navy text-decoration-none px-2 py-1 rounded-pill fw-normal hover-gold"><?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share -->
                    <div class="bg-light rounded-campus p-3 d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4">
                        <span class="font-heading small fw-bold text-campus-navy"><i class="bi bi-share-fill me-2"></i> <?php echo esc_html__( 'BAGIKAN:', 'educampus' ); ?></span>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#1877f2;"><i class="bi bi-facebook"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#000;"><i class="bi bi-twitter-x"></i></a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#25d366;"><i class="bi bi-whatsapp"></i></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#0a66c2;"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Author Box -->
            <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 mt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-campus-navy text-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                            <i class="bi bi-person-fill fs-2 text-campus-gold"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( get_theme_mod( 'educampus_humas_name', 'Hubungan Masyarakat (Humas)' ) ); ?></h4>
                        <p class="text-campus-muted small mb-0"><?php echo esc_html( get_theme_mod( 'educampus_humas_desc', 'Kantor Sekretariat Universitas & Pusat Hubungan Media EduCampus.' ) ); ?></p>
                    </div>
                </div>
            </div>

            <!-- Prev/Next -->
            <nav class="d-flex justify-content-between gap-3 mt-4" aria-label="Posts">
                <?php $prev_post = get_previous_post(); if ( ! empty( $prev_post ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 text-decoration-none flex-fill hover-gold">
                    <span class="text-campus-muted small font-heading text-uppercase fw-bold"><i class="bi bi-chevron-left me-1"></i> Sebelumnya</span>
                    <p class="text-campus-navy small fw-semibold mb-0 mt-1 text-truncate"><?php echo esc_html( $prev_post->post_title ); ?></p>
                </a>
                <?php endif; ?>
                <?php $next_post = get_next_post(); if ( ! empty( $next_post ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 text-decoration-none flex-fill text-end hover-gold">
                    <span class="text-campus-muted small font-heading text-uppercase fw-bold">Berikutnya <i class="bi bi-chevron-right ms-1"></i></span>
                    <p class="text-campus-navy small fw-semibold mb-0 mt-1 text-truncate"><?php echo esc_html( $next_post->post_title ); ?></p>
                </a>
                <?php endif; ?>
            </nav>

            <!-- Related -->
            <div class="mt-5">
                <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-4"><?php echo esc_html__( 'Berita Terkait', 'educampus' ); ?></h3>
                <div class="row g-3">
                    <?php
                    $related_query = new WP_Query( array(
                        'post_type'      => array( 'news', 'post' ),
                        'posts_per_page' => 2,
                        'post__not_in'   => array( $post_id ),
                    ) );
                    if ( $related_query->have_posts() ) :
                        while ( $related_query->have_posts() ) : $related_query->the_post();
                    ?>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-campus-soft rounded-campus bg-white transform-hover d-flex flex-column">
                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="ratio ratio-16x9 overflow-hidden">
                                <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                            </div>
                            <?php endif; ?>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <span class="text-campus-muted small mb-1"><?php echo get_the_date(); ?></span>
                                <h4 class="h6 font-heading fw-bold mb-1">
                                    <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none stretched-link"><?php the_title(); ?></a>
                                </h4>
                                <p class="text-campus-muted small mb-0 flex-grow-1"><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </main>
    </div>
</div>
