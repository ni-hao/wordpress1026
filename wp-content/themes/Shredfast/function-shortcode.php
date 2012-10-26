<?php

/*
 * The ShortCodes of the theme.
 */

/**
 * 
 * @param type $atts; 'href': The url you want to navigate to;
 * 'color': The color of the button. the color include: {'green',
 * 'black', 'orange', 'blue', 'red', 'magenta', 'grey'}
 * @param type $content
 * @return a botton's HTML
 */
function buttons_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => 'caption',
                'href' => '#',
                    ), $atts));

    return '<a href="' . esc_attr($href) . '" class="btn ' .
            esc_attr($color) . '">' . $content . '</a>';
}

add_shortcode('button', 'buttons_shortcode');

/**
 * 
 * @param type $atts;
 * 'aim': {'info'=> a normal information box;
 * 'success' => a success information box;
 * 'warning'=> a warning information box;
 * 'error' => a error information box;
 * }
 * @param type $content
 * @return type a infobox's HTML
 */
function infoboxes_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
                'aim' => 'info',
                    ), $atts));
    switch ($aim) {
        case 'success':
            $box_aim = 'green';
            break;
        case 'warning':
            $box_aim = 'yellow';
            break;
        case 'error':
            $box_aim = 'red';
            break;
        case 'info':
            $box_aim = NULL;
            break;
        default:
            $box_aim = null;
            break;
    }
    $html = '<div class="message-box ' . $box_aim . '">' .
            '<p>' . $content . '</p>' . '</div>';
    return ($html);
}
add_shortcode('infobox', 'infoboxes_shortcode');


/**
 * 
 * @param type $atts 
 * title: The title of the column;
 * type: The type of the column.{12 => the column occupy one second of the width;
 * 13 => the column occupy one third of the width;
 * 14 => the column occupy one forth of the width;
 * 23 => the column occupy two thrid of the width;}
 * @param type $content
 * @return string
 */
function column_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
                'title' => null,
                'type' => '12',
                    ), $atts));
    $html = '<article class="col-'.$type.'">'.
            '<h4>'.$title.'</h4>'.
            '<p>'.$content.'</p>'.
        '</article>';
    return $html;
}
add_shortcode('column', 'column_shortcode');

/**
 * The container of columns
 * @param type $atts
 * @param type $content
 * @return string
 */
function columnbox_shortcode($atts, $content = null) {
    $html = '<div class="container">'.
    do_shortcode($content).
            '</div>';
    return $html;
            
}
add_shortcode('columnbox', 'columnbox_shortcode');


/**
 * The describe of different package.
 * @param type $atts
 * @param type $content
 * @return string
 */
function package_describe_shortcode($atts, $content = null){
    $html = '<li>'.$content.'</li>';
    return $html;
}
add_shortcode('des', 'package_describe_shortcode');

/**
 * The container of the package description. The container will be placed in
 * the price shortcode.
 * @param type $atts
 * @param type $content
 * @return string
 */
function package_info_shortcode($atts, $content=null){
    $html = '<ul class="package-list">'.
    do_shortcode($content).
            '</ul>';
    return $html;
}
add_shortcode('info', 'package_info_shortcode');

/**
 * The container of price. The mark include button mark and 
 * price info mark.
 * @param type $atts
 * @param type $content
 * @return string
 */
function price_shortchode($atts, $content = null){
    extract(shortcode_atts(array(
                'title' => null,
                'price' => null,
                    ), $atts));
    $html = '<article class="col-13 price-box"><div class="heading"><h4>'.
            $title.'</h4><span class="price">'.
            $price.'</span></div><div class="holder">'.
            do_shortcode($content).'</div></article>';
    return $html;
}
add_shortcode('price', 'price_shortchode');

/**
 * This mark include multi price marks.
 * @param type $atts
 * @param type $content
 * @return string
 */
function price_table_shortcode($atts, $content = null){
    $html = '<div class="container">'.
    do_shortcode($content).
            '</div>';
    return $html;
}
add_shortcode('prices', 'price_table_shortcode');

/**
 * Add a horizon tabs. The content of title and article of each tab 
 * should not include '[' and ']' character;
 * @param type $atts
 * @param type $content
 */
function tabs_shortcode($atts, $content = null)
{
    if($content != null){
        $html = '<section class="col-23">';
        $pattern = '/\[tab[^\]]+title=[\'"]([^\]]*)[\'"]/';
        preg_match_all($pattern, $content, $matches);
        if (count($matches[1]) !=0) {
            $html .='<nav class="tabset"><ul class="tabset">';
            $index = 0;
            foreach ($matches[1] as $value) {
                $index++;
                $html .= tabs_nav($value, $index);
            }
            $html.='</ul></nav>';
        }
        
        $pattern = '/\[[^\/]+\]([^\[\]]*)\[\/tab\]/';
        preg_match_all($pattern, $content, $matches);
        if (count($matches[1]) !=0) {
            $index = 0;
            foreach ($matches[1] as $value) {
                $index++;
                $html .= tabs_article($value, $index);
            }
        }
        $html .= '</section>';
    }
    return $html;
}
add_shortcode('tabs', 'tabs_shortcode');

function tabs_nav($title, $index){
    $html = '<li';
    if($index == 1){
        $html.=' class="active"';
    }
    $html .='><a href="#tab-'.$index.'" class="tab1">'.
        $title.'</a></li>';
    return $html;
}

function tabs_article($content, $index){
    
    $html ='<article id="tab-'.$index.'" class="tab-content">';
    $html.= $content;
    $html.='</article>';
    return $html;
}

/**
 * Add a vertical tabs. The content of title and article of each tab 
 * should not include '[' and ']' character;
 * @param type $atts
 * @param type $content
 */
function vertical_tabs_shortcode($atts, $content = null)
{
    if($content != null){
        $html = '<section class="container">';
        $pattern = '/\[tab[^\]]+title=[\'"]([^\]]*)[\'"]/';
        preg_match_all($pattern, $content, $matches);
        if (count($matches[1]) !=0) {
            $html .='<nav class="tabsetnav col-14"><ul class="tabset2">';
            $index = 0;
            foreach ($matches[1] as $value) {
                $index++;
                $html .= vertical_tabs_nav($value, $index);
            }
            $html.='</ul></nav>';
        }
        
        $pattern = '/\[[^\/]+\]([^\[\]]*)\[\/tab\]/';
        preg_match_all($pattern, $content, $matches);
        if (count($matches[1]) !=0) {
            $index = 0;
            foreach ($matches[1] as $value) {
                $index++;
                $html .= vertical_tabs_article($value, $index);
            }
        }
        $html.= '</section>';
    }
    return $html;
}
add_shortcode('vtabs', 'vertical_tabs_shortcode');

function vertical_tabs_nav($title, $index){
    $html = '<li';
    if($index == 1){
        $html.=' class="active"';
    }
    $html .='><a href="#vertical-tab-'.$index.'" class="tab1">'.
        $title.'</a></li>';
    return $html;
}

function vertical_tabs_article($content, $index){
    
    $html ='<article id="vertical-tab-'.$index.'" class="tab-content2">';
    $html.= $content;
    $html.='</article>';
    return $html;
}

/**
 * Add a Toggle tabs. 
 * @param type $atts
 * @param type $content
 */
function toggleBox_shortcode($atts, $content = null)
{
    if($content != null){
        $html = '<section class="container"><ul class="menu col-13 accordion">'.
                do_shortcode($content).
            '</ul></section>';
    }
    return $html;
}
add_shortcode('toggles', 'toggleBox_shortcode');

function toggle_shortcode($atts, $content = null){
    extract(shortcode_atts(array(
                'title' => null,
                    ), $atts));
    $html = '<li><h4><a href="#" class="opener">'.
            $title.'</a></h4>'.
            '<div class="case"><div class="holder">'.
            $content.
            '</div></div></li>';
    return $html;
}
add_shortcode('toggle', 'toggle_shortcode');



//function tab_nav_shortcode($atts, $content = null){
//    extract(shortcode_atts(array(
//                'tabId' => null,
//                'title' => null,
//                'isActive' => 0,
//                    ), $atts));
//    $html = '<li';
//    if($isActive){
//        $html.= ' class="active"';
//    }
//    $html .='><a href="#tab-' . $tabId . '" class="tab1">'.
//            $title.'</a></li>';
//    return $html;
//}
//add_shortcode('nav', 'tabs_shortcode');
//
//function tab_navbox_shortcode($atts, $content = null){
//    $html = '<nav class="tabset"><ul class="tabset">'.
//            do_shortcode($content).
//            '</ul></nav>';
//    
//    return $html;
//}
//add_shortcode('navs', 'tab_navbox_shortcode');
//
//function tab_article_shortCode($atts, $content=null){
//    extract(shortcode_atts(array(
//                'tabId' => null,
//                    ), $atts));
//    $html = '<article id="tab-'.$tabId.'" class="tab-content"><p>'.
//        $content.'</p></article>';
//    return $html;
//}
//add_shortcode('article', 'tab_navbox_shortcode');
//
//function tab_articlebox_shortCode($atts, $content=null){
//    return do_shortcode($content);
//}
//add_shortcode('articles', 'tab_articlebox_shortCode');

?>