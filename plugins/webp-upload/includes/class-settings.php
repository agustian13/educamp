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
        echo '<p class="description">Semakin rendah = semakin kecil ukuran file. 80% adalah nilai optimal.</p>';
    }

    public function field_htaccess() {
        $val = get_option('webpupload_htaccess', 1);
        echo '<label><input type="checkbox" name="webpupload_htaccess" value="1" ' . checked(1, $val, false) . '> ';
        echo 'Aktifkan .htaccess rewrite</label>';
        echo '<p class="description">Sajikan WebP langsung ke browser tanpa modifikasi HTML.</p>';
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'settings_page_webp-upload') {
            return;
        }

        $nonce = wp_create_nonce('webpupload_bulk');
        $nonceGallery = wp_create_nonce('webpupload_gallery');

        $style = '
            .webpupload-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 8px;
                padding: 24px;
                margin: 20px 0;
            }
            .webpupload-card h2 {
                margin: 0 0 16px 0;
                font-size: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .webpupload-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }
            .webpupload-stat-card {
                background: linear-gradient(135deg, #f0f6fc 0%, #fff 100%);
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 20px;
                text-align: center;
            }
            .webpupload-stat-card.green {
                background: linear-gradient(135deg, #e6f9ee 0%, #fff 100%);
                border-color: #a3d9b1;
            }
            .webpupload-stat-card.blue {
                background: linear-gradient(135deg, #e8f0fe 0%, #fff 100%);
                border-color: #a8c7fa;
            }
            .webpupload-stat-card.gold {
                background: linear-gradient(135deg, #fef7e0 0%, #fff 100%);
                border-color: #f0d48a;
            }
            .webpupload-stat-icon {
                font-size: 28px;
                margin-bottom: 8px;
            }
            .webpupload-stat-number {
                font-size: 28px;
                font-weight: 700;
                color: #1d2327;
                line-height: 1.2;
            }
            .webpupload-stat-label {
                font-size: 13px;
                color: #646970;
                margin-top: 4px;
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
                padding: 12px;
                background: #f0f6fc;
                border-left: 4px solid #2271b1;
                border-radius: 4px;
                display: none;
            }
            .webpupload-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 12px;
                margin-top: 16px;
            }
            .webpupload-gallery-item {
                border: 1px solid #e0e0e0;
                border-radius: 6px;
                overflow: hidden;
                background: #f9f9f9;
                transition: transform 0.2s;
            }
            .webpupload-gallery-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            .webpupload-gallery-thumb {
                width: 100%;
                height: 120px;
                object-fit: cover;
                display: block;
                background: #e0e0e0;
            }
            .webpupload-gallery-info {
                padding: 8px 10px;
                font-size: 11px;
                color: #646970;
            }
            .webpupload-gallery-name {
                font-weight: 600;
                color: #1d2327;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
                margin-bottom: 4px;
            }
            .webpupload-gallery-sizes {
                display: flex;
                justify-content: space-between;
                gap: 4px;
            }
            .webpupload-gallery-original {
                color: #d63638;
                text-decoration: line-through;
            }
            .webpupload-gallery-webp {
                color: #00a32a;
                font-weight: 600;
            }
            .webpupload-gallery-saved {
                background: #00a32a;
                color: #fff;
                padding: 1px 6px;
                border-radius: 10px;
                font-size: 10px;
                font-weight: 600;
            }
            .webpupload-load-more {
                text-align: center;
                margin-top: 16px;
            }
        ';
        wp_add_inline_style('wp-admin', $style);

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
                            result.show().html("<strong>✓ Konversi selesai.</strong> " + d.stats.converted + " gambar dikonversi, menghemat " + d.stats.saved_human + ".");
                            btn.prop("disabled", false).text("Mulai Konversi");
                            running = false;
                            loadDashboard();
                            loadGallery();
                        }
                    }).fail(function() {
                        status.text("Error koneksi. Coba lagi.");
                        btn.prop("disabled", false).text("Mulai Konversi");
                        running = false;
                    });
                }

                batch(0);
            });

            function formatBytes(bytes) {
                if (bytes === 0) return "0 B";
                var k = 1024;
                var sizes = ["B", "KB", "MB", "GB"];
                var i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i];
            }

            function loadDashboard() {
                $.post(ajaxurl, {
                    action: "webpupload_stats",
                    nonce: "' . $nonce . '"
                }, function(res) {
                    if (res.success) {
                        var s = res.data;
                        $("#stat-total").text(s.total_images);
                        $("#stat-converted").text(s.converted_images);
                        $("#stat-original").text(formatBytes(s.total_original_size));
                        $("#stat-webp").text(formatBytes(s.total_webp_size));
                        $("#stat-saved").text(formatBytes(s.total_saved));
                        $("#stat-percent").text(s.saving_percent + "%");
                    }
                });
            }

            function loadGallery(page) {
                page = page || 1;
                $.post(ajaxurl, {
                    action: "webpupload_gallery",
                    nonce: "' . $nonceGallery . '",
                    page: page,
                    per_page: 20
                }, function(res) {
                    if (res.success) {
                        var d = res.data;
                        var gallery = $("#webpupload-gallery-list");
                        if (page === 1) gallery.empty();

                        $.each(d.items, function(i, item) {
                            var pct = item.original_size > 0 ? Math.round(((item.original_size - item.webp_size) / item.original_size) * 100) : 0;
                            gallery.append(
                                \'<div class="webpupload-gallery-item">\' +
                                \'<img class="webpupload-gallery-thumb" src="\' + item.thumb + \'" loading="lazy" alt="">\' +
                                \'<div class="webpupload-gallery-info">\' +
                                \'<span class="webpupload-gallery-name" title="\' + item.filename + \'">\' + item.filename + \'</span>\' +
                                \'<div class="webpupload-gallery-sizes">\' +
                                \'<span class="webpupload-gallery-original">\' + formatBytes(item.original_size) + \'</span>\' +
                                \'<span class="webpupload-gallery-webp">\' + formatBytes(item.webp_size) + \'</span>\' +
                                \'<span class="webpupload-gallery-saved">-\' + pct + \%</span>\' +
                                \'</div></div></div>\'
                            );
                        });

                        if (d.has_more) {
                            $("#webpupload-load-more").show().off("click").on("click", function() {
                                loadGallery(page + 1);
                            });
                        } else {
                            $("#webpupload-load-more").hide();
                        }
                    }
                });
            }

            loadDashboard();
            loadGallery();
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
        $stats = $this->get_stats();

        ?>
        <div class="wrap">
            <h1>WebP Upload Dashboard</h1>

            <?php if (!$gd_ok): ?>
                <div class="notice notice-error"><p>GD library tidak mendukung WebP. Hubungi admin hosting.</p></div>
            <?php endif; ?>

            <!-- Dashboard Stats -->
            <div class="webpupload-card">
                <h2>📊 Dashboard Penghematan</h2>
                <div class="webpupload-stats">
                    <div class="webpupload-stat-card blue">
                        <div class="webpupload-stat-icon">🖼️</div>
                        <div class="webpupload-stat-number" id="stat-total"><?php echo $stats['total_images']; ?></div>
                        <div class="webpupload-stat-label">Total Gambar</div>
                    </div>
                    <div class="webpupload-stat-card green">
                        <div class="webpupload-stat-icon">✅</div>
                        <div class="webpupload-stat-number" id="stat-converted"><?php echo $stats['converted_images']; ?></div>
                        <div class="webpupload-stat-label">Sudah Dikonversi</div>
                    </div>
                    <div class="webpupload-stat-card">
                        <div class="webpupload-stat-icon">📦</div>
                        <div class="webpupload-stat-number" id="stat-original"><?php echo $this->format_bytes($stats['total_original_size']); ?></div>
                        <div class="webpupload-stat-label">Ukuran Asli (JPEG/PNG/GIF)</div>
                    </div>
                    <div class="webpupload-stat-card green">
                        <div class="webpupload-stat-icon">⚡</div>
                        <div class="webpupload-stat-number" id="stat-webp"><?php echo $this->format_bytes($stats['total_webp_size']); ?></div>
                        <div class="webpupload-stat-label">Ukuran WebP</div>
                    </div>
                    <div class="webpupload-stat-card gold">
                        <div class="webpupload-stat-icon">💰</div>
                        <div class="webpupload-stat-number" id="stat-saved"><?php echo $this->format_bytes($stats['total_saved']); ?></div>
                        <div class="webpupload-stat-label">Total Terhemat</div>
                    </div>
                    <div class="webpupload-stat-card green">
                        <div class="webpupload-stat-icon">📉</div>
                        <div class="webpupload-stat-number" id="stat-percent"><?php echo $stats['saving_percent']; ?>%</div>
                        <div class="webpupload-stat-label">Persentase Penghematan</div>
                    </div>
                </div>
            </div>

            <!-- Gallery Gambar yang Sudah Dikonversi -->
            <div class="webpupload-card">
                <h2>🖼️ Gambar yang Sudah Dikonversi</h2>
                <p style="color:#646970;margin-top:0;">Perbandingan ukuran asli vs WebP untuk setiap gambar.</p>
                <div id="webpupload-gallery-list" class="webpupload-gallery"></div>
                <div id="webpupload-load-more" class="webpupload-load-more" style="display:none;">
                    <button class="button">Muat Lebih Banyak</button>
                </div>
            </div>

            <!-- Konversi Massal -->
            <div class="webpupload-card">
                <h2>🔄 Konversi Massal</h2>
                <p style="color:#646970;margin-top:0;">Konversi semua gambar JPEG/PNG/GIF yang belum memiliki versi WebP.</p>
                <button id="webpupload-bulk-btn" class="button button-primary" <?php disabled(!$gd_ok); ?>>Mulai Konversi</button>

                <div class="webpupload-progress">
                    <div class="webpupload-bar-bg">
                        <div class="webpupload-bar"></div>
                    </div>
                    <div class="webpupload-status">Menunggu...</div>
                </div>
                <div class="webpupload-result"></div>
            </div>

            <!-- Pengaturan -->
            <div class="webpupload-card">
                <h2>⚙️ Pengaturan</h2>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('webpupload_settings');
                    do_settings_sections('webp-upload-settings');
                    submit_button('Simpan Pengaturan');
                    ?>
                </form>
            </div>

            <!-- Status Sistem -->
            <div class="webpupload-card">
                <h2>🔧 Status Sistem</h2>
                <table class="widefat fixed" style="border:0">
                    <tr><td>GD Library</td><td><strong><?php echo $gd_ok ? '✅ Aktif' : '❌ Tidak aktif'; ?></strong></td></tr>
                    <tr><td>WebP Support</td><td><strong><?php echo function_exists('imagewebp') ? '✅ Didukung' : '❌ Tidak didukung'; ?></strong></td></tr>
                    <tr><td>.htaccess</td><td><strong><?php echo $htaccess_ok ? '✅ Rules aktif' : '⚠️ Rules belum ditambahkan'; ?></strong></td></tr>
                    <tr><td>Kualitas</td><td><strong><?php echo esc_html(get_option('webpupload_quality', 80)); ?>%</strong></td></tr>
                </table>
            </div>
        </div>
        <?php
    }

    private function format_bytes($bytes) {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 1) . ' ' . $units[$i];
    }

    private function get_stats() {
        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        $attachments = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]);

        $total_images = count($attachments);
        $converted = 0;
        $total_original = 0;
        $total_webp = 0;

        foreach ($attachments as $id) {
            $metadata = wp_get_attachment_metadata($id);
            if (empty($metadata) || empty($metadata['file'])) continue;

            $full_path = $base_dir . '/' . $metadata['file'];
            $webp_path = preg_replace('/\.(jpe?g|png|gif)$/i', '', $full_path) . '.webp';

            if (!file_exists($full_path)) continue;

            $original_size = filesize($full_path);
            $total_original += $original_size;

            if (file_exists($webp_path)) {
                $converted++;
                $total_webp += filesize($webp_path);
            } else {
                $total_webp += $original_size;
            }
        }

        $saved = $total_original - $total_webp;
        $percent = $total_original > 0 ? round(($saved / $total_original) * 100) : 0;

        return [
            'total_images'      => $total_images,
            'converted_images'  => $converted,
            'total_original_size' => $total_original,
            'total_webp_size'   => $total_webp,
            'total_saved'       => max(0, $saved),
            'saving_percent'    => max(0, $percent),
        ];
    }
}
