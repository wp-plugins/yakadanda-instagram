<?php
include_once dirname(__FILE__) . "/../../../../wp-load.php";

$message = null;

if (isset($_GET['logout'])) {
  $option = 'yinstagram_access_token';
  $value = null;
  update_option($option, $value);
} elseif (($_POST['client_id'] != null) && ($_POST['client_secret'] != null)) {
  $option = 'yinstagram_settings';
  $data = array_merge( (array) get_option($option), (array) get_option('yinstagram_access_token') );
  
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
  
  update_option($option, $value);
  
  $message = maybe_serialize(array('class' => 'updated', 'msg' => 'Settings updated.'));
  
  if (($data['client_id'] != $_POST['client_id']) || ($data['client_secret'] != $_POST['client_secret']) || !isset($data['access_token']) || !isset($data['user']) ) {
    // make null the token from database
    $option = 'yinstagram_access_token';
    $value = null;
    update_option($option, $value);
    
    $encodeURIComponent = yinstagram_encodeURIComponent(YINSTAGRAM_PLUGIN_URL . '/authentication.php');
    $url = 'https://api.instagram.com/oauth/authorize/?client_id=' . $_POST['client_id'] . '&redirect_uri=' . $encodeURIComponent . '&response_type=code';
    
    wp_redirect($url); exit;
  }
}

if ($message) setcookie('yinstagram_response', $message, time()+3, '/');

wp_redirect(admin_url('admin.php?page=yinstagram/settings.php')); exit;
