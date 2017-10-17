<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Home page widget area, three columns for Home Widgets 1, 2 & 3
 * Usage: get_sidebar('home')
 * @see https://developer.wordpress.org/reference/functions/get_sidebar/
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>

<div id="home-widgets" class="row">

    <div class="col-md-4">
        <?php if (!dynamic_sidebar('home-widget-1')) : ?>
            <section class="widget">
                <header><h3>Home Widget 1</h3></header>
                <div>
                    <p>Widget text</p>
                </div>
            </section>
        <?php endif; //end of home-widget-1 ?>
    </div>

    <div class="col-md-4">
        <?php if (!dynamic_sidebar('home-widget-2')) : ?>
            <section class="widget">
                <header><h3>Home Widget 2</h3></header>
                <div>
                    <p>Widget text</p>
                </div>
            </section>
        <?php endif; //end of home-widget-2 ?>
    </div>
    
    <div class="col-md-4">
        <?php if (!dynamic_sidebar('home-widget-3')) : ?>
            <section class="widget">
                <header><h3>Home Widget 3</h3></header>
                <div>
                    <p>Widget text</p>
                </div>
            </section>
        <?php endif; //end of home-widget-3 ?>
    </div>

</div><!-- end of #home-widgets -->