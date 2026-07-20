<?php
defined('ABSPATH') || exit;

class WebPUpload_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_menu() {
        add_options_page(
            'WebP Upload',
            'WebP Upload',
            'manage_options',
            'webp-upload',
            [$this, 'render_page']
        );
    }

    public function register_settings() {
        register_setting('webpupload_settings', 'webpupload_quality', [
            'type'              => 'integer',
            'sanitize_callback' => function ($val) {
                return max(1, min(100, (int) $val));
            },
            'default'           => 80,
        ]);

        register_setting('webpupload_settings', 'webpupload_htaccess', [
            'type'              => 'integer',
            'sanitize_callback' => 'intval',
            'default'           => 1,
        ]);

        add_settings_section('webpupload_main', 'Pengaturan WebP', null, 'webp-upload-settings');

        add_settings_field('webpupload_quality', 'Kualitas Kompresi', [$this, 'field_quality'], 'webp-upload-settings', 'webpupload_main');
        add_settings_field('webpupload_htaccess', 'Penyajian via .htaccess', [$this, 'field_htaccess'], 'webp-upload-settings', 'webpupload_main');
    }

    public function field_quality() {
        $val = get_option('webpupload_quality', 80);
        echo '<div style="display:flex;align-items:center;gap:12px">';
        echo '<input type="range" name="webpupload_quality" min="1" max="100" value="' . esc_attr($val) . '" style="width:300px" id="webpupload-quality-range">';
        echo '<strong id="webpupload-quality-val">' . esc_html($val) . '%</strong>';
        echo '</div>';
        echo '<p class="description">Semakin rendah = semakin kecil ukuran file. 80% adalah nilai optimal (kualitas bagus, ukuran ringan).</p>';
    }

    public function field_htaccess() {
        $val = get_option('webpupload_htaccess', 1);
        echo '<label><input type="checkbox" name="webpupload_htaccess" value="1" ' . checked(1, $val, false) . '> ';
        echo 'Aktifkan .htaccess rewrite (sajikan WebP langsung ke browser tanpa modifikasi HTML)</label>';
        echo '<p class="description">Gunakan metode ini jika hosting mendukung mod_rewrite. Jika tidak, gunakan filter konten (selalu aktif).</p>';
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'settings_page_webp-upload') {
            return;
        }

        $style = '
            .webpupload-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                padding: 20px;
                margin: 20px 0;
            }
            .webpupload-card h2 {
                margin-top: 0;
                font-size: 16px;
            }
            .webpupload-progress {
                display: none;
                margin-top: 15px;
            }
            .webpupload-bar-bg {
                height: 20px;
                background: #f0f0f1;
                border-radius: 3px;
                overflow: hidden;
            }
            .webpupload-bar {
                height: 100%;
                background: #2271b1;
                width: 0%;
                border-radius: 3px;
                transition: width 0.3s ease;
            }
            .webpupload-bar.done {
                background: #00a32a;
            }
            .webpupload-status {
                margin-top: 8px;
                color: #646970;
                font-size: 13px;
            }
            .webpupload-result {
                margin-top: 10px;
                padding: 10px;
                background: #f0f6fc;
                border-left: 4px solid #2271b1;
                display: none;
            }
        ';
        wp_add_inline_style('wp-admin', $style);

        $nonce = wp_create_nonce('webpupload_bulk');
        $js = '
        jQuery(function($) {
            var range = $("#webpupload-quality-range");
            var display = $("#webpupload-quality-val");
            range.on("input", function() { display.text(this.value + "%"); });

            var running = false;
            $("#webpupload-bulk-btn").on("click", function(e) {
                e.preventDefault();
                if (running) return;
                running = true;

                var btn = $(this);
                var progress = $(".webpupload-progress");
                var bar = $(".webpupload-bar");
                var status = $(".webpupload-status");
                var result = $(".webpupload-result");

                btn.prop("disabled", true).text("Mengkonversi...");
                progress.show();
                result.hide();

                function batch(offset) {
                    $.post(ajaxurl, {
                        action: "webpupload_bulk_convert",
                        offset: offset,
                        nonce: "' . $nonce . '"
                    }, function(res) {
                        if (!res.success) {
                            status.text("Error: " + (res.data || "Gagal memproses"));
                            btn.prop("disabled", false).text("Mulai Konversi");
                            running = false;
                            return;
                        }

                        var d = res.data;
                        var pct = d.done ? 100 : Math.min(99, Math.round((d.offset / (d.offset + 50)) * 100));
                        bar.css("width", pct + "%");
                        status.text("Diproses " + d.offset + " gambar...");

                        if (!d.done) {
                            setTimeout(function() { batch(d.offset); }, 300);
                        } else {
                            bar.addClass("done");
                            status.text("Selesai! Semua gambar telah dikonversi.");
                            result.show().html("<strong>✓ Konversi selesai.</strong> Semua gambar JPEG/PNG/GIF di upload sudah memiliki versi WebP.");
                            btn.prop("disabled", false).text("Mulai Konversi");
                            running = false;
                        }
                    }).fail(function() {
                        status.text("Error koneksi. Coba lagi.");
                        btn.prop("disabled", false).text("Mulai Konversi");
                        running = false;
                    });
                }

                batch(0);
            });
        });
        ';
        wp_add_inline_script('jquery', $js);
    }

    public function render_page() {
        $htaccess_ok = false;
        $htaccess_path = ABSPATH . '.htaccess';
        if (file_exists($htaccess_path)) {
            $htaccess_content = file_get_contents($htaccess_path);
            $htaccess_ok = strpos($htaccess_content, '# BEGIN WebP Upload') !== false;
        }
        $gd_ok = extension_loaded('gd') && function_exists('imagewebp');

        ?>
        <div class="wrap">
            <h1>WebP Upload</h1>

            <?php if (!$gd_ok): ?>
                <div class="notice notice-error"><p>GD library tidak mendukung WebP. Hubungi admin hosting.</p></div>
            <?php endif; ?>

            <div class="webpupload-card">
                <h2>Pengaturan</h2>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('webpupload_settings');
                    do_settings_sections('webp-upload-settings');
                    submit_button('Simpan Pengaturan');
                    ?>
                </form>
            </div>

            <div class="webpupload-card">
                <h2>Konversi Massal</h2>
                <p>Konversi semua gambar JPEG/PNG/GIF yang sudah ada di Media Library ke format WebP.</p>
                <button id="webpupload-bulk-btn" class="button button-primary" <?php disabled(!$gd_ok); ?>>Mulai Konversi</button>

                <div class="webpupload-progress">
                    <div class="webpupload-bar-bg">
                        <div class="webpupload-bar"></div>
                    </div>
                    <div class="webpupload-status">Menunggu...</div>
                </div>
                <div class="webpupload-result"></div>
            </div>

            <div class="webpupload-card">
                <h2>Status Sistem</h2>
                <table class="widefat fixed" style="border:0">
                    <tr><td>GD Library</td><td><strong><?php echo $gd_ok ? '✓ Aktif' : '✗ Tidak aktif'; ?></strong></td></tr>
                    <tr><td>WebP Support</td><td><strong><?php echo function_exists('imagewebp') ? '✓ Didukung' : '✗ Tidak didukung'; ?></strong></td></tr>
                    <tr><td>.htaccess</td><td><strong><?php echo $htaccess_ok ? '✓ Rules aktif' : '✗ Rules belum ditambahkan'; ?></strong></td></tr>
                    <tr><td>Kualitas Default</td><td><strong><?php echo esc_html(get_option('webpupload_quality', 80)); ?>%</strong></td></tr>
                </table>
            </div>
        </div>
        <?php
    }
}
