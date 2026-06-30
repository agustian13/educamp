<?php
/**
 * Template Name: Sejarah Page Layout
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$flower_svg = get_template_directory_uri() . '/assets/images/icons/flower-sec.svg';
educampus_page_hero( array(
    'title' => esc_html__( 'Sejarah', 'educampus' ),
    'badge' => esc_html__( 'TENTANG KAMPUS', 'educampus' ),
    'image' => get_theme_mod( 'educampus_profile_hero_image', '' ),
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-4 mt-2">
        <main id="primary" class="col-lg-10 col-xl-8 mx-auto site-main">

            <section id="tab-sejarah" class="usu-section position-relative">
                <div class="position-absolute d-none d-md-block" style="top: -10px; right: 0; opacity: 0.35;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" width="50" height="50">
                        <path d="M2 2 H42 Q50 2 50 10 V58" stroke="#c5a059" stroke-width="1.5" fill="none" opacity="0.5"/>
                        <circle cx="46" cy="6" r="2.5" fill="#c5a059" opacity="0.4"/>
                    </svg>
                </div>
                <div class="position-absolute d-none d-md-block" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none; width: 80%; height: 300px; background: linear-gradient(90deg, transparent 0%, var(--color-secondary-light) 25%, var(--color-secondary) 50%, var(--color-primary-light) 75%, transparent 100%); opacity: 0.06;"></div>

                <div class="title-fill title-fill-md mb-4 scroll-move">
                    <div class="title-fill__icon">
                        <img src="<?php echo esc_url( $flower_svg ); ?>" alt="" width="32" height="32" />
                    </div>
                    <h2 class="title-fill__text"><?php esc_html_e( 'Sejarah & Lintasan Sejarah', 'educampus' ); ?></h2>
                </div>
                <p class="fs-5 text-campus-muted font-body mb-4 scroll-move" style="line-height: 1.8;"><?php esc_html_e( 'Didirikan di atas cita-cita luhur mencerdaskan kehidupan bangsa, EduCampus terus tumbuh melesat memimpin transformasi pendidikan nasional tinggi.', 'educampus' ); ?></p>

                <div class="text-center mb-4 scroll-move">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/ornament-divider.svg' ); ?>" alt="" width="160" height="16" class="opacity-75" />
                </div>

                <div class="timeline-container position-relative">
                    <?php
                    $ms_years  = array( 1 => '1952', 2 => '1957', 3 => '2003', 4 => '2026' );
                    $ms_titles = array(
                        1 => 'Pendirian Yayasan Kampus',
                        2 => 'Peresmian Universitas Negeri',
                        3 => 'Kenaikan Status PTN-BH',
                        4 => 'Peringkat Akreditasi UNGGUL'
                    );
                    $ms_descs  = array(
                        1 => 'Inisiasi awal perkumpulan pendidik nasional membentuk Yayasan Pendidikan Tinggi guna menyelenggarakan rintisan fakultas kedokteran dan teknik pertama di daerah.',
                        2 => 'Presiden Republik Indonesia pertama, Ir. Soekarno, meresmikan secara langsung alih status universitas menjadi Perguruan Tinggi Negeri (PTN) berdaulat ke-7 di Indonesia.',
                        3 => 'Sesuai Peraturan Pemerintah RI, institusi resmi menyandang predikat Perguruan Tinggi Negeri Badan Hukum (PTN-BH) yang memvalidasi otonomi akademik penuh kampus.',
                        4 => 'BAN-PT memberikan akreditasi tertinggi UNGGUL nasional, sejajar dengan akselerasi program internasionalisasi riset menuju universitas berkelas dunia.'
                    );
                    for ( $i = 1; $i <= 4; $i++ ) :
                        $year  = get_theme_mod( "educampus_ms{$i}_year", $ms_years[ $i ] );
                        $title = get_theme_mod( "educampus_ms{$i}_title", $ms_titles[ $i ] );
                        $desc  = get_theme_mod( "educampus_ms{$i}_desc", $ms_descs[ $i ] );
                        $dot_color    = ( $i === 1 || $i === 4 ) ? 'bg-campus-gold' : 'bg-campus-navy';
                    ?>
                    <div class="d-flex gap-3 mb-5 <?php echo $i === 4 ? 'mb-0' : ''; ?> scroll-move">
                        <div class="flex-shrink-0">
                            <span class="d-flex align-items-center justify-content-center rounded-circle <?php echo esc_attr( $dot_color ); ?>" style="width: 18px; height: 18px; margin-top: 4px; border: 3px solid #fff; box-shadow: 0 0 0 2px var(--color-secondary-light);">
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php echo esc_html( $year ); ?> &mdash; <?php echo esc_html( $title ); ?></h3>
                            <p class="mb-0" style="font-size: 1rem; line-height: 1.7; color: var(--color-text-muted);"><?php echo esc_html( $desc ); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div class="text-center mt-4 pt-2 scroll-move">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/ornament-divider.svg' ); ?>" alt="" width="160" height="16" class="opacity-50" />
                </div>
            </section>

        </main>
    </div>
</div>

<style>
    .timeline-dot { margin-top: 2px; }
</style>

<?php get_footer(); ?>
