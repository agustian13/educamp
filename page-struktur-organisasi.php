<?php
/**
 * Template Name: Struktur Organisasi Page Layout
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$flower_svg = get_template_directory_uri() . '/assets/images/icons/flower-sec.svg';
$hero_image = get_theme_mod( 'educampus_profile_hero_image', '' );
$has_hero   = ! empty( $hero_image );

// Build lecturer maps for auto-link names in org structure
$lecturer_name_map = array();
$lecturer_nidn_map = array();
$all_lecturers = get_posts( array(
    'post_type'      => 'lecturer',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
) );
foreach ( $all_lecturers as $lid ) {
    $url = get_permalink( $lid );
    $lecturer_name_map[ sanitize_title( get_the_title( $lid ) ) ] = $url;
    $nidn = get_post_meta( $lid, '_lecturer_nidn', true );
    if ( $nidn ) {
        $lecturer_nidn_map[ $nidn ] = $url;
    }
}

function educampus_get_dosen_url( $unit_post, $lecturer_name_map, $lecturer_nidn_map ) {
    $nidn = get_post_meta( $unit_post->ID, '_unit_nidn', true );
    if ( $nidn && isset( $lecturer_nidn_map[ $nidn ] ) ) {
        return $lecturer_nidn_map[ $nidn ];
    }
    $name_key = sanitize_title( $unit_post->post_title );
    if ( isset( $lecturer_name_map[ $name_key ] ) ) {
        return $lecturer_name_map[ $name_key ];
    }
    return '';
}

function educampus_get_department_badge_name( $position ) {
    // Strip common job title prefixes to get just the unit/department name
    $prefixes = array(
        'wakil dekan', 'wakil direktur', 'wakil rektor', 'wakil ketua', 'wakil kepala',
        'dekan', 'direktur', 'rektor', 'ketua', 'kepala',
        'sekretaris', 'koordinator', 'bendahara', 'anggota', 'staf ahli',
        'pembantu dekan', 'pembantu rektor', 'pembantu direktur',
    );
    $clean = strtolower( trim( $position ) );
    foreach ( $prefixes as $prefix ) {
        if ( strpos( $clean, $prefix . ' ' ) === 0 ) {
            $clean = substr( $clean, strlen( $prefix ) + 1 );
            break;
        }
    }
    return ucwords( trim( $clean ) );
}
?>

<?php educampus_page_hero( array(
    'title' => esc_html__( 'Struktur Organisasi', 'educampus' ),
    'badge' => esc_html__( 'TENTANG KAMPUS', 'educampus' ),
    'image' => get_theme_mod( 'educampus_profile_hero_image', '' ),
) ); ?>

<div class="container my-4 pb-4">
    <?php educampus_breadcrumbs(); ?>
    <div class="row g-3 mt-1">
        <main id="primary" class="col-12 site-main">
            <section id="tab-organisasi" class="usu-section">

                <?php
                $org_type  = get_theme_mod( 'educampus_org_type', 'visual_tree' );
                $org_image = get_theme_mod( 'educampus_org_image', '' );

                if ( $org_type === 'image_only' && ! empty( $org_image ) ) :
                ?>
                <div class="bg-white p-4 rounded-campus shadow-campus-soft text-center border">
                    <p class="text-campus-muted small mb-3"><i class="bi bi-info-circle me-1"></i> Klik gambar untuk melihat resolusi penuh.</p>
                    <a href="<?php echo esc_url( $org_image ); ?>" target="_blank" class="d-inline-block position-relative overflow-hidden rounded-3 border">
                        <img loading="lazy" src="<?php echo esc_url( $org_image ); ?>" alt="Bagan Struktur Organisasi" class="img-fluid img-hover-zoom" style="max-height: 500px; object-fit: contain;">
                        <div class="zoom-overlay position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 transition-smooth" style="transition: all 0.3s ease;">
                            <i class="bi bi-zoom-in text-white fs-3"></i>
                        </div>
                    </a>
                </div>
                <?php else : ?>

                <?php
                $unit_groups = get_terms( array(
                    'taxonomy'   => 'unit_group',
                    'hide_empty' => false,
                    'orderby'    => 'term_id',
                    'order'      => 'ASC',
                ) );

                // Determine active group slug from URL parameter or default to first non-empty group
                $active_group_slug = isset( $_GET['group'] ) ? sanitize_key( $_GET['group'] ) : '';
                $active_group = null;

                // Validate and find active group object
                if ( ! empty( $unit_groups ) ) {
                    foreach ( $unit_groups as $group ) {
                        // Check if group has units
                        $units_check = get_posts( array(
                            'post_type'      => 'unit',
                            'posts_per_page' => 1,
                            'tax_query'      => array( array(
                                'taxonomy' => 'unit_group',
                                'field'    => 'term_id',
                                'terms'    => $group->term_id,
                            ) ),
                        ) );
                        if ( empty( $units_check ) ) {
                            continue;
                        }
                        if ( empty( $active_group_slug ) ) {
                            $active_group_slug = $group->slug;
                            $active_group = $group;
                            break;
                        } elseif ( $group->slug === $active_group_slug ) {
                            $active_group = $group;
                            break;
                        }
                    }
                    // Fallback if the specified group slug is invalid/empty of units
                    if ( ! $active_group ) {
                        foreach ( $unit_groups as $group ) {
                            $units_check = get_posts( array(
                                'post_type'      => 'unit',
                                'posts_per_page' => 1,
                                'tax_query'      => array( array(
                                    'taxonomy' => 'unit_group',
                                    'field'    => 'term_id',
                                    'terms'    => $group->term_id,
                                ) ),
                            ) );
                            if ( ! empty( $units_check ) ) {
                                $active_group_slug = $group->slug;
                                $active_group = $group;
                                break;
                            }
                        }
                    }
                }
                ?>
                <div class="row g-4 align-items-start">
                    <!-- Sidebar Navigation (USU-style per-group page navigation) -->
                    <div class="col-lg-3">
                        <div class="usu-sidebar-nav bg-white rounded-3 overflow-hidden shadow-campus-soft border border-light-subtle">
                            <div class="usu-sidebar-header px-3 py-2" style="background-color: var(--color-primary);">
                                <h6 class="mb-0 text-white font-heading fw-bold" style="font-size: 0.82rem; letter-spacing: 0.5px; text-transform: uppercase;">Struktur Organisasi</h6>
                            </div>
                            <nav class="usu-sidebar-menu">
                                <?php
                                foreach ( $unit_groups as $group ) :
                                    $has_units = ! empty( get_posts( array(
                                        'post_type'      => 'unit',
                                        'posts_per_page' => 1,
                                        'tax_query'      => array( array(
                                            'taxonomy' => 'unit_group',
                                            'field'    => 'term_id',
                                            'terms'    => $group->term_id,
                                        ) ),
                                    ) ) );
                                    if ( ! $has_units ) continue;
                                    $is_active = ( $group->slug === $active_group_slug );
                                ?>
                                <a href="<?php echo esc_url( add_query_arg( 'group', $group->slug, get_permalink() ) ); ?>" class="usu-sidebar-link d-flex align-items-center gap-2 px-3 py-2 border-bottom text-decoration-none<?php echo $is_active ? ' active' : ''; ?>" style="border-color: #f1f5f9 !important; font-size: 0.85rem; transition: all 0.2s ease;">
                                    <i class="bi bi-chevron-right usu-sidebar-chevron" style="font-size: 0.65rem; flex-shrink: 0; transition: transform 0.2s ease;"></i>
                                    <span class="font-heading fw-semibold"><?php echo esc_html( $group->name ); ?></span>
                                </a>
                                <?php endforeach; ?>
                            </nav>
                        </div>
                    </div>

                    <!-- Content Area for Active Group -->
                    <div class="col-lg-9">
                        <?php
                        if ( $active_group ) :
                            $units = get_posts( array(
                                'post_type'      => 'unit',
                                'posts_per_page' => -1,
                                'tax_query'      => array( array(
                                    'taxonomy' => 'unit_group',
                                    'field'    => 'term_id',
                                    'terms'    => $active_group->term_id,
                                ) ),
                                'orderby' => 'menu_order',
                                'order'   => 'ASC',
                            ) );

                            // Group units by parent for hierarchical rendering (parent-child grouping)
                            $hierarchical_units = array();
                            $children_by_parent = array();

                            foreach ( $units as $unit ) {
                                $parent_id = $unit->post_parent;
                                if ( $parent_id > 0 ) {
                                    $children_by_parent[ $parent_id ][] = $unit;
                                }
                            }

                            // Add parent units and immediately follow them with their children
                            foreach ( $units as $unit ) {
                                if ( 0 == $unit->post_parent ) {
                                    $hierarchical_units[] = $unit;
                                    if ( isset( $children_by_parent[ $unit->ID ] ) ) {
                                        usort( $children_by_parent[ $unit->ID ], function( $a, $b ) {
                                            return $a->menu_order <=> $b->menu_order;
                                        } );
                                        foreach ( $children_by_parent[ $unit->ID ] as $child ) {
                                            $hierarchical_units[] = $child;
                                        }
                                    }
                                }
                            }

                            // Append any orphan units
                            foreach ( $units as $unit ) {
                                if ( ! in_array( $unit, $hierarchical_units, true ) ) {
                                    $hierarchical_units[] = $unit;
                                }
                            }

                            $units = $hierarchical_units;
                        ?>
                            <div class="mb-4">
                                <!-- Group Title Header -->
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: var(--color-primary);">
                                        <i class="bi bi-diagram-3-fill text-white" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 font-heading fw-bold text-campus-navy" style="font-size: 1rem;"><?php echo esc_html( $active_group->name ); ?></h4>
                                        <?php if ( $active_group->description ) : ?>
                                        <p class="mb-0 text-muted small mt-1"><?php echo esc_html( $active_group->description ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- View Toggle -->
                                <div class="d-flex align-items-center justify-content-end gap-2 mb-2">
                                    <span class="small text-campus-muted d-none d-sm-inline">Tampilan:</span>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                                        <button type="button" class="btn btn-outline-campus-primary org-view-btn active" data-view="grid" title="Grid View">
                                            <i class="bi bi-grid-3x3-gap-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-campus-primary org-view-btn" data-view="list" title="List View">
                                            <i class="bi bi-list-ul"></i>
                                        </button>
                                    </div>
                                </div>

                                <?php
                                // Build unit data array
                                $units_data = array();
                                $current_parent_id = -1;
                                $is_rektorat = in_array( $active_group->slug, array( 'rektorat', 'pimpinan-rektorat', 'pimpinan-universitas' ), true );

                                foreach ( $units as $unit ) :
                                    $position = get_post_meta( $unit->ID, '_unit_position_title', true );
                                    $email    = get_post_meta( $unit->ID, '_unit_email', true );
                                    $phone    = get_post_meta( $unit->ID, '_unit_phone', true );
                                    $photo_url = get_post_meta( $unit->ID, '_unit_photo_url', true );
                                    $thumb_id  = get_post_thumbnail_id( $unit->ID );
                                    $img_src   = $photo_url ?: ( $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '' );
                                    $dosen_url = educampus_get_dosen_url( $unit, $lecturer_name_map, $lecturer_nidn_map );
                                    $is_root = ( 0 == $unit->post_parent );
                                    $root_id = $is_root ? $unit->ID : $unit->post_parent;

                                    if ( ! $is_rektorat && $root_id !== $current_parent_id ) {
                                        $current_parent_id = $root_id;
                                        $root_position = get_post_meta( $root_id, '_unit_position_title', true );
                                        $dept_name = educampus_get_department_badge_name( $root_position );
                                    } else {
                                        $dept_name = '';
                                    }

                                    $units_data[] = array(
                                        'id'         => $unit->ID,
                                        'title'      => $unit->post_title,
                                        'position'   => $position,
                                        'email'      => $email,
                                        'phone'      => $phone,
                                        'img_src'    => $img_src,
                                        'dosen_url'  => $dosen_url,
                                        'is_rektor'  => $is_rektorat && ( stripos( $position, 'rektor' ) !== false && stripos( $position, 'wakil' ) === false ),
                                        'is_rektorat'=> $is_rektorat,
                                        'is_root'    => $is_root,
                                        'root_id'    => $root_id,
                                        'dept_name'  => $dept_name,
                                        'excerpt'    => ( ( $excerpt = get_the_excerpt( $unit->ID ) ) ? $excerpt : ( $unit->post_content ? wp_trim_words( $unit->post_content, 40 ) : '' ) ),
                                    );
                                endforeach;
                                ?>

                                <!-- Grid View -->
                                <div class="org-view-grid">
                                    <div class="row g-3">
                                        <?php
                                        $rendered_parent = -1;
                                        foreach ( $units_data as $item ) :
                                            if ( ! $is_rektorat && $item['root_id'] !== $rendered_parent ) {
                                                $rendered_parent = $item['root_id'];
                                                if ( $item['dept_name'] ) :
                                        ?>
                                            <div class="col-12 mt-3 mb-1">
                                                <span class="badge badge-academic-dept py-1 px-3 font-heading fw-bold d-inline-flex align-items-center gap-2">
                                                    <i class="bi bi-house-fill text-campus-gold"></i> <?php echo esc_html( $item['dept_name'] ); ?>
                                                </span>
                                            </div>
                                        <?php
                                                endif;
                                            }

                                            if ( $item['is_rektorat'] ) :
                                        ?>
                                            <div class="col-12 mb-3">
                                                <div class="card rounded-3 overflow-hidden transition-smooth" style="background:transparent;border:none !important;">
                                                    <div class="row g-0 align-items-center">
                                                        <div class="col-md-3 text-center p-3">
                                                            <div class="member-photo rounded-3 overflow-hidden d-inline-block shadow-sm" style="width:<?php echo $item['is_rektor'] ? '140' : '130'; ?>px;height:<?php echo $item['is_rektor'] ? '185' : '170'; ?>px;border:2px solid var(--color-primary) !important;padding:4px;background-color:#fff;">
                                                                <?php if ( $item['img_src'] ) : ?>
                                                                    <img loading="lazy" src="<?php echo esc_url( $item['img_src'] ); ?>" class="w-100 h-100 object-fit-cover rounded-2" alt="<?php echo esc_attr( $item['title'] ); ?>">
                                                                <?php else : ?>
                                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                                        <i class="bi bi-person-fill text-muted" style="font-size:3.5rem;"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9 p-3 border-start-md" style="border-left:1px solid #f1f5f9;">
                                                            <div class="d-flex flex-column justify-content-between h-100">
                                                                <div>
                                                                    <?php if ( $item['dosen_url'] ) : ?>
                                                                        <a href="<?php echo esc_url( $item['dosen_url'] ); ?>" class="text-decoration-none">
                                                                            <h5 class="font-heading fw-bold mb-1" style="color:var(--color-primary) !important;font-size:<?php echo $item['is_rektor'] ? '1.25' : '1.15'; ?>rem;">
                                                                                <?php echo esc_html( $item['title'] ); ?>
                                                                                <i class="bi bi-box-arrow-up-right" style="font-size:0.85rem;opacity:0.5;"></i>
                                                                            </h5>
                                                                        </a>
                                                                    <?php else : ?>
                                                                        <h5 class="font-heading fw-bold mb-1" style="color:var(--color-primary) !important;font-size:<?php echo $item['is_rektor'] ? '1.25' : '1.15'; ?>rem;">
                                                                            <?php echo esc_html( $item['title'] ); ?>
                                                                        </h5>
                                                                    <?php endif; ?>
                                                                    <?php if ( $item['position'] ) : ?>
                                                                        <div class="text-secondary fw-semibold font-body mb-2" style="font-size:0.85rem;color:#495057;"><?php echo esc_html( $item['position'] ); ?></div>
                                                                    <?php endif; ?>
                                                                    <div class="text-muted font-body mb-2" style="font-size:0.82rem;line-height:1.5;"><?php echo esc_html( $item['excerpt'] ?: 'Profil singkat pejabat universitas belum diisi.' ); ?></div>
                                                                    <?php if ( $item['dosen_url'] ) : ?>
                                                                        <div class="mb-2">
                                                                            <a href="<?php echo esc_url( $item['dosen_url'] ); ?>" class="btn px-4 py-2 font-heading rounded-2 text-white text-decoration-none d-inline-flex align-items-center gap-2" style="font-size:0.8rem;background-color:var(--color-primary);">
                                                                                Profil<?php echo $item['is_rektor'] ? ' Rektor' : ''; ?> <i class="bi bi-arrow-right"></i>
                                                                            </a>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="d-flex flex-wrap align-items-center gap-3 pt-2 border-top border-light-subtle font-body text-secondary" style="font-size:0.78rem;">
                                                                    <?php if ( $item['email'] ) : ?>
                                                                        <div><i class="bi bi-envelope me-2" style="color:var(--color-primary);"></i> <?php echo esc_html( $item['email'] ); ?></div>
                                                                    <?php endif; ?>
                                                                    <?php if ( $item['phone'] ) : ?>
                                                                        <div><i class="bi bi-telephone me-2" style="color:var(--color-primary);"></i> <?php echo esc_html( $item['phone'] ); ?></div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="card rounded-3 overflow-hidden h-100 d-flex flex-column transition-smooth" style="background:transparent;border:none !important;">
                                                    <div class="p-2 flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="member-photo rounded-3 overflow-hidden flex-shrink-0" style="width:80px;height:105px;border:1px solid #e2e8f0;background-color:#f8fafc;">
                                                                <?php if ( $item['img_src'] ) : ?>
                                                                    <img loading="lazy" src="<?php echo esc_url( $item['img_src'] ); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo esc_attr( $item['title'] ); ?>">
                                                                <?php else : ?>
                                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                                        <i class="bi bi-person-fill text-muted" style="font-size:2rem;"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="min-width-0">
                                                                <?php if ( $item['dosen_url'] ) : ?>
                                                                    <a href="<?php echo esc_url( $item['dosen_url'] ); ?>" class="text-decoration-none">
                                                                        <h6 class="font-heading fw-bold mb-1 text-wrap" style="font-size:0.95rem;color:var(--color-primary) !important;">
                                                                            <?php echo esc_html( $item['title'] ); ?>
                                                                            <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;opacity:0.4;"></i>
                                                                        </h6>
                                                                    </a>
                                                                <?php else : ?>
                                                                    <h6 class="font-heading fw-bold mb-1 text-wrap" style="font-size:0.95rem;color:var(--color-primary) !important;">
                                                                        <?php echo esc_html( $item['title'] ); ?>
                                                                    </h6>
                                                                <?php endif; ?>
                                                                <?php if ( $item['position'] ) : ?>
                                                                    <div class="text-secondary small fw-medium" style="font-size:0.76rem;color:#6b7280;"><?php echo esc_html( $item['position'] ); ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="px-2 pb-2 border-top pt-1" style="font-size:0.75rem;border-top-color:#e2e8f0 !important;">
                                                        <div class="d-flex flex-column gap-1">
                                                            <?php if ( $item['email'] ) : ?>
                                                                <div class="text-secondary text-truncate"><i class="bi bi-envelope me-1.5" style="color:var(--color-primary);"></i> <?php echo esc_html( $item['email'] ); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ( $item['phone'] ) : ?>
                                                                <div class="text-secondary text-truncate"><i class="bi bi-telephone me-1.5" style="color:var(--color-primary);"></i> <?php echo esc_html( $item['phone'] ); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; endforeach; ?>
                                    </div>
                                </div>

                                <!-- List View with Collapse per Induk -->
                                <?php
                                // Group by parent (root_id)
                                $parent_groups = array();
                                $parent_details = array();
                                foreach ( $units_data as $item ) {
                                    $root_id = $item['root_id'];
                                    if ( ! isset( $parent_groups[ $root_id ] ) ) {
                                        $parent_groups[ $root_id ] = array();
                                    }
                                    $parent_groups[ $root_id ][] = $item;
                                    if ( $item['is_root'] ) {
                                        $parent_details[ $root_id ] = $item;
                                    }
                                }
                                $collapse_idx = 0;
                                ?>
                                <div class="org-position-list">
                                    <?php foreach ( $parent_groups as $root_id => $items ) : $collapse_idx++;
                                        $parent = $parent_details[ $root_id ] ?? $items[0];
                                    ?>
                                    <div class="card border-0 shadow-campus-soft rounded-campus bg-white mb-2 overflow-hidden">
                                        <div class="card-header bg-transparent border-0 p-0" id="pos-heading-<?php echo $collapse_idx; ?>">
                                            <button class="btn btn-light w-100 text-start d-flex align-items-center gap-2 px-3 py-2 rounded-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#pos-collapse-<?php echo $collapse_idx; ?>" aria-expanded="true" aria-controls="pos-collapse-<?php echo $collapse_idx; ?>" style="font-size:0.9rem;">
                                                <i class="bi bi-chevron-down transition-smooth" style="font-size:0.7rem;transition:transform 0.2s;"></i>
                                                <span class="fw-bold font-heading text-campus-navy flex-grow-1">
                                                    <?php echo esc_html( $parent['position'] ?: $parent['title'] ); ?>
                                                </span>
                                                <span class="text-campus-muted small me-2 fw-normal"><?php echo esc_html( $parent['title'] ); ?></span>
                                                <span class="badge bg-campus-gold text-campus-navy rounded-pill small"><?php echo count( $items ); ?></span>
                                            </button>
                                        </div>
                                        <div id="pos-collapse-<?php echo $collapse_idx; ?>" class="collapse show" aria-labelledby="pos-heading-<?php echo $collapse_idx; ?>">
                                            <div class="card-body p-0">
                                                <?php foreach ( $items as $item ) : ?>
                                                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom border-light-subtle <?php echo $item['is_root'] ? 'bg-campus-light bg-opacity-25' : ''; ?>">
                                                    <div class="flex-grow-1 min-w-0">
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <?php if ( $item['dosen_url'] ) : ?>
                                                            <a href="<?php echo esc_url( $item['dosen_url'] ); ?>" class="text-decoration-none fw-bold font-heading small" style="color:var(--color-primary);">
                                                                <?php echo esc_html( $item['title'] ); ?>
                                                                <i class="bi bi-box-arrow-up-right" style="font-size:0.6rem;opacity:0.4;"></i>
                                                            </a>
                                                            <?php else : ?>
                                                            <span class="fw-bold font-heading small text-campus-navy"><?php echo esc_html( $item['title'] ); ?></span>
                                                            <?php endif; ?>
                                                            <?php if ( ! $item['is_root'] && $item['position'] ) : ?>
                                                            <span class="small text-campus-muted">— <?php echo esc_html( $item['position'] ); ?></span>
                                                            <?php endif; ?>
                                                            <?php if ( $item['email'] ) : ?>
                                                            <span class="small text-campus-muted"><i class="bi bi-envelope me-1" style="color:var(--color-primary);"></i><?php echo esc_html( $item['email'] ); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php if ( $item['dosen_url'] ) : ?>
                                                    <a href="<?php echo esc_url( $item['dosen_url'] ); ?>" class="btn btn-sm btn-outline-campus-primary flex-shrink-0 d-none d-sm-inline-block" style="font-size:0.75rem;">Profil</a>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <style>
                                .org-position-list .card-header button .bi-chevron-down {
                                    transition: transform 0.2s ease;
                                }
                                .org-position-list .card-header button[aria-expanded="false"] .bi-chevron-down {
                                    transform: rotate(-90deg);
                                }
                                .org-position-list .card-header button:hover {
                                    background-color: #f8fafc !important;
                                }
                                .org-position-list .card-body > div:last-child {
                                    border-bottom: none !important;
                                }
                                </style>

                                <script>
                                (function() {
                                    var btns = document.querySelectorAll('.org-view-btn');
                                    var grid = document.querySelector('.org-view-grid');
                                    var list = document.querySelector('.org-position-list');
                                    var stored = localStorage.getItem('org_view') || 'grid';

                                    function setView(v) {
                                        btns.forEach(function(b) {
                                            b.classList.toggle('active', b.dataset.view === v);
                                        });
                                        if (grid) grid.style.display = v === 'grid' ? '' : 'none';
                                        if (list) list.style.display = v === 'list' ? '' : 'none';
                                        localStorage.setItem('org_view', v);
                                    }
                                    btns.forEach(function(b) {
                                        b.addEventListener('click', function() { setView(this.dataset.view); });
                                    });
                                    setView(stored);
                                })();
                                </script>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<style>
    .img-hover-zoom { transition: var(--transition-smooth); }
    a:hover .img-hover-zoom { transform: scale(1.03); }
    a:hover .zoom-overlay { opacity: 1 !important; }
    
    /* USU-style Sidebar Navigation */
    .usu-sidebar-link {
        color: #374151;
        background-color: transparent;
    }
    .usu-sidebar-link:hover {
        background-color: #f0fdf4 !important;
        color: var(--color-primary) !important;
        padding-left: 1.6rem !important;
    }
    .usu-sidebar-link:hover .usu-sidebar-chevron {
        transform: translateX(3px);
    }
    .usu-sidebar-link.active {
        background-color: var(--color-primary) !important;
        color: #fff !important;
    }
    .usu-sidebar-link.active .usu-sidebar-chevron {
        color: rgba(255,255,255,0.8);
    }
    .usu-sidebar-link.active:hover {
        background-color: var(--color-primary) !important;
        color: #fff !important;
        padding-left: 1rem !important;
    }

    /* Avatar stack design for Wakil Rektor subordinate preview */
    .avatar-stack {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-transform: uppercase;
    }

    /* Force remove Bootstrap default card border */
    #tab-organisasi .card {
        border: 0 !important;
        box-shadow: none !important;
    }
</style>

<?php get_footer(); ?>
