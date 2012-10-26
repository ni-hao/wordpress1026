<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?>

<!-- article -->
<article id="post-<?php the_ID(); ?>" class="article article-alt">
    <div class="heading">
        <h2><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'twentyeleven'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <?php shredfast_posted_on(); ?>
    </div>	
    <nav class="add-info">
        <ul>
            <li><?php printf(__('%1$s', 'twentyeleven'), esc_html(get_the_date())); ?></li>
            <li class="data"><?php shredfast_get_current_post_tags(get_the_ID());
            ?></li>
            <li class="comments"><?php shredfast_comments_popup_link('<span class="leave-reply">' . __('Reply', 'twentyeleven') . '</span>', _x('1', 'comments number', 'twentyeleven'), _x('%', 'comments number', 'twentyeleven')); ?></li>

        </ul>
    </nav>

    <?php if (is_search()) : // Only display Excerpts for Search ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
    <?php else : ?>
        <figure class="visual">
            <?php if (has_post_thumbnail($post_id)) : ?>  
                <?php post_thumbnail(615, 284); ?>  
            <?php endif; ?>  
        </figure>
        <p>
            <?php echo get_post_excerpt(55, '') ?>
        </p>
        <a href="<?php echo get_permalink(); ?>">Read more ...</a> 
    <?php endif; ?>

    
</article><!-- #post-<?php the_ID(); ?> -->
