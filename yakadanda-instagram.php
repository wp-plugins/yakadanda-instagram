<?php
/*
Plugin Name: Yakadanda Instagram
Plugin URI: http://www.yakadanda.com/plugins/yakadanda-instagram/
Description: A Wordpress plugin that pulls in Instagram images based on profile and hashtags.
Version: 0.0.12
Author: Peter Ricci
Author URI: http://www.yakadanda.com/
License: GPLv2 or later
*/

/* Put setup procedures to be run when the plugin is activated in the following function */
function yinstagram_activate() {
  
}
register_activation_hook( __FILE__, 'yinstagram_activate' );

// On deacativation, clean up anything your component has added.
function yinstagram_deactivate() {
	// You might want to delete any options or tables that your component created.
  
}
register_deactivation_hook( __FILE__, 'yinstagram_deactivate' );

// Register scripts & styles
add_action( 'init', 'yinstagram_register' );
function yinstagram_register() {
  /* Register YInstagram styles */
  // YInstagram style
  wp_register_style( 'yinstagram-style', plugins_url(null, __FILE__) . '/css/style.css', false, '0.0.11', 'all' );

  /* Register YInstagram scripts */
  // Helpers
  wp_register_script( 'yinstagram.ba-throttle-debounce', plugins_url(null, __FILE__) . '/js/helpers/jquery.ba-throttle-debounce.min.js', array('jquery'), '1.1', true );
  wp_register_script( 'yinstagram.mousewheel', plugins_url(null, __FILE__) . '/js/helpers/jquery.mousewheel.min.js', array('jquery'), '3.0.6', true );
  wp_register_script( 'yinstagram.touchSwipe', plugins_url(null, __FILE__) . '/js/helpers/jquery.touchSwipe.min.js', array('jquery'), '1.3.3', true );
  // carouFredSel
  wp_register_script( 'yinstagram.carouFredSel', plugins_url(null, __FILE__) . '/js/jquery.carouFredSel-6.1.0-packed.js', array('jquery', 'yinstagram.ba-throttle-debounce', 'yinstagram.mousewheel', 'yinstagram.touchSwipe'), '6.1.0', true );
  // YInstagram script
  wp_register_script( 'yinstagram.script', plugins_url(null, __FILE__) . '/js/script.js', array('yinstagram.carouFredSel'), '0.0.11', true );
}

// Enqueue styles for admin
function yinstagram_admin_enqueue_styles() {
  wp_enqueue_style( 'farbtastic' );
}
// Enqueue then call styles in frontend
add_action( 'wp_enqueue_scripts', 'yinstagram_wp_enqueue_styles' );
function yinstagram_wp_enqueue_styles() {
  wp_enqueue_style( 'yinstagram-style' );
}

// Enqueue scripts for admin
function yinstagram_admin_enqueue_scripts() {
  // farbtastic
  wp_enqueue_script( 'farbtastic' );
  
  // YInstagram Script
  wp_enqueue_script( 'yinstagram.script' );
}
// Enqueue then call scripts in frontend
add_action( 'wp_enqueue_scripts', 'yinstagram_wp_enqueue_scripts' );
function yinstagram_wp_enqueue_scripts() {
  // jQuery
  wp_enqueue_script( 'jquery' );
  
  // Helpers
  wp_enqueue_script( 'yinstagram.ba-throttle-debounce' );
  wp_enqueue_script( 'yinstagram.mousewheel' );
  wp_enqueue_script( 'yinstagram.touchSwipe' );
  
  // carouFredSel
  wp_enqueue_script( 'yinstagram.carouFredSel' );
  
  // YInstagram script
  wp_enqueue_script( 'yinstagram.script' );
}

add_action('admin_menu', 'yinstagram_register_menu_pages');
function yinstagram_register_menu_pages() {
  YISettings::register_menu_page();
  YIDisplayOptions::register_menu_page();
}

if(!defined('YAKADANDA_INSTAGRAM_PLUGIN_URL')) {
  define('YAKADANDA_INSTAGRAM_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
}

require_once( dirname(__FILE__) . '/settings/functions.php' );
require_once( dirname(__FILE__) . '/display-options/functions.php' );
require_once( dirname(__FILE__) . '/shortcode.php' );
