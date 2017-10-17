<?php
/**
 * Does oEmbed-like stuff for UN Webcast videos
 * - Handles 'standard' video embeds using 'pretty' URLs, 
 *   e.g. http://webtv.un.org/watch/something-something/123456789 
 *   @see https://codex.wordpress.org/Embeds
 *   
 * - Integrates with Featured Video Plus plugin to get/set post thumbnails and 
 *   generate video embed code 
 *   @see https://wordpress.org/plugins/featured-video-plus/
 * 
 * NOTE: This is not a full embed provider, and could hopefully 
 * be replaced in future with a true one
 * e.g. using https://codex.wordpress.org/Function_Reference/wp_oembed_add_provider
 * @see https://www.brightcove.com/en/partners/embedly 
 *
 * @author Mohammed Amir (OWT)
 * 
 * Heavily borrowed from Brian Henderson: https://github.com/brchenderson/brightcove-oembed/blob/master/brightcove-oembed.php
 * for the BC data pull and from Featured Video Plus for making it more Wordpress-y
 */ 
class OembedWebcast {
    
	private $embedded_video_pattern = '#https?://webtv.un.org/(.*)/(\d+)#i';
    private $provider_url = 'https://link.brightcove.com/services/player/bcpid1722935254001/?bctid=';
    
    function __construct(){
        wp_embed_register_handler('unwebtv', $this->embedded_video_pattern, array($this, 'handle_embed'));
        
        // if Featured Video Plus plugin is activated add hooks to handle Webcast videos
        // note: works since plugins loaded before theme
        if (class_exists('Featured_Video_Plus')) {
            add_action('save_post', array($this, 'on_save'), 20); // run after plugin 
            add_filter('get_the_post_video_filter', array($this, 'fvp_embed'), 10, 2);
        }
    }
    
    public function handle_embed($matches, $attr, $url, $rawattr) {
        $html = $this->get_html($url);
        return apply_filters('embed_oembed_html', $html, $url, $attr);
    }
    
    private function get_html($url) {
        $vidid = $this->video_id_from_url($url);
        $player_id = '1722935254001';
        $player_key = 'AQ~~,AAABPSuWdxE~,UHaNXUUB06VgvRTiG_GhQXXPhev1OX58'; 
 
        // width and height only determine aspect ratio of video (16:9)
        // css creates responsive width
        $embed = <<<HILO
            <!--
            By use of this code snippet, I agree to the Brightcove Publisher T and C 
            found at https://accounts.brightcove.com/en/terms-and-conditions/. 
            -->
            <object id="myExperience$vidid" class="BrightcoveExperience">
              <param name="bgcolor" value="#FFFFFF" />
              <param name="wmode" value="transparent" />
              <param name="width" value="480" />
              <param name="height" value="270" />
              <param name="playerID" value="$player_id" />
              <param name="playerKey" value="$player_key" />
              <param name="isVid" value="true" />
              <param name="isUI" value="true" />
              <param name="dynamicStreaming" value="true" />
              <param name="@videoPlayer" value="$vidid" />
HILO;
        if (is_ssl()) {
            $embed .= '<param name="secureConnections" value="true" />
            <param name="secureHTMLConnections" value="true" />';
        }
        $embed .= '</object>';

        wp_enqueue_script('brightcove-experience', '//admin.brightcove.com/js/BrightcoveExperiences.js', null, null, true);
        add_action('wp_footer', array($this, 'create_experiences'), 100); // run after JS includes
        
        return $embed;
    }

    public function create_experiences() {
        echo '<script type="text/javascript">brightcove.createExperiences();</script>';
    }
    
    /**
     * Filter the post video html from get_the_post_video or the_post_video
     * 
     * @filter get_the_post_video_filter 
     */
    public function fvp_embed($html, $args) {
        // if it is webcast, we get the video url wrapper in some html
        // otherwise, it is the embed code for the relevant provider
        if (preg_match($this->embedded_video_pattern, $html, $matches)) {
            $url = $matches[0];
            $embed = $this->get_html($url);
            return preg_replace($this->embedded_video_pattern, $embed, $html);
        }
        
        return $html;
    }

    public function on_save($post_id){
        error_log('OembedWebcast::on_save: $post_id='.$post_id);
        // check if post has video
        if (function_exists('has_post_video') && has_post_video($post_id)) {
            $url = get_the_post_video_url($post_id);
            
            // if the video is a webcast url, then set the thumbnail and metadata
            if ($url && $this->video_id_from_url($url)) {
                $img_data = $this->set_thumbnail($post_id, $this->video_id_from_url($url));
                
                // update FVP post meta
                // WARNING: non-public API
                $meta = get_post_meta( $post_id, '_fvp_video', true );
                $meta['provider'] = 'raw'; // has to be raw for embed to work, probably bc there is not a real oembed provider
                if ($img_data) {
                    $meta = array_merge($meta, $img_data);
                }
                update_post_meta( $post_id, '_fvp_video', $meta );            
            }
        }
    }
    
    private function video_id_from_url($url) {
        // get video id from URL
        if (preg_match($this->embedded_video_pattern, $url, $matches)) {
            return $matches[2];
        } else {
            return false;
        }
    }
    
    private function is_thumb_set($thumb_id, $thumb_url) {
        if ($thumb_id) {
            $meta_url = get_post_meta($thumb_id, '_fvp_image_url', true);
            if ($meta_url && $meta_url == $thumb_url) {
                return true;
            }
        }
        return false;
    }
    
    private function get_video_thumb_url($video_url) {
        $cr = curl_init($video_url); 
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);     
        curl_exec($cr); 
        $info = curl_getinfo($cr);
        
        $sites_html = file_get_contents($info["url"]);
        $patt = '/og\:image[\'\"]\s*content=[\'\"](http:\/\/.+)[\'\"]/';
        preg_match($patt, $sites_html, $matches);
        $video_thumb = (!empty($matches) && isset($matches[1])) ? $matches[1] : null;
        
        if ($video_thumb) {
            // image url can include a query string that messes up the file name
            $video_thumb = preg_replace('/\?.*$/', '', $video_thumb);
        } 
        
        return $video_thumb;
    }

    private function download_thumbnail($url) {
        error_log('OembedWebcast: download_thumbnail: '.$url);
        $wp_filetype = wp_check_filetype($url, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $url,
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $file = array();
        $file['tmp_name'] = download_url($url);
        
        if ( is_wp_error( $file['tmp_name'] ) ) {
            error_log('Error downloading '.$url.': '.join(', ', $file['tmp_name']->get_error_messages()));
            return false;
        }
        
        // Insert into media library
        $type = image_type_to_extension(
            self::get_image_type( $file['tmp_name'] ),
            false
        );
        $title = ! empty( $meta['title'] ) ?
            $meta['title'] : basename( $url, $type );
        $file['name'] = sanitize_file_name( $title . '.' . $type );
        $img = media_handle_sideload( $file, $post_id );
        
        if (!is_wp_error($img)) {

            // save picture source url in post meta of media object
            update_post_meta( $img, '_fvp_image_url', $url );

            set_post_thumbnail( $post_id, $img );
            
            $img_data = array(
                'img_url'   => $url,
                'img'       => $img,
                'filename'  => basename($url),
                'noimg'     => false
            );
            return $img_data;
        }
    }
    
    /**
     * Get the video still and attach it to the post
     */
    private function set_thumbnail($post_id, $vid_id){
        $url = $this->provider_url.$vid_id;
        error_log("OembedWebcast::set_thumbnail: post_id=$post_id, url=$url");
        
        $img = get_post_thumbnail_id($post_id);
        $video_thumb = $this->get_video_thumb_url($url);
        
        if (empty($img)) {
            if ($video_thumb) {
                return $this->download_thumbnail($video_thumb);
            }
        }
        elseif (! $this->is_thumb_set($img, $video_thumb)) {
            delete_post_thumbnail($post_id);
            return $this->download_thumbnail($video_thumb);
        }
    }

    /**
     * exif_imagetype function is not available on all systems - fallback wrapper.
     * @from Featured Video Plus FVP_Backend
     *
     * @param  {string} $filename
     * @return Image mime type.
     */
    private static function get_image_type( $filename ) {
        if ( function_exists( 'exif_imagetype' ) ) {
            return exif_imagetype( $filename );
        } else {
            $img = getimagesize( $filename );
            if ( !empty( $img[2] ) ) {
                return $img[2];
            }
        }
        return false;
    }

}

$webcast_oembed = new OembedWebcast();
