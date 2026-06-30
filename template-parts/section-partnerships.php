<!-- Section 9: Partnerships -->
<?php if ( get_theme_mod( 'educampus_show_partnerships', true ) ) : ?>
<?php 
$partner_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_partnerships', true ) ) {
    $partner_ornament = ' bg-islamic-ornament';
}
$partner_bg_color = get_theme_mod( 'educampus_bg_color_partnerships', '#ffffff' );
$partner_bg_color_end = get_theme_mod( 'educampus_bg_color_end_partnerships', $partner_bg_color );
$partner_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_partnerships', '135deg' );
?>
<section id="partnerships" class="section-padding border-top border-bottom border-light position-relative overflow-hidden section-partnerships<?php echo esc_attr($partner_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($partner_bg_grad_dir); ?>, <?php echo esc_attr($partner_bg_color); ?>, <?php echo esc_attr($partner_bg_color_end); ?>);">
    <i class="bi bi-globe2 deco-icon" style="opacity:0.04;font-size:8rem;top:-20px;right:-30px;animation:float-rotate 12s ease-in-out infinite;"></i>
    <i class="bi bi-handshake deco-icon" style="opacity:0.03;font-size:6rem;bottom:-15px;left:-20px;animation:float-bounce 10s ease-in-out infinite;"></i>

    <div class="container text-center position-relative z-1">
        <div class="text-center mb-5 max-w-600 mx-auto section-reveal">
            <span class="text-gradient font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_partnerships_badge', 'KERJASAMA STRATEGIS' ) ); ?></span>
            <h2 class="h1 font-heading fw-bold text-gradient-navy"><?php echo esc_html( get_theme_mod( 'educampus_heading_partnerships_title', 'Kemitraan & Kolaborasi Global' ) ); ?></h2>
            <?php 
            $partner_desc = get_theme_mod( 'educampus_heading_partnerships_desc', '' );
            if ( ! empty( $partner_desc ) ) : ?>
                <p class="text-campus-muted"><?php echo esc_html( $partner_desc ); ?></p>
            <?php endif; ?>
        </div>

        <?php
        $partners = new WP_Query( array(
            'post_type'      => 'partnership',
            'posts_per_page' => 10,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ) );
        if ( $partners->have_posts() ) :
            $partner_items = array();
            while ( $partners->have_posts() ) : $partners->the_post();
                $partner_url = get_post_meta( get_the_ID(), '_partnership_url', true );
                $thumb = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'img-fluid', 'style' => 'max-height:60px;width:auto;', 'loading' => 'lazy' ) ) : '';
                $title = get_the_title();
                $partner_items[] = array( 'url' => $partner_url, 'thumb' => $thumb, 'title' => $title );
            endwhile;
            wp_reset_postdata();

            // Duplicate items for seamless scrolling
            $partner_items = array_merge( $partner_items, $partner_items );
        ?>

        <div class="partnership-track-wrapper section-reveal" style="overflow:hidden;width:100%;">
            <div class="partnership-track d-flex align-items-center gap-5" style="width:max-content;animation:partnershipScroll 30s linear infinite;">
                <?php foreach ( $partner_items as $item ) : ?>
                    <div class="partnership-item flex-shrink-0 text-center transform-hover" style="opacity:0.7;transition:opacity 0.3s ease,transform 0.3s ease;">
                        <?php if ( ! empty( $item['thumb'] ) ) : ?>
                            <a href="<?php echo esc_url( $item['url'] ?: '#' ); ?>" target="_blank" rel="noopener" class="d-block text-decoration-none">
                                <?php echo $item['thumb']; ?>
                            </a>
                        <?php else : ?>
                            <span class="d-block fw-bold text-campus-navy font-heading small px-3 py-2 bg-white bg-opacity-10 rounded-3"><?php echo esc_html( $item['title'] ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php else : ?>
            <div class="col-12 text-campus-muted small"><?php esc_html_e('Belum ada data mitra. Tambahkan di Admin → Kemitraan.', 'educampus'); ?></div>
        <?php endif; ?>
    </div>
</section>

<style>
@keyframes partnershipScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.partnership-track-wrapper:hover .partnership-track {
    animation-play-state: paused;
}
.partnership-item:hover {
    opacity: 1 !important;
    transform: scale(1.1);
}
.section-partnerships::after {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(135deg, rgba(197,160,89,0.04) 0%, transparent 50%, rgba(10,37,64,0.03) 100%);
    z-index: 0;
}
</style>
<?php endif; ?>
