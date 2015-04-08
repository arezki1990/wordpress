<?php 
/*
 * Plugin Name: UTweets
 * Plugin URI: http://ultimatumtheme.com
 * Description:	Simple Tweets displayer Plugin using OAUTH Protocol
 * Author: O.Demir
 * Version: 1.0
 * Author URI: http://ultimatumtheme.com
 * License: GPLv2 or later 
 */
 
// Define the constants we need
define('UTW_VERSION', '1.0');
define('UTW_PLUGINBASENAME', dirname(plugin_basename(__FILE__)));
define('UTW_PLUGINRELPATH', PLUGINDIR . '/' . UTW_PLUGINBASENAME);
define('UTW_PLUGINPATH', ABSPATH.PLUGINDIR . '/' . UTW_PLUGINBASENAME);
define('UTW_URL', plugins_url().'/'.UTW_PLUGINBASENAME);
if(is_admin()){
	function utweet_admin_actions() {
		add_options_page("U-Tweets", "U-Tweets", 1, "U-Tweets", "utweet_admin");
	}
	add_action('admin_menu', 'utweet_admin_actions');
} else {
	add_action( 'wp_enqueue_scripts', 'utw_enqueue_script' );
}
if(function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('utweets', UTW_PLUGINRELPATH . '/languages', UTW_PLUGINBASENAME . '/languages');
}
function utweet_admin(){
	include (UTW_PLUGINPATH.'/lib/admin.php');
}
// add alert
$utw_options = get_option('utweet');
$tw_consumer_key = $utw_options['tw_consumer_key'];
$tw_consumer_secret = $utw_options['tw_consumer_secret'];
$tw_access_token = $utw_options['tw_access_token'];
$tw_access_secret = $utw_options['tw_access_secret'];
if($tw_consumer_key && $tw_consumer_secret && $tw_access_token && $tw_access_secret ){
	// we are all set
	include (UTW_PLUGINPATH.'/lib/widget.php');
} else {
	// Warn User
	add_action('admin_notices','utw_warning');
}

function utw_warning(){
	echo '<div class="updated fade"><p>';
	_e('<strong>Warning!</strong> You have not set <strong>U-Tweet</strong> OAUTH yet. You can not use the plugin until you <a href="./options-general.php?page=U-Tweets">set them up</a>.','utweets');
	echo '</p></div>';
}
function utw_updated(){
	echo '<div class="updated fade"><p>';
	_e('Settings successfully saved','utweets');
	echo '</p></div>';
}
function utw_enqueue_script(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('u-tweets',UTW_URL.'/assets/jquery.tweet.js','jquery');
	wp_enqueue_style('u-tweets',UTW_URL.'/assets/utweets.css');
}