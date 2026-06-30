/**
 * Kalender Akademik Admin JS
 *
 * @package EduCampus
 */
(function () {
    'use strict';

    var config = window.educampusKalender || {};
    var ajaxUrl = config.ajaxUrl || '';
    var nonce = config.nonce || '';

    // DOM refs
    var notice = document.getElementById('kalender-notice');
    var tbody = document.getElementById('kalender-events-tbody');
    var modal = document.getElementById('kalender-modal');
    var eventForm = document.getElementById('kalender-event-form');
    var settingsForm = document.getElementById('kalender-settings-form');
    var btnSaveAll = document.getElementById('btn-save-all');
    var filterType = document.getElementById('filter-type');

    // ── Helpers ──────────────────────────────────────────────

    function showNotice(message, type) {
        type = type || 'success';
        notice.innerHTML =
            '<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>';
        setTimeout(function () {
            var n = notice.querySelector('.notice');
            if (n) n.remove();
        }, 5000);
    }

    function ajax(action, data, onSuccess) {
        data.action = action;
        data.nonce = nonce;
        var fd = new FormData();
        Object.keys(data).forEach(function (k) {
            fd.append(k, data[k]);
        });
        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (resp) {
                if (resp.success) {
                    showNotice(resp.data.message || 'Berhasil.', 'success');
                    if (onSuccess) onSuccess(resp.data);
                } else {
                    showNotice(resp.data.message || 'Gagal.', 'error');
                }
            })
            .catch(function (err) {
                showNotice('Error: ' + err.message, 'error');
            });
    }

    function formatID(dateStr) {
        if (!dateStr) return '';
        var parts = dateStr.split('-');
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    // ── Modal ────────────────────────────────────────────────

    function openModal(title, data) {
        document.getElementById('kalender-modal-title').textContent = title;
        document.getElementById('event_id').value = data.id || '';
        document.getElementById('event_title').value = data.title || '';
        document.getElementById('event_start').value = data.start || '';
        document.getElementById('event_end').value = data.end || '';
        document.getElementById('event_type').value = data.type || 'other';
        document.getElementById('event_color').value = data.color || '#2271b1';
        document.getElementById('event_description').value = data.description || '';

        // Sync color from type if new event
        if (!data.id) {
            var sel = document.getElementById('event_type');
            var opt = sel.options[sel.selectedIndex];
            if (opt && opt.dataset.color) {
                document.getElementById('event_color').value = opt.dataset.color;
            }
        }

        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
        eventForm.reset();
        document.getElementById('event_id').value = '';
    }

    // ── Event Form Submit ────────────────────────────────────

    eventForm.addEventListener('submit', function (e) {
        e.preventDefault();

        var id = document.getElementById('event_id').value;
        var title = document.getElementById('event_title').value.trim();
        var start = document.getElementById('event_start').value;
        var end = document.getElementById('event_end').value;
        var type = document.getElementById('event_type').value;
        var color = document.getElementById('event_color').value;
        var description = document.getElementById('event_description').value.trim();

        if (!title || !start || !end) {
            showNotice('Judul, tanggal mulai, dan tanggal akhir wajib diisi.', 'error');
            return;
        }

        if (end < start) {
            showNotice('Tanggal akhir tidak boleh sebelum tanggal mulai.', 'error');
            return;
        }

        // Build event object
        var evt = {
            id: id || Date.now().toString(36) + Math.random().toString(36).substr(2, 5),
            title: title,
            start: start,
            end: end,
            type: type,
            color: color,
            description: description,
        };

        // Collect all current events from the table
        var events = collectEventsFromTable();

        // Check if editing or adding
        if (id) {
            // Update existing
            events = events.map(function (e) {
                return e.id === id ? evt : e;
            });
        } else {
            // Add new
            events.push(evt);
        }

        // Save via AJAX
        ajax('kalender_save_events', { events: JSON.stringify(events) }, function (data) {
            refreshTable(data.events);
            closeModal();
        });
    });

    // ── Collect events from visible table rows ───────────────

    function collectEventsFromTable() {
        var rows = tbody.querySelectorAll('tr[data-id]');
        var events = [];
        var typeLabels = {
            kuliah: { color: '#2271b1' },
            uts: { color: '#fd7e14' },
            uas: { color: '#dc3545' },
            libur: { color: '#6f42c1' },
            wisuda: { color: '#c5a059' },
            pendaftaran: { color: '#198754' },
            orientasi: { color: '#0d6efd' },
            seminar: { color: '#20c997' },
            rab: { color: '#6610f2' },
            other: { color: '#6c757d' },
        };

        rows.forEach(function (row) {
            var id = row.dataset.id;
            var title = row.querySelector('.column-title strong');
            var typeBadge = row.querySelector('.kalender-type-badge');
            var startCell = row.querySelector('.column-start');
            var endCell = row.querySelector('.column-end');
            var descCell = row.querySelector('.column-desc-cell');
            var colorDot = row.querySelector('.kalender-color-dot');

            if (!title) return;

            // Determine type from badge text
            var typeKey = 'other';
            var badgeText = typeBadge ? typeBadge.textContent.trim() : '';
            Object.keys(typeLabels).forEach(function (k) {
                var labels = {
                    kuliah: 'Kuliah', uts: 'UTS', uas: 'UAS', libur: 'Libur',
                    wisuda: 'Wisuda', pendaftaran: 'Pendaftaran', orientasi: 'Orientasi',
                    seminar: 'Seminar', rab: 'RAPAT', other: 'Lainnya',
                };
                if (labels[k] === badgeText) typeKey = k;
            });

            events.push({
                id: id,
                title: title.textContent.trim(),
                start: startCell ? startCell.textContent.trim() : '',
                end: endCell ? endCell.textContent.trim() : '',
                type: typeKey,
                color: colorDot ? colorDot.style.background || colorDot.style.backgroundColor : '',
                description: descCell ? descCell.textContent.trim() : '',
            });
        });

        return events;
    }

    // ── Refresh table HTML ───────────────────────────────────

    function refreshTable(events) {
        var typeLabels = {
            kuliah: 'Kuliah', uts: 'UTS', uas: 'UAS', libur: 'Libur',
            wisuda: 'Wisuda', pendaftaran: 'Pendaftaran', orientasi: 'Orientasi',
            seminar: 'Seminar', rab: 'RAPAT', other: 'Lainnya',
        };
        var typeColors = {
            kuliah: '#2271b1', uts: '#fd7e14', uas: '#dc3545', libur: '#6f42c1',
            wisuda: '#c5a059', pendaftaran: '#198754', orientasi: '#0d6efd',
            seminar: '#20c997', rab: '#6610f2', other: '#6c757d',
        };

        document.getElementById('kalender-total-events').textContent = events.length;

        if (!events || events.length === 0) {
            tbody.innerHTML =
                '<tr id="kalender-empty-row"><td colspan="7" style="text-align:center;padding:40px;color:#999;">' +
                '<span class="dashicons dashicons-calendar" style="font-size:48px;width:48px;height:48px;display:block;margin:0 auto 12px;color:#ccc;"></span>' +
                'Belum ada event. Klik "Tambah Event" atau "Import Contoh" untuk memulai.' +
                '</td></tr>';
            return;
        }

        var html = '';
        events.forEach(function (evt) {
            var typeKey = evt.type || 'other';
            var color = evt.color || typeColors[typeKey] || '#6c757d';
            var label = typeLabels[typeKey] || 'Lainnya';
            var desc = evt.description || '';
            if (desc.length > 60) desc = desc.substring(0, 60) + '...';

            html +=
                '<tr data-id="' + evt.id + '">' +
                '<td><span class="kalender-color-dot" style="background:' + color + ';"></span></td>' +
                '<td><strong>' + escapeHtml(evt.title) + '</strong></td>' +
                '<td><span class="kalender-type-badge" style="background:' + color + '20;color:' + color + ';">' + label + '</span></td>' +
                '<td>' + escapeHtml(evt.start) + '</td>' +
                '<td>' + escapeHtml(evt.end) + '</td>' +
                '<td class="kalender-desc-cell">' + escapeHtml(desc) + '</td>' +
                '<td style="text-align:center;">' +
                '<button type="button" class="button button-small btn-edit-event" data-id="' + evt.id + '" title="Edit"><span class="dashicons dashicons-edit"></span></button> ' +
                '<button type="button" class="button button-small btn-delete-event" data-id="' + evt.id + '" title="Hapus" style="color:#b32d2e;"><span class="dashicons dashicons-trash"></span></button>' +
                '</td></tr>';
        });

        tbody.innerHTML = html;
        bindRowActions();
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // ── Bind row action buttons ──────────────────────────────

    function bindRowActions() {
        tbody.querySelectorAll('.btn-edit-event').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.dataset.id;
                var events = collectEventsFromTable();
                var evt = events.find(function (e) { return e.id === id; });
                if (evt) {
                    openModal('Edit Event', evt);
                }
            });
        });

        tbody.querySelectorAll('.btn-delete-event').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!confirm('Hapus event ini?')) return;
                var id = this.dataset.id;
                ajax('kalender_delete_event', { event_id: id }, function (data) {
                    refreshTable(data.events);
                });
            });
        });
    }

    // ── Add Event Button ─────────────────────────────────────

    document.getElementById('btn-add-event').addEventListener('click', function () {
        openModal('Tambah Event Baru', {});
    });

    // ── Modal Close Buttons ──────────────────────────────────

    document.getElementById('btn-close-modal').addEventListener('click', closeModal);
    document.getElementById('btn-cancel-modal').addEventListener('click', closeModal);
    modal.querySelector('.kalender-modal-overlay').addEventListener('click', closeModal);

    // ── Type → Color sync ────────────────────────────────────

    document.getElementById('event_type').addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        if (opt && opt.dataset.color) {
            document.getElementById('event_color').value = opt.dataset.color;
        }
    });

    // ── Settings Form Submit ─────────────────────────────────

    settingsForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var fd = new FormData(settingsForm);
        fd.append('action', 'kalender_save_settings');
        fd.append('nonce', nonce);
        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (resp) {
                if (resp.success) {
                    showNotice(resp.data.message, 'success');
                    document.getElementById('kalender-current-year').textContent = resp.data.settings.academic_year;
                    document.getElementById('kalender-current-semester').textContent = resp.data.settings.semester;
                } else {
                    showNotice(resp.data.message || 'Gagal menyimpan.', 'error');
                }
            })
            .catch(function (err) {
                showNotice('Error: ' + err.message, 'error');
            });
    });

    // ── Toggle Settings Panel ────────────────────────────────

    document.getElementById('btn-toggle-settings').addEventListener('click', function () {
        var body = document.getElementById('kalender-settings-body');
        var icon = this.querySelector('.dashicons');
        body.classList.toggle('collapsed');
        icon.className = body.classList.contains('collapsed')
            ? 'dashicons dashicons-arrow-down-alt2'
            : 'dashicons dashicons-arrow-up-alt2';
    });

    // ── Import Sample ────────────────────────────────────────

    document.getElementById('btn-import-sample').addEventListener('click', function () {
        if (!confirm('Import contoh kalender akademik? Event yang ada akan diganti.')) return;
        ajax('kalender_import_sample', {}, function (data) {
            refreshTable(data.events);
        });
    });

    // ── Filter by Type ───────────────────────────────────────

    filterType.addEventListener('change', function () {
        var val = this.value;
        var rows = tbody.querySelectorAll('tr[data-id]');
        rows.forEach(function (row) {
            if (!val) {
                row.style.display = '';
                return;
            }
            var badge = row.querySelector('.kalender-type-badge');
            if (!badge) { row.style.display = ''; return; }

            var typeLabels = {
                kuliah: 'Kuliah', uts: 'UTS', uas: 'UAS', libur: 'Libur',
                wisuda: 'Wisuda', pendaftaran: 'Pendaftaran', orientasi: 'Orientasi',
                seminar: 'Seminar', rab: 'RAPAT', other: 'Lainnya',
            };
            row.style.display = badge.textContent.trim() === typeLabels[val] ? '' : 'none';
        });
    });

    // ── Init ─────────────────────────────────────────────────

    bindRowActions();

})();
