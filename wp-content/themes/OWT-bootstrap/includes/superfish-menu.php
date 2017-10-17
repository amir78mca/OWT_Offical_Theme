<?php
/*
 * Include the Superfish menu scripts
 * 
 * @author      Mohammed Amir (OWT)
 * @package     site Bootstrap
 * @version     1.0
 * @since       Since version 1.0
 */


add_action('wp_enqueue_scripts', 'site_btsp_enqueue_superfish');
function site_btsp_enqueue_superfish() {

    // Custom SF styles (theme v1.0)
    wp_enqueue_style('superfish-css', get_template_directory_uri() . '/css/superfish.css', array(), '1.0');
    wp_enqueue_style('superfish-navbar-css', get_template_directory_uri() . '/css/superfish-navbar.css', array(), '1.0');

    // SF library scripts
    wp_enqueue_script('superfish-hover', get_template_directory_uri() . '/sf/js/hoverIntent.js', array('jquery'), 'r7');
    wp_enqueue_script('superfish', get_template_directory_uri() . '/sf/js/superfish.min.js', array('jquery'), '1.7.9');

    //wp_enqueue_script('supersubs', get_template_directory_uri() . '/sf/js/supersubs.js', array('jquery'), '0.3b', true);

}

// print inline JS after libraries are included
add_action('wp_print_footer_scripts', 'site_btsp_init_superfish', 50);

function site_btsp_init_superfish(){
        echo <<<HILO
<script type="text/javascript">
    jQuery(document).ready(function(){
        // superFish
        jQuery('ul.sf-menu').superfish({ pathClass: 'current-menu-item'}); // call supersubs first, then superfish

        jQuery('#superfish-1-toggle').click(function(){
            jQuery('.sf-mobile-menu').toggleClass('sf-hidden');
            jQuery('.sf-mobile-menu').toggleClass('sf-expanded');
        });

     });
</script>
HILO;
    
}
