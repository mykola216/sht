<?php
/**
 * Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header();
?>

    <!-- #content Starts -->
<?php woo_content_before(); ?>
    <div id="content" class="col-full">

        <div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">
                <?php
                woo_loop_before();

                if (have_posts()) { $count = 0;
                    while (have_posts()) { the_post(); $count++;
                        woo_get_template_part( 'content', 'page' ); // Get the page content template file, contextually.
                    }
                }

                woo_loop_after();
                ?>

                <?php if(is_page(2677)) :

                $title_our_cli = get_field('title_our_clients', 'option');
                $link_our_cli = get_field('link_our_clients', 'option');
                $text_our_cli = get_field('text_our_clients', 'option');
                ?>

                    <section class="clients">
                        <a href="<?php echo $link_our_cli; ?>" class="home-title-module"><?php echo $title_our_cli; ?></a>
                        <?php echo $text_our_cli; ?>

                        <?php if ( have_rows('page_list_of_our_clients_repeater') ) : ?>
                            <div class="wrap-loop owl-carousel">
                                <?php while ( have_rows('page_list_of_our_clients_repeater') ) : the_row();
                                    $img = get_sub_field('img');
                                    $link = get_sub_field('link');
                                    ?>
                                    <a  href="<?php echo $link; ?>" rel="nofollow">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </section>

                <?php endif; ?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

        </div><!-- /#main-sidebar-container -->

        <?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
<?php woo_content_after(); ?>

<?php get_footer(); ?>