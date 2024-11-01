<?php

if(!defined('ABSPATH'))
{
	die('We can\'t serve you sorryyy come back from the right route!'); 
}

function sharekar_click_to_tweet_init($atts)
{
	global $sharekar;
	
	
	if(empty($atts['tweet']))
	{
		return;
	}
	
	if(is_admin())
	{
		return;
	}
	
	return sharekar_render_tweet($atts);
}

function sharekar_render_tweet($atts){

	// Removes any tag if any
	$tweet = strip_tags($atts['tweet']);
	$post_link = !empty(get_permalink()) ? get_permalink() : ''; 
	$link_tweet = $tweet . ' ' . $post_link;
	$bg_color = empty($atts['bg_color']) ? '#1da1f2' : $atts['bg_color'];
	$color = empty($atts['color']) ? '#fff' : $atts['color'];
	
	$link = 'https://twitter.com/intent/tweet';
	$link = add_query_arg(array('text' => urlencode($link_tweet)), $link);
	
	$html = '<style>.sharekar-click-to-tweet{ display:block; position:relative; background-color: '.esc_attr($bg_color).'; padding: 20px; text-decoration:none !important; margin:30px auto;} .sharekar-click-to-tweet:hover{color:#fff} .sharekar-click-to-tweet:visited{color:#fff} .sharekar-click-to-tweet:link{color:#fff}</style>';
	
	$html .= '<a class="sharekar-click-to-tweet" href="'.$link.'" target="_blank"><span>'.esc_html($tweet).'</span><div style="display: flex; justify-content: flex-end; align-items: center; font-size: 16px; font-weight: 400; margin-top:10px;"><span class="dashicons dashicons-twitter"></span><span style="margin-left: 3px;">Click to Tweet</span></div></a>';
	
	return $html;
}