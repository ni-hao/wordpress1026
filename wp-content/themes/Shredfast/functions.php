<?php
/**
 * Shredfast functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width))
    $content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action('after_setup_theme', 'twentyeleven_setup');
    
if (!function_exists('twentyeleven_setup')):

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
     * functions.php file.
     *
     * @uses load_theme_textdomain() For translation/localization support.
     * @uses add_editor_style() To style the visual editor.
     * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
     * 	and backgrounds, and post formats.
     * @uses register_nav_menus() To add support for navigation menus.
     * @uses register_default_headers() To register the default custom header images provided with the theme.
     * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_setup() {

        /* Make Shredfast available for translation.
         * Translations can be added to the /languages/ directory.
         * If you're building a theme based on Shredfast, use a find and replace
         * to change 'twentyeleven' to the name of your theme in all the template files.
         */
        load_theme_textdomain('twentyeleven', get_template_directory() . '/languages');

        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();

        // Load up our theme options page and related code.
        require( get_template_directory() . '/inc/theme-options.php' );

        // Grab Shredfast's Ephemera widget.
        require( get_template_directory() . '/inc/widgets.php' );

        // Add default posts and comments RSS feed links to <head>.
        add_theme_support('automatic-feed-links');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menu('primary', __('Primary Menu', 'twentyeleven'));

        // Add support for a variety of post formats
        add_theme_support('post-formats', array('aside', 'link', 'gallery', 'status', 'quote', 'image'));

        $theme_options = twentyeleven_get_theme_options();
        if ('dark' == $theme_options['color_scheme'])
            $default_background_color = '1d1d1d';
        else
            $default_background_color = 'f1f1f1';

        // Add support for custom backgrounds.
        add_theme_support('custom-background', array(
            // Let WordPress know what our default background color is.
            // This is dependent on our current color scheme.
            'default-color' => $default_background_color,
        ));

        // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
        add_theme_support('post-thumbnails');

        // Add support for custom headers.
        $custom_header_support = array(
            // The default header text color.
            'default-text-color' => '000',
            // The height and width of our custom header.
            'width' => apply_filters('twentyeleven_header_image_width', 1000),
            'height' => apply_filters('twentyeleven_header_image_height', 288),
            // Support flexible heights.
            'flex-height' => true,
            // Random image rotation by default.
            'random-default' => true,
            // Callback for styling the header.
            'wp-head-callback' => 'twentyeleven_header_style',
            // Callback for styling the header preview in the admin.
            'admin-head-callback' => 'twentyeleven_admin_header_style',
            // Callback used to display the header preview in the admin.
            'admin-preview-callback' => 'twentyeleven_admin_header_image',
        );

        add_theme_support('custom-header', $custom_header_support);

        if (!function_exists('get_custom_header')) {
            // This is all for compatibility with versions of WordPress prior to 3.4.
            define('HEADER_TEXTCOLOR', $custom_header_support['default-text-color']);
            define('HEADER_IMAGE', '');
            define('HEADER_IMAGE_WIDTH', $custom_header_support['width']);
            define('HEADER_IMAGE_HEIGHT', $custom_header_support['height']);
            add_custom_image_header($custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback']);
            add_custom_background();
        }

        // We'll be using post thumbnails for custom header images on posts and pages.
        // We want them to be the size of the header image that we just defined
        // Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
        set_post_thumbnail_size($custom_header_support['width'], $custom_header_support['height'], true);

        // Add Shredfast's custom image sizes.
        // Used for large feature (header) images.
        add_image_size('large-feature', $custom_header_support['width'], $custom_header_support['height'], true);
        // Used for featured posts if a large-feature doesn't exist.
        add_image_size('small-feature', 634, 284, TRUE);
        
        add_image_size('tiny-feature', 70, 70, TRUE);
        
        add_image_size('homepage-feature', 274, 165, TRUE);

        // Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
        register_default_headers(array(
            'wheel' => array(
                'url' => '%s/images/headers/wheel.jpg',
                'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Wheel', 'twentyeleven')
            ),
            'shore' => array(
                'url' => '%s/images/headers/shore.jpg',
                'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Shore', 'twentyeleven')
            ),
            'trolley' => array(
                'url' => '%s/images/headers/trolley.jpg',
                'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Trolley', 'twentyeleven')
            ),
            'pine-cone' => array(
                'url' => '%s/images/headers/pine-cone.jpg',
                'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Pine Cone', 'twentyeleven')
            ),
            'chessboard' => array(
                'url' => '%s/images/headers/chessboard.jpg',
                'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Chessboard', 'twentyeleven')
            ),
            'lanterns' => array(
                'url' => '%s/images/headers/lanterns.jpg',
                'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Lanterns', 'twentyeleven')
            ),
            'willow' => array(
                'url' => '%s/images/headers/willow.jpg',
                'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Willow', 'twentyeleven')
            ),
            'hanoi' => array(
                'url' => '%s/images/headers/hanoi.jpg',
                'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Hanoi Plant', 'twentyeleven')
            )
        ));
    }

endif; // twentyeleven_setup

if (!function_exists('twentyeleven_header_style')) :

    /**
     * Styles the header image and text displayed on the blog
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_header_style() {
        $text_color = get_header_textcolor();

        // If no custom options for text are set, let's bail.
        if ($text_color == HEADER_TEXTCOLOR)
            return;

        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
        <?php
        // Has the text been hidden?
        if ('blank' == $text_color) :
            ?>
                #site-title,
                #site-description {
                    position: absolute !important;
                    clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                    clip: rect(1px, 1px, 1px, 1px);
                }
            <?php
        // If the user has set a custom color for the text use that
        else :
            ?>
                #site-title a,
                #site-description {
                    color: #<?php echo $text_color; ?> !important;
                }
        <?php endif; ?>
        </style>
        <?php
    }

endif; // twentyeleven_header_style

if (!function_exists('twentyeleven_admin_header_style')) :

    /**
     * Styles the header image displayed on the Appearance > Header admin panel.
     *
     * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_admin_header_style() {
        ?>
        <style type="text/css">
            .appearance_page_custom-header #headimg {
                border: none;
            }
            #headimg h1,
            #desc {
                font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
            }
            #headimg h1 {
                margin: 0;
            }
            #headimg h1 a {
                font-size: 32px;
                line-height: 36px;
                text-decoration: none;
            }
            #desc {
                font-size: 14px;
                line-height: 23px;
                padding: 0 0 3em;
            }
        <?php
        // If the user has set a custom color for the text use that
        if (get_header_textcolor() != HEADER_TEXTCOLOR) :
            ?>
                #site-title a,
                #site-description {
                    color: #<?php echo get_header_textcolor(); ?>;
                }
        <?php endif; ?>
            #headimg img {
                max-width: 1000px;
                height: auto;
                width: 100%;
            }
        </style>
        <?php
    }

endif; // twentyeleven_admin_header_style

if (!function_exists('twentyeleven_admin_header_image')) :

    /**
     * Custom header image markup displayed on the Appearance > Header admin panel.
     *
     * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_admin_header_image() {
        ?>
        <div id="headimg">
            <?php
            $color = get_header_textcolor();
            $image = get_header_image();
            if ($color && $color != 'blank')
                $style = ' style="color:#' . $color . '"';
            else
                $style = ' style="display:none"';
            ?>
            <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <div id="desc"<?php echo $style; ?>><?php bloginfo('description'); ?></div>
        <?php if ($image) : ?>
                <img src="<?php echo esc_url($image); ?>" alt="" />
        <?php endif; ?>
        </div>
    <?php
    }

endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length($length) {
    return 40;
}

add_filter('excerpt_length', 'twentyeleven_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 */
function twentyeleven_continue_reading_link() {
    return ' <a href="' . esc_url(get_permalink()) . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven') . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more($more) {
    return ' &hellip;' . twentyeleven_continue_reading_link();
}

add_filter('excerpt_more', 'twentyeleven_auto_excerpt_more');

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more($output) {
    if (has_excerpt() && !is_attachment()) {
        $output .= twentyeleven_continue_reading_link();
    }
    return $output;
}

add_filter('get_the_excerpt', 'twentyeleven_custom_excerpt_more');

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args($args) {
    $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'twentyeleven_page_menu_args');

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Shredfast 1.0
 */
function twentyeleven_widgets_init() {

    register_widget('Twenty_Eleven_Ephemera_Widget');

    register_sidebar(array(
        'name' => __('Main Sidebar', 'twentyeleven'),
        'id' => 'sidebar-1',
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Showcase Sidebar', 'twentyeleven'),
        'id' => 'sidebar-2',
        'description' => __('The sidebar for the optional Showcase Template', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Home Footer Area Left', 'shredfast'),
        'id' => 'sidebar-3',
        'description' => __('An optional widget area for your site footer', 'shredfast'),
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => __('Home Footer Area Center', 'shredfast'),
        'id' => 'sidebar-4',
        'description' => __('An optional widget area for your site footer', 'shredfast'),
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => __('Home Footer Area Right', 'shredfast'),
        'id' => 'sidebar-5',
        'description' => __('An optional widget area for your site footer', 'shredfast'),
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => __('Text Footer Area', 'shredfast'),
        'id' => 'sidebar-6',
        'description' => __('An optional widget area for your site footer', 'shredfast'),
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Menu Footer Area', 'shredfast'),
        'id' => 'sidebar-7',
        'description' => __('An optional widget area for your site footer', 'shredfast'),
        'before_widget' => '',
        'after_widget' => "",
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'twentyeleven_widgets_init');

if (!function_exists('twentyeleven_content_nav')) :

    /**
     * Display navigation to next/previous pages when applicable
     */
    function twentyeleven_content_nav($nav_id) {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo $nav_id; ?>">
                <h3 class="assistive-text"><?php _e('Post navigation', 'twentyeleven'); ?></h3>
                <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven')); ?></div>
                <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven')); ?></div>
            </nav><!-- #nav-above -->
        <?php
        endif;
    }

endif; // twentyeleven_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Shredfast 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
    if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches))
        return false;

    return esc_url_raw($matches[1]);
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
    $count = 0;

    if (is_active_sidebar('sidebar-3'))
        $count++;

    if (is_active_sidebar('sidebar-4'))
        $count++;

    if (is_active_sidebar('sidebar-5'))
        $count++;

    $class = '';

    switch ($count) {
        case '1':
            $class = 'one';
            break;
        case '2':
            $class = 'two';
            break;
        case '3':
            $class = 'three';
            break;
    }

    if ($class)
        echo 'class="' . $class . '"';
}

if (!function_exists('twentyeleven_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own twentyeleven_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="post pingback">
                    <p><?php _e('Pingback:', 'twentyeleven'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'twentyeleven'), '<span class="edit-link">', '</span>'); ?></p>
                                <?php
                                break;
            default :
                                ?>
                <li  id="comment-<?php comment_ID(); ?>">
                            <div class="area">
                                    <div class="comments-info">
                                        <?php
                                        printf(__('%1$s  %2$s', 'twentyeleven'), sprintf('<strong class="author">%s</strong>', get_comment_author_link()), 
                                                sprintf('<em class="date">%1$s</time></em>',  
                                                        sprintf(__('%1$s %2$s', 'twentyeleven'), get_comment_date(), get_comment_time())
                                        )
                                );
                                        ?>
                                            <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'twentyeleven'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                                    </div>
                                    <div class="txt">
                                            <p><?php comment_text(); ?></p>
                                    </div>
                            </div>
                    

                    <?php
                    break;
            endswitch;
        }

    endif; // ends check for twentyeleven_comment()

    if (!function_exists('twentyeleven_posted_on')) :

        /**
         * Prints HTML with meta information for the current post-date/time and author.
         * Create your own twentyeleven_posted_on to override in a child theme
         *
         * @since Shredfast 1.0
         */
        function twentyeleven_posted_on() {
            printf(__('<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven'), esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'twentyeleven'), get_the_author())), get_the_author()
            );
        }

    endif;

    if (!function_exists('shredfast_posted_on')) :

        /**
         * Prints HTML with meta information for the current author.
         * Create your own shredfast_posted_on to override in a child theme
         *
         * @since Shredfast 1.0
         */
        function shredfast_posted_on() {
            printf(__('<strong class="author">by <a href="%1$s" title="%2$s" rel="author">%3$s</a></strong>', 'twentyeleven'), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'twentyeleven'), get_the_author())), get_the_author()
            );
        }

    endif;

    /**
     * Adds two classes to the array of body classes.
     * The first is if the site has only had one author with published posts.
     * The second is if a singular post being displayed
     *
     * @since Shredfast 1.0
     */
    function twentyeleven_body_classes($classes) {

        if (function_exists('is_multi_author') && !is_multi_author())
            $classes[] = 'single-author';

        if (is_singular() && !is_home() && !is_page_template('showcase.php') && !is_page_template('sidebar-page.php'))
            $classes[] = 'singular';

        return $classes;
    }

    add_filter('body_class', 'twentyeleven_body_classes');

    /**
     * Displays a navigation menu.
     *
     * Optional $args contents:
     *
     * menu - The menu that is desired. Accepts (matching in order) id, slug, name. Defaults to blank.
     * menu_class - CSS class to use for the ul element which forms the menu. Defaults to 'menu'.
     * menu_id - The ID that is applied to the ul element which forms the menu. Defaults to the menu slug, incremented.
     * container - Whether to wrap the ul, and what to wrap it with. Defaults to 'div'.
     * container_class - the class that is applied to the container. Defaults to 'menu-{menu slug}-container'.
     * container_id - The ID that is applied to the container. Defaults to blank.
     * fallback_cb - If the menu doesn't exists, a callback function will fire. Defaults to 'wp_page_menu'. Set to false for no fallback.
     * before - Text before the link text.
     * after - Text after the link text.
     * link_before - Text before the link.
     * link_after - Text after the link.
     * echo - Whether to echo the menu or return it. Defaults to echo.
     * depth - how many levels of the hierarchy are to be included. 0 means all. Defaults to 0.
     * walker - allows a custom walker to be specified.
     * theme_location - the location in the theme to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
     * items_wrap - How the list items should be wrapped. Defaults to a ul with an id and class. Uses printf() format with numbered placeholders.
     *
     * @since 3.0.0
     *
     * @param array $args Arguments
     */
    function shredfast_nav_menu($args = array()) {
        $defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
        $args = wp_parse_args($args, $defaults);
        $args = apply_filters('wp_page_menu_args', $args);

        $menu = '';

        $list_args = $args;

        // Show Home in the menu
        if (!empty($args['show_home'])) {
            if (true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'])
                $text = __('Home');
            else
                $text = $args['show_home'];
            $menu .= '<li><a href="' . home_url('/') . '" title="' . esc_attr($text) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
            // If the front page is a page, add it to the exclude list
            if (get_option('show_on_front') == 'page') {
                if (!empty($list_args['exclude'])) {
                    $list_args['exclude'] .= ',';
                } else {
                    $list_args['exclude'] = '';
                }
                $list_args['exclude'] .= get_option('page_on_front');
            }
        }

        $list_args['echo'] = false;
        $list_args['title_li'] = '';
        $menu .= str_replace(array("\r", "\n", "\t"), '', wp_list_pages($list_args));

        if ($menu)
            $menu = '<ul>' . $menu . '</ul>';

        $menu = $menu . "\n";
        $menu = apply_filters('wp_page_menu', $menu, $args);
        if ($args['echo'])
            echo $menu;
        else
            return $menu;
    }
    function shredfast_get_current_post_tags($post_id){
        $tags = get_the_tags($post_id);
                $html = '';
        if(!empty($tags)){
            foreach ($tags as $tag) {
                   $tag_link = get_tag_link($tag->term_id);

                   $html .= "<a href='{$tag_link}' title='{$tag->name} Tag'>";
                   $html .= "{$tag->name},</a>";
            }
            echo substr($html, 0, strlen($html)-5).'</a>';
        }
 }
 
    function shredfast_get_single_post_tags($post_id){
        $tags = get_the_tags($post_id);
                $html = '';
                
        if(!empty($tags)){
            foreach ($tags as $tag) {
                   $tag_link = get_tag_link($tag->term_id);

                   $html .= "<li><a href='{$tag_link}' title='{$tag->name} Tag'>";
                   $html .= "{$tag->name},</a></li>";
            }
            echo substr($html, 0, strlen($html)-10).'</a>';
        }
 }
    
    
/**
 * Displays the link to the comments popup window for the current post ID.
 *
 * Is not meant to be displayed on single posts and pages. Should be used on the
 * lists of posts
 *
 * @since 0.71
 * @uses $wpcommentspopupfile
 * @uses $wpcommentsjavascript
 * @uses $post
 *
 * @param string $zero The string to display when no comments
 * @param string $one The string to display when only one comment is available
 * @param string $more The string to display when there are more than one comment
 * @param string $css_class The CSS class to use for comments
 * @param string $none The string to display when comments have been turned off
 * @return null Returns null on single posts and pages.
 */
function shredfast_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
	global $wpcommentspopupfile, $wpcommentsjavascript;

	$id = get_the_ID();

	if ( false === $zero ) $zero = __( 'No Comments' );
	if ( false === $one ) $one = __( '1 Comment' );
	if ( false === $more ) $more = __( '% Comments' );
	if ( false === $none ) $none = __( 'Comments Off' );

	$number = get_comments_number( $id );

	if ( 0 == $number && !comments_open() && !pings_open() ) {
		echo '<span' . ((!empty($css_class)) ? ' class="' . esc_attr( $css_class ) . '"' : '') . '>' . $none . '</span>';
		return;
	}

	if ( post_password_required() ) {
		echo __('Enter your password to view comments.');
		return;
	}

	echo '<a href="';
	if ( $wpcommentsjavascript ) {
		if ( empty( $wpcommentspopupfile ) )
			$home = home_url();
		else
			$home = get_option('siteurl');
		echo $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
		echo '" onclick="wpopen(this.href); return false"';
	} else { // if comments_popup_script() is not in the template, display simple comment link
		if ( 0 == $number )
			echo get_permalink() . '#respond';
		else
			comments_link();
		echo '"';
	}

	if ( !empty( $css_class ) ) {
		echo ' class="'.$css_class.'" ';
	}
	$title = the_title_attribute( array('echo' => 0 ) );

	echo apply_filters( 'comments_popup_link_attributes', '' );

	echo ' title="' . esc_attr( sprintf( __('Comment on %s'), $title ) ) . '">';
	comments_number( $zero, $one, $more );
	echo ' Comments</a>';
}

function shredfast_pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<nav class='paging'>\n<ul>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>First</a></li>";
         if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&laquo;</a></li>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<li class='active'><a href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&raquo;</a></li>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>Last</a></li>";
         echo "</ul>\n</nav>\n";
     }
}



if ( !function_exists( 'shredfast_get_avatar' ) ) :
/**
 * Retrieve the avatar for a user who provided a user ID or email address.
 *
 * @since 2.5
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return string <img> tag for the user's avatar
*/
function shredfast_get_avatar( $id_or_email, $size = '96', $default = '', $alt = false ) {
	if ( ! get_option('show_avatars') )
		return false;

	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );

	if ( !is_numeric($size) )
		$size = '96';

	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	if ( empty($default) ) {
		$avatar_default = get_option('avatar_default');
		if ( empty($avatar_default) )
			$default = 'mystery';
		else
			$default = $avatar_default;
	}

	if ( !empty($email) )
		$email_hash = md5( strtolower( trim( $email ) ) );

	if ( is_ssl() ) {
		$host = 'https://secure.gravatar.com';
	} else {
		if ( !empty($email) )
			$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
		else
			$host = 'http://0.gravatar.com';
	}

	if ( 'mystery' == $default )
		$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
	elseif ( 'blank' == $default )
		$default = includes_url('images/blank.gif');
	elseif ( !empty($email) && 'gravatar_default' == $default )
		$default = '';
	elseif ( 'gravatar_default' == $default )
		$default = "$host/avatar/?s={$size}";
	elseif ( empty($email) )
		$default = "$host/avatar/?d=$default&amp;s={$size}";
	elseif ( strpos($default, 'http://') === 0 )
		$default = add_query_arg( 's', $size, $default );

	if ( !empty($email) ) {
		$out = "$host/avatar/";
		$out .= $email_hash;
		$out .= '?s='.$size;
		$out .= '&amp;d=' . urlencode( $default );

		$rating = get_option('avatar_rating');
		if ( !empty( $rating ) )
			$out .= "&amp;r={$rating}";

		$avatar = "<img alt='{$safe_alt}' src='{$out}' class='rounded-corner-41' height='{$size}' width='{$size}' />";
	} else {
		$avatar = "<img alt='{$safe_alt}' src='{$default}' class='rounded-corner-41' height='{$size}' width='{$size}' />";
	}

	return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
}
endif;


require_once 'general-template.php';
require_once 'function-shortcode.php';
require_once 'custom-widget.php';

?>
