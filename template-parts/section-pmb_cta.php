<!-- Section 12: PMB CTA -->
<?php
$pmb_show = get_theme_mod( 'educampus_show_pmb_cta', true );
if ( $pmb_show ) :
    $pmb_title    = get_theme_mod( 'educampus_pmb_cta_title', 'Siap Menyongsong Masa Depan Gemilang?' );
    $pmb_desc     = get_theme_mod( 'educampus_pmb_cta_desc', 'Pendaftaran Penerimaan Mahasiswa Baru Gelombang 1 masih dibuka. Raih beasiswa prestasi akademis dan kemudahan cicilan biaya kuliah.' );
    $pmb_btn1_txt = get_theme_mod( 'educampus_pmb_cta_btn1_text', 'Daftar Sekarang' );
    $pmb_btn1_val = get_theme_mod( 'educampus_pmb_cta_btn1_url', '/pmb' );
    $pmb_btn2_txt = get_theme_mod( 'educampus_pmb_cta_btn2_text', 'Hubungi Admisi' );
    $pmb_btn2_val = get_theme_mod( 'educampus_pmb_cta_btn2_url', 'https://wa.me/6281234567890' );
    $pmb_bg_start = get_theme_mod( 'educampus_bg_color_pmb_cta', '#0a2540' );
    $pmb_bg_end   = get_theme_mod( 'educampus_bg_color_end_pmb_cta', $pmb_bg_start );
    $pmb_bg_dir   = get_theme_mod( 'educampus_bg_grad_dir_pmb_cta', '135deg' );

    $pmb_btn1_url = ( preg_match( '/^https?:\/\//i', $pmb_btn1_val ) ) ? $pmb_btn1_val : home_url( $pmb_btn1_val );
    $pmb_btn2_url = ( preg_match( '/^https?:\/\//i', $pmb_btn2_val ) ) ? $pmb_btn2_val : home_url( $pmb_btn2_val );

    $pmb_ornament = '';
    if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_pmb_cta', true ) ) {
        $pmb_ornament = ' bg-islamic-ornament';
    }
?>
<section id="pmb-cta" class="py-5 text-white text-center position-relative overflow-hidden<?php echo esc_attr($pmb_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($pmb_bg_dir); ?>, <?php echo esc_attr($pmb_bg_start); ?>, <?php echo esc_attr($pmb_bg_end); ?>);">
    <div class="position-absolute top-50 start-50 translate-middle w-100 h-100 bg-campus-gold opacity-10 rounded-circle blur-xl" style="filter: blur(120px); width: 300px; height: 300px;"></div>
    <div class="container position-relative z-1 py-4">
        <?php if ( ! empty( $pmb_title ) ) : ?>
            <h2 class="display-6 font-heading fw-bold text-white mb-3"><?php echo esc_html( $pmb_title ); ?></h2>
        <?php endif; ?>
        <?php if ( ! empty( $pmb_desc ) ) : ?>
            <p class="lead text-white-50 mb-5 font-body mx-auto" style="max-width: 650px;">
                <?php echo esc_html( $pmb_desc ); ?>
            </p>
        <?php endif; ?>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <?php if ( ! empty( $pmb_btn1_txt ) ) : ?>
                <a href="<?php echo esc_url( $pmb_btn1_url ); ?>" class="btn btn-campus-secondary btn-lg px-5 font-heading py-3">
                    <i class="bi bi-mortarboard-fill me-2"></i> <?php echo esc_html( $pmb_btn1_txt ); ?>
                </a>
            <?php endif; ?>
            <?php if ( ! empty( $pmb_btn2_txt ) ) : ?>
                <a href="<?php echo esc_url( $pmb_btn2_url ); ?>" class="btn btn-outline-light btn-lg px-5 font-heading py-3" target="_blank" rel="noopener">
                    <i class="bi bi-chat-text-fill me-2 text-success"></i> <?php echo esc_html( $pmb_btn2_txt ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
