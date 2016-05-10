<?php get_header(); ?>
    
    <div id="content" class="col-full">
		<section id="main" class="col-left">
            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  

		<?php if (have_posts()) : $count = 0; ?>
        
            <?php if (is_category()) { ?>
        	<header class="archive_header">
        		<span class="fl cat"><?php _e( 'Archive', 'woothemes' ); ?> | <?php echo single_cat_title(); ?></span> 
        		<span class="fr catrss"><?php $cat_id = get_cat_ID( single_cat_title( '', false ) ); echo '<a href="' . get_category_feed_link( $cat_id, '' ) . '">' . __( "Subscribe", "woothemes" ) . '</a>'; ?></span>
        	</header>        
        
            <?php } elseif (is_day()) { ?>
            <header class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( get_option( 'date_format' ) ); ?></header>

            <?php } elseif (is_month()) { ?>
            <header class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'F, Y' ); ?></header>

            <?php } elseif (is_year()) { ?>
            <header class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'Y' ); ?></header>

            <?php } elseif (is_author()) { ?>
            <header class="archive_header"><?php _e( 'Archive by Author', 'woothemes' ); ?></header>

            <?php } elseif (is_tag()) { ?>
            <header class="archive_header"><?php _e( 'Tag Archives:', 'woothemes' ); ?> <?php echo single_tag_title( '', true ); ?></header>
            
            <?php } ?>

        <?php
        	// Display the description for this archive, if it's available.
        	woo_archive_description();
        ?>
        
        <div class="fix"></div>
        
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <!-- Post Starts -->
            <article <?php post_class(); ?>>

                
				
				<header>
				
                	<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                
               		<?php woo_post_meta(); ?>
                
                </header>
                
                <aside class="meta">
	                <ul>
							<li class="date"><?php the_time('j F Y', '<time>', '</time>'); ?></li>
							<li class="author"><?php the_author_posts_link(); ?></li>
							<li class="category"><?php the_category(', '); ?></li>
							<!--<li class="tags"><?php // the_tags('Tagged: ',', ',''); ?></li>-->
							<li class="comments"><?php comments_popup_link(__( '0 Comments', 'woo themes' ), __( '1 Comment', 'woo themes' ), __( '% Comments', 'woo themes' )); ?></a></li>
							<?php the_tags( '<li class="tags">', ', ', '</li>' ); ?></li>
					</ul>
				</aside>
                
                <section class="entry">
                	<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] != 'content' ) woo_image( 'width=' . $woo_options['woo_thumb_w'].'&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail ' . $woo_options['woo_thumb_align'] ); ?> 
                    <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'content' ) { the_content( __( 'Read More...', 'woothemes' ) ); } else { the_excerpt(); } ?>
                    
                    <p class="post-more">      
	                	<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'excerpt' ) { ?>
	                    <span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'woothemes' ); ?>" class="button"><?php _e( 'Continue Reading &rarr;', 'woothemes' ); ?></a></span>
	                    <?php } ?>
	                </p>  
                </section><!-- /.entry --> 

            </article><!-- /.post -->
            
        <?php endwhile; else: ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>  
    
			<?php woo_pagenav(); ?>
                
		</section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>