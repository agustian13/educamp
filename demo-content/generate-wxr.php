<?php
/**
 * WXR Generator for EduCampus Demo Import
 * Run: php demo-content/generate-wxr.php
 */

// Locate wp-load.php
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
define('SITE_URL_PLACEHOLDER', '{{SITE_URL}}');
$current_site_url = rtrim(get_bloginfo('url'), '/');

// Collect all post types (skip auto-drafts, revisions, customize_changeset)
$exclude_types = array('revision', 'customize_changeset', 'custom_css', 'user_request', 'oembed_cache');

// Build the query
$post_types = get_post_types(array('public' => true, '_builtin' => false), 'names');
$post_types[] = 'page';
$post_types[] = 'post';
$post_types[] = 'nav_menu_item';
$post_types[] = 'attachment';
$post_types = array_diff($post_types, $exclude_types);
$post_types = array_unique($post_types);

// Get all posts
$posts = get_posts(array(
    'post_type'      => $post_types,
    'post_status'    => array('publish', 'inherit'),
    'posts_per_page' => -1,
    'orderby'        => 'post_type',
    'order'          => 'ASC',
));

// Get all terms
$taxonomies = get_object_taxonomies($post_types);
$taxonomies = array_diff($taxonomies, array('nav_menu'));
$terms = get_terms(array(
    'taxonomy'   => $taxonomies,
    'hide_empty' => false,
));

// Get nav menu terms
$nav_menus = get_terms(array(
    'taxonomy'   => 'nav_menu',
    'hide_empty' => false,
));

// Generate WXR
$wxr = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
$wxr .= '<!-- This is a WordPress eXtended RSS file generated for EduCampus Demo Import -->' . "\n";
$wxr .= '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">' . "\n";
$wxr .= '<channel>' . "\n";

// Channel info
$wxr .= "\t<title>" . esc_html(get_bloginfo('name')) . "</title>\n";
$wxr .= "\t<link>" . SITE_URL_PLACEHOLDER . "</link>\n";
$wxr .= "\t<description>" . esc_html(get_bloginfo('description')) . "</description>\n";
$wxr .= "\t<language>" . esc_html(get_bloginfo('language')) . "</language>\n";
$wxr .= "\t<wp:wxr_version>1.2</wp:wxr_version>\n";
$wxr .= "\t<wp:base_site_url>" . SITE_URL_PLACEHOLDER . "</wp:base_site_url>\n";
$wxr .= "\t<wp:base_blog_url>" . SITE_URL_PLACEHOLDER . "</wp:base_blog_url>\n";

// Authors
$authors = array();
foreach ($posts as $p) {
    $user = get_userdata($p->post_author);
    if ($user && !isset($authors[$user->ID])) {
        $authors[$user->ID] = $user;
    }
}
foreach ($authors as $user) {
    $wxr .= "\t<wp:author>\n";
    $wxr .= "\t\t<wp:author_id>" . (int)$user->ID . "</wp:author_id>\n";
    $wxr .= "\t\t<wp:author_login>" . esc_html($user->user_login) . "</wp:author_login>\n";
    $wxr .= "\t\t<wp:author_email>" . esc_html($user->user_email) . "</wp:author_email>\n";
    $wxr .= "\t\t<wp:author_display_name>" . esc_html($user->display_name) . "</wp:author_display_name>\n";
    $wxr .= "\t\t<wp:author_first_name>" . esc_html($user->first_name) . "</wp:author_first_name>\n";
    $wxr .= "\t\t<wp:author_last_name>" . esc_html($user->last_name) . "</wp:author_last_name>\n";
    $wxr .= "\t</wp:author>\n";
}

// Categories
foreach ($terms as $t) {
    $wxr .= "\t<wp:category>\n";
    $wxr .= "\t\t<wp:term_id>" . (int)$t->term_id . "</wp:term_id>\n";
    $wxr .= "\t\t<wp:category_nicename>" . esc_html($t->slug) . "</wp:category_nicename>\n";
    $parent_slug = $t->parent ? get_term_field('slug', $t->parent, $t->taxonomy) : '';
    if (is_wp_error($parent_slug)) $parent_slug = '';
    $wxr .= "\t\t<wp:category_parent>" . esc_html($parent_slug) . "</wp:category_parent>\n";
    $wxr .= "\t\t<wp:cat_name>" . esc_html($t->name) . "</wp:cat_name>\n";
    $wxr .= "\t</wp:category>\n";
}

// Tags
$tags = get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false));
if (!empty($tags) && !is_wp_error($tags)) {
    foreach ($tags as $t) {
        $wxr .= "\t<wp:tag>\n";
        $wxr .= "\t\t<wp:term_id>" . (int)$t->term_id . "</wp:term_id>\n";
        $wxr .= "\t\t<wp:tag_slug>" . esc_html($t->slug) . "</wp:tag_slug>\n";
        $wxr .= "\t\t<wp:tag_name>" . esc_html($t->name) . "</wp:tag_name>\n";
        $wxr .= "\t</wp:tag>\n";
    }
}

// Nav Menus
$menu_items_by_parent = array();
foreach ($nav_menus as $nm) {
    $wxr .= "\t<wp:term>\n";
    $wxr .= "\t\t<wp:term_id>" . (int)$nm->term_id . "</wp:term_id>\n";
    $wxr .= "\t\t<wp:term_taxonomy>" . esc_html($nm->taxonomy) . "</wp:term_taxonomy>\n";
    $wxr .= "\t\t<wp:term_slug>" . esc_html($nm->slug) . "</wp:term_slug>\n";
    $wxr .= "\t\t<wp:term_name>" . esc_html($nm->name) . "</wp:term_name>\n";
    $wxr .= "\t</wp:term>\n";
}

// Custom taxonomies (program_level, news_category)
$custom_taxonomies = array_diff($taxonomies, array('category', 'post_tag'));
foreach ($custom_taxonomies as $tax) {
    $tax_terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
    if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
        foreach ($tax_terms as $t) {
            $wxr .= "\t<wp:term>\n";
            $wxr .= "\t\t<wp:term_id>" . (int)$t->term_id . "</wp:term_id>\n";
            $wxr .= "\t\t<wp:term_taxonomy>" . esc_html($tax) . "</wp:term_taxonomy>\n";
            $wxr .= "\t\t<wp:term_slug>" . esc_html($t->slug) . "</wp:term_slug>\n";
            $term_parent = $t->parent ? get_term_field('slug', $t->parent, $tax) : '';
            if (is_wp_error($term_parent)) $term_parent = '';
            $wxr .= "\t\t<wp:term_parent>" . esc_html($term_parent) . "</wp:term_parent>\n";
            $wxr .= "\t\t<wp:term_name>" . esc_html($t->name) . "</wp:term_name>\n";
            $wxr .= "\t</wp:term>\n";
        }
    }
}

// Items (posts)
foreach ($posts as $p) {
    setup_postdata($p);

    $wxr .= "\t<item>\n";
    $wxr .= "\t\t<title>" . esc_html($p->post_title) . "</title>\n";
    $wxr .= "\t\t<link>" . esc_html(get_permalink($p)) . "</link>\n";

    $pub_date = mysql2date('D, d M Y H:i:s +0000', $p->post_date_gmt, false);
    $wxr .= "\t\t<pubDate>" . esc_html($pub_date) . "</pubDate>\n";

    $wxr .= "\t\t<dc:creator>" . esc_html(get_the_author_meta('user_login', $p->post_author)) . "</dc:creator>\n";

    $guid = get_the_guid($p);
    $wxr .= "\t\t<guid isPermaLink=\"false\">" . esc_html($guid) . "</guid>\n";
    $wxr .= "\t\t<description></description>\n";
    $wxr .= "\t\t<content:encoded><![CDATA[" . $p->post_content . "]]></content:encoded>\n";
    $wxr .= "\t\t<excerpt:encoded><![CDATA[" . $p->post_excerpt . "]]></excerpt:encoded>\n";

    $wxr .= "\t\t<wp:post_id>" . (int)$p->ID . "</wp:post_id>\n";
    $wxr .= "\t\t<wp:post_date>" . esc_html($p->post_date) . "</wp:post_date>\n";
    $wxr .= "\t\t<wp:post_date_gmt>" . esc_html($p->post_date_gmt) . "</wp:post_date_gmt>\n";
    $wxr .= "\t\t<wp:post_modified>" . esc_html($p->post_modified) . "</wp:post_modified>\n";
    $wxr .= "\t\t<wp:post_modified_gmt>" . esc_html($p->post_modified_gmt) . "</wp:post_modified_gmt>\n";
    $wxr .= "\t\t<wp:comment_status>" . esc_html($p->comment_status) . "</wp:comment_status>\n";
    $wxr .= "\t\t<wp:ping_status>" . esc_html($p->ping_status) . "</wp:ping_status>\n";
    $wxr .= "\t\t<wp:post_name>" . esc_html($p->post_name) . "</wp:post_name>\n";
    $wxr .= "\t\t<wp:status>" . esc_html($p->post_status) . "</wp:status>\n";
    $wxr .= "\t\t<wp:post_parent>" . (int)$p->post_parent . "</wp:post_parent>\n";
    $wxr .= "\t\t<wp:menu_order>" . (int)$p->menu_order . "</wp:menu_order>\n";
    $wxr .= "\t\t<wp:post_type>" . esc_html($p->post_type) . "</wp:post_type>\n";
    $wxr .= "\t\t<wp:post_password>" . esc_html($p->post_password) . "</wp:post_password>\n";
    $wxr .= "\t\t<wp:is_sticky>" . (is_sticky($p->ID) ? 1 : 0) . "</wp:is_sticky>\n";

    // Terms for this post
    $post_terms = wp_get_object_terms($p->ID, $taxonomies, array('fields' => 'all'));
    foreach ($post_terms as $pt) {
        $wxr .= "\t\t<category domain=\"" . esc_html($pt->taxonomy) . "\" nicename=\"" . esc_html($pt->slug) . "\">" . esc_html($pt->name) . "</category>\n";
    }

    // Post meta
    $meta = get_post_meta($p->ID);
    foreach ($meta as $key => $values) {
        if (in_array($key, array('_edit_last', '_edit_lock'))) continue;
        foreach ($values as $val) {
            $wxr .= "\t\t<wp:postmeta>\n";
            $wxr .= "\t\t\t<wp:meta_key>" . esc_html($key) . "</wp:meta_key>\n";
            $wxr .= "\t\t\t<wp:meta_value><![CDATA[" . $val . "]]></wp:meta_value>\n";
            $wxr .= "\t\t</wp:postmeta>\n";
        }
    }

    // Nav menu meta
    if ($p->post_type === 'nav_menu_item') {
        $menu_item_type = get_post_meta($p->ID, '_menu_item_type', true);
        $menu_item_object = get_post_meta($p->ID, '_menu_item_object', true);
        $menu_item_object_id = get_post_meta($p->ID, '_menu_item_object_id', true);
        $menu_item_url = get_post_meta($p->ID, '_menu_item_url', true);
        $menu_item_classes = get_post_meta($p->ID, '_menu_item_classes', true);

        $wxr .= "\t\t<wp:postmeta>\n";
        $wxr .= "\t\t\t<wp:meta_key>_menu_item_type</wp:meta_key>\n";
        $wxr .= "\t\t\t<wp:meta_value>" . esc_html($menu_item_type) . "</wp:meta_value>\n";
        $wxr .= "\t\t</wp:postmeta>\n";
        $wxr .= "\t\t<wp:postmeta>\n";
        $wxr .= "\t\t\t<wp:meta_key>_menu_item_object</wp:meta_key>\n";
        $wxr .= "\t\t\t<wp:meta_value>" . esc_html($menu_item_object) . "</wp:meta_value>\n";
        $wxr .= "\t\t</wp:postmeta>\n";
        $wxr .= "\t\t<wp:postmeta>\n";
        $wxr .= "\t\t\t<wp:meta_key>_menu_item_object_id</wp:meta_key>\n";
        $wxr .= "\t\t\t<wp:meta_value>" . esc_html($menu_item_object_id) . "</wp:meta_value>\n";
        $wxr .= "\t\t</wp:postmeta>\n";
        $wxr .= "\t\t<wp:postmeta>\n";
        $wxr .= "\t\t\t<wp:meta_key>_menu_item_url</wp:meta_key>\n";
        $wxr .= "\t\t\t<wp:meta_value><![CDATA[" . $menu_item_url . "]]></wp:meta_value>\n";
        $wxr .= "\t\t</wp:postmeta>\n";
        if (!empty($menu_item_classes)) {
            $wxr .= "\t\t<wp:postmeta>\n";
            $wxr .= "\t\t\t<wp:meta_key>_menu_item_classes</wp:meta_key>\n";
            $wxr .= "\t\t\t<wp:meta_value><![CDATA[" . maybe_serialize($menu_item_classes) . "]]></wp:meta_value>\n";
            $wxr .= "\t\t</wp:postmeta>\n";
        }
    }

    // Comments
    $comments = get_comments(array('post_id' => $p->ID, 'status' => 'approve'));
    foreach ($comments as $c) {
        $wxr .= "\t\t<wp:comment>\n";
        $wxr .= "\t\t\t<wp:comment_id>" . (int)$c->comment_ID . "</wp:comment_id>\n";
        $wxr .= "\t\t\t<wp:comment_author>" . esc_html($c->comment_author) . "</wp:comment_author>\n";
        $wxr .= "\t\t\t<wp:comment_author_email>" . esc_html($c->comment_author_email) . "</wp:comment_author_email>\n";
        $wxr .= "\t\t\t<wp:comment_author_url>" . esc_html($c->comment_author_url) . "</wp:comment_author_url>\n";
        $wxr .= "\t\t\t<wp:comment_author_IP>" . esc_html($c->comment_author_IP) . "</wp:comment_author_IP>\n";
        $wxr .= "\t\t\t<wp:comment_date>" . esc_html($c->comment_date) . "</wp:comment_date>\n";
        $wxr .= "\t\t\t<wp:comment_date_gmt>" . esc_html($c->comment_date_gmt) . "</wp:comment_date_gmt>\n";
        $wxr .= "\t\t\t<wp:comment_content><![CDATA[" . $c->comment_content . "]]></wp:comment_content>\n";
        $wxr .= "\t\t\t<wp:comment_approved>" . esc_html($c->comment_approved) . "</wp:comment_approved>\n";
        $wxr .= "\t\t\t<wp:comment_type>" . esc_html($c->comment_type) . "</wp:comment_type>\n";
        $wxr .= "\t\t\t<wp:comment_parent>" . (int)$c->comment_parent . "</wp:comment_parent>\n";
        $wxr .= "\t\t\t<wp:comment_user_id>" . (int)$c->user_id . "</wp:comment_user_id>\n";
        $wxr .= "\t\t</wp:comment>\n";
    }

    $wxr .= "\t</item>\n";
    wp_reset_postdata();
}

$wxr .= '</channel>' . "\n";
$wxr .= '</rss>' . "\n";

// Replace all localhost URLs with placeholder
$wxr = str_replace($current_site_url, SITE_URL_PLACEHOLDER, $wxr);
// Also handle any URL variants
$wxr = str_replace('http://localhost/campus', SITE_URL_PLACEHOLDER, $wxr);

file_put_contents(__DIR__ . '/demo-content.xml', $wxr);
echo "WXR generated: " . (round(strlen($wxr) / 1024 / 1024, 2)) . " MB\n";
echo "Posts: " . count($posts) . "\n";
echo "Done.\n";
