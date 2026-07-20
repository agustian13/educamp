<?php
/**
 * EduCampus - Fix Unit Groups Assignment
 * Assign units to unit_group taxonomy based on position/title
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

// Get all units
$units = get_posts(array(
    'post_type' => 'unit',
    'numberposts' => -1,
    'post_status' => 'publish',
));

echo "<p>📋 Total units: " . count($units) . "</p>";

// Get or create unit_group terms
$groups = array(
    'rektorat'       => 'Pimpinan Rektorat',
    'dekanat'        => 'Dekanat & Pascasarjana',
    'fakultas'       => 'Fakultas',
    'lembaga-biro'   => 'Lembaga & Biro',
    'upt'            => 'UPT',
    'senat'          => 'Senat Akademik',
    'yayasan'        => 'Pembina Yayasan',
    'prodi'          => 'Program Studi',
    'dosen'          => 'Dosen',
    'tendik'         => 'Tenaga Kependidikan',
    'lainnya'        => 'Lainnya',
);

$group_ids = array();
foreach ($groups as $slug => $name) {
    $term = term_exists($slug, 'unit_group');
    if (!$term) {
        $term = wp_insert_term($name, 'unit_group', array('slug' => $slug));
    }
    if (!is_wp_error($term)) {
        $group_ids[$slug] = is_array($term) ? $term['term_id'] : $term;
    }
}

echo "<p>✅ Unit groups ready: " . count($group_ids) . "</p>";

// Assignment rules
$assigned = 0;
$skipped = 0;

foreach ($units as $unit) {
    $position = strtolower(get_post_meta($unit->ID, '_unit_position_title', true));
    $title = strtolower($unit->post_title);
    $parent_id = $unit->post_parent;
    
    // Determine group based on position/title
    $group_slug = 'lainnya';
    
    // Check position patterns
    if (preg_match('/rektor|wakil rektor/i', $position)) {
        $group_slug = 'rektorat';
    } elseif (preg_match('/dekan|wakil dekan/i', $position)) {
        $group_slug = 'dekanat';
    } elseif (preg_match('/pascasarjana|sekolah pascasarjana/i', $position)) {
        $group_slug = 'dekanat';
    } elseif (preg_match('/ketua prodi|sekretaris prodi|prodi/i', $position)) {
        $group_slug = 'prodi';
    } elseif (preg_match('/lembaga|biro|pusat|unit pelaksana/i', $position)) {
        $group_slug = 'lembaga-biro';
    } elseif (preg_match('/upt|unit pelayanan/i', $position)) {
        $group_slug = 'upt';
    } elseif (preg_match('/senat|majelis/i', $position)) {
        $group_slug = 'senat';
    } elseif (preg_match('/yayasan|pembina/i', $position)) {
        $group_slug = 'yayasan';
    } elseif (preg_match('/dosen/i', $position) || !empty(get_post_meta($unit->ID, '_unit_nidn', true))) {
        $group_slug = 'dosen';
    } elseif (preg_match('/staff|tenaga|tendik|administrasi/i', $position)) {
        $group_slug = 'tendik';
    }
    
    // If still 'lainnya', check by parent hierarchy
    if ($group_slug === 'lainnya' && $parent_id > 0) {
        $parent_position = strtolower(get_post_meta($parent_id, '_unit_position_title', true));
        if (preg_match('/fakultas/i', $parent_position)) {
            $group_slug = 'fakultas';
        }
    }
    
    // Assign to group
    if (isset($group_ids[$group_slug])) {
        wp_set_object_terms($unit->ID, $group_ids[$group_slug], 'unit_group');
        $assigned++;
        echo "<p style='color:green;'>✅ {$unit->post_title} → {$groups[$group_slug]}</p>";
    } else {
        $skipped++;
    }
}

echo "<h3>✅ Selesai!</h3>";
echo "<p>Assigned: {$assigned} units</p>";
echo "<p>Skipped: {$skipped} units</p>";
echo "<p><a href='" . admin_url("edit.php?post_type=unit") . "'>Lihat Units</a></p>";
