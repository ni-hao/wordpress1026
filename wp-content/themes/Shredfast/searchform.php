<?php
/**
 * The template for displaying search forms in Shredfast
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>
	<form method="get" class="search" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<fieldset>
			<input type="text" class="text" name="s" id="s" value="<?php esc_attr_e( 'Click or type here to search', 'twentyeleven' ); ?>" />
			<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" />
		</fieldset>
	</form>
