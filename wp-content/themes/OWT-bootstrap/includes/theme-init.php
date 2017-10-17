<?php
/*
 * Theme initialization
 * 
 * @author Mohammed Amir (OWT)
 * @package site Bootstrap
 * @version 1.0
 */

/*
 * Theme activation
 */
function site_btsp_activation() {
    /* Set default image sizes */
    update_option('thumbnail_size_w', 191);
    update_option('thumbnail_size_h', 107);
    update_option('thumbnail_crop', 0);
    
    update_option('medium_size_w', 360);
    update_option('medium_size_h', 203);
    
    update_option('large_size_w', 848);
    update_option('large_size_h', 476);    
}

add_action('after_switch_theme', 'site_btsp_activation');
 
/*
 * Start theme setup
 */ 
function site_btsp_setup() {
    site_btsp_load_l10n();
    site_btsp_register_theme_menus();
    
    add_theme_support('post-thumbnails');
    add_theme_support('html5');
    
    add_post_type_support('page', 'excerpt');
}
add_action('after_setup_theme','site_btsp_setup');

// Allow functions to be overridden by child themes, since 
// child theme is loaded first.
// To override, simply declare the function in the child theme's functions.php

if (!function_exists('site_btsp_load_l10n')) :
    
    /**
     * Load theme localization
     * Add your files into /languages/ directory.
     * @see http://codex.wordpress.org/Function_Reference/load_theme_textdomain
     */
    function site_btsp_load_l10n(){
        load_theme_textdomain('site', get_template_directory() . '/languages');
    }
    
endif; 

if (!function_exists('site_btsp_register_theme_menus')) :
    
    /**
     * Register default menus supported by the theme
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */
    function site_btsp_register_theme_menus(){
        register_nav_menus(array(
            'header-menu' => _x('Main Nav Menu', 'admin', 'site'),
        ));
    }
    
endif; 

// Default theme initialization hooks
// To override, remove the parent theme hook and add a custom hook
// in the child theme.

function enqueue_owtfont(){
    $langcode = get_locale_lang_code();
    if ($langcode == 'ar') {
        // @see https://www.google.com/fonts/earlyaccess
        // TODO keep an eye on graduation
        // weights normal,bold
        $url = '//fonts.googleapis.com/earlyaccess/notonaskharabic.css';
    }
    else {
        // @see https://www.google.com/fonts/specimen/Roboto
        // if additional weights needed, options are light,regular,medium,thin,italic,mediumitalic,bold
        $url = '//fonts.googleapis.com/css?family=Roboto:regular,medium,italic,bold';
    }
    
    wp_register_style('site-font', $url);
    wp_enqueue_style('site-font');
}
add_action('wp_enqueue_scripts', 'enqueue_owtfont');

function site_btsp_enqueue_theme_scripts(){
    $minified = defined(SCRIPT_DEBUG) && SCRIPT_DEBUG ? '.min' : '';
    $langcode = get_locale_lang_code();
    $me = wp_get_theme();
    $version = $me->Version;

    wp_register_style('owt-brandbar', get_template_directory_uri().'/css/owt-brandbar.css', array('bootstrap'), $version);
    wp_enqueue_style('owt-brandbar');
    
    // ensure theme css is loaded after library dependencies
    wp_enqueue_style('site-bootstrap', get_template_directory_uri().'/style.css', array('bootstrap','superfish-css','superfish-navbar-css'), $version);
    
    // localization styles
    $lang_css_file = get_template_directory().'/css/style-'.$langcode.'.css'; 
    if (is_readable($lang_css_file)) {
        wp_enqueue_style('language-style', get_template_directory_uri()."/css/style-$langcode.css", array('site-bootstrap'), $version);
    }
        
    // Owl Carousel jquery plugin @see http://www.owlcarousel.owlgraphic.com/index.html
    wp_register_style('owl-carousel', get_template_directory_uri().'/css/owl.carousel.css', array(), '2.0.0');
    wp_register_style('owl-theme', get_template_directory_uri().'/css/owl.theme.css', array('owl-carousel'), '1.3.2');
    wp_register_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel'.$minified.'.js', array('jquery'), '2.0.0', true);
    wp_enqueue_style('owl-carousel');
    wp_enqueue_style('owl-theme');
    wp_enqueue_script('owl-carousel');
}
add_action('wp_enqueue_scripts', 'site_btsp_enqueue_theme_scripts');

function site_add_this() {
    $add_this = site_btsp_get_theme_mod('addthis_user');
    if (!empty($add_this)) {
        $js_url = '//s7.addthis.com/js/300/addthis_widget.js#pubid='.$add_this;
        wp_enqueue_script('add-this', $js_url, array(), null, TRUE);
        wp_enqueue_script('site-addthis', get_template_directory_uri().'/js/site.addThis.js', array('jquery', 'add-this'), '1.0', TRUE);
    }
}
add_action('wp_enqueue_scripts', 'site_add_this');
