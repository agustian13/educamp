<?php
/**
 * Single News — Default Layout (Academic / Corporate Style)
 *
 * @package EduCampus
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_id = get_the_ID();
?>
<!-- News Hero -->
<section class="bg-campus-navy bg-islamic-ornament-half text-white py-5 position-relative overflow-hidden">
    <i class="bi bi-newspaper deco-icon" style="opacity:0.1;font-size:6rem;top:-15px;right:-5px;color:#fff;animation:float-rotate 8s ease-in-out infinite;"></i>
    <div class="container position-relative z-1 py-4">
        <?php educampus_breadcrumbs(); ?>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3 mt-3">
            <span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small">
                <?php
                $terms = get_the_terms( $post_id, 'news_category' );
                if ( empty( $terms ) || is_wp_error( $terms ) ) {
                    $terms = get_the_terms( $post_id, 'category' );
                }
                echo esc_html( ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : esc_html__( 'Berita', 'educampus' ) );
                ?>
            </span>
            <span class="text-white-50 small"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date(); ?></span>
            <span class="text-white-50 small"><i class="bi bi-person-fill me-1"></i> <?php echo esc_html( get_theme_mod( 'educampus_humas_name', 'Humas' ) ); ?></span>
        </div>
        <h1 class="display-6 font-heading fw-bold mb-0" style="max-width:800px;"><?php the_title(); ?></h1>
    </div>
</section>

<div class="container my-5 pb-5 position-relative">
    <div class="row g-4">
        <main id="primary" class="col-lg-8 site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-campus-soft rounded-campus bg-white overflow-hidden' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="ratio ratio-21x9 overflow-hidden">
                    <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                </div>
                <?php endif; ?>

                <div class="card-body p-4 p-md-5 section-reveal">
                    <div class="entry-content mb-4">
                        <?php the_content(); ?>
                    </div>

                    <!-- Share -->
                    <div class="bg-light rounded-campus p-3 d-flex flex-wrap align-items-center justify-content-between gap-2 section-reveal">
                        <span class="font-heading small fw-bold text-campus-navy"><i class="bi bi-share-fill me-2"></i> <?php echo esc_html__( 'BAGIKAN:', 'educampus' ); ?></span>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#1877f2;"><i class="bi bi-facebook"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#000;"><i class="bi bi-twitter-x"></i></a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#25d366;"><i class="bi bi-whatsapp"></i></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:#0a66c2;"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Author Box -->
            <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 mt-4 section-reveal-left">
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
            <nav class="d-flex justify-content-between gap-3 mt-4 section-reveal" aria-label="Posts">
                <?php $prev_post = get_previous_post(); if ( ! empty( $prev_post ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 text-decoration-none flex-fill hover-gold">
                    <span class="text-campus-muted small font-heading text-uppercase fw-bold"><i class="bi bi-chevron-left me-1"></i> <?php echo esc_html__( 'Sebelumnya', 'educampus' ); ?></span>
                    <p class="text-campus-navy small fw-semibold mb-0 mt-1 text-truncate"><?php echo esc_html( $prev_post->post_title ); ?></p>
                </a>
                <?php endif; ?>
                <?php $next_post = get_next_post(); if ( ! empty( $next_post ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 text-decoration-none flex-fill text-end hover-gold">
                    <span class="text-campus-muted small font-heading text-uppercase fw-bold"><?php echo esc_html__( 'Berikutnya', 'educampus' ); ?> <i class="bi bi-chevron-right ms-1"></i></span>
                    <p class="text-campus-navy small fw-semibold mb-0 mt-1 text-truncate"><?php echo esc_html( $next_post->post_title ); ?></p>
                </a>
                <?php endif; ?>
            </nav>

            <!-- Related -->
            <div class="mt-5 section-reveal">
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
                    else :
                    ?>
                    <div class="col-12">
                        <p class="text-campus-muted small mb-0"><?php echo esc_html__( 'Belum ada berita terkait.', 'educampus' ); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <aside id="secondary" class="col-lg-4 mt-4 mt-lg-0 widget-area">
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white section-reveal-right">
                <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-3"><i class="bi bi-search me-1"></i> <?php echo esc_html__( 'Cari Berita', 'educampus' ); ?></h3>
                <form role="search" method="get" class="d-flex gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="hidden" name="post_type" value="post" />
                    <input type="search" class="form-control rounded-3 border-light shadow-none bg-campus-light px-3" placeholder="<?php echo esc_attr__( 'Kata kunci...', 'educampus' ); ?>" name="s" required />
                    <button type="submit" class="btn btn-campus-primary px-3 rounded-3"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white section-reveal-right">
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

            <div class="card border-0 bg-campus-navy text-white shadow-campus-soft rounded-campus p-4 text-center position-relative overflow-hidden section-reveal-right">
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
        </aside>
    </div>
</div>
