<!-- Section 3: Rector Welcome -->
<?php if ( get_theme_mod( 'educampus_show_rector', true ) ) : ?>
<?php 
$rector_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_rector', true ) ) {
    $rector_ornament = ' bg-islamic-ornament';
}
$rector_bg_color = get_theme_mod( 'educampus_bg_color_rector', '#f8f6f0' );
$rector_bg_color_end = get_theme_mod( 'educampus_bg_color_end_rector', $rector_bg_color );
$rector_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_rector', '135deg' );
?>
<section id="rector-welcome" class="section-padding<?php echo esc_attr($rector_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($rector_bg_grad_dir); ?>, <?php echo esc_attr($rector_bg_color); ?>, <?php echo esc_attr($rector_bg_color_end); ?>);">
    <div class="container">
        <?php
        $rector_layout = get_theme_mod( 'educampus_rector_layout', 'photo_left' );
        $photo_order = ( $rector_layout === 'photo_right' ) ? 'order-lg-2' : 'order-lg-1';
        $text_order = ( $rector_layout === 'photo_right' ) ? 'order-lg-1' : 'order-lg-2';
        
        $rector_badge   = get_theme_mod( 'educampus_home_rector_badge', 'SAMBUTAN REKTOR' );
        $rector_title   = get_theme_mod( 'educampus_home_rector_title', 'Selamat Datang di EduCampus' );
        $rector_quote   = get_theme_mod( 'educampus_home_rector_quote', '"Menyongsong masa depan pendidikan tinggi yang inklusif, adaptif, dan inovatif."' );
        $rector_p1      = get_theme_mod( 'educampus_home_rector_p1', 'Assalamu\'alaikum Wr. Wb. Selamat datang di portal resmi EduCampus. Kami merasa terhormat dapat menjadi bagian dari perjalanan akademis Anda menuju kesuksesan profesional dan intelektual.' );
        $rector_p2      = get_theme_mod( 'educampus_home_rector_p2', 'Di era transformasi digital ini, EduCampus terus berkomitmen untuk menyelaraskan kurikulum dengan kebutuhan industri, meningkatkan publikasi jurnal internasional, serta memberikan beasiswa penuh bagi putra-putri berprestasi bangsa.' );
        $rector_image   = get_theme_mod( 'educampus_home_rector_image', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop' );
        $rector_name    = get_theme_mod( 'educampus_home_rector_name', 'Prof. Dr. Ir. H. Ahmad Wijaya, M.Sc.' );
        $rector_job     = get_theme_mod( 'educampus_home_rector_name_title', 'Rektor EduCampus' );
        $rector_btn_val = get_theme_mod( 'educampus_home_rector_btn_url', '/profil/sambutan-rektor' );
        $rector_btn_url = ( strpos( $rector_btn_val, 'http' ) === 0 ) ? $rector_btn_val : home_url( $rector_btn_val );
        ?>
        <div class="row align-items-center g-5">
            <!-- Left/Right: Photo -->
            <div class="col-lg-5 text-center <?php echo esc_attr( $photo_order ); ?>">
                <div class="position-relative d-inline-block">
                    <!-- Gold bracket border -->
                    <div class="position-absolute border border-5 border-campus-gold rounded-campus" style="top: 20px; left: 20px; right: -20px; bottom: -20px; z-index: 0;"></div>
                    <img src="<?php echo esc_url( $rector_image ); ?>" class="img-fluid rounded-campus position-relative z-1 shadow-campus-med" alt="<?php echo esc_attr( $rector_name ); ?>" style="max-height: 480px; object-fit: cover;">
                </div>
            </div>
            
            <!-- Right/Left: Message -->
            <div class="col-lg-7 <?php echo esc_attr( $text_order ); ?>">
                <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( $rector_badge ); ?></span>
                <h2 class="h1 font-heading fw-bold text-campus-navy mb-4"><?php echo esc_html( $rector_title ); ?></h2>
                <div class="text-campus-muted font-body mb-4">
                    <?php if ( ! empty( $rector_quote ) ) : ?>
                        <p class="lead fw-normal text-dark mb-3"><?php echo esc_html( $rector_quote ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $rector_p1 ) ) : ?>
                        <p><?php echo esc_html( $rector_p1 ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $rector_p2 ) ) : ?>
                        <p><?php echo esc_html( $rector_p2 ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center justify-content-between border-top pt-4 mt-4">
                    <div>
                        <h4 class="h6 text-campus-navy mb-1 fw-bold"><?php echo esc_html( $rector_name ); ?></h4>
                        <small class="text-campus-muted"><?php echo esc_html( $rector_job ); ?></small>
                    </div>
                    <?php if ( ! empty( $rector_btn_val ) ) : ?>
                        <a href="<?php echo esc_url( $rector_btn_url ); ?>" class="btn btn-campus-outline px-3.5 py-1.5">
                            <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>