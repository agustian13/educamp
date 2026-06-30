<?php
/**
 * Template Name: Profile Page Layout
 *
 * Parent page for Profil sub-pages (Sejarah, Visi & Misi, etc.)
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

educampus_page_hero( array(
    'title' => esc_html__( 'Profil Institusi', 'educampus' ),
    'badge' => esc_html__( 'TENTANG KAMPUS', 'educampus' ),
    'image' => get_theme_mod( 'educampus_profile_hero_image', '' ),
) ); ?>

<div class="container my-5 pb-5">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-4 mt-2">
        <main id="primary" class="col-12 site-main">
            <div class="bg-white p-4 p-md-5 rounded-campus shadow-campus-soft">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <div class="entry-content font-body text-campus-navy mb-5">
                    <?php the_content(); ?>
                </div>

                <?php
                $child_pages = get_pages( array(
                    'child_of'    => get_the_ID(),
                    'parent'      => get_the_ID(),
                    'sort_column' => 'menu_order',
                    'sort_order'  => 'ASC',
                ) );

                if ( ! empty( $child_pages ) ) :
                ?>
                <div class="row g-4">
                    <?php foreach ( $child_pages as $child ) : ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>" class="text-decoration-none">
                            <div class="p-4 border rounded-3 bg-campus-light h-100 shadow-sm hover-shadow-md transition-smooth">
                                <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php echo esc_html( $child->post_title ); ?></h3>
                                <?php if ( has_excerpt( $child->ID ) ) : ?>
                                <p class="text-campus-muted small mb-0"><?php echo esc_html( get_the_excerpt( $child->ID ) ); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php endwhile; endif; ?>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>
