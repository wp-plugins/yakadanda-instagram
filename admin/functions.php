<?php
add_action('admin_menu', 'yinstagram_register_menu_page');
function yinstagram_register_menu_page() {
  // Call scripts in admin
  add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
  // Call styles in admin
  add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_styles' );

  add_menu_page( 'Settings', 'YInstagram', 'add_users', 'yinstagram/settings.php', 'yinstagram_settings_page', network_home_url( 'wp-content/plugins/yakadanda-instagram/img/instagram-icon.png' ), 205 );
  add_submenu_page( 'yinstagram/settings.php', 'Settings', 'Settings', 'manage_options', 'yinstagram/settings.php', 'yinstagram_settings_page' );
  add_submenu_page( 'yinstagram/settings.php', 'Display Options', 'Display Options', 'manage_options', 'yinstagram/display-options.php', 'yinstagram_display_options_page' );
}

function yinstagram_settings_page() {
  global $wpdb;
  
  if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
  
  $data = array_merge( (array)get_option( 'yinstagram_settings' ), (array)get_option( 'yinstagram_access_token' ));
  
  /* for upgrade from 0.0.12 to 0.0.20 */
  $post_yinstagram_settings = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-settings'");
  if ($post_yinstagram_settings) {
    $old_yinstagram_settings = maybe_unserialize($post_yinstagram_settings->post_content);
  }
  
  $client_id = null;
  if ( $data['client_id'] ) {
    $client_id = $data['client_id'];
  } elseif( $old_yinstagram_settings && !$data['client_id'] ) {
    $client_id = $old_yinstagram_settings['client_id'];
  }
  $client_secret = null;
  if ( $data['client_secret'] ) {
    $client_secret = $data['client_secret'];
  } elseif( $old_yinstagram_settings && !$data['client_id'] ) {
    $client_secret = $old_yinstagram_settings['client_secret'];
  }
  /* end for upgrade */
  
  include dirname(__FILE__) . '/page-settings.php';
}

function yinstagram_display_options_page() {
  if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
  if (isset($_POST["update_display_options"])) {
    $action = update_option('yinstagram_display_options', $_POST['ydo']);
    $response = array('class' => 'error', 'msg' => 'Failed.');
    if ($action) $response = array('class' => 'updated', 'msg' => 'Display options changed.');
  }
  $data = get_option('yinstagram_display_options');
  include dirname(__FILE__) . '/page-display-options.php';
}

function yinstagram_encodeURIComponent($str) {
  $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
  return strtr(rawurlencode($str), $revert);
}

add_action( 'admin_notices', 'yinstagram_notice');
function yinstagram_notice() {
  global $wpdb;
  $post_yinstagram_settings = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-settings'");
  
  if ( !get_option('yinstagram_settings') && $post_yinstagram_settings ) {
    echo '<div class="updated"><p>Please reconfigure YInstagram preferences.</p></div>';
  }
}
