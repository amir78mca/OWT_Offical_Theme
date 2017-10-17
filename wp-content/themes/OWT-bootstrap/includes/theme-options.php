<?php
/**
 * Enable customization settings for the theme 
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
class siteBootstrapOption {
    public $name, $type, $default, $label, $description;
    
    function __construct($name, $type='text', $default='', $label='', $description='') {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->label = empty($label) ? $name : $label;
        $this->description = $description;
    }
    
    private static $options;
    
    /**
     * Retrieve the option configuration with the given name
     * or all options if none specified. Returns null if 
     * the requested option name does not exist.
     * 
     * @return (siteBootstrapOption|array|null) 
     */
    public static function get($name=null) {
        if (empty(self::$options)) {
            self::init_options();
        }
        
        if (empty($name)) {
            return self::$options;
        } elseif (isset(self::$options[$name])) {
            return self::$options[$name];
        } else {
            return null;
        }
    }
    
    private static function init_options() {
        self::$options = array(
            'excerpt_length' =>
            new siteBootstrapOption('excerpt_length', 'int', 
                null, 
                _x('Excerpt length', 'admin', 'site')
               // _x('Optional custom length')
				),
                
            'site_header_title' =>
            new siteBootstrapOption('site_header_title', 'text', 
                _x('site Bootstrap', 'admin', 'site'), 
                _x('Site Header Title', 'admin', 'site'),
                _x('Title displayed in masthead', 'admin', 'site')),
                
            'site_header_subtitle' =>
            new siteBootstrapOption('site_header_subtitle', 'text', 
                _x('A lightweight UN theme', 'admin', 'site'),
                _x('Site Header Sub-title', 'admin', 'site'),
                _x('Second-level title in masthead', 'admin', 'site')),
                
            'site_header_short_subtitle' =>
            new siteBootstrapOption('site_header_short_subtitle', 'text', '',
                _x('Short Sub-title', 'admin', 'site'),
                _x('Shorter version of sub-title for smaller devices (optional)', 'admin', 'site')),
                
            'addthis_user' =>
            new siteBootstrapOption('addthis_user', 'text', '',
                _x('AddThis user token', 'admin', 'site'),
                _x('Identifier for <a href="http://AddThis.com" target="_blank">AddThis.com</a> account (social media follow and share buttons), e.g. <em style="white-space:nowrap">ra-######</em>', 'admin', 'site'))
        );
    }
}

function site_btsp_customize_register($wp_customize) {
    $options = siteBootstrapOption::get();
    
    $wp_customize->add_section( 'site-btsp-setting', array(
        'title'          => _x( 'site Bootstrap', 'admin', 'site' ),
        'priority'       => 35,
        'capability'     => 'edit_theme_options'
    ));
    
    foreach ($options as $name => $opt) {
        $wp_customize->add_setting($opt->name, array(
            'type' => 'theme_mod', 
            // 'theme_supports' => '', // Rarely needed.
            'default' => $opt->default,
            // 'transport' => 'refresh', // or postMessage
            'sanitize_callback' => $opt->type == 'int' ? 'absint' : 'sanitize_text_field',
            // 'sanitize_js_callback' => '', // Basically to_json.
        ));
    }
    
    foreach ($options as $name => $opt) {
        $wp_customize->add_control($opt->name, array(
          'type' => 'text',
          'priority' => 10, // Within the section.
          'section' => 'site-btsp-setting', // Required, core or custom.
          'label' => $opt->label,
          'description' => $opt->description,
        // 'input_attrs' => array(
        // 'class' => 'my-custom-class-for-js',
        // 'style' => 'border: 1px solid #900',
        // 'placeholder' => __( 'mm/dd/yyyy' ),
          // ),
          // 'active_callback' => 'is_front_page',
        ));    
    }
    
    
}
add_action( 'customize_register', 'site_btsp_customize_register' );

/*
 * Get the default value for the specified option
 * For use in templates
 */
function site_btsp_option_default($name) {
    $opt = siteBootstrapOption::get($name);
    if ($opt && $opt instanceof siteBootstrapOption) {
        return $opt->default;
    } else {
        return null;
    }
}

/*
 * Get the theme mod value.
 * Returns the default defined by the theme if no value is set.
 */
function site_btsp_get_theme_mod($name) {
    $default = site_btsp_option_default($name);
    return get_theme_mod($name, $default);
}
