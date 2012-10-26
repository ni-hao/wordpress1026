<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$text = '[tab title="one"]one content[/tab][tab title="two"]two content[/tab]';
preg_match_all('/\[tab[^\]]+title=[\'"]([^\]]*)[\'"]/', $text, $matches);

if (count($matches[1]) !=0) {
    foreach ($matches[1] as $value) {
        echo $value . '<br>';
    }
}

preg_match_all('/\[[^\/]+\]([^\[\]]*)\[\/tab\]/', '[tab title="one"]one content[/tab][tab title="two"]two content[/tab]', $matches);
if (count($matches[1]) !=0) {
    foreach ($matches[1] as $value) {
        echo $value . '<br>';
    }
}

$pattern = '/(?!3[2-9])[0-3][[:digit:]]/';
preg_match_all($pattern, '32', $matches);
foreach ($matches as $match) {
    foreach ($match as $value) {
        echo $value . '<br>';
    }
}

$pattern = '/\$(\d+\.?\d*)/';
preg_match_all($pattern, '$10.9, $20.56', $matches);
foreach ($matches as $match) {
    foreach ($match as $value) {
        echo $value . '<br>';
    }
}
copy($pattern, $text)
?>

<?php
function post_thumbnail( $width = 100,$height = 80 ){
    global $post;
    if( has_post_thumbnail() ){    //如果有缩略图，则显示缩略图
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
        echo $post_timthumb;
    } else {
        $post_timthumb = '';
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $index_matches);    //获取日志中第一张图片
        $first_img_src = $index_matches [1];    //获取该图片 src
        if( !empty($first_img_src) ){    //如果日志中有图片
            $path_parts = pathinfo($first_img_src);    //获取图片 src 信息
            $first_img_name = $path_parts["basename"];    //获取图片名
            $first_img_pic = get_bloginfo('wpurl'). '/cache/'.$first_img_name;    //文件所在地址
            $first_img_file = ABSPATH. 'cache/'.$first_img_name;    //保存地址
            $expired = 604800;    //过期时间
            if ( !is_file($first_img_file) || (time() - filemtime($first_img_file)) > $expired ){
                copy($first_img_src, $first_img_file);    //远程获取图片保存于本地
                $post_timthumb = '<img src="'.$first_img_src.'" alt="'.$post->post_title.'" class="thumb" />';    //保存时用原图显示
            }
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$first_img_pic.'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
        } else {    //如果日志中没有图片，则显示默认
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/images/default_thumb.gif" alt="'.$post->post_title.'" class="thumb" />';
        }
        echo $post_timthumb;
    }
}
?>