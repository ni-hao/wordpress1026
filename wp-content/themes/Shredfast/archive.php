<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
                        <?php if ( is_day() ) : ?>
                                <?php printf( __( 'Daily Archives: %s', 'twentyeleven' ), '' . get_the_date() . '' ); ?>
                        <?php elseif ( is_month() ) : ?>
                                <?php printf( __( 'Monthly Archives: %s', 'twentyeleven' ), '' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentyeleven' ) ) . '' ); ?>
                        <?php elseif ( is_year() ) : ?>
                                <?php printf( __( 'Yearly Archives: %s', 'twentyeleven' ), '' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentyeleven' ) ) . '' ); ?>
                        <?php else : ?>
                                <?php _e( 'Blog Archives', 'twentyeleven' ); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </section>

                      <div class="main-holder">
                        <!-- content -->
                        <section class="col" id="content">
                                <?php if ( have_posts() ) : ?>
                            
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
                            
                                <?php shredfast_pagination($wp_query->max_num_pages);?>

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