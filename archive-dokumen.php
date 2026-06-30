<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$hero_image = get_theme_mod( 'educampus_default_hero_image', '' );
educampus_page_hero( array(
    'title' => get_theme_mod( 'educampus_archive_dokumen_title', 'Dokumen' ),
    'badge' => get_theme_mod( 'educampus_archive_dokumen_badge', 'REFERENSI AKADEMIK' ),
    'image' => $hero_image,
    'content' => '',
) );
?>
<style>
.doc-bg-icons { position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;overflow:hidden;z-index:0; }
.doc-bg-icon {
    position:absolute;
    left:var(--x);
    top:var(--y);
    font-size:var(--size);
    color:var(--color-primary,#0a2540);
    opacity:var(--opacity,0.06);
    animation:docFloat var(--dur) ease-in-out var(--delay) infinite alternate;
    will-change:transform;
}
@keyframes docFloat {
    0%   { transform:translate(0,0) rotate(0deg) scale(1); }
    25%  { transform:translate(10px,-14px) rotate(6deg) scale(1.04); }
    50%  { transform:translate(-6px,12px) rotate(-4deg) scale(0.97); }
    75%  { transform:translate(14px,5px) rotate(8deg) scale(1.02); }
    100% { transform:translate(-3px,-8px) rotate(-2deg) scale(1); }
}
</style>

<section class="position-relative overflow-hidden" style="background:linear-gradient(135deg,#f8f9fa 0%,#f0f4f8 50%,#e8f0fe 100%);">
    <div class="doc-bg-icons" aria-hidden="true">
        <div class="doc-bg-icon" style="--x:5%;--y:12%;--dur:20s;--delay:0s;--size:2rem;--opacity:.07;"><i class="bi bi-file-earmark-text"></i></div>
        <div class="doc-bg-icon" style="--x:18%;--y:55%;--dur:24s;--delay:2s;--size:1.7rem;--opacity:.05;"><i class="bi bi-filetype-pdf"></i></div>
        <div class="doc-bg-icon" style="--x:42%;--y:8%;--dur:22s;--delay:4s;--size:2.3rem;--opacity:.06;"><i class="bi bi-folder-fill"></i></div>
        <div class="doc-bg-icon" style="--x:70%;--y:60%;--dur:26s;--delay:1s;--size:1.9rem;--opacity:.05;"><i class="bi bi-download"></i></div>
        <div class="doc-bg-icon" style="--x:88%;--y:20%;--dur:18s;--delay:3s;--size:1.5rem;--opacity:.07;"><i class="bi bi-search"></i></div>
        <div class="doc-bg-icon" style="--x:30%;--y:75%;--dur:25s;--delay:5s;--size:2.1rem;--opacity:.05;"><i class="bi bi-file-earmark-zip"></i></div>
        <div class="doc-bg-icon" style="--x:80%;--y:40%;--dur:19s;--delay:2.5s;--size:1.8rem;--opacity:.06;"><i class="bi bi-journal-text"></i></div>
        <div class="doc-bg-icon" style="--x:10%;--y:38%;--dur:23s;--delay:6s;--size:2rem;--opacity:.05;"><i class="bi bi-book"></i></div>
        <div class="doc-bg-icon" style="--x:55%;--y:25%;--dur:21s;--delay:1.5s;--size:1.6rem;--opacity:.06;"><i class="bi bi-filetype-docx"></i></div>
        <div class="doc-bg-icon" style="--x:95%;--y:70%;--dur:27s;--delay:4.5s;--size:1.8rem;--opacity:.04;"><i class="bi bi-filetype-xlsx"></i></div>
        <div class="doc-bg-icon" style="--x:38%;--y:45%;--dur:17s;--delay:7s;--size:1.4rem;--opacity:.07;"><i class="bi bi-printer"></i></div>
        <div class="doc-bg-icon" style="--x:62%;--y:82%;--dur:28s;--delay:0.5s;--size:2.2rem;--opacity:.04;"><i class="bi bi-file-earmark-arrow-up"></i></div>
    </div>

    <div class="container py-5 position-relative" style="z-index:1;">
        <!-- Custom Breadcrumbs for Dokumen (always consistent) -->
        <nav aria-label="breadcrumb" class="my-3">
            <ol class="breadcrumb mb-0 bg-transparent p-0 align-items-center">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-campus-navy text-decoration-none"><i class="bi bi-house-door-fill text-campus-gold"></i> <?php esc_html_e( 'Home', 'educampus' ); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'dokumen' ) ); ?>" class="text-campus-navy text-decoration-none"><?php esc_html_e( 'Dokumen', 'educampus' ); ?></a></li>
                <?php if ( is_tax( 'dokumen_category' ) ) : ?>
                    <li class="breadcrumb-item active text-campus-muted" aria-current="page"><?php echo esc_html( single_term_title( '', false ) ); ?></li>
                <?php else : ?>
                    <li class="breadcrumb-item active text-campus-muted" aria-current="page"><?php esc_html_e( 'Semua Dokumen', 'educampus' ); ?></li>
                <?php endif; ?>
            </ol>
        </nav>

        <!-- Search & Toolbar -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-4 toolbar-dokumen">
            <form role="search" method="get" class="search-form d-flex align-items-center flex-grow-1 toolbar-search" action="<?php echo esc_url( get_post_type_archive_link( 'dokumen' ) ); ?>">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-campus-muted px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control border-start-0 shadow-none ps-0" placeholder="<?php esc_attr_e( 'Cari dokumen...', 'educampus' ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
                    <input type="hidden" name="post_type" value="dokumen" />
                    <button type="submit" class="btn btn-campus-primary btn-sm px-3"><?php esc_html_e( 'Cari', 'educampus' ); ?></button>
                </div>
            </form>

            <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-2 flex-shrink-0">
                <span class="small text-campus-muted toolbar-view-label"><?php esc_html_e( 'Tampilan:', 'educampus' ); ?></span>
                <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                    <button type="button" class="btn btn-outline-campus-primary dokumen-view-btn active" data-view="list" title="List View">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="btn btn-outline-campus-primary dokumen-view-btn" data-view="grid" title="Grid View">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <?php
        $terms = get_terms( array( 'taxonomy' => 'dokumen_category', 'hide_empty' => true ) );
        $current_cat = get_query_var( 'dokumen_category' );
        ?>
        <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
        <div class="mb-4 dokumen-cat-filter">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'dokumen' ) ); ?>" class="btn btn-sm <?php echo empty( $current_cat ) ? 'btn-campus-primary' : 'btn-outline-campus-primary'; ?> rounded-pill"><?php esc_html_e( 'Semua', 'educampus' ); ?></a>
                <?php foreach ( $terms as $term ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="btn btn-sm <?php echo $current_cat === $term->slug ? 'btn-campus-primary' : 'btn-outline-campus-primary'; ?> rounded-pill"><?php echo esc_html( $term->name ); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>

        <!-- Grid View -->
        <div class="dokumen-view-grid">
            <div class="row g-4">
                <?php while ( have_posts() ) : the_post();
                    $file_url = get_post_meta( get_the_ID(), '_dokumen_file', true );
                    $ext = $file_url ? strtolower( pathinfo( $file_url, PATHINFO_EXTENSION ) ) : '';
                    $views = (int) get_post_meta( get_the_ID(), '_dokumen_view_count', true );
                    $downloads = (int) get_post_meta( get_the_ID(), '_dokumen_download_count', true );
                    $doc_terms = get_the_terms( get_the_ID(), 'dokumen_category' );

                    $icon_map = array( 'pdf' => 'bi-filetype-pdf', 'doc' => 'bi-filetype-docx', 'docx' => 'bi-filetype-docx', 'xls' => 'bi-filetype-xlsx', 'xlsx' => 'bi-filetype-xlsx', 'ppt' => 'bi-filetype-pptx', 'pptx' => 'bi-filetype-pptx', 'jpg' => 'bi-filetype-jpg', 'jpeg' => 'bi-filetype-jpg', 'png' => 'bi-filetype-png' );
                    $icon = isset( $icon_map[ $ext ] ) ? $icon_map[ $ext ] : 'bi-file-earmark';
                    $color_map = array( 'pdf' => '#dc2626', 'doc' => '#2563eb', 'docx' => '#2563eb', 'xls' => '#16a34a', 'xlsx' => '#16a34a', 'ppt' => '#ea580c', 'pptx' => '#ea580c', 'jpg' => '#7c3aed', 'jpeg' => '#7c3aed', 'png' => '#7c3aed' );
                    $icon_color = isset( $color_map[ $ext ] ) ? $color_map[ $ext ] : '#6b7280';
                ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="dokumen-grid-card section-reveal">
                        <div class="dokumen-grid-icon d-flex align-items-center justify-content-center mx-auto rounded-3 mb-3" style="width:80px;height:100px;background:linear-gradient(135deg,<?php echo esc_attr( $icon_color ); ?>15,<?php echo esc_attr( $icon_color ); ?>08);border:1px solid <?php echo esc_attr( $icon_color ); ?>20;">
                            <i class="bi <?php echo esc_attr( $icon ); ?>" style="font-size:2.5rem;color:<?php echo esc_attr( $icon_color ); ?>;"></i>
                        </div>
                        <h3 class="h6 font-heading fw-bold text-campus-navy text-center mb-1">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-reset stretched-link"><?php the_title(); ?></a>
                        </h3>
                        <?php if ( has_excerpt() ) : ?>
                            <p class="small text-campus-muted text-center mb-2 line-clamp-2"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-center gap-2 small text-campus-muted mb-2">
                            <span><i class="bi bi-calendar3 me-1"></i> <?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
                            <?php if ( $ext ) : ?>
                                <span class="badge border text-uppercase" style="font-size:0.6rem;border-color:<?php echo esc_attr( $icon_color ); ?>40 !important;color:<?php echo esc_attr( $icon_color ); ?>;background:<?php echo esc_attr( $icon_color ); ?>08;"><?php echo esc_html( $ext ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 small text-campus-muted">
                            <span><i class="bi bi-eye me-1"></i> <?php echo number_format_i18n( $views ); ?></span>
                            <span><i class="bi bi-download me-1"></i> <?php echo number_format_i18n( $downloads ); ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- List View -->
        <div class="dokumen-view-list" style="display:none;">
            <?php rewind_posts(); while ( have_posts() ) : the_post();
                $file_url = get_post_meta( get_the_ID(), '_dokumen_file', true );
                $ext = $file_url ? strtolower( pathinfo( $file_url, PATHINFO_EXTENSION ) ) : '';
                $views = (int) get_post_meta( get_the_ID(), '_dokumen_view_count', true );
                $downloads = (int) get_post_meta( get_the_ID(), '_dokumen_download_count', true );
                $doc_terms = get_the_terms( get_the_ID(), 'dokumen_category' );

                $icon_map = array( 'pdf' => 'bi-filetype-pdf', 'doc' => 'bi-filetype-docx', 'docx' => 'bi-filetype-docx', 'xls' => 'bi-filetype-xlsx', 'xlsx' => 'bi-filetype-xlsx', 'ppt' => 'bi-filetype-pptx', 'pptx' => 'bi-filetype-pptx', 'jpg' => 'bi-filetype-jpg', 'jpeg' => 'bi-filetype-jpg', 'png' => 'bi-filetype-png' );
                $icon = isset( $icon_map[ $ext ] ) ? $icon_map[ $ext ] : 'bi-file-earmark';
                $color_map = array( 'pdf' => '#dc2626', 'doc' => '#2563eb', 'docx' => '#2563eb', 'xls' => '#16a34a', 'xlsx' => '#16a34a', 'ppt' => '#ea580c', 'pptx' => '#ea580c', 'jpg' => '#7c3aed', 'jpeg' => '#7c3aed', 'png' => '#7c3aed' );
                $icon_color = isset( $color_map[ $ext ] ) ? $color_map[ $ext ] : '#6b7280';
            ?>
            <div class="dokumen-item d-flex align-items-start gap-4 py-4 border-bottom border-light section-reveal">
                <div class="dokumen-icon flex-shrink-0 d-flex align-items-center justify-content-center rounded-3" style="width:72px;height:90px;background:linear-gradient(135deg,<?php echo esc_attr( $icon_color ); ?>15,<?php echo esc_attr( $icon_color ); ?>08);border:1px solid <?php echo esc_attr( $icon_color ); ?>20;">
                    <i class="bi <?php echo esc_attr( $icon ); ?>" style="font-size:2.25rem;color:<?php echo esc_attr( $icon_color ); ?>;"></i>
                </div>
                <div class="dokumen-info flex-grow-1 min-w-0">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h3 class="h5 font-heading fw-bold text-campus-navy mb-0">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-reset"><?php the_title(); ?></a>
                        </h3>
                        <?php if ( $ext ) : ?>
                            <span class="badge border text-uppercase" style="font-size:0.65rem;border-color:<?php echo esc_attr( $icon_color ); ?>40 !important;color:<?php echo esc_attr( $icon_color ); ?>;background:<?php echo esc_attr( $icon_color ); ?>08;"><?php echo esc_html( $ext ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="small text-campus-muted mb-2"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-wrap align-items-center gap-3 small text-campus-muted">
                        <span><i class="bi bi-calendar3 me-1"></i> <?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
                        <?php if ( ! empty( $doc_terms ) && ! is_wp_error( $doc_terms ) ) : ?>
                            <span><i class="bi bi-folder me-1"></i> <?php echo esc_html( join( ', ', wp_list_pluck( $doc_terms, 'name' ) ) ); ?></span>
                        <?php endif; ?>
                        <span><i class="bi bi-eye me-1"></i> <?php echo number_format_i18n( $views ); ?></span>
                        <span><i class="bi bi-download me-1"></i> <?php echo number_format_i18n( $downloads ); ?></span>
                    </div>
                </div>
                <?php if ( $file_url ) : ?>
                <div class="dokumen-actions flex-shrink-0 d-none d-md-flex align-items-center gap-2">
                    <a href="<?php echo esc_url( add_query_arg( 'download_dokumen', get_the_ID(), home_url() ) ); ?>" class="btn btn-sm btn-campus-primary" title="<?php esc_attr_e( 'Download', 'educampus' ); ?>"><i class="bi bi-download"></i></a>
                    <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" class="btn btn-sm btn-outline-campus-primary" title="<?php esc_attr_e( 'Buka', 'educampus' ); ?>"><i class="bi bi-box-arrow-up-right"></i></a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="mt-5">
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '<i class="bi bi-chevron-left"></i>',
                'next_text' => '<i class="bi bi-chevron-right"></i>',
            ) ); ?>
        </div>

        <?php else : ?>
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text fs-1 text-campus-muted d-block mb-3"></i>
                <p class="text-campus-muted"><?php esc_html_e( 'Belum ada dokumen tersedia.', 'educampus' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function() {
    var btns = document.querySelectorAll('.dokumen-view-btn');
    var gridView = document.querySelector('.dokumen-view-grid');
    var listView = document.querySelector('.dokumen-view-list');
    var stored = localStorage.getItem('dokumen_view') || 'list';

    function setView(view) {
        btns.forEach(function(b) {
            b.classList.toggle('active', b.dataset.view === view);
        });
        if (gridView) gridView.style.display = view === 'grid' ? '' : 'none';
        if (listView) listView.style.display = view === 'list' ? '' : 'none';
        localStorage.setItem('dokumen_view', view);
    }

    btns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setView(this.dataset.view);
        });
    });

    setView(stored);
})();
</script>

<?php get_footer(); ?>
