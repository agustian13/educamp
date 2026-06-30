<?php
/**
 * Register Custom Post Types and Taxonomies
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register CPTs and Taxonomies
 */
function educampus_register_cpts() {

    // Helper array to register CPTs quickly
    $cpts = array(
        'news' => array(
            'singular' => 'Berita',
            'plural'   => 'Berita',
            'icon'     => 'dashicons-admin-post',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
            'has_archive' => 'berita', // Slug: /berita
            'hierarchical' => false,
        ),
        'faculty' => array(
            'singular' => 'Fakultas',
            'plural'   => 'Fakultas',
            'icon'     => 'dashicons-welcome-learn-more',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'has_archive' => 'faculty',
            'hierarchical' => false,
        ),
        'program' => array(
            'singular' => 'Program Studi',
            'plural'   => 'Program Studi',
            'icon'     => 'dashicons-media-text',
            'supports' => array( 'title', 'thumbnail', 'excerpt', 'revisions' ),
            'has_archive' => 'program',
            'hierarchical' => false,
        ),
        'lecturer' => array(
            'singular' => 'Dosen',
            'plural'   => 'Dosen',
            'icon'     => 'dashicons-businessman',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'has_archive' => 'lecturer',
            'hierarchical' => false,
        ),
        'announcement' => array(
            'singular' => 'Pengumuman',
            'plural'   => 'Pengumuman',
            'icon'     => 'dashicons-megaphone',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'has_archive' => 'pengumuman',
            'hierarchical' => false,
        ),
        'event' => array(
            'singular' => 'Agenda',
            'plural'   => 'Agenda',
            'icon'     => 'dashicons-calendar-alt',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'has_archive' => 'agenda',
            'hierarchical' => false,
        ),
        'achievement' => array(
            'singular' => 'Prestasi',
            'plural'   => 'Prestasi',
            'icon'     => 'dashicons-awards',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'has_archive' => 'prestasi',
            'hierarchical' => false,
        ),
        'partnership' => array(
            'singular' => 'Kemitraan',
            'plural'   => 'Kemitraan',
            'icon'     => 'dashicons-groups',
            'supports' => array( 'title', 'thumbnail' ),
            'has_archive' => false,
            'hierarchical' => false,
        ),
        'alumni' => array(
            'singular' => 'Alumni',
            'plural'   => 'Alumni',
            'icon'     => 'dashicons-testimonial',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'has_archive' => false,
            'hierarchical' => false,
        ),
        'gallery' => array(
            'singular' => 'Galeri',
            'plural'   => 'Galeri',
            'icon'     => 'dashicons-format-gallery',
            'supports' => array( 'title', 'thumbnail', 'excerpt' ),
            'has_archive' => 'galeri',
            'hierarchical' => false,
        ),
        'slide' => array(
            'singular' => 'Slide Slider',
            'plural'   => 'Slide Slider',
            'icon'     => 'dashicons-images-alt2',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'has_archive' => false,
            'hierarchical' => false,
        ),
        'dokumen' => array(
            'singular' => 'Dokumen',
            'plural'   => 'Dokumen',
            'icon'     => 'dashicons-media-document',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'has_archive' => 'dokumen',
            'hierarchical' => false,
        ),
        'unit' => array(
            'singular' => 'Unit Organisasi',
            'plural'   => 'Unit Organisasi',
            'icon'     => 'dashicons-networking',
            'supports' => array( 'title', 'thumbnail', 'page-attributes', 'excerpt' ),
            'has_archive' => false,
            'hierarchical' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-networking',
        ),
    );

    foreach ( $cpts as $slug => $cpt ) {
        $labels = array(
            'name'                  => $cpt['plural'],
            'singular_name'         => $cpt['singular'],
            'menu_name'             => $cpt['plural'],
            'name_admin_bar'        => $cpt['singular'],
            'add_new'               => __( 'Tambah Baru', 'educampus' ),
            'add_new_item'          => sprintf( __( 'Tambah %s Baru', 'educampus' ), $cpt['singular'] ),
            'new_item'              => sprintf( __( '%s Baru', 'educampus' ), $cpt['singular'] ),
            'edit_item'             => sprintf( __( 'Edit %s', 'educampus' ), $cpt['singular'] ),
            'view_item'             => sprintf( __( 'Lihat %s', 'educampus' ), $cpt['singular'] ),
            'all_items'             => sprintf( __( 'Semua %s', 'educampus' ), $cpt['plural'] ),
            'search_items'          => sprintf( __( 'Cari %s', 'educampus' ), $cpt['plural'] ),
            'parent_item_colon'     => sprintf( __( 'Induk %s:', 'educampus' ), $cpt['singular'] ),
            'not_found'             => sprintf( __( 'Tidak ada %s ditemukan.', 'educampus' ), strtolower( $cpt['plural'] ) ),
            'not_found_in_trash'    => sprintf( __( 'Tidak ada %s ditemukan di Trash.', 'educampus' ), strtolower( $cpt['plural'] ) ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $cpt['has_archive'] ? $cpt['has_archive'] : $slug, 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => $cpt['has_archive'],
            'hierarchical'       => $cpt['hierarchical'],
            'menu_position'      => isset( $cpt['menu_position'] ) ? $cpt['menu_position'] : 5,
            'menu_icon'          => $cpt['icon'],
            'supports'           => $cpt['supports'],
            'show_in_rest'       => true, // Enable Block Editor (Gutenberg)
        );

        register_post_type( $slug, $args );
    }

    // Register custom category taxonomy for News Custom Post Type
    // Registered for both 'news' and 'post' to support existing sites
    register_taxonomy( 'news_category', array( 'news', 'post' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => __( 'Kategori Berita', 'educampus' ),
            'singular_name'     => __( 'Kategori Berita', 'educampus' ),
            'search_items'      => __( 'Cari Kategori Berita', 'educampus' ),
            'all_items'         => __( 'Semua Kategori Berita', 'educampus' ),
            'parent_item'       => __( 'Induk Kategori Berita', 'educampus' ),
            'parent_item_colon' => __( 'Induk Kategori Berita:', 'educampus' ),
            'edit_item'         => __( 'Edit Kategori Berita', 'educampus' ),
            'update_item'       => __( 'Update Kategori Berita', 'educampus' ),
            'add_new_item'      => __( 'Tambah Kategori Berita Baru', 'educampus' ),
            'new_item_name'     => __( 'Nama Kategori Berita Baru', 'educampus' ),
            'menu_name'         => __( 'Kategori Berita', 'educampus' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'news-category' ),
        'show_in_rest'      => true,
    ) );
    
    // Register custom category taxonomy for Program Custom Post Type (e.g. Diploma, Sarjana, Magister, Doktor)
    register_taxonomy( 'program_level', 'program', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => __( 'Jenjang Pendidikan', 'educampus' ),
            'singular_name'     => __( 'Jenjang Pendidikan', 'educampus' ),
            'search_items'      => __( 'Cari Jenjang', 'educampus' ),
            'all_items'         => __( 'Semua Jenjang', 'educampus' ),
            'edit_item'         => __( 'Edit Jenjang', 'educampus' ),
            'update_item'       => __( 'Update Jenjang', 'educampus' ),
            'add_new_item'      => __( 'Tambah Jenjang Baru', 'educampus' ),
            'new_item_name'     => __( 'Nama Jenjang Baru', 'educampus' ),
            'menu_name'         => __( 'Jenjang Pendidikan', 'educampus' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'program-level' ),
        'show_in_rest'      => true,
    ) );

    // Register taxonomy for grouping organizational units
    register_taxonomy( 'unit_group', 'unit', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => __( 'Grup Unit', 'educampus' ),
            'singular_name'     => __( 'Grup Unit', 'educampus' ),
            'search_items'      => __( 'Cari Grup Unit', 'educampus' ),
            'all_items'         => __( 'Semua Grup Unit', 'educampus' ),
            'parent_item'       => __( 'Induk Grup Unit', 'educampus' ),
            'parent_item_colon' => __( 'Induk Grup Unit:', 'educampus' ),
            'edit_item'         => __( 'Edit Grup Unit', 'educampus' ),
            'update_item'       => __( 'Update Grup Unit', 'educampus' ),
            'add_new_item'      => __( 'Tambah Grup Unit Baru', 'educampus' ),
            'new_item_name'     => __( 'Nama Grup Unit Baru', 'educampus' ),
            'menu_name'         => __( 'Grup Unit', 'educampus' ),
        ),
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'unit-group' ),
        'show_in_rest'      => true,
    ) );

    // Register taxonomy for document categories
    register_taxonomy( 'dokumen_category', 'dokumen', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => __( 'Kategori Dokumen', 'educampus' ),
            'singular_name'     => __( 'Kategori Dokumen', 'educampus' ),
            'search_items'      => __( 'Cari Kategori', 'educampus' ),
            'all_items'         => __( 'Semua Kategori', 'educampus' ),
            'parent_item'       => __( 'Induk Kategori', 'educampus' ),
            'parent_item_colon' => __( 'Induk Kategori:', 'educampus' ),
            'edit_item'         => __( 'Edit Kategori', 'educampus' ),
            'update_item'       => __( 'Update Kategori', 'educampus' ),
            'add_new_item'      => __( 'Tambah Kategori Baru', 'educampus' ),
            'new_item_name'     => __( 'Nama Kategori Baru', 'educampus' ),
            'menu_name'         => __( 'Kategori Dokumen', 'educampus' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'dokumen-category' ),
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'educampus_register_cpts' );

// Tambah menu Grup Unit langsung di bawah Unit Organisasi
function educampus_add_unit_group_submenu() {
    add_submenu_page(
        'edit.php?post_type=unit',
        __( 'Grup Unit', 'educampus' ),
        __( 'Grup Unit', 'educampus' ),
        'manage_categories',
        'edit-tags.php?taxonomy=unit_group&post_type=unit'
    );
}
add_action( 'admin_menu', 'educampus_add_unit_group_submenu' );

/**
 * Seed default unit_group taxonomy terms on theme activation.
 */
function educampus_seed_unit_groups() {
    if ( get_option( 'educampus_unit_groups_seeded', false ) ) {
        return;
    }

    $groups = array(
        'yayasan'  => esc_html__( 'Pembina Yayasan', 'educampus' ),
        'senat'    => esc_html__( 'Senat Akademik', 'educampus' ),
        'rektorat' => esc_html__( 'Pimpinan Rektorat', 'educampus' ),
        'dekanat'  => esc_html__( 'Dekan & Pascasarjana', 'educampus' ),
        'lembaga'  => esc_html__( 'Lembaga & Biro', 'educampus' ),
    );

    foreach ( $groups as $slug => $name ) {
        if ( ! term_exists( $slug, 'unit_group' ) ) {
            wp_insert_term( $name, 'unit_group', array( 'slug' => $slug ) );
        }
    }

    update_option( 'educampus_unit_groups_seeded', true );
}
add_action( 'init', 'educampus_seed_unit_groups' );

/**
 * Enqueue admin scripts for media uploader on faculty/program edit screens
 */
function educampus_admin_enqueue_scripts( $hook ) {
    global $post_type;
    if ( ! in_array( $post_type, array( 'faculty', 'program', 'lecturer', 'unit' ), true ) ) {
        return;
    }
    if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ), true ) ) {
        return;
    }

    $css = '
    .educampus-meta-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 16px 18px;
        margin: 18px 0;
    }
    .educampus-meta-section h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #0a2540;
    }
    .educampus-meta-section .description {
        margin-top: -6px;
        margin-bottom: 12px;
    }
    #educampus_lecturer_details_metabox .inside {
        margin: 0;
        padding: 12px;
    }
    #educampus_lecturer_details_metabox .form-table th {
        width: 160px;
    }
    #educampus_lecturer_details_metabox .form-table td {
        padding: 10px 0;
    }
    #educampus_lecturer_details_metabox hr {
        border: 0;
        border-top: 1px solid #e2e8f0;
        margin: 20px 0;
    }
    #educampus_lecturer_details_metabox textarea.large-text {
        width: 100%;
        max-width: 600px;
    }
    ';

    if ( $post_type === 'lecturer' ) {
        wp_add_inline_style( 'wp-admin', $css );
    }

    if ( in_array( $post_type, array( 'faculty', 'program', 'unit' ), true ) ) {
        wp_enqueue_media();
    wp_add_inline_script( 'jquery', '
    jQuery( document ).ready( function( $ ) {
        $( document ).on( "click", ".educampus-media-btn", function( e ) {
            e.preventDefault();
            var btn    = $( this );
            var input  = btn.closest( ".educampus-media-wrap" ).find( ".educampus-media-input" );
            var preview = btn.closest( ".educampus-media-wrap" ).find( ".educampus-media-preview img" );
            var frame  = wp.media({
                title:    btn.data( "title" ) || "Pilih Media",
                button:   { text: "Pilih" },
                multiple: false
            });
            frame.on( "select", function() {
                var attachment = frame.state().get( "selection" ).first().toJSON();
                input.val( attachment.url );
                if ( preview.length ) {
                    preview.attr( "src", attachment.url );
                    btn.closest( ".educampus-media-wrap" ).find( ".educampus-media-preview" ).show();
                }
            });
            frame.open();
        });
        $( document ).on( "click", ".educampus-media-clear-btn", function( e ) {
            e.preventDefault();
            var btn    = $( this );
            var input  = btn.closest( ".educampus-media-wrap" ).find( ".educampus-media-input" );
            input.val( "" );
            btn.closest( ".educampus-media-wrap" ).find( ".educampus-media-preview" ).hide();
        });
    });
    ' );
    }
}
add_action( 'admin_enqueue_scripts', 'educampus_admin_enqueue_scripts' );

/**
 * Helper: output a media upload field (input + button + preview)
 */
function educampus_media_upload_field( $args ) {
    $name     = isset( $args['name'] ) ? $args['name'] : '';
    $value    = isset( $args['value'] ) ? $args['value'] : '';
    $label    = isset( $args['label'] ) ? $args['label'] : 'File';
    $btn_text = isset( $args['btn_text'] ) ? $args['btn_text'] : 'Pilih Media';
    $mime     = isset( $args['mime'] ) ? $args['mime'] : 'image';
    ?>
    <div class="educampus-media-wrap">
        <div class="educampus-media-input-row" style="display:flex; gap:8px; align-items:center;">
            <input name="<?php echo esc_attr( $name ); ?>" type="text" id="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( $value ); ?>" class="regular-text educampus-media-input" placeholder="Klik tombol Pilih..." style="flex:1;">
            <button type="button" class="button educampus-media-btn" data-title="<?php echo esc_attr( $btn_text ); ?>"><?php echo esc_html( $btn_text ); ?></button>
            <?php if ( ! empty( $value ) ) : ?>
            <button type="button" class="button educampus-media-clear-btn" style="color:#a00;">×</button>
            <?php endif; ?>
        </div>
        <div class="educampus-media-preview" style="margin-top:8px;<?php echo empty( $value ) ? ' display:none;' : ''; ?>">
            <?php if ( $mime === 'image' && ! empty( $value ) ) : ?>
                <img src="<?php echo esc_url( $value ); ?>" style="max-width:120px; max-height:120px; border-radius:4px; border:1px solid #ddd; padding:4px; background:#fff;">
            <?php elseif ( ! empty( $value ) ) : ?>
                <a href="<?php echo esc_url( $value ); ?>" target="_blank" class="description">Lihat file</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Force Classic Editor for CPTs that use custom wp_editor() meta boxes.
 * Block Editor (Gutenberg) saves via REST API where $_POST is empty,
 * which causes custom meta save functions to silently fail.
 */
add_filter( 'use_block_editor_for_post_type', function( $enabled, $post_type ) {
    if ( in_array( $post_type, array( 'program', 'faculty', 'unit' ), true ) ) {
        return false;
    }
    return $enabled;
}, 10, 2 );

/**
 * Add Meta Box for Program CPT to select parent Faculty
 */
function educampus_add_program_meta_boxes() {
    add_meta_box(
        'educampus_program_faculty_metabox',
        esc_html__( 'Fakultas Induk', 'educampus' ),
        'educampus_program_faculty_metabox_callback',
        'program',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_program_meta_boxes' );

function educampus_program_faculty_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_program_faculty', 'educampus_program_faculty_nonce' );
    $current_faculty = get_post_meta( $post->ID, '_program_faculty_id', true );
    $faculties = get_posts( array(
        'post_type'      => 'faculty',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );

    echo '<select name="program_faculty_id" id="program_faculty_id" class="postbox-container" style="width:100%; padding:6px; margin-top:5px;">';
    echo '<option value="">' . esc_html__( '-- Pilih Fakultas --', 'educampus' ) . '</option>';
    foreach ( $faculties as $fac ) {
        $selected = selected( $current_faculty, $fac->ID, false );
        echo '<option value="' . esc_attr( $fac->ID ) . '" ' . $selected . '>' . esc_html( $fac->post_title ) . '</option>';
    }
    echo '</select>';
}

/**
 * Save Program CPT parent Faculty metadata
 */
function educampus_save_program_faculty_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_program_faculty_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_program_faculty_nonce'], 'educampus_save_program_faculty' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['program_faculty_id'] ) && ! empty( $_POST['program_faculty_id'] ) ) {
        update_post_meta( $post_id, '_program_faculty_id', sanitize_text_field( $_POST['program_faculty_id'] ) );
    } else {
        delete_post_meta( $post_id, '_program_faculty_id' );
    }
}
add_action( 'save_post_program', 'educampus_save_program_faculty_meta' );

/**
 * Add custom columns to Program list table
 */
function educampus_set_program_columns($columns) {
    $new_columns = array();
    foreach($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'title') {
            $new_columns['program_faculty'] = esc_html__( 'Fakultas', 'educampus' );
        }
    }
    return $new_columns;
}
add_filter( 'manage_program_posts_columns', 'educampus_set_program_columns' );

function educampus_custom_program_column( $column, $post_id ) {
    if ( $column === 'program_faculty' ) {
        $faculty_id = get_post_meta( $post_id, '_program_faculty_id', true );
        if ( $faculty_id ) {
            $faculty = get_post( $faculty_id );
            if ( $faculty ) {
                echo '<a href="' . esc_url( get_edit_post_link( $faculty_id ) ) . '"><strong>' . esc_html( $faculty->post_title ) . '</strong></a>';
            } else {
                echo '<span class="trash">Fakultas tidak ditemukan</span>';
            }
        } else {
            echo '<span class="na" style="color:#999; font-style:italic;">Belum diatur</span>';
        }
    }
}
add_action( 'manage_program_posts_custom_column' , 'educampus_custom_program_column', 10, 2 );

/**
 * Add Meta Box for Lecturer CPT
 */
function educampus_add_lecturer_meta_boxes() {
    add_meta_box(
        'educampus_lecturer_details_metabox',
        esc_html__( 'Detail Dosen', 'educampus' ),
        'educampus_lecturer_details_metabox_callback',
        'lecturer',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_lecturer_meta_boxes' );

function educampus_lecturer_details_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_lecturer_details', 'educampus_lecturer_details_nonce' );

    $position           = get_post_meta( $post->ID, '_lecturer_position', true );
    $jabatan_fungsional = get_post_meta( $post->ID, '_lecturer_jabatan_fungsional', true );
    $nidn               = get_post_meta( $post->ID, '_lecturer_nidn', true );
    $email        = get_post_meta( $post->ID, '_lecturer_email', true );
    $phone        = get_post_meta( $post->ID, '_lecturer_phone', true );
    $program_id   = get_post_meta( $post->ID, '_lecturer_program_id', true );
    $gelar        = get_post_meta( $post->ID, '_lecturer_gelar', true );

    $dummy = educampus_lecturer_dummy_data();

    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="lecturer_position">Jabatan / Posisi</label></th>
            <td>
                <input name="lecturer_position" type="text" id="lecturer_position" value="<?php echo esc_attr( $position ?: $dummy['position'] ); ?>" class="regular-text" placeholder="Contoh: Rektor / Guru Besar Utama">
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">Posisi struktural (Rektor, Kaprodi, Sekprodi, dll). Input manual.</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_jabatan_fungsional">Jabatan Fungsional</label></th>
            <td>
                <input name="lecturer_jabatan_fungsional" type="text" id="lecturer_jabatan_fungsional" value="<?php echo esc_attr( $jabatan_fungsional ); ?>" class="regular-text" placeholder="Contoh: Lektor, Asisten Ahli, Lektor Kepala, Guru Besar">
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">Diisi otomatis dari SISTER. Edit manual jika perlu.</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_gelar">Gelar Akademik</label></th>
            <td>
                <input name="lecturer_gelar" type="text" id="lecturer_gelar" value="<?php echo esc_attr( $gelar ); ?>" class="regular-text" placeholder="Contoh: S.Kom., M.T. (dari pendidikan tertinggi)">
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">Akan ditampilkan setelah nama, misal: "Ahmad Fauzi, S.Kom., M.T."</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_nidn">NIDN</label></th>
            <td>
                <input name="lecturer_nidn" type="text" id="lecturer_nidn" value="<?php echo esc_attr( $nidn ?: $dummy['nidn'] ); ?>" class="regular-text" placeholder="Contoh: 2105087501">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_email">Email Dosen</label></th>
            <td>
                <input name="lecturer_email" type="email" id="lecturer_email" value="<?php echo esc_attr( $email ?: $dummy['email'] ); ?>" class="regular-text" placeholder="Contoh: dosen@iailm.ac.id">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_phone">No. Telepon / HP</label></th>
            <td>
                <input name="lecturer_phone" type="text" id="lecturer_phone" value="<?php echo esc_attr( $phone ?: $dummy['phone'] ); ?>" class="regular-text" placeholder="Contoh: 0812-3456-7890">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="lecturer_program_id">Program Studi</label></th>
            <td>
                <select name="lecturer_program_id" id="lecturer_program_id" class="regular-text" style="width:100%; max-width:400px;">
                    <option value="">— Pilih Program Studi —</option>
                    <?php
                    $programs = get_posts( array(
                        'post_type'      => 'program',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                    ) );
                    foreach ( $programs as $prog ) {
                        $selected = selected( $program_id, $prog->ID, false );
                        echo '<option value="' . esc_attr( $prog->ID ) . '" ' . $selected . '>' . esc_html( $prog->post_title ) . '</option>';
                    }
                    ?>
                </select>
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">Pilih program studi tempat dosen ini mengajar.</p>
            </td>
        </tr>
    </table>

    <hr style="margin:20px 0;">

    <h3 style="margin-top:0;">Riwayat Pendidikan</h3>
    <p class="description" style="color:#666; font-style:italic; margin:-8px 0 12px;">Satu baris per jenjang. Format: <strong>Gelar | Institusi | Tahun</strong></p>
    <textarea name="lecturer_education" id="lecturer_education" rows="4" class="large-text" placeholder="Contoh:&#10;Doktor (S3) Ilmu Komputer | Singapore University | 2021&#10;Magister (S2) Teknik Informatika | Institut Teknologi Bandung | 2017"><?php
        echo esc_textarea( get_post_meta( $post->ID, '_lecturer_education', true ) ?: $dummy['education_text'] );
    ?></textarea>

    <hr style="margin:20px 0;">

    <h3 style="margin-top:0;">Bidang Keahlian</h3>
    <p class="description" style="color:#666; font-style:italic; margin:-8px 0 12px;">Satu baris per bidang keahlian.</p>
    <textarea name="lecturer_expertise" id="lecturer_expertise" rows="3" class="large-text" placeholder="Contoh:&#10;Kecerdasan Buatan (AI)&#10;Sains Data (Data Science)&#10;Pemelajaran Mesin"><?php
        echo esc_textarea( get_post_meta( $post->ID, '_lecturer_expertise', true ) ?: $dummy['expertise_text'] );
    ?></textarea>

    <hr style="margin:20px 0;">

    <h3 style="margin-top:0;">Penelitian Terbaru</h3>
    <p class="description" style="color:#666; font-style:italic; margin:-8px 0 12px;">Satu baris per penelitian. Format: <strong>Judul | Pendanaan | Tahun | Deskripsi singkat</strong></p>
    <textarea name="lecturer_research" id="lecturer_research" rows="4" class="large-text" placeholder="Contoh:&#10;Pengembangan Deep Learning untuk Klasifikasi Citra Medis | Kemendikbudristek | 2025 | Merancang algoritma CNN untuk otomatisasi diagnosa dini."><?php
        echo esc_textarea( get_post_meta( $post->ID, '_lecturer_research', true ) ?: $dummy['research_text'] );
    ?></textarea>

    <hr style="margin:20px 0;">

    <h3 style="margin-top:0;">Publikasi Ilmiah Terpilih</h3>
    <p class="description" style="color:#666; font-style:italic; margin:-8px 0 12px;">Satu baris per publikasi dalam format sitasi lengkap.</p>
    <textarea name="lecturer_publications" id="lecturer_publications" rows="4" class="large-text" placeholder="Contoh:&#10;An Advanced Multi-Class CNN Framework for Early Lung Cancer Detection, IEEE Access, Vol. 13, pp. 2405-2418, 2025."><?php
        echo esc_textarea( get_post_meta( $post->ID, '_lecturer_publications', true ) ?: $dummy['publications_text'] );
    ?></textarea>

    <hr style="margin:20px 0;">

    <h3 style="margin-top:0;">Alamat</h3>
    <textarea name="lecturer_address" id="lecturer_address" rows="3" class="large-text" placeholder="Contoh:&#10;Jl. Raya Simpang Tiga No. 1, Kel. Pekanbaru Kota&#10;Kec. Sukajadi, Kota Pekanbaru, Riau 28156"><?php
        echo esc_textarea( get_post_meta( $post->ID, '_lecturer_address', true ) ?: $dummy['address'] );
    ?></textarea>
    <?php
}

/**
 * Default dummy data for lecturer profiles (used as fallback defaults).
 */
function educampus_lecturer_dummy_data() {
    return array(
        'position'         => 'Dosen Pengajar',
        'nidn'             => '0012345678',
        'email'            => 'dosen@iailm.ac.id',
        'phone'            => '0812-3456-7890',
        'address'          => "Jl. Raya Simpang Tiga No. 1, Kel. Pekanbaru Kota\nKec. Sukajadi, Kota Pekanbaru, Riau 28156",
        'education_text'   => "Doktor (S3) Ilmu Komputer | Singapore University | 2021\nMagister (S2) Teknik Informatika | Institut Teknologi Bandung | 2017\nSarjana (S1) Ilmu Komputer | Universitas Indonesia | 2014",
        'expertise_text'   => "Kecerdasan Buatan (AI)\nSains Data (Data Science)\nPemelajaran Mesin\nKeamanan Siber",
        'research_text'    => "Pengembangan Deep Learning untuk Klasifikasi Citra Medis Kanker Paru | Kemendikbudristek | 2025 | Merancang algoritma CNN tingkat lanjut untuk otomatisasi diagnosa dini nodul paru.\nOptimasi Sistem Keamanan IoT menggunakan Arsitektur Ringan Kriptografi | Mandiri | 2024 | Implementasi cipher ringan untuk menjaga kerahasiaan pertukaran data sensor cerdas hemat energi.",
        'publications_text' => "An Advanced Multi-Class CNN Framework for Early Lung Cancer Detection, IEEE Access, Vol. 13, pp. 2405-2418, 2025.\nSecurity Framework in Edge Computing Node using Lightweight Cryptography, International Journal of Computer Networks and Cyber Security, Vol. 8, Issue 3, 2024.\nSistem Klasifikasi Teks Sentimen Berita Pemilu Menggunakan NLP & SVM, Jurnal Ilmu Komputer dan Informatika (JIKI), Vol. 10, No. 2, 2024.",
    );
}

/**
 * Save Lecturer CPT metadata
 */
function educampus_save_lecturer_details_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_lecturer_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_lecturer_details_nonce'], 'educampus_save_lecturer_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $text_fields = array(
        'lecturer_position'           => '_lecturer_position',
        'lecturer_jabatan_fungsional' => '_lecturer_jabatan_fungsional',
        'lecturer_nidn'               => '_lecturer_nidn',
        'lecturer_email'              => '_lecturer_email',
        'lecturer_phone'              => '_lecturer_phone',
        'lecturer_gelar'              => '_lecturer_gelar',
    );

    foreach ( $text_fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$post_key] ) );
        }
    }

    $textarea_fields = array(
        'lecturer_education'    => '_lecturer_education',
        'lecturer_expertise'    => '_lecturer_expertise',
        'lecturer_research'     => '_lecturer_research',
        'lecturer_publications' => '_lecturer_publications',
        'lecturer_address'      => '_lecturer_address',
    );

    foreach ( $textarea_fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[$post_key] ) );
        }
    }

    if ( isset( $_POST['lecturer_program_id'] ) ) {
        $prog_id = (int) $_POST['lecturer_program_id'];
        if ( $prog_id > 0 ) {
            update_post_meta( $post_id, '_lecturer_program_id', $prog_id );
        } else {
            delete_post_meta( $post_id, '_lecturer_program_id' );
        }
    }
}
add_action( 'save_post_lecturer', 'educampus_save_lecturer_details_meta' );

/**
 * Seed dummy lecturer data into existing posts that have empty meta fields.
 * Runs once after theme activation.
 * Hanya jalan jika SISTER API belum dikonfigurasi.
 */
function educampus_seed_lecturer_dummy_data() {
    if ( get_option( 'educampus_lecturer_seeded' ) ) {
        return;
    }

    $sister_config = get_option( 'educampus_sister_config', array() );
    if ( ! empty( $sister_config['api_url'] ) && ! empty( $sister_config['username'] ) ) {
        update_option( 'educampus_lecturer_seeded', true );
        return;
    }

    $lecturers = get_posts( array(
        'post_type'      => 'lecturer',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ) );

    if ( empty( $lecturers ) ) {
        update_option( 'educampus_lecturer_seeded', true );
        return;
    }

    $dummy = educampus_lecturer_dummy_data();
    $meta_fields = array(
        '_lecturer_phone'        => 'phone',
        '_lecturer_address'      => 'address',
        '_lecturer_education'    => 'education_text',
        '_lecturer_expertise'    => 'expertise_text',
        '_lecturer_research'     => 'research_text',
        '_lecturer_publications' => 'publications_text',
        '_lecturer_position'     => 'position',
        '_lecturer_nidn'         => 'nidn',
        '_lecturer_email'        => 'email',
    );

    foreach ( $lecturers as $lid ) {
        foreach ( $meta_fields as $meta_key => $dummy_key ) {
            $existing = get_post_meta( $lid, $meta_key, true );
            if ( empty( $existing ) ) {
                update_post_meta( $lid, $meta_key, $dummy[ $dummy_key ] );
            }
        }
    }

    update_option( 'educampus_lecturer_seeded', true );
}
add_action( 'admin_init', 'educampus_seed_lecturer_dummy_data' );

/**
 * Add custom columns to Lecturer list table
 */
function educampus_set_lecturer_columns($columns) {
    $new_columns = array();
    foreach($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'title') {
            $new_columns['lecturer_position'] = 'Posisi';
            $new_columns['lecturer_jabatan_fungsional'] = 'Jab. Fungsional';
            $new_columns['lecturer_nidn'] = 'NIDN';
            $new_columns['lecturer_email'] = 'Email';
        }
    }
    return $new_columns;
}
add_filter( 'manage_lecturer_posts_columns', 'educampus_set_lecturer_columns' );

function educampus_custom_lecturer_column( $column, $post_id ) {
    switch ( $column ) {
        case 'lecturer_position':
            echo esc_html( get_post_meta( $post_id, '_lecturer_position', true ) );
            break;
        case 'lecturer_jabatan_fungsional':
            echo esc_html( get_post_meta( $post_id, '_lecturer_jabatan_fungsional', true ) );
            break;
        case 'lecturer_nidn':
            echo esc_html( get_post_meta( $post_id, '_lecturer_nidn', true ) );
            break;
        case 'lecturer_email':
            echo esc_html( get_post_meta( $post_id, '_lecturer_email', true ) );
            break;
    }
}
add_action( 'manage_lecturer_posts_custom_column' , 'educampus_custom_lecturer_column', 10, 2 );

/**
 * Add Meta Box for Faculty CPT (Dean details and extra info)
 */
function educampus_add_faculty_meta_boxes() {
    add_meta_box(
        'educampus_faculty_details_metabox',
        esc_html__( 'Detail Dekan & Informasi Fakultas', 'educampus' ),
        'educampus_faculty_details_metabox_callback',
        'faculty',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_faculty_meta_boxes' );

function educampus_faculty_details_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_faculty_details', 'educampus_faculty_details_nonce' );

    $dean_name     = get_post_meta( $post->ID, '_faculty_dean_name', true );
    $dean_period   = get_post_meta( $post->ID, '_faculty_dean_period', true );
    $dean_photo    = get_post_meta( $post->ID, '_faculty_dean_photo', true );
    $dean_quote    = get_post_meta( $post->ID, '_faculty_dean_quote', true );
    $visi          = get_post_meta( $post->ID, '_faculty_visi', true );
    
    $established   = get_post_meta( $post->ID, '_faculty_established', true );
    $students      = get_post_meta( $post->ID, '_faculty_students_count', true );
    $lecturers     = get_post_meta( $post->ID, '_faculty_lecturers_count', true );
    $location      = get_post_meta( $post->ID, '_faculty_location', true );
    $youtube       = get_post_meta( $post->ID, '_faculty_youtube', true );
    $instagram     = get_post_meta( $post->ID, '_faculty_instagram', true );
    $tiktok        = get_post_meta( $post->ID, '_faculty_tiktok', true );
    $facebook      = get_post_meta( $post->ID, '_faculty_facebook', true );
    ?>
    <div class="panel-wrap" style="padding:12px 0;">
        <div style="background:#f6f7f7; padding:10px 12px; margin-bottom:12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">PROFIL DEKAN</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_dean_name" style="display:block; font-weight:600; margin-bottom:4px;">Nama Dekan</label>
            <input name="faculty_dean_name" type="text" id="faculty_dean_name" value="<?php echo esc_attr( $dean_name ); ?>" class="regular-text" placeholder="Contoh: Dr. H. Budi Prasetyo, M.T." style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_dean_period" style="display:block; font-weight:600; margin-bottom:4px;">Periode Jabatan</label>
            <input name="faculty_dean_period" type="text" id="faculty_dean_period" value="<?php echo esc_attr( $dean_period ); ?>" class="regular-text" placeholder="Contoh: Dekan Fakultas Periode 2024 - 2028" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">Foto Dekan</label>
            <?php educampus_media_upload_field( array(
                'name'     => 'faculty_dean_photo',
                'value'    => $dean_photo,
                'label'    => 'Foto Dekan',
                'btn_text' => 'Pilih Gambar',
                'mime'     => 'image',
            ) ); ?>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_dean_quote" style="display:block; font-weight:600; margin-bottom:4px;">Pernyataan / Kutipan Dekan</label>
            <textarea name="faculty_dean_quote" id="faculty_dean_quote" class="large-text" rows="3" placeholder="Masukkan kata sambutan singkat dekan..." style="width:100%; max-width:500px;"><?php echo esc_textarea( $dean_quote ); ?></textarea>
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">INFORMASI TAMBAHAN FAKULTAS</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_established" style="display:block; font-weight:600; margin-bottom:4px;">Tahun Didirikan</label>
            <input name="faculty_established" type="text" id="faculty_established" value="<?php echo esc_attr( $established ); ?>" class="regular-text" placeholder="Contoh: Tahun 2012" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_students_count" style="display:block; font-weight:600; margin-bottom:4px;">Jumlah Mahasiswa</label>
            <input name="faculty_students_count" type="text" id="faculty_students_count" value="<?php echo esc_attr( $students ); ?>" class="regular-text" placeholder="Contoh: 2.100+" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_lecturers_count" style="display:block; font-weight:600; margin-bottom:4px;">Jumlah Dosen Pengajar</label>
            <input name="faculty_lecturers_count" type="text" id="faculty_lecturers_count" value="<?php echo esc_attr( $lecturers ); ?>" class="regular-text" placeholder="Contoh: 68" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_location" style="display:block; font-weight:600; margin-bottom:4px;">Lokasi Fakultas</label>
            <input name="faculty_location" type="text" id="faculty_location" value="<?php echo esc_attr( $location ); ?>" class="regular-text" placeholder="Contoh: Gedung B, Lantai 1-4" style="width:100%; max-width:400px;">
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">MEDIA SOSIAL</div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_youtube" style="display:block; font-weight:600; margin-bottom:4px;"><i class="bi bi-youtube" style="color:#ff0000;"></i> YouTube</label>
            <input name="faculty_youtube" type="url" id="faculty_youtube" value="<?php echo esc_attr( $youtube ); ?>" class="regular-text" placeholder="https://youtube.com/@..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_instagram" style="display:block; font-weight:600; margin-bottom:4px;"><i class="bi bi-instagram" style="color:#e1306c;"></i> Instagram</label>
            <input name="faculty_instagram" type="url" id="faculty_instagram" value="<?php echo esc_attr( $instagram ); ?>" class="regular-text" placeholder="https://instagram.com/..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_tiktok" style="display:block; font-weight:600; margin-bottom:4px;"><i class="bi bi-tiktok"></i> TikTok</label>
            <input name="faculty_tiktok" type="url" id="faculty_tiktok" value="<?php echo esc_attr( $tiktok ); ?>" class="regular-text" placeholder="https://tiktok.com/@..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_facebook" style="display:block; font-weight:600; margin-bottom:4px;"><i class="bi bi-facebook" style="color:#1877f2;"></i> Facebook</label>
            <input name="faculty_facebook" type="url" id="faculty_facebook" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" placeholder="https://facebook.com/..." style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_visi" style="display:block; font-weight:600; margin-bottom:4px;">Visi Fakultas</label>
            <textarea name="faculty_visi" id="faculty_visi" class="large-text" rows="3" placeholder="Masukkan visi fakultas..." style="width:100%; max-width:500px;"><?php echo esc_textarea( $visi ); ?></textarea>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="faculty_misi" style="display:block; font-weight:600; margin-bottom:4px;">Misi Fakultas</label>
            <textarea name="faculty_misi" id="faculty_misi" class="large-text" rows="4" placeholder="Tuliskan misi fakultas, satu per baris..." style="width:100%; max-width:500px;"><?php echo esc_textarea( get_post_meta( $post->ID, '_faculty_misi', true ) ); ?></textarea>
            <p class="description" style="margin:2px 0 0; color:#666; font-style:italic;">Pisahkan setiap misi dengan baris baru.</p>
        </div>
    </div>
    <?php
}

function educampus_save_faculty_details_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_faculty_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_faculty_details_nonce'], 'educampus_save_faculty_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'faculty_dean_name'       => '_faculty_dean_name',
        'faculty_dean_period'     => '_faculty_dean_period',
        'faculty_dean_photo'      => '_faculty_dean_photo',
        'faculty_dean_quote'      => '_faculty_dean_quote',
        'faculty_established'     => '_faculty_established',
        'faculty_students_count'  => '_faculty_students_count',
        'faculty_lecturers_count' => '_faculty_lecturers_count',
        'faculty_location'        => '_faculty_location',
        'faculty_visi'            => '_faculty_visi',
        'faculty_misi'            => '_faculty_misi',
        'faculty_youtube'         => '_faculty_youtube',
        'faculty_instagram'       => '_faculty_instagram',
        'faculty_tiktok'          => '_faculty_tiktok',
        'faculty_facebook'        => '_faculty_facebook',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            if ($post_key === 'faculty_dean_photo') {
                update_post_meta( $post_id, $meta_key, esc_url_raw( $_POST[$post_key] ) );
            } else {
                update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$post_key] ) );
            }
        }
    }
}
add_action( 'save_post_faculty', 'educampus_save_faculty_details_meta' );

/**
 * Meta Box for Slide Slider CPT — Field terpisah dengan label jelas
 */
function educampus_add_slide_meta_boxes() {
    add_meta_box(
        'educampus_slide_metabox',
        esc_html__( 'Detail Slide', 'educampus' ),
        'educampus_slide_metabox_callback',
        'slide',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_slide_meta_boxes' );

function educampus_slide_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_slide_meta', 'educampus_slide_nonce' );

    $badge      = get_post_meta( $post->ID, '_slide_badge', true );
    $heading    = get_post_meta( $post->ID, '_slide_heading', true );
    $desc       = get_post_meta( $post->ID, '_slide_desc', true );
    $gold_text  = get_post_meta( $post->ID, '_slide_gold_text', true );
    ?>
    <div style="padding:8px 0;">

        <div style="background:#f6f7f7; padding:10px 12px; margin-bottom:12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">KONTEN SLIDE</div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="slide_badge" style="display:block; font-weight:600; margin-bottom:4px;">Badge (Teks Label Kuning)</label>
            <input name="slide_badge" type="text" id="slide_badge" value="<?php echo esc_attr( $badge ); ?>" class="regular-text" placeholder="Contoh: AKREDITASI UNGGUL 2026" style="width:100%; max-width:400px;">
            <p class="description" style="margin:2px 0 0; color:#666;">Teks kecil di atas judul utama.</p>
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="slide_heading" style="display:block; font-weight:600; margin-bottom:4px;">Judul Utama (Heading)</label>
            <textarea name="slide_heading" id="slide_heading" class="large-text" rows="2" placeholder="Contoh: Universitas Terakreditasi [gold]UNGGUL[/gold] Nasional" style="width:100%; max-width:500px;"><?php echo esc_textarea( $heading ); ?></textarea>
            <p class="description" style="margin:2px 0 0; color:#666;">Gunakan <code>[gold]teks[/gold]</code> untuk highlight warna emas.</p>
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="slide_desc" style="display:block; font-weight:600; margin-bottom:4px;">Deskripsi</label>
            <textarea name="slide_desc" id="slide_desc" class="large-text" rows="3" placeholder="Paragraf deskripsi singkat..." style="width:100%; max-width:500px;"><?php echo esc_textarea( $desc ); ?></textarea>
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="slide_gold_text" style="display:block; font-weight:600; margin-bottom:4px;"><span style="color:#d4a843;">&#9679;</span> Teks Emas (Alternatif)</label>
            <input name="slide_gold_text" type="text" id="slide_gold_text" value="<?php echo esc_attr( $gold_text ); ?>" class="regular-text" placeholder="Contoh: UNGGUL" style="width:100%; max-width:400px;">
            <p class="description" style="margin:2px 0 0; color:#666;">Teks ini otomatis ditambahkan ke judul dengan warna emas. Lebih mudah dari pakai <code>[gold]</code>.</p>
        </div>

    </div>
    <?php
}

function educampus_save_slide_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_slide_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['educampus_slide_nonce'], 'educampus_save_slide_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array(
        'slide_badge'     => '_slide_badge',
        'slide_heading'   => '_slide_heading',
        'slide_desc'      => '_slide_desc',
        'slide_gold_text' => '_slide_gold_text',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$post_key] ) );
        } else {
            delete_post_meta( $post_id, $meta_key );
        }
    }
}
add_action( 'save_post_slide', 'educampus_save_slide_meta' );


/**
 * Add Meta Box for Program CPT (Accreditation, Degree, Duration, Method, Visi)
 */
function educampus_add_program_details_meta_boxes() {
    add_meta_box(
        'educampus_program_details_metabox',
        esc_html__( 'Detail & Informasi Program Studi', 'educampus' ),
        'educampus_program_details_metabox_callback',
        'program',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_program_details_meta_boxes' );

function educampus_program_details_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_program_details', 'educampus_program_details_nonce' );

    $accreditation  = get_post_meta( $post->ID, '_program_accreditation', true );
    $accred_inst    = get_post_meta( $post->ID, '_program_accred_institution', true );
    $accred_cert    = get_post_meta( $post->ID, '_program_accred_cert', true );
    $accred_sk      = get_post_meta( $post->ID, '_program_accred_sk', true );
    $gelar         = get_post_meta( $post->ID, '_program_gelar', true );
    $duration      = get_post_meta( $post->ID, '_program_duration', true );
    $method        = get_post_meta( $post->ID, '_program_method', true );
    $visi          = get_post_meta( $post->ID, '_program_visi', true );
    $youtube       = get_post_meta( $post->ID, '_program_youtube', true );
    $instagram     = get_post_meta( $post->ID, '_program_instagram', true );
    $tiktok        = get_post_meta( $post->ID, '_program_tiktok', true );
    $facebook      = get_post_meta( $post->ID, '_program_facebook', true );

    $kaprodi_name   = get_post_meta( $post->ID, '_program_kaprodi_name', true );
    $kaprodi_nidn   = get_post_meta( $post->ID, '_program_kaprodi_nidn', true );
    $kaprodi_photo  = get_post_meta( $post->ID, '_program_kaprodi_photo', true );
    $kaprodi_period = get_post_meta( $post->ID, '_program_kaprodi_period', true );

    $sekprodi_name   = get_post_meta( $post->ID, '_program_sekprodi_name', true );
    $sekprodi_nidn   = get_post_meta( $post->ID, '_program_sekprodi_nidn', true );
    $sekprodi_photo  = get_post_meta( $post->ID, '_program_sekprodi_photo', true );
    $sekprodi_period = get_post_meta( $post->ID, '_program_sekprodi_period', true );

    $featured = get_post_meta( $post->ID, '_program_featured', true );

    ?>
    <div class="panel-wrap" style="padding:12px 0;">
        <div style="background:#f6f7f7; padding:10px 12px; margin-bottom:12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">PENGATURAN TAMPILAN</div>

        <div class="form-field" style="padding:8px 12px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input name="program_featured" type="checkbox" id="program_featured" value="1" <?php checked( $featured, '1' ); ?> style="width:18px;height:18px;">
                <span style="font-weight:600;">Tampilkan sebagai Program Studi Unggulan di Halaman Depan</span>
            </label>
            <p class="description" style="margin:2px 0 0 26px; color:#666; font-style:italic;">Centang jika prodi ini ingin ditampilkan di bagian "Program Studi Favorit & Unggul" pada halaman depan.</p>
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:12px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">AKREDITASI</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_accreditation" style="display:block; font-weight:600; margin-bottom:4px;">Peringkat Akreditasi</label>
            <input name="program_accreditation" type="text" id="program_accreditation" value="<?php echo esc_attr( $accreditation ); ?>" class="regular-text" placeholder="Contoh: Unggul / A / B" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_accred_institution" style="display:block; font-weight:600; margin-bottom:4px;">Lembaga Akreditasi</label>
            <input name="program_accred_institution" type="text" id="program_accred_institution" value="<?php echo esc_attr( $accred_inst ); ?>" class="regular-text" placeholder="Contoh: BAN-PT / LAMDIK / LAMSAMA / FIBAA" style="width:100%; max-width:400px;">
            <p class="description" style="margin:2px 0 0; color:#666; font-style:italic;">Kosongkan jika tidak ingin menampilkan lembaga akreditasi.</p>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">Sertifikat Akreditasi (File PDF/Gambar)</label>
            <?php educampus_media_upload_field( array(
                'name'     => 'program_accred_cert',
                'value'    => $accred_cert,
                'label'    => 'Sertifikat Akreditasi',
                'btn_text' => 'Pilih File',
                'mime'     => 'document',
            ) ); ?>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">SK Akreditasi (File PDF/Gambar)</label>
            <?php educampus_media_upload_field( array(
                'name'     => 'program_accred_sk',
                'value'    => $accred_sk,
                'label'    => 'SK Akreditasi',
                'btn_text' => 'Pilih File',
                'mime'     => 'document',
            ) ); ?>
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">INFORMASI UMUM</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_gelar" style="display:block; font-weight:600; margin-bottom:4px;">Gelar Kelulusan</label>
            <input name="program_gelar" type="text" id="program_gelar" value="<?php echo esc_attr( $gelar ); ?>" class="regular-text" placeholder="Contoh: Sarjana Komputer (S.Kom.)" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_duration" style="display:block; font-weight:600; margin-bottom:4px;">Masa Studi / Durasi</label>
            <input name="program_duration" type="text" id="program_duration" value="<?php echo esc_attr( $duration ); ?>" class="regular-text" placeholder="Contoh: 8 Semester (4 Tahun)" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_method" style="display:block; font-weight:600; margin-bottom:4px;">Metode Kuliah</label>
            <input name="program_method" type="text" id="program_method" value="<?php echo esc_attr( $method ); ?>" class="regular-text" placeholder="Contoh: Hybrid (Luring/Daring)" style="width:100%; max-width:400px;">
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">MEDIA SOSIAL</div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="program_youtube" style="display:block; font-weight:600; margin-bottom:4px;">YouTube</label>
            <input name="program_youtube" type="url" id="program_youtube" value="<?php echo esc_attr( $youtube ); ?>" class="regular-text" placeholder="https://youtube.com/@..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="program_instagram" style="display:block; font-weight:600; margin-bottom:4px;">Instagram</label>
            <input name="program_instagram" type="url" id="program_instagram" value="<?php echo esc_attr( $instagram ); ?>" class="regular-text" placeholder="https://instagram.com/..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="program_tiktok" style="display:block; font-weight:600; margin-bottom:4px;">TikTok</label>
            <input name="program_tiktok" type="url" id="program_tiktok" value="<?php echo esc_attr( $tiktok ); ?>" class="regular-text" placeholder="https://tiktok.com/@..." style="width:100%; max-width:400px;">
        </div>

        <div class="form-field" style="padding:8px 12px;">
            <label for="program_facebook" style="display:block; font-weight:600; margin-bottom:4px;">Facebook</label>
            <input name="program_facebook" type="url" id="program_facebook" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" placeholder="https://facebook.com/..." style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_visi" style="display:block; font-weight:600; margin-bottom:4px;">Visi Program Studi</label>
            <textarea name="program_visi" id="program_visi" class="large-text" rows="3" placeholder="Masukkan visi prodi..." style="width:100%; max-width:500px;"><?php echo esc_textarea( $visi ); ?></textarea>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_misi" style="display:block; font-weight:600; margin-bottom:4px;">Misi Program Studi</label>
            <textarea name="program_misi" id="program_misi" class="large-text" rows="4" placeholder="Tuliskan misi prodi, satu per baris..." style="width:100%; max-width:500px;"><?php echo esc_textarea( get_post_meta( $post->ID, '_program_misi', true ) ); ?></textarea>
            <p class="description" style="margin:2px 0 0; color:#666; font-style:italic;">Pisahkan setiap misi dengan baris baru.</p>
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">KETUA PROGRAM STUDI</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_kaprodi_name" style="display:block; font-weight:600; margin-bottom:4px;">Nama Ketua Prodi</label>
            <input name="program_kaprodi_name" type="text" id="program_kaprodi_name" value="<?php echo esc_attr( $kaprodi_name ); ?>" class="regular-text" placeholder="Contoh: Dr. Ahmad Fauzi, M.T." style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_kaprodi_nidn" style="display:block; font-weight:600; margin-bottom:4px;">NIP / NIDN Ketua Prodi</label>
            <input name="program_kaprodi_nidn" type="text" id="program_kaprodi_nidn" value="<?php echo esc_attr( $kaprodi_nidn ); ?>" class="regular-text" placeholder="Contoh: 2105087501" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">Foto Ketua Prodi</label>
            <?php educampus_media_upload_field( array(
                'name'     => 'program_kaprodi_photo',
                'value'    => $kaprodi_photo,
                'label'    => 'Foto Ketua Prodi',
                'btn_text' => 'Pilih Gambar',
                'mime'     => 'image',
            ) ); ?>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_kaprodi_period" style="display:block; font-weight:600; margin-bottom:4px;">Periode Jabatan Ketua Prodi</label>
            <input name="program_kaprodi_period" type="text" id="program_kaprodi_period" value="<?php echo esc_attr( $kaprodi_period ); ?>" class="regular-text" placeholder="Contoh: 2024 - 2028" style="width:100%; max-width:400px;">
        </div>

        <div style="background:#f6f7f7; padding:10px 12px; margin:16px 0 12px; font-weight:600; border-left:4px solid var(--wp-admin-theme-color, #2271b1);">SEKRETARIS PROGRAM STUDI</div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_sekprodi_name" style="display:block; font-weight:600; margin-bottom:4px;">Nama Sekretaris Prodi</label>
            <input name="program_sekprodi_name" type="text" id="program_sekprodi_name" value="<?php echo esc_attr( $sekprodi_name ); ?>" class="regular-text" placeholder="Contoh: Rina Wijaya, M.Kom." style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_sekprodi_nidn" style="display:block; font-weight:600; margin-bottom:4px;">NIP / NIDN Sekretaris Prodi</label>
            <input name="program_sekprodi_nidn" type="text" id="program_sekprodi_nidn" value="<?php echo esc_attr( $sekprodi_nidn ); ?>" class="regular-text" placeholder="Contoh: 2105087502" style="width:100%; max-width:400px;">
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">Foto Sekretaris Prodi</label>
            <?php educampus_media_upload_field( array(
                'name'     => 'program_sekprodi_photo',
                'value'    => $sekprodi_photo,
                'label'    => 'Foto Sekretaris Prodi',
                'btn_text' => 'Pilih Gambar',
                'mime'     => 'image',
            ) ); ?>
        </div>
        
        <div class="form-field" style="padding:8px 12px;">
            <label for="program_sekprodi_period" style="display:block; font-weight:600; margin-bottom:4px;">Periode Jabatan Sekretaris Prodi</label>
            <input name="program_sekprodi_period" type="text" id="program_sekprodi_period" value="<?php echo esc_attr( $sekprodi_period ); ?>" class="regular-text" placeholder="Contoh: 2024 - 2028" style="width:100%; max-width:400px;">
        </div>
    </div>
    <?php
}

function educampus_save_program_details_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_program_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_program_details_nonce'], 'educampus_save_program_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $text_fields = array(
        'program_accreditation' => '_program_accreditation',
        'program_accred_institution' => '_program_accred_institution',
        'program_gelar'         => '_program_gelar',
        'program_duration'      => '_program_duration',
        'program_method'        => '_program_method',
        'program_kaprodi_name'  => '_program_kaprodi_name',
        'program_kaprodi_nidn'  => '_program_kaprodi_nidn',
        'program_kaprodi_period'=> '_program_kaprodi_period',
        'program_sekprodi_name' => '_program_sekprodi_name',
        'program_sekprodi_nidn' => '_program_sekprodi_nidn',
        'program_sekprodi_period'=> '_program_sekprodi_period',
    );

    foreach ( $text_fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$post_key] ) );
        }
    }

    $url_fields = array(
        'program_accred_cert'   => '_program_accred_cert',
        'program_accred_sk'     => '_program_accred_sk',
        'program_kaprodi_photo' => '_program_kaprodi_photo',
        'program_sekprodi_photo'=> '_program_sekprodi_photo',
        'program_youtube'       => '_program_youtube',
        'program_instagram'     => '_program_instagram',
        'program_tiktok'        => '_program_tiktok',
        'program_facebook'      => '_program_facebook',
    );

    foreach ( $url_fields as $post_key => $meta_key ) {
        if ( isset( $_POST[$post_key] ) ) {
            update_post_meta( $post_id, $meta_key, esc_url_raw( $_POST[$post_key] ) );
        }
    }

    if ( isset( $_POST['program_visi'] ) ) {
        update_post_meta( $post_id, '_program_visi', sanitize_textarea_field( $_POST['program_visi'] ) );
    }
    if ( isset( $_POST['program_misi'] ) ) {
        update_post_meta( $post_id, '_program_misi', sanitize_textarea_field( $_POST['program_misi'] ) );
    }

    $featured = isset( $_POST['program_featured'] ) ? '1' : '0';
    update_post_meta( $post_id, '_program_featured', $featured );
}
add_action( 'save_post_program', 'educampus_save_program_details_meta' );

/**
 * Add Meta Boxes for Program Curriculum and Careers (with WYSIWYG editor)
 */
function educampus_add_program_tabs_meta_boxes() {
    add_meta_box(
        'educampus_program_curriculum_metabox',
        esc_html__( 'Kurikulum Program Studi', 'educampus' ),
        'educampus_program_curriculum_metabox_callback',
        'program',
        'normal',
        'default'
    );
    add_meta_box(
        'educampus_program_careers_metabox',
        esc_html__( 'Prospek Karir Lulusan', 'educampus' ),
        'educampus_program_careers_metabox_callback',
        'program',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_program_tabs_meta_boxes' );

function educampus_program_curriculum_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_program_tabs', 'educampus_program_tabs_nonce' );
    $curriculum_json = get_post_meta( $post->ID, '_program_curriculum_json', true );
    $data = ! empty( $curriculum_json ) ? json_decode( $curriculum_json, true ) : array();
    $total_semesters = 8;
    ?>
    <p class="description" style="margin:0 0 12px; color:#666;">Isi mata kuliah per semester. Gunakan tombol <strong>+ Tambah Baris</strong> untuk menambah baris mata kuliah.</p>
    <div class="curriculum-builder">
        <div style="display:flex; flex-wrap:wrap; gap:4px; margin-bottom:12px; border-bottom:2px solid #ddd; padding-bottom:8px;">
            <?php for ( $s = 1; $s <= $total_semesters; $s++ ) : ?>
                <button type="button" class="button semester-tab <?php echo $s === 1 ? 'button-primary' : ''; ?>" data-semester="<?php echo $s; ?>">Semester <?php echo $s; ?></button>
            <?php endfor; ?>
        </div>
        <?php for ( $s = 1; $s <= $total_semesters; $s++ ) : ?>
            <div class="semester-panel" data-semester="<?php echo $s; ?>" style="<?php echo $s === 1 ? '' : 'display:none;'; ?>">
                <table class="wp-list-table widefat fixed striped" style="margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th style="width:18%;">Kode MK</th>
                            <th style="width:52%;">Nama Mata Kuliah</th>
                            <th style="width:12%; text-align:center;">SKS</th>
                            <th style="width:18%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="curriculum-rows" data-semester="<?php echo $s; ?>">
                        <?php
                        $courses = isset( $data[ $s ] ) ? $data[ $s ] : array();
                        if ( ! empty( $courses ) ) :
                            foreach ( $courses as $i => $course ) :
                        ?>
                        <tr>
                            <td><input type="text" name="curriculum[<?php echo $s; ?>][<?php echo $i; ?>][code]" value="<?php echo esc_attr( $course['code'] ?? '' ); ?>" class="medium-text" placeholder="MK101" style="width:90%;"></td>
                            <td><input type="text" name="curriculum[<?php echo $s; ?>][<?php echo $i; ?>][name]" value="<?php echo esc_attr( $course['name'] ?? '' ); ?>" style="width:95%;" placeholder="Nama mata kuliah"></td>
                            <td><input type="number" name="curriculum[<?php echo $s; ?>][<?php echo $i; ?>][sks]" value="<?php echo esc_attr( $course['sks'] ?? '' ); ?>" min="1" max="6" style="width:60px; text-align:center;" placeholder="3"></td>
                            <td><button type="button" class="button remove-curriculum-row" style="color:#a00;">Hapus</button></td>
                        </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
                <button type="button" class="button add-curriculum-row" data-semester="<?php echo $s; ?>">+ Tambah Baris</button>
            </div>
        <?php endfor; ?>
    </div>
    <script>
    jQuery( function( $ ) {
        // Semester tabs
        $( '.semester-tab' ).on( 'click', function() {
            $( '.semester-tab' ).removeClass( 'button-primary' );
            $( this ).addClass( 'button-primary' );
            $( '.semester-panel' ).hide();
            $( '.semester-panel[data-semester="' + $( this ).data( 'semester' ) + '"]' ).show();
        } );
        // Add row
        $( document ).on( 'click', '.add-curriculum-row', function() {
            var sem = $( this ).data( 'semester' );
            var tbody = $( '.curriculum-rows[data-semester="' + sem + '"]' );
            var count = tbody.find( 'tr' ).length;
            var row = '<tr>' +
                '<td><input type="text" name="curriculum[' + sem + '][' + count + '][code]" class="medium-text" placeholder="MK101" style="width:90%;"></td>' +
                '<td><input type="text" name="curriculum[' + sem + '][' + count + '][name]" style="width:95%;" placeholder="Nama mata kuliah"></td>' +
                '<td><input type="number" name="curriculum[' + sem + '][' + count + '][sks]" min="1" max="6" style="width:60px; text-align:center;" placeholder="3"></td>' +
                '<td><button type="button" class="button remove-curriculum-row" style="color:#a00;">Hapus</button></td>' +
                '</tr>';
            tbody.append( row );
        } );
        // Remove row
        $( document ).on( 'click', '.remove-curriculum-row', function() {
            if ( confirm( 'Hapus baris ini?' ) ) {
                $( this ).closest( 'tr' ).remove();
                // Rename indexes
                $( '.curriculum-rows' ).each( function() {
                    var sem = $( this ).data( 'semester' );
                    $( this ).find( 'tr' ).each( function( idx ) {
                        $( this ).find( 'input' ).each( function() {
                            var name = $( this ).attr( 'name' );
                            name = name.replace( /curriculum\[\d+\]\[\d+\]/, 'curriculum[' + sem + '][' + idx + ']' );
                            $( this ).attr( 'name', name );
                        } );
                    } );
                } );
            }
        } );
    } );
    </script>
    <?php
}

function educampus_program_careers_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_program_tabs', 'educampus_program_tabs_nonce' );
    $careers = get_post_meta( $post->ID, '_program_careers', true );
    
    wp_editor( $careers, 'program_careers', array(
        'textarea_name' => 'program_careers',
        'media_buttons' => false,
        'textarea_rows' => 10,
        'teeny'         => true,
    ) );
}

function educampus_save_program_tabs_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_program_tabs_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_program_tabs_nonce'], 'educampus_save_program_tabs' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['curriculum'] ) && is_array( $_POST['curriculum'] ) ) {
        $data = array();
        foreach ( $_POST['curriculum'] as $sem => $courses ) {
            $sem = (int) $sem;
            if ( $sem < 1 || $sem > 20 ) continue;
            $clean = array();
            foreach ( $courses as $course ) {
                $code = sanitize_text_field( $course['code'] ?? '' );
                $name = sanitize_text_field( $course['name'] ?? '' );
                $sks  = sanitize_text_field( $course['sks'] ?? '' );
                if ( ! empty( $name ) ) {
                    $clean[] = array(
                        'code' => $code,
                        'name' => $name,
                        'sks'  => $sks,
                    );
                }
            }
            if ( ! empty( $clean ) ) {
                $data[ $sem ] = $clean;
            }
        }
        update_post_meta( $post_id, '_program_curriculum_json', wp_json_encode( $data ) );
    } else {
        delete_post_meta( $post_id, '_program_curriculum_json' );
    }

    if ( isset( $_POST['program_careers'] ) ) {
        update_post_meta( $post_id, '_program_careers', wp_kses_post( $_POST['program_careers'] ) );
    }
}
add_action( 'save_post_program', 'educampus_save_program_tabs_meta' );

// =====================================================
// UNIT ORGANISASI (Organizational Unit) META BOXES
// =====================================================

/**
 * Add admin columns for Unit post list.
 */
function educampus_set_unit_columns( $columns ) {
    $new = array();
    foreach ( $columns as $key => $title ) {
        $new[ $key ] = $title;
        if ( $key === 'title' ) {
            $new['unit_position'] = 'Jabatan';
            $new['unit_group']    = 'Grup Unit';
            $new['unit_email']    = 'Email';
        }
    }
    return $new;
}
add_filter( 'manage_unit_posts_columns', 'educampus_set_unit_columns' );

function educampus_custom_unit_column( $column, $post_id ) {
    switch ( $column ) {
        case 'unit_position':
            echo esc_html( get_post_meta( $post_id, '_unit_position_title', true ) );
            break;
        case 'unit_group':
            $terms = get_the_terms( $post_id, 'unit_group' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $names = array();
                foreach ( $terms as $t ) {
                    $names[] = $t->name;
                }
                echo esc_html( implode( ', ', $names ) );
            }
            break;
        case 'unit_email':
            echo esc_html( get_post_meta( $post_id, '_unit_email', true ) );
            break;
    }
}
add_action( 'manage_unit_posts_custom_column', 'educampus_custom_unit_column', 10, 2 );

/**
 * Add meta box for Unit details.
 */
function educampus_add_unit_meta_boxes() {
    add_meta_box(
        'educampus_unit_details_metabox',
        esc_html__( 'Detail Unit Organisasi', 'educampus' ),
        'educampus_unit_details_metabox_callback',
        'unit',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_unit_meta_boxes' );

function educampus_unit_details_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_unit_details', 'educampus_unit_details_nonce' );
    $position = get_post_meta( $post->ID, '_unit_position_title', true );
    $email    = get_post_meta( $post->ID, '_unit_email', true );
    $address  = get_post_meta( $post->ID, '_unit_address', true );
    $phone    = get_post_meta( $post->ID, '_unit_phone', true );
    $photo_url = get_post_meta( $post->ID, '_unit_photo_url', true );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="unit_position_title">Nama Jabatan</label></th>
            <td>
                <input name="unit_position_title" type="text" id="unit_position_title"
                       value="<?php echo esc_attr( $position ); ?>" class="regular-text"
                       placeholder="Contoh: Rektor, Dekan Fakultas Tarbiyah, Kepala LPM">
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">
                    Jabatan struktural yang diemban (contoh: Rektor, Wakil Rektor I, Dekan...).
                    <strong>Nama unit / orang</strong> diisi pada judul post di atas.
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="unit_email">Email Kantor</label></th>
            <td>
                <input name="unit_email" type="email" id="unit_email"
                       value="<?php echo esc_attr( $email ); ?>" class="regular-text"
                       placeholder="contoh: rektor@iailm.ac.id">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="unit_phone">Telepon Kantor</label></th>
            <td>
                <input name="unit_phone" type="text" id="unit_phone"
                       value="<?php echo esc_attr( $phone ); ?>" class="regular-text"
                       placeholder="Contoh: (0265) 123456">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="unit_address">Alamat Kantor</label></th>
            <td>
                <textarea name="unit_address" id="unit_address" class="large-text" rows="2"
                          placeholder="Contoh: Gedung Rektorat Lantai 2, Kampus Utama IAILM"><?php echo esc_textarea( $address ); ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="unit_nidn">NIDN (tautan otomatis ke profil dosen)</label></th>
            <td>
                <input name="_unit_nidn" type="text" id="unit_nidn"
                       value="<?php echo esc_attr( get_post_meta( $post->ID, '_unit_nidn', true ) ); ?>" class="regular-text"
                       placeholder="1234567890">
                <p class="description" style="color:#999; font-style:italic; margin:2px 0 0;">Hanya untuk admin &mdash; tidak tampil di publik.</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Foto / Logo</label></th>
            <td>
                <?php
                educampus_media_upload_field( array(
                    'name'     => 'unit_photo_url',
                    'value'    => $photo_url,
                    'label'    => 'Foto / Logo Unit',
                    'btn_text' => 'Pilih Gambar',
                    'mime'     => 'image',
                ) );
                ?>
                <p class="description" style="color:#666; font-style:italic; margin:2px 0 0;">
                    Atau gunakan <strong>Featured Image</strong> di sidebar kanan sebagai alternatif.
                </p>
            </td>
        </tr>
    </table>
    <?php
}

function educampus_save_unit_details_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_unit_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_unit_details_nonce'], 'educampus_save_unit_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'unit_position_title' => '_unit_position_title',
        'unit_email'          => '_unit_email',
        'unit_phone'          => '_unit_phone',
        'unit_address'        => '_unit_address',
        'unit_photo_url'      => '_unit_photo_url',
        '_unit_nidn'          => '_unit_nidn',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            if ( $post_key === 'unit_photo_url' ) {
                update_post_meta( $post_id, $meta_key, esc_url_raw( $_POST[ $post_key ] ) );
            } elseif ( $post_key === 'unit_address' ) {
                update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $post_key ] ) );
            } else {
                update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $post_key ] ) );
            }
        }
    }
}
add_action( 'save_post_unit', 'educampus_save_unit_details_meta' );

/**
 * Seed initial unit posts from existing Customizer theme mods.
 * Runs once to migrate data so users can manage units via admin.
 */
function educampus_seed_initial_units() {
    if ( get_option( 'educampus_units_seeded', false ) ) {
        return;
    }

    $units_config = array(
        'yayasan' => array(
            'group_slug' => 'yayasan',
            'items' => array(
                array(
                    'title'    => get_theme_mod( 'educampus_org_yayasan_name', 'KH. Baban Ahmad Jihad, S.B.T.' ),
                    'position' => get_theme_mod( 'educampus_org_yayasan_title', 'Ketua Pembina Yayasan' ),
                    'email'    => 'yayasan@iailm.ac.id',
                    'address'  => 'Yayasan Pondok Pesantren Suryalaya',
                    'photo'    => get_theme_mod( 'educampus_org_yayasan_photo', '' ),
                    'order'    => 10,
                ),
            ),
        ),
        'senat' => array(
            'group_slug' => 'senat',
            'items' => array(
                array(
                    'title'    => get_theme_mod( 'educampus_org_senat_name', 'Prof. Dr. H. Edi Komarudin, M.Pd.' ),
                    'position' => get_theme_mod( 'educampus_org_senat_title', 'Ketua Senat Akademik' ),
                    'email'    => 'senat@iailm.ac.id',
                    'address'  => 'Senat Akademik IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_senat_photo', '' ),
                    'order'    => 10,
                ),
            ),
        ),
        'rektorat' => array(
            'group_slug' => 'rektorat',
            'items' => array(
                array(
                    'title'    => get_theme_mod( 'educampus_org_rektor_name', 'Dr. KH. Asep Salahudin, M.Ag.' ),
                    'position' => get_theme_mod( 'educampus_org_rektor_title', 'Rektor' ),
                    'email'    => 'rektor@iailm.ac.id',
                    'address'  => 'Rektorat IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_rektor_photo', '' ),
                    'order'    => 10,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_warek1_name', 'Dr. Hj. Nana Rahiana, M.Ag.' ),
                    'position' => get_theme_mod( 'educampus_org_warek1_title', 'Wakil Rektor I (Akademik & Kelembagaan)' ),
                    'email'    => 'warek1@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_warek1_photo', '' ),
                    'order'    => 20,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_warek2_name', 'Dr. H. Mamat Ruhimat, M.Si.' ),
                    'position' => get_theme_mod( 'educampus_org_warek2_title', 'Wakil Rektor II (Umum & Keuangan)' ),
                    'email'    => 'warek2@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_warek2_photo', '' ),
                    'order'    => 30,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_warek3_name', 'Dr. H. Deden Yusuf, M.Ag.' ),
                    'position' => get_theme_mod( 'educampus_org_warek3_title', 'Wakil Rektor III (Kemahasiswaan & Kerjasama)' ),
                    'email'    => 'warek3@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_warek3_photo', '' ),
                    'order'    => 40,
                ),
            ),
        ),
        'dekanat' => array(
            'group_slug' => 'dekanat',
            'items' => array(
                array(
                    'title'    => get_theme_mod( 'educampus_org_dekan_tarbiyah', 'Dr. H. Edi Komarudin, M.Pd.' ),
                    'position' => 'Dekan Fakultas Tarbiyah',
                    'email'    => 'tarbiyah@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_dekan_tarbiyah_photo', '' ),
                    'order'    => 10,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_dekan_syariah', 'Dr. Hj. Nana Rahiana, M.Ag.' ),
                    'position' => 'Dekan Fakultas Syariah',
                    'email'    => 'syariah@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_dekan_syariah_photo', '' ),
                    'order'    => 20,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_dekan_dakwah', 'Dr. H. Deden Yusuf, M.Ag.' ),
                    'position' => 'Dekan Fakultas Dakwah',
                    'email'    => 'dakwah@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_dekan_dakwah_photo', '' ),
                    'order'    => 30,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_dir_pasca', 'Prof. Dr. KH. Asep Salahudin, M.Ag.' ),
                    'position' => 'Direktur Pascasarjana',
                    'email'    => 'pascasarjana@iailm.ac.id',
                    'photo'    => get_theme_mod( 'educampus_org_dir_pasca_photo', '' ),
                    'order'    => 40,
                ),
            ),
        ),
        'lembaga' => array(
            'group_slug' => 'lembaga',
            'items' => array(
                array(
                    'title'    => get_theme_mod( 'educampus_org_ka_lpm', 'Dr. H. Mamat Ruhimat, M.Si.' ),
                    'position' => 'Kepala Lembaga Penjaminan Mutu',
                    'email'    => 'lpm@iailm.ac.id',
                    'address'  => 'LPM IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_ka_lpm_photo', '' ),
                    'order'    => 10,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_ka_lppm', 'Ahmad Fauzi, M.Ag.' ),
                    'position' => 'Kepala Lembaga Penelitian & Pengabdian Masyarakat',
                    'email'    => 'lppm@iailm.ac.id',
                    'address'  => 'LPPM IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_ka_lppm_photo', '' ),
                    'order'    => 20,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_ka_baak', 'H. Lukmanul Hakim, M.Pd.' ),
                    'position' => 'Kepala Biro Administrasi Akademik & Kelembagaan',
                    'email'    => 'baak@iailm.ac.id',
                    'address'  => 'BAAK IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_ka_baak_photo', '' ),
                    'order'    => 30,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_ka_bauk', 'Hj. Euis Marlina, M.E.' ),
                    'position' => 'Kepala Biro Umum & Keuangan',
                    'email'    => 'bauk@iailm.ac.id',
                    'address'  => 'BAUK IAILM',
                    'photo'    => get_theme_mod( 'educampus_org_ka_bauk_photo', '' ),
                    'order'    => 40,
                ),
                array(
                    'title'    => get_theme_mod( 'educampus_org_ka_it', 'Nana Suryana, M.Kom.' ),
                    'position' => 'Kepala UPT IT Center',
                    'email'    => 'it@iailm.ac.id',
                    'address'  => 'BAPSI / IT Center',
                    'photo'    => get_theme_mod( 'educampus_org_ka_it_photo', '' ),
                    'order'    => 50,
                ),
            ),
        ),
    );

    foreach ( $units_config as $group_data ) {
        $term = get_term_by( 'slug', $group_data['group_slug'], 'unit_group' );
        if ( ! $term ) {
            continue;
        }
        foreach ( $group_data['items'] as $item ) {
            $existing = get_posts( array(
                'post_type'      => 'unit',
                'title'          => $item['title'],
                'posts_per_page' => 1,
                'post_status'    => 'any',
                'fields'         => 'ids',
            ) );
            if ( ! empty( $existing ) ) {
                continue;
            }
            $post_id = wp_insert_post( array(
                'post_title'   => $item['title'],
                'post_type'    => 'unit',
                'post_status'  => 'publish',
                'menu_order'   => $item['order'],
            ) );
            if ( $post_id && ! is_wp_error( $post_id ) ) {
                wp_set_object_terms( $post_id, $term->term_id, 'unit_group' );
                if ( ! empty( $item['position'] ) ) {
                    update_post_meta( $post_id, '_unit_position_title', $item['position'] );
                }
                if ( ! empty( $item['email'] ) ) {
                    update_post_meta( $post_id, '_unit_email', $item['email'] );
                }
                if ( ! empty( $item['address'] ) ) {
                    update_post_meta( $post_id, '_unit_address', $item['address'] );
                }
                if ( ! empty( $item['photo'] ) ) {
                    update_post_meta( $post_id, '_unit_photo_url', $item['photo'] );
                }
            }
        }
    }

    update_option( 'educampus_units_seeded', true );
}
add_action( 'init', 'educampus_seed_initial_units', 20 );

/**
 * Meta Box: Partnership URL
 */
function educampus_add_partnership_meta_boxes() {
    add_meta_box(
        'educampus_partnership_details_metabox',
        __( 'Detail Kemitraan', 'educampus' ),
        'educampus_partnership_details_metabox_callback',
        'partnership',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_partnership_meta_boxes' );

function educampus_partnership_details_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_partnership_details', 'educampus_partnership_details_nonce' );
    $url = get_post_meta( $post->ID, '_partnership_url', true );
?>
    <table class="form-table">
        <tr>
            <th><label for="partnership_url"><?php esc_html_e( 'URL Website Mitra', 'educampus' ); ?></label></th>
            <td>
                <input type="url" id="partnership_url" name="partnership_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://mitra.ac.id">
                <p class="description"><?php esc_html_e( 'Kosongkan jika tidak ada link.', 'educampus' ); ?></p>
            </td>
        </tr>
    </table>
<?php
}

function educampus_save_partnership_details_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_partnership_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_partnership_details_nonce'], 'educampus_save_partnership_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['partnership_url'] ) ) {
        update_post_meta( $post_id, '_partnership_url', esc_url_raw( $_POST['partnership_url'] ) );
    }
}
add_action( 'save_post_partnership', 'educampus_save_partnership_details_meta' );

/**
 * Meta Box: Document File & Stats
 */
function educampus_add_dokumen_meta_boxes() {
    add_meta_box(
        'educampus_dokumen_file_metabox',
        __( 'File Dokumen', 'educampus' ),
        'educampus_dokumen_file_metabox_callback',
        'dokumen',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_dokumen_meta_boxes' );

function educampus_dokumen_file_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_dokumen_file', 'educampus_dokumen_file_nonce' );
    $file_url = get_post_meta( $post->ID, '_dokumen_file', true );
    $views = (int) get_post_meta( $post->ID, '_dokumen_view_count', true );
    $downloads = (int) get_post_meta( $post->ID, '_dokumen_download_count', true );
    
    // Category checkboxes
    $terms = get_terms( array( 'taxonomy' => 'dokumen_category', 'hide_empty' => false ) );
    $selected = wp_get_post_terms( $post->ID, 'dokumen_category', array( 'fields' => 'ids' ) );
    if ( empty( $selected ) ) {
        $selected = array();
    }
    wp_nonce_field( 'educampus_save_dokumen_category', 'educampus_dokumen_category_nonce' );
?>
    <table class="form-table">
        <tr>
            <th><label for="dokumen_file"><?php esc_html_e( 'Upload / URL File', 'educampus' ); ?></label></th>
            <td>
                <input type="url" id="dokumen_file" name="dokumen_file" value="<?php echo esc_attr( $file_url ); ?>" class="regular-text" placeholder="https://example.com/dokumen.pdf" style="width: 80%;">
                <button type="button" class="button dokumen-upload-btn"><?php esc_html_e( 'Pilih File', 'educampus' ); ?></button>
                <p class="description"><?php esc_html_e( 'Upload file PDF, DOC, XLS, atau masukkan URL langsung.', 'educampus' ); ?></p>
                <div class="dokumen-file-preview" style="margin-top:8px;">
                    <?php if ( $file_url ) : ?>
                        <a href="<?php echo esc_url( $file_url ); ?>" target="_blank"><?php echo esc_html( basename( $file_url ) ); ?></a>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Kategori', 'educampus' ); ?></th>
            <td>
                <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <div style="max-height:160px;overflow-y:auto;padding:2px 0;">
                        <?php foreach ( $terms as $term ) : ?>
                            <label style="display:inline-block;margin-right:12px;margin-bottom:6px;white-space:nowrap;">
                                <input type="checkbox" name="dokumen_category[]" value="<?php echo esc_attr( $term->term_id ); ?>" <?php checked( in_array( $term->term_id, $selected ) ); ?> />
                                <?php echo esc_html( $term->name ); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p style="margin:4px 0 0;">
                        <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=dokumen_category&post_type=dokumen' ) ); ?>" target="_blank">+ Tambah Kategori Baru</a>
                    </p>
                <?php else : ?>
                    <p style="margin:0;">Belum ada kategori. <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=dokumen_category&post_type=dokumen' ) ); ?>" target="_blank">Buat kategori baru</a>.</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Statistik', 'educampus' ); ?></th>
            <td>
                <span style="display:inline-block;margin-right:20px;"><strong><?php esc_html_e( 'Dilihat:', 'educampus' ); ?></strong> <?php echo number_format_i18n( $views ); ?></span>
                <span style="display:inline-block;"><strong><?php esc_html_e( 'Diunduh:', 'educampus' ); ?></strong> <?php echo number_format_i18n( $downloads ); ?></span>
            </td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($) {
        $('.dokumen-upload-btn').click(function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: '<?php echo esc_js( __( 'Pilih File Dokumen', 'educampus' ) ); ?>',
                button: { text: '<?php echo esc_js( __( 'Gunakan File Ini', 'educampus' ) ); ?>' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#dokumen_file').val(attachment.url);
                $('.dokumen-file-preview').html('<a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a>');
            });
            frame.open();
        });
    });
    </script>
<?php
}

function educampus_save_dokumen_file_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_dokumen_file_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['educampus_dokumen_file_nonce'], 'educampus_save_dokumen_file' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['dokumen_file'] ) ) {
        update_post_meta( $post_id, '_dokumen_file', esc_url_raw( $_POST['dokumen_file'] ) );
    }
    // Save categories
    $terms = isset( $_POST['dokumen_category'] ) ? array_map( 'intval', $_POST['dokumen_category'] ) : array();
    wp_set_post_terms( $post_id, $terms, 'dokumen_category', false );
}
add_action( 'save_post_dokumen', 'educampus_save_dokumen_file_meta' );


/**
 * Add Meta Box for Pages — Custom Hero Banner Image
 * Allows overriding the default hero with a per-page image.
 */
function educampus_add_page_hero_meta_boxes() {
    add_meta_box(
        'educampus_page_hero_metabox',
        esc_html__( 'Hero Banner Custom', 'educampus' ),
        'educampus_page_hero_metabox_callback',
        'page',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'educampus_add_page_hero_meta_boxes' );

function educampus_page_hero_metabox_callback( $post ) {
    wp_nonce_field( 'educampus_save_page_hero', 'educampus_page_hero_nonce' );
    $hero_image = get_post_meta( $post->ID, '_page_hero_image', true );
    ?>
    <p style="margin:0 0 10px;color:#666;font-size:12px;">Upload gambar custom untuk hero section halaman ini. Kosongkan untuk menggunakan default.</p>
    <?php
    educampus_media_upload_field( array(
        'name'     => 'page_hero_image',
        'value'    => $hero_image,
        'label'    => 'Gambar Hero',
        'btn_text' => 'Pilih Gambar',
        'mime'     => 'image',
    ) );
    ?>
    <?php if ( ! empty( $hero_image ) ) : ?>
    <p style="margin:8px 0 0;"><button type="button" class="button" onclick="document.querySelector('[name=page_hero_image]').value='';this.closest('.inside').querySelector('.educampus-media-preview').style.display='none';this.style.display='none';" style="color:#a00;">Hapus Gambar</button></p>
    <?php endif; ?>
    <?php
}

function educampus_save_page_hero_meta( $post_id ) {
    if ( ! isset( $_POST['educampus_page_hero_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['educampus_page_hero_nonce'], 'educampus_save_page_hero' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['page_hero_image'] ) && ! empty( $_POST['page_hero_image'] ) ) {
        update_post_meta( $post_id, '_page_hero_image', esc_url_raw( $_POST['page_hero_image'] ) );
    } else {
        delete_post_meta( $post_id, '_page_hero_image' );
    }
}
add_action( 'save_post', 'educampus_save_page_hero_meta' );


