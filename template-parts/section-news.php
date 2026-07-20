<!-- Section 6: News -->
<?php if ( get_theme_mod( 'educampus_show_news', true ) ) : ?>
<?php 
$news_ornament = '';
if ( get_theme_mod( 'educampus_enable_islamic_ornament', true ) && get_theme_mod( 'educampus_bg_ornament_news', true ) ) {
    $news_ornament = ' bg-islamic-ornament';
}
$news_bg_color = get_theme_mod( 'educampus_bg_color_news', '#f5f0e4' );
$news_bg_color_end = get_theme_mod( 'educampus_bg_color_end_news', $news_bg_color );
$news_bg_grad_dir = get_theme_mod( 'educampus_bg_grad_dir_news', '135deg' );
?>
<section id="news" class="section-padding<?php echo esc_attr($news_ornament); ?>" style="background: linear-gradient(<?php echo esc_attr($news_bg_grad_dir); ?>, <?php echo esc_attr($news_bg_color); ?>, <?php echo esc_attr($news_bg_color_end); ?>);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div>
                <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( get_theme_mod( 'educampus_heading_news_badge', 'KAMPUS TERKINI' ) ); ?></span>
                <h2 class="h1 font-heading fw-bold text-campus-navy mb-0"><?php echo esc_html( get_theme_mod( 'educampus_heading_news_title', 'Berita & Informasi Terbaru' ) ); ?></h2>
                <?php 
                $news_desc = get_theme_mod( 'educampus_heading_news_desc', '' );
                if ( ! empty( $news_desc ) ) : ?>
                    <p class="text-campus-muted mt-2 mb-0"><?php echo esc_html( $news_desc ); ?></p>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url( educampus_get_news_archive_url() ); ?>" class="btn btn-campus-primary font-heading px-3.5 py-1.5 mt-3 mt-md-0">
                <?php esc_html_e('Semua Berita', 'educampus'); ?> <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <?php
        $news_layout = get_theme_mod( 'educampus_news_layout', 'grid' );
        $news_limit  = get_theme_mod( 'educampus_news_limit', 3 );

        $news_query = new WP_Query( array(
            'post_type'      => array( 'news', 'post' ),
            'posts_per_page' => $news_limit,
        ) );

        $col_class = 'col-md-4';
        if ( $news_layout === 'list' ) {
            $col_class = 'col-12';
        } elseif ( $news_layout === 'overlay' ) {
            $col_class = 'col-md-4 col-sm-6';
        }

        $news_placeholder = array(
            array( 'title' => 'EduCampus Raih Akreditasi Institusi UNGGUL Nasional 2026', 'date' => '10 Juni 2026', 'desc' => 'BAN-PT resmi menetapkan peringkat akreditasi tertinggi untuk EduCampus berdasarkan evaluasi tata kelola.' ),
            array( 'title' => 'Mahasiswa Informatika Juara 1 Internasional Hackathon di Singapura', 'date' => '05 Juni 2026', 'desc' => 'Tim mahasiswa tingkat akhir menciptakan aplikasi berbasis AI untuk deteksi kebocoran karbon.' ),
            array( 'title' => 'Kerjasama Riset Joint Research dengan Arizona State University', 'date' => '28 Mei 2026', 'desc' => 'Delegasi EduCampus bertolak ke AS menandatangani nota kesepahaman pertukaran dosen dan beasiswa S3.' ),
            array( 'title' => 'Workshop Penulisan Jurnal Ilmiah Scopus & Sinta', 'date' => '20 Mei 2026', 'desc' => 'LPPM menyelenggarakan workshop penulisan artikel ilmiah bereputasi bagi seluruh dosen tetap.' ),
            array( 'title' => 'Pengabdian Masyarakat Terpadu di Desa Binaan Sukamaju', 'date' => '15 Mei 2026', 'desc' => 'Dosen dan mahasiswa berkolaborasi mengedukasi digitalisasi UMKM lokal desa.' ),
            array( 'title' => 'Kunjungan Industri Mahasiswa ke Kantor Google Indonesia', 'date' => '10 Mei 2026', 'desc' => 'Melihat langsung kultur kerja tech-giant global dan berdiskusi dengan engineering lead.' )
        );
        $news_placeholder = array_slice( $news_placeholder, 0, $news_limit );
        ?>

        <?php if ( $news_layout === 'slider' ) : ?>
            <!-- Slider Layout -->
            <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    <?php 
                    $slider_index = 0;
                    if ( $news_query->have_posts() ) :
                        while ( $news_query->have_posts() ) : $news_query->the_post();
                    ?>
                        <div class="carousel-item <?php echo $slider_index === 0 ? 'active' : ''; ?>">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white transform-hover d-flex flex-column flex-md-row">
                                        <div class="flex-shrink-0 col-md-5 p-0 overflow-hidden">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'w-100 h-100 object-fit-cover', 'style' => 'min-height: 250px;' ) ); ?>
                                            <?php else : ?>
                                                <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50" style="min-height: 250px;">
                                                    <i class="bi bi-newspaper fs-1 text-campus-gold"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 col-md-7 d-flex flex-column justify-content-center">
                                            <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo get_the_date(); ?></span>
                                            <h3 class="h4 font-heading fw-bold text-campus-navy mb-3">
                                                <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none stretched-link"><?php the_title(); ?></a>
                                            </h3>
                                            <p class="text-campus-muted small mb-4 position-relative" style="z-index:2;"><?php echo wp_trim_words( get_the_excerpt(), 25 ); ?></p>
                                            <span class="text-campus-gold text-decoration-none small fw-bold font-heading position-relative" style="z-index:2;pointer-events:none;">
                                                <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $slider_index++;
                        endwhile;
                        wp_reset_postdata();
                    else :
                        foreach ($news_placeholder as $idx => $n) :
                    ?>
                        <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white transform-hover d-flex flex-column flex-md-row">
                                        <div class="flex-shrink-0 col-md-5 p-0 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 overflow-hidden" style="min-height: 250px;">
                                            <i class="bi bi-newspaper fs-1 text-campus-gold"></i>
                                        </div>
                                        <div class="card-body p-4 col-md-7 d-flex flex-column justify-content-center">
                                            <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo $n['date']; ?></span>
                                            <h3 class="h4 font-heading fw-bold text-campus-navy mb-3">
                                                <a href="#" class="text-campus-navy text-decoration-none hover-secondary">
                                                    <?php echo $n['title']; ?>
                                                </a>
                                            </h3>
                                            <p class="text-campus-muted small mb-4"><?php echo $n['desc']; ?></p>
                                            <a href="#" class="text-campus-gold text-decoration-none small fw-bold font-heading">
                                                <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
                <!-- Navigation -->
                <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev" style="left: -50px; filter: invert(1);">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php esc_html_e('Previous', 'educampus'); ?></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next" style="right: -50px; filter: invert(1);">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php esc_html_e('Next', 'educampus'); ?></span>
                </button>
            </div>
        <?php elseif ( $news_layout === 'featured' ) : ?>
            <!-- Featured Layout: 1 Big Left + Small List Right -->
            <div class="row g-4">
                <?php
                $all_posts = array();
                if ( $news_query->have_posts() ) {
                    while ( $news_query->have_posts() ) {
                        $news_query->the_post();
                        $all_posts[] = array(
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'permalink' => get_permalink(),
                            'date' => get_the_date(),
                            'excerpt' => get_the_excerpt(),
                            'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'campus-medium', array( 'class' => 'w-100 h-100 object-fit-cover rounded-campus' ) ) : '',
                            'thumbnail_small' => has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'object-fit-cover rounded-3', 'style' => 'width: 80px; height: 80px;' ) ) : '',
                        );
                    }
                    wp_reset_postdata();
                } else {
                    foreach ( $news_placeholder as $n ) {
                        $all_posts[] = array(
                            'id' => 0,
                            'title' => $n['title'],
                            'permalink' => '#',
                            'date' => $n['date'],
                            'excerpt' => $n['desc'],
                            'thumbnail' => '<div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-campus min-h-250"><i class="bi bi-newspaper fs-1 text-campus-gold"></i></div>',
                            'thumbnail_small' => '<div class="bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3" style="width: 80px; height: 80px;"><i class="bi bi-newspaper fs-4 text-campus-gold"></i></div>',
                        );
                    }
                }
                
                $big_post = isset( $all_posts[0] ) ? $all_posts[0] : null;
                $small_posts = array_slice( $all_posts, 1 );
                ?>

                <?php if ( $big_post ) : ?>
                    <!-- Big Post (Left Side) -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 transform-hover d-flex flex-column">
                            <div class="ratio ratio-16x9">
                                <?php if ( !empty( $big_post['thumbnail'] ) && $big_post['id'] > 0 ) : ?>
                                    <?php echo $big_post['thumbnail']; ?>
                                <?php else : ?>
                                    <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                        <i class="bi bi-newspaper fs-1 text-campus-gold"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo esc_html( $big_post['date'] ); ?></span>
                                <h3 class="h3 font-heading fw-bold text-campus-navy mb-3">
                                    <a href="<?php echo esc_url( $big_post['permalink'] ); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                        <?php echo esc_html( $big_post['title'] ); ?>
                                    </a>
                                </h3>
                                <p class="text-campus-muted small mb-4 flex-grow-1"><?php echo wp_trim_words( $big_post['excerpt'], 25 ); ?></p>
                                <a href="<?php echo esc_url( $big_post['permalink'] ); ?>" class="btn btn-campus-primary btn-sm align-self-start font-heading">
                                    <?php esc_html_e('Baca Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Small List (Right Side) -->
                    <div class="col-lg-5">
                        <div class="d-flex flex-column gap-3 h-100 justify-content-between">
                            <?php 
                            if ( ! empty( $small_posts ) ) :
                                foreach ( $small_posts as $sp ) :
                            ?>
                                <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden p-3 bg-white transform-hover d-flex flex-row align-items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <?php if ( !empty( $sp['thumbnail_small'] ) && $sp['id'] > 0 ) : ?>
                                            <?php echo $sp['thumbnail_small']; ?>
                                        <?php else : ?>
                                            <div class="bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3" style="width: 80px; height: 80px;">
                                                <i class="bi bi-newspaper fs-4 text-campus-gold"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-campus-gold small font-heading fw-bold mb-1 d-inline-block"><?php echo esc_html( $sp['date'] ); ?></span>
                                        <h4 class="h6 font-heading fw-bold text-campus-navy mb-0 lh-base">
                                            <a href="<?php echo esc_url( $sp['permalink'] ); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                                <?php echo esc_html( $sp['title'] ); ?>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else :
                            ?>
                                <div class="text-center text-campus-muted p-4 bg-white rounded-campus shadow-campus-soft">
                                    <?php _e( 'Tidak ada berita tambahan.', 'educampus' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif ( $news_layout === 'magazine' ) : ?>
            <!-- Magazine Layout -->
            <div class="row g-4 mb-4">
                <?php
                $all_posts = array();
                if ( $news_query->have_posts() ) {
                    while ( $news_query->have_posts() ) {
                        $news_query->the_post();
                        $all_posts[] = array(
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'permalink' => get_permalink(),
                            'date' => get_the_date(),
                            'excerpt' => get_the_excerpt(),
                            'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'campus-medium', array( 'class' => 'w-100 h-100 object-fit-cover rounded-campus' ) ) : '',
                            'thumbnail_small' => has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'campus-square', array( 'class' => 'w-100 h-100 object-fit-cover rounded-campus' ) ) : '',
                        );
                    }
                    wp_reset_postdata();
                } else {
                    foreach ( $news_placeholder as $n ) {
                        $all_posts[] = array(
                            'id' => 0,
                            'title' => $n['title'],
                            'permalink' => '#',
                            'date' => $n['date'],
                            'excerpt' => $n['desc'],
                            'thumbnail' => '<div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-campus min-h-250"><i class="bi bi-newspaper fs-1 text-campus-gold"></i></div>',
                            'thumbnail_small' => '<div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-campus" style="min-height: 120px;"><i class="bi bi-newspaper fs-3 text-campus-gold"></i></div>',
                        );
                    }
                }
                
                $post_1 = isset( $all_posts[0] ) ? $all_posts[0] : null;
                $post_2 = isset( $all_posts[1] ) ? $all_posts[1] : null;
                $post_3 = isset( $all_posts[2] ) ? $all_posts[2] : null;
                $bottom_posts = array_slice( $all_posts, 3 );
                ?>

                <!-- Top Row -->
                <?php if ( $post_1 ) : ?>
                    <!-- 1. Large Overlay Card (Left) -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-dark text-white position-relative transform-hover h-100" style="min-height: 400px;">
                            <?php if ( $post_1['id'] > 0 ) : ?>
                                <div class="position-absolute top-0 start-0 w-100 h-100">
                                    <?php echo $post_1['thumbnail']; ?>
                                </div>
                            <?php else : ?>
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-success" style="opacity: 0.85;">
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white opacity-20">
                                        <i class="bi bi-newspaper display-1"></i>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(10,25,40,0.95) 0%, rgba(10,25,40,0.2) 100%); z-index: 1;"></div>
                            <div class="card-body p-4 position-relative d-flex flex-column justify-content-end h-100" style="z-index: 2;">
                                <span class="text-white-50 small font-heading fw-bold mb-2 d-inline-block"><?php echo esc_html( $post_1['date'] ); ?></span>
                                <h3 class="h3 font-heading fw-bold text-white mb-0">
                                    <a href="<?php echo esc_url( $post_1['permalink'] ); ?>" class="text-white text-decoration-none hover-secondary">
                                        <?php echo esc_html( $post_1['title'] ); ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $post_2 || $post_3 ) : ?>
                    <!-- 2 & 3. Regular Grid Cards (Right) -->
                    <div class="col-lg-6">
                        <div class="row g-4 h-100">
                            <?php if ( $post_2 ) : ?>
                                <div class="col-md-6 d-flex">
                                    <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover d-flex flex-column w-100">
                                        <div class="ratio ratio-16x9">
                                            <?php if ( $post_2['id'] > 0 ) : ?>
                                                <?php echo $post_2['thumbnail']; ?>
                                            <?php else : ?>
                                                <img loading="lazy" src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=400&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="EduCampus Slide" width="400" height="300">
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                            <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo esc_html( $post_2['date'] ); ?></span>
                                            <h4 class="h6 font-heading fw-bold text-campus-navy mb-0 lh-base flex-grow-1">
                                                <a href="<?php echo esc_url( $post_2['permalink'] ); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                                    <?php echo esc_html( $post_2['title'] ); ?>
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $post_3 ) : ?>
                                <div class="col-md-6 d-flex">
                                    <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover d-flex flex-column w-100">
                                        <div class="ratio ratio-16x9">
                                            <?php if ( $post_3['id'] > 0 ) : ?>
                                                <?php echo $post_3['thumbnail']; ?>
                                            <?php else : ?>
                                                <img loading="lazy" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=400&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="EduCampus Slide" width="400" height="300">
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                            <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo esc_html( $post_3['date'] ); ?></span>
                                            <h4 class="h6 font-heading fw-bold text-campus-navy mb-0 lh-base flex-grow-1">
                                                <a href="<?php echo esc_url( $post_3['permalink'] ); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                                    <?php echo esc_html( $post_3['title'] ); ?>
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bottom Row: 4 Small Grid Cards + View More -->
            <div class="row g-3">
                <?php 
                $bottom_count = 0;
                foreach ( $bottom_posts as $bp ) : 
                    $bottom_count++;
                    if ( $bottom_count > 4 ) break;
                ?>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white transform-hover h-100 d-flex flex-column">
                            <div class="ratio ratio-16x9">
                                <?php if ( $bp['id'] > 0 ) : ?>
                                    <?php echo $bp['thumbnail_small']; ?>
                                <?php else : ?>
                                    <?php
                                    $img_urls = array(
                                        1 => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=300&auto=format&fit=crop',
                                        2 => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=300&auto=format&fit=crop',
                                        3 => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=300&auto=format&fit=crop',
                                        4 => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=300&auto=format&fit=crop'
                                    );
                                    ?>
                                    <img loading="lazy" src="<?php echo esc_url( $img_urls[$bottom_count] ); ?>" class="w-100 h-100 object-fit-cover" alt="EduCampus news small" width="300" height="225">
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <h4 class="card-title font-heading fw-bold text-campus-navy mb-0 lh-base" style="font-size: 0.85rem;">
                                    <a href="<?php echo esc_url( $bp['permalink'] ); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                        <?php echo esc_html( $bp['title'] ); ?>
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- View More Card -->
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 transform-hover d-flex align-items-center justify-content-center p-3 text-center border-dashed" style="border: 2px dashed rgba(10,25,40,0.1) !important; min-height: 140px;">
                        <a href="<?php echo esc_url( educampus_get_news_archive_url() ); ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 text-success">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 mb-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-arrow-right fs-5"></i>
                            </div>
                            <span class="font-heading fw-bold small text-success"><?php esc_html_e('Lihat Berita Lainnya', 'educampus'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        <?php elseif ( $news_layout === 'cards-scroll' ) : ?>
            <div class="position-relative">
                <div class="row flex-nowrap overflow-auto pb-3 g-3" style="scroll-snap-type:x mandatory;scrollbar-width:thin;-webkit-overflow-scrolling:touch;">
                    <?php
                    if ( $news_query->have_posts() ) :
                        while ( $news_query->have_posts() ) : $news_query->the_post();
                    ?>
                    <div class="col-sm-8 col-md-5 col-lg-4 flex-shrink-0" style="scroll-snap-align:start;">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 transform-hover d-flex flex-column">
                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="ratio ratio-16x9 overflow-hidden">
                                <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                            </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="text-campus-gold small font-heading fw-bold"><i class="bi bi-calendar-event me-1"></i><?php echo get_the_date(); ?></span>
                                </div>
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-2">
                                    <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none stretched-link"><?php the_title(); ?></a>
                                </h3>
                                <p class="card-text text-campus-muted small flex-grow-1 position-relative" style="z-index:2;"><?php echo wp_trim_words( get_the_excerpt(), 12 ); ?></p>
                                <span class="text-campus-gold text-decoration-none small fw-bold font-heading mt-2 d-inline-flex align-items-center position-relative" style="z-index:2;pointer-events:none;">
                                    <?php esc_html_e('Baca', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        for ( $i = 0; $i < 3; $i++ ) :
                    ?>
                    <div class="col-sm-8 col-md-5 col-lg-4 flex-shrink-0" style="scroll-snap-align:start;">
                        <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden bg-white h-100 d-flex flex-column">
                            <div class="ratio ratio-16x9 bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                <i class="bi bi-newspaper fs-1 text-campus-gold"></i>
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <span class="text-campus-gold small font-heading fw-bold mb-2"><i class="bi bi-calendar-event me-1"></i><?php esc_html_e('Berita Terbaru', 'educampus'); ?></span>
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-2"><?php esc_html_e('Judul Berita EduCampus', 'educampus'); ?></h3>
                                <p class="card-text text-campus-muted small flex-grow-1"><?php esc_html_e('Deskripsi singkat berita terkini seputar kegiatan akademik dan non-akademik.', 'educampus'); ?></p>
                                <span class="text-campus-gold text-decoration-none small fw-bold font-heading mt-2 d-inline-flex align-items-center" style="pointer-events:none;"><?php esc_html_e('Baca', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i></span>
                            </div>
                        </div>
                    </div>
                    <?php endfor; endif; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo esc_url( educampus_get_news_archive_url() ); ?>" class="btn btn-campus-outline btn-sm font-heading px-4">
                        <?php esc_html_e('Lihat Semua Berita', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

        <?php elseif ( $news_layout === 'compact' ) : ?>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="list-group list-group-flush bg-transparent">
                        <?php
                        if ( $news_query->have_posts() ) :
                            while ( $news_query->have_posts() ) : $news_query->the_post();
                        ?>
                        <a href="<?php the_permalink(); ?>" class="list-group-item list-group-item-action bg-transparent border-bottom border-light px-0 py-3 d-flex align-items-center gap-3 text-decoration-none">
                            <div class="flex-shrink-0 text-center" style="width:50px;">
                                <div class="bg-campus-navy text-campus-gold rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:40px;height:40px;">
                                    <i class="bi bi-megaphone small"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php the_title(); ?></h3>
                                <span class="small text-campus-muted"><i class="bi bi-clock me-1"></i><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="flex-shrink-0 text-campus-gold">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            for ( $i = 0; $i < 4; $i++ ) :
                        ?>
                        <div class="list-group-item bg-transparent border-bottom border-light px-0 py-3 d-flex align-items-center gap-3">
                            <div class="flex-shrink-0 text-center" style="width:50px;">
                                <div class="bg-campus-navy text-campus-gold rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:40px;height:40px;">
                                    <i class="bi bi-megaphone small"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 font-heading fw-bold text-campus-navy mb-1"><?php esc_html_e('Judul Berita EduCampus', 'educampus'); ?></h3>
                                <span class="small text-campus-muted"><i class="bi bi-clock me-1"></i>11 Juni 2026</span>
                            </div>
                            <div class="flex-shrink-0 text-campus-gold">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                        <?php endfor; endif; ?>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?php echo esc_url( educampus_get_news_archive_url() ); ?>" class="btn btn-campus-outline btn-sm font-heading px-4">
                            <?php esc_html_e('Lihat Semua Berita', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="row g-4">
                <?php
                if ( $news_query->have_posts() ) :
                    while ( $news_query->have_posts() ) : $news_query->the_post();
                ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <?php if ( $news_layout === 'list' ) : ?>
                            <!-- List Layout -->
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden p-3 bg-white transform-hover d-flex flex-row align-items-center gap-3">
                                <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'campus-square', array( 'class' => 'w-100 h-100 object-fit-cover rounded-3' ) ); ?>
                                    <?php else : ?>
                                        <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                            <i class="bi bi-newspaper fs-3 text-campus-gold"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-campus-gold small font-heading fw-bold mb-1 d-inline-block"><?php echo get_the_date(); ?></span>
                                    <h3 class="h6 font-heading fw-bold text-campus-navy mb-1">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="card-text text-campus-muted small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-campus-outline btn-sm font-heading">
                                        <?php esc_html_e('Baca', 'educampus'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php elseif ( $news_layout === 'overlay' ) : ?>
                            <!-- Overlay Layout -->
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-dark text-white position-relative transform-hover" style="min-height: 320px;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'w-100 h-100 object-fit-cover position-absolute top-0 start-0 opacity-60' ) ); ?>
                                <?php else : ?>
                                    <div class="w-100 h-100 bg-campus-navy position-absolute top-0 start-0 opacity-60"></div>
                                <?php endif; ?>
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(10,25,40,0.95) 0%, rgba(10,25,40,0.2) 100%); z-index: 1;"></div>
                                <div class="card-body p-4 position-relative d-flex flex-column justify-content-end h-100" style="z-index: 2;">
                                    <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo get_the_date(); ?></span>
                                    <h3 class="h5 font-heading fw-bold text-white mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-white text-decoration-none hover-secondary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <p class="text-white-50 small mb-0"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Grid Layout -->
                            <div class="card border-0 shadow-campus-soft rounded-campus h-100 bg-white transform-hover d-flex flex-column">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="ratio ratio-16x9 overflow-hidden">
                                        <?php the_post_thumbnail( 'campus-medium', array( 'class' => 'object-fit-cover img-fluid' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                    <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo get_the_date(); ?></span>
                                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-campus-navy text-decoration-none stretched-link"><?php the_title(); ?></a>
                                    </h3>
                                    <p class="card-text text-campus-muted small flex-grow-1 position-relative" style="z-index:2;"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                                    <span class="text-campus-gold text-decoration-none small fw-bold font-heading mt-4 d-inline-flex align-items-center position-relative" style="z-index:2;pointer-events:none;">
                                        <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    foreach ( $news_placeholder as $n ) :
                ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <?php if ( $news_layout === 'list' ) : ?>
                            <!-- List Fallback -->
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden p-3 bg-white transform-hover d-flex flex-row align-items-center gap-3">
                                <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                    <div class="w-100 h-100 bg-campus-navy d-flex align-items-center justify-content-center text-white-50 rounded-3">
                                        <i class="bi bi-newspaper fs-3 text-campus-gold"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-campus-gold small font-heading fw-bold mb-1 d-inline-block"><?php echo $n['date']; ?></span>
                                    <h3 class="h6 font-heading fw-bold text-campus-navy mb-1">
                                        <a href="#" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php echo $n['title']; ?>
                                        </a>
                                    </h3>
                                    <p class="card-text text-campus-muted small mb-0"><?php echo $n['desc']; ?></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="#" class="btn btn-campus-outline btn-sm font-heading">
                                        <?php esc_html_e('Baca', 'educampus'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php elseif ( $news_layout === 'overlay' ) : ?>
                            <!-- Overlay Fallback -->
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-dark text-white position-relative transform-hover" style="min-height: 320px;">
                                <div class="w-100 h-100 bg-campus-navy position-absolute top-0 start-0 opacity-60"></div>
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(10,25,40,0.95) 0%, rgba(10,25,40,0.2) 100%); z-index: 1;"></div>
                                <div class="card-body p-4 position-relative d-flex flex-column justify-content-end h-100" style="z-index: 2;">
                                    <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo $n['date']; ?></span>
                                    <h3 class="h5 font-heading fw-bold text-white mb-2">
                                        <a href="#" class="text-white text-decoration-none hover-secondary">
                                            <?php echo $n['title']; ?>
                                        </a>
                                    </h3>
                                    <p class="text-white-50 small mb-0"><?php echo $n['desc']; ?></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Grid Fallback -->
                            <div class="card border-0 shadow-campus-soft rounded-campus overflow-hidden h-100 bg-white transform-hover d-flex flex-column">
                                <div class="ratio ratio-16x9 bg-campus-navy d-flex align-items-center justify-content-center text-white-50">
                                    <i class="bi bi-newspaper fs-1 text-campus-gold"></i>
                                </div>
                                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                    <span class="text-campus-gold small font-heading fw-bold mb-2 d-inline-block"><?php echo $n['date']; ?></span>
                                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-3">
                                        <a href="#" class="text-campus-navy text-decoration-none hover-secondary">
                                            <?php echo $n['title']; ?>
                                        </a>
                                    </h3>
                                    <p class="card-text text-campus-muted small flex-grow-1"><?php echo $n['desc']; ?></p>
                                    <a href="#" class="text-campus-gold text-decoration-none small fw-bold font-heading mt-4">
                                        <?php esc_html_e('Selengkapnya', 'educampus'); ?> <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
