<?php
/*
 * Global functions provided by theme
 * 
 * @author Mohammed Amir (OWT)
 * @package site Bootstrap
 * @version 1.0
 */

if (!function_exists('get_locale_lang_code')){
    /**
     * Returns the 2-character ISO language code for the current locale
     * @return String
     */
    function get_locale_lang_code(){
        // create and cache on demand
        if (!isset($GLOBALS['_locale_lang_code']) || empty($GLOBALS['_locale_lang_code'])) {
            $GLOBALS['_locale_lang_code'] = substr(get_locale(), 0, 2);
        }
        return $GLOBALS['_locale_lang_code'];
    }
}


/*
 * Display a badge
 * @see http://getbootstrap.com/2.3.2/components.html#labels-badges
 * 
 * @param (string) $content Badge content
 * @param (string) $type    Badge type: a built-in type (success|warning|important|info|inverse)
 *                           or custom if custom CSS is desired
 * @param (bool)   $return  Return or echo the content. Default is false (echo).
 */
function print_badge($content, $type='', $return=false) {
    $content = esc_html($content);
    $type = (empty($type) ? '' : sprintf(' badge-%s', $type));
    $badge = sprintf('<span class="badge%s">%s</span>', $type, $content);

    /**
     * @param string The badge HTML
     * @return string  
     */
    $badge = apply_filters('site_btsp_print_badge', $badge);
    
    if ($return) {
        return $badge;
    } else {
        echo $badge;
    }
}

/**
 * Check if there is a video in the post content,
 * either through the featured video or as a regular embed
 * 
 * @param $post (WP_Post|id|null) If not supplied, uses the current post (inside the Loop)
 *      @see https://developer.wordpress.org/reference/functions/get_post
 * @return boolean
 */
function has_video($post=null) {
    // get_post handles parameter disambiguation
    $_post = get_post($post);
    
    if (! $_post)
        return null;
    
    // check first for Featured Video 
    // @see https://wordpress.org/plugins/featured-video-plus/installation/
    if (function_exists('has_post_video')) {
        return has_post_video($_post->ID);
    }
    else {
        $content = $_post->post_content;
        return strpos($content, '<iframe') !== FALSE || 
            strpos($content, '[embed') !== FALSE || 
            strpos($content, '<object') !== FALSE;
    } 
}


/**
 * Retrieve recent posts for each category matching the passed arguments. 
 * By default, retrieves the latest post(s) for each existing category.
 * 
 * @see https://developer.wordpress.org/reference/functions/get_categories/
 * @see https://codex.wordpress.org/Template_Tags/get_posts
 * 
 * @param $category_args (Array) Arguments supported by get_categories
 * @param $post_args (Array) Arguments supported by get_posts 
 */
function get_posts_per_category($category_args=array(), $post_args=array()){
    $category_args = shortcode_atts($category_args, array());
    $post_args = shortcode_atts($post_args, array(
        'numberposts'   => 1
    ));
    
    $categories = get_categories($category_args);
    $result = array();
    
    foreach ($categories as $cat) {
        $post_args['category__in'] = array($cat->term_id);
        $posts = get_posts($post_args);
        if (!empty($posts)) {
            $result[] = $posts[0];
        }
    }
    return $result;
}

/**
 * Returns the post excerpt if one exists, or the post teaser if no excerpt exists.
 * This allows use of manual or automatic excerpts, and provides backwards
 * compatibility with the use of << more >> tags.
 * 
 * @see http://buildwpyourself.com/wordpress-manual-excerpts-more-tag/
 * 
 * @filter site_excerpt_or_teaser
 * 
 * @param $_post (int|WP_Post) Optional post or id. Uses global post if in the Loop.
 * @param $words (int)  Length in words, using 
 */
function site_excerpt_or_teaser($_post=null, $words=null) {
    $_post = get_post($_post);

    if (empty($_post))
        return '';
    
    global $post;
    $post = $_post;
    setup_postdata($post);

    $excerpt = get_the_excerpt();
    
    if (empty($excerpt)) {
        $excerpt = get_the_content('');
    }

    wp_reset_postdata();
    
    if ($limit = intval($words)) {
        // multibyte-safe version for Chinese
        if (get_locale_lang_code() == 'zh' && mb_strlen($excerpt) > $limit*2) {
            $excerpt = mb_substr($excerpt, 0, $limit*2).'&hellip;';
        } else {
            $excerpt = wp_trim_words($excerpt, $limit);
        }
    }
    
    /**
     * @param string The excerpt/teaser content
     * @return string  
     */
    return apply_filters('site_excerpt_or_teaser', $excerpt, $_post);
}

