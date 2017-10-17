<?php
/*
 * UN Brandbar
 * Usage: get_template_part('owt-brandbar')
 * 
 * @package site Bootstrap
 * @author Mohammed Amir
 * @release 1.0
 */
?>
<div id="brand-bar" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <!-- UN global brand bar -->
    <div class="container brandbar-header">
        <div class="row">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="col-md-4">
                <a class="navbar-brand" href="/en/"><?php esc_html_e('Welcome to the United Nations','site'); ?></a>
            </div><!-- /UN global brand bar -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="col-md-8 language-switcher">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#language-switcher">
                    <span class="sr-only"><?php esc_html_e('Toggle navigation', 'site'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- TODO what is this for? -->
                <div class="language-title visible-xs-inline">
                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#language-switcher"><?php esc_html_e('Language:', 'site'); ?></a>
                </div>
				<!--Added class amd id attribute-->
                <div class="navbar-collapse collapse pull-right flip" id="language-switcher">
                    <nav>
                        <ul class="language-switcher-locale-url nav navbar-nav navbar-right" role="menu">
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'ar first', 'ar'); ?>">
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'ar'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'ar'); ?>" lang="ar">&#1593;&#1585;&#1576;&#1610;</a>
                            </li>
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'zh', 'zh'); ?>">
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'zh'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'zh'); ?>" lang="zh-hans">&#20013;&#25991;</a>
                            </li>                                                                                                                                                     
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'en', 'en'); ?>">                                                                          
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'en'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'en'); ?>" lang="en">English</a>
                            </li>                                                                                                                                                     
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'fr', 'fr'); ?>">                                                                          
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'fr'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'fr'); ?>" lang="fr">Fran&#231;ais</a>
                            </li>                                                                                                                                                     
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'ru', 'ru'); ?>">                                                                          
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'ru'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'ru'); ?>" lang="ru">&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;</a>
                            </li>                                                                                                                                                     
                            <li class="<?php echo apply_filters('owtbrandbar_active_lang', 'es last', 'es'); ?>">                                                                     
                                <a href="<?php echo apply_filters('owtbrandbar_lang_link', 'es'); ?>" class="<?php echo apply_filters('owtbrandbar_active_lang', 'language-link', 'es'); ?>" lang="es">Espa&#241;ol</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div><!-- /.navbar-collapse -->

        </div>
        <!-- /.row -->
    </div><!-- /.container -->
</div><!-- /UN global brand bar and language switcher -->