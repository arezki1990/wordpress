<?php
/*
Plugin Name: Android 5.0 Lollipop Status Bar Customiser
Plugin URI: http://www.webniraj.com/wordpress/
Description: Use this plugin to change the Status Bar of Devices Running Android 5.0 Lollipop.
Author: Niraj Shah
Version: 1.2.1
Author URI: http://www.webniraj.com/
*/

class WN_Android_50_Statusbar
{
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;
  
  /**
   * Start up
   */
  public function __construct()
  {
    if ( is_admin() ) {
      add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
      add_action( 'admin_init', array( $this, 'page_init' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'load_media_files' ) );
      add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_links' ) );
    }
    add_action( 'wp_head', array( $this, 'add_android_meta' ) );
  }
  
  /**
   * Add Settings Plugin link
   */
  function add_action_links( $links ) {
    $mylinks = array(
      '<a href="' . admin_url( 'themes.php?page=wn-android-statusbar' ) . '">Settings</a>',
    );
    // remove edit link
    unset( $links['edit'] );
    // return new links
    return array_merge( $mylinks, $links );
  }
  
  /**
   * Add options page
   */
  public function add_plugin_page()
  {
    // This page will be under "Settings"
    add_theme_page(
      'Settings Admin', 
      'Android Status Bar', 
      'manage_options', 
      'wn-android-statusbar', 
      array( $this, 'settings_page' )
    );
  }
  
  /**
   * Add media files
   */
  public function load_media_files()
  {
    wp_enqueue_media();
    wp_enqueue_script( 'wn-script', plugins_url( 'js/wn-script.js', __FILE__ ), array(), '1.0.0', true );
  }
  
  /**
   * Options page callback
   */
  public function settings_page()
  {
    // Set class property
    $this->options = get_option( 'wn_android_statusbar' );
    ?>
    <div class="wrap">
      <?php screen_icon(); ?>
      <h2>Androind 5.0 Lollipop Statusbar</h2>           
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields( 'wn_android_options' );   
        do_settings_sections( 'wn-android-statusbar' );
        submit_button(); 
      ?>
      </form>
    </div>
    <?php
  }
  
  /**
   * Add android meta data to header
   */
  public function add_android_meta()
  {
    // get options
    $this->options = get_option( 'wn_android_statusbar' );
    
    $output = '<!-- WebNiraj Androind 5.0 Status Bar Plugin -->' . PHP_EOL;
    
    if ( is_front_page() ) {
      $output .= '<meta name="theme-color" content="#'. ( isset( $this->options['home-colour'] ) ? $this->options['home-colour'] : '#3B9BD6' ) .'">' . PHP_EOL;
    } else if ( is_single() ) {
      $output .= '<meta name="theme-color" content="#'. ( isset( $this->options['post-colour'] ) ? $this->options['post-colour'] : '#57585A' ) .'">' . PHP_EOL;
    } else if ( is_page() ) {
      $output .= '<meta name="theme-color" content="#'. ( isset( $this->options['page-colour'] ) ? $this->options['page-colour'] : '#91268E' ) .'">' . PHP_EOL;
    } else {
      $output .= '<meta name="theme-color" content="#'. ( isset( $this->options['default-colour'] ) ? $this->options['default-colour'] : '#3F51B5' ) .'">' . PHP_EOL;
    }
    
    if ( isset( $this->options['android-icon'] ) ) {
      $output .= '<link rel="icon" sizes="192x192" href="'. $this->options['android-icon'] .'">' . PHP_EOL;
    }
    
    echo $output;
  }
  
  /**
   * Register and add settings
   */
  public function page_init()
  {        
    register_setting(
      'wn_android_options', // Option group
      'wn_android_statusbar', // Option name
      array( $this, 'sanitize' ) // Sanitize
    );

    add_settings_section(
      'android-colours', // ID
      'Status Bar Colour', // Title
      array( $this, 'print_section_info_colours' ), // Callback
      'wn-android-statusbar' // Page
    );

    add_settings_field(
      'default-colour', // ID
      'Default Colour', // Title 
      array( $this, 'colour_callback' ), // Callback
      'wn-android-statusbar', // Page
      'android-colours', // Section     
      array( 'id' => 'default-colour' )
    );
    
    add_settings_field(
      'home-colour', // ID
      'Home Page Colour', // Title 
      array( $this, 'colour_callback' ), // Callback
      'wn-android-statusbar', // Page
      'android-colours', // Section     
      array( 'id' => 'home-colour' )
    );
    
    add_settings_field(
      'page-colour', // ID
      'Page Colour', // Title 
      array( $this, 'colour_callback' ), // Callback
      'wn-android-statusbar', // Page
      'android-colours', // Section     
      array( 'id' => 'page-colour' )  
    );
    
    add_settings_field(
      'post-colour', // ID
      'Post Colour', // Title 
      array( $this, 'colour_callback' ), // Callback
      'wn-android-statusbar', // Page
      'android-colours', // Section     
      array( 'id' => 'post-colour' )
    );
    
    add_settings_section(
      'android-icons', // ID
      'Icon', // Title
      array( $this, 'print_section_info_icons' ), // Callback
      'wn-android-statusbar' // Page
    );
    
    add_settings_field(
      'android-icon', // ID
      'Android Icon (192px &times; 192px)', // Title 
      array( $this, 'file_callback' ), // Callback
      'wn-android-statusbar', // Page
      'android-icons', // Section     
      array( 'id' => 'android-icon' )
    );
  }
  
  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitize( $input )
  {
    $new_input = array();
    foreach ( $input as $id => $val )
      $new_input[$id] = esc_attr( $val );

    return $new_input;
  }
  
  /** 
   * Print the Section text for Colours
   */
  public function print_section_info_colours()
  {
    echo 'Enter a <a href="http://www.w3schools.com/tags/ref_colorpicker.asp" target="_blank">HEX colour</a> below:';
  }
  
  /** 
   * Print the Section text for Icons
   */
  public function print_section_info_icons()
  {
    echo 'Enter a URL or select a file to use from the Media Gallery. For best results, use an absolute URL.';
  }
  
  /** 
   * Get the settings option array and print one of its values
   */
  public function colour_callback( $args )
  {
    printf(
      '#<input type="text" name="wn_android_statusbar[%s]" value="%s" placeholder="3f51b5" />',
      $args['id'],
      isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ] ) : ''
    );
  }
  
  /** 
   * Get the settings option array and print one of its values
   */
  public function file_callback( $args )
  {
    printf(
      '<input type="text" name="wn_android_statusbar[%s]" value="%s" placeholder="" /> <button type="button" class="upload_image_button button button-info" data-uploader-title="Select Android Icon" data-uploader-button-text="Set as Android Icon">Select</button>',
      $args['id'],
      isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ] ) : ''
    );
  }
  
}

// run plugin
$wn_android_statusbar = new WN_Android_50_Statusbar();