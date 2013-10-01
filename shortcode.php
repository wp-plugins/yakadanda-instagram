<?php
add_shortcode('yinstagram', 'yinstagram_shortcode');
function yinstagram_shortcode($atts) {
  $display_options = (get_option('yinstagram_display_options')) ? get_option('yinstagram_display_options') : array(
      'height' => 300,
      'frame_rate' => 24,
      'speed' => 1,
      'direction' => 'forwards',
      'size' => 'thumbnail',
      'display_social_links' => 0
  );

  $yinstagram = array_merge((array) get_option('yinstagram_settings'), (array) $display_options);
  $auth = get_option('yinstagram_access_token');
  $output = null;

  if (isset($auth['access_token']) && isset($auth['user'])) {
    if ($yinstagram['display_your_images'] != 'hashtag') {
      $data = yinstagram_get_own_images($yinstagram, $auth);
    } else {
      $data = yinstagram_get_tags_images($yinstagram, $auth);
    }
    
    if (!empty($data)) {
      yinstagram_styles($yinstagram);
      $output .= '<input id="yinstagram-shortcode-settings" name="yinstagram-shortcode-settings" type="hidden" value="' . htmlentities( json_encode( array( 'frame_rate' => $yinstagram['frame_rate'], 'speed' => $yinstagram['speed'], 'direction' => $yinstagram['direction'] ) ) ) . '">';

      $output .= '<ul id="yinstagram-scroller">';

      $i = $j = 0;
      
      $images = array();
      
      foreach ( $data as $datum ) {
        $i++; $j++;
        $img_src = $datum->images->thumbnail->url;
        if ($yinstagram['size'] == 'low_resolution')
          $img_src = $datum->images->low_resolution->url;
        elseif ($yinstagram['size'] == 'standard_resolution')
          $img_src = $datum->images->standard_resolution->url;

        $output .= ($i == 1) ? '<li>' : null;

        $output .= '<span class="load_s-' . $datum->id . '"></span>';

        $output .= ($i == 4) ? '</li>' : null;
        $i = ($i == 4) ? 0 : $i;
        
        $images[] = array('id' => $datum->id, 'title' => str_replace('"', "'", (string) $datum->caption->text), 'src' => $img_src);
        
        if ($j == 80)
          break;
      }

      if ($j != 80) $output .= '</li>';

      $output .= '</ul>';
      
      if ( $yinstagram['direction'] == 'backwards' ) $images = array_reverse( $images );
      
      $output .= '<textarea id="yinstagram-shortcode-images" name="yinstagram-shortcode-images" style="display: none;">' . json_encode($images) . '</textarea>';

      if ($yinstagram['display_social_links'] == '1') {
        $output .= '<!-- AddThis Button BEGIN -->';
        $output .= '<div class="addthis_toolbox addthis_default_style ">';
        $output .= '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>';
        $output .= '<a class="addthis_button_tweet"></a>';
        $output .= '<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>';
        $output .= '<!-- <a class="addthis_button_pinterest_pinit"></a>';
        $output .= '<a class="addthis_counter addthis_pill_style"></a> -->';
        $output .= '</div>';
        $output .= '<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50b30c8d0ad640e9"></script>';
        $output .= '<!-- AddThis Button END -->';
      }
    } else {
      $output .= '<p>Request timed out, or no have ' . $yinstagram['display_your_images'] . ' images.</p>';
    }
  } else {
    $output .= '<p>Not Connected.</p>';
  }

  return $output;
}

function yinstagram_fetch_data($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}

function yinstagram_extract_hastags($data) {
  // remove # character and space character
  $output = str_replace("#", "", $data);
  $output = str_replace(" ", "", $output);

  return explode(',', $output);
}

function yinstagram_get_own_images($yinstagram, $auth, $shortcode = true) {
  if ($yinstagram['display_your_images'] == 'feed') {
    //https://api.instagram.com/v1/users/self/feed?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/feed/?access_token=' . $auth['access_token'] . '&count=33');
  } elseif ($yinstagram['display_your_images'] == 'liked') {
    //https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/liked/?access_token=' . $auth['access_token'] . '&count=33');
  } else {
    //https://api.instagram.com/v1/users/3/media/recent/?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $auth['access_token'] . '&count=33');
  }

  $responses = json_decode($responses);
  
  $output = array();
  
  if ( isset($responses->data) ) {
    $output = $responses->data;

    $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;

    if ($shortcode) {
      $i = 0;
      while ($next_url) {
        $responses = yinstagram_fetch_data($next_url);
        $responses = json_decode($responses);
        
        if ( isset($responses->data) ) {
          $output = array_merge($output, $responses->data);

          if ($i == 1)
            break;
          $i++;
          $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
        }
      }
    }
  }

  return $output;
}

function yinstagram_get_tags_images($yinstagram, $auth, $shortcode = true) {
  $tags = yinstagram_extract_hastags($yinstagram['display_the_following_hashtags']);
  $number_of_tags = count($tags);
  $count = round(33 / $number_of_tags);
  $output = array();

  foreach ($tags as $tag) {
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $auth['access_token'] . '&count=' . $count);

    $responses = json_decode($responses);
    
    if ( isset($responses->data) ) {
      $output = array_merge( $output, $responses->data );

      $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;

      if ($shortcode) {
        $i = 0;
        while ($next_url) {
          $responses = yinstagram_fetch_data($next_url);
          $responses = json_decode($responses);

          if ( isset($responses->data) ) {
            $output = array_merge($output, $responses->data);

            if ($i == 1)
              break;
            $i++;
            $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
          }
        }
      }
    }
  }

  return yinstagram_shuffle_assoc( $output );
}

function yinstagram_styles($yinstagram) {
  ?>
  <style type="text/css">
    .vert, .vert .simply-scroll-clip {
      height: <?php echo $yinstagram['height'] . 'px'; ?>;
    }
  </style>
  <?php
}

function yinstagram_shuffle_assoc($list) {
  if (!is_array($list))
    return $list;

  $keys = array_keys($list);
  shuffle($keys);
  $random = array();
  foreach ($keys as $key) {
    $random[] = $list[$key];
  }
  return $random;
}
