<?php
/**
 * Header Template
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta http-equiv="Content-Type" content="text/html;charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="X-FRAME-OPTIONS" content="DENY">

        <title><?php wp_title('&#124;', true, 'right'); ?></title>
        
        <?php wp_head(); ?>
		
    </head>
	
    <body <?php body_class(); echo (get_locale_lang_code() == 'ar' ? ' dir="rtl"' : ''); ?> >
	<div class="skipLink">
		<a href="#content"><?php esc_html_e('Skip to Content','site'); ?></a>
	</div>        
        <?php get_template_part('part.owt-brandbar'); ?>

        <header id="header" class="container region-header">
        <div class="row">
			<div id="logo" class="col-md-9 pull-left flip">
			    <h1>
			         <a href="<?php echo home_url('/'); ?>">
    					 <?php echo site_btsp_get_theme_mod('site_header_title'); ?>
						 <small>
    		                 <?php
    		                     $abbr = site_btsp_get_theme_mod('site_header_short_subtitle');
                                 if (!empty($abbr)) {
                                     echo '<span class="abbr">'.site_btsp_get_theme_mod('site_header_subtitle').'</span>';
                                     echo '<abbr>'.$abbr.'</abbr>';
                                 }
                                 else {
                                     echo site_btsp_get_theme_mod('site_header_subtitle');
                                 }
                             ?>
    	                 </small>
			         </a>
			    </h1>
            </div><!-- /#logo -->
			<div class="col-md-3 col-xs-8 pull-right flip" id="search_container"><?php get_template_part('part.search-box'); ?></div>
		</div>
		<div class="row">
		    <div class="col-md-12">
        		<div class="sf-accordion-toggle sf-style-space">
            		<a id="superfish-1-toggle" href="#" class="sf-expanded"><i class="fa fa-bars "></i></a>
        		</div>
                <?php
                    wp_nav_menu(array(
                        'container'      => 'nav',
                        'theme_location' => 'header-menu',
            			'menu_class'     => 'sf-menu sf-navbar sf-desktop-menu',
            			'container_class'=> 'menu-site-container block-superfish menu-site-desktop'
            			)
                    );
        			 wp_nav_menu(array(
                        'container'      => 'nav',
                        'theme_location' => 'header-menu',
            			'menu_class'     => 'sf-menu sf-navbar sf-mobile-menu sf-hidden',
            			'container_class'=> 'menu-site-container block-superfish menu-site-mobile'
            			)
                    );
                ?>
            </div>
        </div>
        </header>
        
        <?php do_action('site_btsp_breadcrumbs'); ?>

        <div id="content" class="container">
