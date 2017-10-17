<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Right sidebar
 * Usage: get_sidebar('right')
 * @see https://developer.wordpress.org/reference/functions/get_sidebar/
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>
<div id="sidebar-right" class="col-md-3">

    <?php if (!dynamic_sidebar('right-sidebar')) : ?>
        
        <section class="widget">
            <header><h3><?php esc_html_e('In Archive', 'site'); ?></h3></header>
            <ul>
                <?php wp_get_archives(array('type' => 'monthly')); ?>
            </ul>
        </section>
    <?php endif; //end of right-sidebar ?>

</div><!-- end of #sidebar-right -->