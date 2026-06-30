<?php
/**
 * Template Name: Kalender Akademik
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = educampus_kalender_get_settings();
$events   = educampus_kalender_get_frontend_events();
$types    = educampus_kalender_event_types();

get_header();
?>

<?php educampus_page_hero( array(
    'title' => esc_html__( 'Kalender Akademik', 'educampus' ),
    'badge' => esc_html( $settings['academic_year'] . ' — Semester ' . $settings['semester'] ),
) ); ?>

<section class="py-5 position-relative overflow-hidden" style="background:linear-gradient(135deg,#f8f9fa 0%,#e8f4f8 50%,#f0f6fc 100%);">

    <!-- Animated Background Icons -->
    <div class="cal-bg-icons" aria-hidden="true">
        <div class="cal-bg-icon" style="--x:8%;--y:15%;--dur:18s;--delay:0s;--size:2.2rem;--opacity:.08;"><i class="bi bi-calendar3"></i></div>
        <div class="cal-bg-icon" style="--x:22%;--y:65%;--dur:22s;--delay:2s;--size:1.8rem;--opacity:.06;"><i class="bi bi-calendar-event"></i></div>
        <div class="cal-bg-icon" style="--x:45%;--y:10%;--dur:20s;--delay:4s;--size:2.5rem;--opacity:.07;"><i class="bi bi-calendar-date"></i></div>
        <div class="cal-bg-icon" style="--x:68%;--y:70%;--dur:25s;--delay:1s;--size:2rem;--opacity:.05;"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="cal-bg-icon" style="--x:85%;--y:25%;--dur:19s;--delay:3s;--size:1.6rem;--opacity:.08;"><i class="bi bi-book-half"></i></div>
        <div class="cal-bg-icon" style="--x:35%;--y:80%;--dur:24s;--delay:5s;--size:2.3rem;--opacity:.06;"><i class="bi bi-clock-history"></i></div>
        <div class="cal-bg-icon" style="--x:75%;--y:45%;--dur:21s;--delay:2.5s;--size:1.9rem;--opacity:.07;"><i class="bi bi-journal-bookmark-fill"></i></div>
        <div class="cal-bg-icon" style="--x:12%;--y:42%;--dur:23s;--delay:6s;--size:2.1rem;--opacity:.05;"><i class="bi bi-pencil-square"></i></div>
        <div class="cal-bg-icon" style="--x:55%;--y:30%;--dur:17s;--delay:1.5s;--size:1.7rem;--opacity:.06;"><i class="bi bi-bell-fill"></i></div>
        <div class="cal-bg-icon" style="--x:92%;--y:55%;--dur:26s;--delay:4.5s;--size:2rem;--opacity:.05;"><i class="bi bi-award-fill"></i></div>
        <div class="cal-bg-icon" style="--x:5%;--y:88%;--dur:20s;--delay:7s;--size:1.5rem;--opacity:.07;"><i class="bi bi-calendar-check"></i></div>
        <div class="cal-bg-icon" style="--x:40%;--y:50%;--dur:28s;--delay:0.5s;--size:2.4rem;--opacity:.04;"><i class="bi bi-person-fill"></i></div>
    </div>

    <div class="container position-relative" style="z-index:1;">

        <div class="row g-4">

            <!-- KIRI: Kalender + Legend -->
            <div class="col-lg-8">

                <!-- Calendar Navigation -->
                <div class="card border-0 shadow-sm rounded-campus mb-4">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" id="cal-prev">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <h3 class="h4 font-heading fw-bold text-campus-navy mb-0" id="cal-month-year"></h3>
                            <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" id="cal-next">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="card border-0 shadow-sm rounded-campus overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle mb-0" id="academic-calendar">
                                <thead>
                                    <tr>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Sen', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Sel', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Rab', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Kam', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Jum', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Sab', 'educampus' ); ?></th>
                                        <th class="bg-campus-navy text-white" style="width:14.28%;font-size:0.85rem;"><?php esc_html_e( 'Min', 'educampus' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="cal-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Legend di bawah kalender -->
                <div class="card border-0 shadow-sm rounded-campus">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center">
                            <span class="fw-bold text-campus-navy font-heading small"><?php esc_html_e( 'Legenda:', 'educampus' ); ?></span>
                            <?php foreach ( $types as $key => $type ) : ?>
                                <?php
                                $has_event = false;
                                foreach ( $events as $e ) {
                                    if ( $e['type'] === $key ) { $has_event = true; break; }
                                }
                                if ( ! $has_event ) continue;
                                ?>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <span class="d-inline-block rounded" style="width:14px;height:14px;background:<?php echo esc_attr( $type['color'] ); ?>;"></span>
                                    <span class="small fw-semibold text-campus-navy"><?php echo esc_html( $type['label'] ); ?></span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KANAN: Daftar Kegiatan -->
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-campus">
                    <div class="card-header bg-campus-navy text-white py-3 px-4 rounded-top-campus d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-heading fw-bold" style="font-size:0.95rem;">
                            <i class="bi bi-list-check me-2 text-campus-gold"></i><?php esc_html_e( 'Daftar Kegiatan', 'educampus' ); ?>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1" id="btn-toggle-filter" style="font-size:0.75rem;">
                            <?php esc_html_e( 'Semua', 'educampus' ); ?>
                        </button>
                    </div>
                    <div class="card-body p-0" style="max-height:620px;overflow-y:auto;" id="event-list">
                        <div class="p-4 text-center text-campus-muted">
                            <i class="bi bi-calendar-x" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="small mt-2 mb-0"><?php esc_html_e( 'Memuat...', 'educampus' ); ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- Modal Detail Event -->
<div id="event-detail-modal" class="ev-modal" style="display:none;">
    <div class="ev-modal-overlay"></div>
    <div class="ev-modal-content">
        <div class="ev-modal-header" id="ev-modal-header">
            <h3 id="ev-modal-title" class="mb-0"></h3>
            <button type="button" class="ev-modal-close" id="ev-modal-close">&times;</button>
        </div>
        <div class="ev-modal-body">
            <div class="ev-detail-row">
                <div class="ev-detail-icon"><i class="bi bi-tag-fill"></i></div>
                <div><span class="ev-detail-label">Tipe</span><br><span id="ev-modal-type" class="badge rounded-pill px-3 py-1"></span></div>
            </div>
            <div class="ev-detail-row">
                <div class="ev-detail-icon"><i class="bi bi-calendar-event"></i></div>
                <div><span class="ev-detail-label">Tanggal Mulai</span><br><span id="ev-modal-start" class="fw-semibold"></span></div>
            </div>
            <div class="ev-detail-row">
                <div class="ev-detail-icon"><i class="bi bi-calendar-check"></i></div>
                <div><span class="ev-detail-label">Tanggal Akhir</span><br><span id="ev-modal-end" class="fw-semibold"></span></div>
            </div>
            <div class="ev-detail-row">
                <div class="ev-detail-icon"><i class="bi bi-clock-history"></i></div>
                <div><span class="ev-detail-label">Durasi</span><br><span id="ev-modal-duration" class="fw-semibold"></span></div>
            </div>
            <div class="ev-detail-row" id="ev-modal-desc-row">
                <div class="ev-detail-icon"><i class="bi bi-card-text"></i></div>
                <div><span class="ev-detail-label">Deskripsi</span><br><p id="ev-modal-desc" class="mb-0 mt-1"></p></div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover { background:#f8f9fa; }
.rounded-top-campus { border-radius:.5rem .5rem 0 0; }
.min-width-0 { min-width:0; }
#academic-calendar td { transition:background .15s; }
#academic-calendar th { padding:6px 2px; font-size:0.75rem; }
#academic-calendar td { padding:3px; }

/* Animated Background Icons */
.cal-bg-icons { position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;overflow:hidden;z-index:0; }
.cal-bg-icon {
    position:absolute;
    left:var(--x);
    top:var(--y);
    font-size:var(--size);
    color:var(--color-primary,#0a2540);
    opacity:var(--opacity,0.06);
    animation:calFloat var(--dur) ease-in-out var(--delay) infinite alternate;
    will-change:transform;
}
@keyframes calFloat {
    0%   { transform:translate(0,0) rotate(0deg) scale(1); }
    25%  { transform:translate(12px,-18px) rotate(8deg) scale(1.05); }
    50%  { transform:translate(-8px,14px) rotate(-5deg) scale(0.97); }
    75%  { transform:translate(16px,6px) rotate(10deg) scale(1.03); }
    100% { transform:translate(-4px,-10px) rotate(-3deg) scale(1); }
}

/* Event List Item */
.ev-item { cursor:pointer; transition:background .15s; }
.ev-item:hover { background:#f8f9fa; }

/* Modal */
.ev-modal { position:fixed;top:0;left:0;right:0;bottom:0;z-index:100000;display:flex;align-items:center;justify-content:center; }
.ev-modal-overlay { position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5); }
.ev-modal-content { position:relative;background:#fff;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,.3);width:100%;max-width:480px;max-height:85vh;overflow-y:auto;animation:evIn .25s ease-out; }
@keyframes evIn { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }
.ev-modal-header { display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0; }
.ev-modal-header h3 { font-size:1rem;font-weight:700; }
.ev-modal-close { background:none;border:none;font-size:24px;cursor:pointer;color:#666;padding:0;line-height:1; }
.ev-modal-close:hover { color:#000; }
.ev-modal-body { padding:20px; }
.ev-detail-row { display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9; }
.ev-detail-row:last-child { border-bottom:none; }
.ev-detail-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0; }
.ev-detail-label { font-size:0.75rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px; }
</style>

<script>
(function() {
    var allEvents = <?php echo wp_json_encode( $events ); ?>;
    var events = allEvents;

    var currentDate = new Date();
    var viewYear = currentDate.getFullYear();
    var viewMonth = currentDate.getMonth();
    var showAll = false;

    var monthNames = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    var typeLabels = <?php echo wp_json_encode( array_map(function($t){ return $t['label']; }, $types) ); ?>;

    function renderCalendar() {
        var firstDay = new Date(viewYear, viewMonth, 1);
        var lastDay = new Date(viewYear, viewMonth + 1, 0);
        var startDay = (firstDay.getDay() + 6) % 7;
        var totalDays = lastDay.getDate();

        document.getElementById('cal-month-year').textContent = monthNames[viewMonth] + ' ' + viewYear;

        var html = '';
        var day = 1;
        var today = new Date();
        today.setHours(0,0,0,0);

        for (var w = 0; w < 6; w++) {
            if (day > totalDays) break;
            html += '<tr>';
            for (var d = 0; d < 7; d++) {
                if (w === 0 && d < startDay || day > totalDays) {
                    html += '<td class="bg-light" style="height:52px;"></td>';
                } else {
                    var thisDate = new Date(viewYear, viewMonth, day);
                    var isToday = thisDate.getTime() === today.getTime();
                    var dateStr = viewYear + '-' + String(viewMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0');

                    var dayEvents = events.filter(function(e) {
                        return e.start <= dateStr && e.end >= dateStr;
                    });

                    var cellBg = '';
                    if (isToday) {
                        cellBg = '#fff3cd';
                    } else if (dayEvents.length > 0) {
                        cellBg = dayEvents[0].color + '30';
                    }

                    html += '<td class="position-relative" style="height:52px;vertical-align:top;padding:3px;background:' + cellBg + ';">';
                    html += '<div class="fw-bold ' + (isToday ? 'text-danger fw-bold' : 'text-campus-navy') + '" style="font-size:0.75rem;line-height:1;">' + day + '</div>';

                    dayEvents.forEach(function(evt) {
                        html += '<div style="height:3px;border-radius:2px;background:' + evt.color + ';margin-top:2px;" title="' + escapeHtml(evt.title) + '"></div>';
                    });

                    html += '</td>';
                    day++;
                }
            }
            html += '</tr>';
        }

        document.getElementById('cal-body').innerHTML = html;
        renderEventList();
    }

    function renderEventList() {
        var container = document.getElementById('event-list');
        if (!container) return;

        var displayEvents;

        if (showAll) {
            displayEvents = allEvents.slice();
        } else {
            displayEvents = allEvents.filter(function(e) {
                var eStart = new Date(e.start);
                var eEnd = new Date(e.end);
                var monthStart = new Date(viewYear, viewMonth, 1);
                var monthEnd = new Date(viewYear, viewMonth + 1, 0);
                return eStart <= monthEnd && eEnd >= monthStart;
            });
        }

        displayEvents.sort(function(a, b) { return a.start.localeCompare(b.start); });

        if (displayEvents.length === 0) {
            container.innerHTML = '<div class="p-4 text-center text-campus-muted"><i class="bi bi-calendar-x" style="font-size:2rem;opacity:0.3;"></i><p class="small mt-2 mb-0">Tidak ada kegiatan.</p></div>';
            return;
        }

        var html = '';
        displayEvents.forEach(function(evt, idx) {
            var color = evt.color || '#6c757d';
            var start = new Date(evt.start);
            var endTmp = new Date(evt.end);
            endTmp.setDate(endTmp.getDate() + 1);
            var diff = Math.ceil((endTmp - start) / (1000*60*60*24));
            var days = diff;

            var dateDisplay = start.getDate() + ' ' + monthNames[start.getMonth()].substring(0,3);
            if (days > 1) {
                var endDate = new Date(endTmp);
                endDate.setDate(endDate.getDate() - 1);
                dateDisplay += ' — ' + endDate.getDate() + ' ' + monthNames[endDate.getMonth()].substring(0,3);
            }

            html += '<div class="ev-item px-3 py-3 border-bottom" style="border-left:3px solid ' + color + ' !important;" data-idx="' + idx + '">';
            html += '<div class="d-flex align-items-start gap-2">';
            html += '<div class="flex-shrink-0 text-center" style="min-width:40px;">';
            html += '<div class="fw-bold text-campus-navy" style="font-size:1.1rem;line-height:1;">' + start.getDate() + '</div>';
            html += '<div class="text-campus-muted" style="font-size:0.65rem;text-transform:uppercase;">' + monthNames[start.getMonth()].substring(0,3) + '</div>';
            html += '</div>';
            html += '<div class="flex-grow-1 min-width-0">';
            html += '<div class="fw-bold text-campus-navy small mb-1">' + escapeHtml(evt.title) + '</div>';
            html += '<div class="d-flex align-items-center gap-2 flex-wrap">';
            html += '<span class="badge rounded-pill px-2 py-0" style="background:' + color + '20;color:' + color + ';font-size:0.7rem;">' + (typeLabels[evt.type] || 'Lainnya') + '</span>';
            if (days > 1) {
                html += '<span class="text-campus-muted" style="font-size:0.7rem;">' + days + ' hari</span>';
            }
            html += '</div>';
            html += '</div>';
            html += '<div class="flex-shrink-0"><i class="bi bi-chevron-right text-campus-muted" style="font-size:0.75rem;"></i></div>';
            html += '</div></div>';
        });

        container.innerHTML = html;

        // Bind click detail
        container.querySelectorAll('.ev-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var idx = parseInt(this.dataset.idx);
                openDetailModal(displayEvents[idx]);
            });
        });
    }

    function openDetailModal(evt) {
        if (!evt) return;
        var color = evt.color || '#6c757d';
        var start = new Date(evt.start);
        var endTmp = new Date(evt.end);
        var endDisp = new Date(evt.end);
        endDisp.setDate(endDisp.getDate() + 1);
        var diff = Math.ceil((endDisp - start) / (1000*60*60*24));

        document.getElementById('ev-modal-header').style.background = color;
        document.getElementById('ev-modal-title').textContent = evt.title;

        var typeBadge = document.getElementById('ev-modal-type');
        typeBadge.textContent = typeLabels[evt.type] || 'Lainnya';
        typeBadge.style.background = color + '20';
        typeBadge.style.color = color;

        document.getElementById('ev-modal-start').textContent = formatDateID(start);
        document.getElementById('ev-modal-end').textContent = formatDateID(endTmp);
        document.getElementById('ev-modal-duration').textContent = diff + ' hari (' + dayRange(start, endTmp) + ')';

        var descRow = document.getElementById('ev-modal-desc-row');
        var descEl = document.getElementById('ev-modal-desc');
        if (evt.description) {
            descRow.style.display = '';
            descEl.textContent = evt.description;
        } else {
            descRow.style.display = 'none';
        }

        // Set icon backgrounds
        document.querySelectorAll('.ev-detail-icon').forEach(function(el) {
            el.style.background = color + '15';
            el.style.color = color;
        });

        document.getElementById('event-detail-modal').style.display = 'flex';
    }

    function formatDateID(d) {
        return d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
    }

    function dayRange(s, e) {
        var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabit'];
        return days[s.getDay()] + (s.getTime() !== e.getTime() ? ' — ' + days[e.getDay()] : '');
    }

    function escapeHtml(s) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(s));
        return d.innerHTML;
    }

    // Close modal
    document.getElementById('ev-modal-close').addEventListener('click', function() {
        document.getElementById('event-detail-modal').style.display = 'none';
    });
    document.querySelector('.ev-modal-overlay').addEventListener('click', function() {
        document.getElementById('event-detail-modal').style.display = 'none';
    });

    // Toggle filter
    document.getElementById('btn-toggle-filter').addEventListener('click', function() {
        showAll = !showAll;
        this.textContent = showAll ? 'Bulan Ini' : 'Semua';
        renderEventList();
    });

    // Nav
    document.getElementById('cal-prev').addEventListener('click', function() {
        viewMonth--;
        if (viewMonth < 0) { viewMonth = 11; viewYear--; }
        renderCalendar();
    });

    document.getElementById('cal-next').addEventListener('click', function() {
        viewMonth++;
        if (viewMonth > 11) { viewMonth = 0; viewYear++; }
        renderCalendar();
    });

    renderCalendar();
})();
</script>

<?php get_footer(); ?>
