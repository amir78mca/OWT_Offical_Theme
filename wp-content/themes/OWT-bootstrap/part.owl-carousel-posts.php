<?php 
/*
 * Template for rendering posts in an Owl Carousel
 * Required by @class siteBootstrapOwlCarousel
 *
 * In scope:
 * $this    - the current siteBootstrapOwlCarousel object
 * $items   - the objects to be placed in the carousel, in this case a WP_Query object
 *
 * @package        site Bootstrap
 * @version        Release: 1.0
 * @since          available since Release 1.0
 *
 */
$id = $this->get_option('id', 'carousel-' . rand());
$class = $this->get_option('class');
$classes = empty($class) ? '' : sprintf(' class="%s"', $class);

// set certain options specific to this template
$js_opts = array(
    'loop' => true, 
    'autoplay' => false, 
    'autoplayHoverPause' => true
);
$this->set_option('carousel',$js_opts);

$slide_num = 0;
$max_slides = $this->get_option('items');

?>
<div id="<?php echo $id; ?>"<?php echo $classes; ?>>
    <?php if ($items->have_posts()) : ?>

    <div class="col-md-7">
        <div class="owl-carousel">
        <?php while($items->have_posts() && $slide_num < $max_slides) : $items->the_post(); ?>
            <div class="item main-image-container" data-hash="slide-<?php echo $slide_num; ?>">
            <?php if (has_video($items->post)) { ?>
                <?php echo get_the_post_video($items->post->ID); ?>
                
            <?php } elseif (has_post_thumbnail($items->post)) { ?>
                <div class="image-wrapper">
                <a href="<?php echo get_permalink($items->post); ?>">
                   <img src="<?php echo get_the_post_thumbnail_url($items->post, 'large'); ?>" alt="<?php echo get_the_title($items->post); ?>" />
                </a>
                </div>
            <?php        
                }
                $slide_num++;
            ?>
                
                <h4><a href="<?php echo get_permalink($items->post) ?>"><?php echo get_the_title($items->post); ?></a></h4>
                <p><?php echo site_excerpt_or_teaser($items->post); ?></p>
                
            </div>
        <?php endwhile; ?>
        </div>
    </div><?php //end col-md-7 ?>

    <?php
        // restart indices
        $slide_num = 0;
        $items->rewind_posts();
    ?>
    <div class="col-md-5 thumbnail-navigation">
        <div class="thumbnails-nav">
            <div class="thumbnails">
            <?php 
                while ($items->have_posts() && $slide_num < $max_slides) : $items->the_post();
                    if($slide_num==0){
                        $firstSlideActiveCLass = "active";
                    } else {
                        $firstSlideActiveCLass = "";
                    }
            ?>
                <div class="item <?php echo $firstSlideActiveCLass; ?>">
                    <div class="col-md-5">
                    <a href="#slide-<?php echo $slide_num; ?>" class="thumb-link">
                        <div class="current-item"></div>
                        <img src="<?php echo get_the_post_thumbnail_url($items->post, 'medium'); ?>" alt="<?php echo get_the_title($items->post); ?>"/>
                    </a>
                    </div>
                    <div class="col-md-7">
                    <section class="text" style="">
                        <h4><a href="<?php echo get_permalink($items->post) ?>"><?php echo get_the_title($items->post); ?></a></h4>
                      <p><?php //echo site_excerpt_or_teaser($items->post, 5); ?></p> 
                    </section>
                    </div>
                </div>
                <?php
                    $slide_num++;
                    endwhile;
                ?>
            </div><?php // end thumbnails ?>
        </div>
        <div class="navigation-div"> 
            <div class="navigation"> 
                <a class="fa fa-chevron-down next" href="#" style="display: inline-block;"></a>
                <a class="fa fa-chevron-up prev" href="#" style="display: none;"></a> 
            </div>
        </div>
    </div><?php // end col-md-5 ?>
        
    <?php else : // !$items->have_posts ?>
        
        <p><?php esc_html(__('No posts found.', 'site')); ?></p>
        
    <?php endif; // $items->have_posts ?>

</div><!-- end #<?php echo $id; ?>-->
