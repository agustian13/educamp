<?php
/**
 * Template Name: PMB Landing Page
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<!-- PMB Hero Banner -->
<section id="pmb-hero" class="bg-campus-navy text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge bg-campus-gold text-campus-navy uppercase font-heading fw-bold px-3 py-2 rounded-pill mb-3" style="font-size: 0.85rem; letter-spacing: 1px;">
                    <?php esc_html_e('PENERIMAAN MAHASISWA BARU (PMB) T.A. 2026/2027', 'educampus'); ?>
                </span>
                <h1 class="display-4 font-heading fw-800 text-white mb-3 lh-sm">
                    <?php echo wp_kses_post( __( 'Rancang Masa Depanmu <span class="text-campus-gold">Bersama EduCampus</span>', 'educampus' ) ); ?>
                </h1>
                <p class="lead text-white-50 mb-5 font-body">
                    <?php esc_html_e('Pilihan terbaik untuk jenjang studi Sarjana, Diploma, dan Pascasarjana dengan kurikulum unggulan, fasilitas lengkap, serta jaringan kemitraan industri global.', 'educampus'); ?>
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                    <a href="#daftar" class="btn btn-campus-secondary btn-lg px-4 py-3 font-heading shadow-campus-soft">
                        <i class="bi bi-mortarboard-fill me-2"></i> <?php esc_html_e('Daftar Online Sekarang', 'educampus'); ?>
                    </a>
                    <a href="#biaya" class="btn btn-outline-light btn-lg px-4 py-3 font-heading">
                        <i class="bi bi-info-circle me-2"></i> <?php esc_html_e('Rincian Biaya & Beasiswa', 'educampus'); ?>
                    </a>
                </div>
            </div>

            <!-- PMB Registration Quick Form Widget -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-campus-lg rounded-campus bg-white p-4">
                    <h3 class="h5 font-heading text-campus-navy fw-bold mb-3 border-bottom pb-2"><?php esc_html_e('Hubungi Konselor Admisi', 'educampus'); ?></h3>
                    <p class="small text-campus-muted mb-4"><?php esc_html_e('Silakan tinggalkan nomor kontak Anda. Tim konselor kami akan segera menghubungi untuk membantu proses pendaftaran.', 'educampus'); ?></p>
                    <form action="#" method="post" class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label font-heading small fw-bold text-campus-navy"><?php esc_html_e('Nama Lengkap', 'educampus'); ?></label>
                            <input type="text" class="form-control rounded-3 border-light shadow-none bg-campus-light" placeholder="<?php esc_attr_e('Masukkan nama lengkap...', 'educampus'); ?>" required />
                        </div>
                        <div>
                            <label class="form-label font-heading small fw-bold text-campus-navy"><?php esc_html_e('Nomor WhatsApp/Phone', 'educampus'); ?></label>
                            <input type="tel" class="form-control rounded-3 border-light shadow-none bg-campus-light" placeholder="<?php esc_attr_e('Contoh: 08123456789', 'educampus'); ?>" required />
                        </div>
                        <div>
                            <label class="form-label font-heading small fw-bold text-campus-navy"><?php esc_html_e('Program Studi Minat', 'educampus'); ?></label>
                            <select class="form-select rounded-3 border-light shadow-none bg-campus-light font-body">
                                <option value=""><?php esc_html_e('Pilih Program Studi...', 'educampus'); ?></option>
                                <option value="ti"><?php esc_html_e('S1 Teknik Informatika (Unggul)', 'educampus'); ?></option>
                                <option value="si"><?php esc_html_e('S1 Sistem Informasi (Unggul)', 'educampus'); ?></option>
                                <option value="mn"><?php esc_html_e('S1 Manajemen & Bisnis', 'educampus'); ?></option>
                                <option value="hk"><?php esc_html_e('S1 Ilmu Hukum', 'educampus'); ?></option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-campus-primary w-100 py-3 mt-2 font-heading shadow-campus-soft">
                            <?php esc_html_e('Kirim Kontak & Konsultasi Gratis', 'educampus'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PMB Advantages -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php esc_html_e('KEUNGGULAN KAMI', 'educampus'); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php esc_html_e('Mengapa Memilih EduCampus?', 'educampus'); ?></h2>
            <p class="text-campus-muted"><?php esc_html_e('Kami menjamin sistem pembelajaran bermutu tinggi yang adaptif dengan iklim industri kerja modern.', 'educampus'); ?></p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded-campus text-center h-100 transform-hover bg-campus-light">
                    <div class="display-6 text-campus-gold mb-3"><i class="bi bi-award-fill"></i></div>
                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php esc_html_e('Akreditasi UNGGUL', 'educampus'); ?></h3>
                    <p class="text-campus-muted small mb-0"><?php esc_html_e('Institusi dan program studi favorit kami telah terakreditasi peringkat tertinggi secara nasional (BAN-PT) dan internasional.', 'educampus'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-campus text-center h-100 transform-hover bg-campus-light">
                    <div class="display-6 text-campus-gold mb-3"><i class="bi bi-briefcase-fill"></i></div>
                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php esc_html_e('Jaminan Karir Global', 'educampus'); ?></h3>
                    <p class="text-campus-muted small mb-0"><?php esc_html_e('Program magang eksklusif 1 tahun di BUMN serta perusahaan teknologi terkemuka menjamin keterserapan kerja lulusan yang tinggi.', 'educampus'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-campus text-center h-100 transform-hover bg-campus-light">
                    <div class="display-6 text-campus-gold mb-3"><i class="bi bi-currency-dollar"></i></div>
                    <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><?php esc_html_e('Beasiswa & Cicilan Fleksibel', 'educampus'); ?></h3>
                    <p class="text-campus-muted small mb-0"><?php esc_html_e('Skema kemudahan cicilan biaya kuliah bulanan tanpa bunga serta beasiswa prestasi penuh 100% untuk mahasiswa berbakat.', 'educampus'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PMB Pathways / Entry Options -->
<section class="section-padding bg-campus-light">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php esc_html_e('JALUR MASUK', 'educampus'); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php esc_html_e('Jalur Pendaftaran PMB', 'educampus'); ?></h2>
            <p class="text-campus-muted"><?php esc_html_e('Pilih jalur pendaftaran yang paling sesuai dengan prestasi atau kebutuhan Anda.', 'educampus'); ?></p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><i class="bi bi-trophy-fill text-campus-gold me-2"></i> <?php esc_html_e('Jalur Prestasi (Rapor)', 'educampus'); ?></h3>
                        <p class="text-campus-muted small mb-4"><?php esc_html_e('Pendaftaran menggunakan nilai rapor semester 1-5 tanpa melalui tes tulis. Berkesempatan mendapatkan beasiswa penuh.', 'educampus'); ?></p>
                    </div>
                    <a href="#daftar" class="btn btn-campus-outline btn-sm font-heading align-self-start"><?php esc_html_e('Daftar Jalur Rapor', 'educampus'); ?></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><i class="bi bi-file-earmark-text-fill text-campus-gold me-2"></i> <?php esc_html_e('Jalur Reguler (Tes Tulis)', 'educampus'); ?></h3>
                        <p class="text-campus-muted small mb-4"><?php esc_html_e('Seleksi berbasis ujian komputer (CBT) mandiri untuk menyaring bakat akademis dasar calon mahasiswa secara komprehensif.', 'educampus'); ?></p>
                    </div>
                    <a href="#daftar" class="btn btn-campus-outline btn-sm font-heading align-self-start"><?php esc_html_e('Daftar Jalur Reguler', 'educampus'); ?></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-campus-soft rounded-campus bg-white p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="h5 font-heading fw-bold text-campus-navy mb-2"><i class="bi bi-patch-check-fill text-campus-gold me-2"></i> <?php esc_html_e('Jalur Kemitraan KIP-K', 'educampus'); ?></h3>
                        <p class="text-campus-muted small mb-4"><?php esc_html_e('Program khusus subsidi penuh biaya hidup dan kuliah bagi pelamar berprestasi yang terkendala kemampuan finansial keluarga.', 'educampus'); ?></p>
                    </div>
                    <a href="#daftar" class="btn btn-campus-outline btn-sm font-heading align-self-start"><?php esc_html_e('Daftar KIP Kuliah', 'educampus'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PMB Tuition Fee -->
<section id="biaya" class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php esc_html_e('STRUKTUR BIAYA', 'educampus'); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php esc_html_e('Rincian Biaya Kuliah Terjangkau', 'educampus'); ?></h2>
            <p class="text-campus-muted"><?php esc_html_e('Kami menjunjung keterbukaan tanpa ada biaya tersembunyi selama masa studi berlangsung.', 'educampus'); ?></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="table-responsive shadow-campus-soft rounded-campus overflow-hidden">
                    <table class="table table-bordered table-striped align-middle mb-0 text-center">
                        <thead class="bg-campus-navy text-white font-heading fw-bold">
                            <tr>
                                <th scope="col" class="text-start ps-4"><?php esc_html_e('Fakultas / Kelompok Ilmu', 'educampus'); ?></th>
                                <th scope="col"><?php esc_html_e('Biaya Pendaftaran', 'educampus'); ?></th>
                                <th scope="col"><?php esc_html_e('Uang Pangkal (DP3)*', 'educampus'); ?></th>
                                <th scope="col"><?php esc_html_e('SPP Per Semester', 'educampus'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="font-body text-campus-navy">
                            <tr>
                                <td class="text-start ps-4"><?php esc_html_e('Teknik & Ilmu Komputer', 'educampus'); ?></td>
                                <td>Rp 250.000</td>
                                <td>Rp 10.000.000</td>
                                <td>Rp 6.500.000</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4"><?php esc_html_e('Ekonomi & Bisnis', 'educampus'); ?></td>
                                <td>Rp 250.000</td>
                                <td>Rp 8.500.000</td>
                                <td>Rp 5.800.000</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4"><?php esc_html_e('Hukum & Ilmu Sosial', 'educampus'); ?></td>
                                <td>Rp 250.000</td>
                                <td>Rp 7.500.000</td>
                                <td>Rp 5.200.000</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4"><?php esc_html_e('Kedokteran & Kesehatan', 'educampus'); ?></td>
                                <td>Rp 350.000</td>
                                <td>Rp 35.000.000</td>
                                <td>Rp 18.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-campus-muted small">
                    <p class="mb-0"><?php esc_html_e('* Uang Pangkal (DP3) dapat diangsur sebanyak 6 kali selama tahun pertama kuliah berlangsung.', 'educampus'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PMB FAQs -->
<section id="faq" class="section-padding bg-campus-light">
    <div class="container">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="text-campus-gold font-heading fw-bold text-uppercase d-block mb-2 small" style="letter-spacing: 1px;"><?php esc_html_e('PERTANYAAN UMUM', 'educampus'); ?></span>
            <h2 class="h1 font-heading fw-bold text-campus-navy"><?php esc_html_e('Tanya Jawab PMB', 'educampus'); ?></h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion shadow-campus-soft rounded-campus overflow-hidden" id="faqAccordion">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="faqHeadingOne">
                            <button class="accordion-button font-heading fw-bold text-campus-navy bg-white py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                                <?php esc_html_e('Bagaimana cara melakukan pendaftaran secara online?', 'educampus'); ?>
                            </button>
                        </h2>
                        <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body font-body text-campus-muted bg-white pb-4">
                                <?php esc_html_e('Calon mahasiswa dapat mengklik tombol "Daftar Sekarang", kemudian membuat akun di portal PMB, mengunggah berkas persyaratan (rapor/foto/ijazah), dan melakukan pembayaran biaya pendaftaran secara transfer.', 'educampus'); ?>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div class="accordion-item border-0 border-top">
                        <h2 class="accordion-header" id="faqHeadingTwo">
                            <button class="accordion-button collapsed font-heading fw-bold text-campus-navy bg-white py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                <?php esc_html_e('Apakah berkas rapor harus dilegalisir oleh pihak sekolah?', 'educampus'); ?>
                            </button>
                        </h2>
                        <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body font-body text-campus-muted bg-white pb-4">
                                <?php esc_html_e('Untuk seleksi awal dokumen digital/online, Anda cukup memindai (scan) rapor asli semester 1 sampai 5. Legalisir sekolah baru diwajibkan saat proses daftar ulang secara fisik setelah dinyatakan lulus seleksi.', 'educampus'); ?>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="accordion-item border-0 border-top">
                        <h2 class="accordion-header" id="faqHeadingThree">
                            <button class="accordion-button collapsed font-heading fw-bold text-campus-navy bg-white py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                <?php esc_html_e('Apakah ada beasiswa penuh yang ditawarkan untuk mahasiswa baru?', 'educampus'); ?>
                            </button>
                        </h2>
                        <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body font-body text-campus-muted bg-white pb-4">
                                <?php esc_html_e('Ya, kami memiliki jalur Beasiswa Prestasi Akademis (berdasarkan peringkat rapor) dan Beasiswa Non-Akademis (atlet/seni/tahfidz Al-Qur\'an) yang mencakup pembebasan SPP secara penuh.', 'educampus'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final PMB CTA -->
<section id="daftar" class="py-5 bg-campus-navy text-white text-center position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4">
        <h2 class="display-6 font-heading fw-bold text-white mb-3"><?php esc_html_e('Pendaftaran Gelombang I Dibuka Hingga Akhir Bulan Ini', 'educampus'); ?></h2>
        <p class="lead text-white-50 mb-5 font-body mx-auto" style="max-width: 600px;">
            <?php esc_html_e('Jangan lewatkan kesempatan meraih beasiswa dan keringanan angsuran biaya pendidikan. Proses verifikasi dokumen berkas hanya butuh waktu 2x24 jam.', 'educampus'); ?>
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="https://pmb.educampus.ac.id" target="_blank" rel="noopener" class="btn btn-campus-secondary btn-lg px-5 font-heading py-3">
                <i class="bi bi-mortarboard-fill me-2"></i> <?php esc_html_e('Isi Formulir Online', 'educampus'); ?>
            </a>
            <a href="https://wa.me/6281234567890" class="btn btn-outline-light btn-lg px-5 font-heading py-3" target="_blank" rel="noopener">
                <i class="bi bi-chat-text-fill me-2 text-success"></i> <?php esc_html_e('Tanya Admisi via WA', 'educampus'); ?>
            </a>
        </div>
    </div>
</section>

<?php
get_footer();
