=== WebP Upload ===
Contributors: webpupload
Tags: webp, image optimization, performance, compression
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Konversi otomatis gambar JPEG/PNG/GIF ke format WebP untuk performa loading lebih cepat.

== Description ==

WebP Upload memudahkan Anda dalam mengoptimasi gambar di WordPress. Setiap kali gambar diupload, plugin ini akan otomatis membuat versi WebP yang jauh lebih ringan tanpa menghapus file asli.

**Fitur Utama:**
* Konversi otomatis saat upload gambar (JPEG, PNG, GIF)
* Membuat versi WebP untuk semua ukuran thumbnail (thumbnail, medium, large, dll)
* Menyajikan WebP ke browser yang mendukung via `<picture>` tag
* Penyajian via .htaccess rewrite (opsional, untuk hosting Apache)
* Settings page: atur kualitas kompresi (1-100%)
* Bulk convert: konversi semua gambar existing di Media Library
* Bersih otomatis: file WebP ikut terhapus saat gambar asli dihapus

**Kenapa WebP?**
* WebP lossy 25-35% lebih kecil dari JPEG dengan kualitas visual setara
* WebP lossless 26% lebih kecil dari PNG
* Didukung semua browser modern (Chrome, Firefox, Edge, Safari 14+)

== Installation ==

1. Upload folder `webp-upload` ke folder `/wp-content/plugins/`
2. Aktifkan plugin melalui menu Plugins di WordPress
3. Buka Settings > WebP Upload untuk mengatur kualitas kompresi
4. (Opsional) Klik "Mulai Konversi" untuk mengkonversi gambar yang sudah ada

== Frequently Asked Questions ==

= Apakah file asli gambar tetap disimpan? =

Ya. Plugin ini tidak menghapus gambar asli. File WebP dibuat berdampingan sebagai versi alternatif.

= Bagaimana cara kerja penyajian WebP? =

Ada 2 metode yang aktif bersamaan:
1. **Filter Konten**: Plugin mengganti tag `<img>` di konten dengan `<picture>` yang menyertakan WebP sebagai prioritas, dengan fallback ke format asli untuk browser lama.
2. **.htaccess Rewrite** (opsional): Jika diaktifkan, browser yang mendukung WebP akan otomatis menerima file WebP tanpa perlu modifikasi HTML.

= Apakah semua browser mendukung WebP? =

Hampir semua browser modern mendukung WebP: Chrome 32+, Firefox 65+, Edge 18+, Safari 14+. Browser yang tidak mendukung akan otomatis menerima file asli (JPEG/PNG/GIF).

= Bagaimana jika hosting tidak mendukung GD library? =

Plugin memerlukan GD library dengan WebP support. Jika tidak tersedia, plugin tidak akan aktif dan menampilkan pesan error.

= Berapa kualitas optimal untuk WebP? =

Default 80% adalah nilai optimal. Kualitas visual hampir identik dengan JPEG asli, namun ukuran file 25-35% lebih kecil. Untuk gambar yang membutuhkan kualitas lebih tinggi (misal: portofolio fotografi), gunakan 90-100%.

== Screenshots ==

1. Settings page - pengaturan kualitas dan .htaccess
2. Status sistem dan bulk convert
3. Perbandingan ukuran file JPEG vs WebP

== Changelog ==

= 1.0.0 =
* Initial release
* Konversi otomatis saat upload
* Penyajian via picture tag dan .htaccess
* Settings page dengan quality control
* Bulk convert untuk gambar existing

== Upgrade Notice ==

= 1.0.0 =
Rilis pertama. Konversi otomatis semua gambar ke WebP.
