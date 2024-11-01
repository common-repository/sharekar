<?php

if(!defined('ABSPATH'))
{
	die('Sorry we can\'t cater you, you tried to get in from the wrong way');
}

add_action('plugins_loaded', 'sharekar_plugin_main');

function sharekar_activation()
{
	sharekar_create_meta_table();	
	update_option('sharekar_version',  SHAREKAR_VERSION);

}

function sharekar_update()
{
	$version = get_option('sharekar_version');

	if(version_compare($version, '1.0.0') < 0){
		delete_option('sharekar_settings');
	}
	
	update_option('sharekar_version',  SHAREKAR_VERSION);

}

// Initiates when plugin is fully loaded
function sharekar_plugin_main()
{
	global $sharekar;
	
	sharekar_update();

	$sharekar['inner'] = get_option('sharekar_settings');
	$sharekar['float'] = get_option('sharekar_float');
	$sharekar['conf'] = get_option('sharekar_conf');
	
	if(is_admin())
	{
		add_action('admin_menu', 'sharekar_admin_menu');
		add_action('admin_init', 'sharekar_add_webshare');
		add_action('admin_enqueue_scripts', 'sharekar_load_settings_script');
		add_action('enqueue_block_editor_assets', 'sharekar_add_gut_block');
		add_action('in_admin_header', 'sharekar_admin_header');
	} else {
		sharekar_front();
	}
}

add_action('init', 'sharekar_register_blocks');

//register and render gutenberg blocks
function sharekar_register_blocks() {

	global $sharekar;

	register_block_type(
		'sharekar/clicktotweet',
		array(
			'attributes' => array(
				'tweet' => array(
					'type' => 'string'
				),
				'bg_color' => array(
					'type' =>  'string',
				),
				'color' => array(
					'type' => 'string',
				),
			),
			'render_callback' => 'sharekar_click_to_tweet_block',
		)
	);
}

// Create the meta table
function sharekar_create_meta_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$wpdb->prefix}sharekar_meta (
		id BIGINT(20) NOT NULL AUTO_INCREMENT,
		post_id BIGINT(20),
		meta_key VARCHAR(255) DEFAULT '' NOT NULL,
		meta_value LONGTEXT,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

function sharekar_click_to_tweet_block($attr)
{
	include_once SHAREKAR_DIR . '/click_to_tweet.php';
	return sharekar_click_to_tweet_init($attr);
}

function sharekar_add_gut_block() {
    wp_enqueue_script('sharekar_click_to_tweet', plugins_url('/js/block.js', __FILE__), array('wp-blocks', 'wp-element', 'wp-i18n'), SHAREKAR_VERSION, true);
}

function sharekar_load_settings_script()
{
	wp_enqueue_script('sharekar_settings', plugins_url('/js/settings.js', __FILE__), array('jquery', 'jquery-ui-sortable', 'wp-color-picker'));
}

add_action('wp_enqueue_scripts', 'sharekar_front_scripts');

function sharekar_admin_menu()
{
	$hooknames[] = add_menu_page('ShareKar Settings', 'ShareKar', 'activate_plugins', 'sharekar', 'sharekar_settings', ' dashicons-share');
	
	$hooknames[] = add_submenu_page('sharekar', 'Settings', 'Settings', 'activate_plugins', 'sharekar', 'sharekar_settings');
	
	$hooknames[] = add_submenu_page('sharekar', 'Web Share', 'Web Share <span style="padding: 1px 3px; line-height: 1.5; background-color:red; color:white; border-radius:1px; font-size:12px;">New</span>', 'activate_plugins', 'sharekar_webshare','sharekar_webshare_settings');

	foreach($hooknames as $hookname){
		add_action('load-'.$hookname, 'sharekar_admin_load');
	}
	
}

function sharekar_admin_load(){
	add_action('admin_enqueue_scripts', 'sharekar_enqueue_admin_scripts');
}

function sharekar_enqueue_admin_scripts(){
	wp_enqueue_style('sharekar_admin', SHAREKAR_PLUGIN_URL . '/admin.css', SHAREKAR_VERSION);
	
	// Color picker CSS
	wp_enqueue_style('wp-color-picker');
}

function sharekar_front_scripts()
{	
	$sharekar_webshare = get_option('sharekar_webshare');

	if(is_admin() || empty($sharekar_webshare['enable_webshare']) || empty(sharekar_can_webshare())){
		return;
	}

	wp_enqueue_script('sharekar_webshare', SHAREKAR_PLUGIN_URL . '/js/web-share.js', array( 'wp-color-picker' ), SHAREKAR_VERSION, true);
	
	
}

function sharekar_admin_header(){
	
	if(empty($_GET['page']) || strpos($_GET['page'], 'sharekar') === FALSE){
		return;
	}
	
	$title = 'ShareKar Settings';
	
	// To hide screenoptions on sharekar settings page
	add_filter('screen_options_show_screen', '__return_false');
	
	
	if($_GET['page'] == 'sharekar_webshare'){
		$title = 'ShareKar WebShare';
	}

	echo '<div style="background-color:#030220; color: white; padding:15px 10px; margin:0 0 0 -20px;">
	<div style="display:flex; align-items:center; font-size:20px; font-weight:500; margin-left:20px;"><span style="margin-left:7px;">'.esc_html($title).'</span><span style="color:rgba(255,255,255,0.5); margin-left:3px; font-weight:400; font-size:0.9rem; line-height:2;"> - v'.esc_html(SHAREKAR_VERSION).'</span></div></div>';
	
	if($_GET['page'] == 'sharekar_webshare'){
		return;
	}
	
	echo'<div class="sharekar-tabs-wrapper">
		<label class="sharekar-tab">
			<input type="radio" name="sharekar_tab" value="#sharekar-inner-content" checked/>
			<div>'.__('Inline Content', 'sharekar').'</div>
		</label>
		<label class="sharekar-tab">
			<input type="radio" name="sharekar_tab" value="#sharekar-floating-bar"/>
			<div>'.__('Floating Bar', 'sharekar').'</div>
		</label>
		<label class="sharekar-tab">
			<input type="radio" name="sharekar_tab" value="#sharekar-config"/>
			<div>'.__('Configuration', 'sharekar').'</div>
		</label>
	</div>';
}

// All the code required for the admin settings page
function sharekar_settings()
{
	global $sharekar;
	include_once SHAREKAR_DIR . '/settings.php';
}

function sharekar_webshare_settings()
{
	global $sharekar;
	include_once SHAREKAR_DIR . '/web-share.php';
}

// All the code that will be displayed when end user will be using the website.
function sharekar_front()
{
	global $sharekar;
	
	include_once SHAREKAR_DIR . '/front.php';
	
}

function sharekar_add_webshare()
{
	include_once SHAREKAR_DIR . '/web-share-settings.php';
}



?>