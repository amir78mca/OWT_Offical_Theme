<?php

/* 
 * Register widget areas
 */
if (!function_exists('site_btsp_register_sidebars')) :
    
    function site_btsp_register_sidebars() {
        register_sidebar(array(
            'name' => _x('Right Sidebar', 'admin-widget', 'site'),
            'id' => 'right-sidebar',
            'before_title' => '<header><h3>',
            'after_title' => '</h3></header>',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>'
        ));
        
         register_sidebar(array(
            'name' => _x('Home Widget 1', 'admin-widget', 'site'),
            'description' => _x('Home widget area column 1 (home.php)', 'admin-widget', 'site'),
            'id' => 'home-widget-1',
            'before_title' => '<header><h3>',
            'after_title' => '</h3></header>',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>'
        ));
    
        register_sidebar(array(
            'name' => _x('Home Widget 2', 'admin-widget', 'site'),
            'description' => _x('Home widget area column 2 (home.php)', 'admin-widget', 'site'),
            'id' => 'home-widget-2',
            'before_title' => '<header><h3>',
            'after_title' => '</h3></header>',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>'
        ));
    
        register_sidebar(array(
            'name' => _x('Home Widget 3', 'admin-widget', 'site'),
            'description' => _x('Home widget area column 3 (home.php)', 'admin-widget', 'site'),
            'id' => 'home-widget-3',
            'before_title' => '<header><h3>',
            'after_title' => '</h3></header>',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>'
        ));   
		
		register_sidebar(array(
            'name' => _x('Category Feature', 'admin-widget', 'site'),
            'description' => _x('Sidebar For Category Page', 'admin-widget', 'site'),
            'id' => 'category-feature',
            'before_title' => '<header><h3>',
            'after_title' => '</h3></header>',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>'
        ));
     
    }

endif;

add_action('widgets_init', 'site_btsp_register_sidebars');

function site_btsp_register_widgets() {
    require_once(get_template_directory().'/includes/class.related-posts-widget.php');
    require_once(get_template_directory().'/includes/class.media-list-widget.php');
    register_widget('RelatedPostsWidget');
    register_widget('MediaListWidget');
}

add_action ('widgets_init', 'site_btsp_register_widgets');
