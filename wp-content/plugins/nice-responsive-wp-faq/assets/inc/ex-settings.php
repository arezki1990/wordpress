<?php


// Specify Hooks/Filters
register_activation_hook(__FILE__, 'add_defaults_fn');
add_action('admin_init', 'sampleoptions_init_fn' );
add_action('admin_menu', 'sampleoptions_add_page_fn');

// Define default option settings
function add_defaults_fn() {
	$tmp = get_option('plugin_options');
    if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
		$arr = array("dropdown1"=>"Orange", "text_area" => "Space to put a lot of information here!", "text_string" => "Some sample text", "pass_string" => "123456", "chkbox1" => "", "chkbox2" => "on", "option_set1" => "Triangle");
		update_option('plugin_options', $arr);
	}
}

// Register our settings. Add the settings section, and settings fields
function sampleoptions_init_fn(){
	register_setting('plugin_options', 'plugin_options', 'plugin_options_validate' );
	add_settings_section('main_section', '', 'section_text_fn', __FILE__);
	add_settings_field('plugin_text_string', 'Title Background', 'setting_string_fn', __FILE__, 'main_section');
	add_settings_field('plugin_text_pass', 'Title Color', 'setting_pass_fn', __FILE__, 'main_section');

}

// Add sub page to the Settings Menu
function sampleoptions_add_page_fn() {
	add_options_page('Options Example Page', 'Faq Color', 'administrator', __FILE__, 'options_page_fn');
}

// ************************************************************************************************************

// Callback functions

// Section HTML, displayed before the first option
function  section_text_fn() {
	echo '<p></p>';
}



// TEXTBOX - Name: plugin_options[text_string]
function setting_string_fn() {
	$options = get_option('plugin_options');
	echo "<input id='plugin_text_string' class='color-field'  name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}



// PASSWORD-TEXTBOX - Name: plugin_options[pass_string]
function setting_pass_fn() {
	$options = get_option('plugin_options');
	echo "<input id='plugin_text_pass' class='color-field' name='plugin_options[pass_string]' size='40' type='text' value='{$options['pass_string']}' />";
}



// Display the admin options page
function options_page_fn() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Color Picker</h2>
		Please select your desire color.
		<form action="options.php" method="post">
		<?php settings_fields('plugin_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

// Validate user data for some/all of your input fields
function plugin_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);	
	return $input; // return validated input
}




add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}
