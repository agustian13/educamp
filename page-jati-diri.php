<?php
/**
 * Template Name: Jati Diri Page Layout
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$flower_svg = get_template_directory_uri() . '/assets/images/icons/flower-sec.svg';

// Build pillars array from Customizer
$pillar_subtitles = array(
    1 => __( 'Sehat', 'educampus' ),
    2 => __( 'Baik', 'educampus' ),
    3 => __( 'Benar', 'educampus' ),
    4 => __( 'Pintar', 'educampus' ),
    5 => __( 'Terampil', 'educampus' ),
);
$pillars = array();
for ( $i = 1; $i <= 5; $i++ ) {
    $pillars[] = array(
        'number' => sprintf( '%02d', $i ),
        'title'   => get_theme_mod( "educampus_pilar{$i}_title", '' ),
        'desc'    => get_theme_mod( "educampus_pilar{$i}_desc", '' ),
        'subtitle' => $pillar_subtitles[ $i ],
    );
}

educampus_page_hero( array(
    'title' => esc_html__( 'Jati Diri', 'educampus' ),
    'badge' => __( 'TENTANG KAMPUS', 'educampus' ),
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

                <section id="tab-jatidiri" class="usu-section">
                    <div class="title-fill title-fill-md mb-4">
                        <div class="title-fill__icon">
                            <img src="<?php echo esc_url( $flower_svg ); ?>" alt="" width="32" height="32" />
                        </div>
                        <h2 class="title-fill__text"><?php esc_html_e( 'Jati Diri & Nilai Dasar', 'educampus' ); ?></h2>
                    </div>
                    <p class="fs-5 lh-lg mb-4" style="color: var(--color-text-muted); line-height: 1.7;"><?php esc_html_e( 'Dalam implementasinya di lingkungan Institut Agama Islam Latifah Mubarokiyah (IAILM) Suryalaya, filosofi Cageur, Bageur, Bener, Pinter, Singer bertransformasi menjadi landasan visi universitas dalam mencetak Insan Kamil yang berlandaskan nilai-nilai tasawuf.', 'educampus' ); ?></p>

                    <p class="fs-6 lh-lg mb-5" style="color: var(--color-text-muted); line-height: 1.7;"><?php esc_html_e( 'Sebagai institusi pendidikan tinggi yang berakar pada tradisi pesantren, IAILM tidak semata-mata mengejar capaian kecerdasan intelektual yang direpresentasikan melalui indeks prestasi dan gelar akademik, melainkan mengintegrasikan ilmu amaliah dan amal ilmiah secara sinergis guna melahirkan lulusan dengan profil unggul pada aspek-aspek berikut:', 'educampus' ); ?></p>

                    <div class="d-flex flex-column gap-4">
                        <?php foreach ( $pillars as $pillar ) : ?>
                        <div class="scroll-move">
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <span class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" style="width: 48px; height: 48px; font-family: var(--font-heading); font-size: 1rem; background: var(--color-primary); box-shadow: 0 0 0 3px var(--color-secondary-light); line-height: 1;"><?php echo esc_html( $pillar['number'] ); ?></span>
                                <div>
                                    <h3 class="h5 fw-bold mb-0" style="color: var(--color-primary);"><?php echo esc_html( $pillar['title'] ); ?></h3>
                                    <p class="small text-campus-gold fw-semibold text-uppercase mb-0" style="letter-spacing: 0.05em;"><?php echo esc_html( $pillar['subtitle'] ); ?></p>
                                </div>
                            </div>
                            <p class="mb-0 lh-lg" style="color: var(--color-text-muted); padding-left: 54px;"><?php echo esc_html( $pillar['desc'] ); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Closing -->
                    <div class="mt-5 pt-4 scroll-move text-center">
                        <hr class="w-25 mx-auto opacity-25 mb-4">
                        <p class="fw-semibold lh-lg mb-0" style="color: var(--color-primary);"><?php esc_html_e( 'Melalui manifestasi filosofi ini, IAILM Suryalaya berkomitmen melahirkan lulusan yang seimbang: tajam secara intelektual, namun tetap kokoh secara spiritual.', 'educampus' ); ?></p>
                    </div>
                </section>

                </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>
