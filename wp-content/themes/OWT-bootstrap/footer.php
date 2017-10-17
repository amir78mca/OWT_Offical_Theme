<?php
/**
 * Footer Template
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>

</div><!-- end of #content -->

<?php $langcode = get_locale_lang_code(); ?>

<?php /* Child themes can optionally define sidebar-footer.php */ ?>
<?php get_sidebar('footer'); ?>

<?php
    $add_this = site_btsp_get_theme_mod('addthis_user');
    if (!empty($add_this)) :
        
?>
<div id="social_media_follow" class="container">
    <div class="row">
        <div class="col-md-12">
            <h5><?php esc_html_e('Follow Us', 'site'); ?></h5>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_vertical_follow_toolbox"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<div id="unfooter" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left flip footer-brand">
                    <a href="//www.un.org/<?php echo $langcode; ?>/" title="Home"><span class="site-name"><?php esc_html_e('United Nations', 'site'); ?></span></a>
                </div>
                <div class="pull-right flip">
                    <ul class="menu nav list-inline">
                        <li class="first leaf">
                            <a href="<?php echo home_url('/contact-us.html'); ?>"><?php esc_html_e('Contact', 'site'); ?></a>
                        </li>
                        <li class="leaf">
                            <a href="//www.un.org/<?php echo $langcode; ?>/aboutun/copyright/"><?php esc_html_e('Copyright', 'site'); ?></a>
                        </li>
                        <li class="leaf">
                            <a href="//www.un.org/<?php echo $langcode; ?>/aboutun/fraudalert/"><?php esc_html_e('Fraud Alert', 'site'); ?></a>
                        </li>
                        <li class="leaf">
                            <a href="//www.un.org/<?php echo $langcode; ?>/aboutun/privacy/"><?php esc_html_e('Privacy Notice', 'site'); ?></a>
                        </li>
                        <li class="leaf">
                            <a href="//www.un.org/<?php echo $langcode; ?>/siteindex/"><?php esc_html_e('Site Index', 'site'); ?></a>
                        </li>
                        <li class="last leaf">
                            <a href="//www.un.org/<?php echo $langcode; ?>/aboutun/terms/"><?php esc_html_e('Terms of Use', 'site'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<?php wp_footer(); // TODO google analytics ?>

</body>
</html>