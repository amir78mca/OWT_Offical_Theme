<?php
/**
 * Owl Carousel generator
 * @see http://www.owlcarousel.owlgraphic.com/index.html
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
abstract class siteBootstrapOwlCarousel {
    
    protected $options;
    
    /**
     * @param $options Carousel and post selection options.
     *      Supported options:
     *          id (string) an optional HTML id for the carousel
     *          class (string) an optional CSS class name(s)
     *          items (int) the number of items to display
     *          query (string) a query string of the format accepted by WP_Query 
     *            @see https://codex.wordpress.org/Class_Reference/WP_Query#Parameters
     *          carousel (array) (API only) additional params for the carousel JS 
     *            @see http://www.owlcarousel.owlgraphic.com/docs/api-options.html
     */
    protected function __construct($options) {
        $defaults = array(
            'id'        => '',
            'class'     => '',
            'items'  => 5,
            'query'     => null,
            'carousel'  => null,
        );
        // filter supported options
        $this->options = shortcode_atts($defaults, $options);
    }
    
    /**
     * Update the value for a carousel option.
     * Allows templates to set template-specific overrides to the initialization
     * options.
     *
     * @param key (string) the option name. Must be one of the supported options. 
     * @param $value (mixed) the option value
     */
    public function set_option($key, $value) {
        if (isset($this->options[$key])) {
            $this->options[$key] = $value;
        }
    }
    
    /**
     * Returns the option value for this carousel instance,
     * or the optional default value if it is not set.
     */
    public function get_option($key, $default_value=null) {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        } else {
            return $default_value;
        }
    }
    
    // NOTES
    // Based on examples seen in other plugins, I foresee this could be
    // expanded to support shortcodes for carousels based on
    // posts or on a gallery, given the right options 
	
    /**
     * @param $items (mixed) Objects to display in the carousel, e.g. posts
     */
    protected function _do_carousel($items) {
        $template_file = $this->_locate_template();
        if (!empty($template_file)) {
            ob_start();
            require($template_file);
            $results = ob_get_clean();
        }
        else {
            $results = esc_html(_x('The specified carousel template was not found','carousel','site'));
        }
        
        // template may modify JS options
        $callback = function(){
            $this->_print_js();
        };
        
        // print JS after libraries are included
        add_action('wp_print_footer_scripts', $callback, 50);
        
        
        return $results;
    }
    
    protected function _locate_template() {
        // TODO could be expanded to allow alternative templates,
        // e.g. select by option 'type' (posts or gallery)
        // or a 'template' option with a filename segment
        // pulling filename from options
        $template_file = locate_template('part.owl-carousel-posts.php');
        return $template_file;
    }

    /**
     * Print inline JS to initialize the carousel
     * 
     * Carousel options can be customized by passing them in the 
     * 'carousel' property of $options to the constructor. 
     * @see http://www.owlcarousel.owlgraphic.com/docs/api-options.html
     */
    protected function _print_js(){
        // TODO accept JSON (parse and validate) for overrides
        $js_opts = array(
            'loop'       => TRUE,
            'nav'        => TRUE,
            'dots'       => FALSE,
            'items'      => 1,
            'autoHeight' => TRUE,
            'navText'    => array(esc_html(_x('Previous','carousel','site')), esc_html(_x('Next','carousel','site')))
        );
        if(get_locale_lang_code() == 'ar'){
			$js_opts['rtl'] = TRUE;
		}
        if (isset($this->options['carousel'])) {
            $js_opts = array_merge($js_opts, $this->get_option('carousel', array()));
        }
        
        $js_opts = json_encode($js_opts);

        echo <<<HILO
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.owl-carousel').owlCarousel({$js_opts}); 
    });
</script>
HILO;
    }
    
    abstract public function do_carousel();
}

/**
 * Creates a carousel object using the posts returned by a WP_Query object.
 * 
 * On a blog page (e.g. home), the global query object is used by default.
 * If the carousel is called on a singular template, or if the 'query' option
 * is passed, a new WP_Query object is constructed from the available query params.
 */
class siteBootstrapPostsCarousel extends siteBootstrapOwlCarousel {
    
    function __construct($options=null) {
        parent::__construct($options);
    }

    /**
     * Get the WP_Query object for this carousel 
     *
     * @param $args (string|array) Arguments for WP_Query
     *      By default, the value of the 'query' option is passed. 
     */
    protected function get_posts($args) {
        global $wp_query;
        
        if (is_singular() || !empty($args)) {
            $items = new WP_Query($args);
        }
        else {
            $items = $wp_query;
        }
        return $items;
    }
    
    public function do_carousel() {
        $posts = $this->get_posts($this->get_option('query'));
        echo $this->_do_carousel($posts);
    }
    
}

/* TODO maybe
class siteBootstrapGalleryCarousel extends siteBootstrapOwlCarousel {
 ...
}
*/

/**
 * Create and render an instance of a posts carousel
 * @param $options (array) @see siteBootstrapCarousel::__construct
 */
function site_btsp_posts_carousel($options = array()){
    $site_btsp_owl_carousel = new siteBootstrapPostsCarousel($options);
    $site_btsp_owl_carousel->do_carousel();
}
