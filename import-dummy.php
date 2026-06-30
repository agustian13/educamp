<?php
/**
 * Standalone Dummy Data Importer for EduCampus Theme
 * Uses IAILM Suryalaya (iailm.ac.id) structure as dummy data.
 */

// 1. Locate and load WordPress core
$wp_load_path = '';
$directories = array(
    dirname(__FILE__) . '/../../../wp-load.php',
    dirname(__FILE__) . '/../../../../wp-load.php',
    dirname(__FILE__) . '/../../../../../wp-load.php'
);

foreach ($directories as $path) {
    if (file_exists($path)) {
        $wp_load_path = $path;
        break;
    }
}

if (!$wp_load_path) {
    header("Content-Type: text/html; charset=UTF-8");
    die("<h3>Error: WordPress core (wp-load.php) tidak ditemukan.</h3><p>Pastikan skrip ini dijalankan di dalam folder tema WordPress (misal: <code>wp-content/themes/educamp/import-dummy.php</code>) pada instalasi lokal Anda.</p>");
}

require_once $wp_load_path;

// Load admin functions required for media sideloading
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

/**
 * Helper: Download an image from URL and set as post featured image.
 * Handles errors gracefully so the seeder won't crash if offline.
 */
function educampus_sideload_featured_image($url, $post_id) {
    if (empty($url) || empty($post_id)) return;

    // Download the image to a temp file (30s timeout)
    $tmp = download_url($url, 30);
    if (is_wp_error($tmp)) {
        echo "[WARN] Gagal download gambar untuk post ID $post_id: " . esc_html($tmp->get_error_message()) . "\n<br>";
        return;
    }

    $file_array = array(
        'name'     => 'featured-' . $post_id . '-' . basename(parse_url($url, PHP_URL_PATH)),
        'tmp_name' => $tmp,
    );

    // Ensure the filename has a proper image extension
    if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file_array['name'])) {
        $file_array['name'] .= '.jpg';
    }

    $attach_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($attach_id)) {
        @unlink($tmp);
        echo "[WARN] Gagal sideload gambar untuk post ID $post_id: " . esc_html($attach_id->get_error_message()) . "\n<br>";
        return;
    }

    set_post_thumbnail($post_id, $attach_id);
    echo "<span style='color:#60a5fa'>[IMAGE] Gambar featured berhasil di-set untuk post ID $post_id</span>\n<br>";
}

// Check if user is logged in as administrator (security safeguard)
if (!current_user_can('manage_options') && (!defined('WP_CLI') || !WP_CLI)) {
    wp_die('Anda harus login sebagai Administrator untuk menjalankan skrip import dummy data ini.');
}

header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Data Dummy IAILM - EduCampus Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; padding-top: 50px; }
        .card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: none; }
        .log-box { background-color: #0c1524; color: #10b981; font-family: monospace; padding: 20px; border-radius: 8px; height: 450px; overflow-y: auto; font-size: 0.9rem; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 bg-white mb-4">
                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-database-fill-down fs-2"></i>
                    </div>
                    <div>
                        <h2 class="h4 mb-0 fw-bold">Pemasang Data Dummy IAILM Suryalaya</h2>
                        <p class="text-muted small mb-0">EduCampus Theme - Premium University Template</p>
                    </div>
                </div>

                <div class="alert alert-info small" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Skrip ini akan menyuntikkan data Fakultas, Program Studi, Dosen, Agenda, dan Berita asli dari <strong>IAILM Suryalaya Tasikmalaya</strong> ke dalam database lokal Anda, <strong>lengkap dengan gambar featured image</strong>.
                </div>

                <div class="log-box mb-4">
                    <?php
                    echo "[INFO] Memulai proses import data...\n<br>";
                    ob_flush(); flush();

                    // --- CLEAN UP OLD DATA ---
                    $types_to_clean = array('faculty', 'program', 'lecturer', 'news', 'event');
                    foreach ($types_to_clean as $type) {
                        $old_posts = get_posts(array(
                            'post_type' => $type,
                            'posts_per_page' => -1,
                            'post_status' => 'any'
                        ));
                        foreach ($old_posts as $op) {
                            // Also delete attached featured image
                            $thumb_id = get_post_thumbnail_id($op->ID);
                            if ($thumb_id) {
                                wp_delete_attachment($thumb_id, true);
                            }
                            wp_delete_post($op->ID, true); // Force delete (skip trash)
                        }
                        echo "[CLEANUP] Menghapus data lama untuk tipe: $type\n<br>";
                    }

                    $taxonomies_to_clean = array('news_category', 'program_level');
                    foreach ($taxonomies_to_clean as $tax) {
                        $terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
                        if (!empty($terms) && !is_wp_error($terms)) {
                            foreach ($terms as $t) {
                                wp_delete_term($t->term_id, $tax);
                            }
                        }
                    }
                    ob_flush(); flush();

                    // --- 1. IMPORT FAKULTAS (faculty) ---
                    $faculties_data = array(
                        'tarbiyah' => array(
                            'title' => 'Fakultas Tarbiyah',
                            'desc'  => 'Fakultas Tarbiyah IAILM Suryalaya berkomitmen melahirkan tenaga pendidik Muslim yang memiliki kompetensi pedagogik unggul dan berakhlak mulia berbasis nilai-nilai luhur tasawuf.',
                            'img'   => 'https://images.unsplash.com/photo-1523050854058-8df90110c476?w=800&fit=crop&q=80'
                        ),
                        'syariah' => array(
                            'title' => 'Fakultas Syariah',
                            'desc'  => 'Fakultas Syariah IAILM Suryalaya mendidik sarjana hukum Islam dan analis ekonomi syariah yang kompeten, profesional, berintegritas tinggi, serta inovatif.',
                            'img'   => 'https://images.unsplash.com/photo-1589391886645-d51941baf7fb?w=800&fit=crop&q=80'
                        ),
                        'dakwah' => array(
                            'title' => 'Fakultas Dakwah',
                            'desc'  => 'Fakultas Dakwah IAILM Suryalaya melahirkan mubaligh dan praktisi komunikasi Islam yang terampil, berwawasan luas, serta berkarakter mulia di era digital.',
                            'img'   => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=800&fit=crop&q=80'
                        ),
                        'pascasarjana' => array(
                            'title' => 'Program Pascasarjana',
                            'desc'  => 'Program Pascasarjana IAILM Suryalaya menyelenggarakan pendidikan lanjutan strata-2 (S2) bidang Pendidikan Agama Islam (PAI) dan Ilmu Tasawuf.',
                            'img'   => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&fit=crop&q=80'
                        ),
                    );

                    $faculty_ids = array();
                    foreach ($faculties_data as $key => $fac) {
                        // Check if already exists
                        $existing = get_page_by_title($fac['title'], OBJECT, 'faculty');
                        if (!$existing) {
                            $id = wp_insert_post(array(
                                'post_title'   => $fac['title'],
                                'post_content' => $fac['desc'],
                                'post_status'  => 'publish',
                                'post_type'    => 'faculty',
                                'post_excerpt' => wp_trim_words($fac['desc'], 15)
                            ));
                            $faculty_ids[$key] = $id;
                            if (!empty($fac['img'])) {
                                educampus_sideload_featured_image($fac['img'], $id);
                            }
                            echo "<span class='text-white'>[SUCCESS] Fakultas berhasil dibuat: {$fac['title']} (ID: $id)</span>\n<br>";
                        } else {
                            $faculty_ids[$key] = $existing->ID;
                            echo "[SKIP] Fakultas '{$fac['title']}' sudah ada.\n<br>";
                        }
                        ob_flush(); flush();
                    }

                    // --- 2. IMPORT PROGRAM STUDI (program) ---
                    $programs_data = array(
                        array(
                            'title'   => 'S1 Pendidikan Agama Islam (PAI)',
                            'faculty' => 'tarbiyah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Menghasilkan sarjana pendidikan agama Islam yang profesional, kompeten, dan siap mengabdi di madrasah/sekolah tingkat dasar hingga menengah.',
                            'img'     => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Pendidikan Islam Anak Usia Dini (PIAUD)',
                            'faculty' => 'tarbiyah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Mendidik guru PAUD/RA yang terampil, inovatif, serta memahami tahapan psikologi perkembangan anak usia emas berlandaskan nilai akhlak.',
                            'img'     => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Pendidikan Guru Madrasah Ibtidaiyah (PGMI)',
                            'faculty' => 'tarbiyah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Menyiapkan guru kelas tingkat SD/MI yang menguasai metode pembelajaran tematik integratif secara kreatif.',
                            'img'     => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Hukum Keluarga Islam (Ahwal Al-Syakhsiyah)',
                            'faculty' => 'syariah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Menelaah hukum perkawinan Islam, waris, hibah, serta menyiapkan praktisi hukum di Pengadilan Agama.',
                            'img'     => 'https://images.unsplash.com/photo-1589994965851-a8f479c573a9?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Ekonomi Syariah',
                            'faculty' => 'syariah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Mengkaji konsep perbankan syariah, lembaga keuangan mikro, asuransi, serta manajemen bisnis syariah terapan.',
                            'img'     => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Komunikasi dan Penyiaran Islam (KPI)',
                            'faculty' => 'dakwah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Mempelajari broadcasting, jurnalisme penyiaran Islam, manajemen media, dan teknik retorika komunikasi dakwah.',
                            'img'     => 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S1 Ilmu Tasawuf',
                            'faculty' => 'dakwah',
                            'level'   => 'Sarjana (S1)',
                            'desc'    => 'Mengkaji aspek spiritual Islam, metode pembersihan hati (tazkiyatun nafs), sejarah tarekat, serta terapi psikospiritual.',
                            'img'     => 'https://images.unsplash.com/photo-1585036156171-384164a8c159?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S2 Pendidikan Agama Islam (PAI)',
                            'faculty' => 'pascasarjana',
                            'level'   => 'Magister (S2)',
                            'desc'    => 'Mengembangkan keilmuan pendidikan Islam tingkat lanjut, riset kurikulum, serta kepemimpinan lembaga pendidikan.',
                            'img'     => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title'   => 'S2 Ilmu Tasawuf',
                            'faculty' => 'pascasarjana',
                            'level'   => 'Magister (S2)',
                            'desc'    => 'Program magister riset tasawuf terapan untuk kajian kerukunan sosial, kesehatan mental spiritual, dan naskah tarekat klasik.',
                            'img'     => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&fit=crop&q=80'
                        ),
                    );

                    foreach ($programs_data as $p) {
                        $existing = get_page_by_title($p['title'], OBJECT, 'program');
                        if (!$existing) {
                            $id = wp_insert_post(array(
                                'post_title'   => $p['title'],
                                'post_content' => $p['desc'],
                                'post_status'  => 'publish',
                                'post_type'    => 'program',
                                'post_excerpt' => wp_trim_words($p['desc'], 15)
                            ));
                            
                            // Link to parent faculty CPT ID
                            if (isset($faculty_ids[$p['faculty']])) {
                                update_post_meta($id, '_program_faculty_id', $faculty_ids[$p['faculty']]);
                            }
                            
                            // Add level taxonomy term
                            $term = term_exists($p['level'], 'program_level');
                            if (!$term) {
                                $term = wp_insert_term($p['level'], 'program_level');
                            }
                            $term_id = is_array($term) ? $term['term_id'] : $term;
                            wp_set_post_terms($id, array((int)$term_id), 'program_level');

                            if (!empty($p['img'])) {
                                educampus_sideload_featured_image($p['img'], $id);
                            }
                            echo "<span class='text-white'>[SUCCESS] Program Studi dibuat: {$p['title']}</span>\n<br>";
                        } else {
                            echo "[SKIP] Program Studi '{$p['title']}' sudah ada.\n<br>";
                        }
                        ob_flush(); flush();
                    }

                    // --- 3. IMPORT DOSEN (lecturer) ---
                    $lecturers_data = array(
                        array(
                            'title' => 'Dr. KH. Asep Salahudin, M.Ag.',
                            'pos'   => 'Rektor / Guru Besar Utama',
                            'nidn'  => '2105087501',
                            'email' => 'asep.salahudin@iailm.ac.id',
                            'desc'  => 'Pakar pemikiran Islam, tasawuf, dan sosiologi agama. Penulis produktif di berbagai harian nasional dan jurnal ilmiah.',
                            'img'   => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'Dr. H. Edi Komarudin, M.Pd.',
                            'pos'   => 'Dekan Fakultas Tarbiyah',
                            'nidn'  => '2112048202',
                            'email' => 'edi.komarudin@iailm.ac.id',
                            'desc'  => 'Peneliti senior bidang manajemen pendidikan Islam, kurikulum berbasis karakter, dan metode pengajaran tafsir.',
                            'img'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'Dr. Hj. Nana Rahiana, M.Ag.',
                            'pos'   => 'Dekan Fakultas Syariah',
                            'nidn'  => '2104037901',
                            'email' => 'nana.rahiana@iailm.ac.id',
                            'desc'  => 'Pakar bidang Hukum Keluarga Islam, sosiologi hukum perkawinan adat, dan fatwa-fatwa ekonomi kontemporer.',
                            'img'   => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'Nana Suryana, M.Kom.',
                            'pos'   => 'Kepala Lembaga Data & IT',
                            'nidn'  => '2115068803',
                            'email' => 'nana.suryana@iailm.ac.id',
                            'desc'  => 'Dosen komputasi terapan, perancang arsitektur data portal admisi PMB, dan pengembang e-learning kampus.',
                            'img'   => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&fit=crop&q=80'
                        ),
                    );

                    foreach ($lecturers_data as $l) {
                        $existing = get_page_by_title($l['title'], OBJECT, 'lecturer');
                        if (!$existing) {
                            $id = wp_insert_post(array(
                                'post_title'   => $l['title'],
                                'post_content' => $l['desc'],
                                'post_status'  => 'publish',
                                'post_type'    => 'lecturer',
                                'post_excerpt' => $l['desc']
                            ));
                            
                            // Save meta variables
                            update_post_meta($id, '_lecturer_position', $l['pos']);
                            update_post_meta($id, '_lecturer_nidn', $l['nidn']);
                            update_post_meta($id, '_lecturer_email', $l['email']);

                            if (!empty($l['img'])) {
                                educampus_sideload_featured_image($l['img'], $id);
                            }
                            echo "<span class='text-white'>[SUCCESS] Dosen berhasil dibuat: {$l['title']}</span>\n<br>";
                        } else {
                            echo "[SKIP] Dosen '{$l['title']}' sudah ada.\n<br>";
                        }
                        ob_flush(); flush();
                    }

                    // --- 4. IMPORT BERITA (news) ---
                    $news_data = array(
                        array(
                            'title' => 'Festival Tarbiyah 2026: Momentum Cetak Generasi Cageur Bageur',
                            'cat'   => 'Akademik',
                            'desc'  => 'Fakultas Tarbiyah IAILM Suryalaya sukses menggelar Festival Tarbiyah 2026. Kegiatan ini merupakan panggung kreativitas mahasiswa dalam bidang kepengajaran, lomba pidato ilmiah, dan kajian tasawuf sosial untuk membentuk lulusan yang cerdas (cageur) dan berkarakter luhur (bageur).',
                            'img'   => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'Perkuat Sinergi Akademik, Kaprodi PIAUD Laksanakan Visiting Lecture di UIN Malang',
                            'cat'   => 'Kerjasama',
                            'desc'  => 'Dalam upaya memperluas jaringan riset pengajaran anak usia dini, Ketua Program Studi PIAUD IAILM Suryalaya melaksanakan Visiting Lecture dan Pengabdian Kepada Masyarakat (PKM) di UIN Maulana Malik Ibrahim Malang. Diskusi berfokus pada kurikulum PAUD terintegrasi nilai tarekat.',
                            'img'   => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'IAILM Suryalaya Tandatangani MoU Kerjasama Jurnal Ilmiah Internasional',
                            'cat'   => 'Riset & Jurnal',
                            'desc'  => 'Lembaga Penelitian dan Pengabdian Masyarakat (LPPM) IAILM secara resmi menandatangani kesepakatan kerjasama peningkatan mutu jurnal ilmiah terakreditasi nasional SINTA dengan beberapa perguruan tinggi keagamaan negeri.',
                            'img'   => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&fit=crop&q=80'
                        )
                    );

                    foreach ($news_data as $n) {
                        $existing = get_page_by_title($n['title'], OBJECT, 'news');
                        if (!$existing) {
                            $id = wp_insert_post(array(
                                'post_title'   => $n['title'],
                                'post_content' => $n['desc'],
                                'post_status'  => 'publish',
                                'post_type'    => 'news',
                                'post_excerpt' => wp_trim_words($n['desc'], 18)
                            ));
                            
                            // Add custom news category taxonomy term
                            $term = term_exists($n['cat'], 'news_category');
                            if (!$term) {
                                $term = wp_insert_term($n['cat'], 'news_category');
                            }
                            $term_id = is_array($term) ? $term['term_id'] : $term;
                            wp_set_post_terms($id, array((int)$term_id), 'news_category');

                            if (!empty($n['img'])) {
                                educampus_sideload_featured_image($n['img'], $id);
                            }
                            echo "<span class='text-white'>[SUCCESS] Berita berhasil dibuat: {$n['title']}</span>\n<br>";
                        } else {
                            echo "[SKIP] Berita '{$n['title']}' sudah ada.\n<br>";
                        }
                        ob_flush(); flush();
                    }

                    // --- 5. IMPORT AGENDA (event) ---
                    $events_data = array(
                        array(
                            'title' => 'Festival Tarbiyah IAILM Suryalaya 2026',
                            'desc'  => 'Rangkaian festival ilmiah, kebudayaan Islam, dan seminar nasional pendidikan anak usia dini.',
                            'img'   => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=800&fit=crop&q=80'
                        ),
                        array(
                            'title' => 'Ujian Akhir Semester (UAS) Genap T.A. 2025/2026',
                            'desc'  => 'Pelaksanaan evaluasi akhir pembelajaran semester genap untuk seluruh program studi fakultas.',
                            'img'   => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&fit=crop&q=80'
                        )
                    );

                    foreach ($events_data as $ev) {
                        $existing = get_page_by_title($ev['title'], OBJECT, 'event');
                        if (!$existing) {
                            $id = wp_insert_post(array(
                                'post_title'   => $ev['title'],
                                'post_content' => $ev['desc'],
                                'post_status'  => 'publish',
                                'post_type'    => 'event',
                                'post_excerpt' => $ev['desc']
                            ));
                            if (!empty($ev['img'])) {
                                educampus_sideload_featured_image($ev['img'], $id);
                            }
                            echo "<span class='text-white'>[SUCCESS] Agenda berhasil dibuat: {$ev['title']}</span>\n<br>";
                        } else {
                            echo "[SKIP] Agenda '{$ev['title']}' sudah ada.\n<br>";
                        }
                        ob_flush(); flush();
                    }

                    // --- 6. PAGE CREATION (Profil & PMB) ---
                    $profil_page_id = 0;
                    $pmb_page_id = 0;

                    // Profil Page
                    $existing_profil = get_page_by_title('Profil Kampus', OBJECT, 'page');
                    if (!$existing_profil) {
                        $profil_page_id = wp_insert_post(array(
                            'post_title'   => 'Profil Kampus',
                            'post_content' => 'Selamat datang di halaman profil kami. Silakan pilih tab menu di bawah untuk melihat sejarah, visi misi, jati diri, lambang identitas, dan struktur organisasi kampus kami secara lengkap.',
                            'post_status'  => 'publish',
                            'post_type'    => 'page'
                        ));
                        update_post_meta($profil_page_id, '_wp_page_template', 'page-profil.php');
                        echo "<span class='text-white'>[SUCCESS] Halaman Profil Kampus dibuat (ID: $profil_page_id)</span>\n<br>";
                    } else {
                        $profil_page_id = $existing_profil->ID;
                        update_post_meta($profil_page_id, '_wp_page_template', 'page-profil.php');
                        echo "[SKIP] Halaman Profil Kampus sudah ada.\n<br>";
                    }
                    ob_flush(); flush();

                    // PMB Page
                    $existing_pmb = get_page_by_title('Penerimaan Mahasiswa Baru', OBJECT, 'page');
                    if (!$existing_pmb) {
                        $pmb_page_id = wp_insert_post(array(
                            'post_title'   => 'Penerimaan Mahasiswa Baru',
                            'post_content' => 'Halaman Pendaftaran Mahasiswa Baru tahun akademik 2026/2027.',
                            'post_status'  => 'publish',
                            'post_type'    => 'page'
                        ));
                        update_post_meta($pmb_page_id, '_wp_page_template', 'page-pmb.php');
                        echo "<span class='text-white'>[SUCCESS] Halaman PMB dibuat (ID: $pmb_page_id)</span>\n<br>";
                    } else {
                        $pmb_page_id = $existing_pmb->ID;
                        update_post_meta($pmb_page_id, '_wp_page_template', 'page-pmb.php');
                        echo "[SKIP] Halaman PMB sudah ada.\n<br>";
                    }
                    ob_flush(); flush();

                    // --- 7. AUTOMATED MENU SEEDING ---
                    $menu_name = 'Primary Menu';
                    $menu_exists = wp_get_nav_menu_object($menu_name);

                    if ($menu_exists) {
                        wp_delete_nav_menu($menu_name);
                        echo "[INFO] Menghapus menu navigasi lama untuk memperbarui item...\n<br>";
                    }

                    $menu_id = wp_create_nav_menu($menu_name);
                    echo "<span class='text-white'>[SUCCESS] Menu Navigasi '$menu_name' berhasil dibuat (ID: $menu_id)</span>\n<br>";

                    // Set menu location to primary
                    $locations = get_theme_mod('nav_menu_locations');
                    $locations['primary'] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);

                    // 1. Add Home link
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'   =>  __('Beranda', 'educampus'),
                        'menu-item-classes' => 'home',
                        'menu-item-url'     => home_url('/'),
                        'menu-item-status'  => 'publish',
                        'menu-item-type'    => 'custom',
                    ));

                    // 2. Add Profil parent menu item pointing to the page
                    $profil_menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Profil', 'educampus'),
                        'menu-item-object-id' => $profil_page_id,
                        'menu-item-object'    => 'page',
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                    ));

                    // Submenus under Profil pointing to hashes
                    $submenus = array(
                        array('title' => 'Sejarah & Milestones', 'hash' => '#tab-sejarah'),
                        array('title' => 'Visi & Misi', 'hash' => '#tab-visi-misi'),
                        array('title' => 'Jati Diri & Nilai', 'hash' => '#tab-jatidiri'),
                        array('title' => 'Lambang & Identitas', 'hash' => '#tab-identitas'),
                        array('title' => 'Struktur Organisasi', 'hash' => '#tab-organisasi'),
                    );

                    $profil_url = get_permalink($profil_page_id);
                    foreach ($submenus as $sub) {
                        wp_update_nav_menu_item($menu_id, 0, array(
                            'menu-item-title'     => __($sub['title'], 'educampus'),
                            'menu-item-url'       => esc_url($profil_url . $sub['hash']),
                            'menu-item-parent-id' => $profil_menu_item_id,
                            'menu-item-type'      => 'custom',
                            'menu-item-status'    => 'publish',
                        ));
                    }

                    // 3. Add Fakultas CPT archive and its submenus
                    $faculty_menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Fakultas', 'educampus'),
                        'menu-item-url'       => get_post_type_archive_link('faculty'),
                        'menu-item-type'      => 'custom',
                        'menu-item-status'    => 'publish',
                    ));

                    // Query all faculties and add as children
                    $fac_posts = get_posts(array(
                        'post_type' => 'faculty',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ));
                    foreach ($fac_posts as $fac_post) {
                        wp_update_nav_menu_item($menu_id, 0, array(
                            'menu-item-title'     => $fac_post->post_title,
                            'menu-item-url'       => get_permalink($fac_post->ID),
                            'menu-item-parent-id' => $faculty_menu_item_id,
                            'menu-item-type'      => 'custom',
                            'menu-item-status'    => 'publish',
                        ));
                    }

                    // 4. Add Program Studi CPT archive and its submenus
                    $program_menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Program Studi', 'educampus'),
                        'menu-item-url'       => get_post_type_archive_link('program'),
                        'menu-item-type'      => 'custom',
                        'menu-item-status'    => 'publish',
                    ));

                    $levels = get_terms(array(
                        'taxonomy' => 'program_level',
                        'hide_empty' => false,
                    ));
                    if (!empty($levels) && !is_wp_error($levels)) {
                        foreach ($levels as $lvl) {
                            wp_update_nav_menu_item($menu_id, 0, array(
                                'menu-item-title'     => $lvl->name,
                                'menu-item-url'       => get_term_link($lvl),
                                'menu-item-parent-id' => $program_menu_item_id,
                                'menu-item-type'      => 'custom',
                                'menu-item-status'    => 'publish',
                            ));
                        }
                    }

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Dosen', 'educampus'),
                        'menu-item-url'       => get_post_type_archive_link('lecturer'),
                        'menu-item-type'      => 'custom',
                        'menu-item-status'    => 'publish',
                    ));

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Berita', 'educampus'),
                        'menu-item-url'       => get_post_type_archive_link('news'),
                        'menu-item-type'      => 'custom',
                        'menu-item-status'    => 'publish',
                    ));

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('Agenda', 'educampus'),
                        'menu-item-url'       => get_post_type_archive_link('event'),
                        'menu-item-type'      => 'custom',
                        'menu-item-status'    => 'publish',
                    ));

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title'     => __('PMB', 'educampus'),
                        'menu-item-object-id' => $pmb_page_id,
                        'menu-item-object'    => 'page',
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                    ));
                    ob_flush(); flush();

                    echo "[INFO] Pembersihan rewrite rules (Permalink)...\n<br>";
                    flush_rewrite_rules();

                    echo "<span class='text-success fw-bold'>[FINISH] Proses import seluruh data dummy IAILM sukses selesai!</span>\n<br>";
                    ?>
                </div>

                <div class="text-center">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-campus-primary px-4 py-2">
                        <i class="bi bi-house-door-fill me-2"></i> Kunjungi Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
