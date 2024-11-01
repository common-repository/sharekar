<?php

if(!defined('ABSPATH'))
{
	die('Sorry we can\'t cater you, you tried to get in from the wrong way');
}

function sharekar_save_inner(){
	global $sharekar;
	
	if(!wp_verify_nonce($_POST['security'], 'sharekar_settings_nonce')){
		return;
	}

	$sharekar['inner'] = map_deep($_POST['sharekar_settings'], 'sanitize_text_field');

	update_option('sharekar_settings', $sharekar['inner']);
	sharekar_notify();
	
}

function sharekar_save_float(){
	global $sharekar;
	
	if(!wp_verify_nonce($_POST['security'], 'sharekar_settings_nonce')){
		return;
	}
	
	$sharekar['float'] = map_deep($_POST['sharekar_float'], 'sanitize_text_field');
	
	update_option('sharekar_float', $sharekar['float']);
	
	sharekar_notify();
}

function sharekar_save_conf(){
	global $sharekar;
	
	if(!wp_verify_nonce($_POST['security'], 'sharekar_settings_nonce')){
		return;
	}
	
	$sharekar['conf'] = map_deep($_POST['sharekar_conf'], 'sanitize_text_field');

	update_option('sharekar_conf', $sharekar['conf']);
	sharekar_notify();
}

function sharekar_notify(){
	add_settings_error('sharekar-notice', esc_attr( 'settings_updated' ), __('Settings Saved Successfully!', 'sharekar'), 'success');
}

function sharekar_settings_page(){

global $sharekar;

if(isset($_POST['sharekar_set_inner'])){
	sharekar_save_inner();
} else if(isset($_POST['sharekar_set_float'])){
	sharekar_save_float();
} else if(isset($_POST['sharekar_set_conf'])){
	sharekar_save_conf();
}

settings_errors('sharekar-notice');

$sharekar_settings_nonce = wp_create_nonce('sharekar_settings_nonce');

include_once SHAREKAR_DIR . '/social.php';

$pos_opts = array('before' => esc_html__('Before Content', 'sharekar'), 'after' => esc_html__('After Content', 'sharekar'), 'both' => esc_html__('Both After and Before Content', 'sharekar'));
$radius_options = array('flat' => __('Flat', 'sharekar'), 'rounded' => __('Rounded', 'sharekar'), 'circular' => __('Circular', 'sharekar'));
$show_txt_opts = array('none' => __('None', 'sharekar'), 'brand_name' => __('Brand Name', 'sharekar'), 'action_name' => __('Action Name', 'sharekar'));
$btn_styles = array('default' => esc_html__('Default', 'sharekar'), 'colored_border' => esc_html__('Colored Border', 'sharekar'), 'highlighted_icon' => esc_html__('Highlighted Icon', 'sharekar'));
$count_pos = array('before' => __('Before', 'sharekar'), 'after' => __('After', 'sharekar'));
$count_refresh = array('frequent' => __('Every 6 Hours', 'sharekar'), 'high' => __('Every 12 Hours', 'sharekar'), 'medium' => __('Every 24 Hours', 'sharekar'), 'low' => __('Every 48 Hours', 'sharekar'));

echo '<div class="sharekar-tab-wrapper" id="sharekar-inner-content">
<form method="POST">
<input type="hidden" name="security" value="'. esc_html($sharekar_settings_nonce).'"/>
<h1 class="sharekar-tab-heading">Inline Content</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_enable_button_field">'.__('Enable Share Buttons', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_enable_button_field" class="sharekar-toggle-wrapper">';
				$checked = '';
				if(isset($sharekar['inner']['enable_sharekar'])){
					$checked = 'checked';
				}	
	
				echo '<input type="checkbox" id="sharekar_enable_button_field" name="sharekar_settings[enable_sharekar]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_position_field">'.__('Button Position', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_position_field">
				<select name="sharekar_settings[button_position]" id="sharekar_button_position_field">';
				
				foreach($pos_opts as $name => $position){
					$checked = '';
					if(!empty($sharekar['inner']['button_position']) && $sharekar['inner']['button_position'] === $name){
						$selected = 'selected';
					} else if('before' === $name){
						$selected = 'selected';
					}
					
					echo '<option value="'.esc_attr($name).'" '.esc_attr($selected).'/>'.esc_html($position).'</option>';
				}
			echo '</select></label>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="sharekar_button_position_field">Social Icons</label></th>
		<td><ul id="sharekar-social-position">';
	
	$default = array('facebook', 'twitter', 'linkedin');
	
	foreach($socials as $key =>$s){
		$checked = '';
		
		if(empty($sharekar['inner']['socials']) && in_array($key, $default))
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar['inner']['socials']) && in_array($key, $sharekar['inner']['socials']))
		{
			$checked = 'checked';
		}
		
		echo '<li class="ui-state-default"><span><input type="checkbox" name="sharekar_settings[socials][]" value="'.esc_attr($key).'" '.esc_attr($checked).'/><span style="display:inline-flex; width:20px; height:20px; padding:10px;">'. $s['icon'] . '</span>' . esc_attr($s['brand_name']).'</span><span class="dashicons dashicons-sort"></span></li>';
	}
	echo '</ul></td>
	</tr>
</table>
</div>
<h1 class="sharekar-tab-heading">Design</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_button_radius_field">'.__('Button Radius', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_radius_field">';
			
				foreach($radius_options as $name => $radius){
					$checked = '';
					if(!empty($sharekar['inner']['button_radius']) && $sharekar['inner']['button_radius'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['inner']['button_radius']) && 'rounded' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_button_radius_field" name="sharekar_settings[button_radius]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($radius).'<br>';
				}
			echo '</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_enable_button_color_field">'.__('Enable Button Color', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_enable_button_color_field" class="sharekar-toggle-wrapper">';
					
					$checked = '';
					if(!empty($sharekar['inner']['enable_button_color'])){
						$checked = 'checked';
					}
			
				echo '<input type="checkbox" id="sharekar_enable_button_color_field" name="sharekar_settings[enable_button_color]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_color_field">'.__('Button Color', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_color_field">
				<input type="text" class="sharekar-colorpicker" id="sharekar_button_color_field" name="sharekar_settings[button_color]" value="'.(!empty($sharekar['inner']['button_color']) ? esc_attr($sharekar['inner']['button_color']) : '').'"/>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_text_field">'.__('Show Text', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_text_field">';
			
				foreach($show_txt_opts as $name => $text){
					$checked = '';
					if(!empty($sharekar['inner']['button_text']) && $sharekar['inner']['button_text'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['inner']['button_text']) && 'none' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_button_text_field" name="sharekar_settings[button_text]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($text).'<br>';
				}
			echo '</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_style_field">'.__('Button Style', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_style_field">
				<select name="sharekar_settings[button_style]" id="sharekar_button_style_field">';
				
				foreach($btn_styles as $name => $text){
					$selected = '';
					if(!empty($sharekar['inner']['button_style']) && $sharekar['inner']['button_style'] === $name){
						$selected = 'selected';
					} else if(empty($sharekar['inner']['button_style']) && 'default' === $name){
						$selected = 'selected';
					}
					
					echo '<option value="'.esc_attr($name).'" '.esc_attr($selected).'/>'.esc_html($text).'</option>';
				}
			echo '</select></label>
		</td>
	</tr>
</table>
</div>

<h1 class="sharekar-tab-heading">Share Count</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_show_sharecount_field">'.__('Show Share Count', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_show_sharecount_field" class="sharekar-toggle-wrapper">';
				$checked = '';
				if(!empty($sharekar['inner']['show_sharecount'])){
					$checked = 'checked';
				}

				echo '<input type="checkbox" id="sharekar_show_sharecount_field" name="sharekar_settings[show_sharecount]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_sharecount_pos_field">'.__('Share Count Position', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_sharecount_pos_field">';
			
				foreach($count_pos as $name => $pos){
					$checked = '';
					if(!empty($sharekar['inner']['sharecount_pos']) && $sharekar['inner']['sharecount_pos'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['inner']['sharecount_pos']) && 'before' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_sharecount_pos_field" name="sharekar_settings[sharecount_pos]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($pos).'<br>';
				}
			echo '</label>
		</td>
	</tr>
</table>
</div>
<br>
<input class="button button-primary" type="submit" name="sharekar_set_inner" value="Save Settings"/>
<br>
<br>
</form>
</div>';

$fb_pos = array('left' => esc_html__('Float to the Left', 'sharekar'), 'right' => esc_html__('Float to the Right', 'sharekar'));

echo '<!-- Floting Bar Content Starts here -->
<div class="sharekar-tab-wrapper" id="sharekar-floating-bar" style="display:none">
<form method="POST">
<input type="hidden" name="security" value="'.esc_html($sharekar_settings_nonce).'"/>
<h1 class="sharekar-tab-heading">Floating Bar</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_enable_button_fb">'.__('Enable Share Buttons', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_enable_button_fb" class="sharekar-toggle-wrapper">';
				
				$checked = '';
				if(isset($sharekar['float']['enable_sharekar'])){
					$checked = 'checked';
				}

				echo '<input type="checkbox" id="sharekar_enable_button_fb" name="sharekar_float[enable_sharekar]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_position_fb">'.__('Button Position', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_position_fb">
				<select name="sharekar_float[button_position]" id="sharekar_button_position_fb">';
				
				foreach($fb_pos as $name => $position){
					$checked = '';
					if(!empty($sharekar['float']['button_position']) && $sharekar['float']['button_position'] === $name){
						$selected = 'selected';
					} else if(empty($sharekar['float']['button_position']) && 'left' === $name){
						$selected = 'selected';
					}
					
					echo '<option value="'.esc_attr($name).'" '.esc_attr($selected).'/>'.esc_html($position).'</option>';
				}
			echo '</select></label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_position_fb">Social Icons</label></th>
		<td><ul id="sharekar-social-position">';
	
	$default = array('facebook', 'twitter', 'linkedin');
	
	foreach($socials as $key =>$s){
		$checked = '';
		
		if(empty($sharekar['float']['socials']) && in_array($key, $default))
		{
			$checked = 'checked';
		}
		
		if(isset($sharekar['float']['socials']) && in_array($key, $sharekar['float']['socials']))
		{
			$checked = 'checked';
		}
		
		echo '<li class="ui-state-default"><span><input type="checkbox" name="sharekar_float[socials][]" value="'.esc_attr($key).'" '.esc_attr($checked).'/><span style="display:inline-flex; width:20px; height:20px; padding:10px;">'. $s['icon'] . '</span>'.esc_attr($s['brand_name']).'</span><span class="dashicons dashicons-sort"></span></li>';
	}
	echo '</ul></td>
	</tr>
</table>
</div>
<h1 class="sharekar-tab-heading">Design</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_button_radius_fb">'.__('Button Radius', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_radius_fb">';
			
				foreach($radius_options as $name => $radius){
					$checked = '';
					if(!empty($sharekar['float']['button_radius']) && $sharekar['float']['button_radius'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['float']['button_radius']) && 'rounded' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_button_radius_fb" name="sharekar_float[button_radius]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($radius).'<br>';
				}
			echo '</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_enable_button_color_fb">'.__('Enable Button Color', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_enable_button_color_fb" class="sharekar-toggle-wrapper">';
				
				$checked = '';
				if(isset($sharekar['float']['enable_button_color'])){
					$checked = 'checked';
				}
			
				echo '<input type="checkbox" id="sharekar_enable_button_color_fb" name="sharekar_float[enable_button_color]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_color_fb">'.__('Button Color', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_color_fb">
				<input type="text" class="sharekar-colorpicker" id="sharekar_button_color_fb" name="sharekar_float[button_color]" value="'.(!empty($sharekar['float']['button_color']) ? esc_attr(($sharekar['float']['button_color'])) : '') .'"/>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_text_fb">'.__('Show Text', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_text_fb">';
			
				foreach($show_txt_opts as $name => $text){
					$checked = '';
					if(!empty($sharekar['float']['show_text']) && $sharekar['float']['show_text'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['float']['show_text']) && 'none' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_button_text_fb" name="sharekar_float[button_text]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($text).'<br>';
				}
			echo '</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_button_style_fb">'.__('Button Style', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_button_style_fb">
				<select name="sharekar_float[button_style]" id="sharekar_button_style_fb">';
				
				foreach($btn_styles as $name => $text){
					$selected = '';
					if(!empty($sharekar['float']['button_style']) && $sharekar['float']['button_style'] === $name){
						$selected = 'selected';
					} else if(empty($sharekar['float']['button_style']) && 'default' === $name){
						$selected = 'selected';
					}
					
					echo '<option value="'.esc_attr($name).'" '.esc_attr($selected).'/>'.esc_html($text).'</option>';
				}
			echo '</select></label>
		</td>
	</tr>
</table>
</div>

<h1 class="sharekar-tab-heading">Share Count</h1>
<div class="sharekar-settings-block">
<table>
	<tr>
		<th scope="row"><label for="sharekar_show_sharecount_fb">'.__('Show Share Count', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_show_sharecount_fb" class="sharekar-toggle-wrapper">';
				$checked = '';
				if(isset($sharekar['float']['show_sharecount'])){
					$checked = 'checked';
				}
			
				echo '<input type="checkbox" id="sharekar_show_sharecount_fb" name="sharekar_float[show_sharecount]" '.esc_attr($checked).'/>
				<div class="sharekar-toggle">
					<div></div>
				</div>
			</label>
		</td>
	</tr>
	
	<tr>
		<th scope="row"><label for="sharekar_sharecount_pos_fb">'.__('Share Count Position', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_sharecount_pos_fb">';
			
				foreach($count_pos as $name => $pos){
					$checked = '';
					if(!empty($sharekar['float']['sharecount_pos']) && $sharekar['float']['sharecount_pos'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['float']['sharecount_pos']) && 'before' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_sharecount_pos_fb" name="sharekar_float[sharecount_pos]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($pos).'<br>';
				}
			echo '</label>
		</td>
	</tr>
</table>
</div>
<br>
<input class="button button-primary" type="submit" name="sharekar_set_float" value="Save Settings"/>
<br>
<br>
</form>
</div>';

echo '<!-- Config Tab -->
<div class="sharekar-tab-wrapper" id="sharekar-config" style="display:none">
<h1 class="sharekar-tab-heading">Configuration</h1>
<div class="sharekar-settings-block">
<form method="POST">
<input type="hidden" name="security" value="'.esc_html($sharekar_settings_nonce).'"/>
<table>
	<tr>
		<th scope="row"><label for="sharekar_sharecount_refresh_field">'.__('Share Count Refresh Rate', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_sharecount_refresh_field">';
			
				foreach($count_refresh as $name => $refresh){
					$checked = '';
					if(!empty($sharekar['conf']['sharecount_refresh']) && $sharekar['conf']['sharecount_refresh'] === $name){
						$checked = 'checked';
					} else if(empty($sharekar['conf']['sharecount_refresh']) && 'frequent' === $name){
						$checked = 'checked';
					}
					
					echo '<input type="radio" id="sharekar_sharecount_refresh_field" name="sharekar_conf[sharecount_refresh]" value="'.esc_attr($name).'" '.esc_attr($checked).'/>'.esc_html($refresh).'<br>';
				}
			echo '</label>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sharekar_fb_app_id_field">'.__('FB App ID', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_fb_app_id_field">
					<input type="text" id="sharekar_fb_app_id_field" name="sharekar_conf[fb_app_id]" value="'.(!empty($sharekar['conf']['fb_app_id']) ? esc_attr($sharekar['conf']['fb_app_id']) : '').'"/>
			</label>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sharekar_fb_app_secret_field">'.__('FB App Secret', 'sharekar').'</label></th>
		<td>
			<label for="sharekar_fb_app_secret_field">
					<input type="text" id="sharekar_fb_app_secret_field" name="sharekar_conf[fb_app_secret]" value="'.(!empty($sharekar['conf']['fb_app_secret']) ? esc_attr($sharekar['conf']['fb_app_secret']) : '').'"/>
			</label>
		</td>
	</tr>
</table>
<br>
<input class="button button-primary" type="submit" name="sharekar_set_conf" value="Save Settings"/>
</form>
<br>
<br>
</div>
</div>';

}

sharekar_settings_page();
