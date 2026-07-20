<?php
/**
 * Plugin Name: WebP Upload
 * Plugin URI:  
 * Description: Konversi otomatis gambar yang diupload ke format WebP. Lebih ringan, loading lebih cepat.
 * Version:     1.0.0
 * Author:      
 * Text Domain: webp-upload
 */

defined('ABSPATH') || exit;

define('WEBPUPLOAD_VERSION', '1.0.0');
define('WEBPUPLOAD_FILE', __FILE__);
define('WEBPUPLOAD_PATH', plugin_dir_path(__FILE__));
define('WEBPUPLOAD_URL', plugin_dir_url(__FILE__));

register_activation_hook(__FILE__, 'webpupload_activate');
register_deactivation_hook(__FILE__, 'webpupload_deactivate');

function webpupload_activate() {
    if (!extension_loaded('gd') || !function_exists('imagewebp')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Server tidak mendukung WebP. Dibutuhkan GD library dengan fungsi imagewebp().');
    }

    if (get_option('webpupload_htaccess', 1)) {
        webpupload_add_htaccess();
    }
}

function webpupload_deactivate() {
    webpupload_remove_htaccess();
}

require_once WEBPUPLOAD_PATH . 'includes/class-converter.php';
require_once WEBPUPLOAD_PATH . 'includes/class-serve.php';
require_once WEBPUPLOAD_PATH . 'includes/class-settings.php';

function webpupload_init() {
    new WebPUpload_Converter();
    new WebPUpload_Serve();

    if (is_admin()) {
        new WebPUpload_Settings();
    }
}
add_action('plugins_loaded', 'webpupload_init');

function webpupload_get_htaccess_rules() {
    return <<<APACHE
# BEGIN WebP Upload
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteCond %{REQUEST_FILENAME} (?i)\.(jpe?g|png|gif)$
    RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} (?i)^(.+)\.(jpe?g|png|gif)$
    RewriteCond %1.webp -f
    RewriteRule (?i)^(.+)\.(jpe?g|png|gif)$ $1.webp [T=image/webp,L]
</IfModule>
<IfModule mod_headers.c>
    Header append Vary Accept env=REDIRECT_ACCEPT
</IfModule>
# END WebP Upload
APACHE;
}

function webpupload_add_htaccess() {
    $htaccess_path = ABSPATH . '.htaccess';
    if (!file_exists($htaccess_path) || !is_writable($htaccess_path)) {
        return false;
    }

    $content = file_get_contents($htaccess_path);
    if (strpos($content, '# BEGIN WebP Upload') !== false) {
        return true;
    }

    $rules = "\n\n" . webpupload_get_htaccess_rules() . "\n";
    if (preg_match('/# BEGIN WordPress/', $content, $m, PREG_OFFSET_CAPTURE)) {
        $content = substr_replace($content, $rules, $m[0][1], 0);
    } else {
        $content .= $rules;
    }

    file_put_contents($htaccess_path, $content);
    return true;
}

function webpupload_remove_htaccess() {
    $htaccess_path = ABSPATH . '.htaccess';
    if (!file_exists($htaccess_path) || !is_writable($htaccess_path)) {
        return false;
    }

    $content = file_get_contents($htaccess_path);
    $content = preg_replace('/\n*# BEGIN WebP Upload.*?# END WebP Upload\n*/s', '', $content);
    file_put_contents($htaccess_path, $content);
    return true;
}
