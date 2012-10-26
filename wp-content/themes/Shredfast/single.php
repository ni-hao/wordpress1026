<?php
/**
 * The Template for displaying all single posts.
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
                            /* Queue the first post, that way we know
                             * what author we're dealing with (if that is the case).
                             *
                             * We reset this later so we can run the loop
                             * properly with a call to rewind_posts().
                             */
                            the_post();
                        ?>
                        <?php the_title(); ?>
                            <?php
                                    /* Since we called the_post() above, we need to
                                     * rewind the loop back to the beginning that way
                                     * we can run the loop properly, in full.
                                     */
                                    rewind_posts();
                            ?>

                     <?php endif; ?></p>
                
            </section>
		<div id="main-holder">
                    <!-- content -->
                    <section class="col" id="content">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</section><!-- #content -->
                
<?php get_sidebar();?>
<?php get_footer(); ?>