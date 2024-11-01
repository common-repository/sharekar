<?php
/**
 * Plugin Name:       ShareKar
 * Plugin URI:        https://sharekar.net/
 * Description:       Simple, easy to use and fast social share plugin. 
 * Version:           1.0.2
 * Requires at least: 4.6
 * Requires PHP:      7.3
 * Author:            Anju
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sharekar
 * Domain Path:       /languages
 */

define( 'SHAREKAR_VERSION', '1.0.2' );
define( 'SHAREKAR_DIR', __DIR__ );
define( 'SHAREKAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SHAREKAR_DIR . '/main.php';
