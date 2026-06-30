<?php
/**
 * EduCampus Theme Customizer settings for Profile Page
 *
 * @package EduCampus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function educampus_customize_register( $wp_customize ) {

    // =====================================================
    // PANEL: TAMPILAN TEMA (Colors & Fonts)
    // =====================================================
    $wp_customize->add_panel( 'educampus_appearance_panel', array(
        'title'       => __( 'Tampilan Tema', 'educampus' ),
        'priority'    => 20,
        'description' => __( 'Sesuaikan warna dan tipografi tema sesuai identitas kampus Anda.', 'educampus' ),
    ) );

    // --- SECTION: Warna Utama ---
    $wp_customize->add_section( 'educampus_colors_section', array(
        'title'    => __( '1. Warna Tema', 'educampus' ),
        'panel'    => 'educampus_appearance_panel',
        'priority' => 10,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'educampus_color_primary', array(
        'default'           => '#0a2540',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_primary', array(
        'label'       => __( 'Warna Utama (Primary/Navy)', 'educampus' ),
        'description' => __( 'Warna dominan untuk header, footer, tombol, dan elemen utama.', 'educampus' ),
        'section'     => 'educampus_colors_section',
    ) ) );

    // Primary Light Color
    $wp_customize->add_setting( 'educampus_color_primary_light', array(
        'default'           => '#163c63',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_primary_light', array(
        'label'   => __( 'Warna Utama Terang (Primary Light)', 'educampus' ),
        'section' => 'educampus_colors_section',
    ) ) );

    // Primary Dark Color
    $wp_customize->add_setting( 'educampus_color_primary_dark', array(
        'default'           => '#051424',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_primary_dark', array(
        'label'   => __( 'Warna Utama Gelap (Primary Dark)', 'educampus' ),
        'section' => 'educampus_colors_section',
    ) ) );

    // Secondary/Accent Color
    $wp_customize->add_setting( 'educampus_color_secondary', array(
        'default'           => '#c5a059',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_secondary', array(
        'label'       => __( 'Warna Aksen (Secondary/Gold)', 'educampus' ),
        'description' => __( 'Warna untuk badge, aksen, dan highlight.', 'educampus' ),
        'section'     => 'educampus_colors_section',
    ) ) );

    // Secondary Light
    $wp_customize->add_setting( 'educampus_color_secondary_light', array(
        'default'           => '#d4b87c',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_secondary_light', array(
        'label'   => __( 'Warna Aksen Terang (Secondary Light)', 'educampus' ),
        'section' => 'educampus_colors_section',
    ) ) );

    // Secondary Dark
    $wp_customize->add_setting( 'educampus_color_secondary_dark', array(
        'default'           => '#a17f3e',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_color_secondary_dark', array(
        'label'   => __( 'Warna Aksen Gelap (Secondary Dark)', 'educampus' ),
        'section' => 'educampus_colors_section',
    ) ) );

    // --- SECTION: Tipografi ---
    $wp_customize->add_section( 'educampus_fonts_section', array(
        'title'    => __( '2. Tipografi (Font)', 'educampus' ),
        'panel'    => 'educampus_appearance_panel',
        'priority' => 20,
    ) );

    $font_choices = array(
        'Poppins'        => 'Poppins',
        'Inter'          => 'Inter',
        'Roboto'         => 'Roboto',
        'Open Sans'      => 'Open Sans',
        'Montserrat'     => 'Montserrat',
        'Lato'           => 'Lato',
        'Nunito'         => 'Nunito',
        'Raleway'        => 'Raleway',
        'Source Sans 3'  => 'Source Sans 3',
        'PT Sans'        => 'PT Sans',
        'Merriweather'   => 'Merriweather',
        'Playfair Display' => 'Playfair Display',
        'Libre Baskerville' => 'Libre Baskerville',
        'DM Sans'        => 'DM Sans',
        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
        'Outfit'         => 'Outfit',
        'Mulish'         => 'Mulish',
        'Quicksand'      => 'Quicksand',
        'Manrope'        => 'Manrope',
        'Space Grotesk'  => 'Space Grotesk',
    );

    // Heading Font
    $wp_customize->add_setting( 'educampus_font_heading', array(
        'default'           => 'Poppins',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'educampus_font_heading', array(
        'label'       => __( 'Font Judul (Heading)', 'educampus' ),
        'description' => __( 'Font untuk H1-H6, badge, tombol, dan label.', 'educampus' ),
        'section'     => 'educampus_fonts_section',
        'type'        => 'select',
        'choices'     => $font_choices,
    ) );

    // Body Font
    $wp_customize->add_setting( 'educampus_font_body', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'educampus_font_body', array(
        'label'       => __( 'Font Isi (Body)', 'educampus' ),
        'description' => __( 'Font untuk paragraf, teks biasa, dan konten.', 'educampus' ),
        'section'     => 'educampus_fonts_section',
        'type'        => 'select',
        'choices'     => $font_choices,
    ) );

    // Add Panel for Campus Profile Settings
    $wp_customize->add_panel( 'educampus_profile_panel', array(
        'title'       => __( 'Profil Kampus (Tentang)', 'educampus' ),
        'priority'    => 30,
        'description' => __( 'Kelola data sejarah, visi misi, jati diri, dan identitas kampus untuk halaman profil.', 'educampus' ),
    ) );

    // 1. SECTION: VISI & MISI
    $wp_customize->add_section( 'educampus_visimisi_section', array(
        'title'    => __( '1. Visi & Misi', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 10,
    ) );

    // Visi Setting
    $wp_customize->add_setting( 'educampus_visi', array(
        'default'           => 'Menjadi pelopor perguruan tinggi nasional berkelas dunia (World Class University) yang unggul, inovatif, mengabdi kepada kepentingan bangsa dan kemanusiaan berlandaskan nilai luhur Pancasila.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_visi', array(
        'label'    => __( 'Pernyataan Visi', 'educampus' ),
        'section'  => 'educampus_visimisi_section',
        'type'     => 'textarea',
    ) );

    // Misi Setting
    $wp_customize->add_setting( 'educampus_misi', array(
        'default'           => "Menyelenggarakan pendidikan tinggi yang berkualitas, relevan, serta responsif terhadap perkembangan revolusi industri.\nMendorong riset terapan yang inovatif dan kolaboratif antar-disiplin ilmu.\nMenyelenggarakan kegiatan pengabdian kepada masyarakat berbasis riset.",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_misi', array(
        'label'       => __( 'Pernyataan Misi (Satu baris per-poin)', 'educampus' ),
        'section'     => 'educampus_visimisi_section',
        'type'        => 'textarea',
        'description' => __( 'Gunakan tombol Enter/baris baru untuk memisahkan setiap poin misi.', 'educampus' ),
    ) );

    // 2. SECTION: JATI DIRI (5 PILAR)
    $wp_customize->add_section( 'educampus_jatidiri_section', array(
        'title'    => __( '2. Jati Diri & Nilai Dasar', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 20,
    ) );

    $default_titles = array(
        1 => 'Cageur Lahir Bathin (Sehat)',
        2 => 'Bageur Lahir Bathin (Baik)',
        3 => 'Bener Lahir Bathin (Benar)',
        4 => 'Pinter Lahir Bathin (Pintar)',
        5 => 'Singer Lahir Bathin (Terampil)'
    );
    $default_descs = array(
        1 => 'Memiliki kesehatan fisik (jasmani) dan kesucian jiwa (rohani) yang seimbang. Mahasiswa dibekali kapasitas intelektual yang sehat melalui pengembangan rasio secara komprehensif dalam menyerap ilmu-ilmu keislaman, sosial, dan eksakta secara terpadu. Proses pembelajaran mengadopsi metode interkoneksi sufistik yang mengintegrasikan kecerdasan intelektual dengan kejernihan hati.',
        2 => 'Berperilaku baik, berakhlak mulia, dan memancarkan welas asih tulus dari lubuk hati. Nilai bageur diimplementasikan dalam pembentukan akhlak sufistik di lingkungan kampus yang mencakup etika komunikasi santun, integritas akademik tinggi, kejujuran, serta sikap tawadhu (rendah hati) dalam interaksi sosial.',
        3 => 'Senantiasa berdiri teguh di atas syariat hukum agama dan aturan negara secara lurus. Mahasiswa dibimbing untuk memiliki integritas moral yang kokoh, mampu membedakan kebenaran dan kebatilan, serta konsisten dalam menegakkan nilai-nilai keadilan di tengah masyarakat.',
        4 => 'Cerdas secara intelektual, spiritual, dan emosional (Ilmu Amaliah, Amal Ilmiah). Mahasiswa tidak hanya menguasai teori di ruang kelas, tetapi juga mampu mengaplikasikan ilmunya secara nyata dengan tetap berlandaskan nilai-nilai spiritual yang berbasis pada ajaran tasawuf.',
        5 => 'Terampil, ulet, kreatif, tanggap, dan mawas diri dalam khidmat kemasyarakatan. Mahasiswa dilatih untuk memiliki kecakapan hidup (life skill) yang relevan dengan kebutuhan era kontemporer, sehingga ilmu yang diperoleh selama perkuliahan dapat diaplikasikan secara langsung dan memberikan manfaat nyata bagi komunitas luas.',
    );

    for ($i = 1; $i <= 5; $i++) {
        // Pillar Title
        $wp_customize->add_setting( "educampus_pilar{$i}_title", array(
            'default'           => $default_titles[$i],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_pilar{$i}_title", array(
            'label'    => sprintf( __( 'Judul Pilar %d', 'educampus' ), $i ),
            'section'  => 'educampus_jatidiri_section',
            'type'     => 'text',
        ) );

        // Pillar Desc
        $wp_customize->add_setting( "educampus_pilar{$i}_desc", array(
            'default'           => $default_descs[$i],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_pilar{$i}_desc", array(
            'label'    => sprintf( __( 'Deskripsi Pilar %d', 'educampus' ), $i ),
            'section'  => 'educampus_jatidiri_section',
            'type'     => 'text',
        ) );
    }

    // 3. SECTION: SEJARAH MILESTONES
    $wp_customize->add_section( 'educampus_sejarah_section', array(
        'title'    => __( '3. Lintasan Sejarah (Milestones)', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 30,
    ) );

    $default_years = array(
        1 => '1952',
        2 => '1957',
        3 => '2003',
        4 => '2026'
    );
    $default_ms_titles = array(
        1 => 'Pendirian Yayasan Kampus',
        2 => 'Peresmian Universitas Negeri',
        3 => 'Kenaikan Status PTN-BH',
        4 => 'Peringkat Akreditasi UNGGUL'
    );
    $default_ms_descs = array(
        1 => 'Inisiasi awal perkumpulan pendidik nasional membentuk Yayasan Pendidikan Tinggi.',
        2 => 'Presiden Republik Indonesia pertama, Ir. Soekarno, meresmikan status universitas.',
        3 => 'Sesuai Peraturan Pemerintah RI, institusi resmi menyandang status PTN-BH.',
        4 => 'BAN-PT memberikan akreditasi tertinggi UNGGUL nasional.'
    );

    for ($i = 1; $i <= 4; $i++) {
        // Milestone Year
        $wp_customize->add_setting( "educampus_ms{$i}_year", array(
            'default'           => $default_years[$i],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_ms{$i}_year", array(
            'label'    => sprintf( __( 'Tahun Milestone %d', 'educampus' ), $i ),
            'section'  => 'educampus_sejarah_section',
            'type'     => 'text',
        ) );

        // Milestone Title
        $wp_customize->add_setting( "educampus_ms{$i}_title", array(
            'default'           => $default_ms_titles[$i],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_ms{$i}_title", array(
            'label'    => sprintf( __( 'Judul Milestone %d', 'educampus' ), $i ),
            'section'  => 'educampus_sejarah_section',
            'type'     => 'text',
        ) );

        // Milestone Desc
        $wp_customize->add_setting( "educampus_ms{$i}_desc", array(
            'default'           => $default_ms_descs[$i],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_ms{$i}_desc", array(
            'label'    => sprintf( __( 'Deskripsi Milestone %d', 'educampus' ), $i ),
            'section'  => 'educampus_sejarah_section',
            'type'     => 'textarea',
        ) );
    }

    // 4. SECTION: HYMNE & MARS
    $wp_customize->add_section( 'educampus_hymnemars_section', array(
        'title'    => __( '4. Lambang & Lirik Lagu', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 40,
    ) );

    // Logo Lambang Upload
    $wp_customize->add_setting( 'educampus_logo_lambang', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_logo_lambang', array(
        'label'       => __( 'Unggah Lambang/Logo Institusi', 'educampus' ),
        'description' => __( 'Diunggah untuk ditampilkan pada tab Lambang & Identitas Visual.', 'educampus' ),
        'section'     => 'educampus_hymnemars_section',
    ) ) );

    // Lambang Description / Meaning
    $wp_customize->add_setting( 'educampus_lambang_desc', array(
        'default'           => "<h5>1. Lapisan Pertama: Bingkai Segi Lima</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Asas tunggal Pancasila.</li>\n  <li>Lima Rukun Islam.</li>\n</ul>\n\n<h5>2. Lapisan Kedua: Kupu-Kupu</h5>\n<p><strong>Terdiri atas:</strong></p>\n<ul>\n  <li>Empat sayap.</li>\n  <li>Dua belas garis badan.</li>\n  <li>Dua mata.</li>\n  <li>Dua kumis.</li>\n  <li>Garis sayap sebelas dan sembilan.</li>\n  <li>Empat kaki.</li>\n</ul>\n<p><strong>Makna:</strong></p>\n<ul>\n  <li><strong>Empat sayap</strong> melambangkan nilai-nilai Tanbih:\n    <ul>\n      <li>Menghormati yang lebih tinggi derajatnya.</li>\n      <li>Hidup rukun, damai, rendah hati, dan bergotong royong.</li>\n      <li>Tidak merendahkan yang berada di bawah.</li>\n      <li>Mengasihi fakir miskin.</li>\n    </ul>\n    Serta mencerminkan empat tahapan: Syariat, Tarekat, Hakikat, dan Makrifat.\n  </li>\n  <li><strong>Dua belas garis badan</strong> melambangkan kalimat <em>La ilaha illallah</em>.</li>\n  <li><strong>Dua mata</strong> melambangkan keseimbangan hubungan: <em>Hablumminallah</em> dan <em>Hablumminannas</em>.</li>\n  <li><strong>Dua kumis</strong> melambangkan pengamalan Tarekat Qadiriyah Naqsyabandiyah.</li>\n  <li><strong>Garis sayap sebelas</strong> melambangkan peringatan manaqib Syekh Abdul Qadir Jailani setiap tanggal 11 Hijriah.</li>\n  <li><strong>Garis sayap sembilan</strong> melambangkan jasa Wali Songo dalam penyebaran Islam di Indonesia.</li>\n  <li><strong>Empat kaki</strong> melambangkan proses metamorfosis spiritual manusia menuju kesempurnaan melalui: Islam (Fiqih), Iman (Tauhid), Ihsan, dan Tajalli.</li>\n</ul>\n\n<h5>3. Lapisan Ketiga: Padi dan Kapas</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Kemakmuran dan kesuburan lahir batin.</li>\n  <li>Padi 17 butir melambangkan tanggal 17.</li>\n  <li>Kapas 8 kelompok melambangkan bulan Agustus.</li>\n  <li>Menggambarkan tanggal kemerdekaan Republik Indonesia, 17 Agustus.</li>\n</ul>\n\n<h5>4. Lapisan Keempat: Tujuh Belas Sudut Sinar Islam</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>17 rakaat salat wajib sehari semalam.</li>\n  <li>Cahaya keagungan dan kejayaan Islam.</li>\n  <li>Semangat kemerdekaan Indonesia pada tanggal 17 Agustus 1945.</li>\n</ul>\n\n<h5>5. Lapisan Kelima: Lafadz Allah</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Allah SWT sebagai tujuan utama kehidupan manusia.</li>\n</ul>\n\n<h5>6. Lapisan Keenam</h5>\n<p><strong>Terdiri atas:</strong></p>\n<ul>\n  <li>Kubah masjid, Al-Qur'an, Al-Hadis, Al-Ijma', Al-Qiyas, serta Lima traf/waktu salat.</li>\n</ul>\n<p><strong>Makna:</strong></p>\n<ul>\n  <li><strong>Kubah masjid</strong> melambangkan agama Islam.</li>\n  <li><strong>Al-Qur'an</strong> sebagai sumber hukum Islam pertama.</li>\n  <li><strong>Al-Hadis</strong> sebagai sumber hukum Islam kedua.</li>\n  <li><strong>Al-Ijma'</strong> sebagai sumber hukum Islam ketiga.</li>\n  <li><strong>Al-Qiyas</strong> sebagai sumber hukum Islam keempat.</li>\n  <li><strong>Lima traf</strong> melambangkan salat lima waktu (Subuh, Zuhur, Asar, Magrib, Isya).</li>\n</ul>\n\n<h5>7. Tulisan \"Latifah Mubarokiyah\"</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Nama institusi. Latifah merupakan singkatan dari Lembaga Tinggi Fadhilah Hidup, sedangkan Mubarokiyah dinisbatkan kepada pendiri Pondok Pesantren Suryalaya, yaitu Syekh Abdullah Mubarok bin Nur Muhammad.</li>\n</ul>\n\n<h5>8. Tulisan \"Suryalaya\"</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Keterkaitan institusi dengan Pondok Pesantren Suryalaya.</li>\n</ul>\n\n<h5>9. Motto \"Ilmu Amaliah, Amal Ilmiah\"</h5>\n<p><strong>Melambangkan:</strong></p>\n<ul>\n  <li>Ilmu yang diamalkan dalam kehidupan.</li>\n  <li>Amal yang didasarkan pada ilmu pengetahuan.</li>\n  <li>Integrasi antara penguasaan ilmu dan implementasi nyata dalam kehidupan.</li>\n</ul>\n\n<hr />\n\n<h4>Nilai Filosofis Utama Lambang</h4>\n<p>Secara keseluruhan, lambang IAILM menggambarkan integrasi:</p>\n<ul>\n  <li><strong>Keislaman:</strong> Syariat, Tarekat, Hakikat, Makrifat.</li>\n  <li><strong>Kebangsaan:</strong> Pancasila dan Kemerdekaan RI.</li>\n  <li><strong>Keilmuan:</strong> Al-Qur'an, Hadis, Ijma', Qiyas.</li>\n  <li><strong>Ketarekatan:</strong> Qadiriyah Naqsyabandiyah Suryalaya.</li>\n  <li><strong>Pengabdian masyarakat:</strong> Kemakmuran, keseimbangan, dan kasih sayang.</li>\n  <li><strong>Motto pendidikan:</strong> Ilmu Amaliah dan Amal Ilmiah.</li>\n</ul>",
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'educampus_lambang_desc', array(
        'label'       => __( 'Penjelasan/Makna Lambang', 'educampus' ),
        'description' => __( 'Teks penjelasan makna lambang atau isi statuta lambang. Mendukung tag HTML dasar.', 'educampus' ),
        'section'     => 'educampus_hymnemars_section',
        'type'        => 'textarea',
    ) );

    // Hymne Lyric
    $wp_customize->add_setting( 'educampus_hymne', array(
        'default'           => "Bakti suci kami persembahkan...\nKe pangkuan ibu pertiwi...\nEduCampus wadah ilmu mulia...\nTeguh jaya abadi...",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_hymne', array(
        'label'    => __( 'Lirik Hymne Kampus', 'educampus' ),
        'section'  => 'educampus_hymnemars_section',
        'type'     => 'textarea',
    ) );

    // Mars Lyric
    $wp_customize->add_setting( 'educampus_mars', array(
        'default'           => "Bangkitlah pemuda pemudi bangsa...\nTuntutlah ilmu setinggi angkasa...\nEduCampus siap membina...\nJayalah almamater kita...",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_mars', array(
        'label'    => __( 'Lirik Mars Kampus', 'educampus' ),
        'section'  => 'educampus_hymnemars_section',
        'type'     => 'textarea',
    ) );

    // Hymne YouTube URL
    $wp_customize->add_setting( 'educampus_hymne_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_hymne_youtube', array(
        'label'       => __( 'YouTube Hymne (URL)', 'educampus' ),
        'description' => __( 'Masukkan URL video YouTube Hymne Kampus. Jika diisi akan menampilkan video, bukan teks lirik.', 'educampus' ),
        'section'     => 'educampus_hymnemars_section',
        'type'        => 'url',
    ) );

    // Mars YouTube URL
    $wp_customize->add_setting( 'educampus_mars_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_mars_youtube', array(
        'label'       => __( 'YouTube Mars (URL)', 'educampus' ),
        'description' => __( 'Masukkan URL video YouTube Mars Kampus. Jika diisi akan menampilkan video, bukan teks lirik.', 'educampus' ),
        'section'     => 'educampus_hymnemars_section',
        'type'        => 'url',
    ) );

    // 5. SECTION: STRUKTUR ORGANISASI
    $wp_customize->add_section( 'educampus_organisasi_section', array(
        'title'    => __( '5. Struktur Organisasi', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 50,
    ) );

    // Type of layout: visual tree or uploaded image
    $wp_customize->add_setting( 'educampus_org_type', array(
        'default'           => 'visual_tree',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_org_type', array(
        'label'    => __( 'Tipe Bagan Organisasi', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'radio',
        'choices'  => array(
            'visual_tree' => __( 'Bagan Interaktif (CSS Tree)', 'educampus' ),
            'image_only'  => __( 'Gambar Saja (Unggah Gambar)', 'educampus' ),
        ),
    ) );

    // Custom Organogram Image upload
    $wp_customize->add_setting( 'educampus_org_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_image', array(
        'label'       => __( 'Unggah Gambar Bagan Organisasi', 'educampus' ),
        'description' => __( 'Digunakan jika Anda memilih tipe "Gambar Saja".', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    // Rektor
    $wp_customize->add_setting( 'educampus_org_rektor_name', array(
        'default'           => 'Dr. KH. Asep Salahudin, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_rektor_name', array(
        'label'    => __( 'Nama Rektor', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_rektor_title', array(
        'default'           => 'Rektor',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_rektor_title', array(
        'label'    => __( 'Jabatan Rektor', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_rektor_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_rektor_photo', array(
        'label'       => __( 'Foto Rektor', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Rektor.', 'educampus' ),
    ) ) );

    // Dewan Pembina Yayasan
    $wp_customize->add_setting( 'educampus_org_yayasan_name', array(
        'default'           => 'KH. Baban Ahmad Jihad, S.B.T.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_yayasan_name', array(
        'label'    => __( 'Nama Ketua Yayasan', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_yayasan_title', array(
        'default'           => 'Ketua Pembina Yayasan',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_yayasan_title', array(
        'label'    => __( 'Jabatan Yayasan', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_yayasan_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_yayasan_photo', array(
        'label'       => __( 'Foto Ketua Yayasan', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Ketua Yayasan.', 'educampus' ),
    ) ) );

    // Senat Akademik
    $wp_customize->add_setting( 'educampus_org_senat_name', array(
        'default'           => 'Prof. Dr. H. Edi Komarudin, M.Pd.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_senat_name', array(
        'label'    => __( 'Nama Ketua Senat', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_senat_title', array(
        'default'           => 'Ketua Senat Akademik',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_senat_title', array(
        'label'    => __( 'Jabatan Senat', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_senat_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_senat_photo', array(
        'label'       => __( 'Foto Ketua Senat', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Ketua Senat.', 'educampus' ),
    ) ) );

    // Wakil Rektor I
    $wp_customize->add_setting( 'educampus_org_warek1_name', array(
        'default'           => 'Dr. Hj. Nana Rahiana, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek1_name', array(
        'label'    => __( 'Nama Wakil Rektor I', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek1_title', array(
        'default'           => 'Wakil Rektor I (Bidang Akademik & Kelembagaan)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek1_title', array(
        'label'    => __( 'Jabatan Wakil Rektor I', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek1_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_warek1_photo', array(
        'label'       => __( 'Foto Wakil Rektor I', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Wakil Rektor I.', 'educampus' ),
    ) ) );

    // Wakil Rektor II
    $wp_customize->add_setting( 'educampus_org_warek2_name', array(
        'default'           => 'Dr. H. Mamat Ruhimat, M.Si.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek2_name', array(
        'label'    => __( 'Nama Wakil Rektor II', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek2_title', array(
        'default'           => 'Wakil Rektor II (Bidang Keuangan & Administrasi)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek2_title', array(
        'label'    => __( 'Jabatan Wakil Rektor II', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek2_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_warek2_photo', array(
        'label'       => __( 'Foto Wakil Rektor II', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Wakil Rektor II.', 'educampus' ),
    ) ) );

    // Wakil Rektor III
    $wp_customize->add_setting( 'educampus_org_warek3_name', array(
        'default'           => 'Dr. H. Deden Yusuf, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek3_name', array(
        'label'    => __( 'Nama Wakil Rektor III', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek3_title', array(
        'default'           => 'Wakil Rektor III (Bidang Kemahasiswaan & Kerjasama)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_warek3_title', array(
        'label'    => __( 'Jabatan Wakil Rektor III', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_warek3_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_warek3_photo', array(
        'label'       => __( 'Foto Wakil Rektor III', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
        'description' => __( 'Unggah foto profil untuk Wakil Rektor III.', 'educampus' ),
    ) ) );

    // Dekan & Direktur Pascasarjana
    $wp_customize->add_setting( 'educampus_org_dekan_tarbiyah', array(
        'default'           => 'Dr. H. Edi Komarudin, M.Pd.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_dekan_tarbiyah', array(
        'label'    => __( 'Dekan Fakultas Tarbiyah', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_dekan_tarbiyah_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_dekan_tarbiyah_photo', array(
        'label'       => __( 'Foto Dekan Tarbiyah', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_dekan_syariah', array(
        'default'           => 'Dr. Hj. Nana Rahiana, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_dekan_syariah', array(
        'label'    => __( 'Dekan Fakultas Syariah', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_dekan_syariah_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_dekan_syariah_photo', array(
        'label'       => __( 'Foto Dekan Syariah', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_dekan_dakwah', array(
        'default'           => 'Dr. H. Deden Yusuf, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_dekan_dakwah', array(
        'label'    => __( 'Dekan Fakultas Dakwah', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_dekan_dakwah_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_dekan_dakwah_photo', array(
        'label'       => __( 'Foto Dekan Dakwah', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_dir_pasca', array(
        'default'           => 'Prof. Dr. KH. Asep Salahudin, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_dir_pasca', array(
        'label'    => __( 'Direktur Pascasarjana', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_dir_pasca_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_dir_pasca_photo', array(
        'label'       => __( 'Foto Direktur Pascasarjana', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    // Kepala Unit / Lembaga / Biro
    $wp_customize->add_setting( 'educampus_org_ka_lpm', array(
        'default'           => 'Dr. H. Mamat Ruhimat, M.Si.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_ka_lpm', array(
        'label'    => __( 'Kepala Lembaga Penjaminan Mutu (LPM)', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_ka_lpm_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_ka_lpm_photo', array(
        'label'       => __( 'Foto Kepala LPM', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_ka_lppm', array(
        'default'           => 'Ahmad Fauzi, M.Ag.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_ka_lppm', array(
        'label'    => __( 'Kepala LPPM', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_ka_lppm_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_ka_lppm_photo', array(
        'label'       => __( 'Foto Kepala LPPM', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_ka_baak', array(
        'default'           => 'H. Lukmanul Hakim, M.Pd.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_ka_baak', array(
        'label'    => __( 'Kepala Biro Akademik & Kelembagaan (BAAK)', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_ka_baak_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_ka_baak_photo', array(
        'label'       => __( 'Foto Kepala BAAK', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_ka_bauk', array(
        'default'           => 'Hj. Euis Marlina, M.E.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_ka_bauk', array(
        'label'    => __( 'Kepala Biro Umum & Keuangan (BAUK)', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_ka_bauk_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_ka_bauk_photo', array(
        'label'       => __( 'Foto Kepala BAUK', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    $wp_customize->add_setting( 'educampus_org_ka_it', array(
        'default'           => 'Nana Suryana, M.Kom.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_org_ka_it', array(
        'label'    => __( 'Kepala UPT Sistem Informasi & IT', 'educampus' ),
        'section'  => 'educampus_organisasi_section',
        'type'     => 'text',
    ) );
    $wp_customize->add_setting( 'educampus_org_ka_it_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_org_ka_it_photo', array(
        'label'       => __( 'Foto Kepala UPT IT', 'educampus' ),
        'section'     => 'educampus_organisasi_section',
    ) ) );

    // 6. SECTION: DOKUMEN UNDUHAN
    $wp_customize->add_section( 'educampus_dokumen_section', array(
        'title'    => __( '6. Dokumen Unduhan', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 60,
    ) );

    for ($i = 1; $i <= 3; $i++) {
        // Document Title
        $wp_customize->add_setting( "educampus_doc{$i}_title", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_doc{$i}_title", array(
            'label'    => sprintf( __( 'Judul Dokumen %d', 'educampus' ), $i ),
            'section'  => 'educampus_dokumen_section',
            'type'     => 'text',
            'description' => sprintf( __( 'Masukkan judul dokumen %d (misal: Statuta Universitas).', 'educampus' ), $i ),
        ) );

        // Document File Upload
        $wp_customize->add_setting( "educampus_doc{$i}_file", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, "educampus_doc{$i}_file", array(
            'label'       => sprintf( __( 'Unggah File Dokumen %d (PDF, dll.)', 'educampus' ), $i ),
            'section'     => 'educampus_dokumen_section',
            'description' => sprintf( __( 'Unggah file untuk dokumen %d.', 'educampus' ), $i ),
        ) ) );
    }

    // 7. SECTION: HERO BANNER PROFIL
    $wp_customize->add_section( 'educampus_profil_hero_section', array(
        'title'    => __( '7. Hero Banner Profil', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 70,
    ) );

    $wp_customize->add_setting( 'educampus_profile_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_profile_hero_image', array(
        'label'       => __( 'Gambar Hero Banner Profil', 'educampus' ),
        'description' => __( 'Unggah gambar latar untuk hero halaman profil. Jika kosong, menggunakan gambar default di bawah.', 'educampus' ),
        'section'     => 'educampus_profil_hero_section',
    ) ) );

    // Default page hero image (fallback for all pages)
    $wp_customize->add_setting( 'educampus_default_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_default_hero_image', array(
        'label'       => __( 'Gambar Hero Default (Semua Halaman)', 'educampus' ),
        'description' => __( 'Gambar latar default untuk semua halaman yang tidak punya hero spesifik. Biarkan kosong untuk warna navy solid.', 'educampus' ),
        'section'     => 'educampus_profil_hero_section',
    ) ) );

    // 8. SECTION: HUMAS & PENULIS BERITA
    $wp_customize->add_section( 'educampus_humas_section', array(
        'title'    => __( '8. Humas & Penulis Berita', 'educampus' ),
        'panel'    => 'educampus_profile_panel',
        'priority' => 80,
    ) );

    $wp_customize->add_setting( 'educampus_humas_name', array(
        'default'           => 'Hubungan Masyarakat (Humas)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_humas_name', array(
        'label'   => __( 'Nama Organisasi / Penulis', 'educampus' ),
        'section' => 'educampus_humas_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_humas_desc', array(
        'default'           => 'Kantor Sekretariat Universitas & Pusat Hubungan Media EduCampus.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_humas_desc', array(
        'label'   => __( 'Deskripsi / Jabatan', 'educampus' ),
        'section' => 'educampus_humas_section',
        'type'    => 'text',
    ) );

    // =====================================================
    // PANEL: PENGATURAN HOMEPAGE
    // =====================================================
    $wp_customize->add_panel( 'educampus_homepage_panel', array(
        'title'       => __( 'Pengaturan Homepage', 'educampus' ),
        'priority'    => 25,
        'description' => __( 'Kelola konten halaman utama seperti Sambutan Rektor dan Statistik.', 'educampus' ),
    ) );

    // --- SECTION: Hero / Banner Utama ---
    $wp_customize->add_section( 'educampus_home_hero_section', array(
        'title'    => __( '1. Hero / Banner Utama', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 5,
    ) );

    // Hero Button 1 Text
    $wp_customize->add_setting( 'educampus_hero_btn1_text', array(
        'default'           => 'Daftar PMB Sekarang',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_hero_btn1_text', array(
        'label'   => __( 'Teks Tombol Utama (CTA 1)', 'educampus' ),
        'section' => 'educampus_home_hero_section',
        'type'    => 'text',
    ) );

    // Hero Button 1 URL
    $wp_customize->add_setting( 'educampus_hero_btn1_url', array(
        'default'           => '/pmb',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_hero_btn1_url', array(
        'label'   => __( 'Link Tombol Utama', 'educampus' ),
        'section' => 'educampus_home_hero_section',
        'type'    => 'text',
    ) );

    // Hero Button 2 Text
    $wp_customize->add_setting( 'educampus_hero_btn2_text', array(
        'default'           => 'Tanya Admisi (WA)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_hero_btn2_text', array(
        'label'   => __( 'Teks Tombol Kedua (CTA 2)', 'educampus' ),
        'section' => 'educampus_home_hero_section',
        'type'    => 'text',
    ) );

    // Hero Button 2 URL
    $wp_customize->add_setting( 'educampus_hero_btn2_url', array(
        'default'           => 'https://wa.me/6281234567890',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_hero_btn2_url', array(
        'label'   => __( 'Link Tombol Kedua', 'educampus' ),
        'section' => 'educampus_home_hero_section',
        'type'    => 'text',
    ) );

    // Hero Description Color
    $wp_customize->add_setting( 'educampus_hero_desc_color', array(
        'default'           => 'rgba(255,255,255,0.55)',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_hero_desc_color', array(
        'label'   => __( 'Warna Teks Deskripsi Hero', 'educampus' ),
        'section' => 'educampus_home_hero_section',
    ) ) );

    // --- SECTION: Sambutan Rektor ---
    $wp_customize->add_section( 'educampus_home_rector_section', array(
        'title'    => __( '2. Sambutan Rektor', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 10,
    ) );

    // Rector Welcome - Badge
    $wp_customize->add_setting( 'educampus_home_rector_badge', array(
        'default'           => 'SAMBUTAN REKTOR',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_badge', array(
        'label'   => __( 'Badge Atas', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'text',
    ) );

    // Rector Welcome - Title
    $wp_customize->add_setting( 'educampus_home_rector_title', array(
        'default'           => 'Selamat Datang di EduCampus',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_title', array(
        'label'   => __( 'Judul Utama', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'text',
    ) );

    // Rector Welcome - Quote
    $wp_customize->add_setting( 'educampus_home_rector_quote', array(
        'default'           => '"Menyongsong masa depan pendidikan tinggi yang inklusif, adaptif, dan inovatif."',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_quote', array(
        'label'   => __( 'Kutipan / Quote', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'text',
    ) );

    // Rector Welcome - Content Paragraph 1
    $wp_customize->add_setting( 'educampus_home_rector_p1', array(
        'default'           => 'Assalamu\'alaikum Wr. Wb. Selamat datang di portal resmi EduCampus. Kami merasa terhormat dapat menjadi bagian dari perjalanan akademis Anda menuju kesuksesan profesional dan intelektual.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_p1', array(
        'label'   => __( 'Paragraf Sambutan 1', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'textarea',
    ) );

    // Rector Welcome - Content Paragraph 2
    $wp_customize->add_setting( 'educampus_home_rector_p2', array(
        'default'           => 'Di era transformasi digital ini, EduCampus terus berkomitmen untuk menyelaraskan kurikulum dengan kebutuhan industri, meningkatkan publikasi jurnal internasional, serta memberikan beasiswa penuh bagi putra-putri berprestasi bangsa.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_p2', array(
        'label'   => __( 'Paragraf Sambutan 2', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'textarea',
    ) );

    // Rector Welcome - Photo
    $wp_customize->add_setting( 'educampus_home_rector_image', array(
        'default'           => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_home_rector_image', array(
        'label'       => __( 'Foto Rektor', 'educampus' ),
        'section'     => 'educampus_home_rector_section',
    ) ) );

    // Rector Welcome - Name
    $wp_customize->add_setting( 'educampus_home_rector_name', array(
        'default'           => 'Prof. Dr. Ir. H. Ahmad Wijaya, M.Sc.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_name', array(
        'label'   => __( 'Nama Rektor', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'text',
    ) );

    // Rector Welcome - Job Title
    $wp_customize->add_setting( 'educampus_home_rector_name_title', array(
        'default'           => 'Rektor EduCampus',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_name_title', array(
        'label'   => __( 'Jabatan Rektor', 'educampus' ),
        'section' => 'educampus_home_rector_section',
        'type'    => 'text',
    ) );

    // Rector Welcome - Button URL
    $wp_customize->add_setting( 'educampus_home_rector_btn_url', array(
        'default'           => '/profil/sambutan-rektor',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_home_rector_btn_url', array(
        'label'       => __( 'URL Tombol Selengkapnya', 'educampus' ),
        'description' => __( 'Bisa berupa path lokal (contoh: /profil/sambutan-rektor) atau URL lengkap.', 'educampus' ),
        'section'     => 'educampus_home_rector_section',
        'type'        => 'text',
    ) );


    // --- SECTION: Statistik ---
    $wp_customize->add_section( 'educampus_home_stats_section', array(
        'title'    => __( '3. Statistik Kampus', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 20,
    ) );

    $default_stats = array(
        1 => array( 'icon' => 'bi-people-fill', 'num' => '15.000+', 'label' => 'Mahasiswa Aktif' ),
        2 => array( 'icon' => 'bi-mortarboard-fill', 'num' => '45.000+', 'label' => 'Alumni Sukses' ),
        3 => array( 'icon' => 'bi-person-badge-fill', 'num' => '350+', 'label' => 'Dosen & Profesor' ),
        4 => array( 'icon' => 'bi-grid-fill', 'num' => '42', 'label' => 'Program Studi' ),
    );

    for ( $i = 1; $i <= 4; $i++ ) {
        // Stat Icon
        $wp_customize->add_setting( "educampus_home_stat{$i}_icon", array(
            'default'           => $default_stats[$i]['icon'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_home_stat{$i}_icon", array(
            'label'       => sprintf( __( 'Icon Statistik %d (Bootstrap Icon Class)', 'educampus' ), $i ),
            'description' => __( 'Contoh: bi-people-fill, bi-mortarboard-fill, dll.', 'educampus' ),
            'section'     => 'educampus_home_stats_section',
            'type'        => 'text',
        ) );

        // Stat Number
        $wp_customize->add_setting( "educampus_home_stat{$i}_num", array(
            'default'           => $default_stats[$i]['num'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_home_stat{$i}_num", array(
            'label'   => sprintf( __( 'Nilai/Angka Statistik %d', 'educampus' ), $i ),
            'section' => 'educampus_home_stats_section',
            'type'    => 'text',
        ) );

        // Stat Label
        $wp_customize->add_setting( "educampus_home_stat{$i}_label", array(
            'default'           => $default_stats[$i]['label'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_home_stat{$i}_label", array(
            'label'   => sprintf( __( 'Label Statistik %d', 'educampus' ), $i ),
            'section' => 'educampus_home_stats_section',
            'type'    => 'text',
        ) );
    }

    // --- SECTION: Kelola Visibilitas & Tipe Section ---
    $wp_customize->add_section( 'educampus_home_layout_section', array(
        'title'    => __( '4. Layout & Visibilitas Section', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 30,
    ) );

    $sections = array(
        'hero'          => __( 'Hero / Banner Slider', 'educampus' ),
        'stats'         => __( 'Statistik Kampus', 'educampus' ),
        'rector'        => __( 'Sambutan Rektor', 'educampus' ),
        'faculties'     => __( 'Fakultas', 'educampus' ),
        'programs'      => __( 'Program Studi', 'educampus' ),
        'news'          => __( 'Berita Terbaru', 'educampus' ),
        'announcements' => __( 'Pengumuman Resmi', 'educampus' ),
        'events'        => __( 'Agenda Kegiatan', 'educampus' ),
        'achievements'  => __( 'Prestasi Kampus', 'educampus' ),
        'partnerships'  => __( 'Kerjasama Strategis', 'educampus' ),
        'testimonials'  => __( 'Testimoni Alumni', 'educampus' ),
        'gallery'       => __( 'Galeri Kampus', 'educampus' ),
        'pmb_cta'       => __( 'PMB CTA (Diatas Footer)', 'educampus' ),
    );

    foreach ( $sections as $key => $label ) {
        $wp_customize->add_setting( "educampus_show_{$key}", array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        $wp_customize->add_control( "educampus_show_{$key}", array(
            'label'   => sprintf( __( 'Tampilkan %s', 'educampus' ), $label ),
            'section' => 'educampus_home_layout_section',
            'type'    => 'checkbox',
        ) );
    }

    // 1b. Section Order — Drag & Drop Sortable
    $section_labels = $sections;

    $wp_customize->add_setting( 'educampus_section_order', array(
        'default'           => '["hero","stats","rector","faculties","programs","news","announcements","events","achievements","partnerships","testimonials","gallery","pmb_cta"]',
        'sanitize_callback' => 'educampus_sanitize_section_order',
    ) );
    $wp_customize->add_control( 'educampus_section_order', array(
        'label'       => __( 'Urutan Section (Drag & Drop)', 'educampus' ),
        'description' => __( 'Seret (drag) item di bawah untuk mengatur urutan tampilan section.', 'educampus' ),
        'section'     => 'educampus_home_layout_section',
        'type'        => 'textarea',
        'input_attrs' => array( 'style' => 'display:none;' ),
    ) );

    // 2. Section Types / Layout Options
    // Hero Type
    $wp_customize->add_setting( 'educampus_hero_type', array(
        'default'           => 'slider',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_hero_type', array(
        'label'   => __( 'Tipe Hero Banner', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'slider' => __( 'Carousel Slider (Dinamis/Banyak Slide)', 'educampus' ),
            'static' => __( 'Static Banner (Satu Slide Saja)', 'educampus' ),
            'video'  => __( 'Video Background (Full-screen, No Overlay)', 'educampus' ),
        ),
    ) );

    // Hero Video URL (self-hosted MP4)
    $wp_customize->add_setting( 'educampus_hero_video_mp4', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_hero_video_mp4', array(
        'label'       => __( '— URL MP4 (Self-hosted)', 'educampus' ),
        'description' => __( 'Masukkan URL video .mp4. Dipakai jika YouTube kosong.', 'educampus' ),
        'section'     => 'educampus_home_layout_section',
        'type'        => 'text',
    ) );

    // Hero Video URL (YouTube)
    $wp_customize->add_setting( 'educampus_hero_video_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_hero_video_youtube', array(
        'label'       => __( '— URL YouTube', 'educampus' ),
        'description' => __( 'Contoh: https://www.youtube.com/watch?v=VIDEO_ID. Prioritas utama.', 'educampus' ),
        'section'     => 'educampus_home_layout_section',
        'type'        => 'text',
    ) );

    // Rector Layout
    $wp_customize->add_setting( 'educampus_rector_layout', array(
        'default'           => 'photo_left',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_rector_layout', array(
        'label'   => __( 'Layout Sambutan Rektor', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'photo_left'  => __( 'Foto Rektor di Kiri', 'educampus' ),
            'photo_right' => __( 'Foto Rektor di Kanan', 'educampus' ),
        ),
    ) );

    // Stats Style
    $wp_customize->add_setting( 'educampus_stats_style', array(
        'default'           => 'card',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_stats_style', array(
        'label'   => __( 'Style Statistik', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'card'     => __( 'Grid Cards (Efek Hover)', 'educampus' ),
            'bordered' => __( 'Line Minimalist (Tanpa Shadow)', 'educampus' ),
        ),
    ) );

    // Faculties Layout
    $wp_customize->add_setting( 'educampus_faculties_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_faculties_layout', array(
        'label'   => __( 'Layout Fakultas', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __( 'Grid Cards (4 Kolom)', 'educampus' ),
            'list' => __( 'List Stacked (Lebih Kompak)', 'educampus' ),
        ),
    ) );

    // Programs Layout
    $wp_customize->add_setting( 'educampus_programs_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_programs_layout', array(
        'label'   => __( 'Layout Program Studi', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __( 'Grid Cards (3 Kolom)', 'educampus' ),
            'list' => __( 'List Stacked (Lebih Kompak)', 'educampus' ),
        ),
    ) );

    // News Layout
    $wp_customize->add_setting( 'educampus_news_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_news_layout', array(
        'label'   => __( 'Layout Berita', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid'        => __( 'Grid Cards (3 Kolom)', 'educampus' ),
            'list'        => __( 'List Stacked (Lebih Kompak)', 'educampus' ),
            'overlay'     => __( 'Overlay Gambar (Modern)', 'educampus' ),
            'slider'      => __( 'Carousel Slider (Slide)', 'educampus' ),
            'featured'    => __( '1 Utama Besar + List Kecil', 'educampus' ),
            'magazine'    => __( 'Majalah Grid (1 Besar + 2 Sedang + 4 Kecil)', 'educampus' ),
            'cards-scroll'=> __( 'Horizontal Scroll (Geser ke Kanan)', 'educampus' ),
            'compact'     => __( 'Compact List (Minimalis, Ikon)', 'educampus' ),
        ),
    ) );

    // News Limit
    $wp_customize->add_setting( 'educampus_news_limit', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'educampus_news_limit', array(
        'label'       => __( 'Limit Jumlah Berita', 'educampus' ),
        'description' => __( 'Tentukan jumlah berita yang ditampilkan di homepage.', 'educampus' ),
        'section'     => 'educampus_home_layout_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
    ) );

    // Announcements Layout
    $wp_customize->add_setting( 'educampus_announcements_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_announcements_layout', array(
        'label'   => __( 'Layout Pengumuman', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __( 'Grid Cards (2 Kolom)', 'educampus' ),
            'list' => __( 'List Full-Width (Daftar Vertikal)', 'educampus' ),
        ),
    ) );

    // Events Layout
    $wp_customize->add_setting( 'educampus_events_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_events_layout', array(
        'label'   => __( 'Layout Agenda / Events', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __( 'Grid Cards (2 Kolom)', 'educampus' ),
            'list' => __( 'List Stacked (Kecil & Ramping)', 'educampus' ),
        ),
    ) );

    // Achievements Layout
    $wp_customize->add_setting( 'educampus_achievements_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_achievements_layout', array(
        'label'   => __( 'Layout Prestasi', 'educampus' ),
        'section' => 'educampus_home_layout_section',
        'type'    => 'select',
        'choices' => array(
            'grid' => __( 'Grid Cards (3 Kolom)', 'educampus' ),
            'list' => __( 'List Minimalist (Baris Tipis)', 'educampus' ),
        ),
    ) );

    // --- SECTION: Judul & Badge Section ---
    $wp_customize->add_section( 'educampus_home_headings_section', array(
        'title'    => __( '5. Judul & Deskripsi Section', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 40,
    ) );

    $heading_fields = array(
        'faculties' => array(
            'badge' => 'FAKULTAS',
            'title' => 'Fakultas & Sekolah Pascasarjana',
            'desc'  => 'EduCampus memiliki berbagai rumpun ilmu sains, humaniora, dan teknik yang terintegrasi secara profesional.'
        ),
        'programs' => array(
            'badge' => 'PROGRAM STUDI UNGGULAN',
            'title' => 'Program Studi Favorit & Unggul',
            'desc'  => 'Pilih program studi dengan tingkat keterserapan kerja tinggi di industri dan kurikulum adaptif.'
        ),
        'news' => array(
            'badge' => 'KAMPUS TERKINI',
            'title' => 'Berita & Informasi Terbaru',
            'desc'  => ''
        ),
        'announcements' => array(
            'badge' => 'PENGUMUMAN',
            'title' => 'Pengumuman Resmi Kampus',
            'desc'  => ''
        ),
        'events' => array(
            'badge' => 'KEGIATAN KAMPUS',
            'title' => 'Agenda Kegiatan Mendatang',
            'desc'  => ''
        ),
        'achievements' => array(
            'badge' => 'PRESTASI',
            'title' => 'Prestasi Internasional & Nasional',
            'desc'  => 'Berbagai pencapaian bergengsi civitas akademika EduCampus di tingkat global.'
        ),
        'partnerships' => array(
            'badge' => 'KERJASAMA STRATEGIS',
            'title' => 'Kemitraan & Kolaborasi Global',
            'desc'  => ''
        ),
        'testimonials' => array(
            'badge' => 'TESTIMONI ALUMNI',
            'title' => 'Apa Kata Mereka Tentang Kami?',
            'desc'  => ''
        ),
        'gallery' => array(
            'badge' => 'GALERI KAMPUS',
            'title' => 'Kehidupan Kampus EduCampus',
            'desc'  => ''
        ),
    );

    foreach ( $heading_fields as $key => $defaults ) {
        // Badge setting & control
        $wp_customize->add_setting( "educampus_heading_{$key}_badge", array(
            'default'           => $defaults['badge'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_heading_{$key}_badge", array(
            'label'   => sprintf( __( 'Badge Section %s', 'educampus' ), strtoupper($key) ),
            'section' => 'educampus_home_headings_section',
            'type'    => 'text',
        ) );

        // Title setting & control
        $wp_customize->add_setting( "educampus_heading_{$key}_title", array(
            'default'           => $defaults['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_heading_{$key}_title", array(
            'label'   => sprintf( __( 'Judul Section %s', 'educampus' ), strtoupper($key) ),
            'section' => 'educampus_home_headings_section',
            'type'    => 'text',
        ) );

        // Desc setting & control (if exists)
        if ( ! empty( $defaults['desc'] ) || isset($defaults['desc']) ) {
            $wp_customize->add_setting( "educampus_heading_{$key}_desc", array(
                'default'           => $defaults['desc'],
                'sanitize_callback' => 'sanitize_textarea_field',
            ) );
            $wp_customize->add_control( "educampus_heading_{$key}_desc", array(
                'label'   => sprintf( __( 'Deskripsi Section %s', 'educampus' ), strtoupper($key) ),
                'section' => 'educampus_home_headings_section',
                'type'    => 'textarea',
            ) );
        }
    }

    // --- SECTION: Background & Ornament Islami ---
    $wp_customize->add_section( 'educampus_backgrounds_section', array(
        'title'    => __( '6. Background & Ornamen Islami', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 50,
    ) );

    // Enable Islamic ornament globally
    $wp_customize->add_setting( 'educampus_enable_islamic_ornament', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'educampus_enable_islamic_ornament', array(
        'label'       => __( 'Aktifkan Ornamen Islami di Homepage', 'educampus' ),
        'description' => __( 'Menambahkan pattern geometri Islami sebagai overlay di atas warna background section.', 'educampus' ),
        'section'     => 'educampus_backgrounds_section',
        'type'        => 'checkbox',
    ) );

    // Ornament color (uses secondary by default)
    $wp_customize->add_setting( 'educampus_ornament_color', array(
        'default'           => '#c5a059',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'educampus_ornament_color', array(
        'label'       => __( 'Warna Ornamen', 'educampus' ),
        'description' => __( 'Warna pattern ornamen Islami (default: gold/aksen tema).', 'educampus' ),
        'section'     => 'educampus_backgrounds_section',
    ) ) );

    // Ornament opacity
    $wp_customize->add_setting( 'educampus_ornament_opacity', array(
        'default'           => '15',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'educampus_ornament_opacity', array(
        'label'       => __( 'Intensitas Ornamen (%)', 'educampus' ),
        'description' => __( 'Seberapa tebal ornamen terlihat (5=sangat tipis, 30=sangat jelas).', 'educampus' ),
        'section'     => 'educampus_backgrounds_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 5,
            'max'  => 40,
            'step' => 5,
        ),
    ) );

    // Per-section: background color + ornament toggle
    $bg_sections = array(
        'rector'        => array( 'label' => __( 'Sambutan Rektor', 'educampus' ),      'color' => '#f8f6f0' ),
        'faculties'     => array( 'label' => __( 'Fakultas', 'educampus' ),              'color' => '#f0ebe0' ),
        'programs'      => array( 'label' => __( 'Program Studi', 'educampus' ),         'color' => '#eef3f8' ),
        'news'          => array( 'label' => __( 'Berita Terbaru', 'educampus' ),        'color' => '#f5f0e4' ),
        'announcements' => array( 'label' => __( 'Pengumuman Resmi', 'educampus' ),     'color' => '#f8f9fa' ),
        'events'        => array( 'label' => __( 'Agenda Kegiatan', 'educampus' ),       'color' => '#eef2e8' ),
        'achievements'  => array( 'label' => __( 'Prestasi Kampus', 'educampus' ),       'color' => '#f5f0e4' ),
        'partnerships'  => array( 'label' => __( 'Kerjasama Strategis', 'educampus' ),  'color' => '#ffffff' ),
        'testimonials'  => array( 'label' => __( 'Testimoni Alumni', 'educampus' ),      'color' => '#eef3f8' ),
        'gallery'       => array( 'label' => __( 'Galeri Kampus', 'educampus' ),         'color' => '#f8f6f0' ),
        'pmb_cta'       => array( 'label' => __( 'PMB CTA', 'educampus' ),               'color' => '#0a2540' ),
    );

    foreach ( $bg_sections as $key => $data ) {
        // Background color (Start)
        $wp_customize->add_setting( "educampus_bg_color_{$key}", array(
            'default'           => $data['color'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "educampus_bg_color_{$key}", array(
            'label'   => sprintf( __( 'BG %s (Warna 1)', 'educampus' ), $data['label'] ),
            'section' => 'educampus_backgrounds_section',
        ) ) );

        // Background color (End)
        $wp_customize->add_setting( "educampus_bg_color_end_{$key}", array(
            'default'           => $data['color'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "educampus_bg_color_end_{$key}", array(
            'label'   => sprintf( __( 'BG %s (Warna 2 - Opsional Gradasi)', 'educampus' ), $data['label'] ),
            'section' => 'educampus_backgrounds_section',
        ) ) );

        // Gradient Direction
        $wp_customize->add_setting( "educampus_bg_grad_dir_{$key}", array(
            'default'           => '135deg',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( "educampus_bg_grad_dir_{$key}", array(
            'label'   => sprintf( __( 'Arah Gradasi %s', 'educampus' ), $data['label'] ),
            'section' => 'educampus_backgrounds_section',
            'type'    => 'select',
            'choices' => array(
                '135deg'    => __( 'Diagonal (135deg)', 'educampus' ),
                '45deg'     => __( 'Diagonal (45deg)', 'educampus' ),
                'to right'  => __( 'Ke Kanan (Horizontal)', 'educampus' ),
                'to bottom' => __( 'Ke Bawah (Vertikal)', 'educampus' ),
            ),
        ) );

        // Ornament toggle
        $wp_customize->add_setting( "educampus_bg_ornament_{$key}", array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        $wp_customize->add_setting( "educampus_bg_ornament_{$key}", array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        $wp_customize->add_control( "educampus_bg_ornament_{$key}", array(
            'label'   => sprintf( __( 'Ornamen di %s', 'educampus' ), $data['label'] ),
            'section' => 'educampus_backgrounds_section',
            'type'    => 'checkbox',
        ) );
    }

    // =====================================================
    // SECTION: Pengaturan Header / Utility Bar
    // =====================================================
    $wp_customize->add_section( 'educampus_header_section', array(
        'title'    => __( 'Pengaturan Header', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 1,
    ) );

    // Show Utility Bar
    $wp_customize->add_setting( 'educampus_show_utility_bar', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'educampus_show_utility_bar', array(
        'label'   => __( 'Tampilkan Utility Bar (Info & Quick Link di Atas)', 'educampus' ),
        'section' => 'educampus_header_section',
        'type'    => 'checkbox',
    ) );

    // Address
    $wp_customize->add_setting( 'educampus_header_address', array(
        'default'           => 'Jl. Kampus Raya No. 1, Jakarta',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_header_address', array(
        'label'   => __( 'Alamat Kampus (Utility Bar)', 'educampus' ),
        'section' => 'educampus_header_section',
        'type'    => 'text',
    ) );

    // Email
    $wp_customize->add_setting( 'educampus_header_email', array(
        'default'           => 'info@educampus.ac.id',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'educampus_header_email', array(
        'label'   => __( 'Email Kampus (Utility Bar)', 'educampus' ),
        'section' => 'educampus_header_section',
        'type'    => 'text',
    ) );

    // Quick Links (4 pasang field individual)
    $quick_link_defaults = array(
        array( 'label' => 'SIAKAD', 'url' => '#' ),
        array( 'label' => 'E-Learning', 'url' => '#' ),
        array( 'label' => 'Perpustakaan', 'url' => '#' ),
        array( 'label' => 'Repository', 'url' => '#' ),
    );
    for ( $i = 1; $i <= 4; $i++ ) {
        $default_label = $quick_link_defaults[ $i - 1 ]['label'];
        $default_url   = $quick_link_defaults[ $i - 1 ]['url'];

        $wp_customize->add_setting( "educampus_quick_link_{$i}_label", array(
            'default'           => $default_label,
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_quick_link_{$i}_label", array(
            'label'       => sprintf( __( 'Quick Link %d — Label', 'educampus' ), $i ),
            'section'     => 'educampus_header_section',
            'type'        => 'text',
        ) );

        $wp_customize->add_setting( "educampus_quick_link_{$i}_url", array(
            'default'           => $default_url,
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "educampus_quick_link_{$i}_url", array(
            'label'       => sprintf( __( 'Quick Link %d — URL', 'educampus' ), $i ),
            'section'     => 'educampus_header_section',
            'type'        => 'url',
        ) );
    }

    // =====================================================
    // SECTION: 7. Section PMB CTA (Diatas Footer)
    // =====================================================
    $wp_customize->add_section( 'educampus_pmb_cta_section', array(
        'title'    => __( '7. Section PMB CTA (Diatas Footer)', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 60,
    ) );

    // PMB CTA Title
    $wp_customize->add_setting( 'educampus_pmb_cta_title', array(
        'default'           => 'Siap Menyongsong Masa Depan Gemilang?',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_title', array(
        'label'   => __( 'Judul CTA', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'text',
    ) );

    // PMB CTA Desc
    $wp_customize->add_setting( 'educampus_pmb_cta_desc', array(
        'default'           => 'Pendaftaran Penerimaan Mahasiswa Baru Gelombang 1 masih dibuka. Raih beasiswa prestasi akademis dan kemudahan cicilan biaya kuliah.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_desc', array(
        'label'   => __( 'Deskripsi CTA', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'textarea',
    ) );

    // Button 1 Text
    $wp_customize->add_setting( 'educampus_pmb_cta_btn1_text', array(
        'default'           => 'Daftar Sekarang',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_btn1_text', array(
        'label'   => __( 'Teks Tombol 1', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'text',
    ) );

    // Button 1 URL
    $wp_customize->add_setting( 'educampus_pmb_cta_btn1_url', array(
        'default'           => '/pmb',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_btn1_url', array(
        'label'   => __( 'URL/Tautan Tombol 1', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'text',
    ) );

    // Button 2 Text
    $wp_customize->add_setting( 'educampus_pmb_cta_btn2_text', array(
        'default'           => 'Hubungi Admisi',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_btn2_text', array(
        'label'   => __( 'Teks Tombol 2', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'text',
    ) );

    // Button 2 URL
    $wp_customize->add_setting( 'educampus_pmb_cta_btn2_url', array(
        'default'           => 'https://wa.me/6281234567890',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'educampus_pmb_cta_btn2_url', array(
        'label'   => __( 'URL/Tautan Tombol 2', 'educampus' ),
        'section' => 'educampus_pmb_cta_section',
        'type'    => 'text',
    ) );




    // =====================================================
    // SECTION: 8. Pengaturan Halaman Arsip
    // =====================================================
    $wp_customize->add_section( 'educampus_archive_section', array(
        'title'    => __( '8. Pengaturan Halaman Arsip', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 60,
    ) );

    // Faculty Archive Badge
    $wp_customize->add_setting( 'educampus_archive_faculty_badge', array(
        'default'           => 'AKADEMIK',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_faculty_badge', array(
        'label'   => __( 'Label Halaman Fakultas', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Faculty Archive Title
    $wp_customize->add_setting( 'educampus_archive_faculty_title', array(
        'default'           => 'Fakultas & Sekolah Pascasarjana',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_faculty_title', array(
        'label'   => __( 'Judul Halaman Fakultas', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Faculty Archive Description
    $wp_customize->add_setting( 'educampus_archive_faculty_desc', array(
        'default'           => 'EduCampus menyelenggarakan program pendidikan unggul di bawah rumpun ilmu teknik, sains, ekonomi, dan hukum.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_faculty_desc', array(
        'label'   => __( 'Deskripsi Halaman Fakultas', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'textarea',
    ) );

    // Program Archive Badge
    $wp_customize->add_setting( 'educampus_archive_program_badge', array(
        'default'           => 'ADMISI',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_program_badge', array(
        'label'   => __( 'Label Halaman Program Studi', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Program Archive Title
    $wp_customize->add_setting( 'educampus_archive_program_title', array(
        'default'           => 'Pilihan Program Studi',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_program_title', array(
        'label'   => __( 'Judul Halaman Program Studi', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Program Archive Description
    $wp_customize->add_setting( 'educampus_archive_program_desc', array(
        'default'           => 'Pilih program studi impian Anda dengan kurikulum berstandar internasional dan peluang karir yang cemerlang.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_program_desc', array(
        'label'   => __( 'Deskripsi Halaman Program Studi', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'textarea',
    ) );

    // Lecturer Archive Badge
    $wp_customize->add_setting( 'educampus_archive_lecturer_badge', array(
        'default'           => 'CIVITAS',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_lecturer_badge', array(
        'label'   => __( 'Label Halaman Dosen', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Lecturer Archive Title
    $wp_customize->add_setting( 'educampus_archive_lecturer_title', array(
        'default'           => 'Direktori Dosen & Akademisi',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_lecturer_title', array(
        'label'   => __( 'Judul Halaman Dosen', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Lecturer Archive Description
    $wp_customize->add_setting( 'educampus_archive_lecturer_desc', array(
        'default'           => 'Mengenal jajaran staf pendidik profesional dan peneliti ahli di lingkungan kampus EduCampus.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_lecturer_desc', array(
        'label'   => __( 'Deskripsi Halaman Dosen', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'textarea',
    ) );

    // News Archive Badge
    $wp_customize->add_setting( 'educampus_archive_news_badge', array(
        'default'           => 'BERITA & PENGUMUMAN',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_news_badge', array(
        'label'   => __( 'Label Halaman Berita', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // News Archive Title
    $wp_customize->add_setting( 'educampus_archive_news_title', array(
        'default'           => 'Berita & Pengumuman',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_news_title', array(
        'label'   => __( 'Judul Halaman Berita', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // News Archive Description
    $wp_customize->add_setting( 'educampus_archive_news_desc', array(
        'default'           => 'Informasi terhangat mengenai riset, akademik, dan kehidupan kampus EduCampus.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_news_desc', array(
        'label'   => __( 'Deskripsi Halaman Berita', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'textarea',
    ) );

    // Dokumen Archive Badge
    $wp_customize->add_setting( 'educampus_archive_dokumen_badge', array(
        'default'           => 'REFERENSI AKADEMIK',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_dokumen_badge', array(
        'label'   => __( 'Label Halaman Dokumen', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );
    // Dokumen Archive Title
    $wp_customize->add_setting( 'educampus_archive_dokumen_title', array(
        'default'           => 'Dokumen',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_archive_dokumen_title', array(
        'label'   => __( 'Judul Halaman Dokumen', 'educampus' ),
        'section' => 'educampus_archive_section',
        'type'    => 'text',
    ) );

    // Single News Layout
    $wp_customize->add_setting( 'educampus_single_news_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'educampus_single_news_layout', array(
        'label'       => __( 'Layout Halaman Detail Berita', 'educampus' ),
        'description' => __( 'Pilih tampilan untuk halaman berita tunggal (single news).', 'educampus' ),
        'section'     => 'educampus_archive_section',
        'type'        => 'select',
        'choices'     => array(
            'default'         => __( 'Default — Akademik (Sidebar Kanan, Navy Hero)', 'educampus' ),
            'sidebar-left'    => __( 'Sidebar Kiri (Sidebar Kiri, Konten Kanan)', 'educampus' ),
            'sidebar-compact' => __( 'Sidebar Compact (Share + TOC Slim, Konten Wide)', 'educampus' ),
            'magazine'        => __( 'Magazine — Cover Story (Featured Image Hero, No Sidebar)', 'educampus' ),
            'modern'          => __( 'Modern — Clean (Gradient Header, Centered, No Sidebar)', 'educampus' ),
            'split'           => __( 'Split — Side-by-Side (Sticky Image Left, Content Right)', 'educampus' ),
            'bold'            => __( 'Bold — Immersive (Full Hero, Large Type, TOC Sidebar, Masonry)', 'educampus' ),
        ),
    ) );

    // Default OG Image for Social Sharing
    $wp_customize->add_setting( 'educampus_og_default_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'educampus_og_default_image', array(
        'label'       => __( 'Gambar Default Social Share (OG Image)', 'educampus' ),
        'description' => __( 'Digunakan saat artikel tidak memiliki featured image. Ukuran ideal: 1200x630 px.', 'educampus' ),
        'section'     => 'educampus_archive_section',
        'settings'    => 'educampus_og_default_image',
    ) ) );

    // =====================================================
    // SECTION: 9. Pengaturan Footer
    // =====================================================
    $wp_customize->add_section( 'educampus_footer_section', array(
        'title'    => __( '9. Pengaturan Footer', 'educampus' ),
        'panel'    => 'educampus_homepage_panel',
        'priority' => 70,
    ) );

    // Footer Description (About Us)
    $wp_customize->add_setting( 'educampus_footer_desc', array(
        'default'           => 'EduCampus adalah institusi pendidikan tinggi terkemuka yang berkomitmen menyelenggarakan pendidikan berkualitas tinggi, berorientasi riset, dan mencetak generasi unggul yang siap menghadapi tantangan global.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_desc', array(
        'label'   => __( 'Deskripsi Tentang Kampus (Footer Kolom 1)', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'textarea',
    ) );

    // Social Links
    $wp_customize->add_setting( 'educampus_footer_fb', array(
        'default'           => '#',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_fb', array(
        'label'   => __( 'Link Facebook', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_ig', array(
        'default'           => '#',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_ig', array(
        'label'   => __( 'Link Instagram', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_tw', array(
        'default'           => '#',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_tw', array(
        'label'   => __( 'Link Twitter/X', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_yt', array(
        'default'           => '#',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_yt', array(
        'label'   => __( 'Link YouTube', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    // Contact details
    $wp_customize->add_setting( 'educampus_footer_address', array(
        'default'           => 'Kampus Utama: Jl. Kampus Raya No. 1, Kebayoran Baru, Jakarta Selatan, 12110',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_address', array(
        'label'   => __( 'Alamat Kampus', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_phone', array(
        'default'           => '(021) 1234-5678',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_phone', array(
        'label'   => __( 'Nomor Telepon', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_wa', array(
        'default'           => '+62 812-3456-7890',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'educampus_footer_wa', array(
        'label'   => __( 'Nomor WhatsApp', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'educampus_footer_email', array(
        'default'           => 'humas@educampus.ac.id',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'educampus_footer_email', array(
        'label'   => __( 'Email Humas', 'educampus' ),
        'section' => 'educampus_footer_section',
        'type'    => 'text',
    ) );

    // Copyright Links (3 pasang field individual)
    $copyright_defaults = array(
        array( 'label' => 'Kebijakan Privasi', 'url' => '#' ),
        array( 'label' => 'Syarat & Ketentuan', 'url' => '#' ),
        array( 'label' => 'Peta Situs', 'url' => '#' ),
    );
    for ( $i = 1; $i <= 3; $i++ ) {
        $default_label = $copyright_defaults[ $i - 1 ]['label'];
        $default_url   = $copyright_defaults[ $i - 1 ]['url'];

        $wp_customize->add_setting( "educampus_copyright_link_{$i}_label", array(
            'default'           => $default_label,
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "educampus_copyright_link_{$i}_label", array(
            'label'       => sprintf( __( 'Copyright Link %d — Label', 'educampus' ), $i ),
            'section'     => 'educampus_footer_section',
            'type'        => 'text',
        ) );

        $wp_customize->add_setting( "educampus_copyright_link_{$i}_url", array(
            'default'           => $default_url,
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "educampus_copyright_link_{$i}_url", array(
            'label'       => sprintf( __( 'Copyright Link %d — URL', 'educampus' ), $i ),
            'section'     => 'educampus_footer_section',
            'type'        => 'url',
        ) );
    }
}
add_action( 'customize_register', 'educampus_customize_register' );

/**
 * Sanitize callback for URL (allows empty)
 */
function educampus_sanitize_url_or_empty( $value ) {
    if ( empty( $value ) ) {
        return '';
    }
    return esc_url_raw( $value );
}

/**
 * Sanitize section order JSON.
 */
function educampus_sanitize_section_order( $value ) {
    $decoded = json_decode( $value, true );
    if ( ! is_array( $decoded ) ) {
        return '["hero","stats","rector","faculties","programs","news","announcements","events","achievements","partnerships","testimonials","gallery","pmb_cta"]';
    }
    $valid_keys = array( 'hero', 'stats', 'rector', 'faculties', 'programs', 'news', 'announcements', 'events', 'achievements', 'partnerships', 'testimonials', 'gallery', 'pmb_cta' );
    $filtered = array();
    foreach ( $decoded as $key ) {
        if ( in_array( $key, $valid_keys, true ) ) {
            $filtered[] = $key;
        }
    }
    if ( empty( $filtered ) ) {
        return '["hero","stats","rector","faculties","programs","news","announcements","events","achievements","partnerships","testimonials","gallery","pmb_cta"]';
    }
    return json_encode( $filtered );
}

/**
 * Enqueue Customizer JS for sortable section order.
 */
function educampus_customizer_controls_scripts() {
    wp_enqueue_script( 'jquery-ui-sortable' );
    $section_labels_json = wp_json_encode( array(
        'hero'          => __( 'Hero / Banner Slider', 'educampus' ),
        'stats'         => __( 'Statistik Kampus', 'educampus' ),
        'rector'        => __( 'Sambutan Rektor', 'educampus' ),
        'faculties'     => __( 'Fakultas', 'educampus' ),
        'programs'      => __( 'Program Studi', 'educampus' ),
        'news'          => __( 'Berita Terbaru', 'educampus' ),
        'announcements' => __( 'Pengumuman Resmi', 'educampus' ),
        'events'        => __( 'Agenda Kegiatan', 'educampus' ),
        'achievements'  => __( 'Prestasi Kampus', 'educampus' ),
        'partnerships'  => __( 'Kerjasama Strategis', 'educampus' ),
        'testimonials'  => __( 'Testimoni Alumni', 'educampus' ),
        'gallery'       => __( 'Galeri Kampus', 'educampus' ),
        'pmb_cta'       => __( 'PMB CTA (Diatas Footer)', 'educampus' ),
    ) );
    $script = '
    ( function( $ ) {
        var sectionLabels = ' . $section_labels_json . ';
        function initSortable() {
            var container = $( "#customize-control-educampus_section_order" );
            if ( ! container.length || container.find( ".educampus-sortable-list" ).length ) return;
            var textarea = container.find( "textarea" );
            var saved = [];
            try { saved = JSON.parse( textarea.val() ); } catch(e) {}
            if ( ! saved.length ) saved = ["hero","stats","rector","faculties","programs","news","announcements","events","achievements","partnerships","testimonials","gallery","pmb_cta"];
            container.find( "label, textarea" ).hide();
            var css = "<style>.educampus-sortable-placeholder{height:42px;margin-bottom:4px;border:2px dashed #c5a059;border-radius:4px;background:#fefcf5;}.educampus-sortable-list li.ui-sortable-helper{box-shadow:0 4px 15px rgba(0,0,0,0.12);border-color:#c5a059!important;}</style>";
            var html = "<ul class=\\"educampus-sortable-list\\" style=\\"list-style:none;margin:8px 0 0;padding:0;\\">";
            $.each( saved, function( i, key ) {
                var label = sectionLabels[key] || key;
                html += "<li data-key=\\"" + key + "\\" style=\\"display:flex;align-items:center;gap:10px;padding:10px 14px;margin-bottom:4px;background:#fff;border:1px solid #ddd;border-radius:4px;cursor:grab;\\">";
                html += "<span style=\\"color:#bbb;font-size:14px;\\">&#9776;</span>";
                html += "<span style=\\"flex:1;font-size:13px;font-weight:500;\\">" + label + "</span>";
                html += "<span class=\\"badge\\" style=\\"color:#999;font-size:11px;background:#f0f0f1;padding:2px 8px;border-radius:10px;\\">" + (i+1) + "</span>";
                html += "</li>";
            } );
            html += "</ul>";
            container.append( css + html );
            container.find( ".educampus-sortable-list" ).sortable( {
                axis: "y",
                placeholder: "educampus-sortable-placeholder",
                update: function() {
                    var order = [];
                    container.find( ".educampus-sortable-list li" ).each( function() {
                        order.push( $( this ).data( "key" ) );
                    } );
                    textarea.val( JSON.stringify( order ) ).trigger( "change" );
                    container.find( ".educampus-sortable-list .badge" ).each( function( i ) {
                        $( this ).text( i + 1 );
                    } );
                }
            } );
        }
        $( document ).on( "click", "#accordion-section-educampus_home_layout_section", function() {
            setTimeout( initSortable, 500 );
        } );
        initSortable();
    } )( jQuery );
    ';
    wp_add_inline_script( 'jquery-ui-sortable', $script );
}
add_action( 'customize_controls_enqueue_scripts', 'educampus_customizer_controls_scripts' );

