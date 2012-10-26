<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>

			</div><!-- #main -->
		</div><!-- .w2 -->

		<!-- footer -->
		<footer id="footer" role="contentinfo">
                    <div class="footer-holder">
				<?php
					/* A sidebar in the footer? Yep. You can can customize
					 * your footer with three columns of widgets.
					 */
						get_sidebar( 'footer' );?>
                                
                        
                        <?php do_action( 'twentyeleven_credits' ); ?>
                    </div> <!-- .footer-holder -->
		</footer><!-- #footer -->
	</div><!-- .w1 -->
</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>