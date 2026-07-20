<?php
defined('ABSPATH') || exit;

class WebPUpload_Converter {
    private $quality;

    public function __construct() {
        $this->quality = (int) get_option('webpupload_quality', 80);
        add_filter('wp_generate_attachment_metadata', [$this, 'convert_attachment'], 10, 2);
        add_action('delete_attachment', [$this, 'delete_webp_files']);
        add_action('wp_ajax_webpupload_bulk_convert', [$this, 'ajax_bulk_convert']);
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

        foreach ($attachments as $attachment) {
            $metadata = wp_get_attachment_metadata($attachment->ID);
            if (!empty($metadata)) {
                $this->convert_attachment($metadata, $attachment->ID);
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

        wp_send_json_success([
            'processed' => $processed,
            'done'      => empty($remaining),
            'offset'    => $offset + $limit,
        ]);
    }
}
