<?php
/*
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>

<?php get_header(); ?>

<div id="404" <?php post_class(); ?>>
    <h2><?php esc_html_e('404 Page Not Found','site'); ?></h2>
    
    <?php
        $search_link = sprintf('http://unitesearch.un.org/?tpl=site&amp;lang=%s', get_locale_lang_code());
    ?>
    <p>
        <?php esc_html_e('The page you requested was not found.','site'); ?><br/>
        <?php esc_html_e('Please check the URL to make sure you entered it correctly.', 'site'); ?><br/>
        <?php echo sprintf(__('You can use our <a href="%s">Search</a> or visit the <a href="%s">site home page</a>.', 'site'), $search_link, site_url()); ?>
    </p>
 
</div><!-- end of #404 ?> -->


<?php get_footer(); ?>


