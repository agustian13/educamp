<?php
/**
 * Single News — Bold / Immersive Layout
 * Full-width hero, large typography, TOC sidebar, masonry related posts
 *
 * @package EduCampus
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_id       = get_the_ID();
$reading_time  = ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ?: 1;

$terms = get_the_terms( $post_id, 'news_category' );
if ( empty( $terms ) || is_wp_error( $terms ) ) {
    $terms = get_the_terms( $post_id, 'category' );
}
$cat_name  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : 'Berita';
$cat_color = ! empty( $terms ) && ! is_wp_error( $terms ) ? get_term_meta( $terms[0]->term_id, 'category_color', true ) : '';

// Extract headings for TOC
$content_raw   = get_the_content();
preg_match_all( '/<h([2-4])[^>]*>(.*?)<\/h[2-4]>/is', $content_raw, $toc_matches, PREG_SET_ORDER );
?>
<!-- Full-Width Hero -->
<div class="position-relative" style="min-height:420px;margin-top:-16px;">
    <?php if ( has_post_thumbnail() ) : ?>
    <?php the_post_thumbnail( 'full', array(
        'class' => 'w-100',
        'style' => 'height:420px;object-fit:cover;filter:brightness(0.35);',
    ) ); ?>
    <?php else : ?>
    <div class="w-100 bg-campus-navy" style="height:420px;"></div>
    <?php endif; ?>

    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end" style="background:linear-gradient(transparent 0%,rgba(10,37,64,0.85) 100%);">
        <div class="container py-5 position-relative" style="z-index:2;">
            <?php educampus_breadcrumbs( 'text-white-50', 'text-white-50 opacity-75' ); ?>

            <div class="mt-3">
                <span class="badge font-heading fw-bold px-3 py-1 rounded-pill mb-3" style="background:<?php echo $cat_color ?: '#c5a059'; ?>;color:#0a2540;font-size:0.75rem;">
                    <?php echo esc_html( $cat_name ); ?>
                </span>
                <h1 class="display-5 fw-bold text-white lh-1 mb-3" style="max-width:820px;font-size:clamp(1.75rem,4vw,2.75rem);">
                    <?php the_title(); ?>
                </h1>
                <div class="d-flex flex-wrap align-items-center gap-3 text-white-75 small">
                    <span class="d-flex align-items-center gap-1">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', get_the_author(), array( 'class' => 'rounded-circle border border-white', 'style' => 'border-width:2px !important;' ) ); ?>
                        <span><?php the_author(); ?></span>
                    </span>
                    <span><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
                    <span><i class="bi bi-clock me-1"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content + TOC Sidebar -->
<div class="bg-white">
    <div class="container py-5">
        <div class="row g-5">

            <!-- Main Content -->
            <div class="col-lg-8">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content" style="font-size:1.1rem;line-height:1.85;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags( $post_id );
                    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-5 pt-4 border-top border-light">
                        <span class="small fw-bold text-campus-muted font-heading me-1"><i class="bi bi-tags me-1"></i> TAGS:</span>
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge bg-light text-campus-navy text-decoration-none px-3 py-1 rounded-pill fw-normal hover-gold"><?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share Bar -->
                    <div class="d-flex align-items-center gap-2 mt-4 pt-4 border-top border-light">
                        <span class="small fw-bold text-campus-muted font-heading me-1">BAGIKAN:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#1877f2;color:#fff;font-size:0.8rem;"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#000;color:#fff;font-size:0.8rem;"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#25d366;color:#fff;font-size:0.8rem;"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#0a66c2;color:#fff;font-size:0.8rem;"><i class="bi bi-linkedin"></i></a>
                    </div>

                    <!-- Prev / Next -->
                    <div class="d-flex justify-content-between gap-3 mt-5 pt-4 border-top border-light">
                        <?php $prev_post = get_previous_post(); if ( ! empty( $prev_post ) ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-decoration-none flex-fill p-3 border rounded-3 hover-border-gold transition-all">
                            <span class="small font-heading fw-bold text-campus-muted text-uppercase"><i class="bi bi-arrow-left me-1"></i> Sebelumnya</span>
                            <p class="small fw-semibold text-campus-navy mb-0 mt-1"><?php echo esc_html( $prev_post->post_title ); ?></p>
                        </a>
                        <?php endif; ?>
                        <?php $next_post = get_next_post(); if ( ! empty( $next_post ) ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-decoration-none flex-fill p-3 border rounded-3 text-end hover-border-gold transition-all">
                            <span class="small font-heading fw-bold text-campus-muted text-uppercase">Berikutnya <i class="bi bi-arrow-right ms-1"></i></span>
                            <p class="small fw-semibold text-campus-navy mb-0 mt-1"><?php echo esc_html( $next_post->post_title ); ?></p>
                        </a>
                        <?php endif; ?>
                    </div>
                </article>
            </div>

            <!-- TOC Sidebar -->
            <div class="col-lg-4 d-none d-lg-block">
                <div style="position:sticky;top:100px;">
                    <?php if ( count( $toc_matches ) >= 2 ) : ?>
                    <div class="p-4 bg-campus-light rounded-3 border border-light">
                        <h6 class="font-heading fw-bold text-campus-navy mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-list-nested text-campus-gold"></i> Daftar Isi
                        </h6>
                        <nav>
                            <ol class="list-unstyled m-0" style="counter-reset:none;">
                                <?php foreach ( $toc_matches as $match ) :
                                    $level = (int) $match[1];
                                    $heading_text = wp_strip_all_tags( $match[2] );
                                    $slug = sanitize_title( $heading_text );
                                ?>
                                <li class="mb-1" style="padding-left:<?php echo ( $level - 2 ) * 16; ?>px;">
                                    <a href="#<?php echo esc_attr( $slug ); ?>" class="text-decoration-none small text-campus-muted d-block py-1 border-start border-2 ps-2 hover-gold hover-border-gold transition-all" style="border-color:transparent;">
                                        <?php echo esc_html( $heading_text ); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    </div>
                    <?php endif; ?>

                    <!-- Share Card -->
                    <div class="p-4 bg-campus-light rounded-3 border border-light mt-3">
                        <h6 class="font-heading fw-bold text-campus-navy mb-2">Bagikan Artikel</h6>
                        <p class="small text-campus-muted mb-3">Dukung kami dengan membagikan artikel ini.</p>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-3 flex-fill fw-bold" style="background:#1877f2;color:#fff;"><i class="bi bi-facebook me-1"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-3 flex-fill fw-bold" style="background:#000;color:#fff;"><i class="bi bi-twitter-x me-1"></i> X</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related News: Masonry Grid -->
<div class="bg-campus-light py-5">
    <div class="container">
        <h3 class="h5 font-heading fw-bold text-campus-navy mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-grid-3x3-gap-fill text-campus-gold"></i> Berita Lainnya
        </h3>
        <div class="row g-4" style="columns:3;column-gap:1rem;">
            <?php
            $related_query = new WP_Query( array(
                'post_type'      => array( 'news', 'post' ),
                'posts_per_page' => 6,
                'post__not_in'   => array( $post_id ),
            ) );
            if ( $related_query->have_posts() ) :
                $r = 0;
                while ( $related_query->have_posts() ) : $related_query->the_post();
                    $r++;
                    $height = ( $r % 3 === 0 ) ? '400px' : ( ( $r % 2 === 0 ) ? '320px' : '280px' );
            ?>
            <div class="mb-4" style="break-inside:avoid;">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white transform-hover">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <div style="height:<?php echo $height; ?>;overflow:hidden;">
                            <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid w-100 h-100', 'style' => 'object-fit:cover;' ) ); ?>
                        </div>
                        <?php endif; ?>
                        <div class="card-body p-3">
                            <span class="small text-campus-gold fw-semibold font-heading"><?php echo get_the_date( 'd F Y' ); ?></span>
                            <h4 class="h6 font-heading fw-bold text-campus-navy mt-1 mb-0"><?php the_title(); ?></h4>
                            <p class="small text-campus-muted mb-0 mt-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="col-12 text-center py-4">
                <p class="text-campus-muted small mb-0">Belum ada berita lainnya.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
