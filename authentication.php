<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

global $wpdb;

if (isset($_GET['code'])) {
  
  list( $client_id, $client_secret, $display_your_images, $display_the_following_hashtags ) = YISettings::data();
  
	$response = wp_remote_post("https://api.instagram.com/oauth/access_token",
		array(
			'body' => array(
				'code' => $_GET['code'],
				'response_type' => 'authorization_code',
				'redirect_uri' => YAKADANDA_INSTAGRAM_PLUGIN_URL . '/authentication.php',
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'grant_type' => 'authorization_code',
			),
			'sslverify' => apply_filters('https_local_ssl_verify', false)
		)
	);

	$access_token = null;
	$username = null;
	$image = null;

	$success = false;
	$errormessage = null;
	$errortype = null;
  
  $authentication = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-authentication'");

	if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
		$auth = json_decode($response['body']);
    
		if(isset($auth->access_token)) {
			$access_token = $auth->access_token;
			$user = $auth->user;
      
      $post_content = array(
        'access_token' => $access_token,
        'username' => $user->username,
        'picture' => $user->profile_picture,
        'fullname' => $user->full_name
      );
      
      if ( $authentication ) {
        $update_post = array(
          'ID' => $authentication->ID,
          'post_content' => maybe_serialize($post_content)
        );
        
        wp_update_post( $update_post );
      } else {
        $args = array(
          'post_title'    => 'yakadanda-instagram-authentication',
          'post_content'  => maybe_serialize($post_content),
          'post_status'   => 'publish',
          'post_type'     => 'yakadanda-instagram',
          'post_author'   => $current_user->ID,
        );

        wp_insert_post( $args );
      }
      
			$success = true;
    }
  } elseif ( $response['response']['code'] >= 400 ) {
		$error = json_decode($response['body']);
		$errormessage = $error->error_message;
		$errortype = $error->error_type;
  }

	if (!$access_token) {
    //wp_delete_post( $post->ID, true );
    if ( $authentication ) {
      wp_delete_post( $authentication->ID, true );
    }
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
<?php if ($success): ?>
	<script type="text/javascript">
		opener.location.reload(true);
    self.close();
	</script>
<?php else: ?>
	<h1>An error occured</h1>
	<p>
		Type: <?php echo $errortype; ?>
		<br>
		Message: <?php echo $errormessage; ?>
	</p>
	<p>Please make sure you entered the right client details</p>
<?php endif; ?>
</body>
</html>
