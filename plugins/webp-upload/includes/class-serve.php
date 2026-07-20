<?php
defined('ABSPATH') || exit;

class WebPUpload_Serve {
    public function __construct() {
        add_filter('the_content', [$this, 'filter_content']);
        add_filter('post_thumbnail_html', [$this, 'filter_image_html']);
        add_filter('wp_get_attachment_image', [$this, 'filter_image_html']);
        add_filter('widget_text', [$this, 'filter_content']);
        add_filter('widget_custom_html', [$this, 'filter_content']);
    }

    public function filter_content($content) {
        if (empty($content) || !is_string($content)) {
            return $content;
        }

        return preg_replace_callback(
            '/<img[^>]+>/i',
            [$this, 'replace_img_with_picture'],
            $content
        );
    }

    public function filter_image_html($html) {
        return $this->filter_content($html);
    }

    private function replace_img_with_picture($matches) {
        $img_tag = $matches[0];

        if (!preg_match('/src=["\']([^"\']+)["\']/i', $img_tag, $src_match)) {
            return $img_tag;
        }

        $src = $src_match[1];
        $webp_src = $this->get_webp_url($src);

        if (!$webp_src) {
            return $img_tag;
        }

        $class = '';
        if (preg_match('/class=["\']([^"\']*)["\']/i', $img_tag, $class_match)) {
            $class = $class_match[1];
        }

        return '<picture' . ($class ? ' class="' . esc_attr($class) . '"' : '') . '>'
             . '<source srcset="' . esc_url($webp_src) . '" type="image/webp">'
             . $img_tag
             . '</picture>';
    }

    private function get_webp_url($url) {
        $upload_dir = wp_upload_dir();
        $base_url = $upload_dir['baseurl'];
        $base_dir = $upload_dir['basedir'];

        if (strpos($url, $base_url) !== 0) {
            return false;
        }

        $relative_path = substr($url, strlen($base_url));
        $relative_path = rawurldecode($relative_path);
        $file_path = $base_dir . $relative_path;
        $webp_path = preg_replace('/\.(jpe?g|png|gif)$/i', '', $file_path) . '.webp';

        if (!file_exists($webp_path)) {
            return false;
        }

        $path_parts = pathinfo($relative_path);
        return $base_url . $path_parts['dirname'] . '/' . rawurlencode($path_parts['filename']) . '.webp';
    }
}
