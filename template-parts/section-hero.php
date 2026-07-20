<?php
/**
 * Section: Hero — Slider, Static, or Video Background
 *
 * @package EduCampus
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$hero_desc_color = get_theme_mod( 'educampus_hero_desc_color', 'rgba(255,255,255,0.55)' );

if ( ! function_exists( 'educampus_parse_gold_text' ) ) {
    function educampus_parse_gold_text( $text ) {
        return preg_replace( '/\[gold\](.*?)\[\/gold\]/', '<span class="text-campus-gold">$1</span>', $text );
    }
}
?>
<!-- Section 1: Hero -->
<?php if ( get_theme_mod( 'educampus_show_hero', true ) ) :

$hero_type = get_theme_mod( 'educampus_hero_type', 'slider' );

// --- Video Hero ---
if ( 'video' === $hero_type ) :
    $mp4_url    = get_theme_mod( 'educampus_hero_video_mp4', '' );
    $youtube_url = get_theme_mod( 'educampus_hero_video_youtube', '' );

    // Extract YouTube ID
    $yt_id = '';
    if ( ! empty( $youtube_url ) ) {
        preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches );
        if ( ! empty( $matches[1] ) ) {
            $yt_id = $matches[1];
        }
    }
?>
<section id="hero-video" class="position-relative overflow-hidden" style="height:100vh;max-height:100vh;">
    <?php if ( ! empty( $yt_id ) ) : ?>
        <!-- YouTube embed as background -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events:none;overflow:hidden;">
            <div id="hero-youtube-player" data-yt-id="<?php echo esc_attr( $yt_id ); ?>"></div>
        </div>
    <?php elseif ( ! empty( $mp4_url ) ) : ?>
        <!-- Self-hosted MP4 -->
        <video class="position-absolute top-0 start-0 w-100 h-100" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( get_theme_mod( 'educampus_hero_video_poster', '' ) ); ?>">
            <source src="<?php echo esc_url( $mp4_url ); ?>" type="video/mp4">
        </video>
    <?php else : ?>
        <!-- Fallback solid color if no video URL set -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-campus-navy"></div>
    <?php endif; ?>

    <!-- Scroll-down indicator -->
    <div class="scroll-indicator">
        <span><?php esc_html_e('Scroll', 'educampus'); ?></span>
        <i class="bi bi-chevron-down"></i>
    </div>
</section>

<?php else :

// --- Slider / Static Hero ---
?>
<section id="hero-slider" class="position-relative overflow-hidden">
<?php
$slide_query = new WP_Query( array(
    'post_type'      => 'slide',
    'posts_per_page' => ( $hero_type === 'static' ) ? 1 : 5,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

    // Fetch Customizer CTA buttons configurations
    $hero_btn1_text = get_theme_mod( 'educampus_hero_btn1_text', 'Daftar PMB Sekarang' );
    $hero_btn1_val  = get_theme_mod( 'educampus_hero_btn1_url', '/pmb' );
    $hero_btn1_url  = educampus_resolve_cta_url( $hero_btn1_val );

    $hero_btn2_text = get_theme_mod( 'educampus_hero_btn2_text', 'Tanya Admisi (WA)' );
    $hero_btn2_val  = get_theme_mod( 'educampus_hero_btn2_url', 'https://wa.me/6281234567890' );
    $hero_btn2_url  = educampus_resolve_cta_url( $hero_btn2_val );

    // Fallback slides if no slide CPT posts exist
    $fallback_slides = array(
        array(
            'image'    => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1200&auto=format&fit=crop',
            'badge'    => 'PENERIMAAN MAHASISWA BARU (PMB) T.A. 2026/2027',
            'title'    => 'Membangun Generasi <span class="text-campus-gold">Unggul & Berkarakter</span> Global',
            'desc'     => 'Bergabunglah dengan EduCampus, universitas berakreditasi UNGGUL yang berfokus pada inovasi riset, teknologi mutakhir, dan pengembangan kompetensi profesional.',
        ),
        array(
            'image'    => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1200&auto=format&fit=crop',
            'badge'    => 'AKREDITASI UNGGUL 2026',
            'title'    => 'Universitas Terakreditasi <span class="text-campus-gold">UNGGUL</span> Nasional',
            'desc'     => 'BAN-PT resmi menetapkan peringkat tertinggi akreditasi institusi EduCampus berdasarkan evaluasi tata kelola, riset, dan kualitas lulusan.',
        ),
        array(
            'image'    => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop',
            'badge'    => 'RISET & INOVASI',
            'title'    => 'Pusat <span class="text-campus-gold">Riset & Inovasi</span> Terdepan',
            'desc'     => 'Laboratorium canggih, publikasi jurnal internasional, dan kerjasama riset dengan universitas terkemuka dunia untuk kemajuan sains dan teknologi.',
        ),
    );

    if ( $hero_type === 'static' ) {
        $total = 1;
    } else {
        if ( $slide_query->have_posts() ) {
            $total = $slide_query->post_count;
        } else {
            $total = count( $fallback_slides );
        }
    }
    ?>

    <div id="heroCarousel" class="carousel slide">
        <!-- Custom Progress Bar Indicators -->
        <?php if ( $total > 1 ) : ?>
            <div class="hero-progress-bar position-absolute bottom-0 start-0 w-100 d-flex justify-content-center gap-2 pb-3" style="z-index:10;">
                <?php for ( $i = 0; $i < $total; $i++ ) : ?>
                    <div class="hero-progress-track" data-slide="<?php echo $i; ?>" style="width:60px; height:3px; background:rgba(255,255,255,0.3); border-radius:2px; overflow:hidden; cursor:pointer; transition: width 0.3s ease;" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>">
                        <div class="hero-progress-fill" style="width:0%; height:100%; background:#fff; border-radius:2px; transition: none;"></div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner">
            <?php if ( $slide_query->have_posts() ) : $slide_index = 0; ?>
                <?php while ( $slide_query->have_posts() ) : $slide_query->the_post(); ?>
                    <div class="carousel-item <?php echo $slide_index === 0 ? 'active' : ''; ?>" style="min-height: 80vh;">
                        <!-- Slide Background Image -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100">
                                <?php the_post_thumbnail( 'campus-large', array(
                                    'class'   => 'w-100 h-100 object-fit-cover',
                                    'style'   => 'object-position: center;',
                                    'width'   => '1200',
                                    'height'  => '600',
                                    'loading' => '',
                                ) ); ?>
                            </div>
                        <?php else : ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-campus-navy"></div>
                        <?php endif; ?>
                        <!-- Dark Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(10,25,49,0.92) 0%, rgba(10,25,49,0.7) 40%, rgba(10,25,49,0.3) 100%);"></div>
                        
                        <!-- Content -->
                        <div class="container position-relative z-1 d-flex align-items-center" style="min-height: 80vh;">
                            <div class="row">
                                <div class="col-lg-6 text-white">
                                    <?php
                                    $s_badge   = get_post_meta( get_the_ID(), '_slide_badge', true );
                                    $s_heading = get_post_meta( get_the_ID(), '_slide_heading', true );
                                    $s_desc    = get_post_meta( get_the_ID(), '_slide_desc', true );
                                    $s_gold    = get_post_meta( get_the_ID(), '_slide_gold_text', true );

                                    $display_badge   = $s_badge ? $s_badge : get_the_title();
                                    $display_heading = $s_heading ? $s_heading : ( get_the_excerpt() ?: get_the_title() );
                                    $display_desc    = $s_desc ? $s_desc : get_the_content();

                                    if ( ! empty( $s_gold ) && ! empty( $display_heading ) ) {
                                        $display_heading = str_replace( $s_gold, '[gold]' . $s_gold . '[/gold]', $display_heading );
                                    }
                                    ?>
                                    <span class="d-inline-block badge bg-campus-gold text-campus-navy font-heading fw-bold px-3 py-1 rounded-pill mb-3" style="font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase;">
                                        <?php echo esc_html( $display_badge ); ?>
                                    </span>
                                    <h1 class="font-heading fw-bold text-white mb-2 hero-slide-title" style="font-size: clamp(1.5rem, 3vw, 2.25rem); line-height: 1.3; max-width: 100%;">
                                        <?php echo wp_kses_post( educampus_parse_gold_text( $display_heading ) ); ?>
                                    </h1>
                                    <p class="mb-2 font-body hero-slide-desc" style="font-size: 0.8rem; max-width: 100%; line-height: 1.7; color: <?php echo esc_attr( $hero_desc_color ); ?>;">
                                        <?php echo $display_desc ? wp_kses_post( wp_trim_words( wp_strip_all_tags( $display_desc ), 25 ) ) : ''; ?>
                                    </p>
                                    <div class="d-flex gap-2 hero-slide-btns">
                                        <?php if ( ! empty( $hero_btn1_text ) ) : ?>
                                            <a href="<?php echo esc_url( $hero_btn1_url ); ?>" class="btn btn-campus-secondary px-3 py-2 font-heading fw-semibold rounded-pill" style="font-size: 0.8rem;">
                                                <i class="bi bi-rocket-takeoff-fill me-1"></i><?php echo esc_html( $hero_btn1_text ); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $hero_btn2_text ) ) : ?>
                                            <a href="<?php echo esc_url( $hero_btn2_url ); ?>" class="btn btn-outline-light px-3 py-2 font-heading fw-semibold rounded-pill" style="font-size: 0.8rem; border-width: 1.5px;" target="_blank" rel="noopener">
                                                <i class="bi bi-whatsapp me-1 text-success"></i><?php echo esc_html( $hero_btn2_text ); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $slide_index++; endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <?php foreach ( $fallback_slides as $idx => $slide ) : ?>
                    <?php if ( $hero_type === 'static' && $idx > 0 ) break; ?>
                    <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>" style="min-height: 80vh;">
                        <!-- Slide Background Image -->
                        <div class="position-absolute top-0 start-0 w-100 h-100">
                            <?php if ( $idx === 0 ) : ?>
                                <img fetchpriority="high" src="<?php echo esc_url( $slide['image'] ); ?>" class="w-100 h-100 object-fit-cover" alt="EduCampus Slide" width="1200" height="600" style="object-position: center;">
                            <?php else : ?>
                                <img loading="lazy" src="<?php echo esc_url( $slide['image'] ); ?>" class="w-100 h-100 object-fit-cover" alt="EduCampus Slide" width="1200" height="600" style="object-position: center;">
                            <?php endif; ?>
                        </div>
                        <!-- Dark Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(10,25,49,0.92) 0%, rgba(10,25,49,0.7) 40%, rgba(10,25,49,0.3) 100%);"></div>
                        
                        <!-- Content -->
                        <div class="container position-relative z-1 d-flex align-items-center" style="min-height: 80vh;">
                            <div class="row">
                                <div class="col-lg-6 text-white">
                                    <span class="d-inline-block badge bg-campus-gold text-campus-navy font-heading fw-bold px-3 py-1 rounded-pill mb-3" style="font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase;">
                                        <?php echo esc_html( $slide['badge'] ); ?>
                                    </span>
                                    <h1 class="font-heading fw-bold text-white mb-2 hero-slide-title" style="font-size: clamp(1.5rem, 3vw, 2.25rem); line-height: 1.3; max-width: 100%;">
                                        <?php echo $slide['title']; ?>
                                    </h1>
                                    <p class="mb-2 font-body hero-slide-desc" style="font-size: 0.8rem; max-width: 100%; line-height: 1.7; color: <?php echo esc_attr( $hero_desc_color ); ?>;">
                                        <?php echo esc_html( $slide['desc'] ); ?>
                                    </p>
                                    <div class="d-flex gap-2 hero-slide-btns">
                                        <?php if ( ! empty( $hero_btn1_text ) ) : ?>
                                            <a href="<?php echo esc_url( $hero_btn1_url ); ?>" class="btn btn-campus-secondary px-3 py-2 font-heading fw-semibold rounded-pill" style="font-size: 0.8rem;">
                                                <i class="bi bi-rocket-takeoff-fill me-1"></i><?php echo esc_html( $hero_btn1_text ); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $hero_btn2_text ) ) : ?>
                                            <a href="<?php echo esc_url( $hero_btn2_url ); ?>" class="btn btn-outline-light px-3 py-2 font-heading fw-semibold rounded-pill" style="font-size: 0.8rem; border-width: 1.5px;" target="_blank" rel="noopener">
                                                <i class="bi bi-whatsapp me-1 text-success"></i><?php echo esc_html( $hero_btn2_text ); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Navigation Arrows -->
        <?php if ( $total > 1 ) : ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; backdrop-filter: blur(10px);">
                    <i class="bi bi-chevron-left text-white fs-5"></i>
                </span>
                <span class="visually-hidden"><?php esc_html_e('Previous', 'educampus'); ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; backdrop-filter: blur(10px);">
                    <i class="bi bi-chevron-right text-white fs-5"></i>
                </span>
                <span class="visually-hidden"><?php esc_html_e('Next', 'educampus'); ?></span>
            </button>
        <?php endif; ?>
    </div>
</section>

<?php if ( $total > 1 ) : ?>
<script>
(function(){
    const SLIDE_INTERVAL = 6000;
    const tracks = document.querySelectorAll('.hero-progress-track');
    const fills = document.querySelectorAll('.hero-progress-fill');
    const carousel = document.getElementById('heroCarousel');
    if (!carousel || !tracks.length) return;

    let timer = null;
    let progress = 0;
    let paused = false;
    const step = 50;

    function resetAll(){
        fills.forEach(f => { f.style.transition = 'none'; f.style.width = '0%'; });
        tracks.forEach(t => t.style.width = '60px');
    }

    function animate(){
        if (paused) return;
        progress += step;
        const pct = Math.min((progress / SLIDE_INTERVAL) * 100, 100);
        const items = carousel.querySelectorAll('.carousel-item');
        let activeIdx = 0;
        items.forEach(function(item, i){ if (item.classList.contains('active')) activeIdx = i; });
        if (fills[activeIdx]) {
            fills[activeIdx].style.width = pct + '%';
        }
        if (progress >= SLIDE_INTERVAL) {
            progress = 0;
            const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);
            bsCarousel.next();
        } else {
            timer = setTimeout(animate, step);
        }
    }

    function startTimer(){
        resetAll();
        progress = 0;
        animate();
    }

    carousel.addEventListener('slide.bs.carousel', function(){
        clearTimeout(timer);
        progress = 0;
        resetAll();
    });

    carousel.addEventListener('slid.bs.carousel', function(){
        startTimer();
    });

    carousel.addEventListener('mouseenter', function(){ paused = true; });
    carousel.addEventListener('mouseleave', function(){ paused = false; startTimer(); });

    tracks.forEach(function(t){
        t.addEventListener('click', function(){
            clearTimeout(timer);
            progress = 0;
            resetAll();
        });
    });

    startTimer();
})();
</script>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>