<?php
/**
 * Template Name: Identitas Page Layout
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$flower_svg = get_template_directory_uri() . '/assets/images/icons/flower-sec.svg';
educampus_page_hero( array(
    'title' => esc_html__( 'Identitas', 'educampus' ),
    'badge' => esc_html__( 'TENTANG KAMPUS', 'educampus' ),
    'image' => get_theme_mod( 'educampus_profile_hero_image', '' ),
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-4 mt-2">
        <main id="primary" class="col-12 site-main">
            <div class="position-relative overflow-hidden rounded-4">
                <div class="position-relative" style="z-index: 2;">
                    <div class="position-absolute d-none d-md-block" style="top: -6px; right: 0; opacity: 0.25; z-index: 0; pointer-events: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" width="44" height="44">
                            <path d="M2 2 H42 Q50 2 50 10 V58" stroke="var(--color-secondary)" stroke-width="1.2" fill="none" opacity="0.6"/>
                            <circle cx="46" cy="6" r="2" fill="var(--color-secondary)" opacity="0.4"/>
                        </svg>
                    </div>
                    <div class="position-absolute start-0 top-0 h-100" style="width: 40%; background: linear-gradient(90deg, var(--color-secondary-light) 0%, transparent 100%); opacity: 0.12; pointer-events: none; z-index: 0;"></div>
                    <div class="position-relative pt-4 pb-4 pt-md-4 pb-md-5 px-3 px-md-4 mx-auto" style="z-index: 1; max-width: 920px;">

                <section id="tab-identitas" class="usu-section">
                    <div class="title-fill title-fill-md mb-4">
                        <div class="title-fill__icon">
                            <img src="<?php echo esc_url( $flower_svg ); ?>" alt="" width="32" height="32" />
                        </div>
                        <h2 class="title-fill__text"><?php esc_html_e( 'Lambang & Identitas Visual', 'educampus' ); ?></h2>
                    </div>

                    <div class="row g-4 align-items-start mb-5">
                        <div class="col-md-3 text-center">
                            <?php
                            $logo_lambang = get_theme_mod( 'educampus_logo_lambang', '' );
                            if ( ! empty( $logo_lambang ) ) :
                            ?>
                            <div class="p-2 border rounded-4 bg-white d-inline-block shadow-campus-soft">
                                <img src="<?php echo esc_url( $logo_lambang ); ?>" alt="<?php esc_attr_e( 'Lambang Kampus', 'educampus' ); ?>" class="img-fluid" style="max-height: 120px; object-fit: contain;">
                            </div>
                            <?php else : ?>
                            <div class="bg-campus-navy text-campus-gold rounded-4 p-4 d-inline-block shadow-campus-soft">
                                <i class="bi bi-shield-check" style="font-size: 4rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9 font-body text-campus-muted">
                            <h5 class="fw-bold mb-2" style="color: var(--color-primary);"><?php esc_html_e( 'Makna Lambang Universitas', 'educampus' ); ?></h5>
                            <?php
                            $default_lambang_desc = "<h5>1. Lapisan Pertama: Bingkai Segi Lima</h5>\n<p>Melambangkan asas tunggal Pancasila dan Lima Rukun Islam.</p>\n<h5>2. Lapisan Kedua: Kupu-Kupu</h5>\n<p>Melambangkan transformasi spiritual melalui Syariat, Tarekat, Hakikat, dan Makrifat.</p>\n<h5>3. Lapisan Ketiga: Padi dan Kapas</h5>\n<p>Melambangkan kemakmuran dan kemerdekaan Republik Indonesia.</p>";
                            echo wp_kses_post( wpautop( get_theme_mod( 'educampus_lambang_desc', $default_lambang_desc ) ) );
                            ?>
                        </div>
                    </div>

                    <div class="border-top pt-4">
                        <h4 class="h5 font-heading fw-bold text-campus-navy mb-3"><i class="bi bi-music-note-beamed text-campus-gold me-2"></i> <?php esc_html_e( 'Hymne & Mars Universitas', 'educampus' ); ?></h4>
                        <div class="row g-3">
                            <?php
                            $hymne_yt = get_theme_mod( 'educampus_hymne_youtube', '' );
                            $mars_yt  = get_theme_mod( 'educampus_mars_youtube', '' );
                            ?>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-campus-light h-100">
                                    <h5 class="h6 font-heading fw-bold text-campus-navy mb-2 text-center"><?php esc_html_e( 'Hymne Kampus', 'educampus' ); ?></h5>
                                    <?php if ( ! empty( $hymne_yt ) ) :
                                        $hymne_id = '';
                                        preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $hymne_yt, $hymne_matches );
                                        if ( ! empty( $hymne_matches[1] ) ) $hymne_id = $hymne_matches[1];
                                    ?>
                                        <div class="position-relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                            <iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $hymne_id ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 6px;"></iframe>
                                        </div>
                                    <?php else : ?>
                                        <p class="text-center text-campus-muted mb-0 font-body" style="font-style: italic; line-height: 1.7;">
                                            <?php
                                            $hymne_default = "Bakti suci kami persembahkan...\nKe pangkuan ibu pertiwi...\nEduCampus wadah ilmu mulia...\nTeguh jaya abadi...";
                                            echo nl2br( esc_html( get_theme_mod( 'educampus_hymne', $hymne_default ) ) );
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-campus-light h-100">
                                    <h5 class="h6 font-heading fw-bold text-campus-navy mb-2 text-center"><?php esc_html_e( 'Mars Kampus', 'educampus' ); ?></h5>
                                    <?php if ( ! empty( $mars_yt ) ) :
                                        $mars_id = '';
                                        preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $mars_yt, $mars_matches );
                                        if ( ! empty( $mars_matches[1] ) ) $mars_id = $mars_matches[1];
                                    ?>
                                        <div class="position-relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                            <iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $mars_id ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 6px;"></iframe>
                                        </div>
                                    <?php else : ?>
                                        <p class="text-center text-campus-muted mb-0 font-body" style="font-style: italic; line-height: 1.7;">
                                            <?php
                                            $mars_default = "Bangkitlah pemuda pemudi bangsa...\nTuntutlah ilmu setinggi angkasa...\nEduCampus siap membina...\nJayalah almamater kita...";
                                            echo nl2br( esc_html( get_theme_mod( 'educampus_mars', $mars_default ) ) );
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>
