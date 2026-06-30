(function () {
  'use strict';

  var E = window.educampusDemoImport || {};
  if (!E.ajaxUrl || !E.nonce) return;

  var steps = E.steps || {};
  var stepNames = Object.keys(steps);

  // DOM refs
  var card = document.getElementById('educampus-demo-card');
  var modal = document.getElementById('educampus-demo-modal');
  var backdrop = document.getElementById('educampus-demo-modal-backdrop');
  var btnStart = document.getElementById('educampus-demo-start');
  var btnModalImport = document.getElementById('educampus-demo-modal-import');
  var btnModalCancel = document.getElementById('educampus-demo-modal-cancel');
  var btnModalClose = document.getElementById('educampus-demo-modal-close');
  var progressEl = document.getElementById('educampus-demo-progress');
  var stepsEl = document.getElementById('educampus-steps');
  var progressBar = document.getElementById('educampus-progress-bar');
  var progressPct = document.getElementById('educampus-progress-pct');
  var logContent = document.getElementById('educampus-log-content');
  var logToggle = document.getElementById('educampus-log-toggle');
  var retryBtn = document.getElementById('educampus-demo-retry');
  var actionsEl = document.getElementById('educampus-progress-actions');

  var running = false;

  // ── Helpers ──

  function getOptions() {
    var checks = document.querySelectorAll('.educampus-import-option');
    var opts = {};
    for (var i = 0; i < checks.length; i++) {
      if (checks[i].name) {
        opts[checks[i].name] = checks[i].checked;
      }
    }
    return opts;
  }

  function addLog(msg) {
    if (!logContent) return;
    logContent.textContent += msg + '\n';
    logContent.scrollTop = logContent.scrollHeight;
  }

  function stepIcon(status) {
    if (status === 'done') return '<span class="dashicons dashicons-yes" style="font-size:16px;width:16px;height:16px;"></span>';
    if (status === 'error') return '<span class="dashicons dashicons-no" style="font-size:16px;width:16px;height:16px;"></span>';
    if (status === 'active') return '<span class="dashicons dashicons-update educampus-spin" style="font-size:16px;width:16px;height:16px;"></span>';
    return '<span style="font-size:12px;opacity:.5">○</span>';
  }

  function stepStatusText(status) {
    if (status === 'done') return 'Selesai';
    if (status === 'error') return 'Gagal';
    if (status === 'active') return 'Memproses...';
    return '';
  }

  function renderSteps(currentIdx, errorIdx) {
    if (!stepsEl) return;
    var html = '';
    for (var i = 0; i < stepNames.length; i++) {
      var name = stepNames[i];
      var label = steps[name] || name;
      var status = 'pending';
      if (i < currentIdx) status = 'done';
      else if (i === currentIdx) status = errorIdx === i ? 'error' : 'active';
      html += '<div class="educampus-step ' + status + '">' +
        '<span class="educampus-step-icon">' + stepIcon(status) + '</span>' +
        '<span class="educampus-step-label">' + label + '</span>' +
        '<span class="educampus-step-status">' + stepStatusText(status) + '</span>' +
        '</div>';
    }
    stepsEl.innerHTML = html;
  }

  function updateProgress(pct) {
    if (progressBar) progressBar.style.width = Math.min(pct, 100) + '%';
    if (progressPct) progressPct.textContent = Math.min(pct, 100) + '%';
  }

  function showActions(show) {
    if (actionsEl) actionsEl.style.display = show ? '' : 'none';
    if (retryBtn) retryBtn.style.display = show && false ? '' : 'none';
  }

  // ── API calls ──

  function api(action, data, cb) {
    var fd = new FormData();
    fd.append('action', action);
    fd.append('nonce', E.nonce);
    if (data) {
      for (var k in data) {
        if (data.hasOwnProperty(k)) {
          if (typeof data[k] === 'object') {
            fd.append(k, JSON.stringify(data[k]));
          } else {
            fd.append(k, data[k]);
          }
        }
      }
    }

    fetch(E.ajaxUrl, { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (resp) {
        if (cb) cb(resp);
      })
      .catch(function (err) {
        if (cb) cb({ success: false, data: { message: 'Network error: ' + err.message } });
      });
  }

  // ── Modal ──

  function openModal() {
    if (modal) modal.style.display = 'flex';
    if (backdrop) backdrop.style.display = '';
  }

  function closeModal() {
    if (modal) modal.style.display = 'none';
    if (backdrop) backdrop.style.display = 'none';
  }

  if (btnStart) {
    btnStart.addEventListener('click', openModal);
  }
  if (btnModalCancel) {
    btnModalCancel.addEventListener('click', closeModal);
  }
  if (btnModalClose) {
    btnModalClose.addEventListener('click', closeModal);
  }
  if (backdrop) {
    backdrop.addEventListener('click', closeModal);
  }

  // ── Import Flow ──

  function doImport() {
    closeModal();
    running = true;

    if (card) card.style.display = 'none';
    if (progressEl) progressEl.style.display = '';
    showActions(false);

    var opts = getOptions();
    addLog('Memulai import demo data...');
    addLog('Options: media=' + (opts.media ? 'ya' : 'tidak') +
      ', settings=' + (opts.settings ? 'ya' : 'tidak') +
      ', widgets=' + (opts.widgets ? 'ya' : 'tidak') +
      ', frontpage=' + (opts.frontpage ? 'ya' : 'tidak'));

    api('educampus_demo_import_start', { options: opts }, function (resp) {
      if (!resp.success) {
        addLog('[ERROR] ' + (resp.data && resp.data.message ? resp.data.message : 'Gagal memulai'));
        running = false;
        showActions(true);
        if (retryBtn) retryBtn.style.display = '';
        return;
      }
      addLog('Import dimulai. Step 1: ' + stepNames[0]);
      processStep(0);
    });
  }

  function processStep(idx) {
    if (!running) return;

    renderSteps(idx, -1);
    var pct = Math.round((idx / stepNames.length) * 100);
    updateProgress(pct);

    api('educampus_demo_import_step', {}, function (resp) {
      if (!resp.success) {
        var errMsg = resp.data && resp.data.message ? resp.data.message : 'Gagal memproses step';
        addLog('[ERROR] ' + errMsg);
        renderSteps(idx, idx);
        running = false;
        showActions(true);
        if (retryBtn) retryBtn.style.display = '';
        if (logToggle) {
          logToggle.textContent = 'Sembunyikan';
          if (logContent) logContent.style.display = '';
        }
        return;
      }

      var d = resp.data;
      addLog('[OK] ' + (d.message || 'Step ' + (idx + 1) + ' selesai'));

      if (d.status === 'completed') {
        renderSteps(stepNames.length, -1);
        updateProgress(100);
        addLog('');
        addLog('========================================');
        addLog('IMPORT SELESAI!');
        addLog('========================================');
        showActions(true);
        running = false;
        return;
      }

      if (d.status === 'next') {
        setTimeout(function () {
          processStep(d.step);
        }, 300);
      }
    });
  }

  // ── Start button in modal ──

  if (btnModalImport) {
    btnModalImport.addEventListener('click', doImport);
  }

  // ── Retry button ──

  if (retryBtn) {
    retryBtn.addEventListener('click', function () {
      if (logContent) logContent.textContent = '';
      if (retryBtn) retryBtn.style.display = 'none';
      showActions(false);
      renderSteps(0, -1);
      updateProgress(0);
      running = true;
      addLog('Mencoba lagi...');
      processStep(0);
    });
  }

  // ── Log toggle ──

  if (logToggle) {
    logToggle.addEventListener('click', function () {
      if (!logContent) return;
      var showing = logContent.style.display !== 'none';
      logContent.style.display = showing ? 'none' : '';
      logToggle.textContent = showing ? 'Tampilkan' : 'Sembunyikan';
    });
  }

  // ── Check existing import on page load ──

  api('educampus_demo_import_status', {}, function (resp) {
    if (resp.success && resp.data && resp.data.status === 'running') {
      var p = resp.data;
      if (card) card.style.display = 'none';
      if (progressEl) progressEl.style.display = '';
      showActions(false);
      running = true;
      addLog('Import terdeteksi masih berjalan. Melanjutkan...');
      renderSteps(p.step || 0, -1);
      updateProgress(Math.round(((p.step || 0) / stepNames.length) * 100));
      processStep(p.step || 0);
    }
  });

})();
