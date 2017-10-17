<?php
/*
 * Theme shortcodes
 * 
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */

/*
 * Render a column either inside a [row] or standalone
 * 
 * If used inside a [row] shortcode, all nested columns will be
 * given equal with unless the width is specified.
 * 
 * Attributes:
 *  width - The width of the column in standard Bootstrap form (a value 1-12)
 *  class - Optional additional CSS classes (space-separated)
 *
 * @filter shortcode_atts_column 
 */
function site_btsp_do_column_shortcode($atts, $content, $tagname) {
    $options = shortcode_atts(array(
        'width' => null,
        'class' => ''
    ), $atts, $tagname);
    
    $colwidth = $options['width'] ? $options['width'] : '{MD}';
    
    return sprintf('<div class="col-md-%s">', $colwidth) .
                do_shortcode($content) .
           '</div>';
}

/*
 * Render a row with columns
 * Usage: [row columns="#"] [column]...[/column][/row]
 * 
 * If the columns attribute is specified, then columns will be 
 * given equal size unless otherwise specified.
 * 
 * Attributes:
 *  columns - The number of columns in the row. Recommended values: 2, 3, 4, 6
 *  class   - Optional additional CSS classes (space-separated)
 * 
 * Note: not intended for complex layouts. Does not adjust for
 * uneven divisors of 12 (columns defined in framework)
 * 
 * @filter shortcode_atts_row
 */
function site_btsp_do_row_shortcode($atts, $content, $tagname) {
    $options = shortcode_atts(array(
        'columns'   => 1,
        'class'     => '',
    ), $atts, $tagname);
    
    $column_size = site_BTSP_COLUMNS / intval($options['columns']);
    
    $result = '<div class="row '.$options['class'].'">' .
        do_shortcode($content) .
        '</div>';
        
    $result = preg_replace('/{MD}/', ''.$column_size, $result);
    
    return $result;
}

/**
 * Render a content section with header.
 * Usage: [section title="My Section"] ... [/section]
 * 
 * Attributes:
 *  title - The section header
 *  id    - Optional CSS id
 *  class - Optional CSS class
 * 
 * @filter shortcode_atts_section
 */
function site_btsp_do_section_shortcode($atts, $content, $tagname) {
    $options = shortcode_atts(array(
        'title' => null,
        'class' => null,
        'id'    => null
    ), $atts, $tagname);
    
    $id = (!empty($options['id']) ? sprintf(' id="%s"', $options['id']) : '');
    $class = (!empty($options['class']) ? sprintf(' class="%s"', $options['class']) : '');
    $title = (!empty($options['title']) ? sprintf('<header><h3>%s</h3></header>', $options['title']) : '');
    
    $result = sprintf('<section%s%s>', $id, $class) .
        $title .
        do_shortcode($content) .
        '</section>';
    return $result;
}

add_shortcode('row', 'site_btsp_do_row_shortcode');
add_shortcode('column', 'site_btsp_do_column_shortcode');
add_shortcode('section', 'site_btsp_do_section_shortcode');
