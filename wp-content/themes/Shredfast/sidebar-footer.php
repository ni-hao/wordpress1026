<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-6'  )
		&& ! is_active_sidebar( 'sidebar-7' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<!-- add -->
<div class="add">
	<div id="first" class="case" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-6' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-6' ); ?>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'sidebar-7' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-7' ); ?>
	<?php endif; ?>
	</div><!-- #first .widget-area -->
</div><!-- #supplementary -->