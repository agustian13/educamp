<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'educampus_sister_admin_menu' );
add_action( 'wp_ajax_sister_test_connection', 'educampus_sister_ajax_test_connection' );
add_action( 'wp_ajax_sister_save_config', 'educampus_sister_ajax_save_config' );
add_action( 'wp_ajax_sister_sync_start', 'educampus_sister_ajax_sync_start' );
add_action( 'wp_ajax_sister_sync_chunk', 'educampus_sister_ajax_sync_chunk' );
add_action( 'wp_ajax_sister_sync_status', 'educampus_sister_ajax_sync_status' );
add_action( 'wp_ajax_sister_debug_list', 'educampus_sister_ajax_debug_list' );
add_action( 'wp_ajax_sister_reset_mapping', 'educampus_sister_ajax_reset_mapping' );
add_action( 'admin_enqueue_scripts', 'educampus_sister_admin_assets' );

function educampus_sister_admin_menu() {
    add_menu_page(
        esc_html__( 'Integrasi SISTER', 'educampus' ),
        esc_html__( 'SISTER', 'educampus' ),
        'manage_options',
        'educampus-sister',
        'educampus_sister_admin_page',
        'dashicons-networking',
        30
    );
}

function educampus_sister_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_educampus-sister' ) {
        return;
    }

    wp_enqueue_style( 'educampus-sister-admin', get_template_directory_uri() . '/assets/vendor/nprogress.css', array(), '0.2.0' );
    wp_enqueue_script( 'educampus-sister-sync', get_template_directory_uri() . '/assets/js/sister-sync.js', array(), filemtime( get_template_directory() . '/assets/js/sister-sync.js' ), array( 'strategy' => 'defer' ) );

    wp_localize_script( 'educampus-sister-sync', 'educampusSister', array(
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'educampus_sister_nonce' ),
        'siteUrl'  => site_url(),
    ) );
}

function educampus_sister_admin_page() {
    $api = new EduCampus_Sister_API();
    $config = $api->get_config();
    $is_configured = ! empty( $config['api_url'] ) && ! empty( $config['username'] );
    $last_sync = ! empty( $config['last_synced_at'] ) ? $config['last_synced_at'] : '';
    $total_dosen = wp_count_posts( 'lecturer' )->publish;
    $total_draft = wp_count_posts( 'lecturer' )->draft;

    include get_template_directory() . '/admin-templates/sister-settings.php';
}

function educampus_sister_ajax_test_connection() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $api_url     = isset( $_POST['api_url'] ) ? esc_url_raw( $_POST['api_url'] ) : '';
    $username    = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
    $password    = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';
    $id_pengguna = isset( $_POST['id_pengguna'] ) ? sanitize_text_field( wp_unslash( $_POST['id_pengguna'] ) ) : '';

    if ( empty( $api_url ) || empty( $username ) ) {
        wp_send_json_error( array( 'message' => 'URL API dan Username harus diisi.' ) );
    }

    $api = new EduCampus_Sister_API();
    $config = $api->get_config();

    $config['api_url']     = $api_url;
    $config['username']    = $username;
    $config['id_pengguna'] = $id_pengguna;

    if ( ! empty( $password ) ) {
        $config['password'] = $password;
    } elseif ( empty( $config['password'] ) ) {
        wp_send_json_error( array( 'message' => 'Password harus diisi (belum ada password tersimpan).' ) );
    }

    $api->save_config( $config );

    $result = $api->login();

    if ( $result['success'] ) {
        wp_send_json_success( $result );
    } else {
        wp_send_json_error( $result );
    }
}

function educampus_sister_ajax_save_config() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $api = new EduCampus_Sister_API();
    $config = $api->get_config();

    $config['api_url']     = isset( $_POST['api_url'] ) ? esc_url_raw( $_POST['api_url'] ) : $config['api_url'];
    $config['username']    = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : $config['username'];
    $config['id_pengguna'] = isset( $_POST['id_pengguna'] ) ? sanitize_text_field( wp_unslash( $_POST['id_pengguna'] ) ) : $config['id_pengguna'];

    if ( ! empty( $_POST['password'] ) ) {
        $config['password'] = sanitize_text_field( wp_unslash( $_POST['password'] ) );
    }

    $api->save_config( $config );

    wp_send_json_success( array( 'message' => 'Konfigurasi berhasil disimpan.' ) );
}

function educampus_sister_ajax_sync_start() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $api = new EduCampus_Sister_API();

    if ( ! $api->is_configured() ) {
        wp_send_json_error( array( 'message' => 'Konfigurasi SISTER belum lengkap.' ) );
    }

    if ( ! $api->login()['success'] ) {
        wp_send_json_error( array( 'message' => 'Gagal login ke SISTER. Test koneksi terlebih dahulu.' ) );
    }

    $mode = isset( $_POST['sync_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['sync_mode'] ) ) : 'basic';
    $chunk_size = ( $mode === 'lengkap' ) ? 5 : 10;

    // Auto-match SDM dari SISTER ke existing post — STRICT match only (exact NIDN or exact title)
    $sdm_list = $api->get_sdm_list( 200, 0 );
    if ( is_array( $sdm_list ) ) {
        $sdm_data = $sdm_list['data'] ?? $sdm_list;
        if ( is_array( $sdm_data ) ) {
            $matched = 0;
            foreach ( $sdm_data as $sdm ) {
                if ( empty( $sdm['id_sdm'] ) ) continue;
                $nidn = $sdm['nidn'] ?? '';
                $nama = $sdm['nama'] ?? $sdm['nama_sdm'] ?? '';
                $post_id = educampus_sister_find_lecturer( $nidn, $nama, true );
                if ( $post_id ) {
                    $existing_sid = get_post_meta( $post_id, '_lecturer_sister_id', true );
                    if ( empty( $existing_sid ) ) {
                        update_post_meta( $post_id, '_lecturer_sister_id', $sdm['id_sdm'] );
                        error_log( "[SISTER MATCH] Post {$post_id} -> SDM {$sdm['id_sdm']} ({$nama})" );
                        $matched++;
                    } elseif ( $existing_sid !== $sdm['id_sdm'] ) {
                        // Title collision: SDM berbeda punya nama sama → buat post baru
                        $new_id = wp_insert_post( array(
                            'post_type'   => 'lecturer',
                            'post_title'  => $nama,
                            'post_status' => 'publish',
                            'meta_input'  => array(
                                '_lecturer_sister_id' => $sdm['id_sdm'],
                                '_lecturer_nidn'      => $nidn,
                            ),
                        ) );
                        if ( ! is_wp_error( $new_id ) ) {
                            error_log( "[SISTER CREATE] Post {$new_id} dibuat untuk SDM {$sdm['id_sdm']} ({$nama}) — tabrakan judul dengan post {$post_id}" );
                            $matched++;
                        }
                    }
                } elseif ( ! empty( $nama ) ) {
                    // No exact match found → buat post baru
                    $new_id = wp_insert_post( array(
                        'post_type'   => 'lecturer',
                        'post_title'  => $nama,
                        'post_status' => 'publish',
                        'meta_input'  => array(
                            '_lecturer_sister_id' => $sdm['id_sdm'],
                            '_lecturer_nidn'      => $nidn,
                        ),
                    ) );
                    if ( ! is_wp_error( $new_id ) ) {
                        error_log( "[SISTER CREATE] Post {$new_id} dibuat untuk SDM {$sdm['id_sdm']} ({$nama}) — tidak ditemukan post yang cocok" );
                        $matched++;
                    }
                }
            }
            if ( $matched > 0 ) {
                error_log( "[SISTER MATCH] Total {$matched} post baru dipasangkan." );
            }
        }
    }

    // Hitung post lecturer yang sudah punya _lecturer_sister_id
    $existing = get_posts( array(
        'post_type'      => 'lecturer',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_lecturer_sister_id',
                'compare' => 'EXISTS',
            ),
        ),
    ) );

    $total = count( $existing );

    if ( $total === 0 ) {
        wp_send_json_error( array( 'message' => 'Belum ada dosen dengan SISTER ID. Jalankan sync dulu, atau tambah dosen manual.' ) );
    }

    $progress = array(
        'total'      => $total,
        'done'       => 0,
        'offset'     => 0,
        'updated'    => 0,
        'skipped'    => 0,
        'status'     => 'running',
        'errors'     => array(),
        'sync_mode'  => $mode,
        'chunk_size' => $chunk_size,
    );

    update_option( 'educampus_sister_progress', $progress, false );

    wp_send_json_success( array(
        'total'   => $total,
        'message' => "Memperbarui {$total} data dosen dari SISTER.",
    ) );
}

function educampus_sister_ajax_sync_chunk() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    try {
        $progress = get_option( 'educampus_sister_progress', array() );

        if ( empty( $progress ) || $progress['status'] !== 'running' ) {
            wp_send_json_error( array( 'message' => 'Tidak ada proses sync yang berjalan.' ) );
        }

        $api = new EduCampus_Sister_API();
        $chunk_size = isset( $progress['chunk_size'] ) ? (int) $progress['chunk_size'] : 10;

        // Ambil post lecturer yang sudah punya sister_id, dengan offset
        $posts = get_posts( array(
            'post_type'      => 'lecturer',
            'posts_per_page' => $chunk_size,
            'offset'         => $progress['offset'],
            'post_status'    => 'any',
            'fields'         => 'ids',
            'meta_query'     => array(
                array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ),
            ),
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ) );

        if ( empty( $posts ) ) {
            $progress['status'] = 'completed';
            $config = $api->get_config();
            $config['last_synced_at'] = current_time( 'mysql' );
            $api->save_config( $config );
            update_option( 'educampus_sister_progress', $progress, false );
            wp_send_json_success( array(
                'done' => $progress['done'], 'total' => $progress['total'],
                'updated' => $progress['updated'], 'skipped' => $progress['skipped'],
                'status' => 'completed',
            ) );
        }

        foreach ( $posts as $post_id ) {
            $sister_id = get_post_meta( $post_id, '_lecturer_sister_id', true );

            if ( empty( $sister_id ) ) {
                $progress['skipped']++;
                $progress['done']++;
                continue;
            }

            $result = educampus_sister_update_single( $api, $post_id, $sister_id, $progress );

            if ( $result['action'] === 'updated' ) {
                $progress['updated']++;
            } else {
                $progress['skipped']++;
                if ( $result['reason'] ) {
                    $progress['errors'][] = $result['reason'];
                }
            }

            $progress['done']++;
        }

        $progress['offset'] += $chunk_size;

        $is_complete = $progress['done'] >= $progress['total'];

        if ( $is_complete ) {
            $progress['status'] = 'completed';
            $config = $api->get_config();
            $config['last_synced_at'] = current_time( 'mysql' );
            $api->save_config( $config );
        }

        update_option( 'educampus_sister_progress', $progress, false );

        wp_send_json_success( array(
            'done' => $progress['done'], 'total' => $progress['total'],
            'updated' => $progress['updated'], 'skipped' => $progress['skipped'],
            'status' => $is_complete ? 'completed' : 'running',
        ) );

    } catch ( Throwable $e ) {
        error_log( '[SISTER SYNC FATAL] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() );
        wp_send_json_success( array(
            'done' => 0, 'total' => 0, 'updated' => 0, 'skipped' => 0,
            'status' => 'completed',
        ) );
    }
}

function educampus_sister_ajax_debug_list() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $api = new EduCampus_Sister_API();
    $offset = isset( $_POST['offset'] ) ? (int) $_POST['offset'] : 0;
    $limit = isset( $_POST['limit'] ) ? (int) $_POST['limit'] : 5;

    $response = $api->get_sdm_list( $limit, $offset );

    wp_send_json_success( array(
        'response_type' => gettype( $response ),
        'response_keys' => is_array( $response ) ? array_keys( $response ) : null,
        'has_data_key'  => is_array( $response ) && isset( $response['data'] ),
        'has_total_key' => is_array( $response ) && isset( $response['total'] ),
        'total_value'   => is_array( $response ) ? ( $response['total'] ?? 'not set' ) : null,
        'data_count'    => is_array( $response ) && isset( $response['data'] ) && is_array( $response['data'] ) ? count( $response['data'] ) : 0,
                'first_item'    => is_array( $response ) && isset( $response['data'][0] ) ? $response['data'][0] : ( is_array( $response ) && isset( $response[0] ) ? $response[0] : null ),
            ) );
}

function educampus_sister_ajax_debug_pendidikan() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }
    $posts = get_posts( array( 'post_type' => 'lecturer', 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ) ),
    ) );
    if ( empty( $posts ) ) { wp_send_json_error( array( 'message' => 'Tidak ada dosen.' ) ); }
    $sid = get_post_meta( $posts[0], '_lecturer_sister_id', true );
    $api = new EduCampus_Sister_API();
    $api->login();
    $r = $api->get_riwayat_pendidikan( $sid );
    wp_send_json_success( array( 'raw_response' => $r ) );
}

function educampus_sister_ajax_debug_penelitian() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }
    $posts = get_posts( array( 'post_type' => 'lecturer', 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ) ),
    ) );
    if ( empty( $posts ) ) { wp_send_json_error( array( 'message' => 'Tidak ada dosen.' ) ); }
    $sid = get_post_meta( $posts[0], '_lecturer_sister_id', true );
    $api = new EduCampus_Sister_API();
    $api->login();
    $r = $api->get_penelitian( $sid );
    wp_send_json_success( array( 'raw_response' => $r ) );
}

function educampus_sister_ajax_debug_publikasi() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }
    $posts = get_posts( array( 'post_type' => 'lecturer', 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ) ),
    ) );
    if ( empty( $posts ) ) { wp_send_json_error( array( 'message' => 'Tidak ada dosen.' ) ); }
    $sid = get_post_meta( $posts[0], '_lecturer_sister_id', true );
    $api = new EduCampus_Sister_API();
    $api->login();
    $r = $api->get_publikasi( $sid );
    wp_send_json_success( array( 'raw_response' => $r ) );
}

function educampus_sister_ajax_debug_prodi() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }
    $posts = get_posts( array( 'post_type' => 'lecturer', 'posts_per_page' => 5, 'post_status' => 'any', 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ) ),
    ) );
    if ( empty( $posts ) ) { wp_send_json_error( array( 'message' => 'Tidak ada dosen.' ) ); }
    $api = new EduCampus_Sister_API();
    $api->login();
    $result = array();
    foreach ( $posts as $pid ) {
        $sid = get_post_meta( $pid, '_lecturer_sister_id', true );
        $penugasan = $api->get_penugasan( $sid );
        $result[] = array(
            'post_id'    => $pid,
            'post_title' => get_the_title( $pid ),
            'sister_id'  => $sid,
            'penugasan'  => is_array( $penugasan ) ? ( $penugasan['data'] ?? $penugasan ) : null,
        );
    }
    $programs = get_posts( array( 'post_type' => 'program', 'posts_per_page' => -1, 'fields' => 'ids' ) );
    $prog_titles = array();
    foreach ( $programs as $pid ) {
        $prog_titles[] = get_the_title( $pid );
    }
    wp_send_json_success( array(
        'lecturers'      => $result,
        'program_titles' => $prog_titles,
        'program_count'  => count( $prog_titles ),
    ) );
}

add_action( 'wp_ajax_sister_debug_prodi', 'educampus_sister_ajax_debug_prodi' );
add_action( 'wp_ajax_sister_debug_pendidikan', 'educampus_sister_ajax_debug_pendidikan' );
add_action( 'wp_ajax_sister_debug_penelitian', 'educampus_sister_ajax_debug_penelitian' );
add_action( 'wp_ajax_sister_debug_publikasi', 'educampus_sister_ajax_debug_publikasi' );
add_action( 'wp_ajax_sister_debug_prodi', 'educampus_sister_ajax_debug_prodi' );
add_action( 'wp_ajax_sister_publish_all', 'educampus_sister_ajax_publish_all' );

function educampus_sister_ajax_debug_foto() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $sister_id = isset( $_POST['sister_id'] ) ? sanitize_text_field( wp_unslash( $_POST['sister_id'] ) ) : '';

    if ( empty( $sister_id ) ) {
        // ambil sister_id pertama dari post lecturer
        $posts = get_posts( array(
            'post_type' => 'lecturer', 'posts_per_page' => 1,
            'post_status' => 'any', 'fields' => 'ids',
            'meta_query' => array( array( 'key' => '_lecturer_sister_id', 'compare' => 'EXISTS' ) ),
        ) );
        if ( empty( $posts ) ) {
            wp_send_json_error( array( 'message' => 'Tidak ada dosen dengan sister_id.' ) );
        }
        $sister_id = get_post_meta( $posts[0], '_lecturer_sister_id', true );
        if ( empty( $sister_id ) ) {
            wp_send_json_error( array( 'message' => 'sister_id kosong.' ) );
        }
    }

    $api = new EduCampus_Sister_API();

    if ( ! $api->login()['success'] ) {
        wp_send_json_error( array( 'message' => 'Gagal login.' ) );
    }

    $foto = $api->get_foto( $sister_id );

    if ( is_null( $foto ) ) {
        wp_send_json_error( array( 'message' => 'Foto tidak ditemukan (null).' ) );
    }

    $len = strlen( $foto );
    $is_jpeg = $len > 2 && ord( $foto[0] ) === 0xFF && ord( $foto[1] ) === 0xD8;
    $is_png  = $len > 4 && ord( $foto[0] ) === 0x89 && ord( $foto[1] ) === 0x50 && ord( $foto[2] ) === 0x4E && ord( $foto[3] ) === 0x47;
    $first_bytes = bin2hex( substr( $foto, 0, min( 20, $len ) ) );

    // Cek apakah response-nya json bukan binary
    $json_test = json_decode( $foto, true );

    wp_send_json_success( array(
        'sister_id'    => $sister_id,
        'length'       => $len,
        'is_jpeg'      => $is_jpeg,
        'is_png'       => $is_png,
        'first_bytes'  => $first_bytes,
        'is_json'      => is_array( $json_test ),
        'json_content' => is_array( $json_test ) ? $json_test : null,
        'sample'       => $len > 0 ? substr( $foto, 0, 500 ) : '(empty)',
    ) );
}

add_action( 'wp_ajax_sister_debug_foto', 'educampus_sister_ajax_debug_foto' );

function educampus_sister_ajax_sync_status() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $progress = get_option( 'educampus_sister_progress', array() );

    if ( empty( $progress ) ) {
        wp_send_json_success( array( 'status' => 'idle' ) );
    }

    wp_send_json_success( array(
        'done'    => $progress['done'],
        'total'   => $progress['total'],
        'updated' => $progress['updated'] ?? 0,
        'skipped' => $progress['skipped'],
        'status'  => $progress['status'],
    ) );
}

function educampus_sister_update_single( $api, $post_id, $sister_id, $progress ) {
    update_post_meta( $post_id, '_lecturer_last_synced_at', current_time( 'mysql' ) );

    // Pastikan _lecturer_sister_id tersimpan
    update_post_meta( $post_id, '_lecturer_sister_id', $sister_id );

    // Bersihkan data dummy lama agar tidak nempel kalau API return kosong
    // CATATAN: _lecturer_position untuk posisi struktural (input manual), tidak di-clean di sini
    $managed_fields = array(
        '_lecturer_jabatan_fungsional', '_lecturer_nidn', '_lecturer_email', '_lecturer_phone',
        '_lecturer_address', '_lecturer_education', '_lecturer_expertise',
        '_lecturer_gelar', '_lecturer_nik', '_lecturer_nuptk',
        '_lecturer_program_id', '_lecturer_sister_prodi_raw',
    );
    foreach ( $managed_fields as $f ) {
        delete_post_meta( $post_id, $f );
    }

    // Penelitian/publikasi/pengabdian hanya dibersihkan di mode 'lengkap'
    // agar data existing tidak hilang di mode 'basic'
    if ( $progress['sync_mode'] === 'lengkap' ) {
        delete_post_meta( $post_id, '_lecturer_research' );
        delete_post_meta( $post_id, '_lecturer_publications' );
        delete_post_meta( $post_id, '_lecturer_community_service' );
    }

    // Profile — nama, gender, TTL
    $profile_data = array();
    $profile = $api->get_profile( $sister_id );
    if ( is_array( $profile ) ) {
        $p = $profile['data'] ?? $profile;
        if ( is_array( $p ) ) {
            $profile_data = $p;
            $new_nama = $p['nama_sdm'] ?? $p['nama'] ?? '';
            if ( $new_nama ) {
                wp_update_post( array( 'ID' => $post_id, 'post_title' => $new_nama ) );
            }
            if ( ! empty( $p['jenis_kelamin'] ) ) {
                update_post_meta( $post_id, '_lecturer_gender', $p['jenis_kelamin'] );
            }
            if ( ! empty( $p['tempat_lahir'] ) ) {
                update_post_meta( $post_id, '_lecturer_tempat_lahir', $p['tempat_lahir'] );
            }
            if ( ! empty( $p['tanggal_lahir'] ) ) {
                update_post_meta( $post_id, '_lecturer_tanggal_lahir', $p['tanggal_lahir'] );
            }
            if ( ! empty( $p['nidn'] ) ) {
                update_post_meta( $post_id, '_lecturer_nidn', $p['nidn'] );
            }
        }
    }

    // Alamat — email + alamat
    $alamat = $api->get_alamat( $sister_id );
    if ( is_array( $alamat ) ) {
        $a = $alamat['data'] ?? $alamat;
        if ( is_array( $a ) ) {
            if ( ! empty( $a['email'] ) ) {
                update_post_meta( $post_id, '_lecturer_email', $a['email'] );
            }
            if ( ! empty( $a['alamat'] ) ) {
                update_post_meta( $post_id, '_lecturer_address', $a['alamat'] );
            }
        }
    }

    // Kependudukan — NIK
    $kependudukan = $api->get_kependudukan( $sister_id );
    if ( is_array( $kependudukan ) ) {
        $k = $kependudukan['data'] ?? $kependudukan;
        if ( is_array( $k ) && ! empty( $k['nik'] ) ) {
            update_post_meta( $post_id, '_lecturer_nik', $k['nik'] );
        }
    }

    // Kepegawaian — NIDN, NUPTK
    $kepegawaian = $api->get_kepegawaian( $sister_id );
    if ( is_array( $kepegawaian ) ) {
        $k = $kepegawaian['data'] ?? $kepegawaian;
        if ( is_array( $k ) ) {
            if ( ! empty( $k['nidn'] ) ) {
                update_post_meta( $post_id, '_lecturer_nidn', $k['nidn'] );
                error_log( "[SISTER NIDN] saved {$k['nidn']} untuk post_id={$post_id}" );
            }
            if ( ! empty( $k['nuptk'] ) ) {
                update_post_meta( $post_id, '_lecturer_nuptk', $k['nuptk'] );
            }
            if ( ! empty( $k['nama_jabatan_fungsional'] ) ) {
                update_post_meta( $post_id, '_lecturer_jabatan_fungsional', $k['nama_jabatan_fungsional'] );
            }
        }
    }

    // Program studi / homebase dari endpoint /penugasan
    $prodi_raw = '';
    $penugasan = $api->get_penugasan( $sister_id );
    if ( is_array( $penugasan ) ) {
        $p_list = $penugasan['data'] ?? $penugasan;
        if ( is_array( $p_list ) && ! empty( $p_list ) ) {
            // Sort: homebase first, then by tanggal_mulai descending
            usort( $p_list, function( $a, $b ) {
                $ha = ( $a['apakah_penugasan_homebase'] ?? '' ) === 'Ya' ? 1 : 0;
                $hb = ( $b['apakah_penugasan_homebase'] ?? '' ) === 'Ya' ? 1 : 0;
                if ( $ha !== $hb ) return $hb - $ha;
                return strtotime( $b['tanggal_mulai'] ?? '1970-01-01' ) - strtotime( $a['tanggal_mulai'] ?? '1970-01-01' );
            } );
            $prodi_raw     = $p_list[0]['unit_kerja'] ?? '';
            $prodi_jenjang = $p_list[0]['jenjang_pendidikan'] ?? '';
            error_log( "[SISTER PRODI] Penugasan: '{$prodi_raw}' jenjang='{$prodi_jenjang}' untuk post_id={$post_id}" );
        }
    }

    if ( ! empty( $prodi_raw ) ) {
        $program_id = educampus_sister_match_program( $prodi_raw, $prodi_jenjang );
        if ( $program_id ) {
            update_post_meta( $post_id, '_lecturer_program_id', $program_id );
        } else {
            update_post_meta( $post_id, '_lecturer_sister_prodi_raw', $prodi_raw );
            error_log( "[SISTER PRODI] Tidak cocok: '{$prodi_raw}' untuk post_id={$post_id}" );
        }
    }

    // Bidang Ilmu → _lecturer_expertise
    $bidang_ilmu = $api->get_bidang_ilmu( $sister_id );
    if ( is_array( $bidang_ilmu ) ) {
        $b = $bidang_ilmu['data'] ?? $bidang_ilmu;
        if ( is_array( $b ) && ! empty( $b ) ) {
            $skills = array();
            foreach ( $b as $item ) {
                $kelompok = $item['kelompok_bidang'] ?? $item['cabang_ilmu'] ?? $item['nama_bidang'] ?? '';
                if ( $kelompok ) {
                    $skills[] = trim( $kelompok );
                }
            }
            if ( $skills ) {
                update_post_meta( $post_id, '_lecturer_expertise', implode( "\n", $skills ) );
            }
        }
    }

    // Pendidikan
    $pendidikan = $api->get_riwayat_pendidikan( $sister_id );
    if ( is_null( $pendidikan ) ) {
        error_log( "[SISTER PENDIDIKAN] null untuk sister_id={$sister_id}" );
    } elseif ( ! is_array( $pendidikan ) ) {
        error_log( "[SISTER PENDIDIKAN] bukan array, type=" . gettype( $pendidikan ) . " untuk sister_id={$sister_id}" );
    } else {
        $pe = $pendidikan['data'] ?? $pendidikan;
        error_log( "[SISTER PENDIDIKAN] count=" . ( is_array( $pe ) ? count( $pe ) : 'not_array' ) . " untuk sister_id={$sister_id}" );
        if ( is_array( $pe ) && ! empty( $pe ) ) {
            $lines = array();
            foreach ( $pe as $i => $edu ) {
                if ( $i === 0 ) {
                    error_log( "[SISTER PENDIDIKAN] item[0] keys: " . implode( ',', array_keys( $edu ) ) );
                }
                $level     = trim( $edu['jenjang_pendidikan'] ?? '' );
                $gelar     = trim( $edu['gelar_akademik'] ?? '' );
                $bidang    = trim( $edu['bidang_studi'] ?? $edu['program_studi'] ?? '' );
                $institusi = trim( $edu['nama_perguruan_tinggi'] ?? $edu['pt'] ?? '' );
                $tahun     = trim( $edu['tahun_lulus'] ?? '' );

                $gelar_parts = array_filter( array( $gelar, $level ? "({$level})" : '', $bidang ) );
                $degree_str  = implode( ' ', $gelar_parts );
                $line_parts  = array_filter( array( $degree_str, $institusi, $tahun ) );
                if ( $line_parts ) {
                    $lines[] = implode( ' | ', $line_parts );
                }
            }
            if ( $lines ) {
                update_post_meta( $post_id, '_lecturer_education', implode( "\n", $lines ) );
                error_log( "[SISTER PENDIDIKAN] saved " . count( $lines ) . " lines for post_id={$post_id}" );
            } else {
                error_log( "[SISTER PENDIDIKAN] semua field kosong untuk sister_id={$sister_id}" );
            }
            // Save all gelar from all jenjang, comma-separated
            $all_gelar = array();
            foreach ( $pe as $edu ) {
                $g = trim( $edu['gelar_akademik'] ?? '' );
                if ( ! empty( $g ) ) {
                    $all_gelar[] = $g;
                }
            }
            if ( ! empty( $all_gelar ) ) {
                $gelar_str = implode( ', ', $all_gelar );
                update_post_meta( $post_id, '_lecturer_gelar', $gelar_str );
                error_log( "[SISTER GELAR] saved '{$gelar_str}' untuk post_id={$post_id}" );
            }
        }
    }

    // Foto di SEMUA mode (sudah terbukti API return JPEG valid)
    $foto = $api->get_foto( $sister_id );
    if ( $foto && strlen( $foto ) > 100 ) {
        $is_image = ord( $foto[0] ) === 0xFF && ord( $foto[1] ) === 0xD8;
        $is_image = $is_image || ( ord( $foto[0] ) === 0x89 && ord( $foto[1] ) === 0x50 );
        if ( $is_image ) {
            $new_hash = md5( $foto );
            $old_hash = get_post_meta( $post_id, '_lecturer_photo_hash', true );
            if ( $new_hash !== $old_hash ) {
                $saved = educampus_sister_set_photo( $post_id, $foto, get_the_title( $post_id ) );
                if ( $saved ) {
                    update_post_meta( $post_id, '_lecturer_photo_hash', $new_hash );
                } else {
                    error_log( "[SISTER FOTO] Gagal simpan post_id={$post_id}" );
                }
            } else {
                error_log( "[SISTER FOTO] Skip — hash sama post_id={$post_id}" );
            }
        }
    }

    // Penelitian + publikasi + pengabdian hanya di mode 'lengkap'
    if ( $progress['sync_mode'] === 'lengkap' ) {
        $penelitian = $api->get_penelitian( $sister_id );
        if ( is_null( $penelitian ) ) {
            error_log( "[SISTER PENELITIAN] null untuk sister_id={$sister_id}" );
        } elseif ( ! is_array( $penelitian ) ) {
            error_log( "[SISTER PENELITIAN] bukan array, type=" . gettype( $penelitian ) . " untuk sister_id={$sister_id}" );
        } else {
            $r_data = $penelitian['data'] ?? $penelitian;
            if ( is_array( $r_data ) && ! empty( $r_data ) ) {
                $lines = array();
                foreach ( $r_data as $r ) {
                    $parts = array_filter( array( $r['judul_penelitian'] ?? $r['judul'] ?? '', $r['tahun_pelaksanaan'] ?? '', $r['sumber_dana'] ?? '' ) );
                    $lines[] = implode( ' | ', $parts );
                }
                if ( $lines ) {
                    update_post_meta( $post_id, '_lecturer_research', implode( "\n", $lines ) );
                    error_log( "[SISTER PENELITIAN] saved " . count( $lines ) . " items untuk post_id={$post_id}" );
                }
            } else {
                error_log( "[SISTER PENELITIAN] data kosong untuk sister_id={$sister_id}" );
            }
        }

        $publikasi = $api->get_publikasi( $sister_id );
        if ( is_null( $publikasi ) ) {
            error_log( "[SISTER PUBLIKASI] null untuk sister_id={$sister_id}" );
        } elseif ( ! is_array( $publikasi ) ) {
            error_log( "[SISTER PUBLIKASI] bukan array, type=" . gettype( $publikasi ) . " untuk sister_id={$sister_id}" );
        } else {
            $p_data = $publikasi['data'] ?? $publikasi;
            if ( is_array( $p_data ) && ! empty( $p_data ) ) {
                $lines = array();
                foreach ( $p_data as $p ) {
                    $parts = array_filter( array( $p['judul'] ?? '', $p['nama_jurnal'] ?? '', $p['tanggal'] ?? '' ) );
                    $lines[] = implode( ' | ', $parts );
                }
                if ( $lines ) {
                    update_post_meta( $post_id, '_lecturer_publications', implode( "\n", $lines ) );
                    error_log( "[SISTER PUBLIKASI] saved " . count( $lines ) . " items untuk post_id={$post_id}" );
                }
            } else {
                error_log( "[SISTER PUBLIKASI] data kosong untuk sister_id={$sister_id}" );
            }
        }

        $pengabdian = $api->get_pengabdian( $sister_id );
        if ( is_null( $pengabdian ) ) {
            error_log( "[SISTER PENGABDIAN] null untuk sister_id={$sister_id}" );
        } elseif ( ! is_array( $pengabdian ) ) {
            error_log( "[SISTER PENGABDIAN] bukan array, type=" . gettype( $pengabdian ) . " untuk sister_id={$sister_id}" );
        } else {
            $a_data = $pengabdian['data'] ?? $pengabdian;
            if ( is_array( $a_data ) && ! empty( $a_data ) ) {
                $lines = array();
                foreach ( $a_data as $a ) {
                    $parts = array_filter( array( $a['judul_pengabdian'] ?? $a['judul'] ?? '', $a['tahun_pelaksanaan'] ?? '', $a['sumber_dana'] ?? '' ) );
                    $lines[] = implode( ' | ', $parts );
                }
                if ( $lines ) {
                    update_post_meta( $post_id, '_lecturer_community_service', implode( "\n", $lines ) );
                    error_log( "[SISTER PENGABDIAN] saved " . count( $lines ) . " items untuk post_id={$post_id}" );
                }
            } else {
                error_log( "[SISTER PENGABDIAN] data kosong untuk sister_id={$sister_id}" );
            }
        }
    }

    return array( 'action' => 'updated', 'reason' => '' );
}

function educampus_sister_find_lecturer( $nidn, $nama, $strict = false ) {
    if ( ! empty( $nidn ) ) {
        $existing = get_posts( array(
            'post_type'      => 'lecturer',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
            'meta_query'     => array(
                array(
                    'key'   => '_lecturer_nidn',
                    'value' => $nidn,
                ),
            ),
        ) );

        if ( ! empty( $existing ) ) {
            return $existing[0];
        }
    }

    if ( ! empty( $nama ) ) {
        $nama_clean = preg_replace( '/^(Dr\.|Drs\.|Dra\.|H\.|Hj\.|Prof\.|Ir\.)\s+/i', '', $nama );
        $nama_clean = trim( explode( ',', $nama_clean )[0] );
        $nama_slug  = sanitize_title( $nama_clean );

        // Ambil semua lecturer posts
        $all = get_posts( array(
            'post_type'      => 'lecturer',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ) );

        // Exact title match
        foreach ( $all as $pid ) {
            if ( sanitize_title( get_the_title( $pid ) ) === $nama_slug ) {
                return $pid;
            }
        }

        // Fuzzy match with high threshold (only when strict=false)
        if ( ! $strict ) {
            $best_match = 0;
            $best_score = 0;
            foreach ( $all as $pid ) {
                $title = get_the_title( $pid );
                similar_text( strtolower( $nama_clean ), strtolower( $title ), $score );
                if ( $score > $best_score ) {
                    $best_score = $score;
                    $best_match = $pid;
                }
            }

            if ( $best_score > 80 ) {
                return $best_match;
            }
        }
    }

    return 0;
}

function educampus_sister_match_program( $unit_kerja, $jenjang = '' ) {
    if ( empty( $unit_kerja ) ) {
        return 0;
    }

    $programs = get_posts( array(
        'post_type'      => 'program',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ) );

    // Parse level (S1/S2/D3/D4) and clean name from SISTER unit_kerja
    $sister_level = '';
    $sister_name  = $unit_kerja;
    if ( preg_match( '/\(([sS]\d)\)/', $unit_kerja, $m ) ) {
        $sister_level = strtolower( $m[1] );
        $sister_name  = trim( preg_replace( '/\([sS]\d\)/', '', $unit_kerja ) );
    } elseif ( ! empty( $jenjang ) ) {
        // Use jenjang from penugasan as level (e.g., S1, S2)
        $j = strtolower( trim( $jenjang ) );
        if ( preg_match( '/^(s\d)/', $j, $m ) ) {
            $sister_level = $m[1];
        }
    }
    $sister_normalized = strtolower( trim( preg_replace( '/\s+/', ' ', $sister_name ) ) );
    $sister_clean      = preg_replace( '/[^a-z0-9\s]/', '', $sister_normalized );
    $sister_clean      = trim( preg_replace( '/\s+/', ' ', $sister_clean ) );

    foreach ( $programs as $pid ) {
        $title = get_the_title( $pid );

        // Parse level from program title prefix (S1, S2 etc.)
        $prog_level = '';
        $prog_name  = $title;
        if ( preg_match( '/^(S\d)\s+/', $title, $m ) ) {
            $prog_level = strtolower( $m[1] );
            $prog_name  = trim( substr( $title, strlen( $m[0] ) ) );
        }

        // Level must match when both sides have it
        if ( ! empty( $sister_level ) && ! empty( $prog_level ) && $sister_level !== $prog_level ) {
            continue;
        }

        $prog_normalized = strtolower( trim( preg_replace( '/\s+/', ' ', $prog_name ) ) );
        $prog_clean      = preg_replace( '/\([^)]*\)/', '', $prog_normalized );
        $prog_clean      = preg_replace( '/[^a-z0-9\s]/', '', $prog_clean );
        $prog_clean      = trim( preg_replace( '/\s+/', ' ', $prog_clean ) );

        // Exact match after cleanup
        if ( $sister_clean === $prog_clean ) {
            return $pid;
        }

        // Substring match
        if ( strpos( $prog_clean, $sister_clean ) !== false || strpos( $sister_clean, $prog_clean ) !== false ) {
            return $pid;
        }
    }

    // Fallback: cari similar_text dengan threshold 75%, hanya dalam level yang sama
    $best_match = null;
    $best_score = 0;
    foreach ( $programs as $pid ) {
        $title = get_the_title( $pid );

        $prog_level = '';
        $prog_name  = $title;
        if ( preg_match( '/^(S\d)\s+/', $title, $m ) ) {
            $prog_level = strtolower( $m[1] );
            $prog_name  = trim( substr( $title, strlen( $m[0] ) ) );
        }

        if ( ! empty( $sister_level ) && ! empty( $prog_level ) && $sister_level !== $prog_level ) {
            continue;
        }

        $prog_normalized = strtolower( trim( preg_replace( '/\s+/', ' ', $prog_name ) ) );
        $prog_clean      = preg_replace( '/\([^)]*\)/', '', $prog_normalized );
        $prog_clean      = preg_replace( '/[^a-z0-9\s]/', '', $prog_clean );
        $prog_clean      = trim( preg_replace( '/\s+/', ' ', $prog_clean ) );

        similar_text( $sister_clean, $prog_clean, $score );
        if ( $score > $best_score ) {
            $best_score = $score;
            $best_match = $pid;
        }
    }

    if ( $best_score > 75 ) {
        error_log( "[SISTER PRODI] Fuzzy-match '{$unit_kerja}' -> '{$best_match}' (score={$best_score}). Verifikasi manual dianjurkan." );
        return $best_match;
    }

    error_log( "[SISTER PRODI] Tidak ada kecocokan untuk '{$unit_kerja}'. Buat/disesuaikan prodi di admin." );
    return 0;
}

function educampus_sister_set_photo( $post_id, $image_data, $name ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Hapus thumbnail lama agar tidak double
    $old_thumb_id = get_post_thumbnail_id( $post_id );
    if ( $old_thumb_id ) {
        wp_delete_attachment( $old_thumb_id, true );
    }

    // Detect actual image type from bytes
    $is_jpeg = ord( $image_data[0] ) === 0xFF && ord( $image_data[1] ) === 0xD8;
    $is_png  = ord( $image_data[0] ) === 0x89 && ord( $image_data[1] ) === 0x50;

    $ext  = $is_png ? 'png' : 'jpg';
    $mime = $is_png ? 'image/png' : 'image/jpeg';

    $upload_dir = wp_upload_dir();
    $filename   = sanitize_file_name( 'sister-' . $post_id . '-' . sanitize_title( $name ) . '.' . $ext );
    $filepath   = $upload_dir['path'] . '/' . $filename;

    $saved = file_put_contents( $filepath, $image_data );
    if ( ! $saved ) {
        return false;
    }

    $attachment = array(
        'post_mime_type' => $mime,
        'post_title'     => sanitize_text_field( $name ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $attach_id = wp_insert_attachment( $attachment, $filepath, $post_id );

    if ( is_wp_error( $attach_id ) ) {
        return false;
    }

    $attach_data = wp_generate_attachment_metadata( $attach_id, $filepath );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    set_post_thumbnail( $post_id, $attach_id );

    return true;
}

function educampus_sister_ajax_publish_all() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $drafts = get_posts( array(
        'post_type'      => 'lecturer',
        'post_status'    => 'draft',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) );

    if ( empty( $drafts ) ) {
        wp_send_json_success( array( 'published' => 0, 'message' => 'Semua dosen sudah publish.' ) );
        wp_die();
    }

    $published = 0;
    $errors = array();
    foreach ( $drafts as $pid ) {
        $result = wp_update_post( array( 'ID' => $pid, 'post_status' => 'publish' ), true );
        if ( is_wp_error( $result ) ) {
            $errors[] = "Post {$pid}: " . $result->get_error_message();
        } else {
            $published++;
        }
    }

    if ( ! empty( $errors ) ) {
        error_log( '[SISTER PUBLISH ALL] ' . implode( '; ', $errors ) );
    }

    wp_send_json_success( array(
        'published' => $published,
        'errors'    => $errors,
        'message'   => $published . ' dosen berhasil dipublish.',
    ) );
    wp_die();
}

function educampus_sister_ajax_reset_mapping() {
    check_ajax_referer( 'educampus_sister_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $posts = get_posts( array(
        'post_type'      => 'lecturer',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_lecturer_sister_id',
                'compare' => 'EXISTS',
            ),
        ),
    ) );

    $cleared = 0;
    foreach ( $posts as $pid ) {
        delete_post_meta( $pid, '_lecturer_sister_id' );
        delete_post_meta( $pid, '_lecturer_nidn' );
        $cleared++;
    }

    error_log( "[SISTER RESET] Mapping di-reset untuk {$cleared} dosen." );
    wp_send_json_success( array(
        'cleared' => $cleared,
        'message' => "Mapping di-reset untuk {$cleared} dosen. Jalankan sinkronisasi untuk auto-match ulang dengan sistem yang lebih ketat.",
    ) );
    wp_die();
}
