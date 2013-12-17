<?php
add_action('admin_menu', 'yinstagram_register_menu_page');

function yinstagram_register_menu_page() {
  // Call scripts in admin
  add_action('admin_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
  // Call styles in admin
  add_action('admin_enqueue_scripts', 'yinstagram_admin_enqueue_styles');

  add_menu_page('Settings', 'YInstagram', 'add_users', 'yinstagram/settings.php', 'yinstagram_settings_page', network_home_url('wp-content/plugins/yakadanda-instagram/img/instagram-icon-16x16.png'), 205);

  $settings_page = add_submenu_page('yinstagram/settings.php', 'Settings', 'Settings', 'manage_options', 'yinstagram/settings.php', 'yinstagram_settings_page');
  add_action('load-' . $settings_page, 'yinstagram_settings_help_tab');

  $display_options_page = add_submenu_page('yinstagram/settings.php', 'Display Options', 'Display Options', 'manage_options', 'yinstagram/display-options.php', 'yinstagram_display_options_page');
  add_action('load-' . $display_options_page, 'yinstagram_display_options_help_tab');
}

function yinstagram_settings_page() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  
  $data = array_merge((array) get_option('yinstagram_settings'), (array) get_option('yinstagram_access_token'));
  $display_options = get_option('yinstagram_display_options');
  
  // set default
  $data['number_of_images'] = isset($data['number_of_images']) ? $data['number_of_images'] : '1';
  
  $data['size'] = isset($data['size']) ? $data['size'] : 'thumbnail';
  $data['size'] = isset($display_options['size']) ? $display_options['size'] : $data['size'];
  
  $data['display_your_images'] = isset($data['display_your_images']) ? $data['display_your_images'] : 'recent';
  $data['option_display_the_following_hashtags'] = isset($data['option_display_the_following_hashtags']) ? $data['option_display_the_following_hashtags'] : 0;
  
  include dirname(__FILE__) . '/page-settings.php';
}

function yinstagram_display_options_page() {
  if (!current_user_can('manage_options')) wp_die(__('You do not have sufficient permissions to access this page.'));
  
  $response = null;
  if (isset($_POST["update_display_options"])) {
    $action = update_option('yinstagram_display_options', $_POST['ydo']);
    if ($action) $response = array('class' => 'updated', 'msg' => 'Display options changed.');
  }
  
  $data = get_option('yinstagram_display_options');
  
  $data['direction'] = isset($data['direction']) ? $data['direction'] : 'forwards';
  $data['size'] = isset($data['size']) ? $data['size'] : 'thumbnail';
  $data['number_of_images'] = isset($data['number_of_images']) ? $data['number_of_images'] : '1';
  $data['display_social_links'] = isset($data['display_social_links']) ? $data['display_social_links'] : null;
  
  include dirname(__FILE__) . '/page-display-options.php';
}

function yinstagram_encodeURIComponent($str) {
  $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
  return strtr(rawurlencode($str), $revert);
}

function yinstagram_settings_help_tab() {
  $screen = get_current_screen();

  $screen->add_help_tab(array(
      'id' => 'yinstagram-setup',
      'title' => __('Setup'),
      'content' => yinstagram_section_setup(),
  ));
  $screen->add_help_tab(array(
      'id' => 'yinstagram-shortcode',
      'title' => __('Shortcode'),
      'content' => yinstagram_section_shortcode(),
  ));
}

function yinstagram_display_options_help_tab() {
  $screen = get_current_screen();

  $screen->add_help_tab(array(
      'id' => 'yinstagram-setup',
      'title' => __('Setup'),
      'content' => yinstagram_section_setup(),
  ));
  $screen->add_help_tab(array(
      'id' => 'yinstagram-shortcode',
      'title' => __('Shortcode'),
      'content' => yinstagram_section_shortcode(),
  ));
}

function yinstagram_section_setup() {
  $output = '<h1>How to get your Instagram Client ID and Client Secret</h1>';
  $output .= '<ol>';
  $output .= '<li>Go to <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a> then login if not logged in.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-1.png"/></li>';
  $output .= '<li>Click Manage Clients, or Register Your Application button.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-2.png"/></li>';
  $output .= '<li>Register new OAuth Client by click Register a New Client button.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-3.png"/></li>';
  $output .= '<li>Setup Register new OAuth Client form.<br><br>';
  $output .= 'a. Fill textboxes and textarea with suitable information<br>';
  $output .= 'b. Fill OAuth redirect_uri textbox with <span>' . YINSTAGRAM_PLUGIN_URL . '/authentication.php</span><br>';
  $output .= '<img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-4.png"/></li>';
  $output .= '<li>Congratulation, now you have Client ID and Client Secret.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-5.png"/></li>';
  $output .= '</ol>';

  return $output;
}

function yinstagram_section_shortcode() {
  $output = '<p><span>[yinstagram]</span></p>';

  return $output;
}

function yinstagram_get_user_info( $auth ) {
  $output = null;
  
  $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/' . $auth['user']->id . '/?access_token=' . $auth['access_token']);
  $responses = json_decode($responses);
  
  if ( $responses->meta->code == 200 )
    $output = $responses->data;
  
  return $output;
}

function yinstagram_get_user_id($auth, $username = null) {
  $id = null;
  
  if ($username) {
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/search?q=' . $username . '&access_token=' . $auth['access_token']);
    
    $responses = json_decode($responses);
    
    if ( $responses->meta->code == 200 ) {
      if ( ! empty($responses->data) ) $id = $responses->data[0]->id;
    }
  }
  
  return $id;
}
