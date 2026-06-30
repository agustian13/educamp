<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

    <footer class="site-footer text-white pt-5 pb-4 mt-auto border-top border-light border-opacity-10">
        <!-- Main Footer Widget Area (4 Columns Layout) -->
        <div class="container pb-4">
            <div class="row g-4">
                <!-- Column 1: Branding and About -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <?php
                        if ( has_custom_logo() ) {
                            $logo_id = get_theme_mod( 'custom_logo' );
                            $logo = wp_get_attachment_image_src( $logo_id , 'full' );
                            if ( $logo ) {
                                echo '<img loading="lazy" src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="img-fluid footer-logo mb-3" style="max-height: 60px;">';
                            }
                        } else {
                            echo '<h3 class="h4 font-heading fw-bold text-campus-gold mb-3">' . esc_html( get_bloginfo( 'name' ) ) . '</h3>';
                        }
                        ?>
                    </div>
                    <p class="text-white-50 mb-4 small me-lg-3">
                        <?php echo esc_html( get_theme_mod( 'educampus_footer_desc', 'EduCampus adalah institusi pendidikan tinggi terkemuka yang berkomitmen menyelenggarakan pendidikan berkualitas tinggi, berorientasi riset, dan mencetak generasi unggul yang siap menghadapi tantangan global.' ) ); ?>
                    </p>
                    <!-- Social Media Links -->
                    <div class="d-flex gap-2">
                        <?php if ( $fb = get_theme_mod( 'educampus_footer_fb', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $fb ); ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if ( $ig = get_theme_mod( 'educampus_footer_ig', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $ig ); ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ( $tw = get_theme_mod( 'educampus_footer_tw', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $tw ); ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center social-btn" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <?php endif; ?>
                        <?php if ( $yt = get_theme_mod( 'educampus_footer_yt', '#' ) ) : ?>
                            <a href="<?php echo esc_url( $yt ); ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center social-btn" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Column 2: Quick Links (via WordPress Menu) -->
                <div class="col-lg-2 col-md-6">
                    <h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3"><?php esc_html_e( 'Tautan Cepat', 'educampus' ); ?></h4>
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'list-unstyled footer-links small',
                            'container'      => false,
                            'fallback_cb'    => '__return_false',
                            'depth'          => 1,
                        ) );
                    } else {
                        echo '<ul class="list-unstyled footer-links small">';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/profil' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Tentang Kampus', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/berita' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Berita Terkini', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/agenda' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Agenda Kegiatan', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/pengumuman' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Pengumuman', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/pmb' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Pendaftaran PMB', 'educampus' ) . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </div>

                <!-- Column 3: Academics (via WordPress Menu) -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3"><?php esc_html_e( 'Akademik', 'educampus' ); ?></h4>
                    <?php
                    if ( has_nav_menu( 'footer_academic' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer_academic',
                            'menu_class'     => 'list-unstyled footer-links small',
                            'container'      => false,
                            'fallback_cb'    => '__return_false',
                            'depth'          => 1,
                        ) );
                    } else {
                        echo '<ul class="list-unstyled footer-links small">';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/faculty' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Fakultas & Sekolah', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/program' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Program Studi', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/lecturer' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Direktori Dosen', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/kalender-akademik' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Kalender Akademik', 'educampus' ) . '</a></li>';
                        echo '<li class="mb-2"><a href="' . esc_url( home_url( '/lppm' ) ) . '" class="text-white-50 text-decoration-none hover-gold">' . esc_html__( 'Lembaga Penelitian (LPPM)', 'educampus' ) . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </div>

                <!-- Column 4: Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3"><?php esc_html_e( 'Kontak & Lokasi', 'educampus' ); ?></h4>
                    <ul class="list-unstyled text-white-50 small footer-contact">
                        <?php if ( $address = get_theme_mod( 'educampus_footer_address', 'Kampus Utama: Jl. Kampus Raya No. 1, Kebayoran Baru, Jakarta Selatan, 12110' ) ) : ?>
                            <li class="d-flex align-items-start mb-3">
                                <i class="bi bi-geo-alt-fill text-campus-gold me-2 mt-1"></i>
                                <span><?php echo esc_html( $address ); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ( $phone = get_theme_mod( 'educampus_footer_phone', '(021) 1234-5678' ) ) : ?>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-telephone-fill text-campus-gold me-2"></i>
                                <span><?php echo esc_html( $phone ); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ( $wa = get_theme_mod( 'educampus_footer_wa', '+62 812-3456-7890' ) ) : ?>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-whatsapp text-campus-gold me-2"></i>
                                <span><?php echo esc_html( $wa ); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ( $email = get_theme_mod( 'educampus_footer_email', 'humas@educampus.ac.id' ) ) : ?>
                            <li class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill text-campus-gold me-2"></i>
                                <span><?php echo esc_html( $email ); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="border-top border-light border-opacity-10 pt-4 mt-4 text-center text-white-50 small">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-start mb-2 mb-md-0">
                        <p class="mb-0">&copy; <?php echo date( 'Y' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Hak Cipta Dilindungi Undang-Undang.', 'educampus' ); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php
                        $copyright_defaults = array(
                            1 => array( 'label' => __( 'Kebijakan Privasi', 'educampus' ), 'url' => '#' ),
                            2 => array( 'label' => __( 'Syarat & Ketentuan', 'educampus' ), 'url' => '#' ),
                            3 => array( 'label' => __( 'Peta Situs', 'educampus' ), 'url' => '#' ),
                        );
                        for ( $i = 1; $i <= 3; $i++ ) :
                            $d     = $copyright_defaults[ $i ];
                            $label = get_theme_mod( "educampus_copyright_link_{$i}_label", $d['label'] );
                            $url   = get_theme_mod( "educampus_copyright_link_{$i}_url", $d['url'] );
                            if ( ! empty( $label ) ) :
                        ?>
                                <a href="<?php echo esc_url( $url ); ?>" class="text-white-50 text-decoration-none me-3 hover-white"><?php echo esc_html( $label ); ?></a>
                        <?php
                            endif;
                        endfor;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
