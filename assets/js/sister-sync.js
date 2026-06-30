(function() {
    'use strict';

    var ajaxUrl = educampusSister.ajaxUrl;
    var nonce   = educampusSister.nonce;

    var btnTest  = document.getElementById('btn-test-connection');
    var btnSave  = document.getElementById('btn-save-config');
    var btnSync  = document.getElementById('btn-start-sync');
    var progressArea = document.getElementById('sister-progress-area');
    var progressBar  = document.getElementById('sister-progress-bar');
    var progressText = document.getElementById('sister-progress-text');
    var progressPct  = document.getElementById('sister-progress-pct');
    var progressDtl  = document.getElementById('sister-progress-detail');
    var resultArea   = document.getElementById('sister-result-area');
    var noticeEl     = document.getElementById('sister-notice');

    function getFormData() {
        return {
            api_url:     document.getElementById('sister_api_url').value.trim(),
            username:    document.getElementById('sister_username').value.trim(),
            id_pengguna: document.getElementById('sister_id_pengguna').value.trim(),
            password:    document.getElementById('sister_password').value,
        };
    }

    function getSyncOptions() {
        var mode = document.querySelector('input[name="sync_mode"]:checked');
        return {
            sync_mode:  mode ? mode.value : 'basic',
        };
    }

    function showNotice(type, message) {
        noticeEl.innerHTML = '<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>';
        var notices = noticeEl.querySelectorAll('.notice');
        for (var i = 0; i < notices.length; i++) {
            notices[i].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function clearNotice() {
        noticeEl.innerHTML = '';
    }

    function setButtonLoading(btn, loading) {
        if (loading) {
            btn._orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner" style="float:none;margin:0;visibility:visible;"></span> Processing...';
        } else {
            btn.disabled = false;
            if (btn._orig) btn.innerHTML = btn._orig;
        }
    }

    // ── Test Connection ──
    if (btnTest) {
        btnTest.addEventListener('click', function() {
            clearNotice();
            var data = getFormData();

            if (!data.api_url || !data.username) {
                showNotice('error', 'URL API dan Username harus diisi.');
                return;
            }
            if (!data.password) {
                showNotice('info', 'Password dikosongkan — menggunakan password tersimpan.');
            }

            setButtonLoading(btnTest, true);

            var formData = new FormData();
            formData.append('action', 'sister_test_connection');
            formData.append('nonce', nonce);
            formData.append('api_url', data.api_url);
            formData.append('username', data.username);
            formData.append('password', data.password);
            formData.append('id_pengguna', data.id_pengguna);

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(resp) {
                    if (resp.success) {
                        showNotice('success', resp.data.message || 'Koneksi berhasil!');
                        document.getElementById('sister-connection-status').textContent = 'Terkonfigurasi';
                        document.getElementById('sister-connection-status').style.color = '#00a32a';
                    } else {
                        showNotice('error', resp.data.message || 'Gagal terhubung ke SISTER.');
                    }
                })
                .catch(function(err) {
                    showNotice('error', 'Error: ' + err.message);
                })
                .finally(function() {
                    setButtonLoading(btnTest, false);
                });
        });
    }

    // ── Save Config ──
    if (btnSave) {
        document.getElementById('sister-config-form').addEventListener('submit', function(e) {
            e.preventDefault();
            clearNotice();

            var data = getFormData();
            if (!data.api_url || !data.username) {
                showNotice('error', 'URL API dan Username harus diisi.');
                return;
            }

            setButtonLoading(btnSave, true);

            var formData = new FormData();
            formData.append('action', 'sister_save_config');
            formData.append('nonce', nonce);
            formData.append('api_url', data.api_url);
            formData.append('username', data.username);
            formData.append('password', data.password);
            formData.append('id_pengguna', data.id_pengguna);

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(resp) {
                    if (resp.success) {
                        showNotice('success', resp.data.message || 'Konfigurasi tersimpan.');
                    } else {
                        showNotice('error', resp.data.message || 'Gagal menyimpan.');
                    }
                })
                .catch(function(err) {
                    showNotice('error', 'Error: ' + err.message);
                })
                .finally(function() {
                    setButtonLoading(btnSave, false);
                });
        });
    }

    // ── Sync Process ──
    if (btnSync) {
        btnSync.addEventListener('click', function() {
            clearNotice();
            resultArea.style.display = 'none';
            resultArea.innerHTML = '';
            btnSync.disabled = true;

            var opts = getSyncOptions();

            // Step 1: Start sync
            var formData = new FormData();
            formData.append('action', 'sister_sync_start');
            formData.append('nonce', nonce);
            formData.append('sync_mode', opts.sync_mode);

            setButtonLoading(btnSync, true);

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(resp) {
                    if (!resp.success) {
                        showNotice('error', resp.data.message || 'Gagal memulai sync.');
                        btnSync.disabled = false;
                        setButtonLoading(btnSync, false);
                        return;
                    }

                    var total = resp.data.total;
                    progressArea.style.display = 'block';
                    updateProgress(0, total, 0, 0);
                    processChunk(total);
                })
                .catch(function(err) {
                    showNotice('error', 'Error: ' + err.message);
                    btnSync.disabled = false;
                    setButtonLoading(btnSync, false);
                });
        });
    }

    function processChunk(total) {
        var formData = new FormData();
        formData.append('action', 'sister_sync_chunk');
        formData.append('nonce', nonce);

        fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                if (!resp.success) {
                    showNotice('error', resp.data.message || 'Gagal memproses chunk.');
                    btnSync.disabled = false;
                    setButtonLoading(btnSync, false);
                    return;
                }

                var d = resp.data;
                updateProgress(d.done, d.total, d.updated, d.skipped);

                if (d.status === 'completed' || d.done >= d.total) {
                    syncComplete(d);
                } else {
                    processChunk(total);
                }
            })
            .catch(function(err) {
                showNotice('error', 'Sync terputus: ' + err.message + '. Coba lagi.');
                btnSync.disabled = false;
                setButtonLoading(btnSync, false);
            });
    }

    function updateProgress(done, total, updated, skipped) {
        var pct = total > 0 ? Math.round((done / total) * 100) : 0;
        progressBar.style.width = pct + '%';
        progressPct.textContent = pct + '%';
        progressText.textContent = 'Memproses ' + done + '/' + total + ' dosen...';
        progressDtl.innerHTML = 'Diperbarui: <strong>' + updated + '</strong> | Dilewati: <strong>' + skipped + '</strong>';
    }

    function syncComplete(data) {
        progressBar.style.width = '100%';
        progressPct.textContent = '100%';
        progressText.textContent = 'Selesai!';

        var html = '<h3 style="margin-top:0;color:#00a32a;">Sinkronisasi Selesai</h3>' +
            '<table class="widefat striped" style="margin-top:8px;">' +
            '<tr><td>Total dosen diupdate</td><td><strong>' + data.total + '</strong></td></tr>' +
            '<tr><td>Berhasil diperbarui</td><td><strong>' + (data.updated || '0') + '</strong></td></tr>' +
            '<tr><td>Dilewati</td><td><strong>' + (data.skipped || '0') + '</strong></td></tr>' +
            '</table>';

        resultArea.innerHTML = html;
        resultArea.style.display = 'block';

        btnSync.disabled = false;
        setButtonLoading(btnSync, false);
    }
})();
