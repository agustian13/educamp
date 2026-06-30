<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Preconnect & DNS Prefetch for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://www.youtube.com" crossorigin>
    <link rel="preconnect" href="https://i.ytimg.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//www.youtube.com">
    <link rel="dns-prefetch" href="//i.ytimg.com">

    <!-- Theme Color -->
    <meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'educampus_color_primary', '#0a2540' ) ); ?>">

    <!-- JSON-LD Schema: Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollegeOrUniversity",
        "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>",
        "url": "<?php echo esc_js( home_url() ); ?>",
        "description": "<?php echo esc_js( get_bloginfo( 'description' ) ); ?>"
        <?php if ( get_theme_mod( 'educampus_og_default_image' ) ) : ?>
        ,"image": "<?php echo esc_js( get_theme_mod( 'educampus_og_default_image' ) ); ?>"
        <?php endif; ?>
    }
    </script>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Langsung ke konten utama', 'educampus' ); ?></a>

<div id="page" class="site">
    <!-- Top Utility Bar (Classic University Look) -->
    <?php if ( get_theme_mod( 'educampus_show_utility_bar', true ) ) : ?>
    <div class="bg-campus-navy border-bottom border-light border-opacity-10 py-2 d-none d-lg-block">
        <div class="container container-max">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Contact / Info Links -->
                <div class="small text-white-50">
                    <span class="me-3"><i class="bi bi-geo-alt-fill text-campus-gold me-1"></i> <?php echo esc_html( get_theme_mod( 'educampus_header_address', 'Jl. Kampus Raya No. 1, Jakarta' ) ); ?></span>
                    <span><i class="bi bi-envelope-fill text-campus-gold me-1"></i> <?php echo esc_html( get_theme_mod( 'educampus_header_email', 'info@educampus.ac.id' ) ); ?></span>
                </div>
                <!-- Mini Quick Links -->
                <div class="small">
                    <?php
                    $quick_link_defaults = array(
                        1 => array( 'label' => __( 'SIAKAD', 'educampus' ), 'url' => '#' ),
                        2 => array( 'label' => __( 'E-Learning', 'educampus' ), 'url' => '#' ),
                        3 => array( 'label' => __( 'Perpustakaan', 'educampus' ), 'url' => '#' ),
                        4 => array( 'label' => __( 'Repository', 'educampus' ), 'url' => '#' ),
                    );
                    for ( $i = 1; $i <= 4; $i++ ) :
                        $d    = $quick_link_defaults[ $i ];
                        $label = get_theme_mod( "educampus_quick_link_{$i}_label", $d['label'] );
                        $url   = get_theme_mod( "educampus_quick_link_{$i}_url", $d['url'] );
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
    <?php endif; ?>

    <!-- Main Navigation Header (Sticky Header) -->
    <header id="masthead" class="site-header sticky-top bg-white shadow-campus-soft py-2 py-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-0" aria-label="Primary Navigation">
            <div class="container">
                <!-- Branding / Logo -->
                <div class="navbar-brand py-0 d-flex align-items-center">
                    <?php
                    if ( has_custom_logo() ) {
                        the_custom_logo();
                    } else {
                        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-campus-navy text-decoration-none fw-bold fs-3 font-heading">';
                        echo esc_html( get_bloginfo( 'name' ) );
                        echo '</a>';
                    }
                    ?>
                </div>

                <!-- Mobile: PMB CTA always visible (outside collapse) -->
                <div class="d-flex d-lg-none align-items-center gap-2 ms-auto me-2">
                    <a href="<?php echo esc_url( educampus_resolve_cta_url( get_theme_mod( 'educampus_header_pmb_url', '/pmb' ) ) ); ?>" class="btn btn-campus-primary font-heading btn-sm px-3 py-1 text-nowrap">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo esc_html( get_theme_mod( 'educampus_header_pmb_text', 'Daftar PMB' ) ); ?>
                    </a>
                </div>

                <!-- Toggle Mobile Button -->
                <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNavbar" aria-controls="primaryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="bi bi-list fs-1 text-campus-navy"></span>
                </button>

                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="primaryNavbar">
                    <!-- Standard menus -->
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav mx-auto mb-0 font-heading fw-500 gap-1 gap-lg-3',
                        'fallback_cb'    => '__return_false',
                        'depth'          => 3,
                    ) );
                    ?>

                    <!-- Fallback menu if not configured in WordPress Admin -->
                    <?php if ( ! has_nav_menu( 'primary' ) ) : ?>
                        <ul class="navbar-nav mx-auto mb-0 font-heading fw-500 gap-3">
                            <li class="nav-item"><a class="nav-link text-campus-navy" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'educampus' ); ?></a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-campus-navy" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php esc_html_e( 'Profil', 'educampus' ); ?></a>
                                <ul class="dropdown-menu border-0 shadow-campus-med rounded-campus p-3">
                                    <li><a class="dropdown-item rounded-3 py-2" href="<?php echo esc_url( home_url( '/profil/sejarah' ) ); ?>"><?php esc_html_e( 'Sejarah', 'educampus' ); ?></a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="<?php echo esc_url( home_url( '/profil/visi-misi' ) ); ?>"><?php esc_html_e( 'Visi & Misi', 'educampus' ); ?></a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="<?php echo esc_url( home_url( '/profil/pimpinan' ) ); ?>"><?php esc_html_e( 'Pimpinan', 'educampus' ); ?></a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link text-campus-navy" href="<?php echo esc_url( home_url( '/faculty' ) ); ?>"><?php esc_html_e( 'Fakultas', 'educampus' ); ?></a></li>
                            <li class="nav-item"><a class="nav-link text-campus-navy" href="<?php echo esc_url( home_url( '/program' ) ); ?>"><?php esc_html_e( 'Program Studi', 'educampus' ); ?></a></li>
                            <li class="nav-item"><a class="nav-link text-campus-navy" href="<?php echo esc_url( home_url( '/berita' ) ); ?>"><?php esc_html_e( 'Berita', 'educampus' ); ?></a></li>
                            <li class="nav-item"><a class="nav-link text-campus-navy" href="<?php echo esc_url( home_url( '/kontak' ) ); ?>"><?php esc_html_e( 'Kontak', 'educampus' ); ?></a></li>
                        </ul>
                    <?php endif; ?>

                    <!-- Action Elements (Search, PMB CTA) inside nav for desktop -->
                    <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-3 mt-lg-0 border-top pt-3 pt-lg-0 border-light border-opacity-10 border-lg-none d-lg-flex">
                        <!-- Search Icon -->
                        <button class="btn btn-link text-campus-navy d-none d-lg-block px-2 text-decoration-none shadow-none" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                        
                        <!-- Mini search for mobile -->
                        <div class="d-lg-none mb-2">
                            <?php get_search_form(); ?>
                        </div>

                        <!-- Desktop PMB Button -->
                        <a href="<?php echo esc_url( educampus_resolve_cta_url( get_theme_mod( 'educampus_header_pmb_url', '/pmb' ) ) ); ?>" class="btn btn-campus-primary font-heading px-4 text-center d-none d-lg-inline-block">
                            <i class="bi bi-mortarboard-fill me-2"></i> <?php echo esc_html( get_theme_mod( 'educampus_header_pmb_text', 'Daftar PMB' ) ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
    </header>

    <!-- Search Modal (Desktop & Mobile) - Placed outside header to avoid z-index/focus issues -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-white text-dark rounded-campus shadow-campus-lg">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 px-lg-5 pb-5 pt-3">
                    <div class="text-center mb-4">
                        <i class="bi bi-search text-campus-gold display-5 mb-2 d-inline-block"></i>
                        <h3 class="h4 font-heading fw-bold text-campus-navy mb-2"><?php esc_html_e( 'Pencarian Informasi', 'educampus' ); ?></h3>
                        <p class="text-campus-muted small font-body mb-0"><?php printf( esc_html__( 'Temukan berita, program studi, fakultas, atau artikel di %s', 'educampus' ), '<strong>' . esc_html( get_bloginfo( 'name' ) ) . '</strong>' ); ?></p>
                    </div>
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="input-group input-group-lg border-bottom border-2 border-campus-gold pb-2 bg-transparent">
                            <span class="input-group-text bg-transparent border-0 text-campus-navy px-2"><i class="bi bi-search fs-4"></i></span>
                            <input type="search" class="form-control bg-transparent text-dark border-0 shadow-none px-2 font-heading" placeholder="<?php esc_attr_e( 'Ketik kata kunci pencarian...', 'educampus' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required style="font-size: 1.25rem;" />
                            <button type="submit" class="btn btn-campus-secondary px-3 py-1.5 fs-6 rounded-3 shadow-campus-soft">
                                <?php esc_html_e( 'Cari', 'educampus' ); ?> <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
// End of file
