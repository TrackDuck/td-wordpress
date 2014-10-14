<?php
/*
Plugin Name: TrackDuck
Plugin URI: https://trackduck.com/
Description: Visual feedback for web-development
Version: 0.1.1.2
Author: TrackDuck & Arūnas Liuiza
Author URI: https://trackduck.com/
*/

// Make sure we don't expose any info if called directly
// block direct access to plugin file
defined('ABSPATH') or die("Hi there!  I'm just a plugin, not much I can do when called directly.");

register_activation_hook( __FILE__,   array( 'TrackDuck', 'activate' ) );
add_action( 'plugins_loaded',         array( 'TrackDuck', 'init' ) );
add_action( 'admin_enqueue_scripts',  array( 'TrackDuck', 'admin_css' ));
add_action( 'admin_enqueue_scripts',  array( 'TrackDuck', 'admin_script' ));

class TrackDuck {
  const VERSION = '0.1.1.2';
  protected static $active = false;
  protected static $options = array();
  protected static $includes_dir;
  public static function admin_css($hook){
    if ('settings_page_trackduck_options'==$hook) {
      wp_register_style( 'trackduck', plugins_url('trackduck.css', __FILE__) );
      wp_enqueue_style( 'trackduck' );
    }
  }
  public static function activate(){
    TrackDuck::init(true);
  }
  public static function init($reset=false) {
    define('TRACKDUCK_PLUGIN_URL',plugin_dir_url( __FILE__ ));
    self::$includes_dir = plugin_dir_path( __FILE__ ) . 'includes/';
    load_plugin_textdomain( 'trackduck_admin_styles', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    # Settings page
    require_once self::$includes_dir . 'options-data.php';
    require_once self::$includes_dir . 'options.php';
    $trackduck_settings = new TrackDuck_Options( TrackDuck_Options_Data::get() );
    if ($reset)
      self::$options =  $trackduck_settings->defaults();
    else
      self::$options = $trackduck_settings->get();
    add_action( 'wp_footer', array( __CLASS__, 'script' ) );
  }
  public static function admin_script($hook) {
    if ($hook=='settings_page_trackduck_options') {
      wp_register_script( 'trackduck', plugins_url('trackduck.js', __FILE__) );
      wp_localize_script( 'trackduck', 'trackduck_admin_data',array(
        'enable'  => __('Enable integration','trackduck'),
        'disable' => __('Disable integration','trackduck'),
        'project' => __('Open project settings','trackduck'),
        'url'     => get_bloginfo('url'),
        'update_browser' => sprintf(
          __('Please, update your browser. Currently we support all modern browsers and IE 10+','trackduck'),
          'https://app.trackduck.com/'
        ),
        'login' => sprintf(
          '<p><a href="%2$s" class="button" id="trackduck_google">'.__('Google signup','trackduck').'</a> <a href="%3$s" class="button" id="trackduck_facebook">'.__('Login with Facebook','trackduck').'</a></p><p><a href="%1$s">'.__('Login or register with email','trackduck').'</a></p>',
          'https://app.trackduck.com/auth/login?utm_source=plugin&utm_medium=wp&utm_content=en&utm_campaign=wp-hosted-plugin&redirect='.admin_url( "admin.php?page=trackduck_options" ),
          'https://app.trackduck.com/auth/google?utm_source=plugin&utm_medium=wp&utm_content=en&utm_campaign=wp-hosted-plugin&redirect='.admin_url( "admin.php?page=trackduck_options" ),
          'https://app.trackduck.com/auth/Facebook?utm_source=plugin&utm_medium=wp&utm_content=en&utm_campaign=wp-hosted-plugin&redirect='.admin_url( "admin.php?page=trackduck_options" )
        ),              
      ));
      wp_enqueue_script( 'trackduck' );
    }
  }
  public static function script() {
    $trackduck_id     = self::$options['trackduck_id'];
    $trackduck_active = self::$options['trackduck_active'];
    if ($trackduck_active && !is_admin())
      echo '                <script src="//tdcdn.blob.core.windows.net/toolbar/assets/prod/td.js" data-trackduck-id="'.$trackduck_id.'" async=""></script>';
  }
}
