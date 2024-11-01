<?php

if(!defined('ABSPATH'))
{
	die('Sorry we can\'t cater you, you tried to get in from the wrong way');
}

function sharekar_style_section()
{
	//
}

function sharekar_enable_button_field($args)
{
	global $sharekar;
	
	$checked = '';
	if(isset($sharekar[$args['name']]))
	{
		$checked = 'checked';
	}
	echo '<input type="checkbox" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" '.$checked.'>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_button_radius_field($args)
{
	global $sharekar;

	$opts = array('flat' => 'Flat', 'rounded' => 'Rounded', 'circular' => 'Circular');

	foreach($opts as $key => $opt)
	{
		
		$checked = '';
		if(empty($sharekar[$args['name']]) && $key === 'rounded'){
			$checked = 'checked';
		}
		
		if(!empty($sharekar[$args['name']]) && $sharekar[$args['name']] === $key)
		{
			$checked = 'checked';
		}
		
		echo '<input type="radio" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name']. '[' .$args['name']).']" value="'.esc_attr($key).'" '.esc_attr($checked).'/>'.esc_html($opt).'<br/>';
	}
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_button_position_field($args)
{
	global $sharekar;
	
	$opts = array('before' => esc_html__('Before Content', 'sharekar'), 'after' => esc_html__('After Content', 'sharekar'), 'both' => esc_html__('Both After and Before Content', 'sharekar'), 'left' => esc_html__('Float to the Left', 'sharekar'), 'right' => esc_html__('Float to the Right', 'sharekar'));
	
	echo '<select id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name']. '[' .$args['name']).']" value="'.(!empty($sharekar[$args['name']]) ? esc_attr($sharekar[$args['name']]) : null).'">';
	
	foreach($opts as $key => $opt)
	{
		$selected = '';
		
		if(empty($sharekar[$args['name']]) && $key === 'before')
		{
			$selected = 'selected';
		}
		
		if(!empty($sharekar[$args['name']]) && $sharekar[$args['name']] === $key)
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

function sharekar_button_style_field($args){
	global $sharekar;
	
	$opts = array('default' => esc_html__('Default', 'sharekar'), 'colored_border' => esc_html__('Colored Border', 'sharekar'), 'highlighted_icon' => esc_html__('Highlighted Icon', 'sharekar'));
	
	echo '<select id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name']. '[' .$args['name']).']" value="'.(!empty($sharekar[$args['name']]) ? esc_attr($sharekar[$args['name']]) : null).'">';
	
	foreach($opts as $key => $opt)
	{
		$selected = '';
		
		if((!empty($sharekar[$args['name']]) && $sharekar[$args['name']] === $key) || (empty($sharekar[$args['name']]) && $key == 'default'))
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

function sharekar_button_color_field($args)
{
	global $sharekar;
	
	echo '<input type="color" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.(!empty($sharekar[$args['name']]) ? esc_attr($sharekar[$args['name']]) : null) . '">';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_enable_button_color_field($args)
{
	global $sharekar;
	
	$checked = '';
	if(isset($sharekar[$args['name']]))
	{
		$checked = 'checked';
	}
	echo '<input type="checkbox" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" '.$checked.'>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_button_text_field($args)
{
	global $sharekar;
	
	$opts = array('none' => __('None', 'sharekar'), 'brand_name' => __('Brand Name', 'sharekar'), 'action_name' => __('Action Name', 'sharekar'));
	
	
	foreach($opts as $key => $opt)
	{
		$checked = '';
		
		if(empty($sharekar[$args['name']]) && $key === 'none')
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar[$args['name']]) && $sharekar[$args['name']] === $key)
		{
			$checked = 'checked';
		}
		echo '<input type="radio" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.esc_attr($key).'"' .$checked.'>'.esc_html($opt) . '<br/>';
	}
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_socials_field($args)
{
	global $sharekar;
	
	include_once SHAREKAR_DIR . '/social.php';
	
	if(empty($socials)){
		return '';
	}
	
	echo '
	<style>
	
	#sharekar-social-position li{
		display:flex;
		justify-content: space-between;
		border: 1px solid #d1d1d1;
		padding: 10px;
		background-color:#eee;
		width:200px;
	}
	
	#sharekar-social-position li input{
		margin-left: 10px;
	}
	</style>
	
	<ul id="sharekar-social-position">';
	
	$default = array('facebook', 'twitter', 'linkedin');
	
	foreach($socials as $key =>$s){
		$checked = '';
		
		if(empty($sharekar[$args['name']]) && in_array($key, $default))
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar[$args['name']]) && in_array($key, $sharekar[$args['name']]))
		{
			$checked = 'checked';
		}
		
		echo '<li class="ui-state-default"><span><input type="checkbox" name="'.esc_attr($args['option_name'].'['. $args['name']).'][]" value="'.esc_attr($key).'" '.esc_attr($checked).'/>'.esc_attr($s['brand_name']).'</span><span class="dashicons dashicons-sort"></span></li>';
	}
	echo '</ul>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_show_sharecount_field($args)
{
	global $sharekar;
	
	$checked = '';
	if(isset($sharekar[$args['name']]))
	{
		$checked = 'checked';
	}
	echo '<input type="checkbox" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" '.$checked.'>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_sharecount_pos_field($args)
{
	global $sharekar;
	
	$opts = array('before' => __('Before', 'sharekar'), 'after' => __('After', 'sharekar'));
	
	
	foreach($opts as $key => $opt)
	{
		$checked = '';
		
		if(empty($sharekar[$args['name']]) && $key === 'after')
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar[$args['name']]) && $sharekar[$args['name']] === $key)
		{
			$checked = 'checked';
		}
		echo '<input type="radio" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.esc_attr($key).'"' .$checked.'>'.esc_html($opt) . '<br/>';
	}
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_sharecount_refresh_field($args)
{
	global $sharekar;
	
	$opts = array('frequent' => __('Every 6 Hours', 'sharekar'), 'high' => __('Every 12 Hours', 'sharekar'), 'medium' => __('Every 24 Hours', 'sharekar'), 'low' => __('Every 48 Hours', 'sharekar'));
	
	
	foreach($opts as $key => $opt)
	{
		$checked = '';
		
		if(empty($sharekar[$args['name']]) && $key === 'medium')
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar[$args['name']]) && $sharekar[$args['name']] === $key)
		{
			$checked = 'checked';
		}

		echo '<input type="radio" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.esc_attr($key).'"' .esc_attr($checked).'>'.esc_html($opt) . '<br/>';
	}
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_fb_app_id_field($args)
{
	global $sharekar;
	
	$value = '';

	if(!empty($sharekar[$args['name']]))
	{
		$value = $sharekar[$args['name']];
	}
	
	echo '<input type="text" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.esc_attr($value).'"/><br/>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}

function sharekar_fb_app_secret_field($args)
{
	global $sharekar;
	
	$value = '';
	
	if(!empty($sharekar[$args['name']]))
	{
		$value = $sharekar[$args['name']];
	}
	
	echo '<input type="text" id="'.esc_attr($args['label_for']).'" name="'.esc_attr($args['option_name'].'['. $args['name']) . ']" value="'.esc_attr($value).'"/><br/>';
	
	if(!empty($args['description'])){
		echo '<p class="description">'. esc_html($args['description']) . '</p>';
	}
}



echo '<style>
#sharekar-dashboard:checked + .nav-tab,
#sharekar-conf:checked + .nav-tab{
	background: transparent;
	margin-bottom: -1px;
	border-bottom: 1px solid #f0f0f1;
}
</style>
';

$tabs = array(
	array('id' => 'sharekar-dashboard', 'name' => esc_html__('Sharekar Dashboard')),
	array('id' => 'sharekar-conf', 'name' => esc_html__('Configurations')),
);

echo '<h1>'.esc_html__('Sharekar Settings', 'sharekar') . '</h2>
<p>'.esc_html__('Simple and straight forward settings', 'sharekar'). '</p>
To check the possible button styles with Sharekar check this link <a href="https://ps.w.org/sharekar/assets/screenshot-3.png" target="_blank">Button Styles</a>';

settings_errors();

echo '<form method="post" action="options.php" style="margin-top:30px">';
settings_fields('sharekar-settings');
do_settings_sections('sharekar');
submit_button();

echo '</form>';

echo '<div>
<h2>'.esc_html__('Upcoming Features', 'sharekar') .'</h2>
<ol>
	<li>'.esc_html__('Add More share options like Line, Print, or you can suggest if you have anything in mind by writing us on the WordPress support form', 'sharekar').'</li>
	<li>'.esc_html__('Gutenberg Block for Share buttons to add on any page.', 'sharekar').'</li>
	<li>'.esc_html__('WhatsApp Call button', 'sharekar') . '</li>
</ol>
<p>'. esc_html__('If you have any suggestion you can write to us in our support thread on WordPress. We hope this plugin has been helpful, if you wanna support us do rate us on WordPress', 'sharekar').'</p>
</div>';
