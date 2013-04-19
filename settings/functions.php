<?php
class YISettings {
  function register_menu_page() {
    // Call scripts in admin
    add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
    // Call styles in admin
    add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_styles' );
  
    add_menu_page( 'Settings', 'YInstagram', 'add_users', 'settings/admin-page.php', 'YISettings::admin_page', network_home_url( 'wp-content/plugins/yakadanda-instagram/img/instagram-icon.png' ), 205 );
    add_submenu_page( 'settings/admin-page.php', 'Settings', 'Settings', 'manage_options', 'settings/admin-page.php', 'YISettings::admin_page' );
  }
  
  function admin_page() {
    global $wpdb;
    
    if ( $_REQUEST['action'] == 'create' ) $response = self::create();
    elseif ( $_REQUEST['action'] == 'update' ) $response = self::update();
    
    $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-settings'");
    
    $data = null;
    $action = 'create';
    if ($post) {
      $id = array( 'ID' => $post->ID );
      $post_content = maybe_unserialize($post->post_content);
      list( $access_token, $username, $picture, $fullname ) = YISettings::authentication_data();
      $authentication = array(
        'access_token' => $access_token,
        'username' => $username,
        'picture' => $picture,
        'fullname' => $fullname  
      );
      $data = array_merge((array)$id, (array)$post_content, (array)$authentication);
      $action = 'update';
    }
    
    include dirname(__FILE__) . '/admin-page.php';
  }
  
  function create() {
    global $current_user;
    if ($_POST['client_id'] && $_POST['client_secret']) {
      $post_content = array(
        'client_id' => $_POST['client_id'],
        'client_secret' => $_POST['client_secret'],
        'display_your_images' => $_POST['display_your_images'],
        'display_the_following_hashtags' => ($_POST['option_display_the_following_hashtags'] && $_POST['display_the_following_hashtags']) ? $_POST['display_the_following_hashtags'] : null
      );

      $args = array(
        'post_title'    => 'yakadanda-instagram-settings',
        'post_content'  => maybe_serialize($post_content),
        'post_status'   => 'publish',
        'post_type'     => 'yakadanda-instagram',
        'post_author'   => $current_user->ID,
      );
      
      wp_insert_post( $args );
      
      ?>   
      <script type = "text/javascript">
        var url = 'https://api.instagram.com/oauth/authorize/'
          + '?redirect_uri=' + encodeURIComponent("<?php echo YAKADANDA_INSTAGRAM_PLUGIN_URL . '/authentication.php'; ?>")
          + '&response_type=code'
          + '&client_id=<?php echo $_POST['client_id'];  ?>'
          + '&display=touch';
        window.open(url, 'wp-instagram-authentication-' + Math.random(), 'height=500,width=600');
      </script>
      <?php
      
      $output = array( 'class' => 'updated' , 'message' => 'Settings saved.' );
      return $output;
    }
  }
  
  function update() {
    global $wpdb;
    
    $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-settings'");
    $post_content = maybe_unserialize($post->post_content);
    
    $authentication = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-authentication'");
    
    if ( ( $_POST['client_id'] != $post_content['client_id'] ) || ( $_POST['client_secret'] != $post_content['client_secret'] ) || ! isset( $authentication ) ) {
      ?>
      <script type = "text/javascript">
        var url = 'https://api.instagram.com/oauth/authorize/'
          + '?redirect_uri=' + encodeURIComponent("<?php echo YAKADANDA_INSTAGRAM_PLUGIN_URL . '/authentication.php'; ?>")
          + '&response_type=code'
          + '&client_id=<?php echo $_POST['client_id'];  ?>'
          + '&display=touch';
        window.open(url, 'wp-instagram-authentication-' + Math.random(), 'height=500,width=600');
      </script>
      <?php
    }
    
    $post_content = array(
      'client_id' => ($_POST['client_id'] == $post_content['client_id']) ? $post_content['client_id'] : $_POST['client_id'],
      'client_secret' => ($_POST['client_secret'] == $post_content['client_secret']) ? $post_content['client_secret'] : $_POST['client_secret'],
      'display_your_images' => $_POST['display_your_images'],
      'display_the_following_hashtags' => ($_POST['option_display_the_following_hashtags'] && $_POST['display_the_following_hashtags']) ? $_POST['display_the_following_hashtags'] : null
    );
    $update_post = array(
      'ID' => $_POST['id'],
      'post_content' => maybe_serialize($post_content)
    );
    if ($_POST['client_id'] && $_POST['client_secret']) {
      wp_update_post( $update_post );
      $output = array( 'class' => 'updated' , 'message' => 'Settings updated.' );
      return $output;
    }
  }
  
  function data() {
    global $wpdb;
    
    $post = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-settings'" );
    $data = maybe_unserialize( $post->post_content );
    
    $display_your_images = false;
    if ($data['display_your_images']) $display_your_images = true;
    
    $display_the_following_hashtags = false;
    if ( $data['display_the_following_hashtags'] ) {
      $hastags = explode( ',', $data['display_the_following_hashtags'] );

      $display_the_following_hashtags = array();
      foreach ( $hastags as $hastag ) {
        $display_the_following_hashtags[] = trim( trim( $hastag, " " ), "#" );
      }
    }
    
    $output = array( $data['client_id'], $data['client_secret'], $display_your_images, $display_the_following_hashtags );
    
    return $output;
  }
  
  function authentication_data() {
    global $wpdb;
    
    $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-authentication'");
    $data = maybe_unserialize($post->post_content);
    
    $output = array( $data['access_token'], $data['username'], $data['picture'], $data['fullname'] );
    
    return $output;
  }
  
  function encode($string) {
    $output = base64_encode($string . AUTH_KEY);
    return $output;
  }
  
  function decode($string) {
    $output = base64_decode($string);
    $output = explode(AUTH_KEY, $output);
    $output = $output[0];
    return $output;
  }
  
}
