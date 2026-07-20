<?php
/**
 * EduCampus - Re-link Images Script
 * Menghubungkan gambar yang sudah di-upload ke post yang benar
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load WordPress
$wp_path = dirname(__DIR__, 2) . '/wp-load.php';
if (file_exists($wp_path)) {
    require_once $wp_path;
    echo "<p>✅ WordPress loaded</p>";
} else {
    die("❌ WordPress tidak ditemukan");
}

if (isset($_POST['run_relink'])) {
    run_relink();
} else {
    ?>
    <h2>Re-link Demo Images</h2>
    <p>Script ini akan menghubungkan gambar demo ke post yang benar berdasarkan nama file.</p>
    <form method="post">
        <input type="hidden" name="run_relink" value="1">
        <button type="submit" style="padding:15px 30px;font-size:16px;background:#0a2540;color:white;border:none;border-radius:8px;cursor:pointer;">
            Mulai Re-link Images
        </button>
    </form>
    <?php
}

function run_relink() {
    $uploads = wp_upload_dir();
    $upload_path = $uploads['basedir'];
    
    echo "<h3>Scanning uploads folder...</h3>";
    
    // Find all images
    $images = array();
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_path));
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file->getFilename())) {
            $images[] = $file->getPathname();
        }
    }
    
    echo "<p>Ditemukan " . count($images) . " gambar</p>";
    
    // Get all posts
    $posts = get_posts(array(
        'post_type' => array('page', 'post', 'faculty', 'program', 'news', 'dokumen', 'lecturer'),
        'numberposts' => -1,
        'post_status' => 'publish',
    ));
    
    echo "<p>Ditemukan " . count($posts) . " posts</p>";
    
    // Build post map by slug and ID
    $post_map = array();
    foreach ($posts as $post) {
        $post_map[$post->ID] = $post;
        $post_map[$post->post_name] = $post;
    }
    
    $linked = 0;
    $skipped = 0;
    
    // Process each image
    foreach ($images as $image_path) {
        $filename = basename($image_path);
        $relative_path = str_replace($upload_path . '/', '', $image_path);
        
        // Skip thumbnails (already handled by WordPress)
        if (preg_match('/-\d+x\d+\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
            continue;
        }
        
        // Check if image already has attachment
        $existing_attach = get_posts(array(
            'post_type' => 'attachment',
            'meta_key' => '_wp_attached_file',
            'meta_value' => $relative_path,
            'numberposts' => 1,
        ));
        
        if (!empty($existing_attach)) {
            // Already has attachment, skip
            $skipped++;
            continue;
        }
        
        // Try to find parent post from filename
        // Pattern: featured-129-photo-xxx.jpg => post ID 129
        $parent_id = 0;
        
        if (preg_match('/featured-(\d+)-/', $filename, $matches)) {
            $candidate_id = intval($matches[1]);
            if (isset($post_map[$candidate_id])) {
                $parent_id = $candidate_id;
            }
        }
        
        // Pattern: sister-380-name.jpg => post ID 380
        if (preg_match('/sister-(\d+)-/', $filename, $matches)) {
            $candidate_id = intval($matches[1]);
            if (isset($post_map[$candidate_id])) {
                $parent_id = $candidate_id;
            }
        }
        
        // Pattern: post-name.jpg => find by slug
        if ($parent_id === 0) {
            $slug_from_file = preg_replace('/-\d+x\d+\.(jpg|jpeg|png|gif|webp)$/i', '', $filename);
            $slug_from_file = preg_replace('/^(featured|sister|photo)-\d+-/', '', $slug_from_file);
            
            if (isset($post_map[$slug_from_file])) {
                $parent_id = $post_map[$slug_from_file]->ID;
            }
        }
        
        // Get MIME type
        $mime_types = array(
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        );
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mime_type = $mime_types[$ext] ?? 'image/jpeg';
        
        // Create attachment post
        $attach_id = wp_insert_post(array(
            'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_mime_type' => $mime_type,
            'post_status' => 'inherit',
            'post_parent' => $parent_id,
            'post_content' => '',
        ));
        
        if ($attach_id && !is_wp_error($attach_id)) {
            update_post_meta($attach_id, '_wp_attached_file', $relative_path);
            
            // Try to get image size
            $size = @getimagesize($image_path);
            if ($size) {
                $metadata = array(
                    'width' => $size[0],
                    'height' => $size[1],
                    'file' => $relative_path,
                );
                update_post_meta($attach_id, '_wp_attachment_metadata', $metadata);
            }
            
            $parent_title = $parent_id > 0 ? $post_map[$parent_id]->post_title : 'none';
            echo "<p style='color:green;'>✅ Linked: {$filename} → post {$parent_id} ({$parent_title})</p>";
            $linked++;
        }
    }
    
    echo "<h3>✅ Selesai!</h3>";
    echo "<p>Linked: {$linked} gambar</p>";
    echo "<p>Skipped: {$skipped} gambar (sudah ada attachment)</p>";
    echo "<p><a href='" . home_url() . "'>Lihat Website</a></p>";
}
