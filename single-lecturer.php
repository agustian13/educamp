<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();

        $sister_id = get_post_meta( $post_id, '_lecturer_sister_id', true );
        $show_dummy = empty( $sister_id );

        $position           = get_post_meta( $post_id, '_lecturer_position', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['position'] : '' );
        $jabatan_fungsional = get_post_meta( $post_id, '_lecturer_jabatan_fungsional', true );
        $nidn               = get_post_meta( $post_id, '_lecturer_nidn', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['nidn'] : '' );
        $email    = get_post_meta( $post_id, '_lecturer_email', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['email'] : '' );
        $address  = get_post_meta( $post_id, '_lecturer_address', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['address'] : '' );

        $education_text = get_post_meta( $post_id, '_lecturer_education', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['education_text'] : '' );
        $expertise_text = get_post_meta( $post_id, '_lecturer_expertise', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['expertise_text'] : '' );
        $research_text  = get_post_meta( $post_id, '_lecturer_research', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['research_text'] : '' );
        $publications_text = get_post_meta( $post_id, '_lecturer_publications', true ) ?: ( $show_dummy ? educampus_lecturer_dummy_data()['publications_text'] : '' );

        $education_lines = array_filter( explode( "\n", $education_text ) );
        $expertise_lines = array_filter( explode( "\n", $expertise_text ) );
        $research_lines  = array_filter( explode( "\n", $research_text ) );
        $publication_lines = array_filter( explode( "\n", $publications_text ) );

        $research_latest     = array_slice( $research_lines, 0, 5 );
        $publication_latest  = array_slice( $publication_lines, 0, 5 );

        if ( ! function_exists( '_fmt_tanggal' ) ) {
            function _fmt_tanggal( $d ) {
                if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $d ) ) return $d;
                $b = [ '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ];
                $p = explode( '-', $d );
                return (int) $p[2] . ' ' . $b[ (int) $p[1] ] . ' ' . $p[0];
            }
        }

        $prog_id = get_post_meta( $post_id, '_lecturer_program_id', true );
        $prog_name = $prog_id ? get_the_title( $prog_id ) : '';

        // Collect all abbreviated gelar from every pendidikan line
        $gelar_list = array();
        $saved_gelar = get_post_meta( $post_id, '_lecturer_gelar', true );
        if ( ! empty( $saved_gelar ) ) {
            $gelar_list = array_map( 'trim', explode( ',', $saved_gelar ) );
        } else {
            foreach ( $education_lines as $line ) {
                $parts = explode( '|', $line );
                $deg_part = trim( $parts[0] ?? '' );
                if ( preg_match( '/^([^\s(]+)/', $deg_part, $m ) ) {
                    $abbr = $m[1];
                    if ( ! empty( $abbr ) && strlen( $abbr ) < 30 ) {
                        $gelar_list[] = $abbr;
                    }
                }
            }
        }
        $front_map = array( 'dr' => 'Dr.', 'prof' => 'Prof.', 'drs' => 'Drs.', 'dra' => 'Dra.', 'ir' => 'Ir.', 'kh' => 'KH.' );
        $front_list = array();
        $back_list = array();
        foreach ( $gelar_list as $g ) {
            $key = strtolower( preg_replace( '/\.$/', '', $g ) );
            if ( isset( $front_map[ $key ] ) ) {
                $front_list[] = $front_map[ $key ];
            } else {
                $back_list[] = $g;
            }
        }
        $display_name = '';
        if ( ! empty( $front_list ) ) {
            $display_name .= implode( ' ', $front_list ) . ' ';
        }
        $display_name .= get_the_title();
        if ( ! empty( $back_list ) ) {
            $display_name .= ', ' . implode( ', ', $back_list );
        }

        // Avatar
        if ( has_post_thumbnail() ) {
            $img_id = get_post_thumbnail_id( $post_id );
            $img_html = wp_get_attachment_image( $img_id, 'campus-square', false, array(
                'class'   => 'w-100 h-100 object-fit-cover',
                'loading' => 'lazy',
            ) );
            $avatar = '<div class="rounded-campus overflow-hidden border border-3 border-campus-gold shadow-campus-soft mx-auto" style="width:130px;height:130px;">' . $img_html . '</div>';
        } else {
            $avatar = '<div class="bg-campus-navy text-campus-gold rounded-campus d-flex align-items-center justify-content-center mx-auto border border-3 border-campus-gold shadow-campus-soft" style="width:130px;height:130px;"><i class="bi bi-person-fill display-5"></i></div>';
        }

        // Counts
        $edu_count   = count( $education_lines );
        $exp_count   = count( $expertise_lines );
        $res_count   = count( $research_lines );
        $pub_count   = count( $publication_lines );
        $total_stats = $edu_count + $exp_count + $res_count + $pub_count;
    ?>
    <?php educampus_page_hero( array( 'title' => $display_name ) ); ?>

    <div class="container my-4 pb-4">
        <?php educampus_breadcrumbs(); ?>

        <!-- Identity Card -->
        <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 mb-3 section-reveal">
            <div class="row align-items-center g-3">
                <div class="col-lg-auto text-center">
                    <?php echo $avatar; ?>
                </div>
                <div class="col-lg text-center text-lg-start">
                    <h1 class="h4 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( $display_name ); ?></h1>
                    <div class="d-flex flex-wrap gap-1 mb-1 justify-content-center justify-content-lg-start">
                        <?php if ( $jabatan_fungsional ) : ?>
                        <span class="badge bg-campus-gold text-campus-navy font-heading fw-bold px-2 py-0 rounded-pill" style="font-size:0.7rem;"><?php echo esc_html( $jabatan_fungsional ); ?></span>
                        <?php endif; ?>
                        <?php if ( $position ) : ?>
                        <span class="badge bg-campus-navy text-white font-heading fw-bold px-2 py-0 rounded-pill" style="font-size:0.7rem;"><?php echo esc_html( $position ); ?></span>
                        <?php endif; ?>
                        <?php if ( $prog_name ) : ?>
                        <span class="badge bg-white text-campus-navy border border-campus-navy font-heading fw-bold px-2 py-0 rounded-pill" style="font-size:0.7rem;"><?php echo esc_html( $prog_name ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start text-campus-muted" style="font-size:0.78rem;">
                        <?php if ( $nidn ) : ?>
                        <span><i class="bi bi-person-vcard-fill text-campus-gold me-1"></i> NIDN: <?php echo esc_html( $nidn ); ?></span>
                        <?php endif; ?>
                        <?php if ( $email ) : ?>
                        <span><i class="bi bi-envelope-fill text-campus-gold me-1"></i> <?php echo esc_html( $email ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-7">
                <?php if ( $edu_count > 0 ) : ?>
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 mb-3 section-reveal">
                    <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-2"><i class="bi bi-mortarboard-fill text-campus-gold me-2"></i> <?php esc_html_e( 'Riwayat Pendidikan', 'educampus' ); ?></h3>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <?php foreach ( $education_lines as $line ) :
                            $parts = array_map( 'trim', explode( '|', $line ) );
                        ?>
                        <li class="position-relative ps-3 border-start border-campus-gold border-2">
                            <strong class="d-block text-campus-navy mb-1"><?php echo esc_html( $parts[0] ?? $line ); ?></strong>
                            <?php if ( isset( $parts[1] ) ) : ?>
                            <span class="text-campus-muted d-block"><?php echo esc_html( $parts[1] ); if ( isset( $parts[2] ) ) echo ' &mdash; ' . esc_html( $parts[2] ); ?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if ( $exp_count > 0 ) : ?>
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 mb-3 section-reveal">
                    <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-2"><i class="bi bi-patch-check-fill text-campus-gold me-2"></i> <?php esc_html_e( 'Bidang Keahlian', 'educampus' ); ?></h3>
                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <?php foreach ( $expertise_lines as $skill ) : ?>
                        <span class="badge bg-light text-campus-navy font-heading border py-2 px-3 rounded-pill small"><?php echo esc_html( trim( $skill ) ); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 mb-3 section-reveal">
                    <h3 class="h6 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-2"><i class="bi bi-geo-alt-fill text-campus-gold me-2"></i> <?php esc_html_e( 'Alamat', 'educampus' ); ?></h3>
                    <?php if ( $address ) : ?>
                    <address class="text-campus-muted small mb-0" style="white-space:pre-line;"><?php echo esc_html( $address ); ?></address>
                    <?php else : ?>
                    <p class="text-campus-muted small fst-italic mb-0"><?php esc_html_e( 'Belum ada data alamat.', 'educampus' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 section-reveal h-100">
                    <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-2"><i class="bi bi-search text-campus-gold me-2"></i> <?php esc_html_e( 'Penelitian Terbaru', 'educampus' ); ?></h3>
                    <?php if ( $res_count > 0 ) : ?>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <?php foreach ( $research_latest as $line ) :
                            $parts = array_map( 'trim', explode( '|', $line ) );
                        ?>
                        <li class="pb-2 border-bottom">
                            <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( $parts[0] ?? $line ); ?></h4>
                            <?php if ( isset( $parts[1] ) || isset( $parts[2] ) ) : ?>
                            <span class="text-campus-gold small d-block mb-1">
                                <?php echo isset( $parts[1] ) ? esc_html( $parts[1] ) : ''; ?>
                                <?php echo isset( $parts[2] ) ? ' &bull; ' . esc_html( $parts[2] ) : ''; ?>
                            </span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <p class="text-campus-muted small fst-italic mb-0"><?php esc_html_e( 'Belum ada data penelitian.', 'educampus' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-3 section-reveal h-100">
                    <h3 class="h5 font-heading fw-bold text-campus-navy border-bottom pb-2 mb-2"><i class="bi bi-journal-text text-campus-gold me-2"></i> <?php esc_html_e( 'Publikasi Ilmiah Terpilih', 'educampus' ); ?></h3>
                    <?php if ( $pub_count > 0 ) : ?>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <?php foreach ( $publication_latest as $line ) :
                            $parts = array_map( 'trim', explode( '|', $line ) );
                        ?>
                        <li class="pb-2 border-bottom">
                            <h4 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php echo esc_html( $parts[0] ?? $line ); ?></h4>
                            <?php if ( isset( $parts[1] ) || isset( $parts[2] ) ) : ?>
                            <span class="text-campus-gold small d-block mb-1">
                                <?php echo isset( $parts[1] ) ? esc_html( $parts[1] ) : ''; ?>
                                <?php echo isset( $parts[2] ) ? ' &bull; ' . esc_html( _fmt_tanggal( $parts[2] ) ) : ''; ?>
                            </span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <p class="text-campus-muted small fst-italic mb-0"><?php esc_html_e( 'Belum ada data publikasi.', 'educampus' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php
    endwhile;
else :
?>
    <div class="container my-4 py-4 text-center">
        <h1 class="h2"><?php esc_html_e( 'Dosen Tidak Ditemukan', 'educampus' ); ?></h1>
        <p class="text-campus-muted"><?php esc_html_e( 'Profil akademisi sedang diperbarui.', 'educampus' ); ?></p>
    </div>
<?php
endif;
get_footer();