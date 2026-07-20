<!-- Section 11: Gallery -->
<?php if ( get_theme_mod( 'educampus_show_gallery', true ) ) : ?>
<?php 
$gallery_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_gallery', true ) ) {
    $gallery_ornament = ' bg-islamic-ornament';
}
$gallery_bg_color = get_theme_mod( 'educampus_bg_color_gallery', '#f8f6f0' );
$gallery_bg_color_end = get_theme_mod( 'educampus_bg_color_end_gallery', $gallery_bg_color );
$gallery_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_gallery', '135deg' );

$gallery_query = new WP_Query( array(
    'post_type'      => 'gallery',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );
?>
<section id="gallery" class="section-padding<?php echo esc_attr($gallery_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($gallery_bg_grad_dir); ?>, <?php echo esc_attr($gallery_bg_color); ?>, <?php echo esc_attr($gallery_bg_color_end); ?>);">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_gallery_badge', 'GALERI KAMPUS' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_gallery_title', 'Kehidupan Kampus EduCampus' ) ); ?></h2>
            <?php 
            $gallery_desc = get_theme_mod( 'educampus_heading_gallery_desc', '' );
            if ( ! empty( $gallery_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $gallery_desc ); ?></p>
            <?php endif; ?>
        </div>

        <?php if ( $gallery_query->have_posts() ) : ?>
        <div class="row g-3">
            <?php while ( $gallery_query->have_posts() ) : $gallery_query->the_post();
                $thumb_id  = get_post_thumbnail_id();
                $img_url   = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=400&auto=format&fit=crop';
                $img_alt   = get_the_title();
                $img_desc  = get_the_excerpt();
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="ratio ratio-4x3 overflow-hidden rounded-campus shadow-campus-soft position-relative group-hover-overlay">
                    <img loading="lazy" src="<?php echo esc_url( $img_url ); ?>" class="img-fluid object-fit-cover transform-scale" alt="<?php echo esc_attr( $img_alt ); ?>" width="768" height="576">
                    <?php if ( ! empty( $img_desc ) ) : ?>
                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <p class="text-white small mb-0"><?php echo esc_html( $img_desc ); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-image fs-1 d-block mb-3"></i>
            <p>Belum ada foto galeri. Silakan tambahkan melalui menu <strong>Galeri</strong> di admin.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
