<?php

if(!defined('ABSPATH')) {
    die('Sorry we can\'t cater you, you tried to get in from the wrong way');
}

if(!empty($sharekar['inner']['enable_sharekar'])){

	sharekar_init('inner');
}

if(!empty($sharekar['float']['enable_sharekar'])){

	sharekar_init('float');
}

sharekar_webshare_init();


function sharekar_init($type)
{
    global $sharekar;

	if(empty($sharekar[$type]['enable_sharekar'])){
		return;
	}
	
	if(!empty($sharekar[$type]['show_sharecount'])){
		include_once SHAREKAR_DIR . '/sharecount.php';
	}
	
	add_action('wp_head', 'sharekar_common_css');
	
    switch($sharekar[$type]['button_position'])
    {
    case 'before':
        add_filter('the_content', 'sharekar_before_content', 10);
        break;
            
    case 'after':
        add_filter('the_content', 'sharekar_after_content', 10);
        break;
            
    case 'both':
        add_filter('the_content', 'sharekar_both', 10);
        break;
            
    case 'left':
        add_filter('wp_footer', 'sharekar_float_left');
        break;
            
    case 'right':
        add_filter('wp_footer', 'sharekar_float_right');
        break;
    }
}

function sharekar_before_content($content)
{
	global $type;
	$type = 'inner';

    return  sharekar_render() . $content;
}

function sharekar_after_content($content)
{
	global $type;
	$type = 'inner';
    return $content . sharekar_render();
}

function sharekar_float_left()
{
	global $type;
	$type = 'float';
    echo sharekar_render();
}

function sharekar_float_right()
{
	global $type;
	$type = 'float';
    echo sharekar_render();
}

function sharekar_both($content)
{
	global $type;
	$type = 'inner';
    $share = sharekar_render();
    return $share . $content . $share;
}


function sharekar_render()
{
    global $sharekar, $type;
	
	include SHAREKAR_DIR . '/social.php';
    
    if(empty($socials)) {
        return '';
    }

    $css = sharekar_get_css($sharekar[$type]['button_position']);
	$text_padding = (!empty($sharekar[$type]['button_style']) && $sharekar[$type]['button_style'] === 'highlighted_icon') ? '0 10px 0 10px' : '0 15px 0 0';
	$hover_opacity = (!empty($sharekar[$type]['button_style']) && $sharekar[$type]['button_style'] === 'colored_border') ? '15%' : '40%';
    
    $html = '<style>' . $css.
    '
	.sharekar-'.esc_attr($type).' .sharekar-button-text{
		padding: '.esc_attr($text_padding).';
	}
	
	.sharekar-'.esc_attr($type).' span.sharekar-button-wrapper:hover {
		background-image: linear-gradient(rgb(0 0 0/'.esc_attr($hover_opacity).') 0 0);
	}';
	
	if(!empty($sharekar[$type]['show_sharecount'])){
		$html .= '
		.sharekar-count-block{
		display:inline-flex;
		flex-direction:column;
		font-size:15px;
		align-items:center;
		justify-content:center;
		width:40px;
		height:40px;
		min-width:40px;
		background-color:white;
		color:black;
		}';
	}
	
	$type_class = 'sharekar-' . $type;

	$html .= '</style>';
    $html .= '<div class="'.esc_attr($type_class).' sharekar-btn-wrapper">';
    
    $btn_color = '';
	
	if(!empty($sharekar[$type]['show_sharecount']) && $sharekar[$type]['sharecount_pos'] === 'before'){
		$html .= sharekar_count_html();
	}

    foreach($sharekar[$type]['socials'] as $s)
    {
        if(!empty($sharekar[$type]['enable_button_color']) && !empty($sharekar[$type]['button_color'])) {
            $btn_color = $sharekar[$type]['button_color'];
        } else {
            $btn_color = $socials[$s]['color'];
        }
        
        $link = $socials[$s]['link'];
        $title = rawurlencode(get_the_title());
        $permalink = rawurlencode(get_permalink());
        
        switch($s) {
        case 'twitter':
            $link = add_query_arg(array('text' => $title . '+' . $permalink), $link);
            break;
            
        case 'linkedin':
            $link = add_query_arg(array('title' => $title, 'url' => $permalink, 'mini' => 'true'), $link);
            break;
                
        case 'reddit':
            $link = add_query_arg(array('url' => $permalink, 'title' => $title), $link);
            break;
                
        case 'facebook':
            $link = add_query_arg(array('u' => $permalink), $link);
            break;
                
        case 'pinterest':
            $link = add_query_arg(array('url' => $permalink), $link);
            break;
                
        case 'whatsapp':
            $link = add_query_arg(array('text' => $title . '+' . $permalink), $link);
            break;
                
        case 'telegram':
            $link = add_query_arg(array('url' => $permalink, 'text' => $title), $link);
            break;
			
		case 'snapchat':
            $link = add_query_arg(array('attachmentUrl' => $permalink), $link);
            break;
            
        case 'vk':
            $link = add_query_arg(array('url' => $permalink), $link);
            break;
                
        case 'email': 
            $link .= $permalink;
            break;                
            
        default:
            $link = '';
            break;
        }
		
		$funcs = array('colored_border' => 'sharekar_colored_border_css', 'highlighted_icon' => 'sharekar_highlighted_icon_css');
		
		$style_css = '" style="background-color:'. $btn_color .';"';
		if(!empty($sharekar[$type]['button_style']) && !empty($funcs[$sharekar[$type]['button_style']])){
			$style_css = call_user_func($funcs[$sharekar[$type]['button_style']], $btn_color);
		}
        
        $html .=  '<a href="'.$link.'" target="_blank" rel="nofollow noopener noreferrer" aria-label="'.esc_attr($socials[$s]['brand_name']).'" title="'.esc_attr($socials[$s]['brand_name']).'">
			<span class="sharekar-button-wrapper'.$style_css.'>
				<span class="sharekar-icon-span">'.$socials[$s]['icon'] . '</span>';
        
        if(!empty($sharekar[$type]['button_text']) && !($sharekar[$type]['button_position'] === 'right' || $sharekar[$type]['button_position'] === 'left')) {
            if(!empty($sharekar[$type]['button_text']) && $sharekar[$type]['button_text'] !== 'none' && !empty($socials[$s][$sharekar[$type]['button_text']])) {
                $html .= '<span class="sharekar-button-text">'.esc_html($socials[$s][$sharekar[$type]['button_text']]).'</span>';
            }
        }

        $html .= '</span></a>';
    }
	
	if(!empty($sharekar[$type]['show_sharecount']) &&  $sharekar[$type]['sharecount_pos'] === 'after'){
		$html .= sharekar_count_html();
	}
    
    $html .= '</div>';
    
    return $html;
    
}

function sharekar_count_html(){

	$share_count = sharekar_get_sharecount();
	$c = 0;
	
	if(empty($share_count) || !is_array($share_count)){
		return '';
	}
	
	foreach($share_count as $count){
		$c += intval($count);
	}
	
	if($c == 0){
		return '';
	}
	
	return '<span class="sharekar-count-block">
		<span style="font-weight:bold;">'.esc_html($c).'</span>
		<span style="font-size:8px; line-height:11px;">SHARES</span>
	</span>';
}

function sharekar_get_css($pos)
{
    global $sharekar, $type;
	
	$style_css = '';
	if($sharekar[$type]['button_style'] === 'highlighted_icon'){
		$style_css = '.sharekar-highlighted-icon .sharekar-icon-span{
			background-image: linear-gradient(rgb(0 0 0/30%) 0 0);
		}';
	}
    
    $button_radius = '0px';
    
    switch($sharekar[$type]['button_radius'])
    {
    case 'flat':
        $button_radius = '0px';
        break;
        
    case 'rounded':
        $button_radius = '3px';
        break;
            
    case 'circular':
        $button_radius = '50px';
        break;
    }
	
	$btn_rad = '
	.sharekar-'.$type.' .sharekar-button-wrapper{
		border-radius:'.esc_html($button_radius).';
	}';
    
    if($pos === 'left' || $pos === 'right') {
        return sharekar_get_float_css($pos) . $btn_rad . $style_css;
    }

    return '.sharekar-'.esc_attr($type).'.sharekar-btn-wrapper{
		display:flex;
		flex-wrap:wrap;
	}
	
	.sharekar-'.esc_attr($type).' *{
		box-sizing:border-box;
	}
	
	.sharekar-'.esc_attr($type).' a{
		margin-bottom: 10px;
		margin-left: 10px;
	}
	'. $btn_rad . $style_css;
}

function sharekar_get_float_css($pos)
{
    
    if(wp_is_mobile()) {
        return sharekar_float_for_mobile();
    }
    
    $css = '.sharekar-float.sharekar-btn-wrapper{
		display:flex;
		flex-direction:column;
		position:fixed;
		z-index:999;
		margin:0;
		';

    if($pos === 'left') {
        $css .= 'left: 0;';
    } else {
        $css .= 'right:0;';
    }

    $css .= 'top:50%;
	transform: translateY(-70%);
	}
	
	.sharekar-float *{
		box-sizing:border-box;
	}
	';

    return $css;
}

function sharekar_float_for_mobile()
{
    $css = '.sharekar-float.sharekar-btn-wrapper{
		display:flex;
		width: 100%;
		justify-content:center;
		flex-direction:row;
		position:fixed;
		z-index:999;
		bottom:0;
		left: 50%;
		transform: translateX(-50%);
		box-shadow: 0px 0px 7px 1px #4c4c4c;
	}
	
	.sharekar-float a{
		width: 100%;
	}
	
	.sharekar-float *{
		box-sizing:border-box;
	}
	';

    return $css;
}

function sharekar_colored_border_css($color)
{
	
	return '" style="background-color: transparent; border: 2px solid '.$color.'; color: '.$color .';"';

}

function sharekar_highlighted_icon_css($color)
{
	return ' sharekar-highlighted-icon" style="background-color:'. $color . '"';
}


function sharekar_webshare_init()
{
	$sharekar_webshare = get_option('sharekar_webshare');
	
	if(empty($sharekar_webshare['enable_webshare'])){
		return;
	}
	
	add_action('wp_footer', 'sharekar_webshare_html');
}

function sharekar_webshare_html()
{
	
	if(empty(sharekar_can_webshare())){
		return;
	}
	
	$webshare = get_option('sharekar_webshare');
	
	if(empty($webshare['position_left'])){
		$webshare['position_left'] = 5;
	}
	
	$left = !empty($webshare['webshare_position']) && $webshare['webshare_position'] == 'left' ?  esc_attr($webshare['position_left']) : 0;
	$right = !empty($webshare['webshare_position']) && $webshare['webshare_position'] == 'right' ?  esc_attr($webshare['position_left']) : 0;
	
	$border_radius = '0px';
	
	switch($webshare['webshare_radius']){
		case 'rounded':
			$border_radius = '3px';
			break;
			
		case 'circular':
			$border_radius = '50px';
			break;
			
		default:
			$border_radius = '0px';
	}
	
	
	$left_right = 'right:'. $right.'px;';
	
	if(!empty($left)){
		$left_right = 'left:'. $left.'px;';
	}
	
	$css = '<style>.sharekar-webshare-wrapper{
display:inline-flex;
justify-content:center;
align-items:center;
position: fixed;
bottom: '. (!empty($webshare['position_bottom']) ? esc_attr($webshare['position_bottom']). 'px' : '5px').';
'.$left_right.'
background-color: '. (!empty($webshare['webshare_bg_color']) ? esc_attr($webshare['webshare_bg_color']) : '#2271b1').';
z-index: 999;
padding:12px 18px;
font-size:17px;
border-radius: '.esc_attr($border_radius).';
cursor:pointer;
width:auto;
font-weight:600;
text-transform:uppercase;
color:white;
}
.sharekar-webshare-wrapper svg{
height:30px;
width:30px;
fill:white;
}
.sharekar-webshare-wrapper:hover{
 box-shadow: 0 2px 3px 0 #545252c4;
}
.sharekar-webshare-text{margin-left:8px;}
</style>';

	$text = '';
	
	if(!empty($webshare['webshare_text']) && $webshare['webshare_text'] !== 'nil'){
		$text = '<div class="sharekar-webshare-text">'.esc_html($webshare['webshare_text']).'</div>';
	}
	
	$html = '<div class="sharekar-webshare-wrapper"><?xml version="1.0" encoding="UTF-8"?>
<svg enable-background="new 0 0 100 100" version="1.0" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
<path d="M75,60c-4.658,0-8.772,2.171-11.526,5.511L39.534,53.542C39.812,52.399,40,51.228,40,50s-0.188-2.399-0.466-3.542  l23.942-11.969C66.228,37.825,70.342,40,75,40c8.284,0,15-6.719,15-15c0-8.285-6.716-15-15-15c-8.278,0-15,6.715-15,15  c0,1.224,0.188,2.399,0.466,3.539L36.526,40.508C33.776,37.171,29.661,35,25,35c-8.278,0-15,6.716-15,15s6.722,15,15,15  c4.661,0,8.776-2.171,11.526-5.508l23.939,11.966C60.188,72.601,60,73.776,60,75c0,8.284,6.722,15,15,15c8.284,0,15-6.716,15-15  S83.284,60,75,60z"/>
</svg>
'.$text.'
</div>';
	
	echo $css . $html;
}

function sharekar_can_webshare()
{
	$webshare = get_option('sharekar_webshare');

	if(empty(is_ssl()) || empty($webshare['webshare_post_type'])){
		return false;
	}
	
	if(is_404() || is_archive() || is_search()){
		return false;
	}

	if((is_home() || is_front_page()) && !in_array('homepage', $webshare['webshare_post_type'])){
		return false;
	}
	
	if(get_post_type() == 'page' && !in_array('page', $webshare['webshare_post_type'])){
		return false;
	}
	
	if(get_post_type() == 'post' && !in_array('posts', $webshare['webshare_post_type']) && !(is_home() || is_front_page()) && get_post_type() !== 'page'){
		return false;
	}
	
	return true;
}

function sharekar_common_css(){
	echo '<style>
	.sharekar-btn-wrapper a{display:flex;height:40px;min-width:40px;font-size:14px;text-decoration:none}.sharekar-button-wrapper{display:inline-flex;width:100%;overflow:hidden;color:#fff;align-items:center;justify-content:center}.sharekar-icon-span{display:inline-flex;align-items:center;justify-content:center;width:100%;height:40px;min-width:40px}.sharekar-icon-span>*{height:50%;margin:0 auto}.sharekar-button-text{line-height:1;font-weight:500}
	</style>';
}

?>
