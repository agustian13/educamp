<?php
/**
 * Gutenberg Block Patterns for EduCampus Theme
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function educampus_register_block_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    // Register Category
    register_block_pattern_category(
        'educampus-academic',
        array( 'label' => __( 'Academic Layouts', 'educampus' ) )
    );

    // 1. Hero Tentang Pattern
    register_block_pattern(
        'educampus/hero-tentang',
        array(
            'title'       => __( 'Hero Tentang (USU Style)', 'educampus' ),
            'description' => _x( 'Hero title with academic accreditation badge', 'Block pattern description', 'educampus' ),
            'categories'  => array( 'educampus-academic' ),
            'content'     => '<!-- wp:group {"tagName":"section","className":"bg-campus-navy text-white py-5 position-relative overflow-hidden","layout":{"type":"constrained"}} -->
<section class="wp-block-group bg-campus-navy text-white py-5 position-relative overflow-hidden">
    <!-- wp:group {"className":"container position-relative z-1 py-4","layout":{"type":"default"}} -->
    <div class="wp-block-group container position-relative z-1 py-4">
        <!-- wp:paragraph {"className":"text-campus-gold font-heading fw-bold text-uppercase d-block mb-1 small"} -->
        <p class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-1 small" style="letter-spacing: 1px;">TENTANG KAMPUS</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:heading {"level":1,"className":"display-5 font-heading fw-bold mb-2"} -->
        <h1 class="wp-block-heading display-5 font-heading fw-bold mb-2">Profil &amp; Jati Diri</h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"className":"lead text-white-50 mb-0"} -->
        <p class="lead text-white-50 mb-0">Terakreditasi Unggul BAN-PT. Menuju Universitas Berstandar Internasional.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->',
        )
    );

    // 2. Stats Grid Pattern
    register_block_pattern(
        'educampus/stats-grid',
        array(
            'title'       => __( 'Key Stats Grid', 'educampus' ),
            'categories'  => array( 'educampus-academic' ),
            'content'     => '<!-- wp:group {"className":"py-5 bg-white","layout":{"type":"constrained"}} -->
<div class="wp-block-group py-5 bg-white">
    <!-- wp:group {"className":"container","layout":{"type":"default"}} -->
    <div class="wp-block-group container">
        <!-- wp:columns {"className":"row g-4 text-center"} -->
        <div class="wp-block-columns row g-4 text-center">
            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-3 border rounded-3 bg-light"} -->
                <div class="wp-block-group p-3 border rounded-3 bg-light">
                    <!-- wp:heading {"level":3,"className":"display-6 fw-bold text-academic-green mb-1"} -->
                    <h3 class="wp-block-heading display-6 fw-bold text-academic-green mb-1" style="color: #038A47;">42.687</h3>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-muted small mb-0"} -->
                    <p class="text-muted small mb-0">Mahasiswa Aktif</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-3 border rounded-3 bg-light"} -->
                <div class="wp-block-group p-3 border rounded-3 bg-light">
                    <!-- wp:heading {"level":3,"className":"display-6 fw-bold text-academic-green mb-1"} -->
                    <h3 class="wp-block-heading display-6 fw-bold text-academic-green mb-1" style="color: #038A47;">218</h3>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-muted small mb-0"} -->
                    <p class="text-muted small mb-0">Guru Besar (Profesor)</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-3 border rounded-3 bg-light"} -->
                <div class="wp-block-group p-3 border rounded-3 bg-light">
                    <!-- wp:heading {"level":3,"className":"display-6 fw-bold text-academic-green mb-1"} -->
                    <h3 class="wp-block-heading display-6 fw-bold text-academic-green mb-1" style="color: #038A47;">1.600+</h3>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-muted small mb-0"} -->
                    <p class="text-muted small mb-0">Dosen &amp; Pengajar</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-3 border rounded-3 bg-light"} -->
                <div class="wp-block-group p-3 border rounded-3 bg-light">
                    <!-- wp:heading {"level":3,"className":"display-6 fw-bold text-academic-green mb-1"} -->
                    <h3 class="wp-block-heading display-6 fw-bold text-academic-green mb-1" style="color: #038A47;">160+</h3>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-muted small mb-0"} -->
                    <p class="text-muted small mb-0">Program Studi</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );

    // 3. Rankings Bar Pattern
    register_block_pattern(
        'educampus/rankings-bar',
        array(
            'title'       => __( 'Rankings Bar', 'educampus' ),
            'categories'  => array( 'educampus-academic' ),
            'content'     => '<!-- wp:group {"className":"py-4 bg-light border-top border-bottom","layout":{"type":"constrained"}} -->
<div class="wp-block-group py-4 bg-light border-top border-bottom">
    <!-- wp:group {"className":"container","layout":{"type":"default"}} -->
    <div class="wp-block-group container">
        <!-- wp:columns {"className":"row g-3 align-items-center text-center"} -->
        <div class="wp-block-columns row g-3 align-items-center text-center">
            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:paragraph {"className":"fw-bold text-campus-navy mb-0"} -->
                <p class="fw-bold text-campus-navy mb-0">Peringkat Internasional:</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-2 bg-white rounded border"} -->
                <div class="wp-block-group p-2 bg-white rounded border">
                    <!-- wp:paragraph {"className":"small mb-0"} -->
                    <p class="small mb-0"><strong>#1201+</strong> QS World Ranking</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-2 bg-white rounded border"} -->
                <div class="wp-block-group p-2 bg-white rounded border">
                    <!-- wp:paragraph {"className":"small mb-0"} -->
                    <p class="small mb-0"><strong>#351-400</strong> QS Asia University</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column {"className":"col-md-3"} -->
            <div class="wp-block-column col-md-3">
                <!-- wp:group {"className":"p-2 bg-white rounded border"} -->
                <div class="wp-block-group p-2 bg-white rounded border">
                    <!-- wp:paragraph {"className":"small mb-0"} -->
                    <p class="small mb-0"><strong>#1201+</strong> THE World Ranking</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );

    // 4. Himne & Mars Lyrics Pattern
    register_block_pattern(
        'educampus/himne-mars',
        array(
            'title'       => __( 'Himne & Mars Lyrics', 'educampus' ),
            'categories'  => array( 'educampus-academic' ),
            'content'     => '<!-- wp:group {"className":"py-5 bg-white","layout":{"type":"constrained"}} -->
<div class="wp-block-group py-5 bg-white">
    <!-- wp:group {"className":"container","layout":{"type":"default"}} -->
    <div class="wp-block-group container">
        <!-- wp:columns {"className":"row g-4"} -->
        <div class="wp-block-columns row g-4">
            <!-- wp:column {"className":"col-md-6"} -->
            <div class="wp-block-column col-md-6">
                <!-- wp:group {"className":"p-4 border rounded-3 bg-light h-100 shadow-sm"} -->
                <div class="wp-block-group p-4 border rounded-3 bg-light h-100 shadow-sm">
                    <!-- wp:heading {"level":4,"className":"h5 text-campus-navy mb-3 text-center border-bottom pb-2"} -->
                    <h4 class="wp-block-heading h5 text-campus-navy mb-3 text-center border-bottom pb-2">Lirik Himne Kampus</h4>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-center text-muted font-body mb-0","style":{"typography":{"fontStyle":"italic","lineHeight":"1.8"}}} -->
                    <p class="text-center text-muted font-body mb-0" style="font-style:italic;line-height:1.8">Bakti suci kami persembahkan...<br>Ke pangkuan ibu pertiwi...<br>EduCampus wadah ilmu mulia...<br>Teguh jaya abadi...</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"col-md-6"} -->
            <div class="wp-block-column col-md-6">
                <!-- wp:group {"className":"p-4 border rounded-3 bg-light h-100 shadow-sm"} -->
                <div class="wp-block-group p-4 border rounded-3 bg-light h-100 shadow-sm">
                    <!-- wp:heading {"level":4,"className":"h5 text-campus-navy mb-3 text-center border-bottom pb-2"} -->
                    <h4 class="wp-block-heading h5 text-campus-navy mb-3 text-center border-bottom pb-2">Lirik Mars Kampus</h4>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"className":"text-center text-muted font-body mb-0","style":{"typography":{"fontStyle":"italic","lineHeight":"1.8"}}} -->
                    <p class="text-center text-muted font-body mb-0" style="font-style:italic;line-height:1.8">Bangkitlah pemuda pemudi bangsa...<br>Tuntutlah ilmu setinggi angkasa...<br>EduCampus siap membina...<br>Jayalah almamater kita...</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );

    // 5. Lambang & Makna Pattern
    register_block_pattern(
        'educampus/lambang-detail',
        array(
            'title'       => __( 'Lambang & Makna Detail', 'educampus' ),
            'categories'  => array( 'educampus-academic' ),
            'content'     => '<!-- wp:group {"className":"py-5 bg-white","layout":{"type":"constrained"}} -->
<div class="wp-block-group py-5 bg-white">
    <!-- wp:group {"className":"container","layout":{"type":"default"}} -->
    <div class="wp-block-group container">
        <!-- wp:columns {"className":"row g-4 align-items-center"} -->
        <div class="wp-block-columns row g-4 align-items-center">
            <!-- wp:column {"className":"col-md-4 text-center"} -->
            <div class="wp-block-column col-md-4 text-center">
                <!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"img-fluid p-3 border rounded-4 bg-light shadow-sm"} -->
                <figure class="wp-block-image size-large img-fluid p-3 border rounded-4 bg-light shadow-sm"><img src="" alt="Lambang Kampus"/></figure>
                <!-- /wp:image -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"col-md-8"} -->
            <div class="wp-block-column col-md-8">
                <!-- wp:heading {"level":3,"className":"h4 text-campus-navy mb-3"} -->
                <h3 class="wp-block-heading h4 text-campus-navy mb-3">Makna Lambang Universitas</h3>
                <!-- /wp:heading -->
                <!-- wp:list {"ordered":true} -->
                <ol><!-- wp:list-item -->
                <li><strong>Bingkai Segi Lima</strong>: Melambangkan falsafah negara Pancasila.</li>
                <!-- /wp:list-item -->
                <!-- wp:list-item -->
                <li><strong>Warna Hijau &amp; Emas</strong>: Melambangkan kejayaan akademik dan kesuburan budi pekerti.</li>
                <!-- /wp:list-item -->
                <!-- wp:list-item -->
                <li><strong>Bintang Bersinar</strong>: Melambangkan ketuhanan dan cita-cita luhur kemanusiaan.</li>
                <!-- /wp:list-item --></ol>
                <!-- /wp:list -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );
}
add_action( 'init', 'educampus_register_block_patterns' );
