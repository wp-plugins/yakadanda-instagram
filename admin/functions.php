<?php
function yinstagram_callback($buffer) {
  return $buffer;
}

add_action('init', 'yinstagram_add_ob_start');
function yinstagram_add_ob_start() {
  ob_start('yinstagram_callback');
}

add_action('wp_footer', 'yinstagram_flush_ob_end');
function yinstagram_flush_ob_end() {
  ob_end_flush();
}

$yinstagram_options = yinstagram_get_options();
function yinstagram_get_options($admin_page = 'all') {
  $output = array();
  
  if (($admin_page == 'all') || ($admin_page == 'settings')) {
    $default = array (
        'client_id' => null,
        'client_secret' => null,
        'display_your_images' => 'recent',
        'option_display_the_following_hashtags' => 0,
        'display_the_following_hashtags' =>  null,
        'size' => 'thumbnail',
        'number_of_images' => 1,
        'username_of_user_id' => null
      );
    $settings = wp_parse_args( get_option('yinstagram_settings'), $default );
    $output = array_merge((array) $output, (array) $settings);
  }
  
  if (($admin_page == 'all') || ($admin_page == 'display_options')) {
    // suit previous display options settings
    $display_options = get_option('yinstagram_display_options');
    
    $default = array(
        'scroll' => 'auto',
        'height' => 300,
        'frame_rate' => 24,
        'speed' => 1,
        'direction' => 'forwards',
        'tooltip' => 'off',
        'lightbox' => (isset($display_options['colorbox']) && ($display_options['colorbox'] == '1')) ? 'colorbox' : 'disable',
        'theme' => '1',
        'effect' => 'elastic',
        'display_social_links' => null,
        'order' => 'default'
      );
    $display_options_preferences = wp_parse_args( $display_options, $default );
    $output = array_merge((array) $output, (array) $display_options_preferences);
  }
  
  if (($admin_page == 'all') || ($admin_page == 'settings') || ($admin_page == 'token')) {
    $default = array(
      'access_token' => null,
      'user' => null
    );
    $token = wp_parse_args( get_option('yinstagram_access_token'), $default );
    $output = array_merge((array) $output, (array) $token);
  }
  
  return $output;
}

function yinstagram_get_page() {
  $requet_uri = str_replace('/wp-admin/', '', $_SERVER['REQUEST_URI']);
  
  return ($requet_uri) ? $requet_uri : 'index.php';
}

function yinstagram_is_plugin_page($url) {
  $output = false;
  
  switch ($url) {
    case 'admin.php?page=yinstagram/settings.php':
      $output = true;
      break;
    case 'admin.php?page=yinstagram/display-options.php':
      $output = true;
      break;
    case 'widgets.php':
      $output = true;
      break;
  }
  
  return $output;
}

add_action('admin_menu', 'yinstagram_register_menu_page');
function yinstagram_register_menu_page() {
  add_menu_page('Settings', 'YInstagram', 'add_users', 'yinstagram/settings.php', 'yinstagram_page_settings', 'none', 205);

  $settings_page = add_submenu_page('yinstagram/settings.php', 'Settings', 'Settings', 'manage_options', 'yinstagram/settings.php', 'yinstagram_page_settings');
  add_action('load-' . $settings_page, 'yinstagram_help_tab');

  $display_options_page = add_submenu_page('yinstagram/settings.php', 'Display Options', 'Display Options', 'manage_options', 'yinstagram/display-options.php', 'yinstagram_page_display_options');
  add_action('load-' . $display_options_page, 'yinstagram_help_tab');
}

function yinstagram_page_settings() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  
  $message = null;
  
  /* authentication */
  if (isset($_GET['code'])) {
    $message = maybe_serialize(array('cookie' => 1, 'class' => 'error', 'msg' => 'Connection to Instagram failed.'));
    
    $response = json_decode( yinstagram_access_token($_GET['code']) );
    
    if ( isset($response->access_token) ) {
      update_option('yinstagram_access_token', $response);
      $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => 'Connection to Instagram succeeded.'));
    }
    
    setcookie('yinstagram_response', $message, time()+1, '/');
    wp_redirect(admin_url('admin.php?page=yinstagram/settings.php')); exit;
  }
  /* end of authentication */
  
  /* posted data */
  if ( isset($_POST['update_settings']) ) {
    $data = yinstagram_get_options('settings');
    
    if (($_POST['display_images'] != 'hashtag') && ($_POST['option_display_the_following_hashtags'] == '1')) {
      $_POST['option_display_the_following_hashtags'] = '0';
    }
    if (($_POST['display_images'] == 'hashtag') && ($_POST['option_display_the_following_hashtags'] == '0')) {
      $_POST['display_images'] = 'recent';
    }
    if (($_POST['display_images'] == 'hashtag') && ($_POST['option_display_the_following_hashtags'] == '1') && empty($_POST['display_the_following_hashtags'])) {
      $_POST['display_images'] = 'recent';
      $_POST['option_display_the_following_hashtags'] = '0';
    }
    $_POST['client_id'] = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
    $_POST['client_secret'] = !empty($_POST['client_secret']) ? $_POST['client_secret'] : null;
    
    $value = array(
        'client_id' => $_POST['client_id'],
        'client_secret' => $_POST['client_secret'],
        'display_your_images' => $_POST['display_images'],
        'option_display_the_following_hashtags' => $_POST['option_display_the_following_hashtags'],
        'display_the_following_hashtags' => $_POST['display_the_following_hashtags'],
        'size' => $_POST['size'],
        'number_of_images' => $_POST['number_of_images'],
        'username_of_user_id' => $_POST['username_of_user_id']
      );
    update_option('yinstagram_settings', $value);
    
    $message = array('class' => 'updated', 'msg' => 'Settings updated.');
    
    $granted = false;
    if (($data['client_id'] != $_POST['client_id']) || ($data['client_secret'] != $_POST['client_secret'])) $granted = true;
    if (empty($data['access_token']) && $data['client_id'] && $data['client_secret']) $granted = true;
    
    if ($granted) {
      // make null the token from database
      update_option('yinstagram_access_token', null);
      
      $encodeURIComponent = yinstagram_encodeURIComponent(admin_url('admin.php?page=yinstagram/settings.php'));
      $url = 'https://api.instagram.com/oauth/authorize/?client_id=' . $_POST['client_id'] . '&redirect_uri=' . $encodeURIComponent . '&response_type=code';
      
      wp_redirect($url); exit;
    }
  }
  /* end of posted data */
  
  $data = yinstagram_get_options('settings');
  
  // message
  if (isset($_COOKIE['yinstagram_response'])) $message = maybe_unserialize(stripslashes($_COOKIE['yinstagram_response']));
  
  include dirname(__FILE__) . '/page-settings.php';
}

function yinstagram_page_display_options() {
  if (!current_user_can('manage_options')) wp_die(__('You do not have sufficient permissions to access this page.'));
  
  $message = null;
  if (isset($_POST["update_display_options"])) {
    $action = update_option('yinstagram_display_options', $_POST['ydo']);
    if ($action) $message = array('class' => 'updated', 'msg' => 'Display options changed.');
  }
  
  $data = yinstagram_get_options('display_options');
  
  // message
  if (isset($_COOKIE['yinstagram_response'])) $message = maybe_unserialize(stripslashes($_COOKIE['yinstagram_response']));
  
  include dirname(__FILE__) . '/page-display-options.php';
}

function yinstagram_encodeURIComponent($str) {
  $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
  return strtr(rawurlencode($str), $revert);
}

function yinstagram_help_tab() {
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
  $output .= '<li>Go to <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a> then login.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-1.png"/></li>';
  $output .= '<li>Click Manage Clients menu, or Register Your Application button.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-2.png"/></li>';
  $output .= '<li>Register a New Client.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-3.png"/></li>';
  $output .= '<li>Setup Register new Client ID form.<br>';
  $output .= 'a. Fill textboxes, textarea, and checkboxes with your suitable information, and preferences.<br>';
  $output .= 'b. Fill OAuth redirect_uri textbox with <code>' . admin_url('admin.php?page=yinstagram/settings.php') . '</code><br>';
  $output .= '<img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-4.png"/></li>';
  $output .= '<li>Congratulation, now you have Client ID and Client Secret.<br><img src="' . YINSTAGRAM_PLUGIN_URL . '/img/manual-5.png"/></li>';
  $output .= '</ol>';

  return $output;
}

function yinstagram_section_shortcode() {
  $output = '<h1>Shortcode</h1>';
  $output .= '<p><strong>Examples</strong></p>';
  $output .= '<ul class="sc_examples">';
  $output .= '<li><code>[yinstagram]</code></li>';
  $output .= '<li><code>[yinstagram display_images="liked"]</code></li>';
  $output .= '<li><code>[yinstagram username="motogp"]</code></li>';
  $output .= '<li><code>[yinstagram hashtags="#supercar, #hypercar"]</code></li>';
  $output .= '</ul>';
  $output .= '<p><strong>Attributes</strong></p>';
  $output .= '<table class="sc_key"><tbody>';
  $output .= '<tr><td style="vertical-align: top;" colspan="3">No attribute will retrieves Instagram images based on plugin settings, and if have attribute will override plugin settings.</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">display_images</td><td style="vertical-align: top;">=</td><td>Get the authenticated user\'s <span>"feed"</span>, list of media they\'ve <span>"liked"</span>, or get the most <span>"recent"</span> media published by a user.</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">username</td><td style="vertical-align: top;">=</td><td>Get the most recent images published by a username, e.g. <span>"motogp"</td></tr>';
  $output .= '<tr><td style="vertical-align: top;">hashtags</td><td style="vertical-align: top;">=</td><td>Get a list of recently tagged media, e.g. <span>"#supercar, #hypercar"</span></td></tr>';
  $output .= '<tbody></table>';
  
  return $output;
}

function yinstagram_get_user_info($yinstagram_options) {
  $output = null;
  
  $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/' . $yinstagram_options['user']->id . '/?access_token=' . $yinstagram_options['access_token']);
  $responses = json_decode($responses);
  
  if ( $responses->meta->code == 200 )
    $output = $responses->data;
  
  return $output;
}

function yinstagram_get_relationships($yinstagram_options) {
  $output = null;
  
  //  Get the list of users this user is followed by. 
  $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/' . $yinstagram_options['user']->id . '/followed-by?access_token=' . $yinstagram_options['access_token']);
  
  $responses = json_decode($responses);
  
  if ( $responses->meta->code == 200 )
    $output = $responses;
  
  return $output;
}

function yinstagram_get_qtip_content($datum) {
  $output = '<p>'. $datum->caption->text . '</p>';
  $output .= '<a href="' . $datum->link . '" target="_blank">' . $datum->link . '</a>';
  
  return $output;
}

function yinstagram_get_user_id($access_token, $username) {
  $id = null;
  
  if ($username != 'self') {
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/search?q=' . $username . '&access_token=' . $access_token);
    
    $responses = json_decode($responses);
    
    if ( $responses->meta->code == 200 ) {
      if ( ! empty($responses->data) ) $id = $responses->data[0]->id;
    }
  }
  
  return $id;
}

if (!get_option( 'yinstagram_ignore_notice' )) add_action('admin_notices', 'yinstagram_admin_notice');
function yinstagram_admin_notice() {
  $url = basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING'];
  
  if ( ($url == 'admin.php?page=yinstagram/settings.php') || ($url == 'admin.php?page=yinstagram/display-options.php') ) {
    ?>
      <div class="updated yinstagram-notice">
        <p><a id="yinstagram-dismiss" href="#">Close</a></p>
        <p>Since 0.0.90, OAuth redirect_uri or REDIRECT URI changed to <code><?php echo admin_url('admin.php?page=yinstagram/settings.php') ?></code>. You can change your OAuth redirect_uri at <a href="http://instagram.com/developer/clients/manage/" target="_blank">instagram.com/developer/clients/manage/</a>. Thank you.</p>
      </div>
    <?php
  }
}

add_action('wp_ajax_yinstagram_dismiss', 'yinstagram_dismiss_callback');
function yinstagram_dismiss_callback() {
  update_option('yinstagram_ignore_notice', 1);
  die();
}

add_action('wp_ajax_yinstagram_logout', 'yinstagram_logout_callback');
function yinstagram_logout_callback() {
  $action = update_option('yinstagram_access_token', null);
  if ($action) {
    $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => 'Disconnected.'));
    setcookie('yinstagram_response', $message, time()+1, '/');
    echo admin_url('admin.php?page=yinstagram/settings.php');
  }
  die();
}

add_action('wp_ajax_yinstagram_restore_settings', 'yinstagram_restore_settings_callback');
function yinstagram_restore_settings_callback() {
  update_option('yinstagram_access_token', null);
  $action = update_option('yinstagram_settings', null);
  if ($action) {
    $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => 'Settings restored to default settings.'));
    setcookie('yinstagram_response', $message, time()+1, '/');
    echo admin_url('admin.php?page=yinstagram/settings.php');
  }
  die();
}

add_action('wp_ajax_yinstagram_restore_display_options', 'yinstagram_restore_display_options_callback');
function yinstagram_restore_display_options_callback() {
  $action = update_option('yinstagram_display_options', null);
  if ($action) {
    $message = maybe_serialize(array('cookie' => 1, 'class' => 'updated', 'msg' => 'Display options restored to default settings.'));
    setcookie('yinstagram_response', $message, time()+1, '/');
    echo admin_url('admin.php?page=yinstagram/display-options.php');
  }
  die();
}

function yinstagram_access_token($code) {
  $data = yinstagram_get_options('settings');
  
  // Get cURL resource
  $curl = curl_init();
  // Set some options - we are passing in a useragent too here
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
    CURLOPT_USERAGENT => 'Yakadanda Instagram Access Token Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
      'client_id' => $data['client_id'],
      'client_secret' => $data['client_secret'],
      'grant_type' => 'authorization_code',
      'redirect_uri' => admin_url('admin.php?page=yinstagram/settings.php'),
      'code' => $code
    )
  ));
  // Send the request & save response to $resp
  $resp = curl_exec($curl);
  // Close request to clear up some resources
  curl_close($curl);
  
  return $resp;
}

function yinstagram_fetch_data($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
