# EduCampus Theme

> WordPress Theme untuk Perguruan Tinggi di Indonesia

[![WordPress](https://img.shields.io/badge/WordPress-6.x-blue)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2-green)](https://www.gnu.org/licenses/gpl-2.0.html)

## Deskripsi

EduCampus adalah theme WordPress custom yang dirancang khusus untuk institusi pendidikan tinggi di Indonesia. Dengan desain **Modern Academic Corporate**, theme ini menghadirkan tampilan profesional, terpercaya, dan sesuai dengan identitas visual kampus negeri Indonesia.

### Fitur Utama

- **Custom Post Types**: Faculty, Program Studi, Berita, Dokumen, Dosen
- **SISTER API Integration**: Sinkronisasi data dosen dari sistem SISTER Kemendikbudristek
- **Demo Content Import**: Import konten demo dengan sekali klik
- **Mobile-First Responsive**: Tampilan optimal di semua perangkat
- **Customizer Settings**: Pengaturan theme tanpa coding
- **SEO Optimized**: Struktur markup yang SEO-friendly
- **Translation Ready**: Support untuk multilingual

## Instalasi

### Cara Pasang Theme

1. Download atau clone repository ini
2. Upload folder `educamp-theme` ke `wp-content/themes/` di server WordPress
3. Aktifkan theme melalui **Appearance → Themes** di dashboard WordPress
4. Selesai!

### Cara Clone dari GitHub

```bash
cd wp-content/themes/
git clone https://github.com/agustian13/educamp.git educamp-theme
```

## Konfigurasi

### 1. Menu Navigation

Buat menu melalui **Appearance → Menus**:
- **Primary Menu**: Menu utama di header
- **Footer Menu**: Menu di footer (Tautan Cepat)
- **Footer Academic Menu**: Menu akademik di footer

### 2. Custom Logo

Upload custom logo melalui **Appearance → Customize → Site Identity**

### 3. Widget Areas

- **Footer Column 1-4**: Widget area di footer

### 4. Theme Options

Akses pengaturan theme melalui **Appearance → Customize**:
- **Header Settings**: Alamat, email, quick links
- **Footer Settings**: Deskripsi, social media, kontak
- **PMB Settings**: URL dan teks tombol PMB
- **Utility Bar**: Toggle tampilan utility bar

## Custom Post Types

### Faculty (Fakultas)
- URL: `/faculty/`
- Single: `/faculty/{slug}/`
- Fields: Logo, deskripsi, program studi

### Program Studi
- URL: `/program/`
- Single: `/program/{slug}/`
- Fields: Fakultas, level, akreditasi, kurikulum

### Berita
- URL: `/berita/`
- Single: `/berita/{slug}/`
- Fields: Featured image, kategori, tag

### Dokumen
- URL: `/dokumen/`
- Single: `/dokumen/{slug}/`
- Fields: File upload, kategori dokumen

### Dosen
- URL: `/lecturer/`
- Single: `/lecturer/{slug}/`
- Fields: Foto, NIDN, bidang ilmu, riwayat pendidikan

## SISTER API Integration

Theme terintegrasi dengan **SISTER (Sistem Informasi Sumber Daya Institusi Perguruan Tinggi)** dari Kemendikbudristek.

### Fitur SISTER
- Sinkronisasi data dosen (profil, kepegawaian, pendidikan)
- Download foto dosen
- Chunked sync untuk data besar
- Auto-retry pada error 401

### Konfigurasi SISTER

1. Buka **Dashboard → SISTER Settings**
2. Masukkan URL API, Username, Password, dan ID Pengguna
3. Klik **Test Connection** untuk verifikasi
4. Klik **Sync Data** untuk sinkronisasi

### Endpoint yang Didukung

| Endpoint | Fungsi |
|----------|--------|
| `/referensi/sdm` | Daftar seluruh SDM |
| `/data_pribadi/profil/{id}` | Profil dosen |
| `/data_pribadi/kepegawaian/{id}` | Data kepegawaian |
| `/pendidikan_formal` | Riwayat pendidikan |
| `/penelitian` | Data penelitian |
| `/publikasi` | Data publikasi |
| `/pengabdian` | Data pengabdian |
| `/dokumen` | Data dokumen |

## Demo Content Import

### Cara Import

1. Buka **Dashboard → Tools → Import**
2. Pilih **WordPress** → **Run Importer**
3. Upload file `demo-content/demo-content.xml`
4. Centang **Download and import file attachments**
5. Klik **Submit**

### Yang Di-import
- Halaman statis (Beranda, Profil, PMB, Kontak, dll)
- Custom Post Types (Faculty, Program, News, Dokumen, Lecturer)
- Menu navigasi
- Widget settings
- Theme options

## Struktur Folder

```
educamp-theme/
├── style.css                  # Theme definition + CSS tokens
├── functions.php              # Theme functions
├── index.php                  # Main template
├── header.php                 # Site header
├── footer.php                 # Site footer
├── front-page.php             # Homepage template
├── 404.php                    # 404 error page
├── search.php                 # Search results
├── searchform.php             # Search form
├── screenshot.png             # Theme screenshot
│
├── page-*.php                 # Custom page templates
│   ├── page-profil.php
│   ├── page-pmb.php
│   ├── page-sejarah.php
│   ├── page-visi-misi.php
│   ├── page-identitas.php
│   ├── page-jati-diri.php
│   ├── page-kalender-akademik.php
│   └── page-struktur-organisasi.php
│
├── archive-*.php              # Archive templates
│   ├── archive-faculty.php
│   ├── archive-program.php
│   ├── archive-news.php
│   ├── archive-dokumen.php
│   └── archive-lecturer.php
│
├── single-*.php               # Single post templates
│   ├── single-faculty.php
│   ├── single-program.php
│   ├── single-news.php
│   ├── single-dokumen.php
│   └── single-lecturer.php
│
├── taxonomy-*.php             # Taxonomy templates
│   ├── taxonomy-dokumen_category.php
│   └── taxonomy-program_level.php
│
├── assets/
│   ├── css/                   # Custom CSS
│   ├── js/                    # Custom JavaScript
│   ├── images/                # Theme images
│   └── vendor/                # Third-party libraries
│
├── includes/
│   ├── cpts.php               # Custom Post Types registration
│   ├── customizer.php         # Customizer settings
│   ├── block-patterns.php     # Block patterns
│   ├── demo-importer.php      # Demo content importer
│   ├── sister-api.php         # SISTER API service class
│   ├── sister-admin.php       # SISTER admin handlers
│   └── kalender-admin.php     # Calendar admin
│
├── template-parts/
│   ├── section-*.php          # Homepage sections
│   └── single-news-*.php      # News single variants
│
├── admin-templates/
│   ├── sister-settings.php    # SISTER settings UI
│   └── kalender-settings.php  # Calendar settings UI
│
├── demo-content/
│   ├── demo-content.xml       # WordPress WXR export
│   ├── demo-settings.json     # Demo settings
│   └── generate-*.php         # Generator scripts
│
└── languages/
    └── educampus.pot          # Translation template
```

## Webhook Auto-Deploy

### Cara Kerja

```
Push ke GitHub → GitHub kirim webhook → Receiver download file via API → Theme ter-update
```

### Setup

1. **Upload webhook receiver** ke document root server:
   ```bash
   # Upload webhook-receiver.php ke folder yang sama dengan wp-config.php
   ```

2. **Buka GitHub Repository Settings**:
   - Buka `https://github.com/agustian13/educamp/settings/hooks`
   - Klik **Add webhook**

3. **Isi form webhook**:
   | Field | Value |
   |-------|-------|
   | Payload URL | `https://domain.com/webhook-receiver.php` |
   | Content type | `application/json` |
   | Secret | (generate random 128 char) |
   | Events | Just the push event |

4. **Simpan secret token** di `webhook-receiver.php`

### Endpoint

| Method | URL | Fungsi |
|--------|-----|--------|
| POST | `/webhook-receiver.php` | Terima webhook dari GitHub |
| GET | `/webhook-receiver.php?status=check` | Cek status deploy terakhir |
| GET | `/webhook-receiver.php?token=XXX&force=full` | Force full sync |

### Log

Semua aktivitas deploy tercatat di `deploy-log.txt`

## Development

### Local Development

```bash
# Clone repository
git clone https://github.com/agustian13/educamp.git educamp-theme

# Edit files
# ...

# Test di local WordPress
# ...

# Commit & Push
git add .
git commit -m "feat: deskripsi perubahan"
git push origin main
```

### Code Style

- **PHP**: WordPress Coding Standards
- **CSS**: BEM-like naming dengan CSS custom properties
- **JavaScript**: Vanilla JS (tidak ada framework)
- **Naming**: `educampus_` prefix untuk semua functions

### File Naming

| Tipe | Format | Contoh |
|------|--------|--------|
| Page template | `page-{slug}.php` | `page-pmb.php` |
| Archive | `archive-{cpt}.php` | `archive-faculty.php` |
| Single | `single-{cpt}.php` | `single-news.php` |
| Taxonomy | `taxonomy-{taxonomy}.php` | `taxonomy-program_level.php` |
| Template part | `section-{name}.php` | `section-hero.php` |

## Requirements

- **WordPress**: 6.0 atau lebih tinggi
- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 5.7 atau lebih tinggi
- **Web Server**: Apache/Nginx dengan mod_rewrite

## License

GPL v2 or later - [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

## Author

**Agustian** - [agussoft.id](https://agussoft.id/)

---

Dibuat dengan ❤️ untuk pendidikan Indonesia
