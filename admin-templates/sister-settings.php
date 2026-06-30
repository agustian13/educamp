<div class="wrap">
    <h1><?php esc_html_e( 'Integrasi SISTER', 'educampus' ); ?></h1>
    <p class="description"><?php esc_html_e( 'Sinkronisasi data dosen dari SISTER (Sistem Informasi Sumber Daya Terintegrasi) Kemdiktisaintek.', 'educampus' ); ?></p>

    <hr class="wp-header-end">

    <?php if ( ! extension_loaded( 'openssl' ) ) : ?>
        <div class="notice notice-error"><p><?php esc_html_e( 'OpenSSL extension tidak tersedia. Password tidak akan terenkripsi dengan aman.', 'educampus' ); ?></p></div>
    <?php endif; ?>

    <div id="sister-notice"></div>

    <div class="sister-status-bar" style="background:#f0f6fc;padding:12px 16px;border-left:4px solid #2271b1;margin:16px 0;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <strong><?php esc_html_e( 'Status:', 'educampus' ); ?></strong>
        <span id="sister-connection-status" style="color:<?php echo $is_configured ? '#00a32a' : '#cc0000'; ?>">
            <?php echo $is_configured ? esc_html__( 'Terkonfigurasi', 'educampus' ) : esc_html__( 'Belum dikonfigurasi', 'educampus' ); ?>
        </span>
        <?php if ( $last_sync ) : ?>
            <span class="separator">|</span>
            <span><?php esc_html_e( 'Terakhir sync:', 'educampus' ); ?> <strong><?php echo esc_html( $last_sync ); ?></strong></span>
        <?php endif; ?>
        <span class="separator">|</span>
        <span><?php esc_html_e( 'Dosen terdaftar:', 'educampus' ); ?> <strong><?php echo (int) $total_dosen; ?></strong> publish, <strong><?php echo (int) $total_draft; ?></strong> draft</span>
    </div>

    <div class="sister-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:12px;">

        <!-- KOLOM KIRI: Konfigurasi -->
        <div class="card" style="background:#fff;border:1px solid #c3c4c7;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <h2><?php esc_html_e( 'Konfigurasi API', 'educampus' ); ?></h2>
            <form id="sister-config-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="sister_api_url">URL API</label></th>
                        <td>
                            <input type="url" name="api_url" id="sister_api_url" class="regular-text" style="width:100%"
                                value="<?php echo esc_attr( $config['api_url'] ?? 'https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0' ); ?>"
                                placeholder="https://sister-api.kemdiktisaintek.go.id/ws.php/1.0" required>
                            <p class="description">Sandbox: <code>ws-sandbox.php</code> | Production: <code>ws.php</code></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sister_username">Username</label></th>
                        <td><input type="text" name="username" id="sister_username" class="regular-text" style="width:100%"
                            value="<?php echo esc_attr( $config['username'] ?? '' ); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sister_id_pengguna">ID Pengguna</label></th>
                        <td><input type="text" name="id_pengguna" id="sister_id_pengguna" class="regular-text" style="width:100%"
                            value="<?php echo esc_attr( $config['id_pengguna'] ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sister_password">Password</label></th>
                        <td>
                            <input type="password" name="password" id="sister_password" class="regular-text" style="width:100%"
                                placeholder="<?php echo ! empty( $config['password'] ) ? esc_attr__( '(Password tersimpan, kosongkan jika tidak ingin mengubah)', 'educampus' ) : ''; ?>">
                        </td>
                    </tr>
                </table>

                <div style="display:flex;gap:8px;margin-top:12px;">
                    <button type="button" id="btn-test-connection" class="button button-secondary">
                        <?php esc_html_e( 'Test Connection', 'educampus' ); ?>
                    </button>
                    <button type="submit" id="btn-save-config" class="button button-primary" <?php echo ! $is_configured ? '' : ''; ?>>
                        <?php esc_html_e( 'Simpan Konfigurasi', 'educampus' ); ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: Sinkronisasi -->
        <div class="card" style="background:#fff;border:1px solid #c3c4c7;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <h2><?php esc_html_e( 'Sinkronisasi Data', 'educampus' ); ?></h2>

            <?php if ( ! $is_configured ) : ?>
                <div class="notice notice-warning inline"><p><?php esc_html_e( 'Konfigurasikan API SISTER terlebih dahulu sebelum melakukan sinkronisasi.', 'educampus' ); ?></p></div>
            <?php else : ?>

            <div class="sister-sync-options" style="margin-bottom:16px;">
                <p><strong><?php esc_html_e( 'Mode Sinkronisasi:', 'educampus' ); ?></strong></p>
                <label style="display:block;margin:6px 0;">
                    <input type="radio" name="sync_mode" value="basic" checked> 
                    <strong><?php esc_html_e( 'Cepat', 'educampus' ); ?></strong> 
                    — <?php esc_html_e( 'Update data dasar + foto (profil, NIDN, jabatan, prodi, pendidikan, bidang ilmu)', 'educampus' ); ?>
                </label>
                <label style="display:block;margin:6px 0;">
                    <input type="radio" name="sync_mode" value="lengkap"> 
                    <strong><?php esc_html_e( 'Lengkap', 'educampus' ); ?></strong> 
                    — <?php esc_html_e( 'Semua data + foto + penelitian + publikasi + pengabdian', 'educampus' ); ?>
                </label>
            </div>

            <div class="sister-sync-options" style="margin-bottom:16px;">
                <p><strong><?php esc_html_e( 'Sinkronisasi berdasarkan dosen yang sudah terdaftar:', 'educampus' ); ?></strong>
                <br><span class="description"><?php esc_html_e( 'Data diambil per dosen dari SISTER menggunakan ID yang tersimpan, bukan menarik seluruh database SISTER.', 'educampus' ); ?></span></p>
            </div>

            <button type="button" id="btn-start-sync" class="button button-primary button-hero" style="width:100%;">
                <span class="dashicons dashicons-update" style="vertical-align:middle;"></span> 
                <?php esc_html_e( 'Mulai Sinkronisasi SISTER', 'educampus' ); ?>
            </button>

            <!-- Progress Bar -->
            <div id="sister-progress-area" style="display:none;margin-top:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span id="sister-progress-text"><?php esc_html_e( 'Memproses...', 'educampus' ); ?></span>
                    <span id="sister-progress-pct">0%</span>
                </div>
                <div style="background:#e0e0e0;border-radius:4px;height:24px;overflow:hidden;">
                    <div id="sister-progress-bar" style="background:#2271b1;width:0%;height:100%;border-radius:4px;transition:width .3s ease;"></div>
                </div>
                <div id="sister-progress-detail" style="margin-top:8px;font-size:12px;color:#666;"></div>
            </div>

            <!-- Sync Result -->
            <div id="sister-result-area" style="display:none;margin-top:16px;padding:16px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px;"></div>

            <?php endif; ?>
        </div>
    </div>

    <!-- Debug Panel -->
    <div style="margin-top:20px;padding:16px;background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 3px rgba(0,0,0,.04);">
        <h2><?php esc_html_e( 'Debug API SISTER', 'educampus' ); ?></h2>
        <p class="description">Cek respons mentah dari endpoint SISTER untuk debugging.</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
            <button type="button" id="btn-debug-api" class="button button-secondary">Cek Data SDM</button>
            <button type="button" id="btn-debug-foto" class="button button-secondary">Cek Foto</button>
            <button type="button" id="btn-debug-pendidikan" class="button button-secondary">Cek Pendidikan</button>
            <button type="button" id="btn-debug-penelitian" class="button button-secondary">Cek Penelitian</button>
            <button type="button" id="btn-debug-publikasi" class="button button-secondary">Cek Publikasi</button>
            <button type="button" id="btn-debug-prodi" class="button button-secondary">Cek Prodi</button>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
            <button type="button" id="btn-publish-all" class="button button-primary">Publish Semua Draft Dosen</button>
            <button type="button" id="btn-reset-mapping" class="button button-secondary" style="color:#b32d2e;border-color:#b32d2e;">Reset Mapping SISTER</button>
        </div>
        <div id="sister-debug-result" style="margin-top:12px;font-family:monospace;font-size:12px;background:#f0f0f0;padding:12px;border-radius:4px;white-space:pre-wrap;max-height:400px;overflow:auto;display:none;"></div>
    </div>

    <style>
        .sister-grid .card h2 { margin-top: 0; }
        #sister-notice .notice { margin: 8px 0; }
        #btn-start-sync:disabled { opacity: .5; cursor: not-allowed; }
    </style>

    <script>
    (function() {
        var debugResult = document.getElementById('sister-debug-result');

        function debugFetch(action, btn) {
            btn.disabled = true;
            var origText = btn.textContent;
            btn.textContent = 'Loading...';
            debugResult.style.display = 'block';
            debugResult.textContent = 'Fetching...';
            var formData = new FormData();
            formData.append('action', action);
            formData.append('nonce', '<?php echo wp_create_nonce( 'educampus_sister_nonce' ); ?>');
            fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(resp) {
                    debugResult.textContent = JSON.stringify(resp, null, 2);
                })
                .catch(function(err) {
                    debugResult.textContent = 'Error: ' + err.message;
                })
                .finally(function() {
                    btn.disabled = false;
                    btn.textContent = origText;
                });
        }

        var btnDebug = document.getElementById('btn-debug-api');
        if (btnDebug) {
            btnDebug.addEventListener('click', function() { debugFetch('sister_debug_list', btnDebug); });
        }

        var btnFoto = document.getElementById('btn-debug-foto');
        if (btnFoto) {
            btnFoto.addEventListener('click', function() { debugFetch('sister_debug_foto', btnFoto); });
        }
        var btnPendidikan = document.getElementById('btn-debug-pendidikan');
        if (btnPendidikan) {
            btnPendidikan.addEventListener('click', function() { debugFetch('sister_debug_pendidikan', btnPendidikan); });
        }
        var btnPenelitian = document.getElementById('btn-debug-penelitian');
        if (btnPenelitian) {
            btnPenelitian.addEventListener('click', function() { debugFetch('sister_debug_penelitian', btnPenelitian); });
        }
        var btnPublikasi = document.getElementById('btn-debug-publikasi');
        if (btnPublikasi) {
            btnPublikasi.addEventListener('click', function() { debugFetch('sister_debug_publikasi', btnPublikasi); });
        }
        var btnProdi = document.getElementById('btn-debug-prodi');
        if (btnProdi) {
            btnProdi.addEventListener('click', function() { debugFetch('sister_debug_prodi', btnProdi); });
        }
        var btnPublishAll = document.getElementById('btn-publish-all');
        if (btnPublishAll) {
            btnPublishAll.addEventListener('click', function() {
                var btn = this;
                btn.disabled = true;
                btn.textContent = 'Memproses...';
                var data = new FormData();
                data.append('action', 'sister_publish_all');
                data.append('nonce', '<?php echo wp_create_nonce( 'educampus_sister_nonce' ); ?>');
                fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', { method: 'POST', body: data })
                    .then(function(r) { return r.json(); })
                    .then(function(resp) {
                        if (resp.success) {
                            btn.textContent = 'Selesai! ' + (resp.data.published || 0) + ' dipublish';
                        } else {
                            btn.textContent = 'Gagal: ' + (resp.data && resp.data.message ? resp.data.message : 'unknown');
                        }
                        setTimeout(function() { btn.disabled = false; btn.textContent = 'Publish Semua Draft Dosen'; }, 3000);
                    })
                    .catch(function(err) {
                        btn.textContent = 'Gagal: ' + err.message;
                        setTimeout(function() { btn.disabled = false; btn.textContent = 'Publish Semua Draft Dosen'; }, 3000);
                    });
            });
        }

        var btnReset = document.getElementById('btn-reset-mapping');
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                if (!confirm('HAPUS semua mapping SISTER dari dosen? Semua ID SISTER dan NIDN akan dihapus, lalu jalankan sinkronisasi ulang untuk auto-match yang lebih akurat.')) return;
                var btn = this;
                btn.disabled = true;
                btn.textContent = 'Mereset...';
                var data = new FormData();
                data.append('action', 'sister_reset_mapping');
                data.append('nonce', '<?php echo wp_create_nonce( 'educampus_sister_nonce' ); ?>');
                fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', { method: 'POST', body: data })
                    .then(function(r) { return r.json(); })
                    .then(function(resp) {
                        if (resp.success) {
                            btn.textContent = 'OK! ' + (resp.data.cleared || 0) + ' direset';
                        } else {
                            btn.textContent = 'Gagal: ' + (resp.data && resp.data.message ? resp.data.message : 'unknown');
                        }
                        setTimeout(function() { btn.disabled = false; btn.textContent = 'Reset Mapping SISTER'; }, 3000);
                    })
                    .catch(function(err) {
                        btn.textContent = 'Gagal: ' + err.message;
                        setTimeout(function() { btn.disabled = false; btn.textContent = 'Reset Mapping SISTER'; }, 3000);
                    });
            });
        }
    })();
    </script>
</div>
