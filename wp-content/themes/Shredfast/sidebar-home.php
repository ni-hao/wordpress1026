<?php
/**
 * The Home footer widget areas.
 *
 * @package WordPress
 * @since Shredfast 1.0
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-3'  )
		&& ! is_active_sidebar( 'sidebar-4' )
		&& ! is_active_sidebar( 'sidebar-5'  )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<!-- add -->
<div class="social-box"">
	<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-4' ); ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-5' ); ?>
	<?php endif; ?>
</div><!-- #supplementary -->