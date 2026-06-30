<?php
/**
 * The template for displaying single posts (standard WordPress posts)
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container my-5 pb-5">
    <!-- Breadcrumbs -->
    <?php educampus_breadcrumbs(); ?>

    <div class="row g-4 mt-2">
        <!-- Main Content (8 Columns) -->
        <main id="primary" class="col-lg-8 site-main">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $post_id = get_the_ID();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-campus-soft rounded-campus bg-white p-4 p-md-5' ); ?>>
                    <!-- Meta Info & Category -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) :
                        ?>
                            <span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-1 rounded-pill small">
                                <?php echo esc_html( $categories[0]->name ); ?>
                            </span>
                        <?php endif; ?>
                        <span class="text-campus-muted small"><i class="bi bi-calendar-event me-1"></i> <?php echo get_the_date(); ?></span>
                        <span class="text-campus-muted small ms-2"><i class="bi bi-person-fill me-1"></i> <?php the_author(); ?></span>
                    </div>

                    <!-- Title -->
                    <h1 class="display-6 font-heading fw-bold text-campus-navy mb-4 lh-sm"><?php the_title(); ?></h1>

                    <!-- Featured Image -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="ratio ratio-21x9 overflow-hidden rounded-campus shadow-campus-soft mb-4">
                            <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="entry-content font-body text-campus-navy mb-5" style="line-height: 1.8; font-size: 1.05rem;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if ( ! empty( $tags ) ) :
                    ?>
                        <div class="mb-4">
                            <i class="bi bi-tags-fill text-campus-gold me-1"></i>
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge bg-light text-campus-navy text-decoration-none me-1 mb-1 px-2 py-1 rounded-pill small"><?php echo esc_html( $tag->name ); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="border-top border-bottom py-3 my-4 d-flex align-items-center justify-content-between">
                        <span class="font-heading small fw-bold text-campus-navy"><i class="bi bi-share-fill me-2"></i> <?php esc_html_e( 'BAGIKAN ARTIKEL:', 'educampus' ); ?></span>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-3"><i class="bi bi-facebook"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm rounded-3"><i class="bi bi-twitter-x"></i> Twitter</a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm rounded-3"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        </div>
                    </div>

                    <!-- Author Box -->
                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 mb-5">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 65, '', '', array( 'class' => 'rounded-circle' ) ); ?>
                            </div>
                            <div class="col">
                                <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_author(); ?></h4>
                                <p class="text-campus-muted small mb-0"><?php echo esc_html( get_the_author_meta( 'description' ) ?: esc_html__( 'Penulis di EduCampus', 'educampus' ) ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Previous / Next Navigation -->
                    <nav class="navigation post-navigation border-top pt-4 mb-5" aria-label="Posts">
                        <div class="d-flex justify-content-between">
                            <div class="nav-previous w-50 pe-3 border-end">
                                <?php
                                $prev_post = get_previous_post();
                                if ( ! empty( $prev_post ) ) :
                                ?>
                                    <span class="text-campus-muted small font-heading text-uppercase d-block mb-1"><?php esc_html_e( 'Sebelumnya', 'educampus' ); ?></span>
                                    <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-campus-navy text-decoration-none font-heading fw-bold small text-truncate d-inline-block mw-100">
                                        <i class="bi bi-chevron-left me-1"></i> <?php echo esc_html( $prev_post->post_title ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="nav-next w-50 ps-3 text-end">
                                <?php
                                $next_post = get_next_post();
                                if ( ! empty( $next_post ) ) :
                                ?>
                                    <span class="text-campus-muted small font-heading text-uppercase d-block mb-1"><?php esc_html_e( 'Berikutnya', 'educampus' ); ?></span>
                                    <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-campus-navy text-decoration-none font-heading fw-bold small text-truncate d-inline-block mw-100">
                                        <?php echo esc_html( $next_post->post_title ); ?> <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>

                    <!-- Related Posts -->
                    <div class="related-posts border-top pt-5">
                        <h3 class="h4 font-heading fw-bold text-campus-navy mb-4"><?php esc_html_e( 'Artikel Terkait', 'educampus' ); ?></h3>
                        <div class="row g-4">
                            <?php
                            $current_cats = wp_get_post_categories( $post_id );
                            $related_query = new WP_Query( array(
                                'post_type'      => 'post',
                                'posts_per_page' => 2,
                                'post__not_in'   => array( $post_id ),
                                'category__in'   => $current_cats,
                            ) );

                            if ( ! $related_query->have_posts() ) {
                                // Fallback: get any recent posts if no same-category posts
                                $related_query = new WP_Query( array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 2,
                                    'post__not_in'   => array( $post_id ),
                                ) );
                            }

                            if ( $related_query->have_posts() ) :
                                while ( $related_query->have_posts() ) : $related_query->the_post();
                            ?>
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover d-flex flex-column">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="ratio ratio-16x9">
                                                    <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body p-4 flex-grow-1">
                                                <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo get_the_date(); ?></span>
                                                <h4 class="h6 font-heading fw-bold text-campus-navy mb-2">
                                                    <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                                <p class="text-campus-muted small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 12 ); ?></p>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                            ?>
                                <div class="col-12">
                                    <p class="text-campus-muted small"><?php esc_html_e( 'Belum ada artikel terkait.', 'educampus' ); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </article>
            <?php
                endwhile;
            endif;
            ?>
        </main>

        <!-- Sidebar Area (4 Columns) -->
        <aside id="secondary" class="col-lg-4 mt-5 mt-lg-0 widget-area">
            <!-- Search Widget -->
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Cari Informasi', 'educampus' ); ?></h3>
                <?php get_search_form(); ?>
            </div>

            <!-- Categories Widget -->
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Kategori', 'educampus' ); ?></h3>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    <?php
                    wp_list_categories( array(
                        'title_li'   => '',
                        'show_count' => true,
                        'style'      => 'list',
                    ) );
                    ?>
                </ul>
            </div>

            <!-- Recent Posts Widget -->
            <div class="card border-0 shadow-campus-soft rounded-campus p-4 mb-4 bg-white">
                <h3 class="h5 border-bottom pb-2 text-campus-navy mb-3"><?php esc_html_e( 'Berita Terbaru', 'educampus' ); ?></h3>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <?php
                    $recent_posts = new WP_Query( array(
                        'post_type'      => 'post',
                        'posts_per_page' => 5,
                        'post__not_in'   => array( get_the_ID() ),
                    ) );
                    if ( $recent_posts->have_posts() ) :
                        while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
                    ?>
                        <li class="d-flex gap-3 align-items-start">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="flex-shrink-0" style="width: 60px; height: 60px;">
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-fluid rounded-campus object-fit-cover w-100 h-100' ) ); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none font-heading fw-bold small d-block mb-1 hover-secondary"><?php the_title(); ?></a>
                                <span class="text-campus-muted" style="font-size: 0.75rem;"><i class="bi bi-calendar-event me-1"></i><?php echo get_the_date(); ?></span>
                            </div>
                        </li>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>
            
            <!-- Information Call Out Widget (PMB oriented) -->
            <div class="card border-0 bg-campus-navy text-white shadow-campus-soft rounded-campus p-4 text-center position-relative overflow-hidden">
                <h4 class="h5 font-heading text-campus-gold fw-bold mb-3 position-relative z-1"><?php esc_html_e( 'Admisi Pendaftaran', 'educampus' ); ?></h4>
                <p class="small text-white-50 mb-4 position-relative z-1"><?php esc_html_e( 'Ada pertanyaan mengenai tata cara pendaftaran, syarat jalur prestasi, atau struktur biaya kuliah?', 'educampus' ); ?></p>
                <div class="d-grid gap-2 position-relative z-1">
                    <a href="<?php echo esc_url( home_url( '/pmb' ) ); ?>" class="btn btn-campus-secondary btn-sm py-2">
                        <?php esc_html_e( 'Brosur Digital PMB', 'educampus' ); ?>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm py-2">
                        <i class="bi bi-whatsapp text-success me-1"></i> <?php esc_html_e( 'Kontak via WhatsApp', 'educampus' ); ?>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php
get_footer();
