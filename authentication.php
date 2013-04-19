<?php
include_once dirname( __FILE__ ) . "/../../../wp-load.php";

global $wpdb;

if (isset($_GET['code'])) {
  $data = get_option('yinstagram_settings');
  
	$response = (array)wp_remote_post("https://api.instagram.com/oauth/access_token",
		array(
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
  
	$access_token = null;
  
	$success = false;
	$errormessage = null;
	$errortype = null;
  
	if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
		$auth = json_decode($response['body']);
    
		if( isset($auth->access_token) ) {
			$access_token = $auth->access_token;
			$user = $auth->user;
      
      $value = array(
        'access_token' => $access_token,
        'username' => $user->username,
        'bio' => $user->bio,
        'website' =>$user->website,
        'profile_picture' => $user->profile_picture,
        'full_name' => $user->full_name,
        'id' => $user->id
      );
      
      $option = 'yinstagram_access_token';
      update_option( $option, $value );
      
			wp_redirect( admin_url( 'admin.php?page=yinstagram/settings.php' ) ); exit;
    }
  } elseif ( $response['response']['code'] >= 400 ) {
		$error = json_decode($response['body']);
		$errormessage = $error->error_message;
		$errortype = $error->error_type;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body, html {
			font-family: arial, sans-serif;
			padding: 30px;
			text-align: center;
		}
	</style>
</head>
<body>
	<h1>An error occured</h1>
	<p>
		Type: <?php echo $errortype; ?>
		<br>
		Message: <?php echo $errormessage; ?>
	</p>
	<p>Please make sure you entered the right client details</p>
</body>
</html>
