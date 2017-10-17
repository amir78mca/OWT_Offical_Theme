<?php
/*
 * Template Name: Category Feature
 * 
 * Display latest posts and content related to a category 
 * The category displayed is the same one assigned to the page
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
 
?>

<?php get_header(); ?>

<?php if (have_posts()) : while(have_posts()) : the_post(); ?>

<div id="page-<?php the_ID(); ?>" <?php post_class('page-category-feature'); ?>>
        <h2><?php the_title(); ?></h2>

    <section id="category-news" class="row featured-news">
        <?php
            $cat = get_the_category();
            /**
             * Allows modification of parameters to post query for latest
             * posts section of the Category Feature page template
             * 
             * @filter site_btsp_cat_page_news_args
             * @param Array
             * @return Array of args accepted by @see get_posts  
             */
            $post_args = apply_filters('site_btsp_cat_page_news_args', array(
                'numberposts'=>5,
                'category__in'=>array($cat[0]->term_id)
            ));
            $posts = get_posts($post_args);
            
            if (!empty($posts)) :
                // Handle first post
                $p = array_shift($posts);
                
                // set Last-Modified to most recent post time
                $mtime = strtotime( $p->post_modified_gmt );
                $header_last_modified_value = str_replace( '+0000', 'GMT', gmdate('r', $mtime) );
                header('Last-Modified: ' . $header_last_modified_value);
                
                // display large
        ?>
		
            <div class="col-md-7">
                <header>
                    <h3><?php esc_html_e('Latest on this issue', 'site'); ?></h3>
                </header>
                <article class="main-image-container">
                    <?php if (function_exists('has_post_video') && has_post_video($p->ID)) {
                        echo get_the_post_video($p->ID);
                    } else {
                        echo get_the_post_thumbnail($p, 'large');
                    } ?>
                    <h4><a href="<?php echo get_permalink($p); ?>"><?php echo get_the_title($p); ?></a></h4>
                    <p><?php echo site_excerpt_or_teaser($p); ?></p>
                </article>
            </div>

            <div class="col-md-5 thumbnails-nav ">
                <header>
                    <h3><?php esc_html_e('Read also:', 'site'); ?></h3>
                </header>
                
                <?php
                                        
                    // display rest of posts
                    foreach ($posts as $p) :
                ?>
				<div class="item">
					<div class="col-md-7">
						<section class="text">
							<h4><a href="<?php echo get_permalink($p); ?>"><?php echo get_the_title($p); ?></a></h4>
							<p><?php //echo site_excerpt_or_teaser($p,13); ?></p>
						</section>
					</div>
					<div class="col-md-5">
						<a class="thumb" href="<?php echo get_the_permalink($p); ?>"><?php echo get_the_post_thumbnail($p, 'thumbnail'); ?></a>
					</div>
				</div>
                    
                <?php
                    endforeach;
                ?>                    
                
        </div>
        <?php            
            endif; // !empty($posts)
        ?>
        
    </section>

    <section id="category-about" class="row">
        <div class="col-md-8">
            <section class="page-content">
                <header>
                    <h3><?php esc_html_e('About', 'site'); ?></h3>
                </header>
                
                <?php the_content(); ?>
            </section>
        </div>
        
        <div class="col-md-4">
            <?php
                if (!dynamic_sidebar('category-feature')) {
                    dynamic_sidebar('right-sidebar');
                }
            ?>
        </div>        
    </section><!-- end #category-about -->
</div><!-- end page-<?php the_ID(); ?> -->
<?php endwhile; endif; ?>

<?php get_footer(); ?>