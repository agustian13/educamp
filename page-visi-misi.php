<?php
/**
 * Template Name: Visi & Misi Page Layout
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$flower_svg = get_template_directory_uri() . '/assets/images/icons/flower-sec.svg';

educampus_page_hero( array(
    'title' => esc_html__( 'Visi & Misi', 'educampus' ),
    'badge' => esc_html__( 'TENTANG KAMPUS', 'educampus' ),
    'image' => get_theme_mod( 'educampus_profile_hero_image', '' ),
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>

    <div class="row mt-2">
        <main id="primary" class="col-lg-10 col-xl-8 mx-auto site-main">

            <section id="tab-visi-misi" class="usu-section position-relative overflow-hidden">
                <div class="position-absolute d-none d-md-block" style="top: -6px; right: 0; opacity: 0.25; z-index: 0; pointer-events: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" width="44" height="44">
                        <path d="M2 2 H42 Q50 2 50 10 V58" stroke="var(--color-secondary)" stroke-width="1.2" fill="none" opacity="0.6"/>
                        <circle cx="46" cy="6" r="2" fill="var(--color-secondary)" opacity="0.4"/>
                    </svg>
                </div>

                <!-- Vision -->
                <div class="position-relative mb-3 scroll-move rounded-4 overflow-hidden">
                    <div class="position-absolute start-0 top-0 h-100" style="width: 40%; background: linear-gradient(90deg, var(--color-secondary-light) 0%, transparent 100%); opacity: 0.12; pointer-events: none; z-index: 0;"></div>
                    <div class="position-relative px-3 px-md-4" style="z-index: 1;">

                    <span class="d-inline-flex align-items-center gap-2 bg-campus-navy text-white px-4 py-2 rounded-2 mb-4">
                        <img src="<?php echo esc_url( $flower_svg ); ?>" alt="" width="22" height="22" class="opacity-75" />
                        <span class="fw-bold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.95rem;">Visi</span>
                    </span>

                    <p class="fs-5 fw-500 lh-lg mb-1" style="color: var(--color-primary); line-height: 1.7;">
                        &ldquo;<?php echo esc_html( get_theme_mod( 'educampus_visi', 'Menjadi pelopor perguruan tinggi nasional berkelas dunia (World Class University) yang unggul, inovatif, mengabdi kepada kepentingan bangsa dan kemanusiaan berlandaskan nilai luhur Pancasila.' ) ); ?>&rdquo;
                    </p>
                    <span class="d-inline-block small text-campus-gold fw-semibold text-uppercase" style="letter-spacing: 0.08em;">&mdash; Visi Institusi</span>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="my-4 opacity-25 scroll-move">

                <!-- Mission -->
                <div class="scroll-move rounded-4 overflow-hidden position-relative">
                    <div class="position-absolute start-0 top-0 h-100" style="width: 40%; background: linear-gradient(90deg, var(--color-secondary-light) 0%, transparent 100%); opacity: 0.12; pointer-events: none; z-index: 0;"></div>
                    <div class="position-relative px-3 px-md-4" style="z-index: 1;">
                    <span class="d-inline-flex align-items-center gap-2 bg-campus-navy text-white px-4 py-2 rounded-2 mb-4">
                        <img src="<?php echo esc_url( $flower_svg ); ?>" alt="" width="22" height="22" class="opacity-75" />
                        <span class="fw-bold text-uppercase" style="letter-spacing: 0.04em; font-size: 0.95rem;">Misi</span>
                    </span>

                    <p class="text-muted mb-4 small"><?php esc_html_e( 'Tri Dharma Perguruan Tinggi', 'educampus' ); ?></p>

                    <div class="d-flex flex-column gap-3">
                        <?php
                        $misi_default = "Menyelenggarakan pendidikan tinggi yang berkualitas, relevan, serta responsif terhadap perkembangan revolusi industri dan kebutuhan sumber daya manusia global.\nMendorong riset terapan yang inovatif dan kolaboratif antar-disiplin ilmu guna memproduksi publikasi jurnal internasional bereputasi tinggi.\nMenyelenggarakan kegiatan pengabdian kepada masyarakat berbasis riset guna mempercepat penyelesaian isu sosial dan pembangunan wilayah berkelanjutan.";
                        $misi_text   = get_theme_mod( 'educampus_misi', $misi_default );
                        $misi_lines  = explode( "\n", $misi_text );
                        $total_misi  = count( $misi_lines );
                        $pad_width   = $total_misi >= 10 ? 2 : 2;
                        $misi_index  = 0;
                        foreach ( $misi_lines as $line ) {
                            if ( trim( $line ) ) {
                                $number = str_pad( $misi_index + 1, $pad_width, '0', STR_PAD_LEFT );
                                ?>
                                <div class="d-flex gap-3 gap-md-4 align-items-start scroll-move">
                                    <div class="flex-shrink-0 d-flex flex-column align-items-center" style="width: 56px;">
                                        <span class="d-flex align-items-center justify-content-center rounded-circle bg-campus-navy text-white fw-bold" style="width: 48px; height: 48px; font-family: var(--font-heading); font-size: 1rem;"><?php echo esc_html( $number ); ?></span>
                                        <div class="mt-2" style="width: 2px; flex-grow: 1; min-height: 24px; background: linear-gradient(to bottom, var(--color-secondary), transparent);"></div>
                                    </div>
                                    <div class="flex-grow-1 pb-4">
                                        <p class="mb-0 lh-lg" style="color: var(--color-text-muted);"><?php echo esc_html( trim( $line ) ); ?></p>
                                    </div>
                                </div>
                                <?php
                                $misi_index++;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            </section>

        </main>
    </div>
</div>

<?php get_footer(); ?>
