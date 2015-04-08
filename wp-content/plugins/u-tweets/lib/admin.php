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

// update
if($_POST){
	$post= array(
			'tw_consumer_key' => $_POST['tw_consumer_key'],
			'tw_consumer_secret' => $_POST['tw_consumer_secret'],
			'tw_access_token' => $_POST['tw_access_token'],
			'tw_access_secret' => $_POST['tw_access_secret'],
	);
	update_option('utweet', $post);
	utw_updated();
}
// get option
$utw_options = get_option('utweet');
$tw_consumer_key = $utw_options['tw_consumer_key'];
$tw_consumer_secret = $utw_options['tw_consumer_secret'];
$tw_access_token = $utw_options['tw_access_token'];
$tw_access_secret = $utw_options['tw_access_secret'];


?>
<div class="wrap">
	<?php    echo "<h2>" . __( 'U-Tweets Settings', 'utweets' ) . "</h2>"; ?>
	<table width="100%">
	<tr valign="top"><td>
	<form name="utweet_admin" method="post" action="">
		<table class="widefat">
			<thead>
			<tr><th colspan="2"><?php _e( 'Twitter OAUTH Settings', 'utweets' ); ?></th></tr>
			</thead>
			<tfoot>
			<tr><td colspan="2" style="text-align:right"><input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'utweets' ) ?>" /></th></tr>
			</tfoot>
			<tbody>
				<tr>
					<td>
					<?php _e("Consumer Key",'utweets' ); ?>
					</td>
					<td>
					<input type="text" name="tw_consumer_key" value="<?php echo $tw_consumer_key; ?>" size="50">
					</td>
				</tr>
				<tr>
					<td>
					<?php _e("Consumer Secret",'utweets' ); ?>
					</td>
					<td>
					<input type="text" name="tw_consumer_secret" value="<?php echo $tw_consumer_secret; ?>" size="50">
					</td>
				</tr>
				<tr>
					<td>
					<?php _e("Access Token",'utweets' ); ?>
					</td>
					<td>
					<input type="text" name="tw_access_token" value="<?php echo $tw_access_token; ?>" size="50">
					</td>
				</tr>
				<tr>
					<td>
					<?php _e("Access Secret",'utweets' ); ?>
					</td>
					<td>
					<input type="text" name="tw_access_secret" value="<?php echo $tw_access_secret; ?>" size="50">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	</td>
	<td>
	<h3><a href="http://ultimatumtheme.com/?ref=powder"><?php _e('Did you know that U-Tweets is a part of Ultimatum?','utweets');?></a></h3>
	<a href="http://ultimatumtheme.com/?ref=powder">
	<img src="<?php echo UTW_URL.'/assets/ultimatum.jpg';?>" />
	</a>
	</td>
	</tr>
	</table>
</div>
	