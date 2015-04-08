<?php
 /*
Plugin Name: Internal Link Building
Plugin URI: http://tools.seochat.com/tools/interlinking-plugin/
Description: This interlinking plugin for WordPress will let you easily and meaningfully link to your old articles, pages or other sites to improve their rankings in search engines and generate more clicks. The Internal Link Building plugin lets you assign keywords to given destination URLs. This way your website will link within itself like it's done <br /> in Wikipedia – every time a keyword occurs, it links... By <a target="_blank" href="http://www.internetmarketingninjas.com/">Internet Marketing Ninjas</a>
Note: Please do use the plugin in moderation to avoid over-optimization. Meaningful and user-friendly inter-linking goes a long way!
Author: Internet Marketing Ninjas
Version: 1.1
Author URI: http://www.internetmarketingninjas.com/
*/

/******************************
* Start up the plugin
/*****************************/
//Lets you easily link to your old articles, pages or other sites to improve their rankings in search engines and generate more clicks.
	if(!empty($_REQUEST['update_keylink_custom'])){ // Handles AJAXed Requests
		require_once( ABSPATH . WPINC . '/pluggable.php');
		if(current_user_can('edit_posts')){
			$keywords = internal_link_building::update_custom();
		}
	}elseif(is_admin()){ // The user is on and admin page
		if(!empty($_GET['internal_link_building']) && $_GET['internal_link_building'] ){// The user just saved a post.
			$keywords = internal_link_building::update_custom();
		}
	}else{ // The user is not in the admin panel, so get ready to print keywords.
		add_action('init', array('internal_link_building','init'));
	}
	add_action('submitpost_box',  array('internal_link_building', 'customUI_box'));
	add_action('admin_menu', array('internal_link_building','menu'));
	add_action('admin_head', array('internal_link_building','head'));

Class internal_link_building{

/******************************
*
* Adds header scripts on edit post page.
*
/*****************************/

	static function head(){
		wp_print_scripts('jquery-form');
	}

/******************************
*
* Adds filters to content.
*
/*****************************/

	static function init(){
		global $id, $post;

		add_action('the_content',array('internal_link_building','filter'),9);
		add_action('get_comment_text',array('internal_link_building','comment_filter'));
		add_action('the_excerpt',array('internal_link_building','filter'),9);
	}

/******************************
*
* Passes comments to the post filter to add custom keywords.
*
/*****************************/

	static function comment_filter($content){
		return internal_link_building::filter($content,1);
	}
/******************************
*
* Filters posts to add custom keywords.
*
/*****************************/

	static function filter($content,$iscomment=0){
		global $id,$post;
		$content = ' '.$content.' ';
		$microtime =  microtime();
		$keywords = get_option('internal_link_building');
		$options = get_option('internal_link_building_options');
		$customkeys = array();
		$used_keywords = array();

		$postkeys = get_post_meta($id, 'keyword_cache',true);
		$customkeys = get_post_meta($id, 'keyword_custom',true);

		if(!$options['max_keys']){
			$max_keys = 999;
		}else{
			$max_keys = $options['max_keys'];
		}

		if(!empty($options['no_posts']) && !is_page())
			return $content;

		if(!empty($options['no_pages']) && is_page())
			return $content;

		$keys = $keywords;
		if(is_array($customkeys)){
		            
			@$keys = $keys+$customkeys;  //Add custom keys
		}
		$update = 0;
		//We should base our cache time off of the most recently updated.
		$keys['keywords_time'] = (!empty($customkeys['keywords_time']) && !empty($keywords['keywords_time']) && $customkeys['keywords_time'] > $keywords['keywords_time'])? $customkeys['keywords_time'] : $keywords['keywords_time'];

		if(is_array($postkeys) && $postkeys['keywords_time'] > $keys['keywords_time']){ /* There is a cache and it is newer.*/
			$keys = $postkeys;
		}elseif(!empty($postkeys['keywords_time'])){ /*Cache, but the cache is old.*/
			delete_post_meta($id, 'keyword_cache');
			$update = 1;
		}else{ /* No cache.*/
			$update = 1;
		}

		if(!is_array($keys)) //Something broke
			return $content;
		
		$content = internal_link_building_blocks::findtags($content); //We do NOT want to replace content inside HTML blocks.

		foreach ($keys as $name => $ops) {
			if(strpos($name,'|')){ // Multiple keywords same link.
				$names = explode('|',$name);
				foreach($names as $new_name){
					$new_name = trim($new_name);
					$key_list[] = $new_name;
					$keys[$new_name] = $ops;
				}
			}else{	
				$key_list[] = $name;
			}
		}

		usort($key_list,'sortByLength_desc');
		$key_count = 0;
		$nofollow = '';
		$newwindow = '';
		$before = '';
		$after = '';
		foreach($key_list as $key){
			$ops = $keys[$key];
			if(is_numeric($key))
				continue;
			$key = trim(stripslashes(stripslashes($key)));
			$name = $key;

			if($name && is_array($ops) && $key_count < $max_keys){
				$linkindices = get_post_meta($id, 'link_indices_'.$name,true);
				$nofollow = '';
				if($ops['nofollow'])
					$nofollow = " rel='nofollow' ";

				if($ops['newwindow'])
					$newwindow = " target='_blank' ";

				$link = explode('|',$ops['url']);

				$replace1 = '<a href="'.$link[0].'"'.$nofollow.$newwindow.'>';
				$replace2 = '</a>';

				$escapes = array('.','$','^','[',']','?','+','(',')','*','|','\\');

				foreach($escapes as $s){
					$r = '\\\\'.$s;
					$name = str_replace($s, stripslashes($r), $name);
				} 
				
				if(intval($ops['between']) >0 && strpos($name,' ')){
					$name = str_replace(' ',"([\s,\(\);:]*?[A-Za-z0-9\x80-\xFF+]*?[\s,\)\(;:]){0,$ops[between]}",$name);//([\s,\(\);:]*?[A-Za-z0-9]*?[\s,\)\(;:])
				}
				$before = '';
				if(intval($ops['before']) >0){
					$before = "([\s,\(\);:]*?[A-Za-z0-9\x80-\xFF+]*?[\s,\)\(;:]){0,$ops[before]}";
				}
				$after = '';
				if(intval($ops['after']) >0){
					$after = "([\s,\(\);:]*?[A-Za-z0-9\x80-\xFF+]*?[\s,\)\(;:]){0,$ops[after]}";
				}
		
				$needle ='@()('.$before.$name.$after.')()@';
				$extra = '';

				if($ops['case']!=1)
					$insensitive = 'i';

				if($ops['times']){
					$times = $ops['times'];
					if($times >($max_keys - $key_count))
						$times = ($max_keys - $key_count);
				}else{
					$times = ($max_keys - $key_count);
				}

				if(count($link) == 1){//Single link simple replace
					
					if(trim(str_replace(array('http://','www.'),'',$link[0]),'/') == trim(str_replace(array('http://','www.'),'',get_permalink()),'/')){
						continue;}

					$content = internal_link_building_blocks::findtags($content,false); //We do NOT want to replace content inside HTML blocks.
					$content = internal_link_building::replace($content, $needle, $replace1, $replace2, $times,$insensitive); 
					$actual_times = preg_match_all($needle.$insensitive,$content,$out);

					$key_count += ($actual_times < $times) ? $actual_times : $times;

				}else{//Multiple links, not so easy
					$actual_times = preg_match_all($needle,$content,$out);
					if($times == -1){
						$times = 1;
						$loops = preg_match_all($needle,$content,$out);
					}else{
						
						$loops =($actual_times < $times) ? $actual_times : $times;
						$times = 1;
					}
					if($loops > ($max_keys - $key_count))
						$loops = $max_keys - $key_count;
					$key_count += $loops;
					if($linkindices && $update != 1){ //Is there a cache so we don't use different links every page load
						$indices = explode(',',$linkindices);
		
					}else{//Multiple links and no chache, create random array
					$indices = array_rand($link, count($link));
						$linkindices = implode(',',$indices);	
						update_post_meta($id, 'link_indices_'.$name,$linkindices);
					}
					$y = 0;
					$indicecount = count($indices);
					for($x=0; $x< $loops; $x++){
						//Grab a random link from the array.
						$indice = $indices[$y];//????
						$this_link = $link[$indice];
						if(trim(str_replace(array('http://','www.'),'',$this_link),'/') == trim(str_replace(array('http://','www.'),'',get_permalink()),'/'))
							continue;
						if($this_link){
							$replace1 = '<a href="'.$this_link.'"'.$nofollow.$newwindow.'>';
							$content = internal_link_building::replace($content, $needle, $replace1, $replace2, $times,$insensitive);
							$content = internal_link_building_blocks::findtags($content,false); //We do NOT want to replace content inside HTML blocks.
						}
						$y++;
						if($y == $indicecount)
						{
							$y=0;
						}
					}
				}
				
				$used_keywords[$key] = $ops;

				unset($name, $before, $after, $insensitive, $times, $loops);
			}
			
		}

		$content = internal_link_building_blocks::findblocks($content); // Return the HTML blocks

		if($update == 1){

			$newkeys = $used_keywords;
			$newkeys['keywords_time'] = time();
			update_post_meta($id, 'keyword_cache', $newkeys);

		}
		$content = trim($content, ' ');
		return $content;
	}

	static function replace($haystack, $needle, $replace1, $replace2, $times=-1,$insensitive=''){
	global $replace;
		$replace = array($replace1,$replace2);
		$result = preg_replace_callback($needle.$insensitive,array('internal_link_building','replace_callback'),$haystack,$times);
		return $result;
	}

	static function replace_callback($matches){
	global $replace;
	$x='';
		$par_open = strpos($matches[2],'('); //check to see if their are an even number of parenthesis.
		$par_close = strpos($matches[2],')');

		if($par_open !== false && $par_close === false || $par_open === false && $par_close !== false )
			return $matches[1].$matches[2].$matches[count($matches)-1];
	$result = $matches[1].$replace[0].$x.$matches[2].$replace[1].$matches[count($matches)-1];
	return $result;
	}

// *******************************
// Admin Panel
// *******************************
	static function update_options($options){

		$keywords = null;
		foreach($options as $option) {
			if($option['name'] && $option['url'])
				$keywords[$option['name']] =array( 'url' => $option['url'], 'times' => $option['times'], 'between' => $option['between'],'before' => $option['before'],'after' => $option['after'], 'case' => $option['case'], 'nofollow' => $option['nofollow'], 'newwindow' => $option['newwindow']);
		}
		return $keywords;
	}

// *******************************
// Converts cvs string to usable array.
// *******************************
	function convert_cvs($cvs){
		$del = "\t";
		if(strpos($cvs, "\t") === false)
			$del = ',';

		$linebr = "\n";
		if(strpos($cvs, "\n") === false)
			$linebr = "\r";

		$lines = explode($linebr,$cvs);
		foreach( $lines as $line){
			unset($items);
			$items = explode($del,$line);

			if($items[0] && $items[1])
				$keywords[trim($items[0])] =array( 'url' => trim($items[1]), 'times' => trim($items[2]), 'case' => trim($items[3]), 'nofollow' => trim($items[4]), 'between' => trim($items[5]), 'before' => trim($items[6]), 'after' => trim($items[7]), 'newwindow' => trim($items[8]));
		}
		return $keywords;
	}

/******************************
*
*  Saves custom keywords.
*
/*****************************/
	static function update_custom(){
		global $id;
		$id = $_GET['post_ID'];
		$stored_custom = get_post_meta($id, 'keyword_custom', false);
		$custom = internal_link_building::update_options($_GET['internal_link_building']);
		if(!is_array($custom)){
			delete_post_meta($id, 'keyword_custom');
			return;
		}
		$custom['keywords_time'] = time();
		update_post_meta($id, 'keyword_custom', $custom);
		
//		echo '<script>alert("'.$_REQUEST['internal_link_building'].'");</script>';
		$keywords = get_option('internal_link_building');
		$keywords['keywords_time'] = time();
		update_option('internal_link_building',$keywords);

		$options = $_REQUEST['internal_link_building_options'];
		update_option('internal_link_building_options',$_REQUEST['internal_link_building_options']);
		
	
		return $custom;
	}
/******************************
*
* Adds a custom keyewords box on the edit post page.
*
/*****************************/

		static function customUI_box(){
			add_meta_box( 'internal_link','Custom Keywords', array('internal_link_building','customUI'), 'post','normal','core');
			add_meta_box( 'internal_link','Custom Keywords', array('internal_link_building','customUI'), 'page','normal','core');

			if(function_exists('get_post_types')){
				$post_types = get_post_types();
				$count = count($post_types);

				if($count > 5){ //If custom types have been added
					for($x=5;$x<$count;$x++)
						add_meta_box( 'internal_link','Custom Keywords', array('internal_link_building','customUI'), $post_types[$x],'normal','core');
				}
			}
		}

		static function customUI(){
			global $id,$wp_version;
		$customkeys = get_post_meta(8, 'keyword_custom',true);
		$keywords = get_post_meta($_GET['post'], 'keyword_custom', false);

			$keywords = $keywords[0];
?>
				<script type="text/javascript">
					var increment = 10000;

					function more_keyword_links(){
						jQuery("#keyword_link_first").before("<tr><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][name]'></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][url]'></td><td><input type='text' value='' name='internal_link_building["+increment+"][times]' size='4' /></td><td><input type='text' value='' name='internal_link_building["+increment+"][between]' size='4' /></td><td><input type='text'  value='' name='internal_link_building["+increment+"][befire]' size='4' /></td><td><input type='text' value='' name='internal_link_building["+increment+"][after]' size='4' /></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][case]'></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][nofollow]'></td></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][newwindow'></td></tr>");
						increment++;

					}
					jQuery(document).ready(function(){
						jQuery(".hlink").click(function(){
							var d= jQuery("#post [name^='internal_link_building']").fieldSerialize();
							if(d.length > 5){
								d += '&update_keylink_custom=1&post_ID=<? echo $_GET['post'];?>'
								var path = '<?php echo  $_SERVER['REQUEST_URI']; ?>';
								 
								if(-1==path.indexOf('?'))
									window.location =  path+'?'+d;
								else
									window.location =  path+'&'+d;
							}
						});	
					});
				</script>
				
				<table width="100%" id="keyword_links">
					<?php internal_link_building::print_menu($keywords)?>
				</table>

				<?php if($_GET['post']){ // We do not want to show this link unless the post is already assinged an ID. ?>
						
						<a href="#" onclick="more_keyword_links(); return false;">Add More Keywords</a>&nbsp;&nbsp;
						<a  class="hlink" style="text-decoration:underline;">Add New Keywords</a>
			
				<?php }else{ ?>
					<!--<a href="#" onclick="more_keyword_links(); return false;">Add More Keywords</a>-->
					<div>First create post for adding keywords.</div>
					<script>jQuery("#keyword_links").hide();</script>
				<?php } ?>

<?php
	}

/******************************
*
* Prints individual menu items for both edit post page
* And main keywords admin page.
*
/*****************************/

	static function print_menu($keywords, $show_first = true){
	
	?>
		
		<thead><tr><td>Keyword</td><td>URL</td><td style="font-size:10px">Times <br/>(optional)</td><td  style="font-size:10px">Words Between <br/>(optional)</td><td style="font-size:10px">Words Before<br/> (optional)</td><td style="font-size:10px">Words after <br/>(optional)</td><td>Exact Match</td><td>Use Nofollow?</td><td>Open in New Window?</td></tr></thead>

			<tr id="keyword_link_first" >
				<td><input type='text' style='width:90%;' value='' name='internal_link_building[0][name]'></td>
				<td><input type='text' style='width:90%;' value='' name='internal_link_building[0][url]'></td>
				<td><input type='text' size="4" value='' name='internal_link_building[0][times]'></td>
				<td><input type='text' size="4" value='' name='internal_link_building[0][between]'></td>
				<td><input type='text' size="4" value='' name='internal_link_building[0][before]'></td>
				<td><input type='text' size="4" value='' name='internal_link_building[0][after]'></td>
				<td><input type='checkbox' value = '1' name='internal_link_building[0][case]'></td>
				<td><input type='checkbox' value = '1' name='internal_link_building[0][nofollow]'></td>
				<td><input type='checkbox' value = '1' name='internal_link_building[0][newwindow]'></td>
			</tr>

<?php

		$x++;
		if($keywords){

			while (list($name, $ops) = each($keywords)) {

				if($name == 'keywords_time')
					continue;

				$case = '';
				if($ops['case'] == 1)
					$case = ' checked="checked" ';
				$nofollow = '';
				if($ops['nofollow'] == 1)
					$nofollow = ' checked="checked" ';
				$newwindow = '';
				if($ops['newwindow'] == 1)
					$newwindow = ' checked="checked" ';

				$name = str_replace("'","&#39;",stripslashes($name));

				echo "
					<tr>
						<td><input type='text' style='width:90%;' value='$name' name='internal_link_building[$x][name]' /></td>
						<td><input type='text' style='width:90%;' value='$ops[url]' name='internal_link_building[$x][url]' /></td>
						<td><input type='text' value='$ops[times]' name='internal_link_building[$x][times]' size='4' /></td>
						<td><input type='text' value='$ops[between]' name='internal_link_building[$x][between]' size='4' /></td>
						<td><input type='text' value='$ops[before]' name='internal_link_building[$x][before]' size='4' /></td>
						<td><input type='text' value='$ops[after]' name='internal_link_building[$x][after]' size='4' /></td>
						<td><input type='checkbox' $case value='1' name='internal_link_building[$x][case]' /></td>
						<td><input type='checkbox' $nofollow value='1' name='internal_link_building[$x][nofollow]' /></td>
						<td><input type='checkbox' $newwindow value='1' name='internal_link_building[$x][newwindow]' /></td>
					</tr>
					";
				$x++;
			}
		}

	}

/******************************	
*
* Purpose: Adds admin menu item to WP menu.
*
/*****************************/

	static function menu() {

		add_options_page('Internal linking', 'Internal linking', 8, __FILE__,'internal_link_building_admin');

	}

} /*Class ends*/



/******************************
*
* Prints and handles the admin menu.
* Called directly by WP.
*
/*****************************/

function internal_link_building_admin(){
global $wpdb;

	$keywords = get_option('internal_link_building');
	$options = get_option('internal_link_building_options');
	if ($_POST["action"] == "saveconfiguration") {
			$keywords = internal_link_building::update_options($_REQUEST['internal_link_building']);
			$keywords['keywords_time'] = time();
			update_option('internal_link_building',$keywords);

			$options = $_REQUEST['internal_link_building_options'];
			update_option('internal_link_building_options',$_REQUEST['internal_link_building_options']);
			$message .= 'Keywords Updated.<br/>';

	}elseif($_POST["action"] == "import"){

			if($_POST['keywordcvs'] != ''){
				$imported_keywords = internal_link_building::convert_cvs($_POST['keywordcvs']);
				

			}elseif($_POST['keywordfile']){
				$imported_keywords = internal_link_building::convert_cvs(file_get_contents($_POST['keywordfile']));
				$keywords = array();
			}

			$keywords['keywords_time'] = time();

			$keywords = array_merge((array)$keywords, (array) $imported_keywords);

			update_option('internal_link_building',$keywords);

			$message .= 'CVS imported.<br/>';

	}
?>
				<script type="text/javascript">
					var increment = 10000;

					function more_keyword_links(){
		
						jQuery("#keyword_link_first").before("<tr><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][name]'></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][url]'></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][times]' size='4' /></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][between]' size='4' /></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][before]' size='4' /></td><td><input type='text' style='width:90%;' value='' name='internal_link_building["+increment+"][after]' size='4' /></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][case]'></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][nofollow]'></td><td><input type='checkbox' value = '1' name='internal_link_building["+increment+"][newwindow]'></td></tr>");

						increment++;

					}

				</script>
	<div class="wrap">
        <div style="display:table-cell;vertical-align: top;"><a href="http://www.internetmarketingninjas.com/" target="_blank"><img src="/wp-content/plugins/internal-link-building-plugin/IMN-logo.png" width="100px"/></a></div><div style="padding-top:8px;display:table-cell;">This plugin is supported by <a href="http://www.internetmarketingninjas.com/" target="_blank">Internet Marketing Ninjas</a></div>
		<form method="post">
		<h2>Keyword Linking Configuration</h2>
			<div id="advancedstuff" class="dbx-group" >
				<div class="dbx-b-ox-wrapper">
					<fieldset id="instructions" class="dbx-box">
						<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">Instructions</h3></div>
							<div class="dbx-c-ontent-wrapper">
								<div class="dbx-content">
								<p>The following is a list of all <em>global</em> keywords. These can be overwritten on individual posts.</p>
								<p>To remove a keyword, just delete the keyword. The entire entry will be removed.</p>
								<p>By default the keyword "dog" will match "dog" and "Dog." To disable this behavior, select "exact match."</p>
								<p>If you only want to link a keyword a certain number of times in a post, you may set this using the "Times" option.</p>
								<p>If you would like a keyword to randomly link to one of several URLs, separate each URL with a bar '|'.</p>
								<p>If you would like to link multiple keywords to a URL, separate each keyword with a bar '|'. (Times will refer to each keyword not all combined.)</p>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="dbx-b-ox-wrapper">
					<fieldset id="accesskeys" class="dbx-box">
						<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">Current Keywords</h3></div>
							<div class="dbx-c-ontent-wrapper">
								<div class="dbx-content">
								<table width="100%" id="keyword_links">
								<?php internal_link_building::print_menu($keywords);?>
								</table>
								<a href="#" onclick="more_keyword_links(); return false;">Add More Keyword Blanks</a> (Does not save keywords.)
							</div>
						</div>
					</fieldset>

				</div>	<div class="dbx-b-ox-wrapper">
					<fieldset id="accesskeys" class="dbx-box">
						<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">General Options </h3></div>
							<div class="dbx-c-ontent-wrapper">
								<div class="dbx-content">
								<p>Maximimum number of keywords in a single post? <input type="text" value="<?php echo $options['max_keys']?>" name="internal_link_building_options[max_keys]" /> (Leave this blank for unlimited)</p>
								<p>Exclude Keyword Linking on Posts? <input type="checkbox" value="1" <?php echo ($options['no_posts']) ? 'checked="checked"' :'';?> name="internal_link_building_options[no_posts]" /></p>
								<p>Exclude Keyword Linking on Pages? <input type="checkbox" value="1" <?php echo ($options['no_pages']) ? 'checked="checked"' :'';?> name="internal_link_building_options[no_pages]" /></p>
							</div>
						</div>
					</fieldset>

				</div>
			</div>

		<input type="hidden" name="action" value="saveconfiguration">
		<input type="submit" value="Save" style="width:100%;" >
	</form>

	<form method="post">
		<h2>Keyword CVS Import</h2>
			<div id="advancedstuff" class="dbx-group" >
				<div class="dbx-b-ox-wrapper">
					<fieldset id="instructions" class="dbx-box">
						<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">Instructions</h3></div>
							<div class="dbx-c-ontent-wrapper">
								<div class="dbx-content">
									<p>Use the textarea below to import a CVS file. Please Note the following items before use:</p>
									<ul>
										<li>The CVS file should be tab or comma delimited with one keyword entry per line.</li> 
										<li>Do not mix and match commas and tabs. If using commas, ensure your URLs do not include commas or the entries will be corrupt.</li>
										<li>The order should be: Keyword, URL, Number of Times, Exact Match, Use Nofollow, Between, Before, After.</li>
										<li>Only Keyword and URL are required; however, if you want to specify Case, you must specify times.</li>
										<li>Exact Match and Nofollow are either a 0 or a 1. Times can be any integer with 0 meaning unlimited.</li>
										<li>The maximum number of keywords you may import at once are based on YOUR browser and server.</li>
									</ul>
									<textarea name="keywordcvs" id="keywordcvs" Rows="5" style="width:90%;"></textarea>
									<p>You may also specify a URL instead of manually inputting the keywords into text file. <b>Using this method DELETES all existing keywords.</b></p>
									<input name="keywordfile" />
								</div>
							</div>
						</div>
					</fieldset>
				</div>


		<input type="hidden" name="action" value="import">
		<input type="submit" value="Import CVS" style="width:100%;" >
	</form>
<?php
/*
	$posts = $wpdb->get_results("SELECT `post_ID` FROM {$wpdb->postmeta} WHERE meta_key = 'keyword_custom' && `post_ID` <> 0 ORDER BY `post_ID` DESC");

	if($posts){

		echo '<h3>Posts with Custom Keywords</h3><ol>';

		foreach($posts as $post){

			echo '<li><a href="post.php?action=edit&amp;post='.$post->post_ID.'">'.get_the_title($post->post_ID).'</a> (<a href="'.get_permalink($post->post_ID).'">View</a>)</li>';

		}

		echo '</ol>';
	}
*/
?>
</div> <!--Matches class wrap-->
<?php

}
/*
The following class is copyright Aaron Harun and was used from AJAXed WordPress with permission.
The following class may not be re-used in other projects without permission.
*/

/******************************
* Purpose: Protects block and html tags from word replacements.
* Temporarily it replaces the blocks and returns them.
*
/*****************************/

class internal_link_building_blocks{

	static function findtags($content,$firstrun=true){
	global $protectblocks;

//protects a tags
		$content = preg_replace_callback('!(\<a[^>]*\>([^>]*)\>)!ims', array('internal_link_building_blocks','returnblocks'), $content);

		if($firstrun){
	//Protects content within <blockquote tags
			//$content = preg_replace_callback('!(\<blockquote\>[\S\s]*?\<\/blockquote\>)!ims', array('internal_link_building_blocks','returnblocks'), $content);
	
	//Protects content within <pre tags.
			//$content = preg_replace_callback('!(\<pre\>[\S\s]*?\<\/pre\>)!ims', array('internal_link_building_blocks','returnblocks'), $content);
	
	//protects code tags.
			$content = preg_replace_callback('!(\<code\>[\S\s]*?\<\/code\>)!ims', array('internal_link_building_blocks','returnblocks'), $content);
	
	//protects simple tags tags
			$content = preg_replace_callback('!(\[tags*\][\S\s]*?\[\/tags*\])!ims', array('internal_link_building_blocks','returnblocks'), $content);
	
	//protects img tags
			$content = preg_replace_callback('!(\<img[^>]*\>)!ims', array('internal_link_building_blocks','returnblocks'), $content);
	
	//protects all correctly formatted URLS
			$content = preg_replace_callback('!(([A-Za-z]{3,9})://([-;:&=\+\$,\w]+@{1})?([-A-Za-z0-9\.]+)+:?(\d+)?((/[-\+~%/\.\w]+)?\??([-\+=&;%@\.\w]+)?#?([\w]+)?)?)!', array('internal_link_building_blocks','returnblocks'), $content);
	
	//protects urls of the form yahoo.com
			$content = preg_replace_callback('!([-A-Za-z0-9_]+\.[A-Za-z][A-Za-z][A-Za-z]?\W?)!', array('internal_link_building_blocks','returnblocks'), $content);
		}

		return $content;
	}

	static function returnblocks($blocks){
		global $protectblocks;
		$protectblocks[] = $blocks[1];
		return '[block]'.(count($protectblocks)-1).'[/block]';
	}


	static function findblocks($output){
	global $protectblocks;
		if(is_array($protectblocks)){
			$output = preg_replace_callback('!(\[block\]([0-9]*?)\[\/block\])!', array('internal_link_building_blocks','return_tags'), $output);
		}
		$protectblocks = '';
	return $output;
	}

	static function return_tags($blocks){
		global $protectblocks;
		return $protectblocks[$blocks[2]];
	}
}

/******************************
*
* Purpose: To allow case insensative string positioning in in older php versions.
*
/*****************************/

//PHP compatability
if(!function_exists('stripos')){
	function stripos($haystack, $needle){
		return strpos($haystack, stristr( $haystack, $needle ));
	}
}
if(!function_exists('sortByLength_desc')){
	function sortByLength_desc($a,$b){
		return strlen($b)-strlen($a) ;
	}
}
?>
