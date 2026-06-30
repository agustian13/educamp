<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_id = get_the_ID();
$reading_time = ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ?: 1;
$terms = get_the_terms( $post_id, 'category' );
$cat_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : 'Berita';
?>
<div class="bg-white">
    <div style="background:linear-gradient(135deg, #0a2540 0%, #163c63 100%);">
        <div class="container py-4">
            <?php educampus_breadcrumbs(); ?>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2 mt-2">
                        <span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small"><?php echo esc_html( $cat_name ); ?></span>
                        <span class="text-white-50 small"><i class="bi bi-clock me-1"></i> <?php echo $reading_time; ?> <?php echo esc_html__( 'menit', 'educampus' ); ?></span>
                        <span class="text-white-50 small"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date( 'd F Y' ); ?></span>
                    </div>
                    <h1 class="h2 fw-bold text-white mb-2 lh-sm"><?php the_title(); ?></h1>
                    <?php if ( has_excerpt() ) : ?>
                    <p class="text-white-50 mb-0" style="max-width:700px;"><?php echo get_the_excerpt(); ?></p>
                    <?php endif; ?>
                    <div class="d-flex align-items-center gap-3 mt-3 pt-3 border-top border-white border-opacity-10">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author(), array( 'class' => 'rounded-circle border border-2 border-white border-opacity-25' ) ); ?>
                        <div>
                            <p class="text-white small fw-semibold mb-0"><?php the_author(); ?></p>
                            <p class="text-white-50 small mb-0" style="font-size:0.75rem;"><?php echo esc_html( get_theme_mod( 'educampus_single_news_author_role', esc_html__( 'Redaksi & Tim Publikasi', 'educampus' ) ) ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="rounded-3 overflow-hidden shadow-campus-soft mb-4">
                    <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid w-100', 'style' => 'max-height:400px;object-fit:cover;' ) ); ?>
                </div>
                <?php endif; ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content lh-base" style="font-size:1rem;">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    $tags = get_the_tags( $post_id );
                    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-4 pt-3 border-top">
                        <span class="small fw-bold text-campus-muted font-heading me-1"><i class="bi bi-tags me-1"></i> <?php echo esc_html__( 'TAGS:', 'educampus' ); ?></span>
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge bg-light text-campus-navy text-decoration-none px-2 py-1 rounded-pill fw-normal hover-gold"><?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                        <span class="small fw-bold text-campus-muted font-heading me-1"><?php echo esc_html__( 'BAGIKAN:', 'educampus' ); ?></span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#1877f2;color:#fff;font-size:0.75rem;"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#000;color:#fff;font-size:0.75rem;"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:#25d366;color:#fff;font-size:0.75rem;"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </article>

                <nav class="d-flex justify-content-between gap-3 mt-4" aria-label="Posts">
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

    <div class="bg-campus-light py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-campus-gold"></i> <?php echo esc_html__( 'Berita Lainnya', 'educampus' ); ?>
                    </h3>
                    <div class="row g-3">
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
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white transform-hover">
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
                        <div class="col-12 text-center py-3">
                            <p class="text-campus-muted small mb-0"><?php echo esc_html__( 'Belum ada berita lainnya.', 'educampus' ); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
