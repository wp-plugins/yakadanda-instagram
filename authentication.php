<?php
include_once dirname(__FILE__) . "/../../../wp-load.php";

$message = 'Failed';

if (isset($_GET['code'])) {
  $data = get_option('yinstagram_settings');
  
  $response = (array) wp_remote_post("https://api.instagram.com/oauth/access_token", array(
      'method' => 'POST',
      'timeout' => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking' => true,
      'headers' => array(),
      'body' => array(
        'client_id' => $data['client_id'],
        'client_secret' => $data['client_secret'],
        'grant_type' => 'authorization_code',
        'redirect_uri' => YINSTAGRAM_PLUGIN_URL . '/authentication.php',
        'code' => $_GET['code'],
        'response_type' => 'authorization_code'
      ),
      'cookies' => array(),
      'sslverify' => apply_filters('https_local_ssl_verify', false)
    )
  );
  
  $message = null;
  
  if (!is_wp_error($response) && isset($response['headers'])) {
    if ($response['response']['code'] == '200') {
      $value = (array) json_decode($response['body']);
      update_option('yinstagram_access_token', $value);
      $message = maybe_serialize(array('class' => 'updated', 'msg' => 'Connection to Instagram succeeded.'));
    } else {
      $body = json_decode($response['body']);
      $message = maybe_serialize(array('class' => 'error', 'msg' => $body->error_message));
    }
  } else {
    $message = maybe_serialize(array('class' => 'error', 'msg' => $response['errors']['http_request_failed'][0]));
  }
}

if ($message) setcookie('yinstagram_response', $message, time()+3, '/');

wp_redirect(admin_url('admin.php?page=yinstagram/settings.php')); exit;
