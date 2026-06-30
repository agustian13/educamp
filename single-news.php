<?php
/**
 * The template for displaying single news articles
 * Dispatches to selected layout (Default or Magazine)
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$layout = get_theme_mod( 'educampus_single_news_layout', 'default' );
get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        if ( 'magazine' === $layout ) {
            get_template_part( 'template-parts/single-news-magazine' );
        } elseif ( 'modern' === $layout ) {
            get_template_part( 'template-parts/single-news-modern' );
        } elseif ( 'split' === $layout ) {
            get_template_part( 'template-parts/single-news-split' );
        } elseif ( 'bold' === $layout ) {
            get_template_part( 'template-parts/single-news-bold' );
        } elseif ( 'sidebar-left' === $layout ) {
            get_template_part( 'template-parts/single-news-sidebar-left' );
        } elseif ( 'sidebar-compact' === $layout ) {
            get_template_part( 'template-parts/single-news-sidebar-compact' );
        } else {
            get_template_part( 'template-parts/single-news-default' );
        }
    endwhile;
else :
?>
<div class="container my-5 py-5 text-center">
    <h1 class="h2"><?php esc_html_e( 'Berita Tidak Ditemukan', 'educampus' ); ?></h1>
    <p class="text-campus-muted"><?php esc_html_e( 'Konten berita sedang dipersiapkan.', 'educampus' ); ?></p>
</div>
<?php
endif;

get_footer();
