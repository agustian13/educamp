<?php
/**
 * EduCampus Webhook Receiver
 * 
 * Menerima webhook dari GitHub dan otomatis update theme di server.
 * 
 * Endpoint:
 *   POST /webhook-receiver.php              - Terima webhook dari GitHub
 *   GET  /webhook-receiver.php?status=check  - Cek status deploy terakhir
 *   GET  /webhook-receiver.php?token=XXX&force=full - Force full sync
 * 
 * @package EduCampus
 * @version 1.0.0
 */

// =============================================================================
// KONFIGURASI - SESUAIKAN DENGAN SERVER KAMU
// =============================================================================

$CONFIG = array(
    // GitHub Repository
    'github_repo'       => 'agustian13/educamp',      // format: username/repo
    'github_branch'     => 'main',
    
    // GitHub Personal Access Token (untuk API, mengurangi rate limit)
    // Buat di: https://github.com/settings/tokens
    // Scope minimal: repo (untuk private) atau public_repo (untuk public)
    'github_token'      => '',  // kosongkan jika repo public dan tidak perlu token
    
    // Webhook Secret (harus sama dengan yang di GitHub)
    'webhook_secret'    => 'LQfMwszjbRcblSwGVRBE1VDBSrYCSY6aJbUC2nTwKoMUyFiu01DhwWQBZUvbzbUCUU140v7Q9SO0aSGF5cQQ',
    
    // Theme path (relatif dari document root)
    'theme_path'        => 'wp-content/themes/educamp-theme/',
    
    // Plugin path (relatif dari document root)
    'plugin_path'       => 'wp-content/plugins/webp-upload/',
    
    // Log file
    'log_file'          => 'deploy-log.txt',
    
    // File yang TIDAK bo di-overwrite (keamanan)
    'protected_files'   => array(
        'config.php',
        'wp-config.php',
    ),
    
    // Ekstensi file yang boleh didownload
    'allowed_extensions' => array(
        'php', 'css', 'js', 'json', 'xml', 'html',
        'svg', 'png', 'jpg', 'jpeg', 'gif', 'ico',
        'pot', 'txt', 'md', 'htaccess',
    ),
    
    // Rate limit: maksimal deploy per menit
    'rate_limit'        => 10,
    'rate_limit_window' => 60, // detik
);

// =============================================================================
// FUNCTIONS
// =============================================================================

/**
 * Tulis log message
 */
function write_log($message, $level = 'INFO') {
    global $CONFIG;
    
    $timestamp = date('Y-m-d H:i:s');
    $log_line = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    file_put_contents($CONFIG['log_file'], $log_line, FILE_APPEND | LOCK_EX);
    
    // Juga tampilkan di output untuk debugging
    if (php_sapi_name() === 'cli') {
        echo $log_line;
    }
}

/**
 * Kirim response JSON
 */
function send_response($status_code, $data) {
    http_response_code($status_code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Verifikasi signature dari GitHub
 */
function verify_webhook_signature($payload, $signature) {
    global $CONFIG;
    
    if (empty($CONFIG['webhook_secret']) || $CONFIG['webhook_secret'] === 'GANTI_DENGAN_RANDOM_128_CHAR') {
        write_log('Webhook secret belum dikonfigurasi! Deploy tanpa verifikasi.', 'WARN');
        return true; // skip verifikasi jika belum dikonfigurasi
    }
    
    if (empty($signature)) {
        return false;
    }
    
    // GitHub mengirim signature dengan format: sha256=xxx
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $CONFIG['webhook_secret']);
    
    return hash_equals($expected, $signature);
}

/**
 * HTTP request menggunakan file_get_contents atau curl
 */
function http_request($url, $headers = array()) {
    global $CONFIG;
    
    // Tambahkan Authorization header jika token tersedia
    if (!empty($CONFIG['github_token'])) {
        $headers[] = 'Authorization: token ' . $CONFIG['github_token'];
    }
    
    $headers[] = 'User-Agent: EduCampus-Webhook/1.0';
    $headers[] = 'Accept: application/vnd.github.v3+json';
    
    // Coba curl dulu
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_USERAGENT      => 'EduCampus-Webhook/1.0',
        ));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            'body'      => $response,
            'http_code' => $http_code,
        );
    }
    
    // Fallback ke file_get_contents
    $context = stream_context_create(array(
        'http' => array(
            'method'        => 'GET',
            'header'        => implode("\r\n", $headers),
            'timeout'       => 30,
            'follow_location' => true,
            'ignore_errors' => true,
        ),
    ));
    
    $response = @file_get_contents($url, false, $context);
    
    // Extract HTTP code from $http_response_header
    $http_code = 0;
    if (isset($http_response_header) && is_array($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                $http_code = (int) $matches[1];
            }
        }
    }
    
    return array(
        'body'      => $response,
        'http_code' => $http_code,
    );
}

/**
 * Download file dari GitHub
 */
function download_file($file_path) {
    global $CONFIG;
    
    $url = "https://api.github.com/repos/{$CONFIG['github_repo']}/contents/{$file_path}?ref={$CONFIG['github_branch']}";
    
    $response = http_request($url);
    
    if ($response['http_code'] !== 200) {
        return false;
    }
    
    $data = json_decode($response['body'], true);
    
    if (!$data || !isset($data['content'])) {
        return false;
    }
    
    // Decode base64 content
    $content = base64_decode($data['content']);
    
    return $content;
}

/**
 * Cek apakah file boleh di-overwrite
 */
function is_file_protected($file_path) {
    global $CONFIG;
    
    $basename = basename($file_path);
    
    return in_array($basename, $CONFIG['protected_files']);
}

/**
 * Cek apakah ekstensi file diizinkan
 */
function is_extension_allowed($file_path) {
    global $CONFIG;
    
    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    
    return in_array($ext, $CONFIG['allowed_extensions']);
}

/**
 * Tentukan path tujuan berdasarkan file path
 */
function get_deploy_path($file_path) {
    global $CONFIG;
    
    // Plugin files: starts with 'plugins/'
    if (strpos($file_path, 'plugins/') === 0) {
        $relative = preg_replace('#^plugins/webp-upload/#', '', $file_path);
        return $CONFIG['plugin_path'] . $relative;
    }
    
    // Theme files (default)
    return $CONFIG['theme_path'] . $file_path;
}

/**
 * Simpan file ke disk
 */
function save_file($file_path, $content) {
    $full_path = get_deploy_path($file_path);
    $dir = dirname($full_path);
    
    // Buat direktori jika belum ada
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Tulis file
    $result = file_put_contents($full_path, $content);
    
    return $result !== false;
}

/**
 * Hapus file
 */
function delete_file($file_path) {
    $full_path = get_deploy_path($file_path);
    
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    
    return true;
}

/**
 * Cek rate limit
 */
function check_rate_limit() {
    global $CONFIG;
    
    $rate_file = '.webhook_rate_limit';
    $now = time();
    
    // Baca timestamp sebelumnya
    $timestamps = array();
    if (file_exists($rate_file)) {
        $timestamps = json_decode(file_get_contents($rate_file), true) ?: array();
    }
    
    // Hapus timestamp yang sudah expired
    $timestamps = array_filter($timestamps, function($ts) use ($now, $CONFIG) {
        return ($now - $ts) < $CONFIG['rate_limit_window'];
    });
    
    // Cek apakah melebihi batas
    if (count($timestamps) >= $CONFIG['rate_limit']) {
        return false;
    }
    
    // Tambah timestamp baru
    $timestamps[] = $now;
    file_put_contents($rate_file, json_encode($timestamps));
    
    return true;
}

/**
 * Dapatkan status deploy terakhir
 */
function get_deploy_status() {
    global $CONFIG;
    
    $status_file = '.webhook_deploy_status';
    
    if (!file_exists($status_file)) {
        return array(
            'status'    => 'never',
            'message'   => 'Belum pernah deploy',
        );
    }
    
    $status = json_decode(file_get_contents($status_file), true);
    
    return $status ?: array(
        'status'    => 'unknown',
        'message'   => 'Status tidak diketahui',
    );
}

/**
 * Simpan status deploy
 */
function save_deploy_status($status, $message, $details = array()) {
    $status_data = array(
        'status'    => $status,
        'message'   => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'details'   => $details,
    );
    
    file_put_contents('.webhook_deploy_status', json_encode($status_data, JSON_PRETTY_PRINT));
}

// =============================================================================
// MAIN
// =============================================================================

// Load config
$config_file = __DIR__ . '/webhook-config.php';
if (file_exists($config_file)) {
    $custom_config = require $config_file;
    $CONFIG = array_merge($CONFIG, $custom_config);
}

// Handle different request methods
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// GET Request - Status check atau force sync
if ($request_method === 'GET') {
    // Cek status
    if (isset($_GET['status']) && $_GET['status'] === 'check') {
        $status = get_deploy_status();
        send_response(200, $status);
    }
    
    // Force full sync
    if (isset($_GET['force']) && $_GET['force'] === 'full') {
        // Verifikasi token sederhana
        $token = $_GET['token'] ?? '';
        if (empty($token) || $token !== md5($CONFIG['webhook_secret'])) {
            send_response(403, array('error' => 'Invalid token'));
        }
        
        write_log('Force full sync requested');
        
        // Download semua file
        $url = "https://api.github.com/repos/{$CONFIG['github_repo']}/git/trees/{$CONFIG['github_branch']}?recursive=1";
        $response = http_request($url);
        
        if ($response['http_code'] !== 200) {
            write_log('Gagal fetch tree dari GitHub', 'ERROR');
            send_response(502, array('error' => 'Gagal fetch tree dari GitHub'));
        }
        
        $tree = json_decode($response['body'], true);
        $files_updated = 0;
        $files_skipped = 0;
        $files_failed = 0;
        
        foreach ($tree['tree'] ?? array() as $item) {
            if ($item['type'] !== 'blob') continue;
            
            $file_path = $item['path'];
            
            // Skip file yang dilindungi
            if (is_file_protected($file_path)) {
                $files_skipped++;
                continue;
            }
            
            // Skip ekstensi yang tidak diizinkan
            if (!is_extension_allowed($file_path)) {
                $files_skipped++;
                continue;
            }
            
            // Download dan simpan
            $content = download_file($file_path);
            if ($content !== false) {
                if (save_file($file_path, $content)) {
                    $files_updated++;
                } else {
                    $files_failed++;
                    write_log("Gagal simpan: {$file_path}", 'ERROR');
                }
            } else {
                $files_failed++;
                write_log("Gagal download: {$file_path}", 'ERROR');
            }
        }
        
        $message = "Full sync selesai: {$files_updated} updated, {$files_skipped} skipped, {$files_failed} failed";
        write_log($message);
        save_deploy_status('success', $message, compact('files_updated', 'files_skipped', 'files_failed'));
        
        send_response(200, array(
            'status'         => 'success',
            'message'        => $message,
            'files_updated'  => $files_updated,
            'files_skipped'  => $files_skipped,
            'files_failed'   => $files_failed,
        ));
    }
    
    // Default: tampilkan info
    send_response(200, array(
        'service'   => 'EduCampus Webhook Receiver',
        'version'   => '1.0.0',
        'endpoints' => array(
            'POST /'            => 'Terima webhook dari GitHub',
            'GET ?status=check' => 'Cek status deploy terakhir',
            'GET ?token=X&force=full' => 'Force full sync',
        ),
    ));
}

// POST Request - Webhook dari GitHub
if ($request_method !== 'POST') {
    send_response(405, array('error' => 'Method not allowed'));
}

// Rate limit check
if (!check_rate_limit()) {
    write_log('Rate limit exceeded', 'WARN');
    send_response(429, array('error' => 'Rate limit exceeded. Coba lagi nanti.'));
}

// Baca payload
$payload = file_get_contents('php://input');

if (empty($payload)) {
    send_response(400, array('error' => 'Empty payload'));
}

// Verifikasi signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
if (!verify_webhook_signature($payload, $signature)) {
    write_log('Invalid webhook signature!', 'ERROR');
    send_response(403, array('error' => 'Invalid signature'));
}

// Parse payload
$data = json_decode($payload, true);

if (!$data) {
    send_response(400, array('error' => 'Invalid JSON'));
}

// Cek event type
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? 'unknown';

if ($event === 'ping') {
    write_log('Webhook ping received');
    send_response(200, array('message' => 'Pong! Webhook configured correctly.'));
}

if ($event !== 'push') {
    send_response(200, array('message' => "Event '{$event}' ignored. Only push events are processed."));
}

// Cek branch
$ref = $data['ref'] ?? '';
$expected_ref = "refs/heads/{$CONFIG['github_branch']}";

if ($ref !== $expected_ref) {
    write_log("Branch mismatch: expected '{$expected_ref}', got '{$ref}'", 'INFO');
    send_response(200, array('message' => "Branch '{$ref}' ignored. Only '{$CONFIG['github_branch']}' is processed."));
}

// Process push event
$head_commit = $data['head_commit'] ?? array();
$commits = $data['commits'] ?? array();

write_log("Push received: " . count($commits) . " commit(s)");

// Kumpulkan semua file yang berubah
$added = array();
$modified = array();
$removed = array();

foreach ($commits as $commit) {
    $added = array_merge($added, $commit['added'] ?? array());
    $modified = array_merge($modified, $commit['modified'] ?? array());
    $removed = array_merge($removed, $commit['removed'] ?? array());
}

// Hapus duplikat
$added = array_unique($added);
$modified = array_unique($modified);
$removed = array_unique($removed);

// Filter hanya file di theme path dan yang diizinkan
$added = array_filter($added, function($f) {
    return is_extension_allowed($f) && !is_file_protected($f);
});
$modified = array_filter($modified, function($f) {
    return is_extension_allowed($f) && !is_file_protected($f);
});
$removed = array_filter($removed, function($f) {
    return !is_file_protected($f);
});

write_log("Files: " . count($added) . " added, " . count($modified) . " modified, " . count($removed) . " removed");

// Process files
$success_count = 0;
$fail_count = 0;
$skip_count = 0;
$details = array();

// Tambah file baru
foreach ($added as $file) {
    $content = download_file($file);
    if ($content !== false) {
        if (save_file($file, $content)) {
            $success_count++;
            $details[] = array('action' => 'added', 'file' => $file, 'status' => 'success');
            write_log("Added: {$file}");
        } else {
            $fail_count++;
            $details[] = array('action' => 'added', 'file' => $file, 'status' => 'failed');
            write_log("Failed to save: {$file}", 'ERROR');
        }
    } else {
        $fail_count++;
        $details[] = array('action' => 'added', 'file' => $file, 'status' => 'download_failed');
        write_log("Failed to download: {$file}", 'ERROR');
    }
}

// Update file yang berubah
foreach ($modified as $file) {
    $content = download_file($file);
    if ($content !== false) {
        if (save_file($file, $content)) {
            $success_count++;
            $details[] = array('action' => 'modified', 'file' => $file, 'status' => 'success');
            write_log("Modified: {$file}");
        } else {
            $fail_count++;
            $details[] = array('action' => 'modified', 'file' => $file, 'status' => 'failed');
            write_log("Failed to save: {$file}", 'ERROR');
        }
    } else {
        $fail_count++;
        $details[] = array('action' => 'modified', 'file' => $file, 'status' => 'download_failed');
        write_log("Failed to download: {$file}", 'ERROR');
    }
}

// Hapus file
foreach ($removed as $file) {
    if (delete_file($file)) {
        $success_count++;
        $details[] = array('action' => 'removed', 'file' => $file, 'status' => 'success');
        write_log("Removed: {$file}");
    } else {
        $skip_count++;
        $details[] = array('action' => 'removed', 'file' => $file, 'status' => 'not_found');
    }
}

// Summary
$total = $success_count + $fail_count;
$message = "Deploy selesai: {$success_count}/{$total} file berhasil";

if ($fail_count > 0) {
    $message .= " ({$fail_count} gagal)";
}

write_log($message);
save_deploy_status(
    $fail_count > 0 ? 'partial' : 'success',
    $message,
    array(
        'added'    => count($added),
        'modified' => count($modified),
        'removed'  => count($removed),
        'success'  => $success_count,
        'failed'   => $fail_count,
        'skipped'  => $skip_count,
        'commit'   => $head_commit['id'] ?? 'unknown',
        'message'  => $head_commit['message'] ?? 'unknown',
        'author'   => $head_commit['author']['name'] ?? 'unknown',
    )
);

send_response(200, array(
    'status'    => $fail_count > 0 ? 'partial' : 'success',
    'message'   => $message,
    'details'   => array(
        'files_added'    => count($added),
        'files_modified' => count($modified),
        'files_removed'  => count($removed),
        'success'        => $success_count,
        'failed'         => $fail_count,
    ),
    'commit'    => array(
        'id'      => $head_commit['id'] ?? null,
        'message' => $head_commit['message'] ?? null,
        'author'  => $head_commit['author']['name'] ?? null,
    ),
));
