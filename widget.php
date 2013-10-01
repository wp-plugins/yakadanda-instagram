<?php
add_action('widgets_init', create_function('', 'register_widget( "yinstagram_widget" );'));
class YInstagram_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(
            'yinstagram', 'Yakadanda Instagram', array('description' => __('Yakadanda Instagram Widget', 'text_domain'))
    );
  }

  public function widget($args, $instance) {
    YInstagram_Widget::colorbox_enqueue_style($instance);
    add_action('wp_enqueue_scripts', 'YInstagram_Widget::colorbox_enqueue_style');

    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? __('Yakadanda Instagram') : $instance['title'], $instance, $this->id_base);

    $instance['limit'] = isset($instance['limit']) ? $instance['limit'] : '6';
    $instance['size'] = isset($instance['size']) ? $instance['size'] : 'thumbnail';
    $instance['custom_size'] = isset($instance['custom_size']) ? $instance['custom_size'] : null;

    $style = null;
    if ($instance['custom_size'])
      $style = 'style="width:' . $instance['custom_size'] . 'px; height:' . $instance['custom_size'] . 'px"';

    echo $before_widget;

    if (!empty($title))
      echo $before_title . $title . $after_title;

    $display_options = (get_option('yinstagram_display_options')) ? get_option('yinstagram_display_options') : array(
        'height' => 300,
        'frame_rate' => 24,
        'speed' => 1,
        'direction' => 'forwards',
        'display_social_links' => 0
    );

    $yinstagram = array_merge((array) get_option('yinstagram_settings'), (array) $display_options);
    $auth = get_option('yinstagram_access_token');

    if (isset($auth['access_token']) && isset($auth['user'])) {
      if ($yinstagram['display_your_images'] != 'hashtag') {
        $data = yinstagram_get_own_images($yinstagram, $auth, false);
      } else {
        $data = yinstagram_get_tags_images($yinstagram, $auth, false);
      }
      if (!empty($data)) {
        $i = 0;
        $colorbox = array('status' => 'off', 'onclick' => 'return false;', 'cursor' => 'default');
        if ($instance['colorbox']) {
          $colorbox = array('status' => 'on', 'onclick' => null, 'cursor' => 'pointer');
          echo '<style type="text/css">';
          echo '.yinstagram_grid li a:hover img {';
          echo 'opacity:0.5; filter:alpha(opacity=50);';
          echo '}';
          echo '</style>';
        }

        echo '<input id="yinstagram-widget-settings" name="yinstagram-widget-settings" type="hidden" value="' . htmlentities( json_encode( array( 'colorbox_status' => $colorbox['status'], 'colorbox_effect' => $instance['effect'], 'dimensions' => $instance['custom_size'] ) ) ) . '">';

        echo '<ul class="yinstagram_grid">';

        foreach ($data as $datum) {
          $img_src = $datum->images->thumbnail->url;
          if ($instance['size'] == 'low_resolution')
            $img_src = $datum->images->low_resolution->url;
          elseif ($instance['size'] == 'standard_resolution')
            $img_src = $datum->images->standard_resolution->url;

          $images[] = array('id' => $datum->id, 'src' => $img_src);

          echo '<li><a class="yinstagram-cbox" style="cursor:' . $colorbox['cursor'] . ';" onclick="' . $colorbox['onclick'] . '" href="' . $datum->images->standard_resolution->url . '" title="' . yinstagram_get_excerpt(str_replace('"', "'", (string) $datum->caption->text)) . '">';

          //echo '<img src="' . $img_src . '" ' . $style . '>';
          echo '<span class="load_w-' . $datum->id . '" ' . $style . '></span>';

          echo '</a></li>';
          $i++;
          if ($i == $instance['limit'])
            break;
        }
        echo '</ul>';
        
        echo '<textarea id="yinstagram-widget-images" name="yinstagram-widget-images" style="display: none;">' . json_encode($images) . '</textarea>';
        
      } else {
        echo '<p>Request timed out, or no have ' . $yinstagram['display_your_images'] . ' images.</p>';
      }
    } else {
      echo '<p>Not Connected.</p>';
    }

    echo $after_widget;
  }

  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['limit'] = strip_tags($new_instance['limit']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['custom_size'] = strip_tags($new_instance['custom_size']);
    $instance['colorbox'] = strip_tags($new_instance['colorbox']);
    $instance['theme'] = strip_tags($new_instance['theme']);
    $instance['effect'] = strip_tags($new_instance['effect']);

    return $instance;
  }

  public function form($instance) {
    $title = __(null, 'text_domain');
    if (isset($instance['title'])) $title = $instance['title'];
    $limit = 6;
    if (isset($instance['limit'])) $limit = $instance['limit'];
    $size = 'thumbnail';
    if (isset($instance['size'])) $size = $instance['size'];
    $custom_size = null;
    if (isset($instance['custom_size'])) $custom_size = $instance['custom_size'];
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
      <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit (max 33):'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Image Size:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
        <option value="thumbnail" <?php echo ($size == 'thumbnail') ? 'selected="selected"' : null; ?>>Thumbnail&nbsp;</option>
        <option value="low_resolution" <?php echo ($size == 'low_resolution') ? 'selected="selected"' : null; ?>>Low Resolution&nbsp;</option>
        <option value="standard_resolution" <?php echo ($size == 'standard_resolution') ? 'selected="selected"' : null; ?>>Standard Resolution&nbsp;</option>
      </select>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('custom_size'); ?>"><?php _e('Custom Image Size (pixel):'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('custom_size'); ?>" name="<?php echo $this->get_field_name('custom_size'); ?>" type="text" value="<?php echo esc_attr($custom_size); ?>" />
    </p>
    <p>
      <input id="<?php echo $this->get_field_id('colorbox'); ?>" name="<?php echo $this->get_field_name('colorbox'); ?>" type="checkbox" class="yinstagram-colorbox" <?php echo empty($colorbox) ? null : 'checked'; ?>/>
      <label for="<?php echo $this->get_field_id('colorbox'); ?>"><?php _e('Colorbox'); ?></label>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>" <?php echo empty($colorbox) ? 'disabled' : null; ?> class="yinstagram-colorbox-options">
        <option value="1" <?php echo ($theme == '1') ? 'selected="selected"' : null; ?>>1&nbsp;</option>
        <option value="2" <?php echo ($theme == '2') ? 'selected="selected"' : null; ?>>2&nbsp;</option>
        <option value="3" <?php echo ($theme == '3') ? 'selected="selected"' : null; ?>>3&nbsp;</option>
        <option value="4" <?php echo ($theme == '4') ? 'selected="selected"' : null; ?>>4&nbsp;</option>
        <option value="5" <?php echo ($theme == '5') ? 'selected="selected"' : null; ?>>5&nbsp;</option>
      </select>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('effect'); ?>"><?php _e('Effect:'); ?></label><br>
      <select id="<?php echo $this->get_field_id('effect'); ?>" name="<?php echo $this->get_field_name('effect'); ?>" <?php echo empty($colorbox) ? 'disabled' : null; ?> class="yinstagram-colorbox-options">
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
