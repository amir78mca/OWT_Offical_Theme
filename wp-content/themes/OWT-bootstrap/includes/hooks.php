<?php
/*
 * Default action and filter hooks
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
 
/*
 * Add class 'active' to current language in switcher
 * @filter owtbrandbar_active_lang
 * @param lang_code (string) The 2-char ISO language code
 */  
function owtbrandbar_filter_active_lang($classes, $lang_code){
    $locale_lang = get_locale_lang_code();
    if ($lang_code == $locale_lang) {
        $classes .= ' active';
    }
    return $classes;
}
add_filter('owtbrandbar_active_lang', 'owtbrandbar_filter_active_lang', 10, 2);

/**
 * Output the correct language link for the language switcher
 * By default, it is <network_site_url/lang>
 * 
 * @filter owtbrandbar_lang_Link
 * @param $lang_code The locale language code (ar, zh, en, fr, ru, es) 
 */
function owtbrandbar_filter_lang_link($lang_code){
    $url = network_site_url().$lang_code.'/';
    return $url;
}
add_action('owtbrandbar_lang_link', 'owtbrandbar_filter_lang_link'); 

/**
 * Filter title for better SEO
 *
 * Adapted from Twenty Twelve
 * @filter wp_title
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_title
 */
function site_btsp_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', 'site'), max($paged, $page));

    return $title;
}
add_filter('wp_title', 'site_btsp_wp_title', 10, 2);
    
/**
 * Sets the post excerpt length
 */
function site_btsp_excerpt_length($length) {
    $len = get_theme_mod('excerpt_length', site_btsp_option_default('excerpt_length'));
    return !(empty($len)) ? $len : $length;
}
add_filter('excerpt_length', 'site_btsp_excerpt_length');


/**
 * Remove WordPress generated category and tag attributes
 * For W3C validation purposes only
 */
function site_btsp_category_rel_removal($output) {
    $output = str_replace(' rel="category tag"', '', $output);
    return $output;
}
add_filter('wp_list_categories', 'site_btsp_category_rel_removal');
add_filter('the_category', 'site_btsp_category_rel_removal');


/*
 * Add social media share buttons to post/page content
 * Requires an AddThis.com account, and adding the account token
 * through theme customizations.
 */
function add_this_share_button($output) {
    $add_this = site_btsp_get_theme_mod('addthis_user');
    if (!empty($add_this)) {
        $output .= 
        '<!-- Go to www.addthis.com/dashboard to customize your tools -->
        <div class="addthis_sharing_toolbox"></div>';
    }
    return $output;
}

add_filter('the_content', 'add_this_share_button');

/*
 * Filter the video embed code from Featured Video Plus
 * 
 * @param embed (string) The html for the video embed
 * @param post_id (int)  This will only be passed by get_the_post_video
 */
function site_filter_the_post_video($embed, $post_id) {
    if (is_single() && !is_admin()) {
        $thumbnail = get_the_post_thumbnail(null, 'large');
    } elseif ($post_id && !is_admin()) {
        $thumbnail = get_the_post_thumbnail($post_id, 'large');
    } else {
        $thumbnail = '';
    }
    return '<div class="embed-responsive embed-responsive-16by9">' .
        $thumbnail .
        $embed .
        '</div>';
}

add_filter('get_the_post_video_filter', 'site_filter_the_post_video', 20, 2);

/*
 * Remove default width and height for embeds
 */
function site_embed_defaults($url) {
    return array(null, null);
}
add_filter('embed_defaults', 'site_embed_defaults');

/*
 * Filter embeds to catch bare URL or [embed] shortcode
 * @param $html (string) The embed code
 * @param $attr (array)  The shortcode attributes, if any
 */
function site_filter_embed($html, $url, $attr, $post_id) {
    global $wp_embed;
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $wp_embed->delete_oembed_caches($post_id);
    }
    
    $wrapped_embed = '<div class="embed-responsive embed-responsive-16by9">' .
        $html .
        '</div>'; 
    if (isset($attr) && !empty($attr)) {
        $width = !empty($attr['width']) ? $attr['width'] : '';
        $width = !empty($width) ? sprintf(' style="width:%dpx;"', $width) : '';
        $align = !empty($attr['align']) ? $attr['align'] : '';
        $align = !empty($align) ? sprintf(' class="align%s"', $align) : '';
        
        if (!empty($width) || !empty($align)) {
            $wrapped_embed = sprintf('<div%s%s>%s</div>', $width, $align, $wrapped_embed);
        }
    }
    return $wrapped_embed;
}
add_filter('embed_oembed_html', 'site_filter_embed', 20, 4);

/*
 * If image is not in 16:9 aspect ratio, add 'native-size' CSS class
 */
function site_image_ratio_check($attr, $attachment, $size) {
    $wp_uploads = wp_upload_dir(null, false);
    // force protocols to match to simplify path extraction
    $baseurl = preg_replace('/^https?/', 'http', $wp_uploads['baseurl']);
    $src = preg_replace('/^https?/', 'http', $attr['src']);
    $attachment_path = $wp_uploads['basedir'].str_replace($baseurl, '', $src);

    // try to get info from filesystem, fallback to url
    if (is_readable($attachment_path)) {
        $info = getimagesize($attachment_path);
    } else {
        $info = getimagesize($attr['src']);
    }

    $width  = $info[0];
    $height = $info[1];

    $ratio = number_format($width/$height, 2);
    $ref_ratio = number_format(16/9, 2); // 16:9 aspect ratio
    
    $ratio_diff = abs($ratio - $ref_ratio);    
    
    // use native size if ratio is off by more than 5%
    if ($ratio_diff > .05*$ref_ratio) {
        $attr['class'] = $attr['class'].' native-size';
    }
    return $attr;
}

add_filter('wp_get_attachment_image_attributes', 'site_image_ratio_check', 10, 3);

