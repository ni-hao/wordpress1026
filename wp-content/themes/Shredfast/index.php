<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */
get_header();
?>

<!-- intro -->
<section class="intro">
    <p></p>
</section>
<div class="main-holder">
    <!-- content -->
    <section class="col" id="content">

        <?php if (have_posts()) : ?>


            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php get_template_part('content', get_post_format()); ?>

            <?php endwhile; ?>
        
            
                <?php shredfast_pagination($wp_query->max_num_pages);
                ?>

        <?php else : ?>

            <article id="post-0" class="article article-alt">
                <header class="entry-header">
                    <h1 class="entry-title"><?php _e('Nothing Found', 'twentyeleven'); ?></h1>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven'); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- .entry-content -->
            </article><!-- #post-0 -->

        <?php endif; ?>

    </section><!-- #content -->

    <?php get_sidebar(); ?>
    <?php get_footer(); ?>