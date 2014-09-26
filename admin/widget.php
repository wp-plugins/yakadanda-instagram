<?php
add_action('widgets_init', create_function('', 'register_widget( "yinstagram_widget" );'));
class YInstagram_Widget extends WP_Widget {
  public function __construct() {
    parent::__construct(
            'yinstagram', 'Yakadanda Instagram', array('description' => __('Yakadanda Instagram Widget', 'text_domain'))
    );
  }
  
  public function widget($args, $instance) {
    global $yinstagram_options;

    // Enqueue scripts
    yinstagram_wp_enqueue_scripts_load($yinstagram_options);
    
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);
    
    // set default
    $instance['type'] = isset($instance['type']) ? $instance['type'] : 'images';
    $instance['display_images'] = isset($instance['display_images']) ? $instance['display_images'] : 'recent';
    $instance['username_of_user_id'] = isset($instance['username_of_user_id']) ? $instance['username_of_user_id'] : null;
    $instance['custom_size'] = isset($instance['custom_size']) ? $instance['custom_size'] : null;
    $instance['limit'] = isset($instance['limit']) ? $instance['limit'] : '6';
    $instance['order'] = isset($instance['order']) ? $instance['order'] : 'default';
    
    $style = null;
    if ($instance['custom_size'])
      $style = 'style="width:' . $instance['custom_size'] . 'px; height:' . $instance['custom_size'] . 'px"';

    $qtipcontent = null;

    echo $before_widget;
    
    if (!empty($title)) echo $before_title . $title . $after_title;
    
    if (isset($yinstagram_options['access_token']) && isset($yinstagram_options['user'])) {
      $data = null;
      switch($instance['display_images']) {
        case 'tags':
          $data = yinstagram_get_tags_images($yinstagram_options['access_token'], $instance['hashtags'], null, false);
          break;
        default:
          $data = yinstagram_get_own_images($yinstagram_options['access_token'], $instance['display_images'], 1, $instance['username_of_user_id'], false);
      }
      
      if ($instance['order'] == 'shuffle') { shuffle($data); }
      
      switch($instance['type']) {
        case 'profile':
          /*begin profile type*/
          $u_info = yinstagram_get_user_info($yinstagram_options);

          if ( $u_info && !empty($data) ) {
            echo '<div class="yinstagram_profile">';

            $i = $j = 0;
            echo '<div class="header"><ul class="images">';
            foreach ($data as $datum) {
              $i++; $j++;

              echo ($i == 1) ? '<li>' : null;

              echo '<img class="img_pw-' . $datum->id . '" title="' . str_replace('"', "'", (string) $datum->caption->text) . '" src="' . $datum->images->thumbnail->url . '"/>';

              echo ($i == 4) ? '</li>' : null;
              $i = ($i == 4) ? 0 : $i;

              if ($yinstagram_options['tooltip'] == 'on')
                $qtipcontent .= '<div class="qtip_pw-' . $datum->id . ' yinstagram-qtip-content" style="display: none;" username="' . $datum->user->username . '">' . yinstagram_get_qtip_content($datum) . '</div>';

              if ($j == 12) break;
            }

            echo ( ($j != 4) || ($j != 8) || ($j != 12)) ? '</li>' : null;
            echo '</ul><span class="icon"></span></div>';

            echo '<div class="info"><img class="yinstagram_circular" title="' . $u_info->full_name . '" alt="' . $u_info->username . '" src="' . $u_info->profile_picture . '">';
            echo '<p class="fullname" title="' . $u_info->username . '">' . $u_info->full_name . '</p>';
            if ( $u_info->website ) echo '<p class="website"><a href="' . $u_info->website . '" target="_blank">' . preg_replace('#^https?://#', '', $u_info->website) . '</a></p>';
            if ( $u_info->bio ) echo '<p class="bio">' . $u_info->bio . '</p>';

            echo '<ul class="counts"><li>Posts: ' . $u_info->counts->media . '</li>';
            echo '<li>Followers: ' . $u_info->counts->followed_by . '</li>';
            echo '<li>Following: ' . $u_info->counts->follows . '</li></ul>';
            echo '<a href="http://instagram.com/' . $u_info->username . '?ref=badge" class="ig-b- ig-b-v-24">';
            echo '<img src="//badges.instagram.com/static/images/ig-badge-view-24.png" alt="Instagram" />';
            echo '</a>';
            echo '</div></div>';

            if ($yinstagram_options['tooltip'] == 'on') echo $qtipcontent;

          } else {
            echo '<p>Request timed out.</p>';
          }
          /*end of profile type*/
          break;
        default:
          /*begin images type*/
          if (!empty($data)) {
            $i = 0;
            
            if ($yinstagram_options['lightbox'] == 'thickbox') { add_thickbox(); }
            
            echo '<input class="yinstagram-widget-settings" type="hidden" value="' . htmlentities( json_encode( array( 'lightbox' => $yinstagram_options['lightbox'], 'colorbox_effect' => $yinstagram_options['effect'], 'dimensions' => $instance['custom_size'] ) ) ) . '">';
            
            echo ($yinstagram_options['lightbox'] == 'disable') ? '<ul class="yinstagram_grid">' : '<ul class="yinstagram_grid lightbox_on">';
            
            foreach ($data as $datum) {
              $img_src = $datum->images->thumbnail->url;
              if ($instance['size'] == 'low_resolution')
                $img_src = $datum->images->low_resolution->url;
              elseif ($instance['size'] == 'standard_resolution')
                $img_src = $datum->images->standard_resolution->url;
              
              $images[] = array(
                  'id' => $datum->id,
                  'title' => str_replace('"', "'", (string) $datum->caption->text),
                  'src' => $img_src
                );

              if ($yinstagram_options['tooltip'] == 'on')
                $qtipcontent .= '<div class="qtip_iw-' . $datum->id . ' yinstagram-qtip-content" style="display: none;" username="' . $datum->user->username . '">' . yinstagram_get_qtip_content($datum) . '</div>';

              echo '<li>';

              switch($yinstagram_options['lightbox']) {
                case 'thickbox':
                  echo '<a class="yinstagram-lbox thickbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '?TB_iframe=true" rel="gallery-yinstagram">';
                  break;
                case 'colorbox':
                  echo '<a class="yinstagram-lbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '">';
                  break;
                default:
                  echo '<a target="_blank" href="' . $datum->images->standard_resolution->url . '">';
              }

              echo '<span class="load_w-' . $datum->id . '" ' . $style . '></span>';
              echo '</a></li>';

              $i++;
              if ($i == $instance['limit'])
                break;
            }
            echo '</ul>';

            echo '<textarea class="yinstagram-widget-images" style="display: none;">' . json_encode($images) . '</textarea>';

            if ($yinstagram_options['tooltip'] == 'on') echo $qtipcontent;

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
    $instance['username_of_user_id'] = strip_tags($new_instance['username_of_user_id']);
    $instance['hashtags'] = strip_tags($new_instance['hashtags']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['custom_size'] = strip_tags($new_instance['custom_size']);
    $instance['limit'] = strip_tags($new_instance['limit']);
    $instance['order'] = strip_tags($new_instance['order']);
    
    return $instance;
  }

  public function form($instance) {
    $auth = yinstagram_get_options('token');
    $title = isset($instance['title']) ? $instance['title'] : __(null, 'text_domain');
    $type = isset($instance['type']) ? $instance['type'] : 'images';
    $display_images = isset($instance['display_images']) ? $instance['display_images'] : 'recent';
    $username_of_user_id = isset($instance['username_of_user_id']) ? $instance['username_of_user_id'] : null;
    $hashtags = isset($instance['hashtags']) ? $instance['hashtags'] : null;
    $size = isset($instance['size']) ? $instance['size'] : 'thumbnail';
    $custom_size = isset($instance['custom_size']) ? $instance['custom_size'] : null;
    $limit = isset($instance['limit']) ? $instance['limit'] : 6;
    $order = isset($instance['order']) ? $instance['order'] : 'default';
    
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
    <p id="<?php echo $this->get_field_id('recent-container'); ?>" <?php echo ($display_images != 'recent') ? 'style="display: none;"' : null; ?>>
      <label for="<?php echo $this->get_field_id('username_of_user_id'); ?>"><?php _e('Username:'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('username_of_user_id'); ?>" name="<?php echo $this->get_field_name('username_of_user_id'); ?>" type="text" value="<?php echo esc_attr($username_of_user_id); ?>" placeholder="<?php echo isset( $auth['user']->username ) ? $auth['user']->username : null; ?>"/>
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
    <p>
      <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
        <option value="default" <?php echo ($order == 'default') ? 'selected="selected"' : null; ?>>Default&nbsp;</option>
        <option value="shuffle" <?php echo ($order == 'shuffle') ? 'selected="selected"' : null; ?>>Shuffle&nbsp;</option>
      </select>
    </p>
    <?php
  }
}

function yinstagram_get_excerpt($str, $startPos = 0, $maxLength = 40) {
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
