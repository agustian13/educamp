<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$wp_load = '';
$paths = array(
    __DIR__ . '/../../../wp-load.php',
    __DIR__ . '/../../../../wp-load.php',
    __DIR__ . '/../../../../../wp-load.php',
    __DIR__ . '/../../campus/wp-load.php',
    __DIR__ . '/../../../campus/wp-load.php',
);
foreach ($paths as $p) {
    if (file_exists($p)) { $wp_load = $p; break; }
}
if (!$wp_load) die("wp-load.php not found\n");
require_once $wp_load;

// Options to export - prefix with {{SITE_URL}} placeholder
$option_keys = array(
    'theme_mods_educamp-theme',
    'sidebars_widgets',
    'widget_block',
    'widget_search',
    'widget_text',
    'widget_custom_html',
    'widget_nav_menu',
    'nav_menu_options',
    'siteurl',
    'home',
    'blogname',
    'blogdescription',
    'category_base',
    'permalink_structure',
    'posts_per_page',
    'show_on_front',
    'page_on_front',
    'page_for_posts',
    'sticky_posts',
);

$settings = array();
$current_url = rtrim(get_bloginfo('url'), '/');

foreach ($option_keys as $key) {
    $val = get_option($key);
    if ($val !== false) {
        // Replace URLs in serialized data
        $settings[$key] = $val;
    }
}

// Replace URLs recursively
array_walk_recursive($settings, function(&$v, $k) use ($current_url) {
    if (is_string($v) && strpos($v, $current_url) !== false) {
        $v = str_replace($current_url, '{{SITE_URL}}', $v);
    }
});

file_put_contents(__DIR__ . '/demo-settings.json', json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "Settings exported: " . filesize(__DIR__ . '/demo-settings.json') . " bytes\n";
echo "Done.\n";
