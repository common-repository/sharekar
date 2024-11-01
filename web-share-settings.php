<?php

if(!defined('ABSPATH')){
	die('Dattebayo!');
}


register_setting( 'sharekar-webshare', 'sharekar_webshare', array('sanitize_callback' => 'sharekar_sanitize_options'));
add_settings_section( 'sharekar-webshare-options', 'Share WebShare Settings', 'sharekar_webshare_section', 'sharekar_webshare' );

function sharekar_sanitize_options($val){
	if(is_array($val)){
		return map_deep($val, 'sanitize_text_field');
	}
	
	return sanitize_text_field($val); 
}


function sharekar_sanitize_webshare($val){
	if(is_array($val)){
		return map_deep($val, 'sanitize_text_field');
	}
	
	return sanitize_text_field($val);
}

add_settings_field(
	'sharekar_enable_webshare',
	esc_attr__( 'Enable WebShare', 'sharekar' ),
	'sharekar_enable_webshare_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'checkbox',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'enable_webshare',
		'label_for'    => 'sharekar_enable_webshare_field',
		'description'  => esc_attr__( 'Enable Webshare', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_btn_color',
	esc_attr__( 'Select Button Color', 'sharekar' ),
	'sharekar_btn_color_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'color',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'webshare_bg_color',
		'label_for'    => 'sharekar_btn_color_field',
		'description'  => esc_attr__( 'Set Button Color According to your theme', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_button_position',
	esc_attr__( 'Button Position', 'sharekar' ),
	'sharekar_webshare_position_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'select',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'webshare_position',
		'label_for'    => 'sharekar_webshare_position_field',
		'description'  => esc_attr__( 'Select the location where you want to show the button', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_position_bottom',
	esc_attr__( 'Position From Bottom', 'sharekar' ),
	'sharekar_webshare_bottom_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'select',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'position_bottom',
		'label_for'    => 'sharekar_webshare_bottom_field',
		'description'  => esc_attr__( 'Position of button from bottom', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_position_left',
	esc_attr__( 'Position from left/right', 'sharekar' ),
	'sharekar_webshare_left_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'select',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'position_left',
		'label_for'    => 'sharekar_webshare_left_field',
		'description'  => esc_attr__( 'Position of button from left or right', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_webshare_text',
	esc_attr__( 'Button Text', 'sharekar' ),
	'sharekar_webshare_text_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'text',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'webshare_text',
		'label_for'    => 'sharekar_webshare_text_field',
		'description'  => esc_attr__( 'Text to show besides the Icon, Text will not be visible on Mobile devices. Set this field "nil" if you dont want to have any text', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_webshare_post_type',
	esc_attr__( 'Post Types', 'sharekar' ),
	'sharekar_webshare_post_type_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'checkbox',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'webshare_post_type',
		'label_for'    => 'sharekar_webshare_post_type_field',
		'description'  => esc_attr__( 'Post types on which the Web Share button should be shown', 'sharekar' ),
	)
);

add_settings_field(
	'sharekar_webshare_radius',
	esc_attr__( 'Button Radius', 'sharekar' ),
	'sharekar_webshare_radius_field',
	'sharekar_webshare',
	'sharekar-webshare-options',
	array(
		'type'         => 'radio',
		'option_group' => 'sharekar-webshare',
		'option_name'  => 'sharekar_webshare',
		'name'         => 'webshare_radius',
		'label_for'    => 'sharekar_webshare_radius_field',
		'description'  => esc_attr__( 'Select the radius of the Webshare button', 'sharekar' ),
	)
);
