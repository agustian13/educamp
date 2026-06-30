<!-- Section 2: Statistics -->
<?php if ( get_theme_mod( 'educampus_show_stats', true ) ) : ?>
<section id="statistics" class="py-4 position-relative" style="margin-top: -50px; z-index: 2;">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php
            $stats_style = get_theme_mod( 'educampus_stats_style', 'card' );
            $card_class = ( $stats_style === 'bordered' ) ? 'border border-light p-3 p-lg-4 text-center bg-white h-100 rounded-campus' : 'card border-0 shadow-campus-soft rounded-campus p-3 p-lg-4 text-center bg-white h-100 transform-hover';
            
            $stats = array(
                array(
                    'icon'  => get_theme_mod( 'educampus_home_stat1_icon', 'bi-people-fill' ),
                    'num'   => get_theme_mod( 'educampus_home_stat1_num', '15.000+' ),
                    'label' => get_theme_mod( 'educampus_home_stat1_label', 'Mahasiswa Aktif' )
                ),
                array(
                    'icon'  => get_theme_mod( 'educampus_home_stat2_icon', 'bi-mortarboard-fill' ),
                    'num'   => get_theme_mod( 'educampus_home_stat2_num', '45.000+' ),
                    'label' => get_theme_mod( 'educampus_home_stat2_label', 'Alumni Sukses' )
                ),
                array(
                    'icon'  => get_theme_mod( 'educampus_home_stat3_icon', 'bi-person-badge-fill' ),
                    'num'   => get_theme_mod( 'educampus_home_stat3_num', '350+' ),
                    'label' => get_theme_mod( 'educampus_home_stat3_label', 'Dosen & Profesor' )
                ),
                array(
                    'icon'  => get_theme_mod( 'educampus_home_stat4_icon', 'bi-grid-fill' ),
                    'num'   => get_theme_mod( 'educampus_home_stat4_num', '42' ),
                    'label' => get_theme_mod( 'educampus_home_stat4_label', 'Program Studi' )
                ),
            );
            foreach ($stats as $stat) :
            ?>
            <div class="col-6 col-md-3 col-lg-3">
                <div class="<?php echo esc_attr( $card_class ); ?>">
                    <div class="d-inline-flex align-items-center justify-content-center bg-campus-gold bg-opacity-10 text-campus-gold rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="bi <?php echo esc_attr( $stat['icon'] ); ?> fs-3"></i>
                    </div>
                    <h3 class="display-6 font-heading fw-bold text-campus-navy mb-1 counter-value" data-target="<?php echo esc_attr($stat['num']); ?>">
                        <?php echo esc_html( $stat['num'] ); ?>
                    </h3>
                    <span class="text-campus-muted small font-heading fw-500 text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                        <?php echo esc_html( $stat['label'] ); ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>