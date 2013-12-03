<?php
add_action('widgets_init', create_function('', 'register_widget( "yinstagram_widget" );'));
class YInstagram_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(
            'yinstagram', 'Yakadanda Instagram', array('description' => __('Yakadanda Instagram Widget', 'text_domain'))
    );
  }

  public function widget($args, $instance) {
    if ($instance['colorbox']) {
      YInstagram_Widget::colorbox_enqueue_style($instance);
      add_action('wp_enqueue_scripts', 'YInstagram_Widget::colorbox_enqueue_style');
    }
    
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);
    
    // set default
    $instance['type'] = isset($instance['type']) ? $instance['type'] : 'images';
    $instance['display_images'] = isset($instance['display_images']) ? $instance['display_images'] : 'recent';
    $instance['custom_size'] = isset($instance['custom_size']) ? $instance['custom_size'] : null;
    $instance['limit'] = isset($instance['limit']) ? $instance['limit'] : '6';
    
    $style = null;
    if ($instance['custom_size'])
      $style = 'style="width:' . $instance['custom_size'] . 'px; height:' . $instance['custom_size'] . 'px"';
    
    echo $before_widget;
    
    if (!empty($title)) echo $before_title . $title . $after_title;
    
    $auth = get_option('yinstagram_access_token');
    if (isset($auth['access_token']) && isset($auth['user'])) {
      
      $data = null;
      if ( $instance['display_images'] == 'tags') {
        $data = yinstagram_get_tags_images($auth, $instance['hashtags'], null, false);
      } else {
        $data = yinstagram_get_own_images($auth, $instance['display_images'], null, false);
      }
      
      if ( $instance['type'] == 'profile' ) {
        /*begin profile type*/
        $u_info = yinstagram_get_user_info($auth);
        
        if ( $u_info && !empty($data) ) {
          echo '<div class="yinstagram_profile">';
          
          $i = $j = 0;
          echo '<div class="header"><ul class="images">';
          foreach ($data as $datum) {
            $i++; $j++;
            
            echo ($i == 1) ? '<li>' : null;
            
            echo '<img src="' . $datum->images->thumbnail->url . '"/>';
            
            echo ($i == 4) ? '</li>' : null;
            $i = ($i == 4) ? 0 : $i;
            
            if ($j == 12) break;
          }
          
          echo ( ($j != 4) || ($j != 8) || ($j != 12)) ? '</li>' : null;
          echo '</ul><img class="icon" alt="instagram-icon" src="' . YINSTAGRAM_PLUGIN_URL . '/img/instagram-icon-32x32.png"></div>';
          
          echo '<div class="info"><img class="circular" title="' . $u_info->full_name . '" alt="' . $u_info->username . '" src="' . $u_info->profile_picture . '">';
          echo '<p class="fullname" title="' . $u_info->username . '"><a href="http://instagram.com/' . $u_info->username . '" target="_blank">' . $u_info->full_name . '</a></p>';
          if ( $u_info->website ) echo '<p class="website"><a href="' . $u_info->website . '" target="_blank">' . preg_replace('#^https?://#', '', $u_info->website) . '</a></p>';
          if ( $u_info->bio ) echo '<p class="bio">' . $u_info->bio . '</p>';

          echo '<ul class="counts"><li>Posts: ' . $u_info->counts->media . '</li>';
          echo '<li>Followers: ' . $u_info->counts->followed_by . '</li>';
          echo '<li>Following: ' . $u_info->counts->follows . '</li></ul>';
          echo '</div></div>';
          
        } else {
          echo '<p>Request timed out.</p>';
        }
        /*end of profile type*/
      } else {
        /*begin images type*/
        if (!empty($data)) {
          $i = 0;
          if ($instance['colorbox']) {
            echo '<style type="text/css">';
            echo '.yinstagram_grid li a:hover img {';
            echo 'opacity:0.5; filter:alpha(opacity=50);';
            echo '}';
            echo '</style>';
          }
          
          echo '<input id="yinstagram-widget-settings" name="yinstagram-widget-settings" type="hidden" value="' . htmlentities( json_encode( array( 'colorbox_status' => $instance['colorbox'], 'colorbox_effect' => $instance['effect'], 'dimensions' => $instance['custom_size'] ) ) ) . '">';
          
          echo '<ul class="yinstagram_grid">';
          
          foreach ($data as $datum) {
            $img_src = $datum->images->thumbnail->url;
            if ($instance['size'] == 'low_resolution')
              $img_src = $datum->images->low_resolution->url;
            elseif ($instance['size'] == 'standard_resolution')
              $img_src = $datum->images->standard_resolution->url;

            $images[] = array('id' => $datum->id, 'src' => $img_src);

            echo '<li>';

            if ($instance['colorbox']) echo '<a class="yinstagram-cbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '" title="' . yinstagram_get_excerpt(str_replace('"', "'", (string) $datum->caption->text)) . '">';
            else echo '<a target="_blank" href="' . $datum->images->standard_resolution->url . '" title="' . yinstagram_get_excerpt(str_replace('"', "'", (string) $datum->caption->text)) . '">';

            echo '<span class="load_w-' . $datum->id . '" ' . $style . '></span>';
            echo '</a></li>';

            $i++;
            if ($i == $instance['limit'])
              break;
          }
          echo '</ul>';
          
          echo '<textarea id="yinstagram-widget-images" name="yinstagram-widget-images" style="display: none;">' . json_encode($images) . '</textarea>';
          
        } else {
          echo '<p>Request timed out, or no have ' . $instance['display_images'] . ' images.</p>';
        }
        /*end of images type*/
      }
      
    } else {
      echo '<p>Not Connected.</p>';
    }

    echo $after_widget;
  }

  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['type'] = strip_tags($new_instance['type']);
    $instance['display_images'] = strip_tags($new_instance['display_images']);
    $instance['hashtags'] = strip_tags($new_instance['hashtags']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['custom_size'] = strip_tags($new_instance['custom_size']);
    $instance['limit'] = strip_tags($new_instance['limit']);
    $instance['colorbox'] = strip_tags($new_instance['colorbox']);
    $instance['theme'] = strip_tags($new_instance['theme']);
    $instance['effect'] = strip_tags($new_instance['effect']);

    return $instance;
  }

  public function form($instance) {
    $title = __(null, 'text_domain');
    if (isset($instance['title'])) $title = $instance['title'];
    $type = 'images';
    if (isset($instance['type'])) $type = $instance['type'];
    $display_images = 'recent';
    if (isset($instance['display_images'])) $display_images = $instance['display_images'];
    $hashtags = null;
    if (isset($instance['hashtags'])) $hashtags = $instance['hashtags'];
    $size = 'thumbnail';
    if (isset($instance['size'])) $size = $instance['size'];
    $custom_size = null;
    if (isset($instance['custom_size'])) $custom_size = $instance['custom_size'];
    $limit = 6;
    if (isset($instance['limit'])) $limit = $instance['limit'];
    $colorbox = null;
    if (isset($instance['colorbox'])) $colorbox = $instance['colorbox'];
    $theme = null;
    if (isset($instance['theme'])) $theme = $instance['theme'];
    $effect = null;
    if (isset($instance['effect'])) $effect = $instance['effect'];
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" class="yinstagram-type">
        <option value="images" <?php echo ($type == 'images') ? 'selected="selected"' : null; ?>>Images&nbsp;</option>
        <option value="profile" <?php echo ($type == 'profile') ? 'selected="selected"' : null; ?>>Profile&nbsp;</option>
      </select>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('display_images'); ?>"><?php _e('Display Images:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('display_images'); ?>" name="<?php echo $this->get_field_name('display_images'); ?>" class="yinstagram-display-images">
        <option value="recent" <?php echo ($display_images == 'recent') ? 'selected="selected"' : null; ?>>Recent&nbsp;</option>
        <option value="feed" <?php echo ($display_images == 'feed') ? 'selected="selected"' : null; ?>>Feed&nbsp;</option>
        <option value="liked" <?php echo ($display_images == 'liked') ? 'selected="selected"' : null; ?>>Liked&nbsp;</option>
        <option value="tags" <?php echo ($display_images == 'tags') ? 'selected="selected"' : null; ?>>Tags&nbsp;</option>
      </select>
    </p>
    <p id="<?php echo $this->get_field_id('hashtags-container'); ?>" <?php echo ($display_images != 'tags') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('hashtags'); ?>"><?php _e('Hashtags (separated by comma):'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('hashtags'); ?>" name="<?php echo $this->get_field_name('hashtags'); ?>" type="text" value="<?php echo esc_attr($hashtags); ?>" placeholder="e.g. #art, #buildings, #graffiti etc."/>
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Image Size:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
        <option value="thumbnail" <?php echo ($size == 'thumbnail') ? 'selected="selected"' : null; ?>>Thumbnail&nbsp;</option>
        <option value="low_resolution" <?php echo ($size == 'low_resolution') ? 'selected="selected"' : null; ?>>Low Resolution&nbsp;</option>
        <option value="standard_resolution" <?php echo ($size == 'standard_resolution') ? 'selected="selected"' : null; ?>>Standard Resolution&nbsp;</option>
      </select>
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('custom_size'); ?>"><?php _e('Custom Image Size (pixel):'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('custom_size'); ?>" name="<?php echo $this->get_field_name('custom_size'); ?>" type="text" value="<?php echo esc_attr($custom_size); ?>" />
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit (max 33):'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <input id="<?php echo $this->get_field_id('colorbox'); ?>" name="<?php echo $this->get_field_name('colorbox'); ?>" type="checkbox" <?php echo empty($colorbox) ? null : 'checked'; ?> class="yinstagram-colorbox"/>
      <label for="<?php echo $this->get_field_id('colorbox'); ?>"><?php _e('Colorbox'); ?></label>
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>" <?php echo empty($colorbox) ? 'disabled' : null; ?>>
        <option value="1" <?php echo ($theme == '1') ? 'selected="selected"' : null; ?>>1&nbsp;</option>
        <option value="2" <?php echo ($theme == '2') ? 'selected="selected"' : null; ?>>2&nbsp;</option>
        <option value="3" <?php echo ($theme == '3') ? 'selected="selected"' : null; ?>>3&nbsp;</option>
        <option value="4" <?php echo ($theme == '4') ? 'selected="selected"' : null; ?>>4&nbsp;</option>
        <option value="5" <?php echo ($theme == '5') ? 'selected="selected"' : null; ?>>5&nbsp;</option>
      </select>
    </p>
    <p class="<?php echo $this->get_field_id('type-container'); ?>" <?php echo ($type == 'profile') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('effect'); ?>"><?php _e('Effect:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('effect'); ?>" name="<?php echo $this->get_field_name('effect'); ?>" <?php echo empty($colorbox) ? 'disabled' : null; ?>>
        <option value="elastic" <?php echo ($effect == 'elastic') ? 'selected="selected"' : null; ?>>Elastic&nbsp;</option>
        <option value="fade" <?php echo ($effect == 'fade') ? 'selected="selected"' : null; ?>>Fade&nbsp;</option>
        <option value="slideshow" <?php echo ($effect == 'slideshow') ? 'selected="selected"' : null; ?>>Slideshow&nbsp;</option>
      </select>
    </p>
    <?php
  }

  function colorbox_enqueue_style($instance) {
    wp_register_style('yinstagram-colorbox', YINSTAGRAM_PLUGIN_URL . '/css/colorbox-' . $instance['theme'] . '.css', false, '1.4.15', 'all');
    wp_enqueue_style('yinstagram-colorbox');
  }
}

function yinstagram_get_excerpt($str, $startPos = 0, $maxLength = 30) {
  $str = preg_replace("#(.*)<iframe(.*?)</iframe>(.*)#", '', $str);
  if (strlen($str) > $maxLength) {
    $excerpt = substr($str, $startPos, $maxLength - 3);
    $lastSpace = strrpos($excerpt, ' ');
    $excerpt = substr($excerpt, 0, $lastSpace);
    $excerpt .= ' ...';
  } else {
    $excerpt = $str;
  }

  return $excerpt;
}
