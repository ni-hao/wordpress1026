<?php
/**
 * The template used for displaying page content in page-fullwidth.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
        
        <div class="headline solid center">
                <h3>Our creative team</h3>
        </div>
        <div>
                <!-- visual-list -->
                <ul class="visual-list">
                        <li class="col-14">
                                <div class="visual">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/team-1.jpg" width="194" height="194" alt="image description" >
                                </div>
                                <h5>Elizabeth Van Carell</h5>
                                <p>Senior designer</p>
                        </li>
                        <li class="col-14">
                                <div class="visual">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/team-2.jpg" width="194" height="194" alt="image description" >
                                </div>
                                <h5>Jemaine Van Clement</h5>
                                <p>Senior Visual Designer</p>
                        </li>
                        <li class="col-14">
                                <div class="visual">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/team-3.jpg" width="194" height="194" alt="image description" >
                                </div>
                                <h5>Julia Mcbride</h5>
                                <p>Business Development Director</p>
                        </li>
                        <li class="col-14">
                                <div class="visual">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/team-4.jpg" width="194" height="194" alt="image description" >
                                </div>
                                <h5>Christian Mcbrayer</h5>
                                <p>Creative Director</p>
                        </li>
                </ul>
        </div>
