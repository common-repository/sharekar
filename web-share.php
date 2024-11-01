<?php

if( !defined('ABSPATH') )
{
	die('You are not allowed here');
}

$GLOBALS['sharekar_webshare'] = get_option('sharekar_webshare');

function sharekar_webshare_section()
{
	//
}

function sharekar_enable_webshare_field($args)
{
	global $sharekar_webshare;
	
	$checked = '';
	if(isset($sharekar_webshare[$args['name']]))
	{
		$checked = 'checked';
	}
	echo '<input type="checkbox" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" '.$checked.'>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_btn_color_field($args)
{
	global $sharekar_webshare;
	
	echo '<input type="text" class="sharekar-colorpicker" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.(!empty($sharekar_webshare[$args['name']]) ? esc_attr($sharekar_webshare[$args['name']]) : '#2271b1') . '">';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_position_field($args)
{
	global $sharekar_webshare;
	
	$opts = array('left' => esc_html__('Bottom Left', 'sharekar'), 'right' => esc_html__('Bottom Right', 'sharekar'));
	
	echo '<select id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name']. '[' .$args['name']).']" value="'.(!empty($sharekar_webshare[$args['name']]) ? esc_attr($sharekar_webshare[$args['name']]) : null).'">';
	
	foreach($opts as $key => $opt)
	{
		$selected = '';
		
		if(empty($sharekar_webshare[$args['name']]) && $key === 'right')
		{
			$selected = 'selected';
		}
		
		if(!empty($sharekar_webshare[$args['name']]) && $sharekar_webshare[$args['name']] === $key)
		{
			$selected = 'selected';
		}
		echo '<option value="'.esc_attr($key).'" '.esc_attr($selected).'>'.esc_html($opt).'</option>';
	}
	
	
	echo '</select>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_bottom_field($args)
{
	global $sharekar_webshare;
	
	echo '<input type="text" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.(!empty($sharekar_webshare[$args['name']]) ? esc_attr($sharekar_webshare[$args['name']]) : 5) . '">';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_left_field($args)
{
	global $sharekar_webshare;
	
	echo '<input type="text" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.(!empty($sharekar_webshare[$args['name']]) ? esc_attr($sharekar_webshare[$args['name']]) : 5) . '">';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_text_field($args)
{
	global $sharekar_webshare;

	echo '<input type="text" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.(!empty($sharekar_webshare[$args['name']]) ? esc_attr($sharekar_webshare[$args['name']]) : 'Share') . '">';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_post_type_field($args)
{
	global $sharekar_webshare;

	$opts = array('homepage' => 'Homepage', 'posts' => 'Posts', 'page' => 'Page');
	
	foreach($opts as $key =>$s){
		$checked = '';
		
		if(isset($sharekar_webshare[$args['name']]) && in_array($key, $sharekar_webshare[$args['name']]))
		{
			$checked = 'checked';
		}
		
		echo '<input type="checkbox" name="'.esc_attr($args['option_name'].'['. $args['name']).'][]" value="'.esc_attr($key).'" '.esc_attr($checked).'/>'.esc_attr($s) . '<br/>';
	}
	echo '</ul>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_webshare_radius_field($args)
{
	global $sharekar_webshare;

	$opts = array('flat' => 'Flat', 'rounded' => 'Rounded', 'circular' => 'Circular');

	foreach($opts as $key => $opt)
	{
		$checked = '';
		if(empty($sharekar_webshare[$args['name']]) && $key === 'rounded'){
			$checked = 'checked';
		}
		
		if(!empty($sharekar_webshare[$args['name']]) && $sharekar_webshare[$args['name']] === $key)
		{
			$checked = 'checked';
		}
		
		echo '<input type="radio" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name']. '[' .$args['name']).']" value="'.esc_attr($key).'" '.esc_attr($checked).'/>'.esc_html($opt).'<br/>';
	}
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}


echo '<h1>Web Share</h1>
	<p>Web Share is a way to use native share capabilities of the Operating System\'s Share feature. To simplify it is a way to use your device\'s share options<br/> 
	<strong style="color:red;">Note:</strong> This feature Works only if your site has https, it wont work if your website dosen\'t have https</p>
	';
	
settings_errors();

echo '<form method="post" action="options.php" style="margin-top:30px">';
settings_fields('sharekar-webshare');
do_settings_sections('sharekar_webshare');
submit_button();

echo '</form>';