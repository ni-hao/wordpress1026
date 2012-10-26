<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */

get_header(); ?>

<!-- intro -->
<section class="intro">
    <p>
        <?php if ( have_posts() ) : ?>
            <?php
                        printf( __( 'Tag Archives: %s', 'twentyeleven' ), single_tag_title( '', false ));
                ?>
        <?php endif; ?>
    </p>
</section>

                    <div class="main-holder">
                        <!-- content -->
                        <section class="col" id="content">

			<?php if ( have_posts() ) : ?>
                                <?php
                                        $tag_description = tag_description();
                                        if ( ! empty( $tag_description ) ):?>
                            
                                            <article class="article article-alt">
                                                <?php echo apply_filters( 'tag_archive_meta', $tag_description );?>
                                                
                                            </article>
                                <?php endif;?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>
                            
                                <?php shredfast_pagination($wp_query->max_num_pages);
                                ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</section><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
