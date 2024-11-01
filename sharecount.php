<?php

if( !defined('ABSPATH') ) {
	die('Sorry you can\'t access this app through this path');
}

function sharekar_save_sharecount(){
	global $sharekar, $wpdb, $post;
	
	if((empty($sharekar['inner']['show_sharecount']) && empty($sharekar['float']['show_sharecount']))|| empty(is_single()) || empty($post)){
		return;
	}
	
	$post_id = $post->ID;

	if(empty($post->ID)){
		return;
	}

	$refresh_rate = array(
		'frequent' => 21600, // 6 hours
		'high' => 43200, // 12 hours
		'medium' => 86400, // 1 day / 24 hours,
		'low' => 172800 // 48 hours
	);
	
	$last_refresh = $wpdb->get_row($wpdb->prepare("SELECT id, meta_value FROM {$wpdb->prefix}sharekar_meta WHERE post_id = %d AND meta_key = 'share_counts_updated'", $post_id));
	$refresh = $refresh_rate[$sharekar['conf']['sharecount_refresh']];
	
	if(!empty($last_refresh->meta_value) && (time() - ($last_refresh->meta_value + $refresh) > 0)){
		return;
	}

	$share_count_apps = array('facebook', 'pinterest', 'vk', 'reddit');
	$inner_socials = !empty($sharekar['inner']['socials']) ? $sharekar['inner']['socials'] : [];
	$float_socials = !empty($sharekar['float']['socials']) ? $sharekar['float']['socials'] : [];
	
	
	$total_socials = array_merge($inner_socials, $float_socials);
	
	$countables = array_intersect($share_count_apps, $total_socials);
	
	if(empty($countables)){
		return;
	}
	
	$share_counts = sharekar_fetch_counts($countables);
	
	if(empty($share_counts)){
		sharekar_update_last_update_time($last_refresh, $post_id);
		return;
	}

	// Find the id if there is any data before
	$share_counts_row = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}sharekar_meta WHERE post_id = %d AND meta_key = 'share_counts'", $post_id));

	// Update/ add
	$wpdb->replace($wpdb->prefix . 'sharekar_meta', array(
			'id'         => $share_counts_row,
			'post_id'    => $post_id,
			'meta_key'   => 'share_counts',
			'meta_value' => maybe_serialize($share_counts)
		),
		array(
			'%d',
			'%d',
			'%s',
			'%s'
		)
	);
	
	sharekar_update_last_update_time($last_refresh, $post_id);

}

function sharekar_update_last_update_time($last_refresh, $post_id){
	global $wpdb;

	// Update Last update time
	$wpdb->replace($wpdb->prefix . 'sharekar_meta', array(
			'id'         => ((!empty($last_refresh) && !empty($last_refresh->id)) || $last_refresh->id != 0 ? $last_refresh->id : ''),
			'post_id'    => $post_id,
			'meta_key'   => 'share_counts_updated',
			'meta_value' => time()
		),
		array(
			'%d',
			'%d',
			'%s',
			'%d'
		)
	);
	
}


add_action('wp_head', 'sharekar_save_sharecount', 10);


function sharekar_fetch_counts($countables){
	global $post;
	
	$social_count = array();

	$post_id = $post->ID;
	$permalink = get_permalink($post);

	if(empty($permalink)){
		return $social_count;
	}
	
	foreach($countables as $countable){
		$share_counts = sharekar_count_api_call($countable, $permalink);
		
		if(empty($share_counts)){
			continue;
		}
		
		$social_count[$countable] = $share_counts;
	}
	
	return $social_count;
	
}

function sharekar_count_api_call($social, $permalink){
	global $sharekar;
	
	$url = '';
	$permalink = urlencode($permalink);

	switch($social){
		case 'facebook':
			if(!empty($sharekar['conf']['fb_app_id'])) {
				$url = 'https://graph.facebook.com/v12.0/?id=' . $permalink . '&access_token=' . urlencode($sharekar['conf']['fb_app_id'])  . '&fields=engagement';
			}
			break;

		case 'pinterest':
			$url = 'https://widgets.pinterest.com/v1/urls/count.json?source=6&url=' . $permalink;
			break;

		case 'reddit':
			$url = 'https://www.reddit.com/api/info.json?url=' . $permalink;
			break;

		case 'vk':
			$url = 'https://vk.com/share.php?act=count&index=1&url=' . $permalink;
			break;

		default:
			break;
	}
	
	if(empty($url)){
		return false;
	}
	
	$response = wp_remote_get($url, array('timeout' => 5));

	//response wasn't successful
	if(wp_remote_retrieve_response_code($response) != 200){
		return false;
	}

	$body = json_decode(wp_remote_retrieve_body($response), true);
	$share_count = 0;

	switch($social){

		case 'facebook':
			$facebook_share_count = 0;
			if(!empty($body['engagement']['share_count'])) {
				$facebook_share_count = $facebook_share_count + $body['engagement']['share_count'];
			}
			if(!empty($body['engagement']['reaction_count'])) {
				$facebook_share_count = $facebook_share_count + $body['engagement']['reaction_count'];
			}
			if(!empty($body['engagement']['comment_count'])) {
				$facebook_share_count = $facebook_share_count + $body['engagement']['comment_count'];
			}
			if($facebook_share_count > 0) {
				$share_count = $facebook_share_count;
			}

			break;

		case 'pinterest':
			$body = wp_remote_retrieve_body($response);
			preg_match('/receiveCount\((.*)\)/', $body, $json_res);

			if(empty($json_res[1])){
				break;		
			}

			$body = json_decode($json_res[1], true);
			if(!empty($body['count'])) {
				$share_count = $body['count'];
			}

			break;

		case 'reddit':
			$reddit_share_count = 0;
			if(!empty($body['data']['children'])) {
				foreach($body['data']['children'] as $child) {
					if(!empty( $child['data']['score'])) {
						$reddit_share_count = $reddit_share_count + $child['data']['score'];
					}
				}	
			}
			if($reddit_share_count > 0) {
				$share_count = $reddit_share_count;
			}

			break;

		case 'vk':
			$body = wp_remote_retrieve_body($response);
			$start = strpos($body, '(');
			$end = strpos($body, ')', $start + 1);
			$length = $end - $start;
			$vk_shares = array_map('trim', explode(',', substr($body, $start + 1, $length - 1)));

			if(!empty($vk_shares[1])) {
				$share_count = $vk_shares[1];
			}

			break;

	}

	return esc_html($share_count);
}

function sharekar_get_sharecount(){
	global $wpdb, $post;

	if(empty(is_single())){
		return;
	}

	$post_id = $post->ID;
	
	if(empty($post_id)){
		return;
	}

	$share_counts = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}sharekar_meta WHERE post_id = %d AND meta_key = 'share_counts'", $post_id));
	
	if(empty($share_counts)){
		return array();
	}
	
	return maybe_unserialize($share_counts);
	
}