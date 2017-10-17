<?php
/**
 * Generates and displays breadcrumbs lists
 * @action site_btsp_breadcrumbs
 * 
 * By default, hierarchical content is displayed from ancestor to descendent.
 * For posts, an attempt is made to determine the chain of navigation to posts based
 * on the referer in case it matches a default archive link.
 * 
 * Hooks are provided to change the breadcrumbs content before display, and
 * to customize the HTML output (unordered list by default).
 * 
 * To customize, use the action/filter hooks, or extend this class.
 * 
 * @author Mohammed Amir (OWT)
 * @version 1.0
 * @package site Bootstrap
 * @since Version 1.0
 * 
 */
 
class siteBootstrapBreadcrumbs {
    
    function do_breadcrumbs(){
        $crumbs = array();
        
        $crumbs[] = array(esc_html__('Home', 'site'), home_url('/'));
        
        if (is_page()) {
            $post_type = get_post_type();
            // hierarchical content, like Pages
            if (is_post_type_hierarchical($post_type)) {
                $crumbs = array_merge($crumbs, $this->get_hierarchical_breadcrumbs());
            }
            // non-hierarchical content, like Posts
            else {
                $crumbs = array_merge($crumbs, $this->get_singular_breadcrumbs());
            }
        }
        else { // not singular
            $crumbs = array_merge($crumbs, $this->get_archive_breadcrumbs());
        }
        
        // var_dump($crumbs);
        
        /*
         * Filter the breadcrumbs items prior to display.
         * Can be used to add, remove, or re-order the breadcrumbs.
         * 
         * @param (array) List of items in display order. 
         *  Each item is either a duple (name, url) or a string.
         */
        $crumbs = apply_filters('site_btsp_breadcrumbs_items', $crumbs);
        
        if (!empty($crumbs)) {
            $this->display_breadcrumbs($crumbs);
        }
    }
    
    protected function display_breadcrumbs($crumbs){
        /*
         * Filter the HTML prefix for the breadcrumbs
         * @param (string)
         */
        $html = apply_filters('site_btsp_breadcrumbs_before','<div id="breadcrumbs" class="container"><ul>');
        
        foreach ($crumbs as $item) {
            if (is_scalar($item)) {
                /*
                 * Filter the html of a breadcrumb item that does not have a link (usually the last item)
                 * @param (string) A string format which will be passed to sprintf with 1 argument (name)
                 */
                $format = apply_filters('site_btsp_breadcrumbs_static_item', '<li><span>%s</span></li>');
                $html .= sprintf($format, $item);
            } else {
                /*
                 * Filter the html of a breadcrumb item with a link
                 * @param (string) A string format which will be passed to vsprintf with a duple (name,url)
                 */
                $format = apply_filters('site_btsp_breadcrumbs_link_item', '<li><a href="%2$s">%1$s</a></li>');
                $html .= vsprintf($format, $item);
            }
        }

        /*
         * Filter the HTML suffix for the breadcrumbs
         * @param (string)
         */
        $html .= apply_filters('site_btsp_breadcrumbs_after','</ul></div>');
        
        echo $html;
    }
    
    /*
     * Returns links to parent content in order from highest to lowest,
     * with the current content last
     */
    protected function get_hierarchical_breadcrumbs(){
        $crumbs = array();
        $parents = get_post_ancestors();

        if (!empty($parents)) {
            // order by farthest to nearest ancestor
            $parents = array_reverse($parents);
            foreach ($parents as $p) {
                $crumbs[] = array(get_the_title($p), get_permalink($p));
            }
        }
        // add current
        if (!is_home()) {
            $crumbs[] = get_the_title();
        }
        return $crumbs;
    }
    
    /*
     * Returns links to the referring archive, if it can be determined, 
     * with current content last. 
     * Matches to 'standard' pretty permalinks archives formats.
     */
    protected function get_singular_breadcrumbs() {
        $referer = wp_get_referer();
        // if the referer is internal, then try to match it to a date or category archive
        if ($referer && preg_match('/'.preg_quote(site_url(),'/').'/', $referer)) {
            // check for year/month archives
            if (preg_match('#/(\d{4})(/\d{2})?$#', $referer, $matches)) {
                if (isset($matches[2])) {
                    $month = date_create_from_format('Ymd', $matches[1].substr($matches[2], 1).'01');
                    $label = date_format($month, 'F Y');
                } else {
                    $label = $matches[1];
                }
                $crumbs[] = array($label, $referer);
            }
            elseif (preg_match('/'.preg_quote(get_option('category_base')).'(\/[-\w]+)$/', $referer, $matches)) {
                $category = get_term_by('slug', substr($matches[1], 1), 'category');
                if ($category) {
                    $crumbs[] = array($category->name, $referer);
                }
            }
            elseif (preg_match('/'.preg_quote(get_option('tag_base')).'(\/[-\w]+)$/', $referer, $matches)) {
                $tag = get_term_by('slug', substr($matches[1], 1), 'post_tag');
                if ($tag) {
                    $crumbs[] = array($matches[0], $referer);
                }
            }
        }
        else { // if not an internal referer, just show the post type
            $post_type_object = get_post_type_object(get_post_type());
            $archive_link = get_post_type_archive_link($post_type_object);
            if (!empty($archive_link)) {
                $crumbs[] = array($post_type_object->labels->name, $archive_link);
            }            
        }

        return $crumbs;
    }

    protected function get_archive_breadcrumbs() {
        $crumbs = array();
        $referer = wp_get_referer();
        if (is_category()) {
			if ($referer && preg_match('/'.preg_quote(site_url(),'/').'/', $referer)) {
            // check for year/month archives
				if (preg_match('#/(\d{4})(/\d{2})?$#', $referer, $matches)) {
					if (isset($matches[2])) {
						$month = date_create_from_format('Ymd', $matches[1].substr($matches[2], 1).'01');
						$label = date_format($month, 'F Y');
					} else {
						$label = $matches[1];
					}
					$crumbs[] = array($label, $referer);
				}
			}
            $crumbs[] = single_cat_title('', FALSE);
        }
        elseif (is_month()) {
            $crumbs[] = get_the_time('F Y');
        }
        elseif (is_year()) {
            $crumbs[] = get_the_time('Y');
        }
        elseif (is_tag()) {
            $crumbs[] = single_tag_title('', FALSE);
        }
		elseif (is_single()) {
            $crumbs[] = single_post_title('', FALSE);
        }
        elseif (is_post_type_archive()) {
            $post_type_object = get_post_type_object(get_post_type());
            $crumbs[] = $post_type_object->labels->name;
        }
        elseif (is_search()) {
            $crumbs[] = _x('Search', 'breadcrumbs', 'site');
        }
        return $crumbs;
    }
}

function site_do_breadcrumbs(){
    /**
     * Filter flag for whether the breadcrumbs should be shown.
     * By default, they are not displayed on the home or blog front page.
     * 
     * @param boolean
     * @return boolean
     */
    $show_breadcrumbs = apply_filters('site_btsp_show_breadcrumbs', !(is_home() || is_front_page() || is_404()));
    
    if ($show_breadcrumbs) {
        $controller = new siteBootstrapBreadcrumbs();
        $controller->do_breadcrumbs();
    }
}
add_action('site_btsp_breadcrumbs', 'site_do_breadcrumbs');
