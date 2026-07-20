<?php
/**
 * EduCampus - Import Units with Hierarchy & Taxonomy
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$wp_path = dirname(__DIR__, 2) . '/wp-load.php';
if (file_exists($wp_path)) {
    require_once $wp_path;
    echo "<p>✅ WordPress loaded: " . home_url() . "</p>";
} else {
    die("❌ WordPress tidak ditemukan");
}

// Only run if called with POST
if (php_sapi_name() !== 'cli' && !isset($_POST['run_import'])) {
    ?>
    <h2>Import Units (Lengkap dengan Kategori & Hierarki)</h2>
    <p>Import 49 unit organisasi dengan hierarki parent-child dan taxonomy unit_group.</p>
    <form method="post">
        <input type="hidden" name="run_import" value="1">
        <button type="submit" style="padding:15px 30px;font-size:16px;background:#0a2540;color:white;border:none;border-radius:8px;cursor:pointer;">
            Mulai Import Units
        </button>
    </form>
    <?php
    exit;
}

function run_import() {
    $xml_file = __DIR__ . '/demo-content.xml';
    
    if (!file_exists($xml_file)) {
        echo "<p style='color:red;'>❌ demo-content.xml tidak ditemukan</p>";
        return;
    }
    
    $xml = file_get_contents($xml_file);
    $xml = str_replace('{{SITE_URL}}', home_url(), $xml);
    
    // Extract ALL items
    preg_match_all('/<item>(.*?)<\/item>/s', $xml, $matches);
    $all_items = $matches[1];
    
    // Build ID map from existing content
    $existing_posts = get_posts(array(
        'post_type' => 'any',
        'numberposts' => -1,
        'post_status' => 'publish',
    ));
    
    $old_to_new = array();
    foreach ($existing_posts as $p) {
        // Try to match by slug
        $old_to_new[$p->post_name] = $p->ID;
    }
    
    echo "<p>📦 Total items in XML: " . count($all_items) . "</p>";
    echo "<p>📋 Existing posts: " . count($existing_posts) . "</p>";
    
    // Filter unit items
    $unit_items = array();
    foreach ($all_items as $item) {
        if (preg_match('/<wp:post_type>unit<\/wp:post_type>/', $item)) {
            $unit_items[] = $item;
        }
    }
    
    echo "<p>🏢 Unit items: " . count($unit_items) . "</p>";
    
    // =====================================================
    // STEP 1: Create unit_group taxonomy terms
    // =====================================================
    echo "<h3>Step 1: Creating unit_group taxonomy terms...</h3>";
    
    // Define groups based on the unit structure
    $groups = array(
        'rektorat' => 'Pimpinan Rektorat',
        'dekanat' => 'Dekanat & Pascasarjana',
        'fakultas' => 'Fakultas',
        'lembaga-biro' => 'Lembaga & Biro',
        'upt' => 'UPT',
        'senat' => 'Senat Akademik',
        'yayasan' => 'Pembina Yayasan',
        'dosen' => 'Dosen',
        'tendik' => 'Tenaga Kependidikan',
        'lainnya' => 'Lainnya',
    );
    
    $group_ids = array();
    foreach ($groups as $slug => $name) {
        $term = term_exists($slug, 'unit_group');
        if (!$term) {
            $term = wp_insert_term($name, 'unit_group', array('slug' => $slug));
            if (!is_wp_error($term)) {
                $group_ids[$slug] = $term['term_id'];
                echo "<p style='color:green;'>✅ Created group: {$name}</p>";
            }
        } else {
            $group_ids[$slug] = $term['term_id'];
            echo "<p>ℹ️ Group exists: {$name}</p>";
        }
    }
    
    // =====================================================
    // STEP 2: Import units (parent first, then children)
    // =====================================================
    echo "<h3>Step 2: Importing units...</h3>";
    
    // Separate parent units (parent=0) and child units
    $parent_units = array();
    $child_units = array();
    
    foreach ($unit_items as $item) {
        preg_match('/<wp:post_parent>(.*?)<\/wp:post_parent>/', $item, $parent);
        if ($parent[1] == 0) {
            $parent_units[] = $item;
        } else {
            $child_units[] = $item;
        }
    }
    
    echo "<p>📊 Parent units: " . count($parent_units) . "</p>";
    echo "<p>📊 Child units: " . count($child_units) . "</p>";
    
    $id_map = array();
    $imported = 0;
    
    // Import parent units first
    foreach ($parent_units as $item) {
        $result = import_unit($item, $id_map, $old_to_new, $group_ids);
        if ($result) {
            $imported++;
            echo "<p style='color:green;'>✅ [PARENT] {$result['title']} (ID: {$result['id']})</p>";
        }
    }
    
    // Import child units
    foreach ($child_units as $item) {
        $result = import_unit($item, $id_map, $old_to_new, $group_ids);
        if ($result) {
            $imported++;
            echo "<p style='color:green;'>✅ [CHILD] {$result['title']} (ID: {$result['id']})</p>";
        }
    }
    
    // =====================================================
    // STEP 3: Update parent relationships
    // =====================================================
    echo "<h3>Step 3: Updating parent relationships...</h3>";
    
    $updated = 0;
    foreach ($unit_items as $item) {
        preg_match('/<wp:post_id>(.*?)<\/wp:post_id>/', $item, $old_id);
        preg_match('/<wp:post_parent>(.*?)<\/wp:post_parent>/', $item, $parent);
        
        if ($old_id && $parent && $parent[1] > 0) {
            $new_id = $id_map[$old_id[1]] ?? 0;
            $new_parent = $id_map[$parent[1]] ?? 0;
            
            if ($new_id && $new_parent) {
                wp_update_post(array(
                    'ID' => $new_id,
                    'post_parent' => $new_parent,
                ));
                $updated++;
            }
        }
    }
    
    echo "<p>Updated {$updated} parent relationships</p>";
    
    // =====================================================
    // STEP 4: Assign units to unit_group based on position
    // =====================================================
    echo "<h3>Step 4: Assigning units to unit_group...</h3>";
    
    $assigned = 0;
    foreach ($id_map as $old_id => $new_id) {
        $position = get_post_meta($new_id, '_unit_position_title', true);
        $title = get_the_title($new_id);
        $parent_id = wp_get_post_parent_id($new_id);
        
        // Determine group based on position/title
        $group_slug = 'lainnya'; // default
        
        $position_lower = strtolower($position . ' ' . $title);
        
        if (preg_match('/rektor|wakil rektor/i', $position_lower)) {
            $group_slug = 'rektorat';
        } elseif (preg_match('/dekan|wakil dekan|pascasarjana/i', $position_lower)) {
            $group_slug = 'dekanat';
        } elseif (preg_match('/fakultas/i', $position_lower)) {
            $group_slug = 'fakultas';
        } elseif (preg_match('/lembaga|biro/i', $position_lower)) {
            $group_slug = 'lembaga-biro';
        } elseif (preg_match('/upt/i', $position_lower)) {
            $group_slug = 'upt';
        } elseif (preg_match('/senat/i', $position_lower)) {
            $group_slug = 'senat';
        } elseif (preg_match('/yayasan/i', $position_lower)) {
            $group_slug = 'yayasan';
        } elseif (preg_match('/dosen|nidn/i', $position_lower)) {
            $group_slug = 'dosen';
        } elseif (preg_match('/staff|tenaga|tendik/i', $position_lower)) {
            $group_slug = 'tendik';
        }
        
        if (isset($group_ids[$group_slug])) {
            wp_set_object_terms($new_id, $group_ids[$group_slug], 'unit_group');
            $assigned++;
        }
    }
    
    echo "<p>Assigned {$assigned} units to unit_group taxonomy</p>";
    
    echo "<h3>✅ Import Selesai!</h3>";
    echo "<p>Total: {$imported} unit di-import</p>";
    echo "<p><a href='" . admin_url("edit.php?post_type=unit") . "'>Lihat Units</a> | <a href='" . home_url() . "'>Website</a></p>";
}

function import_unit($item, &$id_map, $old_to_new, $group_ids) {
    preg_match('/<title>(.*?)<\/title>/', $item, $title);
    preg_match('/<wp:post_name>(.*?)<\/wp:post_name>/', $item, $slug);
    preg_match('/<wp:post_id>(.*?)<\/wp:post_id>/', $item, $old_id);
    preg_match('/<wp:post_parent>(.*?)<\/wp:post_parent>/', $item, $parent);
    preg_match('/<wp:menu_order>(.*?)<\/wp:menu_order>/', $item, $menu_order);
    
    $content = '';
    if (preg_match('/<content:encoded><!\[CDATA\[(.*?)\]\]><\/content:encoded>/s', $item, $c)) {
        $content = $c[1];
    }
    
    $title_text = html_entity_decode($title[1] ?? 'Untitled');
    $slug_text = $slug[1] ?? sanitize_title($title_text);
    $old_parent = $parent[1] ?? 0;
    
    // Check if already exists by slug
    $existing = get_page_by_path($slug_text, OBJECT, 'unit');
    
    $post_data = array(
        'post_title'    => $title_text,
        'post_name'     => $slug_text,
        'post_content'  => $content,
        'post_type'     => 'unit',
        'post_status'   => 'publish',
        'menu_order'    => intval($menu_order[1] ?? 0),
        'post_parent'   => 0, // Will update in step 3
    );
    
    if ($existing) {
        $post_data['ID'] = $existing->ID;
        $new_id = wp_update_post($post_data);
    } else {
        $new_id = wp_insert_post($post_data);
    }
    
    if ($new_id && !is_wp_error($new_id)) {
        $id_map[$old_id[1]] = $new_id;
        
        // Save all meta
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

run_import();
