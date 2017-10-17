<?php
/**
 * Template Name: Sub-page grid
 * 
 * Displays a grid of all child pages
 * Show the page thumbnail if it exists
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
 
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
<div id="page-<?php the_ID(); ?>" <?php post_class('page-subpage-grid'); ?>>
    <h2><?php the_title(); ?></h2>

    <?php
        /**
         * Allows modification of parameters to get child pages
         * for the Sub-page grid template
         * 
         * By default all published child pages of the current page 
         * are retrieved
         * 
         * @filter site_btsp_subpages_args
         * @param Array
         * @return Array of args accepted by @see get_children  
         */
        $args = apply_filters('site_btsp_subpages_args', array(
            'post_parent'   => get_the_ID(),
            'post_type'     => 'page',
            'post_status'   => 'publish',
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        ));
        
        $child_pages = get_children($args);
        $cols = 3;
        $count = 0;
        
        if (!empty($child_pages)) :
            foreach ($child_pages as $page) :
                if ($count % $cols == 0) {
                    if ($count != 0) echo '</div>';
                    echo '<div class="row">';
                }
                $categories = get_the_category($page->ID);
				$categories = array_map(function($c){
				    return 'category-'.$c->slug;
				}, $categories);
    ?>
            <div class="col-md-4">
                <section <?php post_class(join(' ', $categories)); ?>>
                    <header><h3><a href="<?php echo get_permalink($page->ID); ?>"><?php echo get_the_title($page); ?></a></h3></header>
                    <a href="<?php echo get_permalink($page->ID); ?>"><?php echo get_the_post_thumbnail($page->ID, 'medium'); ?></a>
                    <p><?php echo site_excerpt_or_teaser($page->ID); ?></p>
                </section>
            </div>
    <?php
                //var_dump($page);
                $count++;
            endforeach; // end foreach($child_pages)
            
            echo '</div>';
            
        endif; // end !empty($child_pages)

    ?>
 
</div><!-- end of #page-<?php the_ID(); ?> -->
        
<?php endwhile; ?> 
<?php endif; ?>  

<?php get_footer(); ?>