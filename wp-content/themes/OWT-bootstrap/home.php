<?php
/**
 * site Website Homepage
 *
 * @see            https://codex.wordpress.org/Theme_Development
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
 
    // do not use last-modified header on home page
    // see Add Headers plugin 
    function addh_custom_options_home ( $options ) {
        // These are the default options.
        return array_merge( $options, array(
            'add_last_modified_header' => FALSE
        ) );
    }
    add_filter( 'addh_options', 'addh_custom_options_home', 10, 1 );    
  
?>
<?php get_header(); ?>

<div id="featured" class="row">
    <?php 
        site_btsp_posts_carousel(array(
            'id'=>'owt-carousel',
            'class'=>'front-owt-carousel',
            'items'=>8
        )); 
    ?>
</div>

<?php get_sidebar('home'); ?>
<?php get_footer(); ?>