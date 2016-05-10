<?php
/**
 * Search Template
 *
 * The search template is used to display search results from the native WordPress search.
 *
 * If no search results are found, the user is assisted in refining their search query in
 * an attempt to produce an appropriate search results set for the user's search query.
 *
 * @package WooFramework
 * @subpackage Template
 */

 get_header();
 global $woo_options;
?>     
    <div id="content" class="col-full">
		<section id="main" class="col-left">
            
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  	
		<?php if ( have_posts() ) { $count = 0; ?>
            
            <header class="archive_header"><?php echo __( 'Search results:', 'woothemes' ) . ' '; the_search_query(); ?></header>
                
            <?php while ( have_posts() ) { the_post(); $count++; ?>
                                                                        
            <!-- Post Starts -->
            <article <?php post_class(); ?>>
            	
            	<header>
            	
                	<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                	
                	<?php woo_post_meta(); ?>
                	
                </header>
                
                <section class="entry">
                    <?php the_excerpt(); ?>
                </section><!-- /.entry -->
                        
            </article><!-- /.post -->
                                                    
            <?php
            		} // End WHILE Loop
            	} else {
            ?>
            
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
            
            <?php } // End IF Statement ?>
			<?php woo_pagenav(); ?>
                
        </section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>