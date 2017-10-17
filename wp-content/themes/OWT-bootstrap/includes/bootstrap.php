<?php
/*
 * Include the Bootstrap library CSS and JS files, including Font Awesome
 * Current Boostrap version: 3.3.6
 * Current Font Awesome version: 4.6.1
 * 
 * @author      Mohammed Amir (OWT)
 * @package     site Bootstrap
 * @version     1.0
 * @since       Since version 1.0
 * @see         https://fortawesome.github.io/Font-Awesome/
 * @see         http://getbootstrap.com/
 */

define('site_BTSP_VERSION', '3.3.7');
define('site_BTSP_CSS_INT', 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7');
define('site_BTSP_JS_INT', 'sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS');
define('FONT_AWESOME_VERSION', '4.6.1');
define('site_BTSP_COLUMNS', 12);

function site_btsp_register_styles(){
    $minified = defined(SCRIPT_DEBUG) && SCRIPT_DEBUG ? '.min' : '';
    // <link href="" rel="stylesheet" integrity="" crossorigin="anonymous">
    // use protocol-agnostic url
    $url = get_template_directory_uri().'/css/bootstrap'.$minified.'.css';
    wp_enqueue_style('bootstrap', $url, array(), site_BTSP_VERSION);

    if (get_locale_lang_code() == 'ar') {
        $rtl = get_template_directory_uri().'/css/bootstrap-rtl.css';
        wp_enqueue_style('bootstrap-rtl', $rtl, array('bootstrap'));
    }

    $fontawesome = '//maxcdn.bootstrapcdn.com/font-awesome/'.FONT_AWESOME_VERSION.'/css/font-awesome'.$minified.'.css';
    wp_enqueue_style('fontawesome', $fontawesome);
}

function site_btsp_register_scripts(){
    $minified = defined(SCRIPT_DEBUG) && SCRIPT_DEBUG ? '.min' : '';
    // <script src="" integrity="" crossorigin="anonymous"></script>
    // use protocol-agnostic url
    $url = '//maxcdn.bootstrapcdn.com/bootstrap/'.site_BTSP_VERSION.'/js/bootstrap'.$minified.'.js';
    wp_enqueue_script('bootstrap', $url, array(), false, true);
    
    // include polyfills for IE < 9 in head
    $html5shiv = '//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js';
    wp_enqueue_script('html5shiv', $html5shiv);
    
    $respondjs = get_template_directory_uri().'/js/respond.min.js';
    wp_dequeue_script('respondjs'); // if already queued by plugin
    wp_enqueue_script('respondjs', $respondjs, array('html5shiv'), '1.4.2');
    
    wp_script_add_data('html5shiv', 'conditional', 'IE lt 9');
    wp_script_add_data('respondjs', 'conditional', 'IE lt 9');
}

add_action('wp_enqueue_scripts', 'site_btsp_register_scripts');
add_action('wp_enqueue_scripts', 'site_btsp_register_styles');

/*
 * Filter link element to add additional attributes
 */
function site_btsp_filter_style($link, $handle){
    if ($handle == 'bootstrap'){
        $link = str_replace('/>', ' integrity="'.site_BTSP_CSS_INT.'" crossorigin="anonymous"/>', $link);
    }
    return $link;
}
// TODO problems with integrity attr in Chrome
//add_filter('style_loader_tag', 'site_btsp_filter_style', 10, 2);

function site_btsp_filter_script($tag, $handle){
    if ($handle == 'bootstrap'){
        $tag = str_replace('><', ' integrity="'.site_BTSP_JS_INT.'" crossorigin="anonymous"><', $tag);
    }
    return $tag;
}
// TODO problems with integrity attr in Chrome
//add_filter('script_loader_tag', 'site_btsp_filter_script', 10, 2);
 
?>
