<?php 
/*
 * Widget to display a list of media objects with links
 * @see http://getbootstrap.com/2.3.2/components.html#media
 * 
 * @author Mohammed Amir (OWT)
 * 
 * Borrowed from 'Links With Icons Widget' by Ethan Piliavin (http://angeleswebdesign.com)
*/
class MediaListWidget extends WP_Widget {
    
    // If you need more items, you probably need a better solution
    private $max_items = 10;

	function __construct(){
		$options = array(
			'description' => 'A widget that displays a list of thumbnails with links',
			'classname' => 'media-list'
		);
		parent::__construct('media-list', 'Media List', $options);
	}

    /**
     * Display the widget admin form 
     *
     * @param assoc $instance
     */
	public function form($instance) {
	    $title = ($instance ? $instance['title'] : '');
        // using 2 as arbitrary default
        $num_items = min(intval(($instance ? $instance['num_items'] : 2)), $this->max_items);
?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php echo esc_html(_x( 'Title:', 'admin-widget', 'site' )); ?></label>
			<input class="widefat" style="background:#fff;" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo esc_attr($title);?>"/>
		</p>

		<p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php echo esc_html(_x( 'Number of items:', 'admin-widget', 'site' )); ?> 
            <select class='widefat' id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>">
<?php
            for ($i=1; $i<=$this->max_items; $i++) {
                $selected = $i==$num_items ? 'selected' : '';
                printf('<option value="%d" %s>%d</option>', $i, $selected, $i);
            }
?>                
            </select>
            <span><?php echo esc_html(_x('Save to see item fields below', 'admin-widget', 'site')); ?></span>
		</p>

<?php 
        $names = $instance['name'];
        $links = $instance['link'];
        $images = $instance['img'];
            
	    for($i=0; $i<$num_items; $i++) :
            
            printf(
                '<h4>%s</h4>',
                esc_html(_x(sprintf('Item %d:',$i+1), 'admin-widget', 'site'))
            );

            printf(
                '<p><label for="%1$s[%5$s]">%2$s</label><br />
                <input type="text" name="%3$s[%5$s]" id="%1$s" value="%4$s" class="widefat"></p>',
                $this->get_field_id('name'),
                esc_attr(_x('Name:', 'admin-widget', 'site')),
                $this->get_field_name('name'),
                $names[$i],
                $i
            );			
            
            printf(
                '<p><label for="%1$s[%5$s]">%2$s</label><br />
                <input type="text" name="%3$s[%5$s]" id="%1$s" value="%4$s" class="widefat"></p>',
                $this->get_field_id('link'),
                esc_attr(_x('Link URL:', 'admin-widget', 'site')),
                $this->get_field_name('link'),
                $links[$i],
                $i
            );        
            
            printf(
                '<p><label for="%1$s[%5$s]">%2$s</label><br />
                <input type="text" name="%3$s[%5$s]" id="%1$s" value="%4$s" class="widefat"></p>',
                $this->get_field_id('img'),
                esc_attr(_x('Image URL:', 'admin-widget', 'site')),
                $this->get_field_name('img'),
                $images[$i],
                $i
            );         
            
        endfor; 
    }

    /**
     * Output the widget content
     * 
     * @param assoc $args Arguments passed from sidebar config
     * @param assoc $instance The widget data
     */
	public function widget($args, $instance){

        echo $args['before_widget'];
        
        $title = apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

		echo '<ul class="media-list">';

        $num_items = ($instance['num_items'] ? $instance['num_items'] : 0);
        $names = $instance['name'];
        $links = $instance['link'];
        $images = $instance['img'];
        
		for($i=0; $i<$num_items; $i++) {
		    printf(
		      '<li class="media">
		          <a href="%3$s">
    		          <span class="pull-left flip media-object"><img src="%4$s" alt="%2$s"/></span>
    		          <span class="media-body">%1$s</span>
		          </a>
		      </li>',
		      esc_html($names[$i]),
		      esc_attr($names[$i]),
		      esc_attr($links[$i]),
		      esc_attr($images[$i])
            );
        }

		echo '</ul>';
        echo $args['after_widget'];
	}

}

