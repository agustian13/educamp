<?php
defined('ABSPATH') || exit;

class WebPUpload_Converter {
    private $quality;

    public function __construct() {
        $this->quality = (int) get_option('webpupload_quality', 80);
        add_filter('wp_generate_attachment_metadata', [$this, 'convert_attachment'], 10, 2);
        add_action('delete_attachment', [$this, 'delete_webp_files']);
        add_action('wp_ajax_webpupload_bulk_convert', [$this, 'ajax_bulk_convert']);
        add_action('wp_ajax_webpupload_stats', [$this, 'ajax_stats']);
        add_action('wp_ajax_webpupload_gallery', [$this, 'ajax_gallery']);
    }

    public function convert_attachment($metadata, $attachment_id) {
        if (empty($metadata) || empty($metadata['file'])) {
            return $metadata;
        }

        $upload_dir = wp_upload_dir();
        $base_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']);

        $full_path = $upload_dir['basedir'] . '/' . $metadata['file'];
        $this->convert_to_webp($full_path);

        if (!empty($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $size_data) {
                $file_path = $base_path . '/' . $size_data['file'];
                $this->convert_to_webp($file_path);
            }
        }

        return $metadata;
    }

    public function convert_to_webp($file_path) {
        if (!file_exists($file_path) || !is_file($file_path)) {
            return false;
        }

        $webp_path = $this->get_webp_path($file_path);

        if (file_exists($webp_path)) {
            return true;
        }

        $mime = mime_content_type($file_path);
        $image = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($file_path);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($file_path);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($file_path);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        $result = imagewebp($image, $webp_path, $this->quality);
        imagedestroy($image);

        if ($result) {
            $stat = stat($file_path);
            if ($stat) {
                touch($webp_path, $stat['mtime'], $stat['atime']);
            }
        }

        return $result;
    }

    public function get_webp_path($file_path) {
        $info = pathinfo($file_path);
        return $info['dirname'] . '/' . $info['filename'] . '.webp';
    }

    public function delete_webp_files($attachment_id) {
        $metadata = wp_get_attachment_metadata($attachment_id);
        if (empty($metadata)) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $full_path = $upload_dir['basedir'] . '/' . $metadata['file'];
        $this->maybe_delete_webp($full_path);

        if (!empty($metadata['sizes'])) {
            $base_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']);
            foreach ($metadata['sizes'] as $size => $size_data) {
                $this->maybe_delete_webp($base_path . '/' . $size_data['file']);
            }
        }
    }

    private function maybe_delete_webp($file_path) {
        $webp_path = $this->get_webp_path($file_path);
        if (file_exists($webp_path)) {
            @unlink($webp_path);
        }
    }

    public function ajax_bulk_convert() {
        check_ajax_referer('webpupload_bulk', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        $offset = isset($_POST['offset']) ? (int) $_POST['offset'] : 0;
        $limit = 5;

        $attachments = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => $limit,
            'offset'         => $offset,
            'post_status'    => 'any',
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ]);

        $processed = 0;
        $saved_bytes = 0;

        foreach ($attachments as $attachment) {
            $metadata = wp_get_attachment_metadata($attachment->ID);
            if (!empty($metadata)) {
                $before = $this->get_attachment_webp_savings($attachment->ID, $metadata);
                $this->convert_attachment($metadata, $attachment->ID);
                $after = $this->get_attachment_webp_savings($attachment->ID, $metadata);
                $saved_bytes += ($after - $before);
                $processed++;
            }
        }

        $remaining = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => 1,
            'offset'         => $offset + $limit,
            'fields'         => 'ids',
            'post_status'    => 'any',
        ]);

        $stats = $this->get_stats();

        wp_send_json_success([
            'processed' => $processed,
            'done'      => empty($remaining),
            'offset'    => $offset + $limit,
            'stats'     => [
                'converted'    => $stats['converted_images'],
                'saved'        => $stats['total_saved'],
                'saved_human'  => $this->format_bytes($stats['total_saved']),
            ],
        ]);
    }

    public function ajax_stats() {
        check_ajax_referer('webpupload_bulk', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        wp_send_json_success($this->get_stats());
    }

    public function ajax_gallery() {
        check_ajax_referer('webpupload_gallery', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        $page     = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? max(1, min(50, (int) $_POST['per_page'])) : 20;
        $offset   = ($page - 1) * $per_page;

        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        $attachments = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'post_status'    => 'any',
            'orderby'        => 'ID',
            'order'          => 'DESC',
        ]);

        $items = [];
        foreach ($attachments as $att) {
            $metadata = wp_get_attachment_metadata($att->ID);
            if (empty($metadata) || empty($metadata['file'])) continue;

            $full_path = $base_dir . '/' . $metadata['file'];
            $webp_path = preg_replace('/\.(jpe?g|png|gif)$/i', '', $full_path) . '.webp';

            if (!file_exists($webp_path)) continue;

            $original_size = file_exists($full_path) ? filesize($full_path) : 0;
            $webp_size = filesize($webp_path);
            $thumb_url = wp_get_attachment_image_url($att->ID, 'thumbnail');

            $items[] = [
                'id'             => $att->ID,
                'filename'       => basename($metadata['file']),
                'original_size'  => $original_size,
                'webp_size'      => $webp_size,
                'thumb'          => $thumb_url ?: '',
            ];
        }

        $total_count = count(get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]));

        wp_send_json_success([
            'items'    => $items,
            'has_more' => ($offset + $per_page) < $total_count,
        ]);
    }

    private function get_attachment_webp_savings($id, $metadata) {
        if (empty($metadata) || empty($metadata['file'])) return 0;

        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];
        $full_path = $base_dir . '/' . $metadata['file'];
        $webp_path = preg_replace('/\.(jpe?g|png|gif)$/i', '', $full_path) . '.webp';

        $saved = 0;
        if (file_exists($webp_path) && file_exists($full_path)) {
            $saved = filesize($full_path) - filesize($webp_path);
        }
        return max(0, $saved);
    }

    private function get_stats() {
        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        $attachments = get_posts([
            'post_type'      => 'attachment',
            'post_mime_type' => ['image/jpeg', 'image/png', 'image/gif'],
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]);

        $total_images = count($attachments);
        $converted = 0;
        $total_original = 0;
        $total_webp = 0;

        foreach ($attachments as $id) {
            $metadata = wp_get_attachment_metadata($id);
            if (empty($metadata) || empty($metadata['file'])) continue;

            $full_path = $base_dir . '/' . $metadata['file'];
            $webp_path = preg_replace('/\.(jpe?g|png|gif)$/i', '', $full_path) . '.webp';

            if (!file_exists($full_path)) continue;

            $original_size = filesize($full_path);
            $total_original += $original_size;

            if (file_exists($webp_path)) {
                $converted++;
                $total_webp += filesize($webp_path);
            } else {
                $total_webp += $original_size;
            }
        }

        $saved = $total_original - $total_webp;
        $percent = $total_original > 0 ? round(($saved / $total_original) * 100) : 0;

        return [
            'total_images'      => $total_images,
            'converted_images'  => $converted,
            'total_original_size' => $total_original,
            'total_webp_size'   => $total_webp,
            'total_saved'       => max(0, $saved),
            'saving_percent'    => max(0, $percent),
        ];
    }

    private function format_bytes($bytes) {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 1) . ' ' . $units[$i];
    }
}
