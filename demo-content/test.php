<?php
echo "PHP Version: " . phpversion() . "<br>";
echo "ABSPATH: " . (defined('ABSPATH') ? ABSPATH : 'NOT DEFINED') . "<br>";

// Coba load WordPress
$wp_path = dirname(__DIR__, 2) . '/wp-load.php';
echo "wp-load.php path: " . realpath($wp_path) . "<br>";
echo "wp-load.php exists: " . (file_exists($wp_path) ? 'YES' : 'NO') . "<br>";

if (file_exists($wp_path)) {
    require_once $wp_path;
    echo "WordPress loaded: YES<br>";
    echo "Site URL: " . home_url() . "<br>";
} else {
    echo "WordPress loaded: NO<br>";
}
