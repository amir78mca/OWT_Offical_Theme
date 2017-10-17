<?php
/**
 * Pages Template
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

<div class="row">    
    <div class="col-md-9">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2><?php the_title(); ?></h2>
 
            <section class="page-content">
                <?php the_content(__('Read more &#8250;', 'site')); ?>
            </section>
            
        </div><!-- end of #page-<?php the_ID(); ?> -->
            
        <?php endwhile; ?> 
    <?php endif; ?>  
    </div>
    
    <?php get_sidebar('right'); ?>
</div>
<?php get_footer(); ?>