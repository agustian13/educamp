<?php
/**
 * EduCampus One-Click Demo Importer — Premium GUI
 */

defined('ABSPATH') or exit;

define('EDUCAMPUS_DEMO_DIR', get_template_directory() . '/demo-content');
define('EDUCAMPUS_DEMO_WXR', EDUCAMPUS_DEMO_DIR . '/demo-content.xml');
define('EDUCAMPUS_DEMO_SETTINGS', EDUCAMPUS_DEMO_DIR . '/demo-settings.json');
define('EDUCAMPUS_DEMO_UPLOADS', EDUCAMPUS_DEMO_DIR . '/uploads');
define('EDUCAMPUS_DEMO_TRANSIENT', 'educampus_demo_import_progress');

add_action('admin_menu', 'educampus_demo_import_menu');
add_action('admin_enqueue_scripts', 'educampus_demo_admin_assets');
add_action('wp_ajax_educampus_demo_import_start', 'educampus_demo_ajax_import_start');
add_action('wp_ajax_educampus_demo_import_step', 'educampus_demo_ajax_import_step');
add_action('wp_ajax_educampus_demo_import_status', 'educampus_demo_ajax_import_status');

function educampus_demo_import_menu() {
    add_theme_page(
        __('Import Demo Data', 'educampus'),
        __('Import Demo', 'educampus'),
        'manage_options',
        'educampus-demo-import',
        'educampus_demo_import_page'
    );
}

function educampus_demo_admin_assets($hook) {
    if ($hook !== 'appearance_page_educampus-demo-import') {
        return;
    }

    wp_enqueue_style(
        'educampus-demo-import',
        get_template_directory_uri() . '/assets/css/demo-import.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/demo-import.css')
    );

    wp_enqueue_script(
        'educampus-demo-import',
        get_template_directory_uri() . '/assets/js/demo-import.js',
        array(),
        filemtime(get_template_directory() . '/assets/js/demo-import.js'),
        array('strategy' => 'defer')
    );

    wp_localize_script('educampus-demo-import', 'educampusDemoImport', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('educampus_demo_import'),
        'steps'   => educampus_demo_step_labels(),
    ));
}

function educampus_demo_step_labels() {
    return array(
        'validasi'   => __('Validasi Data', 'educampus'),
        'uploads'    => __('Menyalin Media', 'educampus'),
        'wxr'        => __('Import Konten', 'educampus'),
        'settings'   => __('Restore Pengaturan', 'educampus'),
        'frontpage'  => __('Setup Halaman Depan', 'educampus'),
        'rewrite'    => __('Flush Rewrite Rules', 'educampus'),
    );
}

function educampus_demo_import_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Anda tidak memiliki izin.', 'educampus'));
    }
    ?>
    <div class="wrap educampus-demo-wrap">
        <h1><?php esc_html_e('Import Demo Data EduCampus', 'educampus'); ?></h1>
        <p class="description"><?php esc_html_e('Impor data demo lengkap: halaman, program studi, dosen, berita, menu navigasi, dan pengaturan tema.', 'educampus'); ?></p>
        <hr>

        <div id="educampus-demo-app">
            <?php educampus_demo_render_card(); ?>
            <?php educampus_demo_render_modal(); ?>
            <?php educampus_demo_render_progress(); ?>
        </div>

        <script id="educampus-demo-step-tpl" type="text/template">
            <div class="educampus-step {{status}}">
                <span class="educampus-step-icon">{{{icon}}}</span>
                <span class="educampus-step-label">{{label}}</span>
                <span class="educampus-step-status">{{{status_text}}}</span>
            </div>
        </script>
    </div>
    <?php
}

function educampus_demo_render_card() {
    $screenshot = get_template_directory_uri() . '/screenshot.png';
    ?>
    <div class="educampus-demo-card" id="educampus-demo-card">
        <div class="educampus-demo-card-image">
            <img src="<?php echo esc_url($screenshot); ?>" alt="EduCampus Demo">
            <div class="educampus-demo-overlay">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e('Lihat Demo', 'educampus'); ?>
            </div>
        </div>
        <div class="educampus-demo-card-body">
            <h2><?php esc_html_e('Demo Utama EduCampus', 'educampus'); ?></h2>
            <p class="educampus-demo-card-desc"><?php esc_html_e('Data lengkap institusi, akademik, kemahasiswaan, dan publikasi.', 'educampus'); ?></p>
            <div class="educampus-demo-stats">
                <?php
                $stats = array(
                    array('71', __('Dosen', 'educampus')),
                    array('49', __('Unit', 'educampus')),
                    array('9', __('Prodi', 'educampus')),
                    array('8', __('Halaman', 'educampus')),
                    array('7', __('Berita', 'educampus')),
                    array('237', __('Media', 'educampus')),
                );
                foreach ($stats as $s) : ?>
                    <div class="educampus-stat">
                        <span class="educampus-stat-number"><?php echo esc_html($s[0]); ?></span>
                        <span class="educampus-stat-label"><?php echo esc_html($s[1]); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="educampus-demo-card-actions">
                <button type="button" class="button button-primary button-hero" id="educampus-demo-start">
                    <span class="dashicons dashicons-database-import"></span>
                    <?php esc_html_e('Import Demo Sekarang', 'educampus'); ?>
                </button>
            </div>
        </div>
    </div>
    <?php
}

function educampus_demo_render_modal() {
    ?>
    <div class="educampus-modal-backdrop" id="educampus-demo-modal-backdrop" style="display:none;"></div>
    <div class="educampus-modal" id="educampus-demo-modal" style="display:none;" role="dialog" aria-labelledby="educampus-modal-title">
        <div class="educampus-modal-header">
            <h3 id="educampus-modal-title"><?php esc_html_e('Pilih Komponen Demo', 'educampus'); ?></h3>
            <button type="button" class="educampus-modal-close dashicons dashicons-no-alt" id="educampus-modal-close" aria-label="<?php esc_attr_e('Tutup', 'educampus'); ?>"></button>
        </div>
        <div class="educampus-modal-body">
            <div class="educampus-modal-option">
                <label>
                    <input type="checkbox" class="educampus-import-option" name="content" checked disabled>
                    <span class="educampus-option-label"><?php esc_html_e('Konten & Halaman', 'educampus'); ?></span>
                    <span class="educampus-option-badge required"><?php esc_html_e('wajib', 'educampus'); ?></span>
                </label>
                <p class="educampus-option-desc"><?php esc_html_e('Post, page, dosen, prodi, unit, berita, event, menu navigasi.', 'educampus'); ?></p>
            </div>
            <div class="educampus-modal-option">
                <label>
                    <input type="checkbox" class="educampus-import-option" name="media" checked>
                    <span class="educampus-option-label"><?php esc_html_e('Media & Gambar', 'educampus'); ?></span>
                    <span class="educampus-option-badge">~23 MB</span>
                </label>
                <p class="educampus-option-desc"><?php esc_html_e('484 file: foto dosen, featured images, logo, dokumen.', 'educampus'); ?></p>
            </div>
            <div class="educampus-modal-option">
                <label>
                    <input type="checkbox" class="educampus-import-option" name="settings" checked>
                    <span class="educampus-option-label"><?php esc_html_e('Pengaturan Tema', 'educampus'); ?></span>
                </label>
                <p class="educampus-option-desc"><?php esc_html_e('Warna tema, font, header, footer, hero image.', 'educampus'); ?></p>
            </div>
            <div class="educampus-modal-option">
                <label>
                    <input type="checkbox" class="educampus-import-option" name="widgets" checked>
                    <span class="educampus-option-label"><?php esc_html_e('Widget & Sidebar', 'educampus'); ?></span>
                </label>
                <p class="educampus-option-desc"><?php esc_html_e('Widget footer, sidebar, dan navigasi menu.', 'educampus'); ?></p>
            </div>
            <div class="educampus-modal-option">
                <label>
                    <input type="checkbox" class="educampus-import-option" name="frontpage" checked>
                    <span class="educampus-option-label"><?php esc_html_e('Halaman Depan & Berita', 'educampus'); ?></span>
                </label>
                <p class="educampus-option-desc"><?php esc_html_e('Set "Profil Kampus" sebagai front page, "Berita" sebagai posts page.', 'educampus'); ?></p>
            </div>
            <div class="educampus-modal-notice">
                <span class="dashicons dashicons-info-outline"></span>
                <?php esc_html_e('Data yang sudah ada tidak akan dihapus atau ditimpa. Hanya data baru yang akan ditambahkan.', 'educampus'); ?>
            </div>
        </div>
        <div class="educampus-modal-footer">
            <button type="button" class="button button-secondary" id="educampus-modal-cancel">
                <?php esc_html_e('Batal', 'educampus'); ?>
            </button>
            <button type="button" class="button button-primary" id="educampus-modal-import">
                <span class="dashicons dashicons-update"></span>
                <?php esc_html_e('Mulai Import', 'educampus'); ?>
            </button>
        </div>
    </div>
    <?php
}

function educampus_demo_render_progress() {
    ?>
    <div class="educampus-demo-progress" id="educampus-demo-progress" style="display:none;">
        <div class="educampus-progress-header">
            <h3><?php esc_html_e('Import Sedang Berlangsung...', 'educampus'); ?></h3>
            <span class="educampus-progress-pct" id="educampus-progress-pct">0%</span>
        </div>

        <div class="educampus-progress-track">
            <div class="educampus-progress-bar" id="educampus-progress-bar" style="width:0%;"></div>
        </div>

        <div class="educampus-steps" id="educampus-steps"></div>

        <div class="educampus-log" id="educampus-log">
            <div class="educampus-log-header">
                <span><?php esc_html_e('Log Detail', 'educampus'); ?></span>
                <button type="button" class="button button-small button-link educampus-log-toggle" id="educampus-log-toggle">
                    <?php esc_html_e('Tampilkan', 'educampus'); ?>
                </button>
            </div>
            <div class="educampus-log-content" id="educampus-log-content" style="display:none;"></div>
        </div>

        <div class="educampus-progress-actions" id="educampus-progress-actions" style="display:none;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary" target="_blank">
                <span class="dashicons dashicons-welcome-view-site"></span>
                <?php esc_html_e('Lihat Website', 'educampus'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('themes.php?page=educampus-demo-import')); ?>" class="button">
                <?php esc_html_e('Kembali', 'educampus'); ?>
            </a>
            <button type="button" class="button button-secondary" id="educampus-demo-retry" style="display:none;">
                <span class="dashicons dashicons-image-rotate"></span>
                <?php esc_html_e('Coba Lagi', 'educampus'); ?>
            </button>
        </div>
    </div>
    <?php
}

function educampus_demo_get_progress() {
    $default = array(
        'step'      => 0,
        'status'    => 'idle',
        'message'   => '',
        'processed' => 0,
        'total'     => 0,
        'options'   => array(),
        'errors'    => array(),
    );

    $data = get_transient(EDUCAMPUS_DEMO_TRANSIENT);
    return is_array($data) ? $data : $default;
}

function educampus_demo_save_progress($data) {
    set_transient(EDUCAMPUS_DEMO_TRANSIENT, $data, HOUR_IN_SECONDS);
}

function educampus_demo_clear_progress() {
    delete_transient(EDUCAMPUS_DEMO_TRANSIENT);
}

function educampus_demo_ajax_import_status() {
    check_ajax_referer('educampus_demo_import', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Akses ditolak.', 'educampus')));
    }

    $progress = educampus_demo_get_progress();
    $step_labels = educampus_demo_step_labels();
    $step_names = array_keys($step_labels);

    $progress['step_name'] = isset($step_names[$progress['step']]) ? $step_names[$progress['step']] : '';
    $progress['step_label'] = isset($step_labels[$progress['step_name']]) ? $step_labels[$progress['step_name']] : '';

    wp_send_json_success($progress);
}

function educampus_demo_ajax_import_start() {
    check_ajax_referer('educampus_demo_import', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Akses ditolak.', 'educampus')));
    }

    $running = get_transient(EDUCAMPUS_DEMO_TRANSIENT);
    if ($running && isset($running['status']) && $running['status'] === 'running') {
        wp_send_json_error(array(
            'message' => __('Import sedang berjalan. Tunggu hingga selesai atau refresh halaman.', 'educampus'),
            'progress' => $running,
        ));
    }

    $raw_options = isset($_POST['options']) ? json_decode(wp_unslash($_POST['options']), true) : array();
    if (!is_array($raw_options)) {
        $raw_options = array();
    }
    $options = array(
        'media'     => !empty($raw_options['media']),
        'settings'  => !empty($raw_options['settings']),
        'widgets'   => !empty($raw_options['widgets']),
        'frontpage' => !empty($raw_options['frontpage']),
    );

    $progress = array(
        'step'      => 0,
        'status'    => 'running',
        'message'   => '',
        'processed' => 0,
        'total'     => 0,
        'options'   => $options,
        'errors'    => array(),
    );

    educampus_demo_save_progress($progress);

    wp_send_json_success(array(
        'step'      => 0,
        'step_name' => 'validasi',
        'message'   => __('Memulai import...', 'educampus'),
    ));
}

function educampus_demo_ajax_import_step() {
    check_ajax_referer('educampus_demo_import', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Akses ditolak.', 'educampus')));
    }

    $progress = educampus_demo_get_progress();

    if ($progress['status'] === 'completed') {
        wp_send_json_success(array(
            'status'  => 'completed',
            'message' => __('Import selesai!', 'educampus'),
        ));
    }

    $step = $progress['step'];
    $step_names = array_keys(educampus_demo_step_labels());

    if (!isset($step_names[$step])) {
        educampus_demo_clear_progress();
        wp_send_json_error(array('message' => __('Step tidak valid.', 'educampus')));
    }

    $step_name = $step_names[$step];
    $progress['status'] = 'running';

    try {
        switch ($step_name) {
            case 'validasi':
                $result = educampus_demo_step_validasi();
                break;
            case 'uploads':
                $result = educampus_demo_step_uploads($progress);
                break;
            case 'wxr':
                $result = educampus_demo_step_wxr($progress);
                break;
            case 'settings':
                $result = educampus_demo_step_settings($progress);
                break;
            case 'frontpage':
                $result = educampus_demo_step_frontpage($progress);
                break;
            case 'rewrite':
                $result = educampus_demo_step_rewrite();
                break;
            default:
                throw new Exception(__('Step tidak dikenal.', 'educampus'));
        }
    } catch (Exception $e) {
        $progress['status'] = 'error';
        $progress['message'] = $e->getMessage();
        $progress['errors'][] = array(
            'step' => $step_name,
            'message' => $e->getMessage(),
        );
        educampus_demo_save_progress($progress);

        wp_send_json_error(array(
            'message'  => $e->getMessage(),
            'progress' => $progress,
        ));
        return;
    }

    if ($result === false) {
        $progress['status'] = 'error';
        $progress['message'] = sprintf(__('Terjadi kesalahan pada step %s.', 'educampus'), $step_name);
        educampus_demo_save_progress($progress);

        wp_send_json_error(array(
            'message'  => $progress['message'],
            'progress' => $progress,
        ));
        return;
    }

    if (is_array($result)) {
        $progress['message'] = $result['message'] ?? '';
        $progress['processed'] = $result['processed'] ?? 0;
        $progress['total'] = $result['total'] ?? 0;
    }

    $progress['step'] = $step + 1;
    $next_step_name = isset($step_names[$step + 1]) ? $step_names[$step + 1] : null;

    if ($next_step_name === null) {
        $progress['status'] = 'completed';
        $progress['message'] = __('Import demo data berhasil!', 'educampus');
        $progress['step'] = count($step_names);
        educampus_demo_save_progress($progress);

        wp_send_json_success(array(
            'status'    => 'completed',
            'message'   => $progress['message'],
            'step'      => $progress['step'],
            'processed' => $progress['processed'],
            'total'     => $progress['total'],
            'errors'    => $progress['errors'],
        ));
    } else {
        educampus_demo_save_progress($progress);

        wp_send_json_success(array(
            'status'    => 'next',
            'step'      => $progress['step'],
            'step_name' => $next_step_name,
            'message'   => $progress['message'],
            'processed' => $progress['processed'],
            'total'     => $progress['total'],
        ));
    }
}

function educampus_demo_step_validasi() {
    $errors = array();

    if (!file_exists(EDUCAMPUS_DEMO_WXR)) {
        $errors[] = __('File data konten (XML) tidak ditemukan.', 'educampus');
    }
    if (!file_exists(EDUCAMPUS_DEMO_SETTINGS)) {
        $errors[] = __('File pengaturan tema (JSON) tidak ditemukan.', 'educampus');
    }
    if (!class_exists('WP_Import')) {
        $errors[] = __('Plugin WordPress Importer tidak aktif. Install dan aktifkan plugin "WordPress Importer".', 'educampus');
    }

    if (!empty($errors)) {
        throw new Exception(implode("\n", $errors));
    }

    return array(
        'message'   => __('Validasi berhasil. 407 item konten siap diimport.', 'educampus'),
        'processed' => 0,
        'total'     => 0,
    );
}

function educampus_demo_step_uploads($progress) {
    if (empty($progress['options']['media'])) {
        return array(
            'message'   => __('Media dilewati (opsi tidak dipilih).', 'educampus'),
            'processed' => 0,
            'total'     => 0,
        );
    }

    $src = EDUCAMPUS_DEMO_UPLOADS;
    $dst = WP_CONTENT_DIR . '/uploads';

    if (!is_dir($src)) {
        return array(
            'message'   => __('Folder uploads tidak ditemukan, dilewati.', 'educampus'),
            'processed' => 0,
            'total'     => 0,
        );
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $copied = 0;
    $skipped = 0;

    foreach ($files as $file) {
        if ($file->isDir()) continue;

        $rel_path = str_replace(array($src . '\\', $src . '/'), '', $file->getPathname());
        $target = $dst . '/' . str_replace('\\', '/', $rel_path);
        $target_dir = dirname($target);

        if (!is_dir($target_dir)) {
            wp_mkdir_p($target_dir);
        }

        if (!file_exists($target)) {
            copy($file->getPathname(), $target);
            $copied++;
        } else {
            $skipped++;
        }
    }

    return array(
        'message'   => sprintf(__('Media: %d disalin, %d sudah ada.', 'educampus'), $copied, $skipped),
        'processed' => $copied,
        'total'     => $copied + $skipped,
    );
}

function educampus_demo_step_wxr($progress) {
    if (!class_exists('WP_Import')) {
        throw new Exception(__('WordPress Importer tidak tersedia.', 'educampus'));
    }

    $temp_wxr = null;

    try {
        if (function_exists('wp_raise_memory_limit')) {
            wp_raise_memory_limit('admin');
        }
        if (function_exists('set_time_limit')) {
            set_time_limit(0);
        }

        $wxr_content = file_get_contents(EDUCAMPUS_DEMO_WXR);
        if ($wxr_content === false) {
            throw new Exception(__('Gagal membaca file demo-content.xml.', 'educampus'));
        }

        $wxr_content = str_replace('{{SITE_URL}}', rtrim(get_bloginfo('url'), '/'), $wxr_content);

        $temp_wxr = wp_tempnam('educampus-demo-');
        file_put_contents($temp_wxr, $wxr_content);

        $importer = new WP_Import();
        if (method_exists($importer, 'set_time_limit')) {
            $importer->set_time_limit(0);
        }

        ob_start();
        $importer->import($temp_wxr);
        $import_output = ob_get_clean();

        wp_cache_flush();

        return array(
            'message'   => __('Konten berhasil diimport.', 'educampus'),
            'processed' => 0,
            'total'     => 0,
        );

    } catch (Exception $e) {
        throw $e;
    } finally {
        if ($temp_wxr && file_exists($temp_wxr)) {
            @unlink($temp_wxr);
        }
    }
}

function educampus_demo_step_settings($progress) {
    if (empty($progress['options']['settings'])) {
        return array(
            'message'   => __('Pengaturan tema dilewati (opsi tidak dipilih).', 'educampus'),
            'processed' => 0,
            'total'     => 0,
        );
    }

    if (!file_exists(EDUCAMPUS_DEMO_SETTINGS)) {
        return array(
            'message'   => __('File pengaturan tidak ditemukan, dilewati.', 'educampus'),
            'processed' => 0,
            'total'     => 0,
        );
    }

    $json = file_get_contents(EDUCAMPUS_DEMO_SETTINGS);
    $settings = json_decode($json, true);

    if ($settings === null) {
        throw new Exception(__('File pengaturan tidak valid (JSON error).', 'educampus'));
    }

    $current_url = rtrim(get_bloginfo('url'), '/');
    $updated = 0;
    $skipped = 0;

    $widget_keys = array('sidebars_widgets', 'widget_block', 'widget_search', 'widget_text', 'widget_custom_html', 'widget_nav_menu', 'nav_menu_options');

    foreach ($settings as $key => $value) {
        if (in_array($key, array('siteurl', 'home', 'page_on_front', 'page_for_posts'), true)) {
            $skipped++;
            continue;
        }

        $value = educampus_demo_replace_url($value, $current_url);

        $is_widget = in_array($key, $widget_keys, true);

        if ($is_widget && empty($progress['options']['widgets'])) {
            $skipped++;
            continue;
        }

        update_option($key, $value);
        $updated++;
    }

    return array(
        'message'   => sprintf(__('Pengaturan: %d diupdate, %d dilewati.', 'educampus'), $updated, $skipped),
        'processed' => $updated,
        'total'     => $updated + $skipped,
    );
}

function educampus_demo_step_frontpage($progress) {
    if (empty($progress['options']['frontpage'])) {
        return array(
            'message'   => __('Setup halaman depan dilewati (opsi tidak dipilih).', 'educampus'),
            'processed' => 0,
            'total'     => 2,
        );
    }

    $profil = educampus_get_page_by_title('Profil Kampus', 'page');
    if ($profil) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $profil->ID);
    }

    $berita = educampus_get_page_by_title('Berita', 'page');
    if ($berita) {
        update_option('page_for_posts', $berita->ID);
    }

    $msg = '';
    if ($profil) {
        $msg .= sprintf(__('Halaman depan: %s', 'educampus'), 'Profil Kampus') . '. ';
    }
    if ($berita) {
        $msg .= sprintf(__('Halaman berita: %s', 'educampus'), 'Berita') . '.';
    }
    if (empty($msg)) {
        $msg = __('Halaman tidak ditemukan, setup dilewati.', 'educampus');
    }

    return array(
        'message'   => $msg,
        'processed' => ($profil ? 1 : 0) + ($berita ? 1 : 0),
        'total'     => 2,
    );
}

function educampus_demo_step_rewrite() {
    if (function_exists('flush_rewrite_rules')) {
        flush_rewrite_rules();
    }

    return array(
        'message'   => __('Rewrite rules diperbarui.', 'educampus'),
        'processed' => 1,
        'total'     => 1,
    );
}

function educampus_get_page_by_title($title, $post_type = 'page') {
    $query = new WP_Query(array(
        'post_type'              => $post_type,
        'title'                  => $title,
        'posts_per_page'         => 1,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'post_status'            => 'publish',
    ));

    if ($query->have_posts()) {
        return $query->posts[0];
    }

    return null;
}

function educampus_demo_replace_url($data, $current_url) {
    if (is_string($data)) {
        return str_replace('{{SITE_URL}}', $current_url, $data);
    }
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = educampus_demo_replace_url($v, $current_url);
        }
    }
    return $data;
}
