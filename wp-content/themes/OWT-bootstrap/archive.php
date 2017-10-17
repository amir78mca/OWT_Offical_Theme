<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Archive Template
 *
 * @package        site Bootstrap 
 * @version        Release: 1.0
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

<?php if (is_year()) : ?>
    <h2><?php echo apply_filters('site_btsp_archive_title', get_the_time('Y')); ?></h2>
<?php elseif (is_category()) : ?>
    <h2><?php echo apply_filters('site_btsp_archive_title', single_cat_title('', false)); ?></h2>
<?php else : ?>
    <h2><?php echo apply_filters('site_btsp_archive_title', get_the_archive_title()); // display generic title ?></h2>
<?php endif; ?>

<div class="row">    
    <div class="col-md-9">
    <?php
        // get first month; open section 
        $current_month = ''; 
        if (have_posts()){ 
            the_post();
            $current_month = date_i18n('F', get_the_date('U'));
            if (!is_year()){
                $current_month .= get_the_date(' Y'); 
            }
        ?>
            <section>
                <header>
                    <h3><?php echo $current_month; ?></h3>
                </header>
                <ul class="media-list">
        <?php
            rewind_posts();
        }
    ?>

    <?php 
        if (have_posts()) : while (have_posts()) : the_post();
            $month = date_i18n('F', get_the_date('U'));
            if (!is_year()){
                $month .= get_the_date(' Y'); 
            }
            
            // open new section if new month 
            if ($month != $current_month) :         
    ?>
                </ul>
            </section>
            <section>
                <header>
                    <h3><?php echo $month; ?></h3>
                </header>
                <ul class="media-list">
        <?php 
                $current_month = $month; 
            endif;
        ?>
                <li <?php post_class('media'); ?>>
                    <a class="pull-left flip media-object" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                    <div class="media-body">
                        <a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <?php
                            $sep = _x(', ','Archive category separator', 'site');
                            $post_meta = sprintf('<span class="category">%s %s</span>',
                                esc_html(__('Category:', 'site')), get_the_category_list($sep));
                            /**
                             * Filter extra post info displayed in archives listings.
                             * By default, the archives links for the post's categories are displayed.
                             * 
                             * Usage: in the Loop
                             * 
                             * @param $post_meta (string) The post meta HTML
                             * @return (string) The HTML to be output
                             */
                            echo apply_filters('site_btsp_archive_post_meta', $post_meta);
                        ?>
                    </div>
                </li>
    <?php endwhile; ?>
                </ul>
            </section>
    <?php endif; ?>
	<?php 
	$args = array(
		'type'          =>'list',
		'prev_text'          => _x('&laquo;', 'pagination', 'site'),
		'next_text'          => _x('&raquo;', 'pagination', 'site')
	);
	$return = paginate_links( $args );
	if(!empty($return)){
	?>
	<nav class="pagination-nav">
    	<h3><?php echo esc_html(__('More Stories', 'site')) ?></h3>
		<?php echo str_replace( "<ul class='page-numbers'>", '<ul class="pagination">', $return ); ?>
	</nav>
	<?php
	}
	?>

    </div><!-- end .col-md-9 -->

    <?php get_sidebar('right'); ?>
</div><!-- end .row -->
<?php get_footer(); ?>