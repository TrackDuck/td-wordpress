<?php
/*
Plugin Name: TrackDuck
Plugin URI: https://trackduck.com/
Description: Description goes here
Version: 0.1.0
Author: Arūnas Liuiza
Author URI: http://wordofpress.com
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
  echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
  exit;
}


register_activation_hook( __FILE__, array( 'TrackDuck', 'activate' ) );
add_action( 'plugins_loaded', array( 'TrackDuck', 'init' ) );
add_action( 'admin_enqueue_scripts', array('TrackDuck','admin_css' ));

class TrackDuck {
  const VERSION = '0.1.0';
  protected static $active = false;
  protected static $options = array();
  protected static $includes_dir;
  public static function admin_css($hook){
    if ($hook=='settings_page_trackduck_options') {
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
  public static function script() {
    $trackduck_id = self::$options['trackduck_id'];
    $trackduck_active = self::$options['trackduck_active'];
    if ($trackduck_active && !is_admin())
      echo '                <script src="//tdcdn.blob.core.windows.net/toolbar/assets/prod/td.js" data-trackduck-id="'.$trackduck_id.'" async=""></script>';
  }
}
