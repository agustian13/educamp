<?php
/**
 * EduCampus Auto Import - Simple Version
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 300);

echo "<h2>EduCampus Demo Import</h2>";

// Load WordPress
$wp_path = dirname(__DIR__, 2) . '/wp-load.php';
if (file_exists($wp_path)) {
    require_once $wp_path;
    echo "<p>✅ WordPress loaded: " . home_url() . "</p>";
} else {
    die("❌ WordPress tidak ditemukan di: " . dirname(__DIR__, 2));
}

// Check if import is running
if (isset($_POST['run_import'])) {
    run_import();
} else {
    show_form();
}

function show_form() {
    ?>
    <form method="post">
        <input type="hidden" name="run_import" value="1">
        <button type="submit" style="padding:15px 30px;font-size:16px;background:#0a2540;color:white;border:none;border-radius:8px;cursor:pointer;">
            Mulai Import Demo Content
        </button>
    </form>
    <p style="margin-top:20px;color:#666;">Klik tombol di atas untuk memulai import halaman, dosen, fakultas, program studi, dan media.</p>
    <?php
}

function run_import() {
    global $wpdb;
    
    $xml_file = __DIR__ . '/demo-content.xml';
    
    if (!file_exists($xml_file)) {
        echo "<p style='color:red;'>❌ File demo-content.xml tidak ditemukan</p>";
        return;
    }
    
    echo "<p>📄 Reading XML file...</p>";
    $xml = file_get_contents($xml_file);
    $xml = str_replace('{{SITE_URL}}', home_url(), $xml);
    
    // Extract items
    preg_match_all('/<item>(.*?)<\/item>/s', $xml, $matches);
    $items = $matches[1];
    
    echo "<p>📦 Ditemukan " . count($items) . " item</p>";
    
    // Separate by type
    $pages = array();
    $posts = array();
    $attachments = array();
    $cpts = array();
    
    foreach ($items as $item) {
        preg_match('/<wp:post_type>(.*?)<\/wp:post_type>/', $item, $type);
        $post_type = $type[1] ?? '';
        
        if ($post_type === 'page') $pages[] = $item;
        elseif ($post_type === 'post') $posts[] = $item;
        elseif ($post_type === 'attachment') $attachments[] = $item;
        elseif (in_array($post_type, ['faculty','program','news','dokumen','lecturer'])) $cpts[] = $item;
    }
    
    echo "<p>📄 Pages: " . count($pages) . "</p>";
    echo "<p>📰 Posts: " . count($posts) . "</p>";
    echo "<p>🖼️ Media: " . count($attachments) . "</p>";
    echo "<p>📋 CPTs: " . count($cpts) . "</p>";
    
    $id_map = array();
    $imported = 0;
    
    // Import Pages
    echo "<h3>=== Importing Pages ===</h3>";
    foreach ($pages as $item) {
        $result = import_item($item, 'page', $id_map);
        if ($result) {
            $imported++;
            echo "<p style='color:green;'>✅ " . $result['title'] . " (ID: " . $result['id'] . ")</p>";
        }
    }
    
    // Import CPTs
    echo "<h3>=== Importing Custom Post Types ===</h3>";
    foreach ($cpts as $item) {
        preg_match('/<wp:post_type>(.*?)<\/wp:post_type>/', $item, $type);
        $result = import_item($item, $type[1], $id_map);
        if ($result) {
            $imported++;
            echo "<p style='color:green;'>✅ [" . $type[1] . "] " . $result['title'] . " (ID: " . $result['id'] . ")</p>";
        }
    }
    
    echo "<h3>✅ Import Selesai!</h3>";
    echo "<p>Total: {$imported} item di-import</p>";
    echo "<p><a href='" . admin_url() . "'>Buka Dashboard</a> | <a href='" . home_url() . "'>Lihat Website</a></p>";
}

function import_item($item, $post_type, &$id_map) {
    // Extract data
    preg_match('/<title>(.*?)<\/title>/', $item, $title);
    preg_match('/<wp:post_name>(.*?)<\/wp:post_name>/', $item, $slug);
    preg_match('/<wp:post_id>(.*?)<\/wp:post_id>/', $item, $old_id);
    preg_match('/<wp:post_parent>(.*?)<\/wp:post_parent>/', $item, $parent);
    preg_match('/<wp:menu_order>(.*?)<\/wp:menu_order>/', $item, $menu_order);
    
    // Extract content
    $content = '';
    if (preg_match('/<content:encoded><!\[CDATA\[(.*?)\]\]><\/content:encoded>/s', $item, $c)) {
        $content = $c[1];
    }
    
    // Extract template
    $template = '';
    if (preg_match('/_wp_page_template.*?<wp:meta_value><!\[CDATA\[(.*?)\]\]>/s', $item, $t)) {
        $template = $t[1];
    }
    
    $title_text = $title[1] ?? 'Untitled';
    $slug_text = $slug[1] ?? sanitize_title($title_text);
    
    // Check if exists
    $existing = get_page_by_path($slug_text);
    
    $post_data = array(
        'post_title'    => $title_text,
        'post_name'     => $slug_text,
        'post_content'  => $content,
        'post_type'     => $post_type,
        'post_status'   => 'publish',
        'menu_order'    => intval($menu_order[1] ?? 0),
    );
    
    if ($template) {
        $post_data['page_template'] = $template;
    }
    
    if ($existing) {
        $post_data['ID'] = $existing->ID;
        $new_id = wp_update_post($post_data);
    } else {
        $new_id = wp_insert_post($post_data);
    }
    
    if ($new_id && !is_wp_error($new_id)) {
        $id_map[$old_id[1] ?? 0] = $new_id;
        
        // Save meta
        preg_match_all('/<wp:meta_key>(.*?)<\/wp:meta_key>.*?<wp:meta_value>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/wp:meta_value>/s', $item, $metas, PREG_SET_ORDER);
        foreach ($metas as $m) {
            if (!in_array($m[1], ['_wp_page_template', '_edit_lock', '_edit_last'])) {
                update_post_meta($new_id, $m[1], $m[2]);
            }
        }
        
        return array('id' => $new_id, 'title' => $title_text);
    }
    
    return null;
}
