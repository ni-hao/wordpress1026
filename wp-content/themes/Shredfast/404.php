<?php
/**
 * The template for displaying 404 pages (Not Found).
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
                    ERROR 404 - Not Found
                </p>
                
            </section>
<!-- promo-intro -->		
            <section class="error-page">
                    <h1>404.</h1>
                    <p>We're sorry, but the page you were looking for doesn't exist.</p>
            </section>	
<?php get_footer(); ?>