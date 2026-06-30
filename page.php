<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();
?>
        <?php educampus_page_hero( array( 'title' => get_the_title() ) ); ?>

        <!-- Main Page Layout -->
        <div class="container my-5 pb-5">
            <!-- Breadcrumbs -->
            <?php educampus_breadcrumbs(); ?>

            <div class="row g-4 mt-2 justify-content-center">
                <!-- Page Content Area (Centered 9 Columns for Premium Readability) -->
                <main id="primary" class="col-lg-9 site-main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white p-4 p-md-5 rounded-campus shadow-campus-soft' ); ?>>
                        
                        <!-- Featured Image if exists -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="ratio ratio-21x9 overflow-hidden rounded-campus shadow-campus-soft mb-4">
                                <?php the_post_thumbnail( 'campus-large', array( 'class' => 'img-fluid object-fit-cover' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Content Body -->
                        <div class="entry-content font-body text-campus-navy" style="line-height: 1.8; font-size: 1.05rem;">
                            <?php the_content(); ?>
                        </div>

                        <!-- Optional: Link list / metadata if subpages exist -->
                        <?php
                        wp_link_pages( array(
                            'before' => '<div class="page-links mt-4"><span class="font-heading fw-bold text-campus-navy me-2">' . esc_html__( 'Pages:', 'educampus' ) . '</span>',
                            'after'  => '</div>',
                        ) );
                        ?>
                    </article>
                </main>
            </div>
        </div>
<?php
    endwhile;
else :
?>
    <!-- fallback if no posts match query -->
    <div class="container my-5 py-5 text-center">
        <i class="bi bi-exclamation-circle text-campus-muted" style="font-size: 3rem;"></i>
        <h1 class="h2 mt-3"><?php esc_html_e( 'Laman Tidak Ditemukan', 'educampus' ); ?></h1>
        <p class="text-campus-muted"><?php esc_html_e( 'Maaf, halaman yang Anda tuju tidak terdaftar atau telah dihapus.', 'educampus' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-campus-primary mt-3"><?php esc_html_e( 'Kembali ke Beranda', 'educampus' ); ?></a>
    </div>
<?php
endif;
get_footer();
