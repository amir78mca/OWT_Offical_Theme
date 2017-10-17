<?php

/**
 * Widget to display posts related to current post based on taxonomies.
 * Intended for use on a single post/page only.
 * 
 * @author Mohammed Amir (OWT)
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
class RelatedPostsWidget extends WP_Widget {
	
	function __construct() {
        $widget_ops = array(
            'classname' => 'related-posts',
            'description' => 'Display posts related to the current single post',
        );
        parent::__construct( 'related-posts', 'Related Posts', $widget_ops );
	}
    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        // outputs the content of the widget
        echo $args['before_widget'];
        
        $title = apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $posts = $this->get_posts($instance);
        
        if (empty($posts)) {
?>
        <p><?php echo esc_html(_x('No related posts found', 'related-posts-widget', 'site')); ?></p>
<?php
        }
        else {
            echo '<ul>';
            foreach ($posts as $p) {
?>
                <li><a href="<?php echo get_permalink($p); ?>"><?php echo get_the_title($p); ?></a></li>
<?php                
            }
            echo '</ul>';
        }
        echo $args['after_widget'];
    }

    /**
     * Admin options form
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        if ($instance) {
            $title = $instance['title'];
        } else {
            $title = '';
        } 
?>
        <!-- Title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _x( 'Title:', 'admin-widget', 'site' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" placeholder="Title here"/>
        </p>
<?php
    }

    /**
     * Save widget options
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        $old_instance['title'] = strip_tags($new_instance['title']);
        return $old_instance;
    }    
    
    private function get_posts($instance){
        // TODO post type, taxonomy, and number could all be configurable
        
        $cb = function($i){
            return $i->term_id;
        };
        
        $post_categories = get_the_category();
        if (!empty($post_categories)) {
            $_categories = array_map($cb, $post_categories);
        }
        
        $post_tags = get_the_tags();
        if (!empty($post_tags)) {
            $_tags = array_map($cb, $post_tags);
        } 
        
        $args = array(
            'post_type'     => 'post',
            'tax_query'     => array(
                'relation'  => 'OR'
            ),
            'numberposts'   => 4,
            'post__not_in'  => array(get_the_ID())
        );
        
        if (isset($_categories)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $_categories,
                'operator' => 'IN'
            );
        }
        
        if (isset($_tags)) {
            $args['tax_query'][] = array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $_tags,
                    'operator' => 'IN',
            );
        }       
        return get_posts($args);        
    }
}
