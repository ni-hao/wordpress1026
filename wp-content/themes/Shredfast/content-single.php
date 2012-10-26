<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>

<!-- article -->
<article id="post-<?php the_ID(); ?>" class="article article-alt">
    <div class="area">
         
   
        <footer class="info info-alt">
                <div class="img">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_ID()));?>">
                        <?php $author_id = get_the_author_ID(); echo shredfast_get_avatar($author_id, 80);?>
                    </a>
                </div>
                <nav class="info-list">
                        <ul>
                                <li><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')));?>"><?php echo get_the_author(); ?></a>
                                    <strong class="date"><?php echo esc_html(get_the_date()); ?></strong>
                                </li>
                                <li><?php echo get_the_tag_list('', __(', ', 'twentyeleven'));?></li>
                                <li><?php shredfast_comments_popup_link('<span class="leave-reply">' . __('Reply', 'twentyeleven') . '</span>', _x('1', 'comments number', 'twentyeleven'), _x('%', 'comments number', 'twentyeleven')); ?></li>
                        </ul>
                </nav>
                <nav class="social-networks">
                        <ul>
                                <li><a href="https://twitter.com/share?url=<?php the_permalink();?>&t=<?php the_title(); ?>" target="blank">Share on Twitter</a></li>
                                <li><a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" target="blank">Share on Facebook</a></li>
                        </ul>
                </nav>
        </footer>
        <div class="txt">
            <div class="entry-content">
                    <?php the_content(); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
            </div><!-- .entry-content -->
        </div>
        
    </div>
        <!-- tags -->
        <nav class="tags">
                <strong class="title">Tags:</strong>
                <ul>
                    <?php
                    shredfast_get_single_post_tags(get_the_ID());
                    ?>
                </ul>
        </nav>
        <!-- social-networks -->
        <nav class="social-networks">
                <strong class="title">Enjoyed this Post?</strong>
                <ul>
                        <li><a href="https://twitter.com/share?url=<?php the_permalink();?>&t=<?php the_title(); ?>" target="blank">Share on Twitter</a></li>
                        <li><a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" target="blank">Share on Facebook</a></li>
                </ul>
        </nav>
        
</article><!-- #post-<?php the_ID(); ?> -->
	<!-- about-author -->
    <article class="about-author">
            <div class="img align-left">
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_ID()));?>">
                        <?php $author_id = get_the_author_ID(); echo shredfast_get_avatar($author_id, 80);?>
                </a>
            </div>
            <div class="txt">
                    <h3>
                        <strong>
                            <?php  the_author_meta('user_firstname'); 
                            echo ' ';
                            the_author_meta('user_lastname');
                            ?>
                        </strong>
                    </h3>
                    <p><?php the_author_meta('description');?></p>
            </div>
    </article>

        <?php  
            $orig_post = $post;  
            global $post;  
            $tags = wp_get_post_tags($post->ID);  

            if ($tags) :
            $tag_ids = array();  
            foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;  
            $args=array(  
            'tag__in' => $tag_ids,  
            'post__not_in' => array($post->ID),  
            'posts_per_page'=>4, // Number of related posts to display.  
            'caller_get_posts'=>1  
            );  

            $my_query = new wp_query( $args );  

        ?>  
        <!-- article-list -->
        <section class="article-list">
                <h3 class="title">Related Articles:</h3>
                <ul>
                    <?php
                        while( $my_query->have_posts() ) : $my_query->the_post();  ?>
                        <li>
                                <div class="visual">
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(150,100)); ?></a>
                                </div>
                                <h4><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
                                    <?php if ( get_the_title() ) echo Shredfast_NoBreakWord(the_title('', '', false), 20); else the_ID(); ?>
                                </a></h4>
                        </li>
                     <?php endwhile;?>
                </ul>
        </section>
        <?php else :?>
       
        <!-- article-list -->
        <section class="article-list">
                <h3 class="title" style="width:230px;">No Related Articles:</h3>
        </section>
        
        <?php endif; wp_reset_query(); ?>
        
	<footer class="entry-meta">
		
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>

		<?php if ( get_the_author_meta( 'description' ) && ( ! function_exists( 'is_multi_author' ) || is_multi_author() ) ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 68 ) ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2><?php printf( __( 'About %s', 'twentyeleven' ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyeleven' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link	-->
			</div><!-- #author-description -->
		</div><!-- #author-info -->
		<?php endif; ?>
	</footer><!-- .entry-meta -->
