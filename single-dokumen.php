<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$hero_image = get_theme_mod( 'educampus_default_hero_image', '' );
educampus_page_hero( array(
    'title' => get_the_title(),
    'badge' => 'DOKUMEN',
    'image' => $hero_image,
    'content' => '',
) );
?>
<div class="container py-5">
    <?php educampus_breadcrumbs(); ?>

    <?php while ( have_posts() ) : the_post();
        $file_url = get_post_meta( get_the_ID(), '_dokumen_file', true );
        $ext = $file_url ? strtolower( pathinfo( $file_url, PATHINFO_EXTENSION ) ) : '';
        $views = (int) get_post_meta( get_the_ID(), '_dokumen_view_count', true );
        $downloads = (int) get_post_meta( get_the_ID(), '_dokumen_download_count', true );
        $terms = get_the_terms( get_the_ID(), 'dokumen_category' );
    ?>
    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-campus-soft rounded-campus bg-white overflow-hidden">
                <?php if ( $file_url && in_array( $ext, array( 'pdf' ), true ) ) : ?>
                <div class="dokumen-viewer" style="height:75vh;min-height:400px;">
                    <iframe src="<?php echo esc_url( $file_url ); ?>" class="w-100 h-100 border-0" title="<?php the_title_attribute(); ?>"></iframe>
                </div>
                <?php elseif ( $file_url ) : ?>
                <div class="dokumen-viewer-placeholder text-center py-5 bg-light">
                    <i class="bi bi-file-earmark-text fs-1 text-campus-muted d-block mb-3"></i>
                    <p class="h5 font-heading text-campus-navy mb-2"><?php esc_html_e( 'Pratinjau tidak tersedia', 'educampus' ); ?></p>
                    <p class="small text-campus-muted mb-0"><?php esc_html_e( 'Klik tombol download untuk membuka file.', 'educampus' ); ?></p>
                </div>
                <?php else : ?>
                <div class="dokumen-viewer-placeholder text-center py-5 bg-light">
                    <i class="bi bi-file-earmark-x fs-1 text-campus-muted d-block mb-3"></i>
                    <p class="h5 font-heading text-campus-navy mb-2"><?php esc_html_e( 'File tidak tersedia', 'educampus' ); ?></p>
                </div>
                <?php endif; ?>

                <div class="card-body p-4 p-md-5 section-reveal">
                    <div class="d-flex flex-wrap gap-3 small text-campus-muted mb-4 pb-3 border-bottom border-light">
                        <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                        <span>
                            <i class="bi bi-folder me-1"></i>
                            <?php foreach ( $terms as $i => $term ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="text-decoration-none text-reset"><?php echo esc_html( $term->name ); ?></a><?php echo $i < count( $terms ) - 1 ? ', ' : ''; ?>
                            <?php endforeach; ?>
                        </span>
                        <?php endif; ?>
                        <span><i class="bi bi-calendar3 me-1"></i> <?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
                        <span><i class="bi bi-eye me-1"></i> <?php echo number_format_i18n( $views ); ?> <?php esc_html_e( 'dilihat', 'educampus' ); ?></span>
                        <span><i class="bi bi-download me-1"></i> <?php echo number_format_i18n( $downloads ); ?> <?php esc_html_e( 'diunduh', 'educampus' ); ?></span>
                        <?php if ( $ext ) : ?>
                        <span class="badge bg-campus-light text-campus-navy text-uppercase"><?php echo esc_html( $ext ); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-campus-soft rounded-campus bg-white mb-4 section-reveal-right">
                <div class="card-body p-4">
                    <h4 class="font-heading fw-bold text-campus-navy mb-3 small text-uppercase" style="letter-spacing:1px;"><?php esc_html_e( 'Informasi File', 'educampus' ); ?></h4>
                    <ul class="list-unstyled mb-0 small">
                        <li class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-campus-muted"><?php esc_html_e( 'Tanggal Terbit', 'educampus' ); ?></span>
                            <span class="fw-medium text-campus-navy text-end"><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
                        </li>
                        <?php if ( $file_url ) : ?>
                        <li class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-campus-muted"><?php esc_html_e( 'Nama File', 'educampus' ); ?></span>
                            <span class="fw-medium text-campus-navy text-end small"><?php echo esc_html( basename( $file_url ) ); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-campus-muted"><?php esc_html_e( 'Tipe', 'educampus' ); ?></span>
                            <span class="fw-medium text-campus-navy text-uppercase"><?php echo esc_html( $ext ?: '-' ); ?></span>
                        </li>
                        <?php endif; ?>
                        <li class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span class="text-campus-muted"><?php esc_html_e( 'Dilihat', 'educampus' ); ?></span>
                            <span class="fw-medium text-campus-navy"><?php echo number_format_i18n( $views ); ?>x</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-campus-muted"><?php esc_html_e( 'Diunduh', 'educampus' ); ?></span>
                            <span class="fw-medium text-campus-navy"><?php echo number_format_i18n( $downloads ); ?>x</span>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if ( $file_url ) : ?>
            <div class="card border-0 shadow-campus-soft rounded-campus bg-white section-reveal-right">
                <div class="card-body p-4 d-grid gap-2">
                    <a href="<?php echo esc_url( add_query_arg( 'download_dokumen', get_the_ID(), home_url() ) ); ?>" class="btn btn-campus-primary">
                        <i class="bi bi-download me-2"></i> <?php esc_html_e( 'Download File', 'educampus' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" class="btn btn-outline-campus-primary">
                        <i class="bi bi-box-arrow-up-right me-2"></i> <?php esc_html_e( 'Buka di Tab Baru', 'educampus' ); ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
