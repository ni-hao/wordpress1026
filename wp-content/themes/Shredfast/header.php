<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Shredfast 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />

<link media="all" rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/green-style.css">
<link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:600' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Merriweather:300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/tabs.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/carousel.css">
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tabs.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tabs2.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/onebyone/jquery.onebyone.js"></script>              
	<script src="<?php echo get_template_directory_uri(); ?>/js/onebyone/jquery.touchwipe.min.js"></script> 
    <script type="text/javascript">	
	 $(document).ready(function() {		
       $('#banner').oneByOne({
			className: 'oneByOne1', // the wrapper's name
			easeType: 'random', //'fadeInLeft',  // the ease animation style
			width: 990,  // width of the slider
			height: 397, // height of the slider
			delay: 300,  // the delay of the touch/drag tween
			tolerance: 0.25, // the tolerance of the touch/drag  
			enableDrag: true,  // enable or disable the drag function by mouse
			showArrow: false,  // display the previous/next arrow or not
			showButton: true,  // display the circle buttons or not
			slideShow: true,  // auto play the slider or not
			slideShowDelay: 3000 // the delay millisecond of the slidershow
		});
	 });
    </script> 
	<link href="<?php echo get_template_directory_uri(); ?>/js/onebyone/css/jquery.onebyone.css" rel="stylesheet" type="text/css">
	<link href="<?php echo get_template_directory_uri(); ?>/js/onebyone/css/default.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/js/onebyone/css/animate.css"> 
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" />
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.prettyPhoto.js" type="text/javascript"></script>
	<script type="text/javascript">
	  $(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.70, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'facebook' /* light_rounded / dark_rounded / light_square / dark_square / facebook */
		});
	  });
	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.carouFredSel-5.5.0.js"></script>
	<script type="text/javascript">
		$(function() {
			//	Scrolled by user interaction
			$('#portfolio_carousel').carouFredSel({
				pagination: "#carousel_pager",
				auto: false
			});

		});
	</script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css"><![endif]-->

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body>
<div id="wrapper" >
	<div class="w1" >
		<div class="w2">
			<!-- header -->
			<header id="header">
				<div class="line"></div>
				<!-- todo logo -->
				<h1 class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>

					<nav id="nav">
						<?php shredfast_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					</nav><!-- #access -->
			</header><!-- #header -->

			<!-- main -->
			<div id="main">
