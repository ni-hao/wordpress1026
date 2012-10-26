<?php

/**
 * Display search form.
 *
 * Will first attempt to locate the searchform.php file in either the child or
 * the parent, then load it. If it doesn't exist, then the default search form
 * will be displayed. The default search form is HTML, which will be displayed.
 * There is a filter applied to the search form HTML in order to edit or replace
 * it. The filter is 'get_search_form'.
 *
 * This function is primarily used by themes which want to hardcode the search
 * form into the sidebar and also by the search widget in WordPress.
 *
 * There is also an action that is called whenever the function is run called,
 * 'get_search_form'. This can be useful for outputting JavaScript that the
 * search relies on or various formatting that applies to the beginning of the
 * search. To give a few examples of what it can be used for.
 *
 * @since 2.7.0
 * @param boolean $echo Default to echo and not return the form.
 */
function shredfast_get_search_form($echo = true) {
    do_action('get_search_form');

    $search_form_template = locate_template('searchform.php');
    if ('' != $search_form_template) {
        require($search_form_template);
        return;
    }

    $form = '<form role="search" method="get" id="searchform" action="' . esc_url(home_url('/')) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="' . esc_attr__('Search') . '" />
	</div>
	</form>';

    if ($echo)
        echo apply_filters('get_search_form', $form);
    else
        return apply_filters('get_search_form', $form);
}


/**
 * List comments
 *
 * Used in the comments.php template to list comments for a particular post
 *
 * @since 2.7.0
 * @uses Walker_Comment
 *
 * @param string|array $args Formatting options
 * @param array $comments Optional array of comment objects. Defaults to $wp_query->comments
 */
function Shredfast_list_comments($args = array(), $comments = null ) {
	global $wp_query, $comment_alt, $comment_depth, $comment_thread_alt, $overridden_cpage, $in_comment_loop;

	$in_comment_loop = true;

	$comment_alt = $comment_thread_alt = 0;
	$comment_depth = 1;

	$defaults = array('walker' => null, 'max_depth' => '', 'style' => 'ul', 'callback' => null, 'end-callback' => null, 'type' => 'all',
		'page' => '', 'per_page' => '', 'avatar_size' => 32, 'reverse_top_level' => null, 'reverse_children' => '');

	$r = wp_parse_args( $args, $defaults );

	// Figure out what comments we'll be looping through ($_comments)
	if ( null !== $comments ) {
		$comments = (array) $comments;
		if ( empty($comments) )
			return;
		if ( 'all' != $r['type'] ) {
			$comments_by_type = &separate_comments($comments);
			if ( empty($comments_by_type[$r['type']]) )
				return;
			$_comments = $comments_by_type[$r['type']];
		} else {
			$_comments = $comments;
		}
	} else {
		if ( empty($wp_query->comments) )
			return;
		if ( 'all' != $r['type'] ) {
			if ( empty($wp_query->comments_by_type) )
				$wp_query->comments_by_type = &separate_comments($wp_query->comments);
			if ( empty($wp_query->comments_by_type[$r['type']]) )
				return;
			$_comments = $wp_query->comments_by_type[$r['type']];
		} else {
			$_comments = $wp_query->comments;
		}
	}

	if ( '' === $r['per_page'] && get_option('page_comments') )
		$r['per_page'] = get_query_var('comments_per_page');

	if ( empty($r['per_page']) ) {
		$r['per_page'] = 0;
		$r['page'] = 0;
	}

	if ( '' === $r['max_depth'] ) {
		if ( get_option('thread_comments') )
			$r['max_depth'] = get_option('thread_comments_depth');
		else
			$r['max_depth'] = -1;
	}

	if ( '' === $r['page'] ) {
		if ( empty($overridden_cpage) ) {
			$r['page'] = get_query_var('cpage');
		} else {
			$threaded = ( -1 != $r['max_depth'] );
			$r['page'] = ( 'newest' == get_option('default_comments_page') ) ? get_comment_pages_count($_comments, $r['per_page'], $threaded) : 1;
			set_query_var( 'cpage', $r['page'] );
		}
	}
	// Validation check
	$r['page'] = intval($r['page']);
	if ( 0 == $r['page'] && 0 != $r['per_page'] )
		$r['page'] = 1;

	if ( null === $r['reverse_top_level'] )
		$r['reverse_top_level'] = ( 'desc' == get_option('comment_order') );

	extract( $r, EXTR_SKIP );

	if ( empty($walker) )
		$walker = new Walker_Comment;

	$walker->paged_walk($_comments, $max_depth, $page, $per_page, $r);
	$wp_query->max_num_comment_pages = $walker->max_pages;

	$in_comment_loop = false;
}

/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0.0
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function Shredfast_comment_form( $args = array(), $post_id = null ) {
	global $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<div class="row">' . '<label for="author">' . __( 'Your Name' ). ( $req ? '<span class="required">*</span>' : '' ) . '</label> '  .
		            '<input id="author"class="text"  name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		'email'  => '<div class="row"><label for="email">' . __( 'Your Email' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email"class="text"  name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
		'url'    => '<div class="row"><label for="url">' . __( 'Your Website' ) . '</label>' .
		            '<input id="url" class="text" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );
	$defaults = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<div class="row"><label for="message">' . _x( 'Your Message', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></label></div>',
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => __( 'Leave a Reply' ),
		'title_reply_to'       => __( 'Leave a Reply to %s' ),
		'cancel_reply_link'    => __( 'Cancel reply' ),
		'label_submit'         => __( 'Post Comment' ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open( $post_id ) ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
                    <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
                            <?php echo $args['must_log_in']; ?>
                            <?php do_action( 'comment_form_must_log_in_after' ); ?>
                    <?php else : ?>
			<form id="respond" action="<?php echo site_url( '/wp-comments-post.php' ); ?>" class="comments-form" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
                            <fieldset>
                                <div class="headline">
                                        <h2><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> </h2>
                                        <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small>
                                </div>
                                <?php do_action( 'comment_form_top' ); ?>
                                <?php if ( is_user_logged_in() ) : ?>
                                        <?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
                                        <?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
                                <?php else : ?>
                                        <?php
                                        do_action( 'comment_form_before_fields' );
                                        foreach ( (array) $args['fields'] as $name => $field ) {
                                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
                                        }
                                        do_action( 'comment_form_after_fields' );
                                        ?>
                                <?php endif; ?>
                                
                                
                                <?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
                                <?php  $args['comment_notes_after']; ?>
                                <span class="submit">Post
                                        <input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
                                        <?php comment_id_fields( $post_id ); ?>
                                </span>
                                <?php do_action( 'comment_form', $post_id ); ?>
    
                                
                                </fieldset>
			</form><!-- #respond -->

                        <?php endif; ?>
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
	<?php
}

function Shredfast_NoBreakWord($str="", $max=0) {
    if($str === ""){
        return "";
    }
    
    if(strlen($str) <= $max){
        return $str;
    }
    
    $test_str = substr($str, $max, 1);

    if ($test_str == " ") {

        echo substr($str, 0, $max);
    } else {

        while ($test_str <> " ") {

            $test_str = substr($str, $max, 1);

            if ($test_str == " ") {

                return substr($str, 0, $max);
            } else {

                $max = $max - 1;
            }
        }
    }
}

/**
 * 
 * @param type $length
 * if length is 0, apear all.
 * @return string
 */
function get_title_excerpt($length = 20)
{
    $html = '<a href="'. get_permalink().'" title="'.
            (esc_attr(get_the_title() ? get_the_title() : get_the_ID())).'">';
    if ( get_the_title() ){
        if($length ==0){
            $excerptTitle = get_the_title();
        }  else {
            
            $excerptTitle = Shredfast_NoBreakWord(get_the_title(), $length);
            if(strlen(get_the_title()) > $length){
                 $excerptTitle .= '...';
            }
        }
    }
    else
    {
        $excerptTitle = the_ID();
    }
    $html .= $excerptTitle. '</a>';
    
    return $html;
}

function get_post_excerpt($length = 55, $more_text = '...')
{
    if ( '' == $text ) {
            $text = get_the_content('');

            $text = strip_shortcodes( $text );

            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);
            $text = wp_trim_words( $text, $length, $more_text );
    }
    return $text;
}

function Shredfast_top_tags() {
        $tags = get_tags();
        if (empty($tags))
                return;
        $counts = $tag_links = array();
        foreach ( (array) $tags as $tag ) {
                $counts[$tag->name] = $tag->count;
                $tag_links[$tag->name] = get_tag_link( $tag->term_id );
        }
        asort($counts);
        $counts = array_reverse( $counts, true );
        $i = 0;
        foreach ( $counts as $tag => $count ) {
                $i++;
                $tag_link = clean_url($tag_links[$tag]);
                $tag = str_replace(' ', '&nbsp;', wp_specialchars( $tag ));
                if($i > 19 ){
                    break;    
                }else if ($i == count($counts)){
                    echo "<li><a href=\"$tag_link\">$tag</a></li>";
                }else {
                    echo "<li><a href=\"$tag_link\">$tag, </a></li>";
                }
                
        }
}


function post_thumbnail( $width = 100,$height = 80 ){
    global $post;
    if( has_post_thumbnail() ){    //if thumb nail exists, display it.
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
        echo $post_timthumb;
    } else {
        $post_timthumb = '';
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $index_matches);    //the post first image.
        $first_img_src = $index_matches [1];    //the image src
        if( !empty($first_img_src) ){    //if post include image
            $path_parts = pathinfo($first_img_src);    //gain image src information
            $first_img_name = $path_parts["basename"];    // image name
            $first_img_pic = get_template_directory_uri(). '/cache/'.$first_img_name;    //image url after save
            $first_img_file = get_template_directory(). '/cache/'.$first_img_name;    //image saved directory
            $expired = 604800;    //expired time
            if ( !is_file($first_img_file) || (time() - filemtime($first_img_file)) > $expired ){
                copy($first_img_src, $first_img_file);    //Save remote image to local
                $post_timthumb = '<img src="'.$first_img_src.'" alt="'.$post->post_title.'" class="thumb" />';
            }
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$first_img_pic.'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
        } else {    //if no image, display the default image.
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.get_bloginfo("template_url").'/images/default_thumb.jpg&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
           
        }
        echo $post_timthumb;
    }
}
?>