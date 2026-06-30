<?php
/**
 * EduCampus Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Set up theme defaults and register support for various WordPress features.
 */
function educampus_setup() {
    load_theme_textdomain( 'educampus', get_template_directory() . '/languages' );

    // Set the default content width for responsive embeds.
    if ( ! isset( $GLOBALS['content_width'] ) ) {
        $GLOBALS['content_width'] = 1200;
    }

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'campus-large', 1200, 600, true );
    add_image_size( 'campus-medium', 800, 450, true );
    add_image_size( 'campus-square', 400, 400, true );

    register_nav_menus( array(
        'primary'          => esc_html__( 'Menu Theme', 'educampus' ),
        'footer'           => esc_html__( 'Footer Menu', 'educampus' ),
        'footer_academic'  => esc_html__( 'Footer Akademik', 'educampus' ),
    ) );

    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ) );

    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Gutenberg supports
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );

    // Enable lazy loading for images & iframes
    add_filter( 'wp_lazy_loading_enabled', '__return_true' );
}
add_action( 'after_setup_theme', 'educampus_setup' );

/**
 * Enqueue scripts and styles with performance optimizations.
 */
function educampus_scripts() {
    $heading_font = get_theme_mod( 'educampus_font_heading', 'Plus Jakarta Sans' );
    $body_font    = get_theme_mod( 'educampus_font_body', 'Plus Jakarta Sans' );

    // Google Fonts with preconnect already in header.php
    $font_families = array();
    $font_families[] = str_replace( ' ', '+', $heading_font ) . ':wght@300;400;500;600;700;800';
    if ( $body_font !== $heading_font ) {
        $font_families[] = str_replace( ' ', '+', $body_font ) . ':wght@300;400;500;600;700';
    }
    $google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $font_families ) . '&display=swap';
    wp_enqueue_style( 'educampus-google-fonts', $google_fonts_url, array(), null );

    // Bootstrap Icons (load async in footer)
    wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3' );
    wp_style_add_data( 'bootstrap-icons', 'media', 'print' );
    wp_style_add_data( 'bootstrap-icons', 'onload', 'this.media=\'all\'' );

    // Bootstrap 5 CSS
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );

    // Main Theme Stylesheet (depend on bootstrap-css only)
    wp_enqueue_style( 'educampus-style', get_stylesheet_uri(), array( 'bootstrap-css' ), filemtime( get_template_directory() . '/style.css' ) );

    // Bootstrap 5 Bundle JS (defer)
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', array( 'strategy' => 'defer' ) );

    // Custom JS (defer)
    wp_enqueue_script( 'educampus-custom-js', get_template_directory_uri() . '/assets/js/theme.js', array(), filemtime( get_template_directory() . '/assets/js/theme.js' ), array( 'strategy' => 'defer' ) );

    // NProgress.js — loading bar on page navigation
    wp_enqueue_style( 'nprogress', get_template_directory_uri() . '/assets/vendor/nprogress.css', array(), '0.2.0' );
    wp_enqueue_script( 'nprogress', get_template_directory_uri() . '/assets/vendor/nprogress.js', array(), '0.2.0', array( 'strategy' => 'defer' ) );
    wp_enqueue_script( 'nprogress-init', get_template_directory_uri() . '/assets/js/nprogress-init.js', array( 'nprogress' ), filemtime( get_template_directory() . '/assets/js/nprogress-init.js' ), array( 'strategy' => 'defer' ) );

    // Preload hero image if available
    if ( is_front_page() ) {
        $hero_posts = get_posts( array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'meta_key'       => '_thumbnail_id',
        ) );
        if ( ! empty( $hero_posts ) ) {
            foreach ( $hero_posts as $p ) {
                $thumb = get_the_post_thumbnail_url( $p->ID, 'campus-large' );
                if ( $thumb ) {
                    echo '<link rel="preload" as="image" href="' . esc_url( $thumb ) . '" fetchpriority="high">';
                    break; // only preload first hero image
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'educampus_scripts' );

/**
 * Load SISTER API integration files.
 */
require_once get_template_directory() . '/includes/sister-api.php';
require_once get_template_directory() . '/includes/sister-admin.php';

/**
 * Load Kalender Akademik admin settings.
 */
require_once get_template_directory() . '/includes/kalender-admin.php';

/**
 * Flush rewrite rules on theme activation.
 */
function educampus_flush_rewrite_rules() {
    educampus_register_cpts();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'educampus_flush_rewrite_rules' );

/**
 * Preload Bootstrap CSS using onload trick.
 */
function educampus_preload_bootstrap_css( $tag, $handle, $href ) {
    if ( 'bootstrap-css' === $handle ) {
        $tag = str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $tag );
    }
    return $tag;
}
add_filter( 'style_loader_tag', 'educampus_preload_bootstrap_css', 10, 3 );

/**
 * Output dynamic CSS variables from Customizer into <head>.
 */
function educampus_customizer_css_output() {
    $primary       = get_theme_mod( 'educampus_color_primary', '#0a2540' );
    $primary_light = get_theme_mod( 'educampus_color_primary_light', '#163c63' );
    $primary_dark  = get_theme_mod( 'educampus_color_primary_dark', '#051424' );
    $secondary       = get_theme_mod( 'educampus_color_secondary', '#c5a059' );
    $secondary_light = get_theme_mod( 'educampus_color_secondary_light', '#d4b87c' );
    $secondary_dark  = get_theme_mod( 'educampus_color_secondary_dark', '#a17f3e' );
    $font_heading = get_theme_mod( 'educampus_font_heading', 'Plus Jakarta Sans' );
    $font_body    = get_theme_mod( 'educampus_font_body', 'Plus Jakarta Sans' );
    ?>
    <style id="educampus-customizer-css">
        :root {
            --font-heading: '<?php echo esc_attr( $font_heading ); ?>', sans-serif;
            --font-body: '<?php echo esc_attr( $font_body ); ?>', sans-serif;
            --color-primary: <?php echo esc_attr( $primary ); ?>;
            --color-primary-light: <?php echo esc_attr( $primary_light ); ?>;
            --color-primary-dark: <?php echo esc_attr( $primary_dark ); ?>;
            --color-secondary: <?php echo esc_attr( $secondary ); ?>;
            --color-secondary-light: <?php echo esc_attr( $secondary_light ); ?>;
            --color-secondary-dark: <?php echo esc_attr( $secondary_dark ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'educampus_customizer_css_output', 99 );

/**
 * Include Custom Post Types registration.
 */
require get_template_directory() . '/includes/cpts.php';

/**
 * Include Customizer settings options.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Include Custom Gutenberg Block Patterns.
 */
require get_template_directory() . '/includes/block-patterns.php';

/**
 * Register Header PMB Button settings for Customizer.
 */
function educampus_header_pmb_customize( $wp_customize ) {
    $wp_customize->add_setting( 'educampus_header_pmb_text', array(
        'default'           => __( 'Daftar PMB', 'educampus' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_header_pmb_text', array(
        'label'   => __( 'Teks Tombol PMB Header', 'educampus' ),
        'section' => 'educampus_header_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_header_pmb_url', array(
        'default'           => '/pmb',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_header_pmb_url', array(
        'label'   => __( 'URL Tombol PMB Header', 'educampus' ),
        'section' => 'educampus_header_section',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'educampus_header_pmb_customize' );

/**
 * Register widget areas.
 */
function educampus_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'educampus' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Sidebar utama untuk blog dan arsip.', 'educampus' ),
        'before_widget' => '<div class="card border-0 shadow-campus-soft rounded-campus p-3 mb-4 bg-white %s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="h5 border-bottom pb-2 text-campus-navy mb-3">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Kolom 1', 'educampus' ),
        'id'            => 'footer-1',
        'description'   => __( 'Logo, deskripsi, dan media sosial.', 'educampus' ),
        'before_widget' => '<div class="col-lg-4 col-md-6 %s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Kolom 2', 'educampus' ),
        'id'            => 'footer-2',
        'description'   => __( 'Tautan cepat.', 'educampus' ),
        'before_widget' => '<div class="col-lg-2 col-md-6 %s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Kolom 3', 'educampus' ),
        'id'            => 'footer-3',
        'description'   => __( 'Akademik.', 'educampus' ),
        'before_widget' => '<div class="col-lg-3 col-md-6 %s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Kolom 4', 'educampus' ),
        'id'            => 'footer-4',
        'description'   => __( 'Kontak dan lokasi.', 'educampus' ),
        'before_widget' => '<div class="col-lg-3 col-md-6 %s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="h5 font-heading text-campus-gold border-bottom border-light border-opacity-20 pb-2 mb-3">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'educampus_widgets_init' );

/**
 * Custom Breadcrumb Helper with JSON-LD BreadcrumbList schema.
 */
function educampus_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $items = array();
    $items[] = array(
        'name' => __( 'Home', 'educampus' ),
        'url'  => home_url( '/' ),
    );

    ob_start();
    echo '<nav aria-label="breadcrumb" class="my-3">';
    echo '<ol class="breadcrumb mb-0 bg-transparent p-0 align-items-center">';
    echo '<li class="breadcrumb-item"><a href="' . esc_url( home_url( '/' ) ) . '" class="text-campus-navy text-decoration-none"><i class="bi bi-house-door-fill text-campus-gold"></i> ' . esc_html__( 'Home', 'educampus' ) . '</a></li>';

    if ( is_archive() ) {
        $title = '';
        if ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        } elseif ( is_tax() || is_category() || is_tag() ) {
            $title = single_term_title( '', false );
        } else {
            $title = wp_strip_all_tags( get_the_archive_title() );
            $title = preg_replace( '/^[^:]+:\s*/', '', $title );
        }
        echo '<li class="breadcrumb-item active text-campus-muted" aria-current="page">' . esc_html( $title ) . '</li>';
        $items[] = array( 'name' => $title, 'url' => '' );
    } elseif ( is_single() ) {
        $post_type = get_post_type();
        if ( $post_type !== 'post' ) {
            $post_type_obj = get_post_type_object( $post_type );
            if ( $post_type_obj && $post_type_obj->has_archive ) {
                $archive_url = get_post_type_archive_link( $post_type );
                echo '<li class="breadcrumb-item"><a href="' . esc_url( $archive_url ) . '" class="text-campus-navy text-decoration-none">' . esc_html( $post_type_obj->labels->name ) . '</a></li>';
                $items[] = array( 'name' => $post_type_obj->labels->name, 'url' => $archive_url );
            }
        }
        echo '<li class="breadcrumb-item active text-campus-muted text-truncate d-inline-block" style="max-width: 250px;" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
        $items[] = array( 'name' => get_the_title(), 'url' => '' );
    } elseif ( is_page() ) {
        global $post;
        if ( $post && $post->post_parent ) {
            $anc = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $anc as $ancestor ) {
                $anc_url = get_permalink( $ancestor );
                echo '<li class="breadcrumb-item"><a href="' . esc_url( $anc_url ) . '" class="text-campus-navy text-decoration-none">' . esc_html( get_the_title( $ancestor ) ) . '</a></li>';
                $items[] = array( 'name' => get_the_title( $ancestor ), 'url' => $anc_url );
            }
        }
        echo '<li class="breadcrumb-item active text-campus-muted" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
        $items[] = array( 'name' => get_the_title(), 'url' => '' );
    } elseif ( is_search() ) {
        $search_q = get_search_query();
        echo '<li class="breadcrumb-item active text-campus-muted" aria-current="page">' . esc_html__( 'Pencarian: "', 'educampus' ) . esc_html( $search_q ) . '"</li>';
        $items[] = array( 'name' => __( 'Pencarian: ', 'educampus' ) . $search_q, 'url' => '' );
    }

    echo '</ol>';
    echo '</nav>';
    $html = ob_get_clean();

    // Output JSON-LD BreadcrumbList
    $json_items = array();
    foreach ( $items as $i => $item ) {
        $json_items[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $item['name'],
            'item'     => ! empty( $item['url'] ) ? $item['url'] : home_url( add_query_arg( array() ) ),
        );
    }
    echo '<script type="application/ld+json">';
    echo json_encode( array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $json_items,
    ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    echo '</script>';

    echo wp_kses_post( $html );
}

/**
 * Include standard 'post' type in 'news' archive for backward compatibility.
 */
function educampus_news_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'news' ) ) {
        $query->set( 'post_type', array( 'news', 'post' ) );
    }
}
add_action( 'pre_get_posts', 'educampus_news_archive_query' );

/**
 * Add Open Graph / Twitter Card meta tags for social sharing.
 * Automatically disabled when Yoast SEO or Rank Math is active.
 */
function educampus_og_tags() {
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
        return;
    }

    $title       = '';
    $description = '';
    $url         = '';
    $image       = '';
    $type        = 'website';

    if ( is_singular() ) {
        global $post;
        setup_postdata( $post );
        $title       = single_post_title( '', false );
        $description = wp_trim_words( wp_strip_all_tags( get_the_excerpt() ?: get_the_content() ), 30 );
        $url         = get_permalink();
        $image       = get_the_post_thumbnail_url( $post, 'large' );
        $type        = 'article';
        wp_reset_postdata();
    } elseif ( is_home() || is_front_page() ) {
        $title       = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $url         = home_url();
        $image       = get_theme_mod( 'educampus_og_default_image', '' );
    } else {
        $title       = wp_get_document_title();
        $description = get_bloginfo( 'description' );
        $url         = home_url( add_query_arg( array() ) );
        $image       = get_theme_mod( 'educampus_og_default_image', '' );
    }

    if ( empty( $image ) ) {
        $image = get_theme_mod( 'educampus_og_default_image', '' );
    }
    ?>
<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
<meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
<meta property="og:type" content="<?php echo esc_attr( $type ); ?>" />
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
<?php if ( $image ) : ?>
<meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image" />
<?php if ( $image ) : ?>
<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>" />
<?php endif; ?>
<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
<link rel="canonical" href="<?php echo esc_url( $url ); ?>" />
    <?php
}
add_action( 'wp_head', 'educampus_og_tags', 1 );

/**
 * Add JSON-LD Article schema to single posts/pages.
 */
function educampus_jsonld_article() {
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
        return;
    }
    if ( is_singular( array( 'post', 'news' ) ) ) {
        global $post;
        setup_postdata( $post );
        $image = get_the_post_thumbnail_url( $post, 'full' ) ?: get_theme_mod( 'educampus_og_default_image', '' );
        $author_name = get_the_author_meta( 'display_name', $post->post_author );
        $date_pub = get_the_date( 'c' );
        $date_mod = get_the_modified_date( 'c' );
        wp_reset_postdata();
        ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo esc_js( get_the_title() ); ?>",
    "description": "<?php echo esc_js( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ?: get_the_content() ), 30 ) ); ?>",
    "image": "<?php echo esc_js( $image ); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_js( $author_name ); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>"
    },
    "datePublished": "<?php echo esc_js( $date_pub ); ?>",
    "dateModified": "<?php echo esc_js( $date_mod ); ?>"
}
</script>
        <?php
    }
}
add_action( 'wp_head', 'educampus_jsonld_article', 2 );

/**
 * Register custom query vars for lecturer filtering.
 */
function educampus_register_lecturer_query_vars( $vars ) {
    $vars[] = 'letter';
    $vars[] = 'program_id';
    return $vars;
}
add_filter( 'query_vars', 'educampus_register_lecturer_query_vars' );

/**
 * Filter lecturer archive query by first letter of post title.
 */
function educampus_filter_lecturer_by_letter( $where, $query ) {
    global $wpdb;
    if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'lecturer' ) ) {
        $letter = get_query_var( 'letter' );
        if ( ! empty( $letter ) && preg_match( '/^[A-Za-z]$/', $letter ) ) {
            $letter_lower = strtolower( $letter );
            $letter_upper = strtoupper( $letter );
            $where .= $wpdb->prepare( " AND ( LOWER( {$wpdb->posts}.post_title ) LIKE %s", $letter_lower . '%' );
            $where .= $wpdb->prepare( " OR UPPER( {$wpdb->posts}.post_title ) LIKE %s )", $letter_upper . '%' );
        }
    }
    return $where;
}
add_filter( 'posts_where', 'educampus_filter_lecturer_by_letter', 10, 2 );

/**
 * Filter lecturer archive by program_id via meta_query.
 */
function educampus_filter_lecturer_by_program( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'lecturer' ) ) {
        $program_id = get_query_var( 'program_id' );
        if ( ! empty( $program_id ) && absint( $program_id ) > 0 ) {
            $meta_query = $query->get( 'meta_query' ) ?: array();
            $meta_query[] = array(
                'key'   => '_lecturer_program_id',
                'value' => absint( $program_id ),
            );
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'educampus_filter_lecturer_by_program' );

/**
 * Remove query strings from static resources for better caching.
 */
function educampus_remove_script_version( $src ) {
    if ( strpos( $src, 'cdn.jsdelivr.net' ) !== false || strpos( $src, 'fonts.googleapis.com' ) !== false ) {
        return $src;
    }
    $parts = explode( '?', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'educampus_remove_script_version' );
add_filter( 'style_loader_src', 'educampus_remove_script_version' );

/**
 * Disable emoji scripts for performance.
 */
function educampus_disable_emoji() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'educampus_disable_emoji' );

/**
 * Disable oEmbed discovery for performance.
 */
function educampus_disable_oembed() {
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'educampus_disable_oembed' );

/**
 * Remove WP version from head and RSS for security/SEO.
 */
function educampus_remove_wp_version() {
    return '';
}
add_filter( 'the_generator', 'educampus_remove_wp_version' );

/**
 * Add WebP support via content-type detection.
 */
function educampus_webp_upload_mimes( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'upload_mimes', 'educampus_webp_upload_mimes' );

/**
 * Generate SVG placeholder data URI for posts without featured image.
 */
function educampus_placeholder_thumbnail_svg( $post_type ) {
    $colors = array(
        'announcement' => array( 'bg' => '#0a2540', 'icon' => '#c5a059' ),
        'event'        => array( 'bg' => '#163c63', 'icon' => '#c5a059' ),
        'achievement'  => array( 'bg' => '#051424', 'icon' => '#d4b87c' ),
        'partnership'  => array( 'bg' => '#1a3a5c', 'icon' => '#c5a059' ),
    );
    $c = isset( $colors[ $post_type ] ) ? $colors[ $post_type ] : array( 'bg' => '#0a2540', 'icon' => '#c5a059' );

    $icons = array(
        'announcement' => '<path d="M4 8a3 3 0 0 1 3-3h7l4 4v4a2 2 0 0 1-2 2h-1a3 3 0 1 1-6 0H9a3 3 0 1 1-6 0H4a2 2 0 0 1-2-2V8zm2 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM4 8v3h12V8l-3-3H7l-3 3z"/>',
        'event'        => '<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/><path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>',
        'achievement'  => '<path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/><path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>',
        'partnership'  => '<path d="M.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5H.5zm3 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H4a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H4a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H4a.5.5 0 0 1-.5-.5v-1z"/>',
    );
    $icon_path = isset( $icons[ $post_type ] ) ? $icons[ $post_type ] : $icons['announcement'];

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="600" viewBox="0 0 1200 600">
        <rect width="1200" height="600" fill="' . $c['bg'] . '"/>
        <g transform="translate(560, 200)" fill="' . $c['icon'] . '" opacity="0.25">
            <g transform="scale(3)">' . $icon_path . '</g>
        </g>
        <text x="600" y="380" text-anchor="middle" font-family="Arial,sans-serif" font-size="18" fill="' . $c['icon'] . '" opacity="0.4" letter-spacing="2">' . strtoupper( get_post_type_object( $post_type )->labels->singular_name ?? $post_type ) . '</text>
    </svg>';
    return 'data:image/svg+xml;base64,' . base64_encode( $svg );
}

/**
 * Filter thumbnail URL for announcement/event posts without featured image.
 */
function educampus_default_thumbnail_url( $url, $post_id ) {
    if ( ! empty( $url ) ) {
        return $url;
    }
    $post_type = get_post_type( $post_id );
    $targets = array( 'announcement', 'event', 'achievement', 'partnership' );
    if ( in_array( $post_type, $targets, true ) ) {
        return educampus_placeholder_thumbnail_svg( $post_type );
    }
    return $url;
}
add_filter( 'get_the_post_thumbnail_url', 'educampus_default_thumbnail_url', 10, 2 );
add_filter( 'post_thumbnail_url', 'educampus_default_thumbnail_url', 10, 2 );

/**
 * Return a sentinel ID for target post types without a featured image,
 * so get_the_post_thumbnail() proceeds to the post_thumbnail_html filter.
 */
function educampus_default_thumbnail_id( $thumbnail_id, $post ) {
    if ( ! empty( $thumbnail_id ) ) {
        return $thumbnail_id;
    }
    $targets = array( 'announcement', 'event', 'achievement', 'partnership' );
    if ( in_array( get_post_type( $post ), $targets, true ) ) {
        return -1;
    }
    return $thumbnail_id;
}
add_filter( 'get_post_thumbnail_id', 'educampus_default_thumbnail_id', 10, 2 );

/**
 * Replace sentinel thumbnail HTML with SVG placeholder.
 */
function educampus_default_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( -1 !== $post_thumbnail_id || ! empty( $html ) ) {
        return $html;
    }
    $placeholder_url = educampus_placeholder_thumbnail_svg( get_post_type( $post_id ) );
    $class = isset( $attr['class'] ) ? esc_attr( $attr['class'] ) : 'img-fluid object-fit-cover w-100 h-100';
    $alt = esc_attr( get_the_title( $post_id ) );
    return '<img src="' . esc_url( $placeholder_url ) . '" class="' . $class . '" alt="' . $alt . '" loading="lazy">';
}
add_filter( 'post_thumbnail_html', 'educampus_default_post_thumbnail_html', 10, 5 );

/**
 * Register /berita/ rewrite endpoint for standard posts.
 */
function educampus_berita_rewrite() {
    add_rewrite_tag( '%educampus_berita%', '1' );
    add_rewrite_rule( '^berita/?$', 'index.php?post_type=post&educampus_berita=1', 'top' );
}
add_action( 'init', 'educampus_berita_rewrite' );

/**
 * Return the correct news archive URL.
 */
function educampus_get_news_archive_url() {
    $posts_page = get_option( 'page_for_posts' );
    if ( $posts_page ) {
        return get_permalink( $posts_page );
    }
    return home_url( '/berita/' );
}

/**
 * Use archive-news.php for standard posts archive (blog page).
 */
function educampus_use_news_archive_for_posts( $template ) {
    if ( ( is_home() && ! is_front_page() ) || get_query_var( 'educampus_berita' ) ) {
        $news_archive = get_template_directory() . '/archive-news.php';
        if ( file_exists( $news_archive ) ) {
            return $news_archive;
        }
    }
    return $template;
}
add_filter( 'template_include', 'educampus_use_news_archive_for_posts' );

/**
 * Force template for /berita/ endpoint.
 */
function educampus_berita_template_redirect() {
    if ( get_query_var( 'educampus_berita' ) ) {
        include get_template_directory() . '/archive-news.php';
        exit;
    }
}
add_action( 'template_redirect', 'educampus_berita_template_redirect' );

/**
 * Use single-news.php for standard single posts.
 */
function educampus_use_news_single_for_posts( $template ) {
    global $post;
    if ( $post && $post->post_type === 'post' ) {
        $news_single = get_template_directory() . '/single-news.php';
        if ( file_exists( $news_single ) ) {
            return $news_single;
        }
    }
    return $template;
}
add_filter( 'single_template', 'educampus_use_news_single_for_posts' );

/**
 * Resolve CTA URL: external (http/https) → as-is, internal → prepend home_url().
 */
function educampus_resolve_cta_url( $url ) {
    if ( preg_match( '/^https?:\/\//i', $url ) ) {
        return $url;
    }
    return home_url( $url );
}

/**
 * Add CSS classes to footer menu links for consistent styling.
 */
function educampus_footer_menu_link_attrs( $atts, $item, $args ) {
    if ( in_array( $args->theme_location, array( 'footer', 'footer_academic' ), true ) ) {
        $atts['class'] = 'text-white-50 text-decoration-none hover-gold';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'educampus_footer_menu_link_attrs', 10, 3 );

/**
 * Add mb-2 class to footer menu li items.
 */
function educampus_footer_menu_li_classes( $classes, $item, $args, $depth ) {
    if ( in_array( $args->theme_location, array( 'footer', 'footer_academic' ), true ) ) {
        $classes[] = 'mb-2';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'educampus_footer_menu_li_classes', 10, 4 );

/**
 * Reusable page hero banner with optional image overlay.
 *
 * @param array $args {
 *     @type string $title           Heading text (required).
 *     @type string $badge           Small uppercase label above title.
 *     @type string $image           Custom image URL (falls back to educampus_default_hero_image).
 *     @type string $class           Extra CSS classes on the <section>.
 *     @type string $container_class Extra CSS classes on the inner container.
 *     @type string $content         Raw HTML injected after the title.
 * }
 */
function educampus_page_hero( $args = array() ) {
    $defaults = array(
        'title'           => '',
        'badge'           => '',
        'image'           => '',
        'class'           => '',
        'container_class' => '',
        'content'         => '',
    );
    $args = wp_parse_args( $args, $defaults );

    $hero_image = $args['image'] ?: get_theme_mod( 'educampus_default_hero_image', '' );
    // Per-page hero banner override (meta box)
    if ( is_singular() ) {
        $page_hero = get_post_meta( get_the_ID(), '_page_hero_image', true );
        if ( ! empty( $page_hero ) ) {
            $hero_image = $page_hero;
        }
    }
    $has_hero = ! empty( $hero_image );
?>
    <section class="bg-campus-navy text-white py-5 position-relative overflow-hidden <?php echo esc_attr( $args['class'] ); ?>">
        <?php if ( $has_hero ) : ?>
            <img src="<?php echo esc_url( $hero_image ); ?>" alt="" class="section-hero-image" loading="lazy" />
            <div class="section-hero-overlay bg-campus-navy" style="opacity: 0.75;"></div>
        <?php endif; ?>
        <div class="container position-relative z-1 py-4 <?php echo esc_attr( $args['container_class'] ); ?>">
            <?php if ( ! empty( $args['badge'] ) ) : ?>
                <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php echo esc_html( $args['badge'] ); ?></span>
            <?php endif; ?>
            <h1 class="display-5 font-heading fw-bold mb-0"><?php echo wp_kses_post( $args['title'] ); ?></h1>
            <?php echo $args['content']; ?>
        </div>
    </section>
<?php
}

/**
 * Add automatic gradient overlay to footer background.
 */
function educampus_footer_gradient_style() {
    ?>
    <style>
        .site-footer {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%) !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'educampus_footer_gradient_style', 99 );

/**
 * Dokumen: Track views on single-dokumen pages
 */
function educampus_track_dokumen_view() {
    if ( is_singular( 'dokumen' ) ) {
        $post_id = get_queried_object_id();
        $views = (int) get_post_meta( $post_id, '_dokumen_view_count', true );
        update_post_meta( $post_id, '_dokumen_view_count', $views + 1 );
    }
}
add_action( 'template_redirect', 'educampus_track_dokumen_view' );

/**
 * Dokumen: Download handler
 * Usage: ?download_dokumen={post_id}
 */
function educampus_handle_dokumen_download() {
    if ( ! isset( $_GET['download_dokumen'] ) ) {
        return;
    }
    $post_id = intval( $_GET['download_dokumen'] );
    if ( ! $post_id || get_post_type( $post_id ) !== 'dokumen' ) {
        return;
    }
    $file_url = get_post_meta( $post_id, '_dokumen_file', true );
    if ( empty( $file_url ) ) {
        return;
    }
    // Increment download count
    $downloads = (int) get_post_meta( $post_id, '_dokumen_download_count', true );
    update_post_meta( $post_id, '_dokumen_download_count', $downloads + 1 );
    // Redirect to file
    wp_redirect( esc_url_raw( $file_url ) );
    exit;
}
add_action( 'template_redirect', 'educampus_handle_dokumen_download' );

/**
 * Get sibling menu items (same parent) for the current page.
 *
 * @param string $menu_location Theme location identifier.
 * @return array Menu item objects (empty if none).
 */
function educampus_get_menu_siblings( $menu_location = 'primary' ) {
    $post_id   = get_queried_object_id();
    $locations = get_nav_menu_locations();
    if ( ! isset( $locations[ $menu_location ] ) ) {
        return array();
    }
    $menu = wp_get_nav_menu_object( $locations[ $menu_location ] );
    if ( ! $menu ) {
        return array();
    }
    $menu_items = wp_get_nav_menu_items( $menu->term_id );
    if ( empty( $menu_items ) ) {
        return array();
    }

    // Find current page's menu item
    $current_item = null;
    foreach ( $menu_items as $item ) {
        if ( (int) $item->object_id === $post_id && $item->object === 'page' ) {
            $current_item = $item;
            break;
        }
    }
    if ( ! $current_item ) {
        return array();
    }

    $parent_id = $current_item->menu_item_parent;

    // Get navigation group: if item is a child → show siblings; if top-level → show other top-level pages
    $siblings = array();
    if ( $parent_id != 0 ) {
        // Child menu item → get siblings under same parent (preserve menu order)
        foreach ( $menu_items as $item ) {
            if ( $item->menu_item_parent == $parent_id ) {
                $siblings[] = $item;
            }
        }
    } else {
        // Top-level menu item → get all top-level pages in the menu
        foreach ( $menu_items as $item ) {
            if ( $item->menu_item_parent == 0 && $item->object === 'page' ) {
                $siblings[] = $item;
            }
        }
    }

    return $siblings;
}

/**
 * Load Demo Importer (one-click demo import system).
 */
if (is_admin() && file_exists(get_template_directory() . '/includes/demo-importer.php')) {
    require_once get_template_directory() . '/includes/demo-importer.php';
}
