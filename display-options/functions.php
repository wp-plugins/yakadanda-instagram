<?php
class YIDisplayOptions {
  function register_menu_page() {
    // Call scripts in admin
    add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_scripts');
    // Call styles in admin
    add_action( 'admin_enqueue_scripts', 'yinstagram_admin_enqueue_styles' );
    
    add_submenu_page( 'settings/admin-page.php', 'Display Options', 'Display Options', 'manage_options', 'display-options/admin-page.php', 'YIDisplayOptions::admin_page' );
  }
  
  function admin_page() {
    global $wpdb;
    
    if ( $_REQUEST['action'] == 'create' ) $response = self::create();
    elseif ( $_REQUEST['action'] == 'update' ) $response = self::update();
    
    $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-display-options'");
    
    $data = null;
    $action = 'create';
    if ($post) {
      $id = array( 'ID' => $post->ID );
      $post_content = maybe_unserialize($post->post_content);
      $data = array_merge((array)$id, (array)$post_content);
      $action = 'update';
    }
    
    include dirname(__FILE__) . '/admin-page.php';
  }
  
  function create() {
    global $current_user;
    
    if ($_POST['header_menu_color'] && $_POST['background_color']) {
      
      $upload = null;
      
      if ($_FILES["logo_display_file"]["name"]) {
        list($image_rules, $response) = self::image_rules($_FILES["logo_display_file"]["tmp_name"]);
        if ($image_rules == false) $output = $response;
        
        if ($image_rules) {
          $upload = wp_upload_bits($_FILES["logo_display_file"]["name"], null, file_get_contents($_FILES["logo_display_file"]["tmp_name"]));
          if ( $upload['error'] ) {
            $output = array( 'class' => 'error' , 'message' => $upload['error'] );
          }
        }
      } else {
        $output = array('class' => 'updated' , 'message' => 'Display Options saved.' );
      }
      
      $post_content = array (
        'logo_display_file' => ($upload['url']) ? $upload['url'] : null,
        'header_menu_color' => ($_POST['header_menu_color']) ? $_POST['header_menu_color'] : '#000000',
        'background_color' => ($_POST['background_color']) ? $_POST['background_color'] : '#000000',
        'resolution' => ($_POST['resolution']) ? $_POST['resolution'] : 'thumbnail',
        'height' => ($_POST['height']) ? $_POST['height'] : '300',
        'direction' => ($_POST['direction']) ? $_POST['direction'] : 'up',
        'speed' => ($_POST['speed']) ? $_POST['speed'] : '10000',
        'display_social_links' => ($_POST['display_social_links']) ? $_POST['display_social_links'] : '0'
      );

      $args = array(
        'post_title' => 'yakadanda-instagram-display-options',
        'post_content' => maybe_serialize($post_content),
        'post_status' => 'publish',
        'post_type' => 'yakadanda-instagram',
        'post_author' => $current_user->ID,
      );
      wp_insert_post($args);
      
      return $output;
    }
  }
  
  function update() {
    $postid = $_POST['id'];
    $post = get_post($postid);
    $data = maybe_unserialize($post->post_content);
    
    $update_post_content['logo_display_file'] = $data['logo_display_file'];
    
    if ( $_FILES["logo_display_file"]["name"] ) {
      list($image_rules, $response) = self::image_rules($_FILES["logo_display_file"]["tmp_name"]);
      if ($image_rules == false) $upload_error = $response;
      
      if ($image_rules) {
        $upload = wp_upload_bits($_FILES["logo_display_file"]["name"], null, file_get_contents($_FILES["logo_display_file"]["tmp_name"]));
        $upload_error = null;
        if ( $upload['error'] ) {
          $upload_error = array( 'class' => 'error' , 'message' => $upload['error'] );
        } else {
          self::delete_file($postid);
          $update_post_content['logo_display_file'] = $upload['url'];
        }
      }
    }
    
    if ($postid) {
      $post_content = array(
        'logo_display_file' => $update_post_content['logo_display_file'],
        'header_menu_color' => ($_POST['header_menu_color']) ? $_POST['header_menu_color'] : '#000000',
        'background_color' => ($_POST['background_color']) ? $_POST['background_color'] : '#000000',
        'resolution' => ($_POST['resolution']) ? $_POST['resolution'] : 'thumbnail',
        'height' => ($_POST['height']) ? $_POST['height'] : '300',
        'direction' => ($_POST['direction']) ? $_POST['direction'] : 'up',
        'speed' => ($_POST['speed']) ? $_POST['speed'] : '10000',
        'display_social_links' => ($_POST['display_social_links']) ? $_POST['display_social_links'] : '0'
      );
      $update_post = array(
        'ID' => $postid,
        'post_content' => maybe_serialize($post_content)
      );
      wp_update_post( $update_post );
      $output = ($upload_error) ? $upload_error : array( 'class' => 'updated' , 'message' => 'Display Options updated.' );
    }
    return $output;
  }
  
  function delete_file($postid) {
    $id = $postid;
    $post = get_post($id);
    
    $post_content = maybe_unserialize($post->post_content);
    
    $filename = explode( network_home_url(), $post_content['logo_display_file'] );
    $filename = ABSPATH . $filename[1];
    if (is_file($filename) == TRUE) {
      chmod($filename, 0666);
      unlink($filename);
    }
  }
  
  function image_rules($filename) {
    //Type of Image
    //1 = GIF 	5 = PSD 	9 = JPC 	13 = SWC
    //2 = JPG 	6 = BMP 	10 = JP2 	14 = IFF
    //3 = PNG 	7 = TIFF(intel byte order) 	11 = JPX 	15 = WBMP
    //4 = SWF 	8 = TIFF(motorola byte order) 	12 = JB2 	16 = XBM
    $types = array('1', '2', '3', '6');
    
    list($width, $height, $type, $attr) = getimagesize($filename);
    
    $output = array( false, array( 'class' => 'error' , 'message' => 'Upload logo 100x100 maximum, or invalid file type.' ) );
    if ( ($width <= 100) && ($height <= 100) && in_array($type, $types) ) {
      $output = array( true, null );
    }
    return $output;
  }
  
  function data() {
    global $wpdb;
    
    $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE `post_title` = 'yakadanda-instagram-display-options'");
    
    $output = array( null, '#000000', '#000000', 'thumbnail', 'up', 10000 );
    
    if ($post) {
      $data = maybe_unserialize($post->post_content);
      $output = array( $data['logo_display_file'], $data['header_menu_color'], $data['background_color'], $data['thumbnail'], $data['height'], $data['direction'], $data['speed'], $data['display_social_links'] );
    }
    
    return $output;
  }
  
}
