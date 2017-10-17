<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Single Posts Template
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

<?php 
    /**
     * Filter the title used on single post views.
     * Note this is not the post title, which is displayed below the main image.
     * 
     * @param (string) The title text
     * @return (string)
     */
    $page_title = apply_filters('site_single_post_title', __('News', 'site'));
    /**
     * Filter the single post header.
     * Note this is not the post title, but an optional header text.
     * e.g. Return empty string to omit header.
     * 
     * @param (string) The header html
     * @return (string) 
     */
    $page_header = sprintf('<div class="h2">%s</div>', esc_html($page_title));
    echo apply_filters('site_single_post_header', $page_header);
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="row">
        
        <div class="col-md-9">
    	
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <?php 
                    if (has_post_thumbnail() && !has_video()) {
                        the_post_thumbnail('large');
                    } 
                    elseif (function_exists('has_post_video') && has_post_video()) {
                        the_post_video();
                    }  
                ?>
                
                <h2><?php the_title(); ?></h2>
                
                <?php
					if(get_locale_lang_code() == 'zh'){
						$dateFormat = "Y" ."年". "m". "月" . "j" . "日";
					}elseif(get_locale_lang_code() == 'es'){ 
						$dateFormat = "j \d\\e F Y";	
					}else{
						$dateFormat = "j F Y";					
					}
					if(get_locale_lang_code() == 'fr' || get_locale_lang_code() == 'es'){
						$post_meta = sprintf('<span class="date">%s</span>', strtolower(date_i18n($dateFormat, get_the_date('U')))); // Y F J format in Chinese lang
					}else {
						$post_meta = sprintf('<span class="date">%s</span>', date_i18n($dateFormat, get_the_date('U'))); // Y F J format in Chinese lang
					}
                    $post_meta = apply_filters('site_btsp_post_meta', $post_meta);
                    if (!empty($post_meta)) : 
                ?>

    			<section class="post-meta">
    			    <?php echo $post_meta; ?>
                </section>
                
                <?php endif; ?>
                
                <section><?php the_content(); ?></section>
    
            </article><!-- end of #post-<?php the_ID(); ?> -->
    
    </div>
    
    <?php get_sidebar('right'); ?>
    </div><!-- end row -->
    
<?php endwhile; ?> 
<?php endif; ?>  

<?php get_footer(); ?>