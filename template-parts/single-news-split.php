<?php
/**
 * Single News — Split / Side-by-Side Layout
 *
 * @package EduCampus
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_id = get_the_ID();
$reading_time = ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ?: 1;

$terms = get_the_terms( $post_id, 'news_category' );
if ( empty( $terms ) || is_wp_error( $terms ) ) {
    $terms = get_the_terms( $post_id, 'category' );
}
$cat_name  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : 'Berita';
$cat_color = ! empty( $terms ) && ! is_wp_error( $terms ) ? get_term_meta( $terms[0]->term_id, 'category_color', true ) : '';
?>
<!-- Split Hero Bar -->
<div class="bg-campus-navy text-white">
    <div class="container py-3">
        <?php educampus_breadcrumbs(); ?>
    </div>
</div>

<div class="bg-white">
    <div class="container py-4">
        <div class="row g-4">

            <!-- LEFT: Featured Image (Sticky) -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="split-sticky-wrapper" style="position:sticky;top:100px;">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="rounded-4 overflow-hidden shadow-campus-soft">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'img-fluid w-100', 'style' => 'max-height:500px;object-fit:cover;' ) ); ?>
                    </div>
                    <?php else : ?>
                    <div class="rounded-4 overflow-hidden shadow-campus-soft bg-campus-navy d-flex align-items-center justify-content-center" style="height:400px;">
                        <i class="bi bi-newspaper text-white-50" style="font-size:4rem;"></i>
                    </div>
                    <?php endif; ?>

                    <!-- Meta Info Card Below Image -->
                    <div class="mt-3 p-3 bg-campus-light rounded-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author(), array( 'class' => 'rounded-circle' ) ); ?>
                            <div>
                                <p class="small fw-bold text-campus-navy mb-0"><?php the_author(); ?></p>
                                <p class="small text-campus-muted mb-0" style="font-size:0.7rem;"><?php echo esc_html( get_theme_mod( 'educampus_single_news_author_role', esc_html__( 'Redaksi & Tim Publikasi', 'educampus' ) ) ); ?></p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex flex-wrap gap-3 small text-campus-muted">
                            <span><i class="bi bi-calendar-event me-1 text-campus-gold"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
                            <span><i class="bi bi-clock me-1 text-campus-gold"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Content -->
            <div class="col-lg-7">
                <!-- Mobile Meta (visible below lg) -->
                <div class="d-lg-none mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small" style="background:<?php echo $cat_color ?: '#c5a059'; ?>;color:#0a2540;">
                            <?php echo esc_html( $cat_name ); ?>
                        </span>
                        <span class="small text-campus-muted"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
                        <span class="small text-campus-muted"><i class="bi bi-clock me-1"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
                    </div>
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="rounded-3 overflow-hidden mt-3">
                        <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid w-100', 'style' => 'max-height:300px;object-fit:cover;' ) ); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Category + Title -->
                <div class="mb-4">
                    <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small d-none d-lg-inline-block" style="background:<?php echo $cat_color ?: '#c5a059'; ?>;color:#0a2540;">
                        <?php echo esc_html( $cat_name ); ?>
                    </span>
                    <h1 class="h2 fw-bold text-campus-navy mt-2 mb-0 lh-sm"><?php the_title(); ?></h1>
                </div>

                <!-- Article Content -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content lh-base" style="font-size:1rem;">
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

                    <!-- Share Bar -->
                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <span class="small fw-bold text-campus-muted font-heading me-1">BAGIKAN:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#1877f2;color:#fff;font-size:0.75rem;"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#000;color:#fff;font-size:0.75rem;"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#25d366;color:#fff;font-size:0.75rem;"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#0a66c2;color:#fff;font-size:0.75rem;"><i class="bi bi-linkedin"></i></a>
                    </div>
                </article>

                <!-- Prev / Next -->
                <nav class="d-flex justify-content-between gap-3 mt-5" aria-label="Posts">
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
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Related News: Horizontal Scroll -->
<div class="bg-campus-light py-5">
    <div class="container">
        <h3 class="h5 font-heading fw-bold text-campus-navy mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-grid-3x3-gap-fill text-campus-gold"></i> Berita Lainnya
        </h3>
        <div class="row g-3">
            <?php
            $related_query = new WP_Query( array(
                'post_type'      => array( 'news', 'post' ),
                'posts_per_page' => 4,
                'post__not_in'   => array( $post_id ),
            ) );
            if ( $related_query->have_posts() ) :
                while ( $related_query->have_posts() ) : $related_query->the_post();
            ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white transform-hover">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <div class="ratio ratio-16x9 overflow-hidden">
                            <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                        </div>
                        <?php endif; ?>
                        <div class="card-body p-3">
                            <span class="small text-campus-gold fw-semibold font-heading"><?php echo get_the_date( 'd F Y' ); ?></span>
                            <h4 class="h6 font-heading fw-bold text-campus-navy mt-1 mb-0"><?php the_title(); ?></h4>
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
