<?php
/**
 * Single News — Magazine / Cover Story Layout
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
$cat_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : 'Berita';
$cat_color = ! empty( $terms ) && ! is_wp_error( $terms ) ? get_term_meta( $terms[0]->term_id, 'category_color', true ) : '';

$featured_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'full' ) : '';
?>
<!-- Magazine Hero: Featured Image as Cover Background -->
<section class="position-relative overflow-hidden" style="min-height:75vh;background:#0a2540;">
    <?php if ( $featured_url ) : ?>
    <div class="position-absolute inset-0 w-100 h-100" style="background:url('<?php echo esc_url( $featured_url ); ?>') center center / cover no-repeat;"></div>
    <?php endif; ?>
    <div class="position-absolute inset-0 w-100 h-100" style="background:linear-gradient(180deg, rgba(10,37,64,0.15) 0%, rgba(10,37,64,0.85) 70%, #0a2540 100%);"></div>

    <div class="container position-relative z-1 h-100 d-flex flex-column justify-content-end pb-5" style="min-height:75vh;">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3 mt-auto pt-5">
            <span class="badge font-heading fw-bold px-2 py-1 rounded-pill small" style="background:<?php echo $cat_color ?: '#c5a059'; ?>;color:#0a2540;">
                <?php echo esc_html( $cat_name ); ?>
            </span>
            <span class="text-white-50 small"><i class="bi bi-clock me-1"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit baca', 'educampus' ); ?></span>
            <span class="text-white-50 small"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" style="max-width:900px;text-shadow:0 2px 20px rgba(0,0,0,0.3);"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
        <p class="lead text-white-50 mb-0" style="max-width:700px;"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Breadcrumb Bar (separate from hero) -->
<div class="border-bottom bg-white">
    <div class="container py-2 small">
        <?php educampus_breadcrumbs(); ?>
    </div>
</div>

<!-- Sticky Floating Share Buttons (Left) -->
<div class="d-none d-xl-block position-fixed start-0 top-50 translate-middle-y z-3" style="margin-left:24px;">
    <div class="d-flex flex-column gap-2 bg-white shadow-sm rounded-3 p-2 border">
        <span class="text-uppercase small fw-bold text-campus-muted text-center font-heading" style="font-size:9px;letter-spacing:1px;"><?php echo esc_html__( 'Bagikan', 'educampus' ); ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#1877f2;background:transparent;border:1px solid #eee;" onmouseover="this.style.background='#1877f2';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#1877f2';"><i class="bi bi-facebook"></i></a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#000;background:transparent;border:1px solid #eee;" onmouseover="this.style.background='#000';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#000';"><i class="bi bi-twitter-x"></i></a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#25d366;background:transparent;border:1px solid #eee;" onmouseover="this.style.background='#25d366';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#25d366';"><i class="bi bi-whatsapp"></i></a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#0a66c2;background:transparent;border:1px solid #eee;" onmouseover="this.style.background='#0a66c2';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#0a66c2';"><i class="bi bi-linkedin"></i></a>
        <button onclick="window.print()" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#666;background:transparent;border:1px solid #eee;" onmouseover="this.style.background='#666';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#666';"><i class="bi bi-printer"></i></button>
    </div>
</div>

<!-- Magazine Content Area (Full Width, No Sidebar) -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white' ); ?>>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                <!-- Bottom Share Bar (Mobile) -->
                <div class="d-flex d-xl-none align-items-center gap-2 mb-4 pb-3 border-bottom">
                    <span class="small fw-bold text-campus-muted font-heading me-2"><?php echo esc_html__( 'BAGIKAN:', 'educampus' ); ?></span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#1877f2;color:#fff;"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#000;color:#fff;"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#25d366;color:#fff;"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#0a66c2;color:#fff;"><i class="bi bi-linkedin"></i></a>
                </div>

                <!-- Entry Content with Drop Cap -->
                <div class="entry-content entry-content-magazine">
                    <?php the_content(); ?>
                </div>

                <!-- Tags -->
                <?php
                $tags = get_the_tags( $post_id );
                if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-5 pt-4 border-top">
                    <span class="small fw-bold text-campus-muted font-heading me-1"><i class="bi bi-tags me-1"></i> <?php echo esc_html__( 'TAGS:', 'educampus' ); ?></span>
                    <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge bg-light text-campus-navy text-decoration-none px-3 py-2 rounded-pill fw-normal hover-gold"><?php echo esc_html( $tag->name ); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Author Bio -->
                <div class="d-flex align-items-start gap-3 mt-5 p-4 bg-campus-light rounded-4 section-reveal">
                    <div class="flex-shrink-0">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', get_the_author(), array( 'class' => 'rounded-circle border border-2 border-white shadow-sm' ) ); ?>
                    </div>
                    <div>
                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_author(); ?></h4>
                        <p class="small text-campus-muted mb-2"><?php echo esc_html( get_theme_mod( 'educampus_single_news_author_role', esc_html__( 'Redaksi & Tim Publikasi', 'educampus' ) ) ); ?></p>
                        <p class="small text-campus-muted mb-0"><?php echo esc_html( get_theme_mod( 'educampus_single_news_author_note', esc_html__( 'Artikel ini diterbitkan oleh tim redaksi. Informasi lebih lanjut dapat menghubungi kontak resmi.', 'educampus' ) ) ); ?></p>
                    </div>
                </div>

                <!-- Prev / Next -->
                <nav class="d-flex justify-content-between gap-3 mt-5 section-reveal" aria-label="Posts">
                    <?php $prev_post = get_previous_post(); if ( ! empty( $prev_post ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-decoration-none flex-fill p-3 border rounded-3 hover-border-gold transition-all">
                        <span class="small font-heading fw-bold text-campus-muted text-uppercase"><i class="bi bi-arrow-left me-1"></i> <?php echo esc_html__( 'Sebelumnya', 'educampus' ); ?></span>
                        <p class="small fw-semibold text-campus-navy mb-0 mt-1"><?php echo esc_html( $prev_post->post_title ); ?></p>
                    </a>
                    <?php endif; ?>
                    <?php $next_post = get_next_post(); if ( ! empty( $next_post ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-decoration-none flex-fill p-3 border rounded-3 text-end hover-border-gold transition-all">
                        <span class="small font-heading fw-bold text-campus-muted text-uppercase"><?php echo esc_html__( 'Berikutnya', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1"></i></span>
                        <p class="small fw-semibold text-campus-navy mb-0 mt-1"><?php echo esc_html( $next_post->post_title ); ?></p>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Related Posts (Full-width, 3 Columns) -->
    <div class="bg-campus-light py-5 mt-3 section-reveal">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-campus-gold"></i> <?php echo esc_html__( 'Berita Lainnya', 'educampus' ); ?>
                    </h3>
                    <div class="row g-4">
                        <?php
                        $related_query = new WP_Query( array(
                            'post_type'      => array( 'news', 'post' ),
                            'posts_per_page' => 3,
                            'post__not_in'   => array( $post_id ),
                        ) );
                        if ( $related_query->have_posts() ) :
                            while ( $related_query->have_posts() ) : $related_query->the_post();
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white transform-hover">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="ratio ratio-16x10 overflow-hidden">
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
                            <p class="text-campus-muted small mb-0"><?php echo esc_html__( 'Belum ada berita lainnya.', 'educampus' ); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
