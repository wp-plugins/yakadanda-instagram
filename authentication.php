<?php
include_once dirname(__FILE__) . "/../../../wp-load.php";

$message = 'Failed';

if (isset($_GET['code'])) {
  $data = get_option('yinstagram_settings');

  $response = (array) wp_remote_post("https://api.instagram.com/oauth/access_token", array(
              'body' => array(
                  'code' => $_GET['code'],
                  'response_type' => 'authorization_code',
                  'redirect_uri' => YINSTAGRAM_PLUGIN_URL . '/authentication.php',
                  'client_id' => $data['client_id'],
                  'client_secret' => $data['client_secret'],
                  'grant_type' => 'authorization_code',
              ),
              'sslverify' => apply_filters('https_local_ssl_verify', false)
                  )
  );

  if (!is_wp_error($response) && isset($response['headers'])) {
    if ($response['response']['code'] == '200') {
      $value = (array) json_decode($response['body']);

      update_option('yinstagram_access_token', $value);

      wp_redirect(admin_url('admin.php?page=yinstagram/settings.php')); exit;
    } else {
      $body = json_decode($response['body']);
      $message = $body->error_message;
    }
  } else {
    $message = $response['errors']['http_request_failed'][0];
  }
}

wp_redirect(admin_url('admin.php?page=yinstagram/settings.php&msg=' . yinstagram_encodeURIComponent($message))); exit;
