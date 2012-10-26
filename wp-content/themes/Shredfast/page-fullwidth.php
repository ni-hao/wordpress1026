<?php
/**
 * Template Name: Fullwidth Template
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */

get_header(); ?>
<!-- intro -->
            <section class="intro">
                <?php the_widget('Shredfast_Widget_Search', array(),  array('before_widget' => '', 'after_widget' => '')); ?>
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

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'fullwidth' ); ?>

					

				<?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>