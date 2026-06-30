<?php
/**
 * Kalender Akademik - Admin Settings Page
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'educampus_kalender_admin_menu' );
add_action( 'admin_enqueue_scripts', 'educampus_kalender_admin_assets' );
add_action( 'wp_ajax_kalender_save_events', 'educampus_kalender_ajax_save_events' );
add_action( 'wp_ajax_kalender_delete_event', 'educampus_kalender_ajax_delete_event' );
add_action( 'wp_ajax_kalender_save_settings', 'educampus_kalender_ajax_save_settings' );
add_action( 'wp_ajax_kalender_import_sample', 'educampus_kalender_ajax_import_sample' );

/**
 * Register admin menu page.
 */
function educampus_kalender_admin_menu() {
    add_menu_page(
        esc_html__( 'Kalender Akademik', 'educampus' ),
        esc_html__( 'Kalender Akademik', 'educampus' ),
        'manage_options',
        'educampus-kalender',
        'educampus_kalender_admin_page',
        'dashicons-calendar-alt',
        31
    );
}

/**
 * Enqueue admin assets only on our page.
 */
function educampus_kalender_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_educampus-kalender' ) {
        return;
    }

    wp_enqueue_style( 'educampus-kalender-admin', get_template_directory_uri() . '/assets/css/kalender-admin.css', array(), filemtime( get_template_directory() . '/assets/css/kalender-admin.css' ) );
    wp_enqueue_script( 'educampus-kalender-admin', get_template_directory_uri() . '/assets/js/kalender-admin.js', array(), filemtime( get_template_directory() . '/assets/js/kalender-admin.js' ), array( 'strategy' => 'defer' ) );

    wp_localize_script( 'educampus-kalender-admin', 'educampusKalender', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'educampus_kalender_nonce' ),
    ) );
}

/**
 * Main admin page callback.
 */
function educampus_kalender_admin_page() {
    $settings = educampus_kalender_get_settings();
    $events   = educampus_kalender_get_events();

    include get_template_directory() . '/admin-templates/kalender-settings.php';
}

/**
 * Get kalender settings from wp_options.
 */
function educampus_kalender_get_settings() {
    $defaults = array(
        'academic_year'   => date( 'Y' ) . '/' . ( date( 'Y' ) + 1 ),
        'semester'        => 'Ganjil',
        'semester_start'  => '',
        'semester_end'    => '',
        'show_on_front'   => false,
        'front_page_slug' => 'kalender-akademik',
    );
    $saved = get_option( 'educampus_kalender_settings', array() );
    return wp_parse_args( $saved, $defaults );
}

/**
 * Get all calendar events from wp_options.
 */
function educampus_kalender_get_events() {
    return get_option( 'educampus_kalender_events', array() );
}

/**
 * Save calendar events.
 */
function educampus_kalender_save_events( $events ) {
    update_option( 'educampus_kalender_events', $events );
}

/**
 * AJAX: Save events.
 */
function educampus_kalender_ajax_save_events() {
    check_ajax_referer( 'educampus_kalender_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $events_json = isset( $_POST['events'] ) ? sanitize_text_field( wp_unslash( $_POST['events'] ) ) : '[]';
    $events = json_decode( $events_json, true );

    if ( ! is_array( $events ) ) {
        wp_send_json_error( array( 'message' => 'Data event tidak valid.' ) );
    }

    // Sanitize each event
    $sanitized = array();
    foreach ( $events as $event ) {
        $sanitized[] = array(
            'id'          => isset( $event['id'] ) ? absint( $event['id'] ) : uniqid(),
            'title'       => isset( $event['title'] ) ? sanitize_text_field( $event['title'] ) : '',
            'start'       => isset( $event['start'] ) ? sanitize_text_field( $event['start'] ) : '',
            'end'         => isset( $event['end'] ) ? sanitize_text_field( $event['end'] ) : '',
            'type'        => isset( $event['type'] ) ? sanitize_text_field( $event['type'] ) : 'other',
            'color'       => isset( $event['color'] ) ? sanitize_hex_color( $event['color'] ) : '#2271b1',
            'description' => isset( $event['description'] ) ? sanitize_textarea_field( $event['description'] ) : '',
        );
    }

    educampus_kalender_save_events( $sanitized );
    wp_send_json_success( array( 'message' => 'Event berhasil disimpan.', 'events' => $sanitized ) );
}

/**
 * AJAX: Delete a single event.
 */
function educampus_kalender_ajax_delete_event() {
    check_ajax_referer( 'educampus_kalender_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $event_id = isset( $_POST['event_id'] ) ? sanitize_text_field( wp_unslash( $_POST['event_id'] ) ) : '';
    if ( empty( $event_id ) ) {
        wp_send_json_error( array( 'message' => 'ID event tidak diberikan.' ) );
    }

    $events = educampus_kalender_get_events();
    $events = array_filter( $events, function( $e ) use ( $event_id ) {
        return (string) $e['id'] !== (string) $event_id;
    });
    $events = array_values( $events );
    educampus_kalender_save_events( $events );

    wp_send_json_success( array( 'message' => 'Event dihapus.', 'events' => $events ) );
}

/**
 * AJAX: Save settings (academic year, semester, etc).
 */
function educampus_kalender_ajax_save_settings() {
    check_ajax_referer( 'educampus_kalender_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $settings = array(
        'academic_year'   => isset( $_POST['academic_year'] ) ? sanitize_text_field( wp_unslash( $_POST['academic_year'] ) ) : '',
        'semester'        => isset( $_POST['semester'] ) ? sanitize_text_field( wp_unslash( $_POST['semester'] ) ) : 'Ganjil',
        'semester_start'  => isset( $_POST['semester_start'] ) ? sanitize_text_field( wp_unslash( $_POST['semester_start'] ) ) : '',
        'semester_end'    => isset( $_POST['semester_end'] ) ? sanitize_text_field( wp_unslash( $_POST['semester_end'] ) ) : '',
        'show_on_front'   => ! empty( $_POST['show_on_front'] ),
        'front_page_slug' => isset( $_POST['front_page_slug'] ) ? sanitize_title( wp_unslash( $_POST['front_page_slug'] ) ) : 'kalender-akademik',
    );

    update_option( 'educampus_kalender_settings', $settings );
    wp_send_json_success( array( 'message' => 'Pengaturan berhasil disimpan.', 'settings' => $settings ) );
}

/**
 * AJAX: Import sample academic calendar events.
 */
function educampus_kalender_ajax_import_sample() {
    check_ajax_referer( 'educampus_kalender_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $year = intval( date( 'Y' ) );
    $sample = array(
        array(
            'id'          => uniqid(),
            'title'       => 'Pendaftaran Mahasiswa Baru',
            'start'       => sprintf( '%d-01-10', $year ),
            'end'         => sprintf( '%d-02-28', $year ),
            'type'        => 'pendaftaran',
            'color'       => '#198754',
            'description' => 'Penerimaan mahasiswa baru semester ganjil.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Orientasi Mahasiswa Baru',
            'start'       => sprintf( '%d-08-18', $year ),
            'end'         => sprintf( '%d-08-20', $year ),
            'type'        => 'orientasi',
            'color'       => '#0d6efd',
            'description' => 'Pengenalan kampus dan fakultas.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Kuliah Semester Ganjil',
            'start'       => sprintf( '%d-09-01', $year ),
            'end'         => sprintf( '%d-12-15', $year ),
            'type'        => 'kuliah',
            'color'       => '#2271b1',
            'description' => 'Perkuliahan semester ganjil berlangsung.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Ujian Tengah Semester (UTS)',
            'start'       => sprintf( '%d-10-20', $year ),
            'end'         => sprintf( '%d-10-31', $year ),
            'type'        => 'uts',
            'color'       => '#fd7e14',
            'description' => 'Pelaksanaan Ujian Tengah Semester.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Ujian Akhir Semester (UAS)',
            'start'       => sprintf( '%d-12-16', $year ),
            'end'         => sprintf( '%d-12-31', $year ),
            'type'        => 'uas',
            'color'       => '#dc3545',
            'description' => 'Pelaksanaan Ujian Akhir Semester.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Libur Semester Ganjil',
            'start'       => sprintf( '%d-01-01', $year + 1 ),
            'end'         => sprintf( '%d-01-07', $year + 1 ),
            'type'        => 'libur',
            'color'       => '#6f42c1',
            'description' => 'Libur akhir semester ganjil.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Wisuda',
            'start'       => sprintf( '%d-02-15', $year + 1 ),
            'end'         => sprintf( '%d-02-15', $year + 1 ),
            'type'        => 'wisuda',
            'color'       => '#c5a059',
            'description' => 'Upacara wisuda periode pertama.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Kuliah Semester Genap',
            'start'       => sprintf( '%d-02-01', $year + 1 ),
            'end'         => sprintf( '%d-06-15', $year + 1 ),
            'type'        => 'kuliah',
            'color'       => '#2271b1',
            'description' => 'Perkuliahan semester genap berlangsung.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Ujian Tengah Semester (UTS) Genap',
            'start'       => sprintf( '%d-03-25', $year + 1 ),
            'end'         => sprintf( '%d-04-05', $year + 1 ),
            'type'        => 'uts',
            'color'       => '#fd7e14',
            'description' => 'Pelaksanaan Ujian Tengah Semester Genap.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Ujian Akhir Semester (UAS) Genap',
            'start'       => sprintf( '%d-06-16', $year + 1 ),
            'end'         => sprintf( '%d-06-30', $year + 1 ),
            'type'        => 'uas',
            'color'       => '#dc3545',
            'description' => 'Pelaksanaan Ujian Akhir Semester Genap.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Wisuda Periode Kedua',
            'start'       => sprintf( '%d-07-10', $year + 1 ),
            'end'         => sprintf( '%d-07-10', $year + 1 ),
            'type'        => 'wisuda',
            'color'       => '#c5a059',
            'description' => 'Upacara wisuda periode kedua.',
        ),
        array(
            'id'          => uniqid(),
            'title'       => 'Libur Akhir Tahun Akademik',
            'start'       => sprintf( '%d-07-11', $year + 1 ),
            'end'         => sprintf( '%d-08-15', $year + 1 ),
            'type'        => 'libur',
            'color'       => '#6f42c1',
            'description' => 'Libur akhir tahun akademik.',
        ),
    );

    educampus_kalender_save_events( $sample );

    // Update settings for current academic year
    update_option( 'educampus_kalender_settings', array(
        'academic_year'   => $year . '/' . ( $year + 1 ),
        'semester'        => 'Ganjil',
        'semester_start'  => sprintf( '%d-09-01', $year ),
        'semester_end'    => sprintf( '%d-12-15', $year ),
        'show_on_front'   => false,
        'front_page_slug' => 'kalender-akademik',
    ) );

    wp_send_json_success( array( 'message' => 'Contoh kalender akademik berhasil diimport.', 'events' => $sample ) );
}

/**
 * Get event type labels and colors.
 */
function educampus_kalender_event_types() {
    return array(
        'kuliah'      => array( 'label' => 'Kuliah',         'color' => '#2271b1' ),
        'uts'         => array( 'label' => 'UTS',            'color' => '#fd7e14' ),
        'uas'         => array( 'label' => 'UAS',            'color' => '#dc3545' ),
        'libur'       => array( 'label' => 'Libur',          'color' => '#6f42c1' ),
        'wisuda'      => array( 'label' => 'Wisuda',         'color' => '#c5a059' ),
        'pendaftaran' => array( 'label' => 'Pendaftaran',    'color' => '#198754' ),
        'orientasi'   => array( 'label' => 'Orientasi',      'color' => '#0d6efd' ),
        'seminar'     => array( 'label' => 'Seminar',        'color' => '#20c997' ),
        'rab'         => array( 'label' => 'RAPAT',          'color' => '#6610f2' ),
        'other'       => array( 'label' => 'Lainnya',        'color' => '#6c757d' ),
    );
}

/**
 * Get front-end events as JSON for calendar rendering.
 */
function educampus_kalender_get_frontend_events() {
    $events  = educampus_kalender_get_events();
    $types   = educampus_kalender_event_types();
    $output  = array();

    foreach ( $events as $event ) {
        $type_key = $event['type'] ?? 'other';
        $color    = ! empty( $event['color'] ) ? $event['color'] : ( $types[ $type_key ]['color'] ?? '#6c757d' );

        $output[] = array(
            'id'          => $event['id'],
            'title'       => $event['title'],
            'start'       => $event['start'],
            'end'         => $event['end'],
            'color'       => $color,
            'type'        => $type_key,
            'typeLabel'   => $types[ $type_key ]['label'] ?? 'Lainnya',
            'description' => $event['description'] ?? '',
        );
    }

    return $output;
}
