<?php
/**
 * Plugin Name: Yakadanda Instagram
 * Plugin URI: http://www.yakadanda.com/plugins/yakadanda-instagram/
 * Description: A Wordpress plugin that pulls in Instagram images of user or hashtags.
 * Version: 0.2.2
 * Author: Peter Ricci
 * Author URI: http://www.yakadanda.com/
 * Text Domain: yakadanda-instagram
 * Domain Path: /languages/
 * License: GPL2
 */

/* Put setup procedures to be run when the plugin is activated in the following function */
register_activation_hook(__FILE__, 'yinstagram_activate');
function yinstagram_activate() {
  if (!get_option('yinstagram_settings')) { add_option('yinstagram_settings', null, false, false); }
  if (!get_option('yinstagram_display_options')) { add_option('yinstagram_display_options', null, false, false); }
  if (!get_option('yinstagram_access_token')) { add_option('yinstagram_access_token', null, false, false); }
}

// On deacativation, clean up anything your component has added.
register_deactivation_hook(__FILE__, 'yinstagram_deactivate');
function yinstagram_deactivate() {
  // You might want to delete any options or tables that your component created.
  
}

if (!defined('YINSTAGRAM_VER')) define('YINSTAGRAM_VER', '0.2.2');
if (!defined('YINSTAGRAM_PLUGIN_DIR')) define('YINSTAGRAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!defined('YINSTAGRAM_PLUGIN_URL')) define('YINSTAGRAM_PLUGIN_URL', plugins_url(null, __FILE__));
if (!defined('YINSTAGRAM_THEME_DIR')) define('YINSTAGRAM_THEME_DIR', get_stylesheet_directory());
if (!defined('YINSTAGRAM_THEME_URL')) define('YINSTAGRAM_THEME_URL', get_stylesheet_directory_uri());

// Load plugin textdomain
add_action('plugins_loaded', 'yinstagram_load_textdomain');
function yinstagram_load_textdomain() {
  load_plugin_textdomain('yakadanda-instagram', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}

add_filter('plugin_action_links', 'yinstagram_action_links', 10, 2);
function yinstagram_action_links($links, $file) {
  static $yakadanda_instagram;
  
  if (!$yakadanda_instagram) $yakadanda_instagram = plugin_basename(__FILE__);
  
  if ($file == $yakadanda_instagram) {
    $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=yinstagram-settings">Settings</a>';
    array_unshift($links, $settings_link);
  }
  
  return $links;
}

// Register scripts
add_action('init', 'yinstagram_register');
function yinstagram_register() {
  global $yinstagram_options;
  
  /* Register styles */
  wp_register_style('yinstagram-colorbox', YINSTAGRAM_PLUGIN_URL . '/css/colorbox-' . $yinstagram_options['theme'] . '.css', false, '1.6.1', 'all');
  wp_register_style('yinstagram-qtip', YINSTAGRAM_PLUGIN_URL . '/css/jquery.qtip.min.css', array(), '2.2.1', 'all');
  wp_register_style('yinstagram-jquery-ui', YINSTAGRAM_PLUGIN_URL . '/css/jquery-ui.css', array(), YINSTAGRAM_VER ,'all');
  wp_register_style('yinstagram-admin', YINSTAGRAM_PLUGIN_URL . '/css/admin.css', array(), YINSTAGRAM_VER, 'all');
  
  if (file_exists(YINSTAGRAM_THEME_DIR . '/css/yakadanda-instagram.css')) {
    wp_register_style('yinstagram-style', YINSTAGRAM_THEME_URL . '/css/yakadanda-instagram.css', array(), YINSTAGRAM_VER, 'all');
  } else {
    wp_register_style('yinstagram-style', YINSTAGRAM_PLUGIN_URL . '/css/yakadanda-instagram.css', array(), YINSTAGRAM_VER, 'all');
  }
  
  /* Register scripts */
  // simplyScroll
  wp_register_script('yinstagram-simplyScroll', YINSTAGRAM_PLUGIN_URL . '/js/jquery.simplyscroll.min.js', array('jquery'), '2.0.5', true);
  // ColorBox
  wp_register_script('yinstagram-colorbox', YINSTAGRAM_PLUGIN_URL . '/js/jquery.colorbox-min.js', array('jquery'), '1.5.14', true);
  // qtip
  wp_register_script('yinstagram-qtip', YINSTAGRAM_PLUGIN_URL . '/js/jquery.qtip.min.js', array('jquery'), '2.2.1', true);
  // YInstagram
  wp_register_script('yinstagram-script', YINSTAGRAM_PLUGIN_URL . '/js/script.js', array('jquery'), YINSTAGRAM_VER, true);
  
  // ajax
  wp_localize_script('yinstagram-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));
}

// Enqueue scripts for admin
add_action('admin_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
function yinstagram_admin_enqueue_scripts() {
  wp_enqueue_style('yinstagram-admin');
  
  if (yinstagram_is_plugin_page(yinstagram_get_page())) {
    // load stylesheets
    wp_enqueue_style('yinstagram-jquery-ui');
    
    // load javascripts
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('yinstagram-script');
  }
}

// Enqueue scripts for frontend
add_action('wp_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
function yinstagram_wp_enqueue_scripts() {
  
}
function yinstagram_wp_enqueue_scripts_load($yinstagram_options) {
  if ($yinstagram_options['lightbox'] == 'colorbox') { wp_enqueue_style('yinstagram-colorbox'); }
  if ($yinstagram_options['tooltip'] == 'on') { wp_enqueue_style('yinstagram-qtip'); }
  wp_enqueue_style('yinstagram-style');

  if ($yinstagram_options['scroll'] == 'auto') { wp_enqueue_script('yinstagram-simplyScroll'); }
  if ($yinstagram_options['lightbox'] == 'colorbox') { wp_enqueue_script('yinstagram-colorbox'); }
  if ($yinstagram_options['tooltip'] == 'on') { wp_enqueue_script('yinstagram-qtip'); }
  wp_enqueue_script('yinstagram-script');
}

require_once( dirname(__FILE__) . '/admin/includes.php' );
