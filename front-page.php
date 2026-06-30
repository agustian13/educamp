<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$section_keys = array_merge(
    array( 'hero', 'stats', 'rector', 'faculties', 'programs' ),
    array( 'news', 'announcements', 'events', 'achievements' ),
    array( 'partnerships', 'testimonials', 'gallery', 'pmb_cta' )
);

$saved_order = get_theme_mod( 'educampus_section_order', '' );
$ordered = array();
if ( ! empty( $saved_order ) ) {
    $decoded = json_decode( $saved_order, true );
    if ( is_array( $decoded ) && ! empty( $decoded ) ) {
        $ordered = $decoded;
    }
}
if ( empty( $ordered ) ) {
    $ordered = $section_keys;
} else {
    $missing = array_diff( $section_keys, $ordered );
    $ordered = array_merge( $ordered, $missing );
}

foreach ( $ordered as $key ) {
    $show = get_theme_mod( "educampus_show_{$key}", true );
    if ( $show ) {
        $template_file = get_template_directory() . '/template-parts/section-' . $key . '.php';
        if ( file_exists( $template_file ) ) {
            include $template_file;
        }
    }
}
?>

<?php
get_footer();
