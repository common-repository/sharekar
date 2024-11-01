<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_option( 'sharekar_settings' );
delete_option( 'sharekar_webshare' );
delete_option( 'sharekar_version' );
global $wpdb;

//delete our custom table for that site
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sharekar_meta");